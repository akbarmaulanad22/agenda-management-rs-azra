<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi - {{ $agenda->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div x-data="attendanceApp()" class="max-w-lg mx-auto pb-8">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-primary to-primary-700 text-white px-6 pt-8 pb-6 rounded-b-3xl shadow-lg">
            <a href="{{ route('home') }}"
                class="inline-flex items-center gap-1.5 text-primary-100 text-sm hover:text-white transition mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Daftar Agenda
            </a>
            <h1 class="text-xl font-bold leading-tight">{{ $agenda->title }}</h1>
            <div class="text-primary-100 text-sm mt-2 space-y-1">
                <p class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ $agenda->event_date->translatedFormat('l, d F Y') }}
                </p>
                <p class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $agenda->event_time }} WIB
                </p>
                <p class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ $agenda->location }}
                </p>
            </div>
        </div>

        {{-- Search --}}
        <div class="px-4 mt-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="search" placeholder="Ketik nama Anda untuk mencari..."
                    class="w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary pl-11 pr-4 py-3 text-sm bg-white">
            </div>
        </div>

        {{-- Participant List --}}
        <div class="px-4 mt-4 space-y-2 pb-6">
            <template x-for="p in filteredParticipants" :key="p.id">
                <div @click="openSignModal(p)"
                    class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center justify-between transition-all duration-200"
                    :class="p.signed_at ? 'opacity-60' : 'cursor-pointer hover:shadow-md active:scale-[0.98]'">
                    <div class="min-w-0 flex-1">
                        <div class="font-semibold text-gray-900 text-sm" x-text="p.name"></div>
                        <div class="text-xs text-gray-500 mt-0.5" x-text="p.position + ' - ' + p.department"></div>
                    </div>
                    <div class="shrink-0 ml-3">
                        <template x-if="p.signed_at">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-secondary-50 text-secondary-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                Hadir
                            </span>
                        </template>
                        <template x-if="!p.signed_at">
                            <span
                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                Absen
                            </span>
                        </template>
                    </div>
                </div>
            </template>

            <template x-if="filteredParticipants.length === 0">
                <div class="text-center py-12">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm">Nama tidak ditemukan dalam daftar undangan.</p>
                </div>
            </template>
        </div>

        {{-- Signature Modal --}}
        <div x-show="showModal" x-cloak x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-end sm:items-center justify-center p-4"
            @click.self="closeModal()">
            <div x-show="showModal" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-8 opacity-0 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="translate-y-0 opacity-100 sm:scale-100"
                x-transition:leave-end="translate-y-8 opacity-0 sm:translate-y-0 sm:scale-95"
                class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl" @click.stop>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Tanda Tangan</h3>
                <p class="text-sm text-gray-500 mb-4" x-text="selectedParticipant?.name"></p>

                <div class="border-2 border-dashed border-gray-200 rounded-xl mb-4 bg-gray-50 overflow-hidden">
                    <canvas id="signature-canvas" class="w-full bg-white"
                        style="height: 200px; touch-action: none;"></canvas>
                </div>

                <div class="flex gap-3">
                    <button @click="clearPad()"
                        class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 text-sm font-medium rounded-xl hover:bg-gray-50 transition active:scale-[0.98]">
                        Hapus
                    </button>
                    <button @click="submitSignature()" :disabled="submitting"
                        class="flex-1 px-4 py-2.5 bg-gradient-to-r from-primary to-primary-700 text-white text-sm font-semibold rounded-xl hover:from-primary-700 hover:to-primary-800 transition disabled:opacity-50 active:scale-[0.98]">
                        <span x-show="!submitting">Simpan</span>
                        <span x-show="submitting">Menyimpan...</span>
                    </button>
                </div>

                <button @click="closeModal()"
                    class="mt-3 w-full text-center text-sm text-gray-400 hover:text-gray-600 transition">
                    Batal
                </button>
            </div>
        </div>

        {{-- Success Toast --}}
        <div x-show="showToast" x-transition x-cloak
            class="fixed top-4 left-1/2 -translate-x-1/2 bg-secondary-700 text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Absensi berhasil disimpan!
        </div>

        {{-- Error Toast --}}
        <div x-show="showError" x-transition x-cloak
            class="fixed top-4 left-1/2 -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <span x-text="errorMessage"></span>
        </div>
    </div>

    <script>
        function attendanceApp() {
            return {
                search: '',
                participants: @json($participantsJson),
                showModal: false,
                selectedParticipant: null,
                signaturePad: null,
                submitting: false,
                showToast: false,
                showError: false,
                errorMessage: '',
                agendaId: {{ $agenda->id }},

                get filteredParticipants() {
                    if (!this.search) return this.participants;
                    const q = this.search.toLowerCase();
                    return this.participants.filter(p => p.name.toLowerCase().includes(q));
                },

                openSignModal(participant) {
                    if (participant.signed_at) return;
                    this.selectedParticipant = participant;
                    this.showModal = true;
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const canvas = document.getElementById('signature-canvas');
                            const rect = canvas.getBoundingClientRect();
                            canvas.width = rect.width || canvas.parentElement.offsetWidth;
                            canvas.height = 200;
                            this.signaturePad = new window.SignaturePad(canvas, {
                                backgroundColor: 'rgb(255, 255, 255)',
                                penColor: 'rgb(0, 0, 0)',
                            });
                        }, 50);
                    });
                },

                closeModal() {
                    this.showModal = false;
                    this.selectedParticipant = null;
                    if (this.signaturePad) {
                        this.signaturePad.clear();
                        this.signaturePad = null;
                    }
                },

                clearPad() {
                    if (this.signaturePad) this.signaturePad.clear();
                },

                async submitSignature() {
                    if (!this.signaturePad || this.signaturePad.isEmpty()) {
                        this.showErrorToast('Silakan tanda tangan terlebih dahulu.');
                        return;
                    }

                    this.submitting = true;
                    const base64 = this.signaturePad.toDataURL('image/png');

                    try {
                        const response = await fetch(`/absen/${this.agendaId}/sign`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                participant_id: this.selectedParticipant.id,
                                signature: base64,
                            }),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.selectedParticipant.signed_at = new Date().toISOString();
                            this.closeModal();
                            this.showToast = true;
                            setTimeout(() => this.showToast = false, 3000);
                        } else {
                            this.showErrorToast(data.message || 'Terjadi kesalahan.');
                        }
                    } catch (e) {
                        this.showErrorToast('Gagal menghubungi server.');
                    } finally {
                        this.submitting = false;
                    }
                },

                showErrorToast(msg) {
                    this.errorMessage = msg;
                    this.showError = true;
                    setTimeout(() => this.showError = false, 3000);
                },
            };
        }
    </script>
</body>

</html>