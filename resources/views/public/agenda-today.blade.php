<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agenda Hari Ini - RS AZRA</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100/60 min-h-screen font-sans">
    <div x-data="agendaSearch()" class="max-w-3xl mx-auto pb-8">
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

        {{-- Agenda List --}}
        <div class="px-4 mt-5 space-y-2.5">
            @forelse ($agendas as $agenda)
                <a href="{{ route('attendance.show', $agenda) }}"
                    x-show="matchesSearch('{{ addslashes($agenda->title) }}', '{{ addslashes($agenda->organizer) }}', '{{ addslashes($agenda->room->room_name ?? '') }}')"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="group block bg-white rounded-xl shadow-md shadow-gray-200/60 hover:shadow-lg hover:shadow-gray-300/50 transition-all duration-300 border border-gray-200/80 hover:border-primary/30 active:scale-[0.99] overflow-hidden">

                    <div class="flex">
                        {{-- Left accent bar --}}
                        <div class="w-1 bg-gradient-to-b from-primary to-primary-400 shrink-0"></div>

                        <div class="flex-1 px-4 py-3.5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 text-sm leading-snug group-hover:text-primary transition-colors">{{ $agenda->title }}</h3>
                                    @if($agenda->description)
                                        <p class="text-[11px] text-gray-400 mt-0.5 line-clamp-1">{{ $agenda->description }}</p>
                                    @endif
                                </div>

                                <div class="shrink-0 w-7 h-7 rounded-lg bg-primary-50 group-hover:bg-primary flex items-center justify-center transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary group-hover:text-white transition-colors" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                    </svg>
                                </div>
                            </div>

                            {{-- Info row --}}
                            <div class="mt-2.5 flex flex-wrap items-center gap-x-3 gap-y-1.5">
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WIB
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $agenda->room->room_name ?? '-' }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-medium text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    {{ $agenda->organizer }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold shrink-0 ml-auto {{ $agenda->signed_count > 0 ? 'text-green-600' : 'text-gray-300' }}">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-1.053M18 8.625a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4.5 11.25a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0z"/></svg>
                                    {{ $agenda->signed_count }} hadir
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
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
            @endforelse

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
            ];
        });
    @endphp
    <script>
        function agendaSearch() {
            return {
                search: '',
                agendas: @json($agendaItems),

                matchesSearch(title, organizer, room) {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    return title.toLowerCase().includes(q)
                        || organizer.toLowerCase().includes(q)
                        || room.toLowerCase().includes(q);
                },

                hasVisibleAgendas() {
                    if (!this.search) return true;
                    const q = this.search.toLowerCase();
                    return this.agendas.some(a =>
                        a.title.toLowerCase().includes(q)
                        || a.organizer.toLowerCase().includes(q)
                        || a.room.toLowerCase().includes(q)
                    );
                }
            };
        }
    </script>
</body>

</html>
