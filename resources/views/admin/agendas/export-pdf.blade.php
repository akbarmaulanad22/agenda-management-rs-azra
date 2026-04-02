<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $agenda->title }}</title>
    <style>
        @page { margin: 10mm 15mm 15mm 15mm; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #000; line-height: 1.5; }

        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; vertical-align: top; }
        table.data-table th { text-align: left; font-weight: bold; background-color: #f2f2f2; }
        .num { text-align: center; width: 35px; }
        .sig-cell { text-align: center; width: 110px; }
        .sig-cell img { max-width: 80px; max-height: 40px; }

        .photo-grid { width: 100%; border-collapse: collapse; }
        .photo-grid td { border: none; padding: 6px; text-align: center; width: 33.33%; vertical-align: top; }
        .photo-grid img { max-width: 100%; max-height: 200px; }

        .empty-notice { text-align: center; color: #888; font-style: italic; padding: 20px 0; }
    </style>
</head>
<body>

    @if($section === 'attendance')
        {{-- ===== DAFTAR KEHADIRAN ===== --}}
        @if($agenda->employees->count())
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="num">No</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th class="sig-cell">Tanda Tangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agenda->employees as $i => $employee)
                        <tr>
                            <td class="num">{{ $i + 1 }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->job_position }}</td>
                            <td class="sig-cell">
                                @if($employee->pivot->signature_image_path)
                                    <img src="{{ $signatureImages[$employee->id] ?? '' }}" alt="TTD">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-notice">Belum ada peserta yang hadir.</p>
        @endif

    @elseif($section === 'notes')
        {{-- ===== NOTULENSI RAPAT ===== --}}
        @if($agenda->notes->count())
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="num">No</th>
                        <th>Topik</th>
                        <th>Keputusan</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($agenda->notes as $i => $note)
                        <tr>
                            <td class="num">{{ $i + 1 }}</td>
                            <td>{{ $note->topic }}</td>
                            <td>{{ $note->decision }}</td>
                            <td>{{ $note->remarks ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="empty-notice">Belum ada notulensi.</p>
        @endif

    @elseif($section === 'photos')
        {{-- ===== DOKUMENTASI FOTO ===== --}}
        @if($agenda->images->count())
            <table class="photo-grid">
                @foreach($agenda->images->chunk(3) as $chunk)
                    <tr>
                        @foreach($chunk as $image)
                            <td>
                                <img src="{{ $agendaImages[$image->id] ?? '' }}" alt="Dokumentasi">
                            </td>
                        @endforeach
                        @for($fill = $chunk->count(); $fill < 3; $fill++)
                            <td></td>
                        @endfor
                    </tr>
                @endforeach
            </table>
        @else
            <p class="empty-notice">Belum ada dokumentasi foto.</p>
        @endif
    @endif

</body>
</html>
