# Speed ID - Smart Public Service Platform

Buatkan aplikasi web fullstack bernama **Speed ID** menggunakan:

* Laravel 13
* PHP 8.4
* MySQL 8+
* TailwindCSS 4
* Alpine.js
* Vanilla JavaScript
* Laravel Queue
* Laravel Scheduler
* Laravel Notifications
* Laravel Policies & Gates
* Spatie Laravel Permission
* Simple QR Code Package
* Leaflet.js (Map)
* OpenStreetMap
* Chart.js

Aplikasi merupakan platform layanan publik digital terintegrasi yang menyediakan:

1. SpeedQ (Antrean Digital)
2. SpeedReport (Pelaporan Masyarakat)
3. SpeedSOS (Layanan Darurat)
4. SpeedNews (Berita dan Informasi Wilayah)

Gunakan arsitektur monolith Laravel yang clean, scalable, dan mudah dikembangkan.

---

# Role System

Gunakan RBAC (Role Based Access Control).

Role:

1. Admin
2. User

Gunakan package:

* spatie/laravel-permission

---

# Authentication

Gunakan Laravel Breeze.

Fitur:

* Register
* Login
* Logout
* Forgot Password
* Email Verification
* Remember Me

Field User:

* name
* email
* phone
* avatar
* region_id
* password
* role

---

# Landing Page

Mengikuti desain landing page Speed ID.

## Navbar

* Home
* Layanan
* Tentang
* Kontak
* Login
* Register

## Hero Section

Tampilkan:

* Judul utama Speed ID
* Deskripsi layanan publik digital
* Tombol Mulai Sekarang
* Tombol Pelajari Layanan

## Statistik

Tampilkan:

* Total Pengguna
* Total Instansi
* Tingkat Kepuasan
* Rata-rata Waktu Respon

## Layanan Utama

### SpeedQ

Antrean Online

### SpeedReport

Pelaporan Masyarakat

### SpeedSOS

Layanan Darurat

### SpeedNews

Informasi Wilayah

## Tentang Speed ID

Penjelasan platform.

## CTA

Daftar Gratis Sekarang

## Footer

* Tentang
* Layanan
* Kontak
* Sosial Media

---

# Dashboard User

Tampilkan:

* Ringkasan layanan
* Tiket antrean aktif
* Riwayat laporan
* SOS terakhir
* Berita terbaru
* Notifikasi

---

# Dashboard Admin

Tampilkan:

* Total User
* Total Antrean
* Total Laporan
* Total SOS
* Total Artikel

Gunakan Chart.js:

* Statistik laporan
* Statistik antrean
* Statistik SOS
* Statistik pengguna

---

# Modul SpeedQ

Sistem antrean digital berbasis slot waktu.

## Instansi

Admin dapat mengelola:

* Rumah Sakit
* Puskesmas
* Dukcapil
* Samsat
* Kantor Pemerintah
* Instansi lainnya

Field:

* nama
* deskripsi
* alamat
* wilayah
* latitude
* longitude
* foto
* is_active

---

## Layanan Instansi

Field:

* institution_id
* nama_layanan
* deskripsi
* durasi_layanan
* kuota_harian

---

## Slot Jadwal

Field:

* service_id
* tanggal
* jam_mulai
* jam_selesai
* kuota

---

## Booking Antrean

User dapat:

* memilih instansi
* memilih layanan
* memilih tanggal
* memilih slot

Sistem otomatis:

* generate nomor antrean
* generate kode booking
* generate QR Ticket
* menghitung estimasi waktu

---

## QR Ticket

QR Code berisi:

* kode booking
* nomor antrean
* user id

---

## Tracking Antrean

Realtime polling setiap 10 detik.

Tampilkan:

* nomor berjalan
* nomor user
* estimasi waktu
* status antrean

Status:

* menunggu
* dipanggil
* selesai
* batal

---

## Manajemen Antrean Admin

Admin dapat:

* memanggil antrean
* skip antrean
* menyelesaikan antrean
* membatalkan antrean
* reset antrean harian

---

# Modul SpeedReport

Pelaporan masyarakat berbasis lokasi.

## Buat Laporan

Field:

* kategori
* judul
* deskripsi
* foto/video
* latitude
* longitude
* anonymous

Kategori:

* Jalan Rusak
* Sampah
* Lampu Jalan
* Banjir
* Fasilitas Umum
* Kriminalitas
* Lainnya

---

## Upload Bukti

Support:

* jpg
* jpeg
* png
* webp
* mp4

Maksimal:

20 MB

---

## Status Laporan

Status:

* terkirim
* diverifikasi
* diproses
* selesai
* ditolak

---

## Timeline Laporan

Tampilkan histori perubahan status.

---

## Komentar Tindak Lanjut

Admin dapat:

* memberi komentar
* mengunggah bukti tindak lanjut

---

## Notifikasi

User menerima notifikasi setiap status berubah.

---

# Modul SpeedSOS

Layanan bantuan darurat.

## Tombol SOS

Tombol besar berwarna merah.

Saat ditekan:

1. tampilkan konfirmasi
2. ambil lokasi realtime
3. kirim permintaan SOS

---

## Data SOS

Field:

* user_id
* emergency_type
* note
* latitude
* longitude
* status

Jenis:

* Ambulans
* Polisi
* Pemadam
* Bencana
* Lainnya

---

## Kontak Darurat

User dapat menyimpan maksimal 5 kontak.

Field:

* nama
* nomor_telepon
* hubungan

---

## Monitoring SOS

Admin dapat melihat:

* daftar SOS masuk
* lokasi pada peta
* waktu masuk
* informasi user

Status:

* masuk
* diproses
* dalam_penanganan
* selesai

---

## Peta Realtime

Gunakan:

* Leaflet.js
* OpenStreetMap

---

# Modul SpeedNews

Portal informasi wilayah.

## Kategori

* Berita
* Pengumuman
* Event
* Cuaca
* Darurat

---

## Artikel

Field:

* title
* slug
* thumbnail
* content
* category_id
* region_id
* published_at
* status

Status:

* draft
* published

---

## Fitur Berita

* pencarian
* filter wilayah
* filter kategori
* artikel terkait
* artikel populer

---

## Emergency Alert

Admin dapat membuat alert darurat.

Tampilkan:

* banner merah
* popup
* notification

---

# Notifikasi Sistem

Gunakan Laravel Database Notification.

Trigger:

* booking antrean berhasil
* antrean dipanggil
* status laporan berubah
* SOS diterima
* berita penting
* emergency alert

---

# Wilayah

Master wilayah:

## Provinsi

* id
* nama

## Kabupaten

* province_id
* nama

## Kecamatan

* district_id
* nama

Semua modul mendukung filter wilayah.

---

# Admin Panel

Menu:

Dashboard

User Management

SpeedQ

* Instansi
* Layanan
* Slot
* Antrean

SpeedReport

* Daftar Laporan
* Kategori

SpeedSOS

* Monitoring SOS

SpeedNews

* Artikel
* Kategori
* Emergency Alert

Statistik

Activity Log

Settings

---

# Search

Global Search.

Cari:

* instansi
* berita
* laporan
* antrean

---

# Activity Log

Gunakan:

* spatie/laravel-activitylog

Catat:

* login
* logout
* create
* update
* delete
* perubahan status

---

# Security

Gunakan:

* CSRF Protection
* XSS Protection
* Form Request Validation
* Authorization Policy
* Rate Limiting
* Password Hashing
* File Validation
* MIME Validation

Sanitize semua input.

---

# Performance

Target:

* response < 500ms
* support 500 concurrent users
* pagination
* eager loading
* caching untuk statistik

---

# Database Structure

users

regions
districts
subdistricts

institutions
services
service_slots
queue_tickets

report_categories
reports
report_media
report_comments

emergency_contacts
sos_requests

news_categories
news_posts

notifications

activity_logs

---

# Realtime Strategy

Gunakan AJAX Polling untuk MVP.

SpeedQ:

* polling 10 detik

SpeedReport:

* polling 15 detik

SpeedSOS:

* polling 5 detik

Notifikasi:

* polling 10 detik

Jangan menggunakan websocket pada MVP.

---

# Scheduler

Buat Laravel Scheduler.

## Queue Cleanup

Membersihkan antrean lama.

## Notification Cleanup

Membersihkan notifikasi lama.

## Activity Cleanup

Membersihkan log lama.

## Publish News

Publish artikel terjadwal.

---

# UI/UX

Style:

* Modern Government Technology
* Clean
* Responsive
* Mobile First

Warna:

Primary:
#1677FF

Secondary:
#00B4D8

Success:
#22C55E

Danger:
#EF4444

Dark:
#0F172A

---

# Code Style

* Clean Architecture
* Service Layer
* Eloquent Relationship
* Form Request Validation
* Resource API
* Reusable Blade Components

---

# Deliverables

Generate:

* Migration
* Seeder
* Factory
* Model
* Controller
* Middleware
* Policy
* Service Layer
* Notification
* Scheduler
* Routes
* Blade Views
* Tailwind Components
* Dashboard Admin
* Dashboard User
* RBAC Configuration

Tujuan:

Aplikasi harus siap dijalankan sebagai platform layanan publik digital terintegrasi yang mencakup SpeedQ, SpeedReport, SpeedSOS, dan SpeedNews dengan role Admin dan User.