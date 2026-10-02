<?php

namespace App\Filament\Resources\ExamResults;

use App\Filament\Resources\ExamResults\Pages\ListExamResults;
use App\Filament\Resources\ExamResults\Pages\SubjectHistory;
use App\Filament\Resources\ExamResults\Pages\ViewExamResult;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static string|\UnitEnum|null $navigationGroup = 'Basis Data';

    protected static ?string $navigationLabel = 'Rekap Hasil Ujian';

    protected static ?string $modelLabel = 'Hasil Ujian';

    protected static ?string $pluralModelLabel = 'Rekap Hasil Ujian';

    protected static ?int $navigationSort = 90;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['exam', 'user']);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('submitted_at', 'desc')
            ->columns([
                TextColumn::make('user.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('exam.title')->label('Ujian')->searchable(),
                TextColumn::make('subject_names')->label('Mata Pelajaran')->wrap(),
                TextColumn::make('submitted_at')
                    ->label('Tanggal Ujian')
                    ->dateTime('d F Y, H:i')
                    ->sortable(),
                TextColumn::make('score')->label('Nilai')->numeric(decimalPlaces: 2)->sortable(),
                TextColumn::make('correct_count')->label('Benar')->numeric(),
                TextColumn::make('wrong_count')->label('Salah')->numeric(),
                IconColumn::make('passed')->label('Lulus')->boolean(),
            ])
            ->filters([
                SelectFilter::make('user_id')
                    ->label('Siswa')
                    ->options(fn () => User::where('is_staff', false)->pluck('name', 'id')),
                SelectFilter::make('exam_id')
                    ->label('Ujian')
                    ->options(fn () => Exam::pluck('title', 'id')),
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon(Heroicon::OutlinedEye)
                    ->button()
                    ->url(fn ($record) => route(
                        ViewExamResult::getRouteName(),
                        ['examResult' => $record->id]
                    )),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExamResults::route('/'),
            'histori' => SubjectHistory::route('/histori-ujian'),
            'detail' => ViewExamResult::route('/{examResult}/detail'),
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            ...parent::getNavigationItems(),
            NavigationItem::make('Histori Ujian')
                ->group('Basis Data')
                ->icon(Heroicon::OutlinedBookOpen)
                ->url(fn (): string => static::getUrl('histori'))
                ->sort(100),
        ];
    }
}