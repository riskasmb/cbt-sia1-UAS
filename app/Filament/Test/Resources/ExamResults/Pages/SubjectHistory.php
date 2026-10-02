<?php

namespace App\Filament\Test\Resources\ExamResults\Pages;
use Illuminate\Support\Facades\Auth;
use App\Filament\Test\Resources\ExamResults\ExamResultResource;
use App\Models\ExamResultAnswer;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class SubjectHistory extends Page
{
    protected static string $resource = ExamResultResource::class;

    protected string $view = 'filament.test.resources.exam-results.pages.subject-history';

    public function getTitle(): string
    {
        return 'Histori Pelajaran';
    }

    public function getHistories(): Collection
    {
        return ExamResultAnswer::query()
            ->join('exam_results', 'exam_results.id', '=', 'exam_result_answers.exam_result_id')
            ->join('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->where('exam_results.user_id', Auth::id())
            ->selectRaw('exam_result_answers.subject_id, exam_result_answers.subject_name, exam_results.id as result_id, exam_results.submitted_at, exams.title as exam_title, COUNT(*) as total, SUM(exam_result_answers.is_correct) as benar')
            ->groupBy(
                'exam_result_answers.subject_id',
                'exam_result_answers.subject_name',
                'exam_results.id',
                'exam_results.submitted_at',
                'exams.title'
            )
            ->orderByDesc('exam_results.submitted_at')
            ->get()
            ->groupBy('subject_id');
    }
}