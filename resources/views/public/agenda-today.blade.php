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

<body class="bg-gray-50 min-h-screen font-sans">
    <div class="max-w-lg mx-auto pb-8">
        {{-- Header --}}
        <div class="bg-gradient-to-br from-primary to-primary-700 text-white px-6 pt-10 pb-8 rounded-b-3xl shadow-lg">
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-xl font-bold leading-tight">Agenda Hari Ini</h1>
                </div>
            </div>
            <p class="text-primary-100 text-sm mt-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-1 -mt-0.5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ now()->translatedFormat('l, d F Y') }}
            </p>
        </div>

        {{-- Agenda List --}}
        <div class="px-4 mt-6 space-y-3">
            @forelse ($agendas as $agenda)
                <a href="{{ route('attendance.show', $agenda) }}"
                    class="group block bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 hover:border-primary/20 active:scale-[0.98] overflow-hidden">

                    {{-- Color accent bar --}}
                    <div class="h-1 bg-gradient-to-r from-primary to-primary-400"></div>

                    <div class="p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-gray-900 text-[15px] leading-snug group-hover:text-primary transition-colors">{{ $agenda->title }}</h3>

                                @if($agenda->description)
                                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $agenda->description }}</p>
                                @endif
                            </div>

                            <div class="shrink-0 w-9 h-9 rounded-xl bg-primary-50 group-hover:bg-primary group-hover:text-white flex items-center justify-center transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary group-hover:text-white transition-colors" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>

                        {{-- Info pills --}}
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-primary-50 text-xs font-medium text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WIB
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-100 text-xs font-medium text-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                {{ $agenda->room->room_name ?? '-' }}
                            </span>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mt-3.5 pt-3.5 flex items-center justify-between">
                            {{-- Penyelenggara --}}
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                </div>
                                <span class="text-xs text-gray-500 truncate">{{ $agenda->organizer }}</span>
                            </div>

                            {{-- Kehadiran badge --}}
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 {{ $agenda->signed_count > 0 ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-1.053M18 8.625a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4.5 11.25a3.375 3.375 0 116.75 0 3.375 3.375 0 01-6.75 0z"/></svg>
                                {{ $agenda->signed_count }} hadir
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-gray-300" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Tidak ada agenda hari ini</p>
                    <p class="text-gray-300 text-xs mt-1">Silakan periksa kembali nanti</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="text-center mt-8 px-4">
            <p class="text-xs text-gray-300">D-ASSA &middot; Digital Agenda & Attendance System</p>
        </div>
    </div>
</body>

</html>
