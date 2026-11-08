<?php

namespace App\Http\Controllers;

use App\Models\StudySet;
use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use ZipArchive;
use App\Services\ClaudeService;
use App\Models\Flashcard;
use App\Models\QuizQuestion;

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

        return redirect(route('sets.show', $set, false));
    }

    public function show(StudySet $set)
    {
        return view('show', compact('set'));
    }
    public function summarize(StudySet $set, ClaudeService $claude)
{
    try {
        $set->update(['summary' => $claude->summarize($set)]);
    } catch (\Throwable $e) {
        return redirect('/sets/' . $set->id)->withErrors(['ai' => $e->getMessage()]);
    }

    return redirect('/sets/' . $set->id);
}
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
        return redirect('/sets/' . $set->id . '/flashcards')->withErrors(['ai' => $e->getMessage()]);
    }

    return redirect('/sets/' . $set->id . '/flashcards');
}
public function quiz(StudySet $set)
{
    $questions = $set->questions->map(fn ($q) => [
        'question' => $q->question,
        'options' => $q->options,
        'correct_index' => $q->correct_index,
        'explanation' => $q->explanation,
    ])->values();

    return view('quiz', compact('set', 'questions'));
}

public function makeQuiz(StudySet $set, ClaudeService $claude)
{
    try {
        $items = $claude->quiz($set);

        $set->questions()->delete();
        foreach ($items as $item) {
            if (
                !empty($item['question']) &&
                isset($item['options']) && is_array($item['options']) && count($item['options']) === 4 &&
                isset($item['correct_index']) && $item['correct_index'] >= 0 && $item['correct_index'] <= 3 &&
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
        return redirect('/sets/' . $set->id . '/quiz')->withErrors(['ai' => $e->getMessage()]);
    }

    return redirect('/sets/' . $set->id . '/quiz');
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