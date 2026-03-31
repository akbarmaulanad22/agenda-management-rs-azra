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
                    class="block bg-white rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 active:scale-[0.98]">
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-sm leading-snug">{{ $agenda->title }}</h3>
                            <div class="mt-2 space-y-1">
                                <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($agenda->event_time)->format('H:i') }} WIB
                                </p>
                                <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-primary shrink-0"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="truncate">{{ $agenda->location }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="shrink-0 flex flex-col items-end gap-2">
                            <div class="w-8 h-8 rounded-full bg-primary-50 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-1.5">
                            <span class="text-xs font-medium text-gray-500">Kehadiran</span>
                            <span
                                class="text-xs font-semibold text-primary">{{ $agenda->signed_count }}/{{ $agenda->participants_count }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            @php
                                $percentage = $agenda->participants_count > 0
                                    ? round(($agenda->signed_count / $agenda->participants_count) * 100)
                                    : 0;
                            @endphp
                            <div class="h-2 rounded-full transition-all duration-500 {{ $percentage === 100 ? 'bg-secondary' : 'bg-primary' }}"
                                style="width: {{ $percentage }}%"></div>
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