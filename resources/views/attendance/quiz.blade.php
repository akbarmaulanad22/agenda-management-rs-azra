<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Soal - {{ $agenda->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div x-data="quizApp" class="max-w-3xl mx-auto pb-8">
        {{-- Header --}}
        <x-agenda-header :agenda="$agenda">
            <x-slot:actions>
                <a href="{{ route('attendance.show', $agenda) }}"
                    class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm text-white text-[11px] font-medium px-3 py-1.5 rounded-full hover:bg-white/25 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Absensi
                </a>
            </x-slot:actions>
        </x-agenda-header>

        {{-- Step 1: Select Employee --}}
        <div x-show="!selectedEmployee" class="px-4 mt-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-sm">Identifikasi Peserta</h2>
                        <p class="text-xs text-gray-400">Cari dan pilih nama Anda untuk mengerjakan soal</p>
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

                <div x-show="search.length >= 2" x-cloak class="mt-3 space-y-2 max-h-64 overflow-y-auto">
                    <template x-for="emp in filteredEmployees" :key="emp.id">
                        <div @click="selectEmployee(emp)"
                            class="rounded-xl p-3 border transition-all duration-200 flex items-center justify-between"
                            :class="completedIds.includes(emp.id)
                                ? 'bg-gray-50 border-gray-100 opacity-60'
                                : 'bg-white border-gray-200 cursor-pointer hover:border-primary/30 hover:shadow-sm active:scale-[0.99]'">
                            <div class="min-w-0 flex-1">
                                <div class="font-semibold text-gray-900 text-sm" x-text="emp.name"></div>
                                <div class="text-xs text-gray-500 mt-0.5" x-text="emp.position + ' - ' + emp.organization"></div>
                            </div>
                            <div class="shrink-0 ml-3">
                                <template x-if="completedIds.includes(emp.id)">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Selesai
                                    </span>
                                </template>
                                <template x-if="!completedIds.includes(emp.id)">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Kerjakan
                                    </span>
                                </template>
                            </div>
                        </div>
                    </template>

                    <template x-if="filteredEmployees.length === 0 && search.length >= 2">
                        <div class="text-center py-6">
                            <p class="text-gray-400 text-sm">Nama tidak ditemukan.</p>
                        </div>
                    </template>
                </div>

                <div x-show="search.length < 2" class="mt-3 text-center py-4">
                    <p class="text-xs text-gray-300">Ketik minimal 2 huruf untuk menampilkan hasil</p>
                </div>
            </div>
        </div>

        {{-- Step 2: Answer Questions --}}
        <div x-show="selectedEmployee && !submitted" x-cloak class="px-4 mt-5">
            {{-- Employee info bar --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-4 flex items-center justify-between">
                <div class="min-w-0">
                    <div class="font-bold text-gray-900 text-sm" x-text="selectedEmployee?.name"></div>
                    <div class="text-xs text-gray-500" x-text="selectedEmployee?.position + ' - ' + selectedEmployee?.organization"></div>
                </div>
                <button @click="resetSelection()"
                    class="shrink-0 ml-3 text-xs text-gray-400 hover:text-gray-600 transition font-medium">
                    Ganti
                </button>
            </div>

            {{-- Questions --}}
            <div class="space-y-4">
                <template x-for="(q, index) in questions" :key="q.id">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <div class="flex items-start gap-3 mb-4">
                            <span class="shrink-0 w-7 h-7 rounded-lg bg-primary-50 flex items-center justify-center text-xs font-bold text-primary"
                                x-text="index + 1"></span>
                            <p class="text-sm text-gray-800 font-medium leading-relaxed" x-text="q.question_text"></p>
                        </div>
                        <div class="space-y-2 ml-10">
                            <template x-for="opt in ['a','b','c','d','e']" :key="opt">
                                <label x-show="q['option_' + opt]"
                                    class="flex items-start gap-3 p-3 rounded-xl border cursor-pointer transition-all duration-200"
                                    :class="answers[q.id] === opt
                                        ? 'border-primary bg-primary-50/50 ring-1 ring-primary/20'
                                        : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50/50'">
                                    <input type="radio" :name="'q_' + q.id" :value="opt"
                                        x-model="answers[q.id]"
                                        class="mt-0.5 text-primary focus:ring-primary">
                                    <div class="flex items-start gap-2 min-w-0">
                                        <span class="shrink-0 text-xs font-bold text-gray-400 uppercase mt-0.5" x-text="opt + '.'"></span>
                                        <span class="text-sm text-gray-700" x-text="q['option_' + opt]"></span>
                                    </div>
                                </label>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Submit button --}}
            <div class="mt-6">
                <button @click="submitQuiz()" :disabled="submitting || !allAnswered"
                    class="w-full px-6 py-3 bg-gradient-to-r from-primary to-primary-700 text-white text-sm font-semibold rounded-2xl hover:from-primary-700 hover:to-primary-800 transition disabled:opacity-50 active:scale-[0.99] shadow-lg shadow-primary/20">
                    <span x-show="!submitting">Kirim Jawaban</span>
                    <span x-show="submitting">Mengirim...</span>
                </button>
                <p class="text-center text-xs mt-2" :class="allAnswered ? 'text-gray-300' : 'text-amber-500'">
                    <span x-show="!allAnswered" x-text="answeredCount + ' dari ' + questions.length + ' soal dijawab'"></span>
                    <span x-show="allAnswered">Semua soal sudah dijawab</span>
                </p>
            </div>
        </div>

        {{-- Step 3: Result --}}
        <div x-show="submitted" x-cloak class="px-4 mt-5">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900">Jawaban Anda Telah Terkirim</h3>
                <p class="text-sm text-gray-500 mt-2">Terima kasih telah mengerjakan soal pada agenda ini. Partisipasi Anda sangat kami hargai.</p>

                <a href="{{ route('attendance.show', $agenda) }}"
                    class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-2xl hover:bg-primary-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Absensi
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 px-4">
            <p class="text-xs text-gray-300">D-ASSA &middot; Digital Agenda & Attendance System</p>
        </div>

        {{-- Success Toast --}}
        <div x-show="showToast" x-transition x-cloak
            class="fixed top-4 left-1/2 -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-50 text-sm font-medium flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            Jawaban berhasil disimpan!
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

    @php
        $completedIdsArray = $completedEmployeeIds->values()->toArray();
    @endphp
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quizApp', () => ({
                search: '',
                employees: @json($employeesJson),
                questions: @json($questionsJson),
                completedIds: @json($completedIdsArray),
                selectedEmployee: null,
                answers: {},
                submitting: false,
                submitted: false,
                correctCount: 0,
                totalCount: 0,
                score: 0,
                showToast: false,
                showError: false,
                errorMessage: '',
                agendaId: {{ $agenda->id }},

                get filteredEmployees() {
                    if (this.search.length < 2) return [];
                    const q = this.search.toLowerCase();
                    return this.employees.filter(e => e.name.toLowerCase().includes(q));
                },

                get answeredCount() {
                    return Object.keys(this.answers).filter(k => this.answers[k]).length;
                },

                get allAnswered() {
                    return this.answeredCount === this.questions.length;
                },

                selectEmployee(emp) {
                    if (this.completedIds.includes(emp.id)) return;
                    this.selectedEmployee = emp;
                    this.search = '';
                    // Initialize answers object
                    this.answers = {};
                    this.questions.forEach(q => {
                        this.answers[q.id] = '';
                    });
                },

                resetSelection() {
                    this.selectedEmployee = null;
                    this.answers = {};
                },

                async submitQuiz() {
                    if (!this.allAnswered || this.submitting) return;

                    this.submitting = true;

                    try {
                        const response = await fetch(`/absen/${this.agendaId}/quiz`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                employee_id: this.selectedEmployee.id,
                                answers: this.answers,
                            }),
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.correctCount = data.correct;
                            this.totalCount = data.total;
                            this.score = data.total > 0 ? Math.round((data.correct / data.total) * 100) : 0;
                            this.submitted = true;
                            this.completedIds.push(this.selectedEmployee.id);
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
            }));
        });
    </script>
</body>

</html>
