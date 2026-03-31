<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.participants.index') }}" class="text-gray-400 hover:text-primary transition-colors">Peserta</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Tambah Peserta</span>
        </div>
    </x-slot>

    <div class="max-w-2xl">
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Tambah Peserta Baru</h3>
                <p class="text-sm text-gray-400 mt-0.5">Lengkapi data peserta rapat di bawah ini</p>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.participants.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('name') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="identifier_number" class="block text-sm font-semibold text-gray-700 mb-2">NIP/ID</label>
                        <input type="text" name="identifier_number" id="identifier_number" value="{{ old('identifier_number') }}" placeholder="Masukkan NIP atau ID" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('identifier_number') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-semibold text-gray-700 mb-2">Jabatan</label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" placeholder="Masukkan jabatan" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('position') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="department" class="block text-sm font-semibold text-gray-700 mb-2">Bagian/Unit</label>
                        <input type="text" name="department" id="department" value="{{ old('department') }}" placeholder="Masukkan bagian atau unit" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                        @error('department') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Simpan</button>
                        <a href="{{ route('admin.participants.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
