<?php

namespace App\Services;

use App\Models\StudySet;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ClaudeService
{
    public function ask(string $system, array $parts): string
    {
        $model = env('GEMINI_MODEL', 'gemini-2.5-flash');

        $response = Http::withHeaders([
            'x-goog-api-key' => env('GEMINI_API_KEY'),
        ])->timeout(180)->post(
            "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent",
            [
                'system_instruction' => ['parts' => [['text' => $system]]],
                'contents' => [['role' => 'user', 'parts' => $parts]],
            ]
        );

        if ($response->failed()) {
            throw new \Exception($response->json('error.message') ?? 'AI request failed');
        }

        return $response->json('candidates.0.content.parts.0.text') ?? 'No answer came back. Please try again.';
    }

    public function summarize(StudySet $set): string
    {
        $system = "You are Shelly, a friendly study helper. Write accurate study notes using ONLY the provided material. Use short headings and bullet points. Never invent facts.";

        if ($set->image_path) {
            $path = Storage::disk('public')->path($set->image_path);
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            $types = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];

            return $this->ask($system, [
                ['inline_data' => [
                    'mime_type' => $types[$ext],
                    'data' => base64_encode(file_get_contents($path)),
                ]],
                ['text' => 'Turn this into study notes.'],
            ]);
        }

        $chunks = mb_str_split($set->source_text ?? '', 30000);

        if (count($chunks) <= 1) {
            return $this->ask($system, [
                ['text' => "Turn this into study notes:\n\n" . ($chunks[0] ?? '')],
            ]);
        }

        // Long files: summarize each part, then combine
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
}