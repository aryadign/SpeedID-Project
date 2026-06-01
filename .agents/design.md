---

version: 1.0
name: Speed ID - Civic Precision
description: Premium Smart City Public Service Platform for Indonesia. Modern government-tech design with civic trust, accessibility, transparency, and speed.

colors:
primary: "#2563EB"
secondary: "#081C3A"
tertiary: "#00B4D8"
success: "#22C55E"
warning: "#F59E0B"
danger: "#EF4444"
surface: "#F8FAFC"
surface-alt: "#FFFFFF"
border: "#E2E8F0"
text-primary: "#0F172A"
text-secondary: "#64748B"
text-muted: "#94A3B8"
on-primary: "#FFFFFF"

typography:
display:
fontFamily: Outfit
fontSize: 4rem
fontWeight: 700
letterSpacing: "-0.03em"

```
h1:
    fontFamily: Outfit
    fontSize: 3rem
    fontWeight: 700

h2:
    fontFamily: Outfit
    fontSize: 2.25rem
    fontWeight: 700

h3:
    fontFamily: Outfit
    fontSize: 1.5rem
    fontWeight: 600

body:
    fontFamily: Outfit
    fontSize: 1rem
    lineHeight: 1.7

small:
    fontFamily: Outfit
    fontSize: 0.875rem

label:
    fontFamily: Outfit
    fontSize: 0.875rem
    fontWeight: 500
```

rounded:
sm: 10px
md: 16px
lg: 20px
xl: 28px
full: 9999px

spacing:
xs: 4px
sm: 8px
md: 16px
lg: 24px
xl: 32px
xxl: 64px

shadow:
sm: "0 4px 12px rgba(15,23,42,0.05)"
md: "0 10px 30px rgba(15,23,42,0.08)"
lg: "0 20px 40px rgba(15,23,42,0.12)"

components:

```
button-primary:
    backgroundColor: "{colors.primary}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.full}"
    padding: "12px 24px"

button-secondary:
    backgroundColor: "{colors.surface-alt}"
    textColor: "{colors.text-primary}"
    border: "1px solid {colors.border}"
    rounded: "{rounded.full}"
    padding: "12px 24px"

button-danger:
    backgroundColor: "{colors.danger}"
    textColor: "{colors.on-primary}"
    rounded: "{rounded.full}"
    padding: "12px 24px"

card:
    backgroundColor: "{colors.surface-alt}"
    textColor: "{colors.text-primary}"
    rounded: "{rounded.lg}"
    shadow: "{shadow.md}"
    padding: "24px"

stat-card:
    backgroundColor: "{colors.surface-alt}"
    rounded: "{rounded.lg}"
    shadow: "{shadow.sm}"
    padding: "24px"

glass-card:
    backgroundColor: "rgba(255,255,255,0.7)"
    backdropFilter: "blur(20px)"
    border: "1px solid rgba(255,255,255,0.3)"
    rounded: "{rounded.lg}"

input:
    backgroundColor: "#FFFFFF"
    border: "1px solid {colors.border}"
    rounded: "{rounded.md}"
    padding: "12px 16px"
```

---

# Overview

Speed ID menggunakan filosofi desain **Civic Precision**.

Desain harus memberikan kesan:

* Cepat
* Profesional
* Modern
* Terpercaya
* Transparan
* Premium Government Technology

UI harus terasa seperti gabungan:

* GovTech Singapore
* Stripe Dashboard
* Linear
* Vercel
* Notion

Namun tetap ramah untuk masyarakat umum Indonesia.

---

# Core Design Principles

## 1. Trust First

Setiap layar harus menumbuhkan rasa percaya.

Gunakan:

* ruang kosong yang cukup
* hierarki visual jelas
* ikon yang konsisten
* status yang mudah dipahami

Jangan membuat UI yang ramai.

---

## 2. Information Clarity

Data layanan publik harus mudah dibaca.

Prioritaskan:

* statistik
* status layanan
* progress
* timeline

---

## 3. Mobile First

Mayoritas pengguna berasal dari smartphone.

Desain dimulai dari:

* Mobile
* Tablet
* Desktop

Urutan tersebut wajib.

---

## 4. Accessibility

Pastikan:

* kontras WCAG compliant
* ukuran klik minimal 44px
* keyboard accessible
* screen-reader friendly

---

# Layout Rules

## Container

Gunakan:

* max-width: 1280px
* center aligned

---

## Section Spacing

Desktop:

80px - 120px

Tablet:

64px - 80px

Mobile:

48px - 64px

---

## Grid

Gunakan:

* 12 kolom desktop
* 6 kolom tablet
* 1 kolom mobile

---

# Navigation

## Navbar

Tinggi:

80px

Isi:

* Logo Speed ID
* Home
* Layanan
* Tentang
* Kontak
* Login
* Register

Navbar harus:

* sticky
* blur background
* transparan saat top
* solid saat scroll

---

# Landing Page

## Hero Section

Layout:

Kiri:

* Judul
* Deskripsi
* CTA

Kanan:

* Hero image

Gunakan overlay gelap pada gambar.

---

## Statistics Section

Tampilkan:

* Total Pengguna
* Total Instansi
* Tingkat Kepuasan
* Waktu Respon

Gunakan stat card besar.

---

## Service Section

4 kartu utama:

### SpeedQ

Icon:
Ticket

Warna:
Primary Blue

---

### SpeedReport

Icon:
Triangle Alert

Warna:
Orange

---

### SpeedSOS

Icon:
Shield Alert

Warna:
Danger Red

---

### SpeedNews

Icon:
Newspaper

Warna:
Cyan

---

Setiap card memiliki:

* Icon besar
* Judul
* Deskripsi
* Hover animation

---

# Dashboard Design

Dashboard mengikuti pola SaaS modern.

Layout:

Sidebar kiri
Content kanan

---

# Sidebar

Lebar:

280px

Style:

* Dark Navy
* Fixed
* Collapsible

---

# Dashboard Cards

Gunakan:

* Rounded 20px
* Soft shadow
* White background

---

# SpeedQ Design

Gunakan warna dominan:

Primary Blue

Fokus:

* QR Ticket
* Nomor antrean
* Estimasi waktu

Komponen utama:

* Queue Card
* Progress Card
* Slot Calendar
* QR Ticket

QR Ticket harus terlihat seperti boarding pass modern.

---

# SpeedReport Design

Gunakan warna dominan:

Warning Orange

Komponen:

* Report Form
* Upload Area
* Status Timeline

Timeline wajib visual dan mudah dipahami.

Status:

* Terkirim
* Diverifikasi
* Diproses
* Selesai
* Ditolak

---

# SpeedSOS Design

Modul paling kritis.

Gunakan:

* Danger Red
* Kontras tinggi

Tombol SOS:

* Lingkaran besar
* Diameter minimal 220px desktop
* Diameter minimal 180px mobile

Animasi:

* Pulse effect
* Glow effect

Harus menjadi fokus utama layar.

---

# SpeedNews Design

Gunakan:

* White background
* Newspaper style modern

Card berita:

* Thumbnail besar
* Judul
* Ringkasan
* Tanggal

---

# Status System

## Success

Color:

#22C55E

Digunakan untuk:

* antrean berhasil
* laporan selesai

---

## Warning

Color:

#F59E0B

Digunakan untuk:

* antrean menunggu
* laporan diproses

---

## Danger

Color:

#EF4444

Digunakan untuk:

* SOS
* error
* alert

---

## Info

Color:

#2563EB

Digunakan untuk:

* notifikasi
* informasi umum

---

# Animations

Gunakan animasi ringan.

Durasi:

150ms - 300ms

Gunakan:

* fade
* slide up
* scale hover

Hindari:

* animasi berlebihan
* efek bouncing

---

# Icons

Gunakan Lucide Icons.

Konsisten di seluruh aplikasi.

---

# Do's

* Gunakan banyak whitespace.
* Gunakan card modern.
* Gunakan rounded besar.
* Gunakan visual hierarchy yang jelas.
* Prioritaskan mobile usability.
* Pertahankan identitas Civic Blue.

---

# Don'ts

* Jangan gunakan warna neon.
* Jangan gunakan banyak gradient.
* Jangan gunakan shadow berat.
* Jangan gunakan card tajam tanpa rounded.
* Jangan membuat dashboard terlihat seperti sistem lama pemerintahan.
* Jangan mencampur banyak warna aksen dalam satu layar.

---

# Final Goal

Saat pengguna membuka Speed ID, kesan pertama yang harus muncul adalah:

"Ini adalah platform layanan publik Indonesia yang modern, cepat, profesional, dan setara dengan produk digital kelas dunia."