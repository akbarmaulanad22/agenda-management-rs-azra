# D-ASSA (Digital Agenda & Self-Service Attendance)

<p align="center">
<a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a>
</p>

<p align="center">
Sistem web untuk mengelola agenda pertemuan, menghasilkan undangan PDF formal dengan tanda tangan resmi ganda, dan memfasilitasi check-in mobile melalui pad tanda tangan digital.
</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Proyek

**D-ASSA** adalah sistem berbasis web yang dirancang untuk:
- Mengelola agenda pertemuan secara digital
- Membuat undangan PDF formal dengan dua tanda tangan pejabat
- Memudahkan absensi peserta melalui tanda tangan digital di perangkat mobile

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Framework | Laravel 13 |
| Authentication | Laravel Breeze |
| Database | PostgreSQL |
| Frontend | Blade, Alpine.js, Tailwind CSS |
| PDF Engine | barryvdh/laravel-dompdf |
| Testing | PHPUnit |

## Brand Guidelines

- **Warna Utama:** `#007774` (Deep Teal)
- **Warna Sekunder:** `#81bd41` (Apple Green)
- **Bahasa UI:** Bahasa Indonesia
- **Styling:** Profesional, bersih, dengan gradien warna utama/sekunder

## Fitur Utama

### 1. Admin Dashboard (Protected)
- **CRUD Master Data:** Kelola Peserta dan Penandatangan (dengan upload PNG transparan)
- **Template Builder:** Buat template undangan dengan placeholder seperti `[JUDUL_AGENDA]`, `[TANGGAL]`, `[TEMPAT]`, `[WAKTU]`
- **Manajemen Agenda:** 
  - Buat agenda dan pilih peserta dengan multi-select UI (Alpine.js)
  - Pilih dua penandatangan berbeda untuk footer undangan
- **PDF Invitation:** Generate PDF formal tanpa logo, dengan positioning absolut untuk tanda tangan

### 2. Mobile Self-Service Attendance (Public)
- **Route:** `/absen/{agenda_id}`
- **Live Search:** Pencarian nama peserta secara real-time dengan Alpine.js
- **Signature Pad:** Modal dengan HTML5 `<canvas>` untuk tanda tangan digital
- **Submission:** Convert Canvas ke Base64 → Simpan sebagai PNG → Update database

## Instalasi

### Prasyarat
- PHP ^8.3
- Composer
- Node.js & NPM
- PostgreSQL

### Langkah Instalasi

1. **Clone repository**
```bash
git clone <repository-url>
cd d-assa
```

2. **Install dependencies**
```bash
composer install
npm install
```

3. **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Konfigurasi database** (edit `.env`)
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=d_assa
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

5. **Migrate database**
```bash
php artisan migrate
```

6. **Build assets**
```bash
npm run build
```

7. **Jalankan development server**
```bash
npm run dev
# atau
php artisan serve
```

## Struktur Database

### Tabel Utama

1. **`signers`** - Data penandatangan resmi
   - `id`, `name`, `position`, `signature_path`

2. **`participants`** - Data peserta
   - `id`, `name`, `identifier_number` (NIP), `position`, `department`

3. **`invitation_templates`** - Template undangan
   - `id`, `name`, `body_content` (dengan placeholders)

4. **`agendas`** - Transaksi agenda
   - `id`, `title`, `description`, `location`, `event_date`, `event_time`, `status`
   - `template_id` (FK)
   - `created_by_signer_id` (FK) - "Hormat Kami"
   - `validated_by_signer_id` (FK) - "Mengetahui"

5. **`agenda_participant`** - Pivot table
   - `agenda_id`, `participant_id`, `signature_path`, `signed_at`

## Development Scripts

```bash
# Install semua dependencies dan setup
composer run setup

# Development mode (server, queue, logs, vite)
composer run dev

# Run tests
composer run test
```

## Testing

Unit tests mencakup:
1. **Placeholder Service** - Verifikasi replacement tag dalam PDF
2. **Signature Storage** - Verifikasi penyimpanan Base64 canvas sebagai file
3. **Double Attendance Prevention** - Mencegah participant sign-in ganda

```bash
php artisan test
```

## Kontribusi

Terima kasih atas kontribusi Anda untuk proyek D-ASSA!

## Keamanan

Jika menemukan kerentanan keamanan, silakan hubungi tim pengembang.

## License

Proyek ini menggunakan Laravel framework yang open-sourced di bawah [MIT license](https://opensource.org/licenses/MIT).

## Credits

- Laravel Framework
- Alpine.js
- Tailwind CSS
- DOMPDF
