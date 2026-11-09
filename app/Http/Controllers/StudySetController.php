<?php

namespace App\Http\Controllers;

use App\Models\StudySet;
use App\Models\StudyMaterial;
use App\Models\Flashcard;
use App\Models\QuizQuestion;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use ZipArchive;

class StudySetController extends Controller
{
    // How long generated AI results remain cached.
    private const AI_CACHE_DAYS = 30;

    // Maximum number of generated flashcards.
    private const MAX_FLASHCARDS = 100;

    // Maximum number of uploaded files per study set.
    private const MAX_UPLOAD_FILES = 10;

    // Maximum size of one uploaded file: 25 MB.
    private const MAX_FILE_SIZE_KB = 25600;

    // Maximum combined upload size: 128 MB.
    private const MAX_TOTAL_UPLOAD_BYTES = 128 * 1024 * 1024;

    // CHECK FOR DUPLICATE FILE CONTENTS
    private function hasDuplicateFiles(array $files): bool
    {
        $hashes = [];

        foreach ($files as $file) {
            $hash = hash_file(
                'sha256',
                $file->getRealPath()
            );

            if ($hash === false) {
                throw new \RuntimeException(
                    'Could not verify an uploaded file.'
                );
            }

            if (isset($hashes[$hash])) {
                return true;
            }

            $hashes[$hash] = true;
        }

        return false;
    }

    // UPLOAD PAGE
    public function create()
    {
        return view('upload');
    }

    // STORE UPLOADED MATERIALS
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'files' => 'required|array|min:1|max:' . self::MAX_UPLOAD_FILES,
            'files.*' => 'required|file|mimes:pdf,txt,docx,pptx,jpg,jpeg,png,webp|max:' . self::MAX_FILE_SIZE_KB,
        ]);

        $files = $request->file('files');

        // Check the combined size of all uploaded files.
        $totalBytes = array_sum(
            array_map(
                fn ($file) => $file->getSize(),
                $files
            )
        );

        if ($totalBytes > self::MAX_TOTAL_UPLOAD_BYTES) {
            return back()
                ->withErrors([
                    'files' => 'Your selected files exceed the combined 128 MB upload limit. Please remove some files.',
                ])
                ->withInput();
        }

        // Prevent identical files from being uploaded twice.
        if ($this->hasDuplicateFiles($files)) {
            return back()
                ->withErrors([
                    'files' => 'Duplicate files detected. Please upload each study material only once.',
                ])
                ->withInput();
        }

        $storedPaths = [];

        $set = StudySet::create([
            'title' => trim($validated['title']),
        ]);

        try {
            foreach ($files as $index => $file) {
                $material = $this->storeMaterial($set, $file);

                $storedPaths[] = $material['stored_path'];

                if ($index === 0) {
                    $set->update([
                        'source_text' => $material['source_text'],
                        'image_path' => $material['file_path'],
                    ]);
                }
            }
        } catch (\Throwable $e) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            $set->materials()->delete();
            $set->delete();

            throw $e;
        }

        $count = count($files);

        return redirect()
            ->route('sets.show', $set)
            ->with(
                'success',
                "\"{$set->title}\" is ready! Your {$count} study " .
                ($count === 1 ? 'material is' : 'materials are') .
                " ready! ♡"
            );
    }

    // STUDY MATERIAL DETAILS
    public function show(StudySet $set)
    {
        $set->load('materials');

        return view('show', compact('set'));
    }

    // EDIT PAGE
    public function edit(StudySet $set)
    {
        $set->load('materials');

        return view('edit', compact('set'));
    }

    // UPDATE STUDY MATERIAL
    public function update(Request $request, StudySet $set)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'files' => 'nullable|array|max:' . self::MAX_UPLOAD_FILES,
            'files.*' => 'required|file|mimes:pdf,txt,docx,pptx,jpg,jpeg,png,webp|max:' . self::MAX_FILE_SIZE_KB,
            'file' => 'nullable|file|mimes:pdf,txt,docx,pptx,jpg,jpeg,png,webp|max:' . self::MAX_FILE_SIZE_KB,
        ]);

        $uploads = $request->file('files', []);

        if ($request->hasFile('file')) {
            $uploads[] = $request->file('file');
        }

        // Check the maximum number of uploaded files.
        if (count($uploads) > self::MAX_UPLOAD_FILES) {
            return back()
                ->withErrors([
                    'files' => 'You can upload a maximum of 10 files.',
                ])
                ->withInput();
        }

        // Check the combined size of all uploaded files.
        $totalBytes = array_sum(
            array_map(
                fn ($file) => $file->getSize(),
                $uploads
            )
        );

        if ($totalBytes > self::MAX_TOTAL_UPLOAD_BYTES) {
            return back()
                ->withErrors([
                    'files' => 'Your selected files exceed the combined 128 MB upload limit. Please remove some files.',
                ])
                ->withInput();
        }

        // Prevent identical files from being uploaded twice.
        if ($this->hasDuplicateFiles($uploads)) {
            return back()
                ->withErrors([
                    'files' => 'Duplicate files detected. Please upload each study material only once.',
                ])
                ->withInput();
        }

        $data = [
            'title' => $request->title,
        ];

        if (!empty($uploads)) {
            $oldPaths = $set->materials()
                ->whereNotNull('file_path')
                ->pluck('file_path')
                ->toArray();

            if ($set->image_path) {
                $oldPaths[] = $set->image_path;
            }

            $newPaths = [];

            try {
                DB::transaction(function () use (
                    $set,
                    $uploads,
                    &$newPaths,
                    $data
                ) {
                    $set->materials()->delete();
                    $set->flashcards()->delete();
                    $set->questions()->delete();

                    $newMaterials = [];

                    foreach ($uploads as $file) {
                        $material = $this->storeMaterial($set, $file);

                        $newMaterials[] = $material;
                        $newPaths[] = $material['stored_path'];
                    }

                    $first = $newMaterials[0];

                    $set->update([
                        'title' => $data['title'],
                        'source_text' => $first['source_text'],
                        'image_path' => $first['file_path'],
                        'summary' => null,
                    ]);
                });
            } catch (\Throwable $e) {
                foreach ($newPaths as $path) {
                    Storage::disk('public')->delete($path);
                }

                throw $e;
            }

            foreach (array_unique($oldPaths) as $path) {
                Storage::disk('public')->delete($path);
            }
        } else {
            $set->update($data);
        }

        return redirect()
            ->route('sets.show', $set)
            ->with('success', 'Your study set has been updated! ♡');
    }

    // DELETE STUDY MATERIAL
    public function destroy(StudySet $set)
    {
        $paths = $set->materials()
            ->whereNotNull('file_path')
            ->pluck('file_path')
            ->toArray();

        if ($set->image_path) {
            $paths[] = $set->image_path;
        }

        $set->delete();

        foreach (array_unique($paths) as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Study set deleted successfully.');
    }

    // DISPLAY SUMMARY PAGE
    public function summary(StudySet $set)
    {
        return view('summary', compact('set'));
    }

    // SUMMARY SETUP PAGE
    public function summarySetup(StudySet $set)
    {
        return view('summary-setup', compact('set'));
    }

    // PREPARE SUMMARY SETTINGS
    public function prepareSummary(Request $request, StudySet $set)
    {
        $validated = $request->validate([
            'length' => 'required|in:quick,balanced,detailed',
            'format' => 'required|in:organized,key_points,exam_revision',
            'focus' => 'nullable|string|max:2000',
        ]);

        session([
            'summary_generation_' . $set->id => $validated,
        ]);

        return redirect()->route('sets.summary.generating', $set);
    }

    // SUMMARY GENERATING PAGE
    public function summaryGenerating(StudySet $set)
    {
        $settings = session('summary_generation_' . $set->id);

        if (!$settings) {
            return redirect()
                ->route('sets.summary.setup', $set)
                ->with('error', 'Please choose your summary settings first.');
        }

        return view('summary-generating', compact('set', 'settings'));
    }

    // GENERATE SUMMARY
    public function summarize(StudySet $set, ClaudeService $claude)
    {
        $settings = session('summary_generation_' . $set->id);

        if (!$settings) {
            return response()->json([
                'success' => false,
                'message' => 'Your summary settings have expired. Please choose them again.',
            ], 422);
        }

        $options = [
            'length' => $settings['length'],
            'format' => $settings['format'],
            'focus' => trim($settings['focus'] ?? ''),
        ];

        try {
            $cacheKey = $this->aiCacheKey(
                $set,
                'summary',
                $options
            );

            $summary = Cache::remember(
                $cacheKey,
                now()->addDays(self::AI_CACHE_DAYS),
                fn () => $claude->summarize($set, $options)
            );

            if (!is_string($summary) || trim($summary) === '') {
                throw new \Exception(
                    'Shelly could not generate a valid summary. Please try again.'
                );
            }

            $set->update([
                'summary' => $summary,
            ]);

            session()->forget('summary_generation_' . $set->id);

            session()->flash(
                'success',
                'Your study summary is ready! ♡'
            );

            return response()->json([
                'success' => true,
                'redirect' => route('sets.summary', $set),
            ]);

        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Shelly encountered a problem while creating your summary. Please try again.',
            ], 500);
        }
    }

    // FLASHCARDS PAGE
    public function flashcards(Request $request, StudySet $set)
    {
        $allCards = $set->flashcards()->get();

        // Count cards marked as known.
        $knownCount = $allCards
            ->where('reviewed', true)
            ->where('known', true)
            ->count();

        // Count cards marked for further study.
        $reviewCount = $allCards
            ->where('reviewed', true)
            ->where('known', false)
            ->count();

        // Count all cards that have been reviewed.
        $reviewedCount = $allCards
            ->where('reviewed', true)
            ->count();

        $reviewMode = $request->boolean('review');

        $query = $set->flashcards()
            ->orderBy('id');

        // Review mode displays only difficult cards.
        if ($reviewMode) {
            $query->where('reviewed', true)
                ->where('known', false);
        }

        $cards = $query->get()->map(function ($card) use ($set) {
            return [
                'id' => $card->id,
                'front' => $card->front,
                'back' => $card->back,
                'known' => (bool) $card->known,
                'reviewed' => (bool) $card->reviewed,
                'markUrl' => route(
                    'sets.flashcards.mark',
                    [$set, $card]
                ),
            ];
        })->values();

        return view('flashcards', compact(
            'set',
            'cards',
            'knownCount',
            'reviewCount',
            'reviewedCount',
            'reviewMode'
        ));
    }

    // GENERATE FLASHCARDS
    public function makeFlashcards(StudySet $set, ClaudeService $claude)
    {
        try {
            $cacheKey = $this->aiCacheKey(
                $set,
                'flashcards_v3'
            );

            $cards = Cache::remember(
                $cacheKey,
                now()->addDays(self::AI_CACHE_DAYS),
                fn () => $claude->flashcards($set)
            );

            if (!is_array($cards)) {
                throw new \Exception(
                    'Shelly could not generate valid flashcards. Please try again.'
                );
            }

            // Enforce the maximum of 100 cards.
            $cards = array_slice(
                $cards,
                0,
                self::MAX_FLASHCARDS
            );

            $validCards = [];

            foreach ($cards as $card) {
                if (
                    !is_array($card) ||
                    !isset($card['front'], $card['back']) ||
                    !is_string($card['front']) ||
                    !is_string($card['back']) ||
                    trim($card['front']) === '' ||
                    trim($card['back']) === ''
                ) {
                    continue;
                }

                $validCards[] = [
                    'study_set_id' => $set->id,
                    'front' => trim($card['front']),
                    'back' => trim($card['back']),
                ];
            }

            if (empty($validCards)) {
                throw new \Exception(
                    'No valid flashcards were generated.'
                );
            }

            // Preserve existing progress for matching flashcards.
            DB::transaction(function () use ($set, $validCards) {

                $existingCards = $set->flashcards()
                    ->lockForUpdate()
                    ->get();

                $usedIds = [];

                foreach ($validCards as $card) {

                    $front = $this->normalizeMatchingText(
                        $card['front']
                    );

                    $back = $this->normalizeMatchingText(
                        $card['back']
                    );

                    $existing = $existingCards->first(
                        function ($existing) use (
                            $front,
                            $back,
                            $usedIds
                        ) {
                            return !in_array(
                                $existing->id,
                                $usedIds,
                                true
                            )
                            && $this->normalizeMatchingText(
                                $existing->front
                            ) === $front
                            && $this->normalizeMatchingText(
                                $existing->back
                            ) === $back;
                        }
                    );

                    if ($existing) {
                        $existing->update([
                            'front' => $card['front'],
                            'back' => $card['back'],
                        ]);

                        $usedIds[] = $existing->id;

                    } else {
                        $newCard = Flashcard::create([
                            ...$card,
                            'known' => false,
                            'reviewed' => false,
                        ]);

                        $usedIds[] = $newCard->id;
                    }
                }

                // Remove only cards that are no longer generated.
                $set->flashcards()
                    ->whereNotIn('id', $usedIds)
                    ->delete();
            });

        } catch (\Throwable $e) {
            return redirect()
                ->route('sets.flashcards', $set)
                ->withErrors(['ai' => $e->getMessage()]);
        }

        return redirect()
            ->route('sets.flashcards', $set)
            ->with('success', 'Your flashcards are ready! ♡');
    }

    // MARK FLASHCARD
    public function markCard(
        Request $request,
        StudySet $set,
        Flashcard $flashcard
    ) {
        $validated = $request->validate([
            'known' => 'required|boolean',
        ]);

        // Make sure this card belongs to the requested study set.
        $card = $set->flashcards()
            ->whereKey($flashcard->id)
            ->firstOrFail();

        $card->update([
            'known' => $validated['known'],
            'reviewed' => true,
        ]);

        return response()->json([
            'ok' => true,
            'known' => (bool) $card->known,
            'reviewed' => (bool) $card->reviewed,
        ]);
    }

    // QUIZ SETTINGS PAGE
    public function quiz(StudySet $set)
    {
        return view('quiz', compact('set'));
    }

    // QUIZ GAMEPLAY PAGE
    public function quizPlay(Request $request, StudySet $set)
    {
        $missed = $request->boolean('missed');

        $query = $set->questions();

        if ($missed) {
            $query->where('missed', true);
        }

        $questions = $query->get()->map(fn ($q) => [
            'id' => $q->id,
            'question' => $q->question,
            'type' => $q->type ?? 'multiple_choice',
            'options' => $q->options,
            'correct_index' => $q->correct_index,
            'explanation' => $q->explanation,
        ])->values();

        return view('quiz-play', compact(
            'set',
            'questions',
            'missed'
        ));
    }

    // PREPARE QUIZ SETTINGS
    public function prepareQuiz(Request $request, StudySet $set)
    {
        $validated = $request->validate([
            'question_count' => 'required|integer|min:1|max:100',
            'quiz_type' => 'required|in:true_false,multiple_choice,mixed',
            'custom_instructions' => 'nullable|string|max:2000',
        ]);

        session([
            'quiz_generation_' . $set->id => $validated,
        ]);

        return redirect()->route('sets.quiz.generating', $set);
    }

    // QUIZ GENERATING PAGE
    public function quizGenerating(StudySet $set)
    {
        $settings = session('quiz_generation_' . $set->id);

        if (!$settings) {
            return redirect()
                ->route('sets.quiz', $set)
                ->with('error', 'Please choose your quiz settings first.');
        }

        return view('quiz-generating', compact('set', 'settings'));
    }

    // GENERATE QUIZ
    public function makeQuiz(
        Request $request,
        StudySet $set,
        ClaudeService $claude
    ) {
        $validated = $request->validate([
            'question_count' => 'required|integer|min:1|max:100',
            'quiz_type' => 'required|in:true_false,multiple_choice,mixed',
            'custom_instructions' => 'nullable|string|max:2000',
        ]);

        $count = (int) $validated['question_count'];
        $type = $validated['quiz_type'];
        $instructions = $validated['custom_instructions'] ?? '';

        try {
            $cacheKey = $this->aiCacheKey(
                $set,
                'quiz',
                [
                    'count' => $count,
                    'type' => $type,
                    'instructions' => trim($instructions),
                ]
            );

            $items = Cache::remember(
                $cacheKey,
                now()->addDays(self::AI_CACHE_DAYS),
                fn () => $claude->quiz(
                    $set,
                    $count,
                    $type,
                    $instructions
                )
            );

            if (!is_array($items) || count($items) !== $count) {
                throw new \Exception(
                    'Shelly could not generate the requested number of questions. Please try again.'
                );
            }

            $validQuestions = [];

            foreach ($items as $item) {
                $questionType = $item['type'] ?? 'multiple_choice';

                if (!in_array(
                    $questionType,
                    ['true_false', 'multiple_choice'],
                    true
                )) {
                    throw new \Exception(
                        'Shelly generated an unsupported question type. Please try again.'
                    );
                }

                if (
                    $type !== 'mixed' &&
                    $questionType !== $type
                ) {
                    throw new \Exception(
                        'Shelly generated a question in the wrong format. Please try again.'
                    );
                }

                $expectedOptions = $questionType === 'true_false' ? 2 : 4;

                if (
                    empty($item['question']) ||
                    empty($item['explanation']) ||
                    !isset($item['options']) ||
                    !is_array($item['options']) ||
                    count($item['options']) !== $expectedOptions ||
                    !isset($item['correct_index']) ||
                    !is_numeric($item['correct_index']) ||
                    (float) $item['correct_index'] !==
                        (float) (int) $item['correct_index'] ||
                    (int) $item['correct_index'] < 0 ||
                    (int) $item['correct_index'] >= $expectedOptions
                ) {
                    throw new \Exception(
                        'Shelly generated an invalid question. Please try again.'
                    );
                }

                $options = array_values(array_map(
                    fn ($option) => trim((string) $option),
                    $item['options']
                ));

                if (in_array('', $options, true)) {
                    throw new \Exception(
                        'Shelly generated an incomplete question. Please try again.'
                    );
                }

                $validQuestions[] = [
                    'study_set_id' => $set->id,
                    'question' => trim($item['question']),
                    'type' => $questionType,
                    'options' => $options,
                    'correct_index' => (int) $item['correct_index'],
                    'explanation' => trim($item['explanation']),
                ];
            }

            // Preserve existing progress for matching quiz questions.
            DB::transaction(function () use ($set, $validQuestions) {

                $existingQuestions = $set->questions()
                    ->lockForUpdate()
                    ->get();

                $usedIds = [];

                foreach ($validQuestions as $question) {

                    $normalizedQuestion = $this->normalizeMatchingText(
                        $question['question']
                    );

                    $existing = $existingQuestions->first(
                        function ($existing) use (
                            $normalizedQuestion,
                            $question,
                            $usedIds
                        ) {
                            return !in_array(
                                $existing->id,
                                $usedIds,
                                true
                            )
                            && $existing->type === $question['type']
                            && $this->normalizeMatchingText(
                                $existing->question
                            ) === $normalizedQuestion;
                        }
                    );

                    if ($existing) {
                        $existing->update([
                            'question' => $question['question'],
                            'type' => $question['type'],
                            'options' => $question['options'],
                            'correct_index' => $question['correct_index'],
                            'explanation' => $question['explanation'],
                        ]);

                        $usedIds[] = $existing->id;

                    } else {
                        $newQuestion = QuizQuestion::create($question);

                        $usedIds[] = $newQuestion->id;
                    }
                }

                // Remove only questions that are no longer generated.
                $set->questions()
                    ->whereNotIn('id', $usedIds)
                    ->delete();
            });

            session()->forget('quiz_generation_' . $set->id);

            session()->flash('success', 'Your new quiz is ready! ♡');

            return response()->json([
                'success' => true,
                'message' => 'Your quiz is ready!',
                'redirect' => route('sets.quiz.play', $set),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // NORMALIZE TEXT WHEN MATCHING EXISTING GENERATED CONTENT
    private function normalizeMatchingText(string $text): string
    {
        $text = preg_replace('/\s+/', ' ', trim($text));

        return mb_strtolower($text ?? '', 'UTF-8');
    }

    // CREATE A CACHE KEY BASED ON THE STUDY MATERIALS AND SETTINGS
    private function aiCacheKey(
        StudySet $set,
        string $feature,
        array $options = []
    ): string {
        $materials = $set->materials()
            ->orderBy('id')
            ->get([
                'id',
                'file_path',
                'source_text',
                'mime_type',
                'file_size',
            ]);

        $materialData = $materials->map(fn ($material) => [
            'id' => $material->id,
            'file_path' => $material->file_path,
            'source_text' => $material->source_text,
            'mime_type' => $material->mime_type,
            'file_size' => $material->file_size,
        ])->toArray();

        if ($materials->isEmpty()) {
            $materialData = [
                'source_text' => $set->source_text,
                'image_path' => $set->image_path,
            ];
        }

        $contentHash = hash(
            'sha256',
            json_encode($materialData, JSON_UNESCAPED_UNICODE)
        );

        $optionsHash = hash(
            'sha256',
            json_encode($options, JSON_UNESCAPED_UNICODE)
        );

        return "shelly:ai:{$feature}:set:{$set->id}:{$contentHash}:{$optionsHash}";
    }

    // STORE ONE UPLOADED FILE
    private function storeMaterial(StudySet $set, $file): array
    {
        $ext = strtolower($file->getClientOriginalExtension());

        $isImage = in_array(
            $ext,
            ['jpg', 'jpeg', 'png', 'webp'],
            true
        );

        $filePath = $file->store('uploads', 'public');

        try {
            $text = null;

            if (!$isImage) {
                $text = $this->extractText(
                    $file->getRealPath(),
                    $ext
                );

                $text = mb_convert_encoding(
                    $text,
                    'UTF-8',
                    'UTF-8'
                );
            }

            $material = $set->materials()->create([
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $filePath,
                'source_text' => $text,
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            return [
                'material' => $material,
                'source_text' => $text,
                'file_path' => $isImage ? $filePath : null,
                'stored_path' => $filePath,
            ];

        } catch (\Throwable $e) {
            Storage::disk('public')->delete($filePath);

            throw $e;
        }
    }

    // EXTRACT TEXT FROM FILE
    private function extractText(string $path, string $ext): string
    {
        // PDF TEXT EXTRACTION
        if ($ext === 'pdf') {
            return (new Parser())
                ->parseFile($path)
                ->getText();
        }

        // DOCX TEXT EXTRACTION
        if ($ext === 'docx') {
            $zip = new ZipArchive();

            if ($zip->open($path) !== true) {
                throw new \Exception(
                    'Could not open the DOCX file.'
                );
            }

            try {
                $xml = $zip->getFromName('word/document.xml');

                if ($xml === false) {
                    throw new \Exception(
                        'Could not find readable document content in the DOCX file.'
                    );
                }

                $document = new \DOMDocument();

                $loaded = $document->loadXML(
                    $xml,
                    LIBXML_NONET | LIBXML_NOBLANKS
                );

                if (!$loaded) {
                    throw new \Exception(
                        'Could not read the DOCX document content.'
                    );
                }

                $xpath = new \DOMXPath($document);

                $paragraphs = $xpath->query(
                    '//*[local-name()="p"]'
                );

                $lines = [];

                foreach ($paragraphs as $paragraph) {
                    $textNodes = $xpath->query(
                        './/*[local-name()="t"]',
                        $paragraph
                    );

                    $parts = [];

                    foreach ($textNodes as $textNode) {
                        $parts[] = $textNode->textContent;
                    }

                    $line = trim(implode('', $parts));

                    if ($line !== '') {
                        $lines[] = $line;
                    }
                }

                return implode("\n", $lines);

            } finally {
                $zip->close();
            }
        }

        // POWERPOINT PPTX TEXT EXTRACTION
        if ($ext === 'pptx') {
            $zip = new ZipArchive();

            if ($zip->open($path) !== true) {
                throw new \Exception(
                    'Could not open the PowerPoint file.'
                );
            }

            try {
                $slides = [];

                // Find all PowerPoint slide XML files.
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $name = $zip->getNameIndex($i);

                    if (
                        preg_match(
                            '/^ppt\/slides\/slide\d+\.xml$/',
                            $name
                        )
                    ) {
                        $slides[] = $name;
                    }
                }

                // Keep slides in their numerical order.
                natsort($slides);

                $slideTexts = [];

                foreach ($slides as $slideFile) {
                    $xml = $zip->getFromName($slideFile);

                    if ($xml === false) {
                        continue;
                    }

                    $document = new \DOMDocument();

                    $loaded = $document->loadXML(
                        $xml,
                        LIBXML_NONET | LIBXML_NOBLANKS
                    );

                    if (!$loaded) {
                        continue;
                    }

                    $xpath = new \DOMXPath($document);

                    // Extract text from PowerPoint paragraphs.
                    $paragraphs = $xpath->query(
                        '//*[local-name()="p"]'
                    );

                    $slideParagraphs = [];

                    foreach ($paragraphs as $paragraph) {
                        $textNodes = $xpath->query(
                            './/*[local-name()="t"]',
                            $paragraph
                        );

                        $parts = [];

                        foreach ($textNodes as $textNode) {
                            $parts[] = $textNode->textContent;
                        }

                        $paragraphText = trim(
                            implode('', $parts)
                        );

                        if ($paragraphText !== '') {
                            $slideParagraphs[] = $paragraphText;
                        }
                    }

                    if (!empty($slideParagraphs)) {
                        $slideTexts[] = implode(
                            "\n",
                            $slideParagraphs
                        );
                    }
                }

                if (empty($slideTexts)) {
                    throw new \Exception(
                        'No readable text was found in the PowerPoint file.'
                    );
                }

                return implode(
                    "\n\n",
                    $slideTexts
                );

            } finally {
                $zip->close();
            }
        }

        // TXT TEXT EXTRACTION
        if ($ext === 'txt') {
            return file_get_contents($path) ?: '';
        }

        throw new \Exception(
            'This file format is not supported for text extraction.'
        );
    }
}
