# Portofolio Guru (Laravel 12 + MySQL)

Website portofolio pribadi untuk guru, lengkap dengan panel admin (CMS) untuk mengisi semuanya tanpa menyentuh kode. Cocok untuk kebutuhan PKG, pengajuan kenaikan pangkat, seleksi guru berprestasi, Guru Penggerak, atau sekadar menjadi "kartu nama digital" yang bisa dibagikan ke siswa, orang tua, dan rekan sejawat.

## Fitur

**Situs publik**
- Beranda: perkenalan, angka singkat (lama mengajar, siswa dibimbing, karya, prestasi), kompetensi, karya terbaru, prestasi, artikel, testimoni, ajakan kolaborasi
- Profil: tentang saya, riwayat pendidikan & pengalaman (garis waktu), kompetensi per kelompok, daftar pelatihan dengan total JP, unduh CV PDF
- Karya & publikasi (PTK, artikel jurnal, buku, modul ajar, media pembelajaran, best practice) dengan filter jenis, halaman detail, dan unduhan berkas
- Bahan ajar untuk siswa: filter kelas, pencarian, unduh materi, tautan video
- Prestasi pribadi & siswa bimbingan dikelompokkan per tahun
- Galeri dengan filter album dan tampilan foto besar (lightbox)
- Artikel/blog dengan kategori, jadwal terbit, dan hitungan dibaca
- Formulir kontak (anti-spam honeypot + batas kiriman) dan tombol WhatsApp
- SEO: judul & deskripsi per halaman, Open Graph untuk pratinjau saat dibagikan, `sitemap.xml`, `robots.txt`

**Panel admin** (`/admin`)
- Dasbor: jumlah karya & bahan ajar, total unduhan, pesan belum dibaca, karya paling banyak diunduh, artikel terpopuler, checklist kelengkapan portofolio
- Kelola 10 jenis konten: karya, bahan ajar, artikel, prestasi, galeri, testimoni, pendidikan, pengalaman, sertifikat, kompetensi
- Editor Markdown dengan tombol **Pratinjau**
- Unggah sampul & berkas (PDF/Word/PowerPoint/Excel/ZIP), unggah banyak foto galeri sekaligus
- Tampilkan/sembunyikan konten dengan satu klik, pencarian di setiap daftar
- Profil & tampilan: foto, CV, kontak, media sosial, **6 pilihan warna aksen**, menu yang ditampilkan
- Kotak pesan masuk dari formulir kontak, balas lewat email
- Ganti kata sandi

**Keamanan**: CSRF di semua form, pembatasan percobaan login, validasi berbahasa Indonesia, Markdown aman (HTML/skrip dibuang), jenis & ukuran berkas dibatasi, berkas unduhan disimpan di folder privat dan hanya keluar lewat tautan unduh, header keamanan (X-Frame-Options, nosniff, Referrer-Policy), folder internal & `.env` tidak bisa diakses dari browser.

Tampilan responsif, font Plus Jakarta Sans + Montserrat Bold, ikon garis, tanpa CDN.

## Kebutuhan

- PHP 8.2 atau lebih baru (XAMPP 8.2+ / Laragon) dengan ekstensi pdo_mysql, fileinfo, mbstring
- MySQL 5.7+ / MariaDB 10.4+
- [Composer](https://getcomposer.org)

## Cara install (XAMPP / Laragon di Windows)

1. Ekstrak ZIP lalu pindahkan folder `portofolio-guru` ke `C:\xampp\htdocs\` (atau `C:\laragon\www\`).
2. Nyalakan **Apache** dan **MySQL** dari XAMPP Control Panel.
3. Klik dua kali **`setup.bat`** (atau buka *Shell* XAMPP, masuk ke folder proyek, ketik `setup.bat`). Script ini memasang dependensi, membuat database `portofolio_guru`, tabel, dan data contoh.
4. Buka **http://localhost/portofolio-guru** untuk situs, dan **http://localhost/portofolio-guru/admin** untuk panel admin:
   - Username `admin`, kata sandi `admin123` (segera ganti di menu **Profil & tampilan**)

Linux/macOS: jalankan `bash setup.sh`, lalu `php artisan serve` dan buka http://127.0.0.1:8000.

User/password MySQL berbeda? Ubah `DB_USERNAME` dan `DB_PASSWORD` di file `.env` lalu jalankan setup lagi.

## Mulai mengisi

1. **Profil & tampilan**: ganti nama, foto, bio, kontak, warna, lalu unggah CV.
2. Hapus data contoh satu per satu di tiap menu, lalu isi dengan data Anda (atau ubah saja yang sudah ada).
3. Checklist di **Dasbor** membantu memastikan portofolio sudah lengkap.

## Pasang di hosting

Arahkan document root ke folder `public/` (atau unggah ke `public_html` dengan pola folder yang sama), ubah `APP_URL` di `.env`, pastikan `APP_ENV=production` dan `APP_DEBUG=false`, lalu jalankan `php artisan migrate --force` (tanpa `--seed` bila tidak ingin data contoh). Folder `storage/` dan `public/uploads/` harus bisa ditulis.

## Struktur singkat

- `app/Support/Konten.php`: definisi semua jenis konten. Panel admin dibuat otomatis dari daftar ini, jadi menambah jenis konten baru cukup satu entri + tabel.
- `app/Support/Situs.php`: pengaturan situs & profil (tabel `pengaturan`)
- `app/Http/Controllers/SitusController.php`: halaman publik; `Admin/*`: panel admin
- `resources/views/situs`, `resources/views/admin`: tampilan; `public/css`: CSS tanpa framework
- `database/seeders`: data contoh guru Biologi beserta gambar & berkas contoh
- `index.php` + `.htaccess` di folder utama: agar bisa dibuka langsung dari `htdocs` tanpa `/public`
