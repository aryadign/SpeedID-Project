# Task Instruction untuk OpenCode

## Proyek: Speed ID - Smart Public Service Platform

Saya telah menyimpan:

* Spesifikasi lengkap proyek → `prd.md`
* Panduan Design & UI/UX → `design.md`

---

# Instruksi Utama

Selalu baca dan ikuti file `prd.md` dan `design.md` sebelum mengerjakan task apapun.

## Aturan Penting

### PRD Priority

* Semua fitur, database, relasi, role, workflow, endpoint, dan business logic harus mengikuti `prd.md`.
* Jangan membuat fitur di luar PRD tanpa persetujuan.
* Jika menemukan konflik antara implementasi dan PRD, ikuti PRD.

### Design Priority

* Semua UI wajib mengikuti `design.md`.
* Ikuti warna, typography, spacing, layout, komponen, dan design system yang telah ditentukan.
* Jangan membuat desain baru yang tidak sesuai dengan design guide.

### Development Rules

* Gunakan Laravel 13.
* Gunakan PHP 8.4.
* Gunakan MySQL.
* Gunakan TailwindCSS.
* Gunakan Alpine.js untuk interaktivitas ringan.
* Gunakan Vanilla JavaScript untuk fitur frontend tambahan.
* Gunakan Eloquent Relationship.
* Gunakan Form Request Validation.
* Gunakan Service Layer untuk business logic.
* Gunakan clean code dan struktur folder yang rapi.
* Hindari duplicate code.
* Gunakan reusable component.

---

# Struktur Task Checklist

Gunakan kategori berikut.

---

# 1. Project Setup & Configuration

## 1.1 Inisialisasi Project Laravel

* Install Laravel 13
* Setup environment
* Setup database connection
* Setup Breeze Authentication

## 1.2 Frontend Setup

* Install TailwindCSS
* Setup Alpine.js
* Setup Vite
* Setup reusable layout

## 1.3 Package Installation

Install dan konfigurasi:

* spatie/laravel-permission
* spatie/laravel-activitylog
* simplesoftwareio/simple-qrcode
* intervention/image
* laravel/sanctum (persiapan API)
* leaflet integration

## 1.4 Base Architecture

* Service Layer
* Policies
* Middleware
* Notification Structure
* Shared Components

---

# 2. Database & Migration

## 2.1 Authentication

* users table

## 2.2 Region Management

* provinces
* districts
* subdistricts

## 2.3 Role & Permission

* roles
* permissions

## 2.4 SpeedQ

* institutions
* services
* service_slots
* queue_tickets

## 2.5 SpeedReport

* report_categories
* reports
* report_media
* report_comments

## 2.6 SpeedSOS

* emergency_contacts
* sos_requests

## 2.7 SpeedNews

* news_categories
* news_posts

## 2.8 System

* notifications
* activity_logs

## 2.9 Seeder

* roles
* admin account
* sample regions
* sample institutions
* sample categories

---

# 3. Models & Relationships

## 3.1 User

Relationship:

* role
* queue tickets
* reports
* sos requests
* emergency contacts

## 3.2 SpeedQ Models

* Institution
* Service
* ServiceSlot
* QueueTicket

## 3.3 SpeedReport Models

* Report
* ReportMedia
* ReportComment
* ReportCategory

## 3.4 SpeedSOS Models

* SOSRequest
* EmergencyContact

## 3.5 SpeedNews Models

* NewsPost
* NewsCategory

## 3.6 Region Models

* Province
* District
* Subdistrict

---

# 4. Authentication & Authorization

## 4.1 Authentication

* Register
* Login
* Logout
* Forgot Password
* Email Verification

## 4.2 Roles

Role:

* Admin
* User

## 4.3 Authorization

Policies:

* ReportPolicy
* QueuePolicy
* NewsPolicy
* SOSPolicy

---

# 5. Backend Logic & Business Features

## 5.1 Dashboard

### User Dashboard

* Summary card
* Active queue
* Recent reports
* SOS history
* Latest news

### Admin Dashboard

* Statistics
* Charts
* Activity overview

---

## 5.2 SpeedQ

### Institution Management

CRUD institution

### Service Management

CRUD services

### Slot Management

CRUD service slots

### Queue Booking

* Create booking
* Generate queue number
* Generate booking code
* Generate QR ticket
* Estimate service time

### Queue Monitoring

* Current queue
* Queue status
* Queue progress

### Queue Administration

* Call queue
* Skip queue
* Complete queue
* Cancel queue

---

## 5.3 SpeedReport

### Report Submission

* Create report
* Upload media
* Anonymous report

### Report Tracking

Status:

* terkirim
* diverifikasi
* diproses
* selesai
* ditolak

### Report Comments

Admin comment system

### Report Timeline

Track all status changes

---

## 5.4 SpeedSOS

### Emergency Request

* Create SOS request
* Capture location
* Store coordinates

### Emergency Contacts

CRUD emergency contacts

### SOS Monitoring

* Active SOS list
* Status tracking
* Map display

---

## 5.5 SpeedNews

### Category Management

CRUD categories

### News Management

CRUD articles

### Publishing System

* Draft
* Published

### Emergency Alert

* Create alert
* Broadcast alert

---

# 6. Security

## 6.1 Validation

* Form Request Validation
* Server-side validation

## 6.2 Protection

* CSRF Protection
* XSS Protection
* SQL Injection Prevention

## 6.3 Upload Security

* MIME validation
* File size validation
* Image validation

## 6.4 Authorization

* Policy validation
* Permission validation

## 6.5 Rate Limiting

Implement rate limiting pada:

* Login
* Register
* Report submission
* SOS request

---

# 7. Frontend & Blade Views

## 7.1 Public Pages

* Landing Page
* Login
* Register
* Forgot Password

## 7.2 User Pages

* Dashboard
* SpeedQ
* SpeedReport
* SpeedSOS
* SpeedNews
* Profile

## 7.3 Admin Pages

* Dashboard
* User Management
* Institution Management
* Queue Management
* Report Management
* SOS Monitoring
* News Management
* Settings

Semua halaman wajib mengikuti design.md.

---

# 8. JavaScript & Realtime Features

## 8.1 Notification Polling

Polling notifikasi setiap 10 detik.

## 8.2 Queue Monitoring

Polling antrean setiap 10 detik.

## 8.3 SOS Monitoring

Polling SOS setiap 5 detik.

## 8.4 Report Tracking

Polling status laporan setiap 15 detik.

## 8.5 Chart Dashboard

Gunakan Chart.js.

---

# 9. Additional Features

## 9.1 QR Ticket

Generate QR untuk antrean.

## 9.2 Map Integration

Leaflet + OpenStreetMap.

## 9.3 Activity Log

Catat semua aktivitas penting.

## 9.4 Notification System

Database notification.

## 9.5 Search

Global search.

## 9.6 Emergency Alert

Banner + popup notification.

---

# 10. Scheduler & Background Tasks

## 10.1 Queue Cleanup

Membersihkan antrean lama.

## 10.2 Notification Cleanup

Membersihkan notifikasi lama.

## 10.3 Activity Cleanup

Membersihkan log lama.

## 10.4 News Scheduler

Publish artikel terjadwal.

---

# 11. Testing

## 11.1 Feature Test

* Authentication
* Queue
* Report
* SOS
* News

## 11.2 Validation Test

* Form validation
* File upload validation

## 11.3 Permission Test

* Admin access
* User access

---

# 12. Final Polish & Bug Fixes

## 12.1 UI Consistency

Pastikan seluruh halaman mengikuti design.md.

## 12.2 Responsive Testing

* Mobile
* Tablet
* Desktop

## 12.3 Performance Optimization

* Pagination
* Eager Loading
* Query Optimization

## 12.4 Final Review

* Bug fixing
* Cleanup code
* Remove unused files

---

# Aturan Wajib Update Tasklist (PENTING)

Setiap kali selesai mengerjakan satu task atau satu grup task, WAJIB melakukan langkah berikut sebelum melapor:

## 1. Update File

`.agents/tasklist.md`

## 2. Checklist Progress

Task selesai wajib diubah menjadi:

* [x] ✅ Task selesai

## 3. Update Progress

Contoh:

Progress: 35%

Progress: 62%

Progress: 100%

## 4. Tambahkan Catatan

Setiap task selesai harus memiliki catatan singkat:

Contoh:

* [x] ✅ Task 2.4 - Membuat Migration SpeedQ `[Mudah]` (Selesai)

Catatan:

* Membuat migration institutions
* Membuat migration services
* Membuat migration service_slots
* Membuat migration queue_tickets

## 5. Jangan Pernah Mengklaim Selesai

Jangan mengatakan task selesai jika:

* file belum dibuat
* migration belum dibuat
* controller belum dibuat
* fitur belum berjalan

Pastikan implementasi benar-benar selesai sebelum checklist diubah menjadi [x].

---

# Workflow Kerja

Urutan pengerjaan wajib:

1. Project Setup
2. Database
3. Models
4. Authentication
5. Backend Logic
6. Security
7. Frontend
8. Realtime Features
9. Additional Features
10. Scheduler
11. Testing
12. Final Polish

Jangan melompati tahapan tanpa alasan yang jelas.