<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.agendas.index') }}" class="text-gray-400 hover:text-primary transition-colors">Agenda</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Ubah Agenda</span>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Ubah Agenda</h3>
                <p class="text-sm text-gray-400 mt-0.5">Perbarui informasi agenda rapat</p>
            </div>
            <div class="p-8" x-data="{ type: '{{ old('type', $agenda->type) }}' }">
                <form action="{{ route('admin.agendas.update', $agenda) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')

                    {{-- Section: Informasi Agenda --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Informasi Agenda
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Agenda</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $agenda->title) }}" placeholder="Judul agenda" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                @error('title') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" placeholder="Deskripsikan agenda rapat..." class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('description', $agenda->description) }}</textarea>
                                @error('description') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="event_date" class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Acara</label>
                                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date', $agenda->event_date->format('Y-m-d')) }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('event_date') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="event_time" class="block text-sm font-semibold text-gray-700 mb-2">Pukul Mulai</label>
                                    <input type="time" name="event_time" id="event_time" value="{{ old('event_time', $agenda->event_time) }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('event_time') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                        <option value="draft" {{ old('status', $agenda->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="active" {{ old('status', $agenda->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                        <option value="completed" {{ old('status', $agenda->status) == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    @error('status') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="organizer" class="block text-sm font-semibold text-gray-700 mb-2">Penyelenggara</label>
                                    <input type="text" name="organizer" id="organizer" value="{{ old('organizer', $agenda->organizer) }}" placeholder="Nama penyelenggara" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('organizer') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="unit" class="block text-sm font-semibold text-gray-700 mb-2">Unit</label>
                                    <input type="text" name="unit" id="unit" value="{{ old('unit', $agenda->unit) }}" placeholder="Nama unit" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    @error('unit') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="meeting_chair" class="block text-sm font-semibold text-gray-700 mb-2">Pimpinan Rapat</label>
                                <input type="text" name="meeting_chair" id="meeting_chair" value="{{ old('meeting_chair', $agenda->meeting_chair) }}" placeholder="Nama pimpinan rapat" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                @error('meeting_chair') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">Ruangan</label>
                                <select name="room_id" id="room_id" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $agenda->room_id) == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                                    @endforeach
                                </select>
                                @error('room_id') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="type" class="block text-sm font-semibold text-gray-700 mb-2">Tipe Agenda</label>
                                <select name="type" id="type" x-model="type" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    <option value="rapat">Rapat</option>
                                    <option value="diklat">Diklat</option>
                                    <option value="pelatihan">Pelatihan</option>
                                </select>
                                @error('type') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="type === 'diklat' || type === 'pelatihan'" x-transition>
                                <label for="bank_soal_id" class="block text-sm font-semibold text-gray-700 mb-2">Template Bank Soal</label>
                                <select name="bank_soal_id" id="bank_soal_id" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    <option value="">-- Pilih Bank Soal --</option>
                                    @foreach($bankSoals as $bankSoal)
                                        <option value="{{ $bankSoal->id }}" {{ old('bank_soal_id', $agenda->bank_soal_id) == $bankSoal->id ? 'selected' : '' }}>{{ $bankSoal->title }}</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-400 mt-1">Soal akan disalin ulang dari template yang dipilih</p>
                                @error('bank_soal_id') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
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
                                <label for="letter_file" class="block text-sm font-semibold text-gray-700 mb-2">File Surat</label>
                                @if($agenda->letter_file_path)
                                    <p class="text-xs text-gray-500 mb-2">File saat ini: <span class="font-medium text-gray-700">{{ basename($agenda->letter_file_path) }}</span></p>
                                @endif
                                <input type="file" name="letter_file" id="letter_file" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                @error('letter_file') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="material_file" class="block text-sm font-semibold text-gray-700 mb-2">File Materi</label>
                                @if($agenda->material_file_path)
                                    <p class="text-xs text-gray-500 mb-2">File saat ini: <span class="font-medium text-gray-700">{{ basename($agenda->material_file_path) }}</span></p>
                                @endif
                                <input type="file" name="material_file" id="material_file" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                @error('material_file') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Perbarui</button>
                        <a href="{{ route('admin.agendas.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>
