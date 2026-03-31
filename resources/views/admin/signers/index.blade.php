<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Penandatangan</h2>
            <p class="text-sm text-gray-400 mt-0.5">Kelola pejabat penandatangan surat</p>
        </div>
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 rounded-2xl bg-secondary-50 flex items-center justify-center">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
            </div>
            <span class="text-sm font-semibold text-gray-500">{{ $signers->total() }} penandatangan</span>
        </div>
        <a href="{{ route('admin.signers.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 hover:bg-primary-700 hover:shadow-lg active:scale-[0.98] transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Tambah Penandatangan
        </a>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Jabatan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tanda Tangan</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($signers as $signer)
                        <tr class="group hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-xl bg-secondary-50 flex items-center justify-center text-xs font-bold text-secondary">{{ strtoupper(substr($signer->name, 0, 1)) }}</div>
                                    <span class="text-sm font-semibold text-gray-800">{{ $signer->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $signer->position }}</td>
                            <td class="px-6 py-4">
                                @if($signer->signature_path)
                                    <div class="h-10 w-24 rounded-lg bg-gray-50 border border-gray-100 p-1 flex items-center justify-center">
                                        <img src="{{ Storage::url($signer->signature_path) }}" alt="TTD" class="h-full object-contain">
                                    </div>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-semibold bg-amber-50 text-amber-600">Belum ada</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.signers.edit', $signer) }}" class="p-2 rounded-xl hover:bg-primary-50 text-gray-400 hover:text-primary transition-colors" title="Ubah">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.signers.destroy', $signer) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-xl hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </div>
                                <p class="text-sm text-gray-400">Belum ada data penandatangan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($signers->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">{{ $signers->links() }}</div>
        @endif
    </div>
</x-app-layout>
