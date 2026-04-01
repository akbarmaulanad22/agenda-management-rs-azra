<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $agenda->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #000; line-height: 1.5; padding: 20px 40px; }

        .page-break { page-break-before: always; }

        h3 { font-size: 13px; font-weight: bold; margin: 0 0 10px 0; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { border: 1px solid #000; padding: 6px 8px; font-size: 11px; vertical-align: top; }
        th { text-align: left; font-weight: bold; }
        .num { text-align: center; width: 30px; }
        .sig-cell { text-align: center; width: 100px; }
        .sig-cell img { max-width: 80px; max-height: 40px; }

        .photo-grid { border: none; }
        .photo-grid td { border: none; padding: 6px; text-align: center; width: 33.33%; }
        .photo-grid img { max-width: 100%; max-height: 200px; }

        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #999; padding: 8px 0; }
    </style>
</head>
<body>
    {{-- ===== DAFTAR KEHADIRAN ===== --}}
    <h3>Daftar Kehadiran</h3>

    @if($agenda->employees->count())
        <table>
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
        <p>Belum ada peserta yang hadir.</p>
    @endif

    {{-- ===== NOTULENSI ===== --}}
    @if($agenda->notes->count())
        <div class="page-break"></div>
        <h3>Notulensi Rapat</h3>

        <table>
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
    @endif

    {{-- ===== DOKUMENTASI FOTO ===== --}}
    @if($agenda->images->count())
        <div class="page-break"></div>
        <h3>Dokumentasi Foto</h3>

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
    @endif

    <div class="footer">
        D-ASSA &middot; Digital Agenda & Attendance System &middot; Diekspor {{ now()->translatedFormat('d F Y, H:i') }}
    </div>
</body>
</html>
