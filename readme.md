# 🎮 Sistem Pencatatan Omset Rental PS & FnB

Aplikasi web berbasis **Serverless** untuk mencatat pemasukan harian usaha Rental PlayStation dan penjualan Makanan/Minuman (FnB). Aplikasi ini terintegrasi langsung dengan **Google Sheets** sebagai database, sehingga pemilik usaha dapat memantau laporan secara *real-time* dari mana saja.

![App Screenshot](https://via.placeholder.com/800x400?text=Preview+Aplikasi+Rental+PS)
*(Ganti link gambar di atas dengan screenshot aplikasi kamu sendiri jika ada)*

## ✨ Fitur Unggulan

* **📱 Mobile-First Design:** Tampilan responsif dan nyaman digunakan di HP (Sticky Header & Footer).
* **🔐 Multi-User Login:**
    * **Staff:** Hanya bisa input data.
    * **Owner:** Bisa input dan melihat/mengedit laporan.
* **📝 Input Detail:** Memisahkan omset Rental PS vs FnB, serta pembayaran Tunai vs QRIS.
* **⚡ Auto Calculate:** Menghitung total omset secara otomatis saat mengetik.
* **📊 Laporan Real-time:** Halaman rekapitulasi data yang mengambil data langsung dari Google Sheets.
* **✏️ CRUD System:** Fitur Edit dan Hapus data langsung dari aplikasi web (Khusus Owner).
* **🕵️ Audit Trail:** Mencatat *username* karyawan yang menginput data ke database.
* **🧾 Struk Digital:** Menampilkan ringkasan sukses setelah input data.

---

## 🛠️ Teknologi yang Digunakan

* **Frontend:** HTML5, CSS3, Bootstrap 5, Vanilla JavaScript.
* **Backend:** Google Apps Script (GAS).
* **Database:** Google Sheets.
* **Alerts:** SweetAlert2.

---

## 🚀 Panduan Instalasi (Setup)

Ikuti langkah ini untuk menghubungkan aplikasi dengan Google Sheets kamu sendiri.

### Langkah 1: Siapkan Google Sheets
1.  Buat Google Sheet baru.
2.  Beri nama Sheet (Tab) di bawah dengan nama: **Data**.
3.  Buat Header di Baris 1 (Kolom A - H) dengan urutan wajib berikut:
    `Tanggal` | `Total PS` | `PS Cash` | `PS QRIS` | `Total FNB` | `FNB Cash` | `FNB QRIS` | `USER`

### Langkah 2: Setup Google Apps Script (Backend)
1.  Di Google Sheets, klik menu **Ekstensi (Extensions)** > **Apps Script**.
2.  Hapus kode yang ada, copy-paste kode backend (`doPost` & `doGet`) yang sudah dibuat.
3.  Klik **Terapkan (Deploy)** > **Deployment Baru**.
4.  Pilih jenis: **Aplikasi Web**.
5.  Konfigurasi:
    * **Jalankan sebagai:** Saya (Email Kamu).
    * **Yang memiliki akses:** Siapa saja (Anyone). ⚠️ *Wajib pilih ini!*
6.  Salin **URL Web App** yang muncul (berakhiran `/exec`).

### Langkah 3: Konfigurasi Frontend
1.  Buka file `input.html` dan `rekap.html` di repository ini.
2.  Cari variabel `scriptURL`.
3.  Ganti dengan URL Web App yang kamu salin tadi.
    ```javascript
    const scriptURL = '[https://script.google.com/macros/s/...../exec](https://script.google.com/macros/s/...../exec)';
    ```
4.  Commit perubahan ke GitHub.

---

## 👤 Akun Pengguna (Default)

Berikut adalah akun bawaan yang diatur di dalam `index.html`. Silakan ubah kodenya jika ingin mengganti password.

| Role | Username | Password | Akses |
| :--- | :--- | :--- | :--- |
| **Owner** | `owner` | `admin` | Input Data + Akses Penuh Rekap |
| **Staff Pagi** | `pagi` | `123` | Input Data Saja |
| **Staff Malam** | `malam` | `123` | Input Data Saja |

**Password Tambahan:**
Untuk membuka halaman Rekapitulasi (`rekap.html`), diperlukan password kedua (Hardcoded):
* **Password Rekap:** `ADMIN123`
*(Jika login menggunakan akun 'owner', password ini tidak diminta)*.

---

## 📂 Struktur File

* `index.html` : Halaman Login (Gatekeeper).
* `input.html` : Formulir input setoran harian.
* `rekap.html` : Halaman admin untuk melihat, mengedit, dan menghapus data history.

---

## 🤝 Kontribusi

Project ini bebas untuk dikembangkan. Jika ingin mengubah fitur:
1.  Fork repository ini.
2.  Lakukan perubahan pada file HTML/JS.
3.  Jangan lupa update kode Apps Script jika kamu mengubah struktur data yang dikirim.

---

**Dibuat dengan ❤️ untuk kemudahan pembukuan UMKM.**
