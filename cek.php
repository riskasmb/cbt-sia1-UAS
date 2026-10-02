<?php

foreach (App\Models\Exam::with('subjects')->get() as $e) {
    echo "UJIAN #{$e->id} | {$e->title} | tersedia: " . ($e->is_available ? 'ya' : 'tidak') . PHP_EOL;

    if ($e->subjects->isEmpty()) {
        echo "   (belum ada mapel terpasang)" . PHP_EOL;
    }

    foreach ($e->subjects as $s) {
        $aktif = $s->questions()->where('is_active', true)->count();
        echo "   mapel: {$s->name} (id {$s->id}) | qty: {$s->pivot->qty} | soal aktif: {$aktif}" . PHP_EOL;
    }
}