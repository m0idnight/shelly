<?php

namespace App\Services;

use App\Models\StudySet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Client\ConnectionException;

class ClaudeService
{
    /**
     * Send a request to Gemini first, then use Cloudflare as fallback.
     */
    public function ask(string $system, array $parts, bool $json = false): string
    {
        $model = env('GEMINI_MODEL', 'gemini-3.8-flash');
        $apiKey = env('GEMINI_API_KEY');

        // Use Cloudflare directly if Gemini is not configured.
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

        $maximumAttempts = 3;

        $retryableStatuses = [
            408,
            500,
            502,
            503,
            504,
            529,
        ];

        $lastError = null;

        for ($attempt = 1; $attempt <= $maximumAttempts; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $apiKey,
                ])->timeout(180)->post($url, $body);

                // Gemini succeeded.
                if (!$response->failed()) {
                    $answer = $response->json(
                        'candidates.0.content.parts.0.text'
                    );

                    if ($answer) {
                        return $answer;
                    }

                    $lastError = 'Gemini returned an empty response.';

                    // Try the fallback provider.
                    break;
                }

                $status = $response->status();

                $lastError = $response->json('error.message')
                    ?? 'Gemini request failed.';

                // A quota error should immediately trigger fallback.
                if ($status === 429) {
                    break;
                }

                // Do not retry permanent errors such as invalid API keys.
                if (!in_array($status, $retryableStatuses, true)) {
                    throw new \Exception(
                        'Gemini API error: ' . $lastError
                    );
                }

            } catch (ConnectionException $e) {
                $lastError = 'Could not connect to Gemini.';
            }

            // Retry temporary errors, then use Cloudflare.
            if ($attempt < $maximumAttempts) {
                sleep(2 ** $attempt);
            }
        }

        // Gemini is unavailable. Try Cloudflare.
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

        // The configured fallback model is text-only.
        // Do not silently ignore uploaded images.
        foreach ($parts as $part) {
            if (isset($part['inline_data'])) {
                throw new \Exception(
                    'This study set contains images. '
                    . 'The Cloudflare fallback currently supports text only. '
                    . 'Please try again when Gemini is available.'
                );
            }
        }

        // Convert Gemini text parts into a single prompt.
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

            // Cloudflare may return decoded JSON instead of a string.
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
     * Collect all available study materials.
     *
     * New study sets use the study_materials table.
     * Older study sets continue using source_text and image_path.
     */
    private function sourceParts(StudySet $set, string $instruction): array
    {
        $parts = [];
        $textMaterials = [];

        $materials = $set->materials()->get();

        if ($materials->isNotEmpty()) {
            foreach ($materials as $material) {
                if (!empty($material->source_text)) {
                    $textMaterials[] =
                        "FILE: {$material->original_name}\n" .
                        $material->source_text;
                }

                if (
                    !empty($material->file_path) &&
                    Storage::disk('public')->exists($material->file_path)
                ) {
                    $path = Storage::disk('public')->path(
                        $material->file_path
                    );

                    $mime = $material->mime_type;

                    if ($mime && str_starts_with($mime, 'image/')) {
                        $parts[] = [
                            'inline_data' => [
                                'mime_type' => $mime,
                                'data' => base64_encode(
                                    file_get_contents($path)
                                ),
                            ],
                        ];

                        $parts[] = [
                            'text' => 'Image file: ' .
                                $material->original_name,
                        ];
                    }
                }
            }
        } else {
            // Backward compatibility for older study sets.
            if (!empty($set->source_text)) {
                $textMaterials[] = $set->source_text;
            }

            if (!empty($set->image_path)) {
                $path = Storage::disk('public')->path(
                    $set->image_path
                );

                if (file_exists($path)) {
                    $ext = strtolower(
                        pathinfo($path, PATHINFO_EXTENSION)
                    );

                    $types = [
                        'jpg' => 'image/jpeg',
                        'jpeg' => 'image/jpeg',
                        'png' => 'image/png',
                        'webp' => 'image/webp',
                    ];

                    if (isset($types[$ext])) {
                        $parts[] = [
                            'inline_data' => [
                                'mime_type' => $types[$ext],
                                'data' => base64_encode(
                                    file_get_contents($path)
                                ),
                            ],
                        ];
                    }
                }
            }
        }

        $combinedText = implode("\n\n", $textMaterials);

        // Limit the text included in one AI request.
        $combinedText = mb_substr($combinedText, 0, 120000);

        if ($combinedText !== '') {
            array_unshift($parts, [
                'text' => "Study materials:\n\n" . $combinedText,
            ]);
        }

        if (empty($parts)) {
            throw new \Exception(
                'This study set does not contain readable materials. '
                . 'Please upload your files again.'
            );
        }

        $parts[] = [
            'text' => $instruction,
        ];

        return $parts;
    }

    /**
     * Generate study notes.
/**
 * Generate study notes based on the student's preferences.
 */
public function summarize(StudySet $set, array $options = []): string
{
    $length = $options['length'] ?? 'balanced';
    $format = $options['format'] ?? 'organized';
    $focus = trim((string) ($options['focus'] ?? ''));

    $lengthInstructions = [
        'quick' => 'Keep the notes concise. Include only the most important concepts, definitions, and facts.',
        'balanced' => 'Create moderately detailed notes. Explain important concepts clearly without unnecessary repetition.',
        'detailed' => 'Create comprehensive notes. Explain important concepts thoroughly and include relevant details from the source materials.',
    ];

    $formatInstructions = [
        'organized' => 'Use clear headings and subheadings, short explanations, and bullet points. Group related topics together.',
        'key_points' => 'Prioritize essential information. Use concise bullet points, important terms, and short explanations.',
        'exam_revision' => 'Create exam-focused revision notes. Highlight important definitions, concepts, comparisons, and facts that students should remember.',
    ];

    $lengthInstruction = $lengthInstructions[$length]
        ?? $lengthInstructions['balanced'];

    $formatInstruction = $formatInstructions[$format]
        ?? $formatInstructions['organized'];

    $system = "You are Shelly, a friendly study helper. "
        . "Write accurate study notes using ONLY the provided study materials. "
        . "Never invent facts or introduce information that is not supported by the materials. "
        . "Follow the requested length and format.";

    $instruction = "Turn all the provided study materials into one useful set of study notes.\n\n"
        . "LENGTH:\n{$lengthInstruction}\n\n"
        . "FORMAT:\n{$formatInstruction}\n\n"
        . "Additional student instructions:\n"
        . ($focus !== ''
            ? $focus
            : 'No additional instructions. Use your judgment to organize the material clearly.')
        . "\n\nCombine related information and avoid unnecessary repetition.";

    $parts = $this->sourceParts($set, $instruction);

    return $this->ask($system, $parts);
}


    /**
     * Generate flashcards in small batches to avoid response truncation.
     *
     * The target is estimated from the amount of material, with a hard
     * maximum of 100 cards. The AI may return fewer if the source does not
     * contain enough distinct information.
     */
    public function flashcards(StudySet $set): array
    {
        $targetCount = $this->flashcardTargetCount($set);

        $system = "You are Shelly, a friendly study helper. "
            . "Use ONLY the provided study materials. "
            . "Never invent facts. Reply with valid JSON only.";

        $allCards = [];
        $seenQuestions = [];

        // Each request asks for at most 10 cards. This keeps JSON responses
        // small enough for both Gemini and the Cloudflare fallback.
        $maximumAttempts = max(4, (int) ceil($targetCount / 10) * 2);
        $attempts = 0;
        $consecutiveNoProgress = 0;

        while (
            count($allCards) < $targetCount &&
            count($allCards) < 100 &&
            $attempts < $maximumAttempts
        ) {
            $attempts++;

            $batchSize = min(10, $targetCount - count($allCards));

            $previousQuestions = array_column($allCards, 'front');

            $instruction = $this->flashcardInstruction(
                $batchSize,
                $previousQuestions
            );

            $raw = $this->ask(
                $system,
                $this->sourceParts($set, $instruction),
                true
            );

            $items = $this->decodeJson($raw);

            if (!is_array($items)) {
                $consecutiveNoProgress++;

                if ($consecutiveNoProgress >= 2) {
                    break;
                }

                continue;
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

                // Normalize question text so small spacing/case differences
                // do not create duplicate cards.
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

                if (count($allCards) >= $targetCount || count($allCards) >= 100) {
                    break;
                }
            }

            if (count($allCards) === $beforeBatch) {
                $consecutiveNoProgress++;
            } else {
                $consecutiveNoProgress = 0;
            }

            // Stop if repeated attempts cannot produce any new useful cards.
            if ($consecutiveNoProgress >= 2) {
                break;
            }
        }

        return array_slice($allCards, 0, 100);
    }

    /**
     * Build one flashcard batch request.
     */
    private function flashcardInstruction(
        int $count,
        array $previousQuestions = []
    ): string {
        $instruction =
            "Generate exactly {$count} NEW and useful flashcards from the "
            . "provided study materials.\n\n"
            . "Cover important concepts, definitions, processes, facts, and "
            . "relationships found in the materials. Use a variety of question "
            . "styles where appropriate.\n\n"
            . "Do not repeat a question or test the same fact in a slightly "
            . "different way. Every answer must be supported by the materials. "
            . "Do not invent facts or add outside information.\n\n"
            . "Return a JSON array of objects. Each object must contain:\n"
            . '- "front": a clear, concise question or term' . "\n"
            . '- "back": a clear and accurate answer' . "\n\n"
            . "Return valid JSON only. Do not include Markdown or commentary.";

        if (!empty($previousQuestions)) {
            $previousQuestions = array_slice($previousQuestions, 0, 100);

            $instruction .= "\n\nDo NOT create questions similar to these "
                . "questions already generated:\n- "
                . implode("\n- ", $previousQuestions);
        }

        return $instruction;
    }

    /**
     * Estimate how many flashcards the study materials can support.
     *
     * The estimate is one card per 250 characters of extracted text.
     * Each image contributes an estimated 5,000 characters of content.
     * Minimum target: 10. Maximum target: 100.
     */
    private function flashcardTargetCount(StudySet $set): int
    {
        $totalCharacters = 0;
        $imageCount = 0;

        $materials = $set->materials()->get();

        if ($materials->isNotEmpty()) {
            foreach ($materials as $material) {
                $totalCharacters += mb_strlen(
                    (string) $material->source_text
                );

                if (
                    !empty($material->mime_type) &&
                    str_starts_with($material->mime_type, 'image/')
                ) {
                    $imageCount++;
                }
            }
        } else {
            // Support older study sets.
            $totalCharacters = mb_strlen(
                (string) $set->source_text
            );

            if (!empty($set->image_path)) {
                $imageCount++;
            }
        }

        // Give each image an estimated content contribution.
        $estimatedCharacters = $totalCharacters + ($imageCount * 5000);

        // Use a denser estimate than the previous 1 card per 500 characters.
        $target = (int) ceil($estimatedCharacters / 250);

        return max(10, min(100, $target));
    }

    /**
     * Generate a quiz in batches.
     *
     * Supported types:
     * - true_false
     * - multiple_choice
     * - mixed
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

        $count = max(1, min($count, 100));

        $system = "You are Shelly, a careful and friendly study helper. "
            . "Use ONLY the supplied study materials. "
            . "Every question must be answerable from those materials. "
            . "Never invent facts or use outside knowledge. "
            . "Return valid JSON only.";

        $allQuestions = [];
        $seenQuestions = [];

        $remaining = $count;

        // Prevent unlimited requests if the AI repeatedly returns invalid questions.
        $maximumAttempts = (int) ceil($count / 10) * 3;
        $attempts = 0;

        while ($remaining > 0) {
            if ($attempts >= $maximumAttempts) {
                throw new \Exception(
                    'Shelly could not generate enough unique questions. '
                    . 'Try a smaller question count or different instructions.'
                );
            }

            $attempts++;

            $batchSize = min(10, $remaining);

            $instruction = $this->quizInstruction(
                $batchSize,
                $type,
                $customInstructions,
                count($allQuestions)
            );

            $raw = $this->ask(
                $system,
                $this->sourceParts($set, $instruction),
                true
            );

            $items = $this->decodeJson($raw);

            if (!is_array($items)) {
                continue;
            }

            foreach ($items as $item) {
                if (!is_array($item)) {
                    continue;
                }

                $question = trim($item['question'] ?? '');
                $options = $item['options'] ?? [];
                $correctIndex = $item['correct_index'] ?? null;
                $explanation = trim($item['explanation'] ?? '');

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

                // Normalize True/False options without changing the answer.
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

                // Normalize question text to detect duplicates.
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

            $remaining = $count - count($allQuestions);
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
                'Create a balanced mixture of True or False questions and four-option multiple-choice questions. Include the "type" property for every question, using either "true_false" or "multiple_choice". True or False questions must have exactly ["True", "False"] as their options and a correct_index of 0 or 1. Multiple-choice questions must have exactly four options and a correct_index between 0 and 3.',
        };

        $custom = trim($customInstructions);

        return
            "Generate exactly {$count} NEW quiz questions.\n\n" .
            "These questions are part of a larger quiz. Question batch offset: {$offset}. Do not repeat questions from earlier batches.\n\n" .
            "{$format}\n\n" .
            "Each question must be different and cover useful information from the materials.\n\n" .
            "For every question, return:\n" .
            '- "question": a clear question string' . "\n" .
            '- "type": "true_false" or "multiple_choice"' . "\n" .
            '- "options": an array of answer choices' . "\n" .
            '- "correct_index": the zero-based index of the correct answer' . "\n" .
            '- "explanation": one or two sentences explaining the correct answer using the materials' . "\n\n" .
            ($custom !== ''
                ? "Additional instructions from the student:\n{$custom}\n\n"
                : '') .
            'Return only a valid JSON array. Do not include Markdown or extra commentary.';
    }

    /**
     * Decode JSON responses, including responses wrapped in code fences.
     */
    private function decodeJson(string $raw): ?array
    {
        $raw = trim($raw);

        // Remove opening Markdown code fences.
        $raw = preg_replace('/^```(?:json)?\s*/i', '', $raw);

        // Remove closing Markdown code fences.
        $raw = preg_replace('/\s*```$/', '', $raw);

        $decoded = json_decode(trim($raw), true);

        return is_array($decoded) ? $decoded : null;
    }
}
