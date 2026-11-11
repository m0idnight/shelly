<?php

namespace App\Services;

use App\Models\StudySet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;

class ClaudeService
{
    /**
     * Maximum text sent to the AI in one request.
     */
    private const CHUNK_SIZE = 16000;

    /**
     * Maximum number of generated flashcards or quiz questions.
     */
    private const MAX_GENERATED_ITEMS = 100;

    /**
     * Send a request to Gemini first, then use Cloudflare as fallback.
     */
    public function ask(string $system, array $parts, bool $json = false): string
    {
        $model = env('GEMINI_MODEL', 'gemini-3.8-flash');
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return $this->askCloudflare($system, $parts, $json);
        }

        $body = [
            'system_instruction' => [
                'parts' => [
                    ['text' => $system],
                ],
            ],
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => $parts,
                ],
            ],
        ];

        if ($json) {
            $body['generationConfig'] = [
                'responseMimeType' => 'application/json',
            ];
        }

        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent";

        $maximumAttempts = 2;

        $retryableStatuses = [
            408, 500, 502, 503, 504, 529,
        ];

        $lastError = null;

        for ($attempt = 1; $attempt <= $maximumAttempts; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $apiKey,
                ])->timeout(30)->post($url, $body);

                if (!$response->failed()) {
                    $answer = $response->json(
                        'candidates.0.content.parts.0.text'
                    );

                    if ($answer) {
                        return $answer;
                    }

                    $lastError = 'Gemini returned an empty response.';
                    break;
                }

                $status = $response->status();

                $lastError = $response->json('error.message')
                    ?? 'Gemini request failed.';

                if ($status === 429) {
                    break;
                }

                if (!in_array($status, $retryableStatuses, true)) {
                    throw new \Exception(
                        'Gemini API error: ' . $lastError
                    );
                }

            } catch (ConnectionException $e) {
                $lastError = 'Could not connect to Gemini.';
            }

            if ($attempt < $maximumAttempts) {
                sleep(2 ** $attempt);
            }
        }

        try {
            return $this->askCloudflare($system, $parts, $json);

        } catch (\Throwable $e) {
            throw new \Exception(
                'Both AI providers are currently unavailable. '
                . 'Gemini: ' . ($lastError ?? 'Unavailable.')
                . ' Cloudflare: ' . $e->getMessage()
            );
        }
    }

    /**
     * Send a request to Cloudflare Workers AI.
     */
    private function askCloudflare(
        string $system,
        array $parts,
        bool $json = false
    ): string {
        $accountId = env('CLOUDFLARE_ACCOUNT_ID');
        $apiToken = env('CLOUDFLARE_API_TOKEN');

        $model = env(
            'CLOUDFLARE_AI_MODEL',
            '@cf/meta/llama-3.1-8b-instruct'
        );

        if (!$accountId || !$apiToken) {
            throw new \Exception(
                'Cloudflare is not configured. Please check your .env file.'
            );
        }

        foreach ($parts as $part) {
            if (isset($part['inline_data'])) {
                throw new \Exception(
                    'This study set contains images. '
                    . 'The Cloudflare fallback currently supports text only. '
                    . 'Please try again when Gemini is available.'
                );
            }
        }

        $userText = [];

        foreach ($parts as $part) {
            if (isset($part['text'])) {
                $userText[] = $part['text'];
            }
        }

        $prompt = implode("\n\n", $userText);

        if ($json) {
            $prompt .= "\n\nIMPORTANT: Return valid JSON only. "
                . "Do not include Markdown, code fences, or extra commentary.";
        }

        $url = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/{$model}";

        $body = [
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $system,
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'max_tokens' => 4096,
        ];

        try {
            $response = Http::withToken($apiToken)
                ->acceptJson()
                ->timeout(180)
                ->post($url, $body);

            if ($response->failed()) {
                $error = $response->json('errors.0.message')
                    ?? 'Cloudflare AI request failed.';

                throw new \Exception($error);
            }

            $data = $response->json();

            $answer = $data['result']['choices'][0]['message']['content']
                ?? $data['result']['response']
                ?? null;

            if (is_array($answer) && $json) {
                $answer = json_encode(
                    $answer,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
                );
            }

            if (!is_string($answer) || trim($answer) === '') {
                throw new \Exception(
                    'Cloudflare returned an empty or invalid response. '
                    . 'HTTP Status: ' . $response->status()
                );
            }

            return trim($answer);

        } catch (ConnectionException $e) {
            throw new \Exception(
                'Could not connect to Cloudflare Workers AI.'
            );
        }
    }


/**
 * Collect all text and image materials from a study set.
 *
 * Each text material is divided into smaller sections.
 * Older study sets using source_text and image_path remain supported.
 */
private function sourceChunks(StudySet $set): array
{
    $chunks = [];

    // Use the configured materials storage disk.
    $disk = Storage::disk(config('filesystems.materials_disk'));

    $materials = $set->materials()->get();

    if ($materials->isNotEmpty()) {
        foreach ($materials as $material) {
            $filename = $material->original_name ?: 'Study material';

            if (!empty($material->source_text)) {
                $textChunks = $this->splitText(
                    "FILE: {$filename}\n\n" . $material->source_text
                );

                foreach ($textChunks as $text) {
                    $chunks[] = [
                        'parts' => [
                            ['text' => $text],
                        ],
                    ];
                }
            }

            // Read uploaded images from local or cloud storage.
            if (
                !empty($material->file_path) &&
                $disk->exists($material->file_path) &&
                !empty($material->mime_type) &&
                str_starts_with($material->mime_type, 'image/')
            ) {
                $imageData = $disk->get($material->file_path);

                if ($imageData !== false) {
                    $chunks[] = [
                        'parts' => [
                            [
                                'text' => 'Image file: ' . $filename,
                            ],
                            [
                                'inline_data' => [
                                    'mime_type' => $material->mime_type,
                                    'data' => base64_encode($imageData),
                                ],
                            ],
                        ],
                    ];
                }
            }
        }

    } else {
        // Backward compatibility for older study sets.
        if (!empty($set->source_text)) {
            foreach ($this->splitText($set->source_text) as $text) {
                $chunks[] = [
                    'parts' => [
                        ['text' => $text],
                    ],
                ];
            }
        }

        // Support older study sets containing an image.
        if (
            !empty($set->image_path) &&
            $disk->exists($set->image_path)
        ) {
            $ext = strtolower(
                pathinfo($set->image_path, PATHINFO_EXTENSION)
            );

            $types = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'webp' => 'image/webp',
            ];

            if (isset($types[$ext])) {
                $imageData = $disk->get($set->image_path);

                if ($imageData !== false) {
                    $chunks[] = [
                        'parts' => [
                            [
                                'inline_data' => [
                                    'mime_type' => $types[$ext],
                                    'data' => base64_encode($imageData),
                                ],
                            ],
                        ],
                    ];
                }
            }
        }
    }

    if (empty($chunks)) {
        throw new \Exception(
            'This study set does not contain readable materials. '
            . 'Please upload your files again.'
        );
    }

    return $chunks;
}


    /**
     * Split text into manageable sections without intentionally
     * discarding the remainder of the source.
     */
    private function splitText(string $text): array
    {
        $text = trim($text);

        if ($text === '') {
            return [];
        }

        $chunks = [];

        $length = mb_strlen($text);

        for ($offset = 0; $offset < $length; $offset += self::CHUNK_SIZE) {
            $chunk = mb_substr(
                $text,
                $offset,
                self::CHUNK_SIZE
            );

            // Try to end at a paragraph or sentence boundary.
            if ($offset + self::CHUNK_SIZE < $length) {
                $lastBreak = mb_strrpos($chunk, "\n\n");

                if ($lastBreak === false || $lastBreak < self::CHUNK_SIZE * 0.6) {
                    $lastBreak = mb_strrpos($chunk, '. ');

                    if ($lastBreak !== false) {
                        $lastBreak++;
                    }
                }

                if ($lastBreak !== false && $lastBreak > 0) {
                    $chunk = mb_substr($chunk, 0, $lastBreak);
                }
            }

            $chunk = trim($chunk);

            if ($chunk !== '') {
                $chunks[] = $chunk;
            }

            $consumed = mb_strlen($chunk);

            if ($consumed === 0) {
                break;
            }

            // Advance by the actual consumed length.
            $offset += $consumed - self::CHUNK_SIZE;
        }

        return $chunks;
    }

    /**
     * Attach an instruction to one source chunk.
     */
    private function chunkParts(array $chunk, string $instruction): array
    {
        $parts = $chunk['parts'];

        $parts[] = [
            'text' => $instruction,
        ];

        return $parts;
    }

    /**
 * Distribute generated items across the full range of source sections.
 * When there are fewer items than sections, spread them across the document.
 */
private function distributeCount(int $total, int $numberOfChunks): array
{
    $numberOfChunks = max(1, $numberOfChunks);

    $distribution = array_fill(0, $numberOfChunks, 0);

    $total = min($total, $numberOfChunks * $total);

    if ($total >= $numberOfChunks) {
        $base = intdiv($total, $numberOfChunks);
        $remainder = $total % $numberOfChunks;

        for ($i = 0; $i < $numberOfChunks; $i++) {
            $distribution[$i] = $base + ($i < $remainder ? 1 : 0);
        }

        return $distribution;
    }

    // Spread a smaller number of questions across the document.
    for ($i = 0; $i < $total; $i++) {
        $index = (int) floor(
            ($i + 0.5) * $numberOfChunks / $total
        );

        $distribution[$index]++;
    }

    return $distribution;
}

    /**
     * Generate study notes from every source section, then combine them.
     */

/**
 * Generate study notes from all source materials.
 * Preserve original extracted text whenever possible.
 */
public function summarize(StudySet $set, array $options = []): string
{
    $length = $options['length'] ?? 'balanced';
    $format = $options['format'] ?? 'organized';
    $focus = trim((string) ($options['focus'] ?? ''));

    $lengthInstructions = [
        'quick' => 'Keep the notes concise. Include the most important concepts, definitions, and facts.',
        'balanced' => 'Create moderately detailed notes. Explain important concepts clearly without unnecessary repetition.',
        'detailed' => 'Create comprehensive notes. Explain important concepts thoroughly and include relevant details.',
    ];

    $formatInstructions = [
        'organized' => 'Use clear headings, subheadings, short explanations, and bullet points.',
        'key_points' => 'Prioritize essential information using concise bullet points and short explanations.',
        'exam_revision' => 'Create exam-focused revision notes highlighting definitions, concepts, comparisons, and facts.',
    ];

    $lengthInstruction = $lengthInstructions[$length]
        ?? $lengthInstructions['balanced'];

    $formatInstruction = $formatInstructions[$format]
        ?? $formatInstructions['organized'];

    $system = "You are Shelly, a friendly study helper. "
        . "Create accurate study notes using ONLY the supplied study materials. "
        . "Never invent facts or introduce outside knowledge.";

    $chunks = $this->sourceChunks($set);

    // Calculate the total amount of extracted text.
    $totalCharacters = 0;

    foreach ($chunks as $chunk) {
        foreach ($chunk['parts'] as $part) {
            if (isset($part['text'])) {
                $totalCharacters += mb_strlen($part['text']);
            }
        }
    }

    // Small study sets are processed together to avoid losing
    // information when separate AI summaries are combined.
    if ($totalCharacters <= 30000) {
        $parts = [];

        foreach ($chunks as $index => $chunk) {
            $parts[] = [
                'text' => "SOURCE MATERIAL " . ($index + 1) . ":\n"
                    . implode(
                        "\n\n",
                        array_filter(
                            array_map(
                                fn ($part) => $part['text'] ?? '',
                                $chunk['parts']
                            )
                        )
                    ),
            ];

            // Preserve image parts for Gemini.
            foreach ($chunk['parts'] as $part) {
                if (isset($part['inline_data'])) {
                    $parts[] = $part;
                }
            }
        }

        $parts[] = [
            'text' =>
                "Create one complete set of study notes from ALL source materials above.\n\n"
                . "IMPORTANT:\n"
                . "- Read every source material before writing.\n"
                . "- Preserve meaningful information from every file.\n"
                . "- Do not treat short titles, slide text, or brief statements as empty content.\n"
                . "- Keep each file's unique information.\n"
                . "- Do not invent missing explanations or facts.\n\n"
                . "LENGTH:\n{$lengthInstruction}\n\n"
                . "FORMAT:\n{$formatInstruction}\n\n"
                . "Additional student instructions:\n"
                . ($focus !== '' ? $focus : 'Use your judgment to organize the material clearly.'),
        ];

        return $this->ask($system, $parts);
    }

    // Larger study sets retain the chunk-processing approach.
    $chunkNotes = [];

    foreach ($chunks as $index => $chunk) {
        $instruction =
            "Create accurate study notes from this section.\n"
            . "This is section " . ($index + 1) . " of " . count($chunks) . ".\n"
            . "Preserve all meaningful titles, definitions, facts, examples, and details.\n"
            . "Do not invent information.";

        $chunkNotes[] = $this->ask(
            $system,
            $this->chunkParts($chunk, $instruction)
        );
    }

    $combinedNotes = $this->combineNotes($chunkNotes, $system);

    return $this->ask(
        $system,
        [
            [
                'text' => "Processed study notes:\n\n" . $combinedNotes,
            ],
            [
                'text' =>
                    "Create the final study notes using all the processed notes.\n\n"
                    . "Preserve unique information from every source section.\n\n"
                    . "LENGTH:\n{$lengthInstruction}\n\n"
                    . "FORMAT:\n{$formatInstruction}\n\n"
                    . "Additional instructions:\n"
                    . ($focus !== '' ? $focus : 'Organize the material clearly.'),
            ],
        ]
    );
}

    /**
 * Combine section notes while preserving information from every file.
 */
private function combineNotes(array $notes, string $system): string
{
    if (empty($notes)) {
        return '';
    }

    if (count($notes) === 1) {
        return $notes[0];
    }

    while (count($notes) > 1) {
        $groups = array_chunk($notes, 4);
        $newNotes = [];

        foreach ($groups as $group) {
            if (count($group) === 1) {
                $newNotes[] = $group[0];
                continue;
            }

            $sections = [];

            foreach ($group as $index => $note) {
                $sections[] =
                    "SOURCE SECTION " . ($index + 1) . ":\n"
                    . $note;
            }

            $text = implode("\n\n---\n\n", $sections);

            $instruction =
                "Combine the following study-note sections into one organized document.\n\n"
                . "IMPORTANT REQUIREMENTS:\n"
                . "1. Preserve information from EVERY source section.\n"
                . "2. Do not completely omit a section just because it contains little information.\n"
                . "3. Preserve titles, definitions, facts, examples, and important details.\n"
                . "4. Do not invent information or introduce outside knowledge.\n"
                . "5. Remove repetition only when the information is genuinely duplicated.\n"
                . "6. Keep the information traceable to its original source section.\n\n"
                . $text;

            $newNotes[] = $this->ask(
                $system,
                [['text' => $instruction]]
            );
        }

        $notes = $newNotes;
    }

    return $notes[0] ?? '';
}

    /**
     * Generate flashcards across all source sections.
     */
    public function flashcards(
    StudySet $set,
    int $count = 20
): array {
    $targetCount = max(
        1,
        min($count, self::MAX_GENERATED_ITEMS)
    );

        $system = "You are Shelly, a friendly study helper. "
            . "Use ONLY the provided study materials. "
            . "Never invent facts. Reply with valid JSON only.";

        $chunks = $this->sourceChunks($set);

        $allocations = $this->distributeCount(
            $targetCount,
            count($chunks)
        );

        $allCards = [];
        $seenQuestions = [];

        foreach ($chunks as $chunkIndex => $chunk) {
            $remainingForChunk = $allocations[$chunkIndex];

            while (
    $remainingForChunk > 0 &&
    count($allCards) < self::MAX_GENERATED_ITEMS
) {
                $batchSize = min(10, $remainingForChunk);

                $instruction = $this->flashcardInstruction(
                    $batchSize,
                    array_column($allCards, 'front')
                );

                $instruction .= "\nFocus on this particular section. "
                    . "Do not rely on information from other sections.";

                $raw = $this->ask(
                    $system,
                    $this->chunkParts($chunk, $instruction),
                    true
                );

                $items = $this->decodeJson($raw);

                if (!is_array($items)) {
                    break;
                }

                $beforeBatch = count($allCards);

                foreach ($items as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $front = trim((string) ($item['front'] ?? ''));
                    $back = trim((string) ($item['back'] ?? ''));

                    if ($front === '' || $back === '') {
                        continue;
                    }

                    $normalizedQuestion = mb_strtolower(
                        preg_replace('/\s+/', ' ', $front)
                    );

                    if (isset($seenQuestions[$normalizedQuestion])) {
                        continue;
                    }

                    $seenQuestions[$normalizedQuestion] = true;

                    $allCards[] = [
                        'front' => $front,
                        'back' => $back,
                    ];

                    if (count($allCards) >= 100) {
                        break;
                    }
                }

                $generated = count($allCards) - $beforeBatch;

                if ($generated === 0) {
                    break;
                }

                $remainingForChunk -= $generated;
            }

            if (count($allCards) >= self::MAX_GENERATED_ITEMS) {
                break;
            }
        }

        return array_slice($allCards, 0, $targetCount);
    }

    /**
     * Build one flashcard batch request.
     */
    private function flashcardInstruction(
        int $count,
        array $previousQuestions = []
    ): string {
        $instruction =
            "Generate up to {$count} NEW and useful flashcards from the "
            . "provided study materials.\n\n"
            . "Cover important concepts, definitions, processes, facts, and "
            . "relationships found in the materials.\n\n"
            . "Do not repeat a question or test the same fact in a slightly "
            . "different way. Every answer must be supported by the materials. "
            . "Do not invent facts or add outside information.\n\n"
            . "Return a JSON array of objects. Each object must contain:\n"
            . '- "front": a clear, concise question or term' . "\n"
            . '- "back": a clear and accurate answer' . "\n\n"
            . "Return valid JSON only. Do not include Markdown or commentary.";

        if (!empty($previousQuestions)) {
            $previousQuestions = array_slice($previousQuestions, -30);

            $instruction .= "\n\nDo NOT create questions similar to these "
                . "questions already generated:\n- "
                . implode("\n- ", $previousQuestions);
        }

        return $instruction;
    }

    /**
     * Generate a quiz across all source sections.
     */
    public function quiz(
        StudySet $set,
        int $count = 10,
        string $type = 'multiple_choice',
        string $customInstructions = ''
    ): array {
        $allowedTypes = [
            'true_false',
            'multiple_choice',
            'mixed',
        ];

        if (!in_array($type, $allowedTypes, true)) {
            throw new \Exception(
                'Please select a valid quiz type.'
            );
        }

        $count = max(1, min($count, self::MAX_GENERATED_ITEMS));

        $system = "You are Shelly, a careful and friendly study helper. "
            . "Use ONLY the supplied study materials. "
            . "Every question must be answerable from those materials. "
            . "Never invent facts or use outside knowledge. "
            . "Return valid JSON only.";

        $chunks = $this->sourceChunks($set);

        $allocations = $this->distributeCount(
            $count,
            count($chunks)
        );

        $allQuestions = [];
        $seenQuestions = [];

        foreach ($chunks as $chunkIndex => $chunk) {
            $remainingForChunk = $allocations[$chunkIndex];

            while ($remainingForChunk > 0) {
                $batchSize = min(10, $remainingForChunk);

                $instruction = $this->quizInstruction(
                    $batchSize,
                    $type,
                    $customInstructions,
                    count($allQuestions)
                );

                $instruction .= "\nFocus on this particular section of the materials. "
                    . "Do not rely on information from other sections.";

                $raw = $this->ask(
                    $system,
                    $this->chunkParts($chunk, $instruction),
                    true
                );

                $items = $this->decodeJson($raw);

                if (!is_array($items)) {
                    break;
                }

                $beforeBatch = count($allQuestions);

                foreach ($items as $item) {
                    if (!is_array($item)) {
                        continue;
                    }

                    $question = trim((string) ($item['question'] ?? ''));
                    $options = $item['options'] ?? [];
                    $correctIndex = $item['correct_index'] ?? null;
                    $explanation = trim((string) ($item['explanation'] ?? ''));

                    $questionType = $item['type'] ?? $type;

                    if ($type === 'mixed') {
                        if (!in_array(
                            $questionType,
                            ['true_false', 'multiple_choice'],
                            true
                        )) {
                            continue;
                        }
                    } else {
                        $questionType = $type;
                    }

                    $expectedOptions = $questionType === 'true_false' ? 2 : 4;

                    if (
                        $question === '' ||
                        !is_array($options) ||
                        count($options) !== $expectedOptions ||
                        !is_numeric($correctIndex) ||
                        (float) $correctIndex !== (float) (int) $correctIndex ||
                        (int) $correctIndex < 0 ||
                        (int) $correctIndex >= $expectedOptions ||
                        $explanation === ''
                    ) {
                        continue;
                    }

                    $options = array_values(array_map(
                        fn ($option) => trim((string) $option),
                        $options
                    ));

                    if (in_array('', $options, true)) {
                        continue;
                    }

                    if ($questionType === 'true_false') {
                        $normalizedOptions = array_map(
                            fn ($option) => strtolower($option),
                            $options
                        );

                        if ($normalizedOptions === ['true', 'false']) {
                            // Already in the correct order.
                        } elseif ($normalizedOptions === ['false', 'true']) {
                            $correctIndex = 1 - (int) $correctIndex;
                        } else {
                            continue;
                        }

                        $options = ['True', 'False'];
                    }

                    $normalizedQuestion = mb_strtolower(
                        preg_replace('/\s+/', ' ', $question)
                    );

                    if (isset($seenQuestions[$normalizedQuestion])) {
                        continue;
                    }

                    $seenQuestions[$normalizedQuestion] = true;

                    $allQuestions[] = [
                        'question' => $question,
                        'type' => $questionType,
                        'options' => $options,
                        'correct_index' => (int) $correctIndex,
                        'explanation' => $explanation,
                    ];

                    if (count($allQuestions) >= $count) {
                        break;
                    }
                }

                $generated = count($allQuestions) - $beforeBatch;

                if ($generated === 0) {
                    break;
                }

                $remainingForChunk -= $generated;

                if (count($allQuestions) >= $count) {
                    break;
                }
            }

            if (count($allQuestions) >= $count) {
                break;
            }
        }

        if (count($allQuestions) < $count) {
            throw new \Exception(
                'Shelly could generate only ' . count($allQuestions)
                . ' of the requested ' . $count . ' questions. '
                . 'Try a smaller question count or different instructions.'
            );
        }

        return array_slice($allQuestions, 0, $count);
    }

    /**
     * Build quiz generation instructions.
     */
    private function quizInstruction(
        int $count,
        string $type,
        string $customInstructions,
        int $offset
    ): string {
        $format = match ($type) {
            'true_false' =>
                'Every question must be True or False. Each question must have exactly two options in this exact order: ["True", "False"]. The correct_index must be 0 or 1.',

            'multiple_choice' =>
                'Every question must be multiple choice with exactly four answer options. The correct_index must be between 0 and 3.',

            'mixed' =>
                'Create a mixture of True or False questions and four-option multiple-choice questions. Include the "type" property for every question, using either "true_false" or "multiple_choice". True or False questions must have exactly ["True", "False"] as their options and a correct_index of 0 or 1. Multiple-choice questions must have exactly four options and a correct_index between 0 and 3.',
        };

        $custom = trim($customInstructions);

        return
            "Generate up to {$count} NEW quiz questions.\n\n"
            . "These questions are part of a larger quiz. Question batch offset: {$offset}. Do not repeat questions from earlier batches.\n\n"
            . "{$format}\n\n"
            . "Each question must be different and cover useful information from the materials.\n\n"
            . "For every question, return:\n"
            . '- "question": a clear question string' . "\n"
            . '- "type": "true_false" or "multiple_choice"' . "\n"
            . '- "options": an array of answer choices' . "\n"
            . '- "correct_index": the zero-based index of the correct answer' . "\n"
            . '- "explanation": one or two sentences explaining the correct answer using the materials' . "\n\n"
            . ($custom !== ''
                ? "Additional instructions from the student:\n{$custom}\n\n"
                : '')
            . 'Return only a valid JSON array. Do not include Markdown or extra commentary.';
    }

    /**
     * Decode JSON responses, including responses wrapped in code fences.
     */
    private function decodeJson(string $raw): ?array
    {
        $raw = trim($raw);

        $raw = preg_replace('/^```(?:json)?\s*/i', '', $raw);
        $raw = preg_replace('/\s*```$/', '', $raw);

        $decoded = json_decode(trim($raw), true);

        return is_array($decoded) ? $decoded : null;
    }
}