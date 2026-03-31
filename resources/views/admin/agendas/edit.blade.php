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
                <p class="text-sm text-gray-400 mt-0.5">Perbarui informasi agenda rapat dan detail surat undangan</p>
            </div>
            <div class="p-8">
                <form action="{{ route('admin.agendas.update', $agenda) }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')

                    {{-- Section: Informasi Agenda --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            Informasi Agenda
                        </h4>

                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Judul Agenda / Hal Surat</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $agenda->title) }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                @error('title') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                                <textarea name="description" id="description" rows="3" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('description', $agenda->description) }}</textarea>
                                @error('description') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="location" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Acara</label>
                                    <input type="text" name="location" id="location" value="{{ old('location', $agenda->location) }}" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @error('location') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
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
                    </div>

                    {{-- Section: Detail Surat Undangan --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            Detail Surat Undangan
                        </h4>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="letter_place" class="block text-sm font-semibold text-gray-700 mb-2">Tempat Surat Dibuat</label>
                                    <input type="text" name="letter_place" id="letter_place" value="{{ old('letter_place', $agenda->letter_place ?? 'Bogor') }}" placeholder="Contoh: Bogor" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    @error('letter_place') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="letter_number" class="block text-sm font-semibold text-gray-700 mb-2">Nomor Surat</label>
                                    <input type="text" name="letter_number" id="letter_number" value="{{ old('letter_number', $agenda->letter_number) }}" placeholder="Contoh: 001/SIRS-RSAZRA/XI/2024" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                                    @error('letter_number') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label for="letter_recipient" class="block text-sm font-semibold text-gray-700 mb-2">Kepada Yth.</label>
                                <textarea name="letter_recipient" id="letter_recipient" rows="4" placeholder="Contoh:&#10;Wakil Direktur&#10;Manager Medis&#10;Manager Keperawatan" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('letter_recipient', $agenda->letter_recipient) }}</textarea>
                                @error('letter_recipient') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="letter_body" class="block text-sm font-semibold text-gray-700 mb-2">Isi Surat</label>
                                <div class="mb-2 p-3 rounded-xl bg-amber-50/80 border border-amber-100">
                                    <p class="text-xs text-amber-700"><strong>Catatan:</strong> Teks ini akan dilanjutkan dengan kalimat <em>"maka kami bermaksud mengundang bapak/ibu pada:"</em> secara otomatis.</p>
                                </div>
                                <textarea name="letter_body" id="letter_body" rows="4" placeholder="Contoh: Sehubungan dengan persiapan penerapan rekam medis elektronik, maka perlunya dilakukan sosialisasi form-form asuhan keperawatan baik rawat jalan, rawat inap, OK, dan VK" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">{{ old('letter_body', $agenda->letter_body) }}</textarea>
                                @error('letter_body') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section: Penandatangan --}}
                    <div class="pb-4 border-b border-gray-100">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            Penandatangan
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="created_by_signer_id" class="block text-sm font-semibold text-gray-700 mb-2">Pembuat (Hormat Kami)</label>
                                <select name="created_by_signer_id" id="created_by_signer_id" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @foreach($signers as $signer)
                                        <option value="{{ $signer->id }}" {{ old('created_by_signer_id', $agenda->created_by_signer_id) == $signer->id ? 'selected' : '' }}>{{ $signer->name }} - {{ $signer->position }}</option>
                                    @endforeach
                                </select>
                                @error('created_by_signer_id') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="validated_by_signer_id" class="block text-sm font-semibold text-gray-700 mb-2">Mengetahui</label>
                                <select name="validated_by_signer_id" id="validated_by_signer_id" class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none" required>
                                    @foreach($signers as $signer)
                                        <option value="{{ $signer->id }}" {{ old('validated_by_signer_id', $agenda->validated_by_signer_id) == $signer->id ? 'selected' : '' }}>{{ $signer->name }} - {{ $signer->position }}</option>
                                    @endforeach
                                </select>
                                @error('validated_by_signer_id') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Section: Peserta --}}
                    @php $existingIds = old('participants', $agenda->participants->pluck('id')->toArray()); @endphp
                    <div x-data="participantSelector({{ json_encode($participants->map(fn($p) => ['id' => $p->id, 'name' => $p->name, 'department' => $p->department])) }}, {{ json_encode($existingIds) }})">
                        <h4 class="text-sm font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                            Peserta
                        </h4>
                        @error('participants') <p class="text-rose-500 text-xs font-medium mt-1.5">{{ $message }}</p> @enderror

                        <div class="mb-3">
                            <input type="text" x-model="search" placeholder="Cari peserta..." class="block w-full px-4 py-3 rounded-2xl border border-gray-200 bg-gray-50/50 text-sm text-gray-900 placeholder-gray-400 transition duration-200 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 focus:outline-none">
                        </div>

                        <div class="rounded-2xl border border-gray-200 max-h-52 overflow-y-auto">
                            <template x-for="p in filteredParticipants" :key="p.id">
                                <label class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-50 last:border-b-0 transition-colors">
                                    <input type="checkbox" :value="p.id" class="w-4 h-4 rounded-lg border-gray-300 text-primary focus:ring-primary/20" :checked="selectedIds.includes(p.id)" @change="toggle(p.id)">
                                    <span class="text-sm text-gray-700" x-text="p.name"></span>
                                    <span class="text-xs text-gray-400 ml-auto" x-text="p.department"></span>
                                </label>
                            </template>
                        </div>
                        <p class="text-xs font-medium text-gray-400 mt-2">Terpilih: <span class="text-primary font-bold" x-text="selectedIds.length"></span> peserta</p>

                        <template x-for="id in selectedIds" :key="id">
                            <input type="hidden" name="participants[]" :value="id">
                        </template>
                    </div>

                    <div class="flex items-center gap-3 pt-3">
                        <button type="submit" class="px-6 py-3 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">Perbarui</button>
                        <a href="{{ route('admin.agendas.index') }}" class="px-5 py-3 rounded-2xl bg-gray-100 text-gray-600 text-sm font-semibold hover:bg-gray-200 transition-colors">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function participantSelector(participants, initialSelected) {
            return {
                search: '',
                participants: participants,
                selectedIds: initialSelected.map(Number),
                get filteredParticipants() {
                    if (!this.search) return this.participants;
                    return this.participants.filter(p => p.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                toggle(id) {
                    const idx = this.selectedIds.indexOf(id);
                    if (idx > -1) { this.selectedIds.splice(idx, 1); }
                    else { this.selectedIds.push(id); }
                }
            }
        }
    </script>
</x-app-layout>
