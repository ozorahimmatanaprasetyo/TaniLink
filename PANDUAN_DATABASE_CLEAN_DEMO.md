# PANDUAN PENGELOLAAN DATABASE CLEAN & DEMO TANILINK

Dokumen teknis ini menjelaskan cara mengoperasikan dua kondisi database pada platform TaniLink (**Clean** dan **Demo**) tanpa membuat dua aplikasi web terpisah.

---

## 1. Arsitektur Pemisahan Database

Pemisahan data dilakukan secara aman pada tingkat database MySQL lokal:
- **`tanilink_clean`**: Database kosong standar produksi untuk instalasi awal.
- **`tanilink_demo`**: Database contoh dengan data realistis, komprehensif, dan saling terhubung untuk pengujian fitur & presentasi klien.
- **`tanilink`**: Database kerja aktif default (backup otomatis tersimpan di `database/backup_tanilink_original.sql`).

---

## 2. Cara Beralih Antara Mode Clean dan Demo

Untuk beralih kondisi, cukup ubah variabel `DB_DATABASE` pada file `.env` di direktori utama:

### A. Menjalankan Mode CLEAN (Baru / Siap Rilis)
Ubah baris database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tanilink_clean
DB_USERNAME=root
DB_PASSWORD=
```
Lalu bersihkan cache konfigurasi Laravel:
```bash
php artisan config:clear
php artisan cache:clear
```

### B. Menjalankan Mode DEMO (Presentasi / Pengujian Lengkap)
Ubah baris database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tanilink_demo
DB_USERNAME=root
DB_PASSWORD=
```
Lalu bersihkan cache konfigurasi Laravel:
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 3. Cara Menjalankan Migrasi & Seeder Ulang (Jika Diperlukan)

Kedua database telah memiliki skema tabel terbaru dan data awal. Jika sewaktu-waktu ingin melakukan *re-seed* secara aman:

### Menjalankan Seeder Clean:
```bash
php artisan db:seed --class=CleanSeeder
```
*(Hanya memastikan akun Admin utama dan konfigurasi dasar tersedia tanpa membuat data transaksi).*

### Menjalankan Seeder Demo:
```bash
php artisan db:seed --class=DemoSeeder
```
*(Menambahkan/memperbarui 5 akun petani, 5 konsumen, 12 komoditas, 15 batch panen, 25 pesanan, dokumentasi tanaman, dan riwayat aktivitas secara idempoten tanpa duplikasi).*

---

## 4. Kredensial Akun Pengujian (Khusus Mode Demo)

Seluruh password akun demo adalah: **`password123`** (khusus Admin: **`admintanilink`**)

| Peran | Nama | Email | Organisasi |
|---|---|---|---|
| **Admin** | Admin TaniLink | `admin@tanilink.id` | TaniLink Central Admin |
| **Petani 1** | Pak Joko Widodo Subroto (Demo) | `petani.joko@example.test` | Kelompok Tani Makmur Bersama |
| **Petani 2** | Ibu Siti Aminah Lestari (Demo) | `petani.siti@example.test` | Gapoktan Sumber Berkah Organik |
| **Petani 3** | Pak Wayan Sudarma (Demo) | `petani.wayan@example.test` | Subak Tani Subur Alami |
| **Petani 4** | Pak Asep Sunandar (Demo) | `petani.asep@example.test` | Koperasi Tani Parahyangan Asri |
| **Petani 5** | Pak Budi Hartono Sejati (Demo) | `petani.budi@example.test` | Tani Lestari Unggul Bersatu |
| **Konsumen 1** | Restoran Selera Nusantara (Demo) | `horeka.nusantara@example.test` | PT Kuliner Cita Rasa Sejati |
| **Konsumen 2** | Ibu Ratna Kumalasari (Demo) | `konsumen.ratna@example.test` | Dapur Sehat Rumahan |
| **Konsumen 3** | Supermarket Segar Mart (Demo) | `retail.segarmart@example.test` | CV Retail Agro Makmur |
| **Konsumen 4** | Katering Berkah Sedap (Demo) | `katering.berkah@example.test` | Usaha Katering Keluarga Mandiri |
| **Konsumen 5** | Hotel Grand Harmoni Malang (Demo) | `hotel.grandharmoni@example.test` | Divisi Pengadaan Horeka |

---

## 5. Prosedur Keamanan & Backup Data

Sebelum melakukan perubahan skema atau eksperimen baru:
1. **Backup Database MySQL:**
   ```bash
   mysqldump -u root tanilink_clean > database/backup_clean.sql
   mysqldump -u root tanilink_demo > database/backup_demo.sql
   ```
2. Jangan pernah menjalankan `migrate:fresh` atau `db:wipe` pada database yang sedang aktif digunakan untuk data penting.
3. Seluruh berkas `.sql` dan `.env.*` telah dikecualikan oleh `.gitignore` sehingga aman dari kebocoran repositori Git.
