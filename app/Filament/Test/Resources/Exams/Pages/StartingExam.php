<?php

namespace App\Filament\Test\Resources\Exams\Pages;

use App\Filament\Test\Resources\ExamResults\Pages\ViewExamResult;
use App\Filament\Test\Resources\Exams\ExamResource;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Question;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Locked;

class StartingExam extends Page
{
    protected static string $resource = ExamResource::class;

    protected string $view = 'filament.test.resources.exams.pages.starting-exam';

    #[Locked]
    public array $collections = [];

    #[Locked]
    public ?int $examId = null;

    /** Jawaban siswa: [question_id => answer_id] */
    public array $answers = [];

    public function mount(Exam $exam): void
    {
        abort_unless($exam->is_available, 404);

        $this->examId = $exam->id;

        foreach ($exam->subjects as $mapel) {
            $this->collections[] = [
                'id' => $mapel->id,
                'name' => $mapel->name,
                'soals' => $mapel
                    ->questions()
                    ->where('is_active', true)
                    ->with([
                        // is_correct sengaja tidak dikirim ke browser
                        'answers' => fn ($query) => $query
                            ->select('id', 'question_id', 'text', 'is_active')
                            ->inRandomOrder(),
                    ])
                    ->inRandomOrder()
                    ->limit($mapel->pivot->qty)
                    ->get()
                    ->toArray(),
            ];
        }
    }

    public function submitExam(): void
    {
        $exam = Exam::findOrFail($this->examId);

        $questionIds = collect($this->collections)
            ->flatMap(fn ($c) => collect($c['soals'])->pluck('id'))
            ->all();

        $questions = Question::with('answers')
            ->whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        $rows = [];
        $correct = 0;
        $earned = 0;
        $maxPoints = 0;

        foreach ($this->collections as $collection) {
            foreach ($collection['soals'] as $soal) {
                $question = $questions->get($soal['id']);

                if (! $question) {
                    continue;
                }

                $selectedId = $this->answers[$soal['id']] ?? null;
                $selected = $selectedId
                    ? $question->answers->firstWhere('id', (int) $selectedId)
                    : null;
                $key = $question->answers->firstWhere('is_correct', true);

                $isCorrect = $selected && $key && $selected->id === $key->id;
                $points = (int) $question->score;

                $maxPoints += $points;

                if ($isCorrect) {
                    $correct++;
                    $earned += $points;
                }

                $rows[] = [
                    'subject_id' => $collection['id'],
                    'subject_name' => $collection['name'],
                    'question_id' => $question->id,
                    'question_text' => $question->payload,
                    'selected_answer_id' => $selected?->id,
                    'selected_text' => $selected?->text,
                    'correct_answer_id' => $key?->id,
                    'correct_text' => $key?->text,
                    'points' => $isCorrect ? $points : 0,
                    'is_correct' => (bool) $isCorrect,
                ];
            }
        }

        $total = count($rows);

        $score = $maxPoints > 0
            ? round($earned / $maxPoints * 100, 2)
            : ($total > 0 ? round($correct / $total * 100, 2) : 0);

        $result = DB::transaction(function () use ($exam, $rows, $total, $correct, $score) {
            $result = ExamResult::create([
                'user_id' => Auth::id(),
                'exam_id' => $exam->id,
                'subject_names' => collect($this->collections)->pluck('name')->implode(', '),
                'submitted_at' => now(),
                'total_questions' => $total,
                'correct_count' => $correct,
                'wrong_count' => $total - $correct,
                'score' => $score,
                'passed' => $score >= (float) $exam->threshold,
            ]);

            $result->answers()->createMany($rows);

            return $result;
        });

        Notification::make()
            ->title('Ujian berhasil dikumpulkan')
            ->success()
            ->send();

        $this->redirect(route(ViewExamResult::getRouteName(), ['examResult' => $result->id]));
    }
}