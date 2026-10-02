<x-filament-panels::page>

    @php
        $histories = $this->getHistories();
    @endphp

    @forelse ($histories as $attempts)
        @php
            $total = $attempts->sum('total');
            $benar = $attempts->sum('benar');
            $rata = $total ? round($benar / $total * 100, 1) : 0;
        @endphp

        <x-filament::section :heading="$attempts->first()->subject_name ?: 'Tanpa Mata Pelajaran'">
            <x-slot name="description">
                Rata-rata nilai: {{ $rata }} &middot; Diujikan {{ $attempts->count() }} kali
            </x-slot>

            <table style="width:100%; text-align:left; border-collapse:collapse;">
                <thead>
                    <tr style="border-bottom:1px solid rgba(128,128,128,.4);">
                        <th style="padding:8px 0;">Ujian</th>
                        <th>Tanggal</th>
                        <th>Benar</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attempts as $a)
                        <tr style="border-bottom:1px solid rgba(128,128,128,.2);">
                            <td style="padding:8px 0;">{{ $a->exam_title }}</td>
                            <td>{{ \Carbon\Carbon::parse($a->submitted_at)->format('d M Y, H:i') }}</td>
                            <td>{{ $a->benar }} / {{ $a->total }}</td>
                            <td>{{ $a->total ? round($a->benar / $a->total * 100, 1) : 0 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
    @empty
        <x-filament::section>
            Belum ada riwayat ujian.
        </x-filament::section>
    @endforelse

</x-filament-panels::page>