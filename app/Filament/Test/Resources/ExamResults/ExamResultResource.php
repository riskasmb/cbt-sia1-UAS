<?php

namespace App\Filament\Test\Resources\ExamResults;
use Illuminate\Support\Facades\Auth;
use App\Filament\Test\Resources\ExamResults\Pages\ListExamResults;
use App\Filament\Test\Resources\ExamResults\Pages\SubjectHistory;
use App\Filament\Test\Resources\ExamResults\Pages\ViewExamResult;
use App\Models\ExamResult;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Navigation\NavigationItem;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExamResultResource extends Resource
{
    protected static ?string $model = ExamResult::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Rekap Ujian';

    protected static ?string $modelLabel = 'Hasil Ujian';

    protected static ?string $pluralModelLabel = 'Rekap Ujian';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
          ->where('user_id', Auth::id())
            ->with(['exam', 'user']);
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
                TextColumn::make('user.name')->label('Siswa'),
                TextColumn::make('exam.title')->label('Ujian')->searchable(),
                TextColumn::make('subject_names')->label('Mata Pelajaran')->wrap(),
                TextColumn::make('submitted_at')
                    ->label('Tanggal Ujian')
                    ->dateTime('d F Y, H:i')
                    ->sortable(),
                TextColumn::make('score')->label('Skor')->numeric(decimalPlaces: 2)->sortable(),
                TextColumn::make('correct_count')->label('Benar')->numeric(),
                TextColumn::make('wrong_count')->label('Salah')->numeric(),
                IconColumn::make('passed')->label('Lulus')->boolean(),
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
            'histori' => SubjectHistory::route('/histori-pelajaran'),
            'detail' => ViewExamResult::route('/{examResult}/detail'),
        ];
    }

    public static function getNavigationItems(): array
    {
        return [
            ...parent::getNavigationItems(),
            NavigationItem::make('Histori Pelajaran')
                ->icon(Heroicon::OutlinedBookOpen)
                ->url(fn (): string => static::getUrl('histori'))
                ->sort(100),
        ];
    }
}