<x-filament-panels::page>

    <x-filament::section>
        <x-slot name="heading">{{ $examResult->exam?->title }}</x-slot>
        <x-slot name="description">{{ $examResult->subject_names }}</x-slot>

        <div style="display:flex; gap:32px; flex-wrap:wrap; align-items:center;">
            <div><strong>Tanggal:</strong> {{ $examResult->submitted_at->format('d F Y, H:i') }}</div>
            <div><strong>Skor:</strong> {{ number_format($examResult->score, 2) }}</div>
            <div><strong>Benar:</strong> {{ $examResult->correct_count }}</div>
            <div><strong>Salah:</strong> {{ $examResult->wrong_count }}</div>
            <x-filament::badge :color="$examResult->passed ? 'success' : 'danger'">
                {{ $examResult->passed ? 'Lulus' : 'Tidak Lulus' }}
            </x-filament::badge>
        </div>
    </x-filament::section>

    @foreach ($examResult->answers->groupBy('subject_name') as $subjectName => $items)
        <x-filament::section :heading="$subjectName ?: 'Tanpa Mata Pelajaran'">

            @foreach ($items as $item)
                <div style="padding:14px 0; border-bottom:1px solid rgba(128,128,128,.25);">

                    <div style="display:flex; justify-content:space-between; gap:12px;">
                        <div>
                            <strong>{{ $loop->iteration }}.</strong>
                            {{ strip_tags($item->question_text) }}
                        </div>
                        <x-filament::badge :color="$item->is_correct ? 'success' : 'danger'">
                            {{ $item->is_correct ? 'Benar' : 'Salah' }}
                        </x-filament::badge>
                    </div>

                    <div style="margin-top:8px;">
                        <div><strong>Jawaban kamu:</strong> {{ $item->selected_text ?? 'Tidak dijawab' }}</div>
                        <div><strong>Kunci jawaban:</strong> {{ $item->correct_text ?? '-' }}</div>
                    </div>

                </div>
            @endforeach

        </x-filament::section>
    @endforeach

</x-filament-panels::page>