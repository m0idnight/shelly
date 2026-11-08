<?php

namespace App\Http\Controllers;

use App\Models\QuizQuestion;
use App\Models\StudyLog;
use App\Models\StudySet;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{
    public function home()
    {
        $recent = StudySet::latest()->take(3)->get();
        $latest = $recent->first();
        $cheer = Arr::random(config('shelly.done'));

        return view('home', compact('recent', 'latest', 'cheer'));
    }

    public function index()
    {
        $goal = 10;
        $today = now()->toDateString();

        $days = StudyLog::selectRaw('date(created_at) as d')->distinct()->pluck('d')->flip();

        $cursor = ($days->has($today) ? now() : now()->subDay())->copy();
        $streak = 0;
        while ($days->has($cursor->toDateString())) {
            $streak++;
            $cursor->subDay();
        }

        $week = [];
        $start = now()->startOfWeek()->copy();
        for ($i = 0; $i < 7; $i++) {
            $d = $start->copy()->addDays($i);
            $week[] = [
                'label' => substr($d->format('D'), 0, 1),
                'done' => $days->has($d->toDateString()),
                'today' => $d->toDateString() === $today,
            ];
        }

        $done = StudyLog::whereDate('created_at', $today)->count();
        $percent = min(100, (int) round($done / $goal * 100));
        $points = (int) StudyLog::sum('points');

        $sets = StudySet::latest()->get()->map(function ($s) {
            $quiz = StudyLog::where('study_set_id', $s->id)->where('type', 'quiz');
            $total = (clone $quiz)->count();
            $right = (clone $quiz)->where('correct', true)->count();
            $s->answered = $total;
            $s->accuracy = $total ? (int) round($right / $total * 100) : 0;
            return $s;
        });

        $cheer = Arr::random(config('shelly.done'));

        return view('dashboard', compact('goal', 'done', 'percent', 'streak', 'week', 'points', 'sets', 'cheer'));
    }

    public function log(Request $request)
    {
        $data = $request->validate([
            'study_set_id' => 'nullable|exists:study_sets,id',
            'question_id' => 'nullable|exists:quiz_questions,id',
            'type' => 'required|in:quiz,flashcard',
            'correct' => 'nullable|boolean',
        ]);

        $points = $data['type'] === 'quiz' ? (($data['correct'] ?? false) ? 10 : 2) : 2;

        StudyLog::create([
            'study_set_id' => $data['study_set_id'] ?? null,
            'type' => $data['type'],
            'correct' => $data['correct'] ?? null,
            'points' => $points,
        ]);

        if ($data['type'] === 'quiz' && !empty($data['question_id']) && isset($data['correct'])) {
            QuizQuestion::where('id', $data['question_id'])->update(['missed' => !$data['correct']]);
        }

        return response()->json(['ok' => true]);
    }
}