<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.signers.index') }}" class="text-gray-400 hover:text-primary transition-colors">Penandatangan</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Tambah Penandatangan</span>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Tambah Penandatangan Baru</h3>
                <p class="text-sm text-gray-400 mt-0.5">Masukkan data pejabat penandatangan surat</p>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.signers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('name') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" placeholder="Masukkan jabatan" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('position') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="signature_file" class="block text-sm font-semibold text-gray-700 mb-2">File Tanda Tangan</label>
                        <p class="text-xs text-gray-400 mb-2">Upload file PNG dengan latar belakang transparan</p>
                        <input type="file" name="signature_file" id="signature_file" accept=".png" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-2xl file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 file:transition-colors file:cursor-pointer">
                        @error('signature_file') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Simpan</button>
                        <a href="{{ route('admin.signers.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
