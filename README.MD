# 🛡️ Keamanan Perangkat Lunak - Manajemen Artikel

Selamat datang di repositori _Manajemen Artikel_! 🚀

## 📌 Deskripsi

Proyek ini adalah sistem manajemen artikel yang dibuat menggunakan PHP tanpa framework. Sistem ini dirancang dengan fitur keamanan yang memastikan hanya penulis terdaftar yang dapat menerbitkan, mengubah, dan menarik kembali artikel yang telah dipublikasikan.

## 🎯 Fitur Utama

✅ **Penerbitan Artikel** - Penulis dapat membuat dan menerbitkan artikel dengan informasi lengkap (judul, gambar, isi, kata kunci, dll.)

✅ **Pengeditan Artikel** - Penulis dapat memperbarui artikel yang telah diterbitkan dengan riwayat revisi yang tercatat.

✅ **Penarikan Artikel** - Artikel yang telah diterbitkan dapat ditarik kembali dan tidak dapat diakses oleh pembaca umum.

✅ **Komentar Pembaca** - Pembaca dapat memberikan komentar pada artikel yang telah dipublikasikan.

## 🔒 Keamanan

🔹 Hanya penulis terdaftar yang dapat menerbitkan artikel.

🔹 Hanya penulis artikel yang berhak mengedit dan menarik artikel yang telah diterbitkan.

🔹 Riwayat revisi dan penarikan artikel tercatat dengan jelas.

## 🛠️ Teknologi yang Digunakan

- **PHP (Tanpa Framework)** - Untuk pengembangan backend
- **MySQL** - Untuk penyimpanan data
- **HTML, CSS, JavaScript** - Untuk tampilan antarmuka pengguna
- **Laragon** - Untuk lingkungan pengembangan lokal

## 🚀 Cara Menjalankan Proyek

1. **Clone Repository**
   ```sh
   git clone https://git.unpas.dev/dhikaid/KPL2025.git
   ```
2. **Konfigurasi Database**

   - Buat database baru di MySQL
   - Import file `database.sql` ke dalam database
   - Sesuaikan konfigurasi database di `config.php`

3. **Menjalankan Proyek dengan Laragon**
   - Pastikan Laragon sudah terinstal
   - Pindahkan proyek ke folder `www` di Laragon
   - Jalankan Laragon dan aktifkan Apache & MySQL
   - Akses aplikasi di [http://localhost](http://localhost)

## 📜 Lisensi

Proyek ini dirilis di bawah lisensi **MIT**. Silakan gunakan dan kembangkan sesuai kebutuhan! 🚀

## 👥 Kontribusi

Kami menerima kontribusi dari siapa saja! Jika ingin berkontribusi:

1. Fork repositori ini.
2. Buat branch baru (`git checkout -b fitur-baru`).
3. Lakukan perubahan dan commit (`git commit -m 'Menambahkan fitur X'`).
4. Push ke branch (`git push origin fitur-baru`).
5. Ajukan pull request.

## 💌 Hubungi Kami

Jika ada pertanyaan atau masukan, jangan ragu untuk menghubungi kami melalui [email@example.com](mailto:email@example.com) atau buat _issue_ di GitHub.

---

✨ **Selamat Coding!** ✨
