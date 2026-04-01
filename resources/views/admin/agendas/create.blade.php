<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.agendas.index') }}" class="text-gray-400 hover:text-primary transition-colors">Agenda</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Buat Agenda Baru</span>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Buat Agenda Baru</h3>
                <p class="text-sm text-gray-400 mt-0.5">Lengkapi informasi agenda rapat</p>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.agendas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Section: Informasi Agenda --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Informasi Agenda
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Agenda</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" placeholder="Masukkan judul agenda" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                @error('title') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" placeholder="Deskripsikan agenda rapat..." class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('description') }}</textarea>
                                @error('description') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="event_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Acara</label>
                                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('event_date') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="event_time" class="block text-sm font-semibold text-gray-700 mb-2">Pukul Mulai</label>
                                    <input type="time" name="event_time" id="event_time" value="{{ old('event_time') }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('event_time') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                <select name="status" id="status" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                @error('status') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="organizer" class="block text-sm font-semibold text-gray-700 mb-2">Penyelenggara</label>
                                    <input type="text" name="organizer" id="organizer" value="{{ old('organizer') }}" placeholder="Nama penyelenggara" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    @error('organizer') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="meeting_chair" class="block text-sm font-semibold text-gray-700 mb-2">Pimpinan Rapat</label>
                                    <input type="text" name="meeting_chair" id="meeting_chair" value="{{ old('meeting_chair') }}" placeholder="Nama pimpinan rapat" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    @error('meeting_chair') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">Ruangan</label>
                                <select name="room_id" id="room_id" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>{{ $room->room_name }}</option>
                                    @endforeach
                                </select>
                                @error('room_id') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section: Berkas Rapat --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            Berkas Rapat
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label for="letter_file" class="block text-sm font-semibold text-gray-700 mb-2">Surat Undangan</label>
                                <input type="file" name="letter_file" id="letter_file" accept=".pdf" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-xs text-gray-400 mt-1">Format: PDF</p>
                                <p class="text-xs text-gray-400 mt-1">Max size: 5MB</p>
                                @error('letter_file') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="material_file" class="block text-sm font-semibold text-gray-700 mb-2">Materi Rapat</label>
                                <input type="file" name="material_file" id="material_file" accept=".pdf" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none file:mr-4 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                                <p class="text-xs text-gray-400 mt-1">Format: PDF</p>
                                <p class="text-xs text-gray-400 mt-1">Max size: 10MB</p>
                                @error('material_file') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Simpan</button>
                        <a href="{{ route('admin.agendas.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
