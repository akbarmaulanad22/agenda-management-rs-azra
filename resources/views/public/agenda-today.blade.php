<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agenda Hari Ini - RS AZRA</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    <style>[x-cloak] { display: none !important; }</style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100/60 min-h-screen font-sans">
    <div x-data="agendaSearch()" class="max-w-6xl mx-auto pb-8">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-primary to-primary-700 text-white px-6 pt-8 pb-5 rounded-b-3xl shadow-lg">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold leading-tight">Agenda Hari Ini</h1>
                    <p class="text-primary-100/70 text-xs mt-0.5">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            {{-- Search --}}
            <div class="mt-4 relative">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-4 h-4 text-white/50 absolute left-4 top-1/2 -translate-y-1/2 pointer-events-none"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input type="text" x-model="search" placeholder="Cari agenda..."
                    class="w-full rounded-2xl border-0 bg-white/15 text-white placeholder-white/50 shadow-sm focus:ring-2 focus:ring-white/30 pl-11 pr-4 py-2.5 text-sm backdrop-blur-sm">
            </div>
        </div>

        {{-- Agenda Count --}}
        <div class="px-5 mt-5 mb-3">
            <span class="text-xs font-semibold text-gray-400">{{ $agendas->count() }} agenda hari ini</span>
        </div>

        {{-- Agenda List --}}
        <div class="px-4">
            @if($agendas->count())
                {{-- ===== TABLET+: Table View ===== --}}
                <div class="hidden sm:block bg-white rounded-2xl border border-gray-200/60 shadow-sm overflow-hidden">
                    <table class="w-full table-fixed">
                        <thead>
                            <tr class="border-b border-gray-200/60">
                                <th class="w-[30%] text-left px-2 sm:px-5 py-2 sm:py-3 text-[9px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul</th>
                                <th class="w-[15%] text-left px-1 sm:px-4 py-2 sm:py-3 text-[9px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Waktu</th>
                                <th class="w-[22%] text-left px-1 sm:px-4 py-2 sm:py-3 text-[9px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Ruangan</th>
                                <th class="w-[20%] text-left px-1 sm:px-4 py-2 sm:py-3 text-[9px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Unit</th>
                                <th class="w-[13%] text-right px-1 sm:px-4 py-2 sm:py-3 text-[9px] sm:text-xs font-semibold text-gray-400 uppercase tracking-wider">Hadir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100/80">
                            @foreach($agendas as $agenda)
                                <tr data-id="{{ $agenda->id }}"
                                    x-show="isVisible({{ $agenda->id }})"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-1"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    onclick="window.location='{{ route('attendance.show', $agenda) }}'"
                                    class="group cursor-pointer hover:bg-primary-50/40 transition-all duration-200">

                                    {{-- Judul --}}
                                    <td class="px-2 sm:px-5 py-2 sm:py-3.5">
                                        <div class="truncate text-[11px] sm:text-sm font-semibold text-gray-800 group-hover:text-primary transition-colors">{{ $agenda->title }}</div>
                                        @if($agenda->description)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $agenda->description }}</p>
                                        @endif
                                    </td>

                                    {{-- Waktu --}}
                                    <td class="px-1 sm:px-4 py-2 sm:py-3.5 text-[11px] sm:text-sm text-gray-600">
                                        <span class="font-medium">{{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }}</span>
                                        <span class="hidden sm:inline text-gray-400 text-xs ml-0.5">WIB</span>
                                    </td>

                                    {{-- Ruangan --}}
                                    <td class="px-1 sm:px-4 py-2 sm:py-3.5">
                                        <div class="truncate text-[11px] sm:text-sm text-gray-600">{{ $agenda->room->room_name ?? '-' }}</div>
                                    </td>

                                    {{-- Unit --}}
                                    <td class="px-1 sm:px-4 py-2 sm:py-3.5">
                                        <div class="truncate text-[11px] sm:text-sm text-gray-500">{{ $agenda->unit ?? '-' }}</div>
                                    </td>

                                    {{-- Hadir --}}
                                    <td class="px-1 sm:px-4 py-2 sm:py-3.5 text-right">
                                        <span class="inline-flex items-center justify-center px-1 sm:px-2 py-0.5 rounded-lg text-[10px] sm:text-xs font-bold {{ $agenda->signed_count > 0 ? 'bg-secondary-50 text-secondary-700' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $agenda->signed_count }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- ===== MOBILE: Card Layout ===== --}}
                <div class="sm:hidden space-y-2.5">
                    @foreach($agendas as $agenda)
                        <a href="{{ route('attendance.show', $agenda) }}"
                            data-id="{{ $agenda->id }}"
                            x-show="isVisible({{ $agenda->id }})"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="group block bg-white rounded-xl shadow-sm border border-gray-200/80 hover:border-primary/30 active:scale-[0.99] transition-all duration-200 overflow-hidden">

                            <div class="flex min-w-0">
                                <div class="w-1 bg-gradient-to-b from-primary to-primary-400 shrink-0"></div>
                                <div class="flex-1 p-4">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-sm font-bold text-gray-900 group-hover:text-primary transition-colors">{{ $agenda->title }}</h3>
                                            @if($agenda->description)
                                                <p class="text-xs text-gray-400 mt-0.5 whitespace-pre-line">{{ $agenda->description }}</p>
                                            @endif
                                        </div>
                                        <div class="shrink-0 w-7 h-7 rounded-lg bg-primary-50 group-hover:bg-primary flex items-center justify-center transition-all duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="mt-3 grid grid-cols-2 gap-x-4 gap-y-2.5">
                                        <div>
                                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Waktu</span>
                                            <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WIB</p>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Ruangan</span>
                                            <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ $agenda->room->room_name ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Unit</span>
                                            <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ $agenda->unit ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider">Hadir</span>
                                            <p class="text-xs font-semibold mt-0.5 {{ $agenda->signed_count > 0 ? 'text-secondary-700' : 'text-gray-400' }}">{{ $agenda->signed_count }} orang</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16">
                    <div class="w-14 h-14 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">Tidak ada agenda hari ini</p>
                    <p class="text-gray-400 text-xs mt-1">Silakan periksa kembali nanti</p>
                </div>
            @endif

            {{-- No results state (when search active but nothing matches) --}}
            <div x-show="search && !hasVisibleAgendas()" x-cloak class="text-center py-12">
                <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <p class="text-gray-500 text-sm font-medium">Agenda tidak ditemukan</p>
                <p class="text-gray-400 text-xs mt-1">Coba kata kunci lain</p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 px-4">
            <p class="text-xs text-gray-400">D-ASSA &middot; Digital Agenda & Attendance System</p>
        </div>
    </div>

    @php
        $agendaItems = $agendas->map(function ($a) {
            return [
                'id' => $a->id,
                'title' => $a->title,
                'organizer' => $a->organizer,
                'room' => $a->room->room_name ?? '',
                'unit' => $a->unit ?? '',
                'description' => $a->description
            ];
        });
    @endphp
    <script>
        function agendaSearch() {
            return {
                search: '',
                agendas: @json($agendaItems),

                isVisible(id) {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    const a = this.agendas.find(item => item.id === id);
                    if (!a) return false;
                    return (a.title || '').toLowerCase().includes(q)
                        || (a.organizer || '').toLowerCase().includes(q)
                        || (a.room || '').toLowerCase().includes(q)
                        || (a.unit || '').toLowerCase().includes(q)
                        || (a.description || '').toLowerCase().includes(q);
                },

                hasVisibleAgendas() {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    return this.agendas.some(a =>
                        a.title.toLowerCase().includes(q)
                        || a.organizer.toLowerCase().includes(q)
                        || a.room.toLowerCase().includes(q)
                        || (a.unit || '').toLowerCase().includes(q)
                        || (a.description || '').toLowerCase().includes(q)
                    );
                }
            };
        }
    </script>
</body>

</html>
