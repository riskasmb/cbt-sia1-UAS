<?php

namespace App\Filament\Resources\ExamResults\Pages;

use App\Filament\Resources\ExamResults\ExamResultResource;
use App\Models\ExamResult;
use Filament\Resources\Pages\Page;

class ViewExamResult extends Page
{
    protected static string $resource = ExamResultResource::class;

    protected string $view = 'filament.resources.exam-results.pages.view-exam-result';

    public ExamResult $examResult;

    public function mount(ExamResult $examResult): void
    {
        $this->examResult = $examResult->load(['exam', 'user', 'answers']);
    }

    public function getTitle(): string
    {
        return 'Detail Histori Ujian';
    }
}