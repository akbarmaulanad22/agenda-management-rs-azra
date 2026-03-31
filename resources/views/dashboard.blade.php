<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Beranda</h2>
            <p class="text-sm text-gray-400 mt-0.5">Ringkasan data dan aktivitas terkini</p>
        </div>
    </x-slot>

    @php
        $totalPeserta = \App\Models\Participant::count();
        $totalPenandatangan = \App\Models\Signer::count();
        $totalAgenda = \App\Models\Agenda::count();
        $agendaAktif = \App\Models\Agenda::where('status', 'active')->count();
        $agendaDraft = \App\Models\Agenda::where('status', 'draft')->count();
        $agendaSelesai = \App\Models\Agenda::where('status', 'completed')->count();
        $upcomingAgendas = \App\Models\Agenda::where('status', 'active')->orderBy('event_date', 'asc')->take(6)->get();
        $recentAgendas = \App\Models\Agenda::latest()->take(5)->get();
    @endphp

    {{-- Bento Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 auto-rows-min">

        {{-- ===== ROW 1: Stat Cards ===== --}}

        {{-- Stat: Total Peserta --}}
        <div class="group relative bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-primary-50 to-transparent rounded-bl-[60px] opacity-60"></div>
            <div class="relative">
                <div class="w-11 h-11 rounded-2xl bg-primary-50 flex items-center justify-center mb-4">
                    <svg class="w-5.5 h-5.5 text-primary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400 mb-1">Total Peserta</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalPeserta) }}</p>
            </div>
        </div>

        {{-- Stat: Penandatangan --}}
        <div class="group relative bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-secondary/5 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-secondary-50 to-transparent rounded-bl-[60px] opacity-60"></div>
            <div class="relative">
                <div class="w-11 h-11 rounded-2xl bg-secondary-50 flex items-center justify-center mb-4">
                    <svg class="w-5.5 h-5.5 text-secondary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400 mb-1">Penandatangan</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalPenandatangan) }}</p>
            </div>
        </div>

        {{-- Stat: Total Agenda --}}
        <div class="group relative bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-blue-50 to-transparent rounded-bl-[60px] opacity-60"></div>
            <div class="relative">
                <div class="w-11 h-11 rounded-2xl bg-blue-50 flex items-center justify-center mb-4">
                    <svg class="w-5.5 h-5.5 text-blue-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <p class="text-sm font-medium text-gray-400 mb-1">Total Agenda</p>
                <p class="text-3xl font-extrabold text-gray-900">{{ number_format($totalAgenda) }}</p>
            </div>
        </div>

        {{-- Stat: Agenda Aktif (with pulse) --}}
        <div class="group relative bg-gradient-to-br from-primary to-primary-700 rounded-3xl p-6 hover:shadow-2xl hover:shadow-primary/20 hover:-translate-y-0.5 transition-all duration-300 overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-bl-[80px]"></div>
            <div class="absolute bottom-0 left-0 w-20 h-20 bg-white/5 rounded-tr-[50px]"></div>
            <div class="relative">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-11 h-11 rounded-2xl bg-white/15 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-5.5 h-5.5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    </div>
                    @if($agendaAktif > 0)
                        <span class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-white/15 backdrop-blur-sm">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-secondary-300 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-secondary"></span>
                            </span>
                            <span class="text-[11px] font-semibold text-white/90">Live</span>
                        </span>
                    @endif
                </div>
                <p class="text-sm font-medium text-white/60 mb-1">Agenda Aktif</p>
                <p class="text-3xl font-extrabold text-white">{{ number_format($agendaAktif) }}</p>
            </div>
        </div>

        {{-- ===== ROW 2: Agenda Breakdown Chart + Upcoming Agendas ===== --}}

        {{-- Agenda Status Breakdown (Span 2 cols) --}}
        <div class="md:col-span-2 bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-gray-100/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Distribusi Agenda</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Berdasarkan status</p>
                </div>
                <div class="w-9 h-9 rounded-xl bg-gray-50 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/></svg>
                </div>
            </div>

            <div class="space-y-4">
                {{-- Draft --}}
                <div class="flex items-center gap-4">
                    <div class="w-20 text-sm font-medium text-gray-500">Draft</div>
                    <div class="flex-1 h-3 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gray-400 transition-all duration-500" style="width: {{ $totalAgenda > 0 ? ($agendaDraft / $totalAgenda) * 100 : 0 }}%"></div>
                    </div>
                    <div class="w-8 text-sm font-bold text-gray-600 text-right">{{ $agendaDraft }}</div>
                </div>
                {{-- Aktif --}}
                <div class="flex items-center gap-4">
                    <div class="w-20 text-sm font-medium text-gray-500">Aktif</div>
                    <div class="flex-1 h-3 rounded-full bg-primary-50 overflow-hidden">
                        <div class="h-full rounded-full bg-primary transition-all duration-500" style="width: {{ $totalAgenda > 0 ? ($agendaAktif / $totalAgenda) * 100 : 0 }}%"></div>
                    </div>
                    <div class="w-8 text-sm font-bold text-primary text-right">{{ $agendaAktif }}</div>
                </div>
                {{-- Selesai --}}
                <div class="flex items-center gap-4">
                    <div class="w-20 text-sm font-medium text-gray-500">Selesai</div>
                    <div class="flex-1 h-3 rounded-full bg-secondary-50 overflow-hidden">
                        <div class="h-full rounded-full bg-secondary transition-all duration-500" style="width: {{ $totalAgenda > 0 ? ($agendaSelesai / $totalAgenda) * 100 : 0 }}%"></div>
                    </div>
                    <div class="w-8 text-sm font-bold text-secondary text-right">{{ $agendaSelesai }}</div>
                </div>
            </div>

            {{-- Summary row --}}
            <div class="mt-6 pt-4 border-t border-gray-100 flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
                    <span class="text-xs text-gray-500">Draft ({{ $agendaDraft }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-primary"></span>
                    <span class="text-xs text-gray-500">Aktif ({{ $agendaAktif }})</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-secondary"></span>
                    <span class="text-xs text-gray-500">Selesai ({{ $agendaSelesai }})</span>
                </div>
            </div>
        </div>

        {{-- Upcoming Agendas (Span 2 cols) --}}
        <div class="md:col-span-2 bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-gray-100/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Agenda Mendatang</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Agenda aktif yang akan datang</p>
                </div>
                <a href="{{ route('admin.agendas.index') }}" class="text-xs font-semibold text-primary hover:text-primary-700 transition-colors flex items-center gap-1">
                    Lihat Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                </a>
            </div>

            <div class="space-y-2.5 max-h-[280px] overflow-y-auto pr-1 scrollbar-thin">
                @forelse($upcomingAgendas as $agenda)
                    <a href="{{ route('admin.agendas.show', $agenda) }}" class="flex items-center gap-4 p-3.5 rounded-2xl hover:bg-gray-50 transition-colors group">
                        <div class="w-12 h-12 rounded-2xl bg-primary-50 flex flex-col items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold text-primary leading-none">{{ $agenda->event_date->format('d') }}</span>
                            <span class="text-[10px] text-primary/60 font-medium uppercase">{{ $agenda->event_date->translatedFormat('M') }}</span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-primary transition-colors">{{ $agenda->title }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <span class="text-xs text-gray-400 truncate">{{ $agenda->location }}</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1.5 flex-shrink-0">
                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-xs font-medium text-gray-400">{{ $agenda->event_time }}</span>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-8">
                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        </div>
                        <p class="text-sm text-gray-400">Belum ada agenda aktif</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== ROW 3: Quick Actions + Recent Activity ===== --}}

        {{-- Quick Actions --}}
        <div class="md:col-span-2 xl:col-span-1 bg-gradient-to-br from-gray-900 to-gray-800 rounded-3xl p-6 overflow-hidden relative hover:shadow-2xl hover:shadow-gray-900/20 transition-all duration-300">
            <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-primary/20 to-transparent rounded-bl-[80px]"></div>
            <div class="relative">
                <h3 class="text-base font-bold text-white mb-1">Aksi Cepat</h3>
                <p class="text-sm text-gray-400 mb-5">Buat data baru</p>

                <div class="space-y-2.5">
                    <a href="{{ route('admin.agendas.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/15 text-white transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-xl bg-primary/30 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        </div>
                        <span class="text-sm font-semibold">Buat Agenda Baru</span>
                        <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                    <a href="{{ route('admin.participants.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/15 text-white transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-xl bg-secondary/30 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/></svg>
                        </div>
                        <span class="text-sm font-semibold">Tambah Peserta</span>
                        <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                    <a href="{{ route('admin.templates.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-white/10 hover:bg-white/15 text-white transition-all duration-200 group">
                        <div class="w-8 h-8 rounded-xl bg-blue-500/30 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                        </div>
                        <span class="text-sm font-semibold">Buat Template</span>
                        <svg class="w-4 h-4 ml-auto opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="md:col-span-2 xl:col-span-3 bg-white rounded-3xl border border-gray-100 p-6 hover:shadow-xl hover:shadow-gray-100/50 transition-all duration-300">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Aktivitas Terbaru</h3>
                    <p class="text-sm text-gray-400 mt-0.5">Agenda yang baru ditambahkan</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanggal</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Lokasi</th>
                            <th class="pb-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="pb-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($recentAgendas as $agenda)
                            <tr class="group hover:bg-gray-50/50 transition-colors">
                                <td class="py-3.5 pr-4">
                                    <p class="text-sm font-semibold text-gray-800 group-hover:text-primary transition-colors">{{ $agenda->title }}</p>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span class="text-sm text-gray-500">{{ $agenda->event_date->translatedFormat('d M Y') }}</span>
                                </td>
                                <td class="py-3.5 pr-4">
                                    <span class="text-sm text-gray-500">{{ $agenda->location }}</span>
                                </td>
                                <td class="py-3.5 pr-4">
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
                                </td>
                                <td class="py-3.5 text-right">
                                    <a href="{{ route('admin.agendas.show', $agenda) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:text-primary-700 transition-colors">
                                        Detail
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm text-gray-400">Belum ada data agenda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
