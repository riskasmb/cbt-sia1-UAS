<?php

namespace App\Filament\Test\Resources\ExamResults\Pages;
use Illuminate\Support\Facades\Auth;
use App\Filament\Test\Resources\ExamResults\ExamResultResource;
use App\Models\ExamResult;
use Filament\Resources\Pages\Page;

class ViewExamResult extends Page
{
    protected static string $resource = ExamResultResource::class;

    protected string $view = 'filament.test.resources.exam-results.pages.view-exam-result';

    public ExamResult $examResult;

    public function mount(ExamResult $examResult): void
    {
       abort_unless($examResult->user_id === Auth::id(), 403);

        $this->examResult = $examResult->load(['exam', 'answers']);
    }

    public function getTitle(): string
    {
        return 'Detail Histori Ujian';
    }
}