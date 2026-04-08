<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.bank-soals.index') }}" class="text-gray-400 hover:text-primary transition-colors">Bank Soal</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Tambah Bank Soal</span>
        </div>
    </x-slot>

    <div class="max-w-4xl" x-data="{
        questions: {{ json_encode(old('questions', [['question_text' => '', 'option_a' => '', 'option_b' => '', 'option_c' => '', 'option_d' => '', 'option_e' => '', 'correct_option' => '']])) }}
    }">
        <form action="{{ route('admin.bank-soals.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Info Bank Soal --}}
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Informasi Bank Soal</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Lengkapi judul dan deskripsi bank soal</p>
                </div>
                <div class="p-8 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul</label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Masukkan judul bank soal" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('title') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                        <textarea name="description" id="description" rows="3" placeholder="Masukkan deskripsi bank soal" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none resize-none">{{ old('description') }}</textarea>
                        @error('description') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Daftar Soal --}}
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Daftar Soal</h3>
                        <p class="text-sm text-gray-400 mt-0.5">Tambahkan soal pilihan ganda (A-E)</p>
                    </div>
                    <button type="button" @click="questions.push({ question_text: '', option_a: '', option_b: '', option_c: '', option_d: '', option_e: '', correct_option: '' })" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-primary-50 text-primary text-sm font-semibold hover:bg-primary-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Tambah Soal
                    </button>
                </div>

                @error('questions') <div class="px-8 pt-4"><p class="text-rose-500 text-xs font-medium">{{ $message }}</p></div> @enderror

                <div class="p-8 space-y-6">
                    <template x-for="(question, index) in questions" :key="index">
                        <div class="rounded-2xl border border-gray-200 p-6 space-y-4 relative">
                            <div class="flex items-center justify-between">
                                <h4 class="text-sm font-bold text-gray-700">Soal <span x-text="index + 1"></span></h4>
                                <button type="button" x-show="questions.length > 1" @click="questions.splice(index, 1)" class="p-1.5 rounded-lg hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus soal">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Pertanyaan</label>
                                <textarea :name="'questions[' + index + '][question_text]'" x-model="question.question_text" rows="2" placeholder="Masukkan pertanyaan" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none resize-none" required></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                <template x-for="opt in ['a', 'b', 'c', 'd', 'e']" :key="opt">
                                    <div class="flex items-center gap-2">
                                        <label class="flex items-center gap-2 shrink-0">
                                            <input type="radio" :name="'questions[' + index + '][correct_option]'" :value="opt" x-model="question.correct_option" class="w-4 h-4 text-primary border-gray-300 focus:ring-primary/20">
                                            <span class="text-sm font-bold text-gray-500 uppercase w-4" x-text="opt"></span>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][option_' + opt + ']'" x-model="question['option_' + opt]" :placeholder="'Opsi ' + opt.toUpperCase()" class="block w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Simpan</button>
                <a href="{{ route('admin.bank-soals.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
