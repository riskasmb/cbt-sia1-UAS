<?php

namespace App\Filament\Resources\ExamResults\Pages;

use App\Filament\Resources\ExamResults\ExamResultResource;
use App\Models\ExamResultAnswer;
use App\Models\User;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Collection;

class SubjectHistory extends Page
{
    protected static string $resource = ExamResultResource::class;

    protected string $view = 'filament.resources.exam-results.pages.subject-history';

    public ?int $studentId = null;

    public function getTitle(): string
    {
        return 'Histori Ujian';
    }

    public function getStudents(): Collection
    {
        return User::where('is_staff', false)->orderBy('name')->pluck('name', 'id');
    }

    public function getHistories(): Collection
    {
        return ExamResultAnswer::query()
            ->join('exam_results', 'exam_results.id', '=', 'exam_result_answers.exam_result_id')
            ->join('exams', 'exams.id', '=', 'exam_results.exam_id')
            ->join('users', 'users.id', '=', 'exam_results.user_id')
            ->when($this->studentId, fn ($q) => $q->where('exam_results.user_id', $this->studentId))
            ->selectRaw('exam_result_answers.subject_id, exam_result_answers.subject_name, users.name as student_name, exam_results.id as result_id, exam_results.submitted_at, exams.title as exam_title, COUNT(*) as total, SUM(exam_result_answers.is_correct) as benar')
            ->groupBy(
                'exam_result_answers.subject_id',
                'exam_result_answers.subject_name',
                'users.name',
                'exam_results.id',
                'exam_results.submitted_at',
                'exams.title'
            )
            ->orderByDesc('exam_results.submitted_at')
            ->get()
            ->groupBy('subject_id');
    }
}