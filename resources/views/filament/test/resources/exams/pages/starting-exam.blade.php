<x-filament-panels::page>

    <div class="space-y-6">

        @foreach ($this->collections as $collection)

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">

                <h2 class="text-xl font-bold text-gray-900">
                    {{ $collection['name'] }}
                </h2>

                <div class="mt-5 space-y-5">

                    @foreach ($collection['soals'] as $index => $soal)

                        <div class="rounded-lg border border-gray-200 p-5">

                            <div class="text-gray-700">
                                <span class="font-semibold text-gray-900">
                                    {{ $index + 1 }}.
                                </span>

                                <span>
                                    {{ strip_tags($soal['payload'] ?? 'Pertanyaan tidak tersedia') }}
                                </span>
                            </div>

                            <div class="mt-4 ml-6">

                                @foreach ($soal['answers'] ?? [] as $answerIndex => $jawaban)

                                    @if ($jawaban['is_active'] ?? true)

                                        <div class="mb-3">

                                            <label class="cursor-pointer">

                                                <input
                                                    type="radio"
                                                    name="question_{{ $soal['id'] }}"
                                                    value="{{ $jawaban['id'] }}"
                                                    wire:model="answers.{{ $soal['id'] }}"
                                                    class="mr-3"
                                                >

                                                <span class="text-gray-700">
                                                    {{ chr(97 + $answerIndex) }}.
                                                    {{ $jawaban['text'] ?? 'Jawaban tidak tersedia' }}
                                                </span>

                                            </label>

                                        </div>

                                    @endif

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

        @endforeach

        <div class="flex justify-end">

            <x-filament::button
                wire:click="submitExam"
                wire:confirm="Yakin ingin mengumpulkan ujian?"
                color="primary"
            >
                Kumpulkan Ujian
            </x-filament::button>

        </div>

    </div>

</x-filament-panels::page>