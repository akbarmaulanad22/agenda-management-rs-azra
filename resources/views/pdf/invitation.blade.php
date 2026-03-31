<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Undangan - {{ $agenda->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #222;
            line-height: 1.7;
            padding: 50px 70px;
        }
        .letter-date {
            text-align: right;
            margin-bottom: 24px;
        }
        .letter-meta {
            margin-bottom: 20px;
        }
        .letter-meta table td {
            vertical-align: top;
            font-size: 12pt;
            padding: 1px 0;
        }
        .letter-meta table td:first-child {
            width: 40px;
        }
        .letter-meta table td:nth-child(2) {
            width: 10px;
            text-align: center;
        }
        .letter-recipient {
            margin-bottom: 20px;
        }
        .letter-recipient p {
            margin: 0;
            line-height: 1.5;
        }
        .letter-body {
            text-align: justify;
            margin-bottom: 10px;
        }
        .letter-body p {
            margin-bottom: 5px;
            text-indent: 40px;
        }
        .event-details {
            margin: 10px 0 15px 60px;
        }
        .event-details table td {
            vertical-align: top;
            font-size: 12pt;
            padding: 2px 0;
        }
        .event-details table td:first-child {
            width: 120px;
        }
        .event-details table td:nth-child(2) {
            width: 15px;
            text-align: center;
        }
        .closing-paragraph {
            text-align: justify;
            margin-bottom: 5px;
            text-indent: 40px;
        }
        .signature-section {
            width: 100%;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .signature-section td {
            width: 50%;
            vertical-align: top;
            text-align: center;
            padding: 0 15px;
        }
        .signature-label {
            font-size: 12pt;
            margin-bottom: 5px;
        }
        .signature-block {
            position: relative;
            height: 90px;
            margin-bottom: 5px;
        }
        .signature-img {
            height: 75px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        .signature-name {
            font-weight: bold;
            border-bottom: 1px solid #222;
            display: inline-block;
            padding-bottom: 2px;
        }
        .signature-position {
            font-size: 11pt;
            color: #444;
        }
    </style>
</head>
<body>
    {{-- Tempat, Tanggal Surat --}}
    <div class="letter-date">
        {{ $agenda->letter_place ?? '' }}{{ $agenda->letter_place ? ', ' : '' }}{{ $agenda->created_at->translatedFormat('d F Y') }}
    </div>

    {{-- No Surat & Hal --}}
    <div class="letter-meta">
        <table>
            <tr>
                <td>No</td>
                <td>:</td>
                <td>{{ $agenda->letter_number ?? '-' }}</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td>{{ $agenda->title }}</td>
            </tr>
        </table>
    </div>

    {{-- Kepada Yth. --}}
    <div class="letter-recipient">
        <p>Kepada Yth.</p>
        @if($agenda->letter_recipient)
            @foreach(explode("\n", $agenda->letter_recipient) as $line)
                <p>{{ trim($line) }}</p>
            @endforeach
        @endif
    </div>

    {{-- Body Surat --}}
    <div class="letter-body">
        <p>Dengan hormat,</p>
        @if($agenda->letter_body)
            <p>{{ $agenda->letter_body }} maka kami bermaksud mengundang bapak/ibu pada:</p>
        @endif
    </div>

    {{-- Detail Acara --}}
    <div class="event-details">
        <table>
            <tr>
                <td>Hari, Tanggal</td>
                <td>:</td>
                <td>{{ $agenda->event_date->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <td>Pukul</td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($agenda->event_time)->format('H.i') }} – Selesai</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>{{ $agenda->location }}</td>
            </tr>
        </table>
    </div>

    {{-- Kalimat Penutup --}}
    <p class="closing-paragraph">Mengingat pentingnya acara ini, diharapkan Bapak/Ibu untuk hadir tepat waktu sesuai tanggal dan daftar undangan di lampiran surat. Atas perhatiannya saya ucapkan terimakasih.</p>

    {{-- Tanda Tangan --}}
    <table class="signature-section">
        <tr>
            <td>
                <div class="signature-label">Hormat Kami,</div>
                <div class="signature-block">
                    @if($agenda->creator->signature_path)
                        <img src="{{ storage_path('app/public/' . $agenda->creator->signature_path) }}" class="signature-img" alt="Tanda tangan">
                    @endif
                </div>
                <div class="signature-name">{{ $agenda->creator->name }}</div>
                <div class="signature-position">{{ $agenda->creator->position }}</div>
            </td>
            <td>
                <div class="signature-label">Mengetahui,</div>
                <div class="signature-block">
                    @if($agenda->validator->signature_path)
                        <img src="{{ storage_path('app/public/' . $agenda->validator->signature_path) }}" class="signature-img" alt="Tanda tangan">
                    @endif
                </div>
                <div class="signature-name">{{ $agenda->validator->name }}</div>
                <div class="signature-position">{{ $agenda->validator->position }}</div>
            </td>
        </tr>
    </table>
</body>
</html>
