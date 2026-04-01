<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Input Notulensi - {{ $agenda->title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>[x-cloak] { display: none !important; }</style>
</head>

<body class="bg-gray-50 min-h-screen font-sans">
    <div x-data="{ activeTab: 'notes', imagePreview: null }" class="max-w-2xl mx-auto pb-8">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-primary to-primary-700 text-white px-6 pt-8 pb-6 rounded-b-3xl shadow-lg">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-primary-100 text-sm hover:text-white transition mb-4">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" /></svg>
                Kembali
            </a>
            <h1 class="text-xl font-bold leading-tight">{{ $agenda->title }}</h1>
            <div class="text-primary-100 text-sm mt-2 space-y-1">
                <p class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    {{ $agenda->event_date->translatedFormat('l, d F Y') }} &middot; {{ $agenda->event_time }} WIB
                </p>
                <p class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ $agenda->room->room_name ?? '-' }}
                </p>
            </div>
        </div>

        {{-- Flash Messages --}}
        @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition class="mx-4 mt-4">
                <div class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-green-50 border border-green-200 text-green-800">
                    <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        {{-- Tabs --}}
        <div class="px-4 mt-5">
            <div class="flex bg-white rounded-2xl p-1 border border-gray-200 shadow-sm">
                <button @click="activeTab = 'notes'" :class="activeTab === 'notes' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200">
                    Notulensi
                </button>
                <button @click="activeTab = 'photos'" :class="activeTab === 'photos' ? 'bg-primary text-white shadow-md' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200">
                    Foto
                </button>
            </div>
        </div>

        {{-- Notes Tab --}}
        <div x-show="activeTab === 'notes'" x-transition class="px-4 mt-5 space-y-4">
            {{-- Add Note Form --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Tambah Catatan Baru</h3>
                <form action="{{ route('agenda.input.note', $agenda) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label for="topic" class="block text-xs font-semibold text-gray-600 mb-1.5">Topik Pembahasan</label>
                        <input type="text" name="topic" id="topic" value="{{ old('topic') }}" placeholder="Masukkan topik yang dibahas" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 text-sm bg-gray-50/50" required>
                        @error('topic') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="decision" class="block text-xs font-semibold text-gray-600 mb-1.5">Keputusan</label>
                        <textarea name="decision" id="decision" rows="3" placeholder="Tuliskan keputusan yang diambil" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 text-sm bg-gray-50/50" required>{{ old('decision') }}</textarea>
                        @error('decision') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="remarks" class="block text-xs font-semibold text-gray-600 mb-1.5">Keterangan <span class="text-gray-400">(opsional)</span></label>
                        <textarea name="remarks" id="remarks" rows="2" placeholder="Informasi tambahan" class="w-full rounded-xl border-gray-200 shadow-sm focus:border-primary focus:ring-primary px-4 py-2.5 text-sm bg-gray-50/50">{{ old('remarks') }}</textarea>
                        @error('remarks') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary-700 active:scale-[0.98] transition-all">
                        Simpan Catatan
                    </button>
                </form>
            </div>

            {{-- Existing Notes --}}
            @if($agenda->notes->count() > 0)
                <div class="space-y-3">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Catatan Sebelumnya ({{ $agenda->notes->count() }})</h3>
                    @foreach($agenda->notes->sortByDesc('created_at') as $note)
                        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100">
                            <h4 class="text-sm font-bold text-gray-900">{{ $note->topic }}</h4>
                            <p class="text-sm text-gray-600 mt-1.5">{{ $note->decision }}</p>
                            @if($note->remarks)
                                <p class="text-xs text-gray-400 mt-2 pt-2 border-t border-gray-100">{{ $note->remarks }}</p>
                            @endif
                            <p class="text-[10px] text-gray-300 mt-2">{{ $note->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Photos Tab --}}
        <div x-show="activeTab === 'photos'" x-transition class="px-4 mt-5 space-y-4">
            {{-- Upload Form --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-800 mb-4">Unggah Foto Dokumentasi</h3>
                <form action="{{ route('agenda.input.image', $agenda) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block w-full cursor-pointer">
                            <div class="border-2 border-dashed border-gray-200 rounded-xl p-6 text-center hover:border-primary/40 transition-colors" :class="imagePreview && 'border-primary/40'">
                                <template x-if="!imagePreview">
                                    <div>
                                        <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M2.25 18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V6a2.25 2.25 0 00-2.25-2.25h-15A2.25 2.25 0 002.25 6v12z"/></svg>
                                        <p class="text-sm text-gray-500 font-medium">Tap untuk memilih foto</p>
                                        <p class="text-xs text-gray-400 mt-1">JPG/PNG, maks. 3MB</p>
                                    </div>
                                </template>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="max-h-48 mx-auto rounded-lg">
                                </template>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/png" class="hidden"
                                @change="
                                    const file = $event.target.files[0];
                                    if (file && file.size > 3 * 1024 * 1024) {
                                        alert('Ukuran file maksimal 3MB');
                                        $event.target.value = '';
                                        imagePreview = null;
                                        return;
                                    }
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = e => imagePreview = e.target.result;
                                        reader.readAsDataURL(file);
                                    }
                                "
                            >
                        </label>
                        @error('image') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="w-full px-4 py-2.5 rounded-xl bg-primary text-white text-sm font-semibold shadow-md shadow-primary/20 hover:bg-primary-700 active:scale-[0.98] transition-all">
                        Unggah Foto
                    </button>
                </form>
            </div>

            {{-- Existing Images --}}
            @if($agenda->images->count() > 0)
                <div>
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Foto Sebelumnya ({{ $agenda->images->count() }})</h3>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($agenda->images as $image)
                            <div class="rounded-xl overflow-hidden border border-gray-100 shadow-sm">
                                <img src="{{ Storage::url($image->image_path) }}" alt="Dokumentasi" class="w-full h-32 object-cover" loading="lazy">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 px-4">
            <p class="text-xs text-gray-300">D-ASSA &middot; Digital Agenda & Attendance System</p>
        </div>
    </div>
</body>

</html>
