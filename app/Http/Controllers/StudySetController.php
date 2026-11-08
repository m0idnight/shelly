<?php

namespace App\Http\Controllers;

use App\Models\StudySet;
use App\Models\Flashcard;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;
use ZipArchive;
use App\Services\ClaudeService;

class StudySetController extends Controller
{
    public function create()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|extensions:pdf,txt,docx,jpg,jpeg,png,webp',
        ]);

        $file = $request->file('file');
        $ext = strtolower($file->getClientOriginalExtension());
        $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $text = null;
        $imagePath = null;

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $imagePath = $file->store('uploads', 'public');
        } else {
            $text = $this->extractText($file->getRealPath(), $ext);
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }

        $set = StudySet::create([
            'title' => $title,
            'source_text' => $text,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('sets.show', $set);
    }

    public function show(StudySet $set)
    {
        return view('show', compact('set'));
    }

    // EDIT
    public function edit(StudySet $set)
    {
        return view('edit', compact('set'));
    }

    // UPDATE
    public function update(Request $request, StudySet $set)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'nullable|file|extensions:pdf,txt,docx,jpg,jpeg,png,webp',
        ]);

        $oldImage = $set->image_path;
        $newImage = null;

        $data = [
            'title' => $request->title,
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $ext = strtolower($file->getClientOriginalExtension());

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $newImage = $file->store('uploads', 'public');

                $data['image_path'] = $newImage;
                $data['source_text'] = null;
            } else {
                $text = $this->extractText($file->getRealPath(), $ext);

                $data['source_text'] = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
                $data['image_path'] = null;
            }

            // Generated content belongs to the old material.
            $data['summary'] = null;
        }

        try {
            DB::transaction(function () use ($set, $data, $request) {
                $set->update($data);

                if ($request->hasFile('file')) {
                    $set->flashcards()->delete();
                    $set->questions()->delete();
                }
            });
        } catch (\Throwable $e) {
            if ($newImage) {
                Storage::disk('public')->delete($newImage);
            }

            throw $e;
        }

        if ($request->hasFile('file') && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()
            ->route('sets.show', $set)
            ->with('success', 'Your study set has been updated! ♡');
    }

    // DELETE
    public function destroy(StudySet $set)
    {
        $imagePath = $set->image_path;

        // Flashcards and questions cascade automatically.
        // Study logs are preserved by the database's nullOnDelete rule.
        $set->delete();

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Study set deleted successfully.');
    }

    // SUMMARY
    public function summarize(StudySet $set, ClaudeService $claude)
    {
        try {
            $set->update([
                'summary' => $claude->summarize($set),
            ]);
        } catch (\Throwable $e) {
            return redirect()
                ->route('sets.show', $set)
                ->withErrors(['ai' => $e->getMessage()]);
        }

        return redirect()->route('sets.show', $set);
    }

    // FLASHCARDS
    public function flashcards(StudySet $set)
    {
        return view('flashcards', compact('set'));
    }

    public function makeFlashcards(StudySet $set, ClaudeService $claude)
    {
        try {
            $cards = $claude->flashcards($set);

            $set->flashcards()->delete();

            foreach ($cards as $card) {
                if (!empty($card['front']) && !empty($card['back'])) {
                    Flashcard::create([
                        'study_set_id' => $set->id,
                        'front' => $card['front'],
                        'back' => $card['back'],
                    ]);
                }
            }
        } catch (\Throwable $e) {
            return redirect()
                ->route('sets.flashcards', $set)
                ->withErrors(['ai' => $e->getMessage()]);
        }

        return redirect()->route('sets.flashcards', $set);
    }

    public function markCard(Request $request, Flashcard $flashcard)
    {
        $flashcard->update([
            'known' => $request->boolean('known'),
        ]);

        return response()->json(['ok' => true]);
    }

    // QUIZ
    public function quiz(Request $request, StudySet $set)
    {
        $missed = $request->boolean('missed');

        $query = $set->questions();

        if ($missed) {
            $query->where('missed', true);
        }

        $questions = $query->get()->map(fn ($q) => [
            'id' => $q->id,
            'question' => $q->question,
            'options' => $q->options,
            'correct_index' => $q->correct_index,
            'explanation' => $q->explanation,
        ])->values();

        return view('quiz', compact('set', 'questions', 'missed'));
    }

    public function makeQuiz(StudySet $set, ClaudeService $claude)
    {
        try {
            $items = $claude->quiz($set);

            $set->questions()->delete();

            foreach ($items as $item) {
                if (
                    !empty($item['question']) &&
                    isset($item['options']) &&
                    is_array($item['options']) &&
                    count($item['options']) === 4 &&
                    isset($item['correct_index']) &&
                    $item['correct_index'] >= 0 &&
                    $item['correct_index'] <= 3 &&
                    !empty($item['explanation'])
                ) {
                    QuizQuestion::create([
                        'study_set_id' => $set->id,
                        'question' => $item['question'],
                        'options' => array_values($item['options']),
                        'correct_index' => (int) $item['correct_index'],
                        'explanation' => $item['explanation'],
                    ]);
                }
            }
        } catch (\Throwable $e) {
            return redirect()
                ->route('sets.quiz', $set)
                ->withErrors(['ai' => $e->getMessage()]);
        }

        return redirect()->route('sets.quiz', $set);
    }

    private function extractText(string $path, string $ext): string
    {
        if ($ext === 'pdf') {
            return (new Parser())->parseFile($path)->getText();
        }

        if ($ext === 'docx') {
            $zip = new ZipArchive();

            if ($zip->open($path) === true) {
                $xml = $zip->getFromName('word/document.xml');
                $zip->close();

                $xml = str_replace('</w:p>', "\n", $xml);

                return trim(strip_tags($xml));
            }

            return '';
        }

        return file_get_contents($path);
    }
}
