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
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div x-data="attendanceApp()" class="max-w-3xl mx-auto pb-8">
        {{-- Header --}}
        <x-agenda-header :agenda="$agenda">
            <x-slot:actions>
                <div class="flex items-center gap-2">
                    @if($agenda->agendaQuestions->count() > 0)
                        <a href="{{ route('attendance.quiz', $agenda) }}"
                            class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm text-white text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-white/25 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Kerjakan Soal
                        </a>
                    @endif
                    <a href="{{ route('agenda.input', $agenda) }}"
                        class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm text-white text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-white/25 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Input Agenda
                    </a>
                </div>
            </x-slot:actions>
        </x-agenda-header>

        {{-- Search to Attend Section --}}
        <div class="px-4 mt-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-primary-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-sm">Absen Kehadiran</h2>
                        <p class="text-xs text-gray-400">Cari nama Anda untuk melakukan absensi</p>
                    </div>
                </div>
                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Ketik nama Anda untuk mencari..."
                        class="w-full rounded-2xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary pl-11 pr-4 py-3 text-sm bg-white">
                </div>

                {{-- Search Results (only show when searching) --}}
                <div x-show="search.length >= 2" x-cloak class="mt-3 space-y-2 max-h-64 overflow-y-auto">
                    <template x-for="p in filteredEmployees" :key="p.id">
                        <div @click="openSignModal(p)"
                            class="rounded-xl p-3 border transition-all duration-200 flex items-center justify-between"
                            :class="p.signed_at
                                ? 'bg-gray-50 border-gray-100 opacity-60'
                                : 'bg-white border-gray-200 cursor-pointer hover:border-primary/30 hover:shadow-sm active:scale-[0.99]'">
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-gray-900 text-sm" x-text="p.name"></div>
                                <div class="text-xs text-gray-500 mt-0.5" x-text="p.position + ' - ' + p.organization"></div>
                            </div>
                            <div class="shrink-0 ml-3">
                                <template x-if="p.signed_at">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Sudah Hadir
                                    </span>
                                </template>
                                <template x-if="!p.signed_at">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-primary-50 text-primary">
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

                    <template x-if="filteredEmployees.length === 0 && search.length >= 2">
                        <div class="text-center py-6">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-300" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">Nama tidak ditemukan.</p>
                        </div>
                    </template>
                </div>

                {{-- Hint when not searching --}}
                <div x-show="search.length < 2" class="mt-3 text-center py-4">
                    <p class="text-xs text-gray-300">Ketik minimal 2 huruf untuk menampilkan hasil</p>
                </div>
            </div>
        </div>

        {{-- Attendee List Section --}}
        <div class="px-4 mt-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                {{-- Section Header --}}
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-green-50 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-900 text-sm">Peserta yang Sudah Hadir</h2>
                            <p class="text-xs text-gray-400" x-text="attendees.length + ' peserta'"></p>
                        </div>
                    </div>
                </div>

                {{-- Attendee Table --}}
                <div x-show="attendees.length > 0" class="overflow-hidden">
                    <table class="w-full text-[11px] table-fixed">
                        <thead>
                            <tr class="bg-gray-50/80">
                                <th class="text-left px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider" style="width:24px">No</th>
                                <th class="text-left px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="text-left px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="text-left px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider">Organisasi</th>
                                <th class="text-center px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider" style="width:40px">Jam</th>
                                <th class="text-center px-1.5 py-2 text-[9px] font-semibold text-gray-500 uppercase tracking-wider" style="width:52px">TTD</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <template x-for="(a, index) in attendees" :key="a.id">
                                <tr class="hover:bg-gray-50/40 transition-colors">
                                    <td class="px-1.5 py-1.5 text-gray-400 text-[10px]" x-text="index + 1"></td>
                                    <td class="px-1.5 py-1.5">
                                        <span class="font-semibold text-gray-900 text-[11px] break-words" x-text="a.name"></span>
                                    </td>
                                    <td class="px-1.5 py-1.5 text-gray-500 text-[11px] break-words" x-text="a.position"></td>
                                    <td class="px-1.5 py-1.5 text-gray-500 text-[11px] break-words" x-text="a.organization"></td>
                                    <td class="px-1.5 py-1.5 text-center">
                                        <span class="text-[10px] font-medium text-gray-600" x-text="a.signed_at"></span>
                                    </td>
                                    <td class="px-1.5 py-1.5 text-center">
                                        <img :src="a.signature_url" alt="TTD" class="h-7 w-auto mx-auto rounded border border-gray-100 bg-white object-contain">
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                {{-- Empty state --}}
                <div x-show="attendees.length === 0" class="py-10 text-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-1.053M18 8.625a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4.5 11.25a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Belum ada peserta yang hadir</p>
                    <p class="text-gray-300 text-xs mt-1">Gunakan pencarian di atas untuk melakukan absensi</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 px-4">
            <p class="text-xs text-gray-300">D-ASSA &middot; Digital Agenda & Attendance System</p>
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
                <p class="text-sm text-gray-500 mb-4" x-text="selectedEmployee?.name"></p>

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
            class="fixed top-4 left-1/2 -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm font-medium flex items-center gap-2">
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
                employees: @json($employeesJson),
                attendees: @json($attendeesJson),
                showModal: false,
                selectedEmployee: null,
                signaturePad: null,
                submitting: false,
                showToast: false,
                showError: false,
                errorMessage: '',
                agendaId: {{ $agenda->id }},

                get filteredEmployees() {
                    if (this.search.length < 2) return [];
                    const q = this.search.toLowerCase();
                    return this.employees.filter(p => p.name.toLowerCase().includes(q));
                },

                openSignModal(employee) {
                    if (employee.signed_at) return;
                    this.selectedEmployee = employee;
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
                    this.selectedEmployee = null;
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
                                employee_id: this.selectedEmployee.id,
                                signature: base64,
                            }),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Mark employee as signed
                            this.selectedEmployee.signed_at = true;

                            // Add to attendees list
                            if (data.attendee) {
                                this.attendees.push(data.attendee);
                            }

                            this.closeModal();
                            this.search = '';
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
