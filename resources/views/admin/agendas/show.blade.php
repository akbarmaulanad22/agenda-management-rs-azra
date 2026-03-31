<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('admin.agendas.index') }}" class="text-gray-400 hover:text-primary transition-colors">Agenda</a>
            <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
            <span class="font-semibold text-gray-700">Detail Agenda</span>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ showSignatureModal: false, signatureName: '', signatureUrl: '', signatureTime: '' }">
        {{-- Header Card --}}
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <h2 class="text-xl font-extrabold text-gray-900">{{ $agenda->title }}</h2>
                            @if($agenda->status === 'draft')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-600">Draft</span>
                            @elseif($agenda->status === 'active')
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-primary-50 text-primary">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-secondary-50 text-secondary-700">Selesai</span>
                            @endif
                        </div>
                        @if($agenda->description)
                            <p class="text-sm text-gray-500 max-w-xl">{{ $agenda->description }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        <a href="{{ route('admin.agendas.pdf', $agenda) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-secondary text-white text-sm font-bold shadow-md shadow-secondary/20 hover:bg-secondary-700 active:scale-[0.98] transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            Unduh PDF
                        </a>
                        @if($agenda->status === 'active')
                            <a href="{{ route('attendance.show', $agenda) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 active:scale-[0.98] transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m9.86-2.282a4.5 4.5 0 00-1.242-7.244l4.5-4.5a4.5 4.5 0 016.364 6.364l-1.757 1.757"/></svg>
                                Link Absensi
                            </a>
                            <div x-data="{ copied: false }" class="relative">
                                <button
                                    @click="navigator.clipboard.writeText('{{ route('attendance.show', $agenda) }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gray-100 text-gray-700 text-sm font-bold hover:bg-gray-200 active:scale-[0.98] transition-all duration-200"
                                    title="Copy Link Absensi"
                                >
                                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9.75a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184"/></svg>
                                    <svg x-show="copied" x-cloak class="w-4 h-4 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    <span x-text="copied ? 'Tersalin!' : 'Copy Link'"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <span class="text-[11px] font-semibold text-gray-400 uppercase">Tanggal</span>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $agenda->event_date->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-[11px] font-semibold text-gray-400 uppercase">Waktu</span>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $agenda->event_time }} WIB</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <span class="text-[11px] font-semibold text-gray-400 uppercase">Lokasi</span>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $agenda->location }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-gray-50 border border-gray-100">
                        <div class="flex items-center gap-2 mb-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            <span class="text-[11px] font-semibold text-gray-400 uppercase">No. Surat</span>
                        </div>
                        <p class="text-sm font-bold text-gray-800">{{ $agenda->letter_number ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div class="p-4 rounded-2xl bg-primary-50/50 border border-primary-100">
                        <span class="text-[11px] font-semibold text-primary/60 uppercase">Pembuat (Hormat Kami)</span>
                        <p class="text-sm font-bold text-primary-800 mt-1">{{ $agenda->creator->name }}</p>
                        <p class="text-xs text-primary/60">{{ $agenda->creator->position }}</p>
                    </div>
                    <div class="p-4 rounded-2xl bg-secondary-50/50 border border-secondary-100">
                        <span class="text-[11px] font-semibold text-secondary/60 uppercase">Mengetahui</span>
                        <p class="text-sm font-bold text-secondary-800 mt-1">{{ $agenda->validator->name }}</p>
                        <p class="text-xs text-secondary/60">{{ $agenda->validator->position }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Participants Table --}}
        <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Daftar Peserta</h3>
                    <p class="text-sm text-gray-400 mt-0.5">{{ $agenda->participants->count() }} peserta terdaftar</p>
                </div>
                @php
                    $hadir = $agenda->participants->filter(fn($p) => $p->pivot->signed_at)->count();
                @endphp
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-primary-50 text-xs font-bold text-primary">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $hadir }}/{{ $agenda->participants->count() }} Hadir
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider w-12">No</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">NIP/ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jabatan</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status Absensi</th>
                            <th class="px-6 py-3.5 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">TTD</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($agenda->participants as $index => $participant)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-3.5 text-sm text-gray-400 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-7 h-7 rounded-lg {{ $participant->pivot->signed_at ? 'bg-primary-50' : 'bg-gray-100' }} flex items-center justify-center text-[10px] font-bold {{ $participant->pivot->signed_at ? 'text-primary' : 'text-gray-400' }}">{{ strtoupper(substr($participant->name, 0, 1)) }}</div>
                                        <span class="text-sm font-semibold text-gray-800">{{ $participant->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ $participant->identifier_number }}</td>
                                <td class="px-6 py-3.5 text-sm text-gray-500">{{ $participant->position }}</td>
                                <td class="px-6 py-3.5">
                                    @if($participant->pivot->signed_at)
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-primary-50 text-primary">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                            Hadir - {{ \Carbon\Carbon::parse($participant->pivot->signed_at)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-gray-100 text-gray-400">Belum Absen</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-center">
                                    @if($participant->pivot->signature_path)
                                        <button
                                            @click="signatureName = '{{ addslashes($participant->name) }}'; signatureUrl = '{{ Storage::url($participant->pivot->signature_path) }}'; signatureTime = '{{ \Carbon\Carbon::parse($participant->pivot->signed_at)->format('d M Y, H:i') }}'; showSignatureModal = true"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-primary-50 text-primary hover:bg-primary-100 active:scale-[0.97] transition-all duration-200 cursor-pointer group"
                                            title="Lihat tanda tangan {{ $participant->name }}"
                                        >
                                            <svg class="w-3.5 h-3.5 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Lihat TTD
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Signature Preview Modal --}}
        <template x-teleport="body">
        <div
            x-show="showSignatureModal"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex items-center justify-center p-4"
            @click.self="showSignatureModal = false"
            @keydown.escape.window="showSignatureModal = false"
        >
            <div
                x-show="showSignatureModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-3xl w-full max-w-md shadow-2xl overflow-hidden"
                @click.stop
            >
                {{-- Modal Header --}}
                <div class="px-6 pt-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Tanda Tangan Peserta</h3>
                            <p class="text-sm text-gray-400 mt-0.5" x-text="signatureName"></p>
                        </div>
                        <button
                            @click="showSignatureModal = false"
                            class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                        >
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Signature Image --}}
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-200 rounded-2xl p-4 bg-gray-50/50">
                        <img
                            :src="signatureUrl"
                            :alt="'Tanda tangan ' + signatureName"
                            class="w-full h-48 object-contain"
                        >
                    </div>

                    {{-- Signature Metadata --}}
                    <div class="mt-4 flex items-center gap-2 text-xs text-gray-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Ditandatangani pada: <strong class="text-gray-600" x-text="signatureTime"></strong></span>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="px-6 pb-6">
                    <button
                        @click="showSignatureModal = false"
                        class="w-full px-4 py-2.5 rounded-2xl bg-gray-100 text-gray-700 text-sm font-semibold hover:bg-gray-200 active:scale-[0.98] transition-all duration-200"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
        </template>
    </div>
</x-app-layout>
