<?php

namespace App\Services;

use App\Models\StudySet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ClaudeService
{
    public function ask(string $system, array $parts, bool $json = false): string
    {
        $model = env('GEMINI_MODEL', 'gemini-3.8-flash');

        $body = [
            'system_instruction' => ['parts' => [['text' => $system]]],
            'contents' => [['role' => 'user', 'parts' => $parts]],
        ];

        if ($json) {
            $body['generationConfig'] = ['responseMimeType' => 'application/json'];
        }

        $response = Http::withHeaders([
            'x-goog-api-key' => env('GEMINI_API_KEY'),
        ])->timeout(180)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
            $body
        );

        if ($response->failed()) {
            throw new \Exception($response->json('error.message') ?? 'AI request failed');
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'No answer came back. Please try again.';
    }

    private function sourceParts(StudySet $set, string $instruction): array
    {
        if ($set->image_path) {
            $path = Storage::disk('public')->path($set->image_path);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];

            return [
                ['inline_data' => [
                    'mime_type' => $types[$ext],
                    'data' => base64_encode(file_get_contents($path)),
                ]],
                ['text' => $instruction],
            ];
        }

        $text = mb_substr($set->source_text ?? '', 0, 30000);

        return [['text' => $instruction . "\n\nMaterial:\n\n" . $text]];
    }

    public function summarize(StudySet $set): string
    {
        $system = "You are Shelly, a friendly study helper. Write accurate study notes using ONLY the provided material. Use short headings and bullet points. Never invent facts.";

        if ($set->image_path) {
            return $this->ask($system, $this->sourceParts($set, 'Turn this into study notes.'));
        }

        $chunks = mb_str_split($set->source_text ?? '', 30000);

        if (count($chunks) <= 1) {
            return $this->ask($system, [
                ['text' => "Turn this into study notes:\n\n" . ($chunks[0] ?? '')],
            ]);
        }

        $parts = [];
        foreach ($chunks as $chunk) {
            $parts[] = $this->ask($system, [
                ['text' => "Turn this part into study notes:\n\n" . $chunk],
            ]);
        }

        return $this->ask($system, [
            ['text' => "Combine these partial notes into one organized set of study notes:\n\n" . implode("\n\n", $parts)],
        ]);
    }

    public function flashcards(StudySet $set): array
    {
        $system = "You are Shelly, a friendly study helper. Use ONLY the provided material. Never invent facts. Reply with JSON only.";

        $instruction = 'Make 12 flashcards from this material. Return a JSON array of objects, each with "front" (a short question or term) and "back" (a clear, short answer).';

        $raw = $this->ask($system, $this->sourceParts($set, $instruction), true);
        $raw = trim(preg_replace('/^```(?:json)?|```$/m', '', $raw));
        $cards = json_decode($raw, true);

        if (!is_array($cards)) {
            throw new \Exception('Shelly could not make flashcards this time. Please try again.');
        }

        return $cards;
    }
}