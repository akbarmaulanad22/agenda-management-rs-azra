<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.templates.index') }}" class="text-gray-400 hover:text-primary transition-colors">Template Surat</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Ubah Template</span>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Ubah Template</h3>
                <p class="text-sm text-gray-400 mt-0.5">Perbarui isi template undangan</p>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.templates.update', $template) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Template</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $template->name) }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('name') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="body_content" class="block text-sm font-semibold text-gray-700 mb-2">Isi Template</label>
                        <div class="mb-3 p-3.5 rounded-2xl bg-primary-50/50 border border-primary-100">
                            <p class="text-xs font-semibold text-primary mb-1">Placeholder yang tersedia:</p>
                            <div class="flex flex-wrap gap-2">
                                <code class="px-2 py-0.5 rounded-lg bg-white text-xs font-mono text-primary-700 border border-primary-100">[JUDUL_AGENDA]</code>
                                <code class="px-2 py-0.5 rounded-lg bg-white text-xs font-mono text-primary-700 border border-primary-100">[TANGGAL]</code>
                                <code class="px-2 py-0.5 rounded-lg bg-white text-xs font-mono text-primary-700 border border-primary-100">[WAKTU]</code>
                                <code class="px-2 py-0.5 rounded-lg bg-white text-xs font-mono text-primary-700 border border-primary-100">[TEMPAT]</code>
                            </div>
                        </div>
                        <textarea name="body_content" id="body_content" rows="12" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 font-mono placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>{{ old('body_content', $template->body_content) }}</textarea>
                        @error('body_content') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Perbarui</button>
                        <a href="{{ route('admin.templates.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
