<?php

namespace App\Support;

/**
 * Definisi semua jenis konten portofolio. Admin CRUD dibangun otomatis dari daftar ini:
 * menambah jenis konten baru cukup menambah satu entri di sini + tabelnya.
 *
 * Tipe field: text, textarea, markdown, number, select, date, url, gambar, berkas, cek
 */
class Konten
{
    public const JENIS_KARYA = ['Penelitian Tindakan Kelas', 'Artikel Jurnal', 'Buku', 'Modul Ajar', 'Media Pembelajaran', 'Best Practice'];
    public const TINGKAT = ['Sekolah', 'Kecamatan', 'Kabupaten/Kota', 'Provinsi', 'Nasional', 'Internasional'];

    public static function semua(): array
    {
        $tahun = 'required|integer|between:1970,' . (date('Y') + 1);
        return [
            'karya' => [
                'tabel' => 'karya', 'label' => 'Karya & publikasi', 'satuan' => 'karya', 'ikon' => 'buku', 'slug' => 'judul', 'urut' => ['tahun', 'desc'],
                'kolom' => ['judul' => 'Judul', 'jenis' => 'Jenis', 'tahun' => 'Tahun', 'diunduh' => 'Unduh'], 'cari' => ['judul', 'jenis'], 'publik' => 'karya/{slug}',
                'field' => [
                    'judul' => ['text', 'Judul', 'required|string|max:200'],
                    'jenis' => ['select', 'Jenis', 'required|in:' . implode(',', self::JENIS_KARYA), self::JENIS_KARYA],
                    'tahun' => ['number', 'Tahun', $tahun],
                    'ringkasan' => ['textarea', 'Ringkasan', 'nullable|string|max:400', null, 'Tampil di kartu daftar karya.'],
                    'isi' => ['markdown', 'Uraian lengkap', 'nullable|string|max:60000'],
                    'sampul' => ['gambar', 'Gambar sampul', 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                    'berkas' => ['berkas', 'Berkas untuk diunduh', 'nullable|file|extensions:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240', null, 'PDF, Word, PowerPoint, Excel, atau ZIP maks 10 MB.'],
                    'tautan' => ['url', 'Tautan luar', 'nullable|url:http,https|max:250', null, 'Misal tautan jurnal, Google Drive, atau YouTube.'],
                    'tampil' => ['cek', 'Tampilkan di situs', 'boolean'],
                ],
            ],
            'bahan-ajar' => [
                'tabel' => 'bahan_ajar', 'label' => 'Bahan ajar', 'satuan' => 'bahan ajar', 'ikon' => 'map', 'slug' => 'judul', 'urut' => ['created_at', 'desc'],
                'kolom' => ['judul' => 'Judul', 'kelas' => 'Kelas', 'mapel' => 'Mapel', 'diunduh' => 'Unduh'], 'cari' => ['judul', 'kelas', 'mapel'], 'publik' => 'bahan-ajar',
                'field' => [
                    'judul' => ['text', 'Judul materi', 'required|string|max:200'],
                    'kelas' => ['text', 'Kelas', 'required|string|max:30', null, 'Contoh: X, XI IPA, Fase E'],
                    'mapel' => ['text', 'Mata pelajaran', 'required|string|max:80'],
                    'deskripsi' => ['textarea', 'Deskripsi', 'nullable|string|max:400'],
                    'berkas' => ['berkas', 'Berkas materi', 'nullable|file|extensions:pdf,doc,docx,ppt,pptx,xls,xlsx,zip|max:10240', null, 'PDF, Word, PowerPoint, Excel, atau ZIP maks 10 MB.'],
                    'tautan_video' => ['url', 'Tautan video', 'nullable|url:http,https|max:250', null, 'Misal video YouTube penjelasan materi.'],
                    'tampil' => ['cek', 'Tampilkan di situs', 'boolean'],
                ],
            ],
            'artikel' => [
                'tabel' => 'artikel', 'label' => 'Artikel', 'satuan' => 'artikel', 'ikon' => 'pena', 'slug' => 'judul', 'urut' => ['terbit_pada', 'desc'],
                'kolom' => ['judul' => 'Judul', 'kategori' => 'Kategori', 'terbit_pada' => 'Terbit', 'dilihat' => 'Dibaca'], 'cari' => ['judul', 'kategori'], 'publik' => 'artikel/{slug}',
                'field' => [
                    'judul' => ['text', 'Judul', 'required|string|max:200'],
                    'kategori' => ['text', 'Kategori', 'required|string|max:60', null, 'Contoh: Refleksi, Tips Belajar, Kegiatan Kelas'],
                    'ringkasan' => ['textarea', 'Ringkasan', 'nullable|string|max:400'],
                    'isi' => ['markdown', 'Isi artikel', 'required|string|max:60000'],
                    'sampul' => ['gambar', 'Gambar sampul', 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                    'terbit_pada' => ['date', 'Tanggal terbit', 'nullable|date'],
                    'tampil' => ['cek', 'Terbitkan', 'boolean'],
                ],
            ],
            'prestasi' => [
                'tabel' => 'prestasi', 'label' => 'Prestasi', 'satuan' => 'prestasi', 'ikon' => 'trofi', 'urut' => ['tahun', 'desc'],
                'kolom' => ['judul' => 'Prestasi', 'kategori' => 'Kategori', 'tingkat' => 'Tingkat', 'peringkat' => 'Peringkat', 'tahun' => 'Tahun'], 'cari' => ['judul'], 'publik' => 'prestasi',
                'field' => [
                    'judul' => ['text', 'Nama lomba / penghargaan', 'required|string|max:200'],
                    'kategori' => ['select', 'Kategori', 'required|in:guru,siswa', ['guru' => 'Prestasi guru', 'siswa' => 'Siswa bimbingan']],
                    'tingkat' => ['select', 'Tingkat', 'required|in:' . implode(',', self::TINGKAT), self::TINGKAT],
                    'peringkat' => ['text', 'Peringkat', 'required|string|max:60', null, 'Contoh: Juara 1, Finalis, Guru Inspiratif'],
                    'tahun' => ['number', 'Tahun', $tahun],
                    'deskripsi' => ['textarea', 'Keterangan', 'nullable|string|max:400'],
                    'gambar' => ['gambar', 'Foto / piagam', 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048'],
                    'tampil' => ['cek', 'Tampilkan di situs', 'boolean'],
                ],
            ],
            'galeri' => [
                'tabel' => 'galeri', 'label' => 'Galeri', 'satuan' => 'foto', 'ikon' => 'foto', 'urut' => ['tanggal', 'desc'],
                'kolom' => ['gambar' => 'Foto', 'judul' => 'Judul', 'album' => 'Album', 'tanggal' => 'Tanggal'], 'cari' => ['judul', 'album'], 'publik' => 'galeri',
                'field' => [
                    'judul' => ['text', 'Judul foto', 'required|string|max:150'],
                    'album' => ['text', 'Album', 'required|string|max:80', null, 'Contoh: Kegiatan Kelas, Studi Lapangan, Lomba'],
                    'gambar' => ['gambar', 'Foto', 'required_without:id|image|mimes:jpg,jpeg,png,webp|max:3072'],
                    'tanggal' => ['date', 'Tanggal', 'nullable|date'],
                    'tampil' => ['cek', 'Tampilkan di situs', 'boolean'],
                ],
            ],
            'pendidikan' => [
                'tabel' => 'pendidikan', 'label' => 'Riwayat pendidikan', 'satuan' => 'pendidikan', 'ikon' => 'toga', 'urut' => ['tahun_selesai', 'desc'],
                'kolom' => ['jenjang' => 'Jenjang', 'institusi' => 'Institusi', 'jurusan' => 'Jurusan', 'tahun_selesai' => 'Lulus'], 'cari' => ['institusi'],
                'field' => [
                    'jenjang' => ['select', 'Jenjang', 'required|in:SMA/SMK,D3,S1,S2,S3,PPG', ['SMA/SMK', 'D3', 'S1', 'S2', 'S3', 'PPG']],
                    'institusi' => ['text', 'Institusi', 'required|string|max:150'],
                    'jurusan' => ['text', 'Program studi / jurusan', 'nullable|string|max:150'],
                    'tahun_mulai' => ['number', 'Tahun masuk', 'nullable|integer|between:1960,2100'],
                    'tahun_selesai' => ['number', 'Tahun lulus', 'nullable|integer|between:1960,2100'],
                ],
            ],
            'pengalaman' => [
                'tabel' => 'pengalaman', 'label' => 'Pengalaman mengajar', 'satuan' => 'pengalaman', 'ikon' => 'koper', 'urut' => ['tahun_mulai', 'desc'],
                'kolom' => ['jabatan' => 'Jabatan', 'instansi' => 'Instansi', 'tahun_mulai' => 'Mulai', 'tahun_selesai' => 'Selesai'], 'cari' => ['jabatan', 'instansi'],
                'field' => [
                    'jabatan' => ['text', 'Jabatan / peran', 'required|string|max:150'],
                    'instansi' => ['text', 'Sekolah / instansi', 'required|string|max:150'],
                    'tahun_mulai' => ['number', 'Tahun mulai', 'required|integer|between:1960,2100'],
                    'tahun_selesai' => ['number', 'Tahun selesai', 'nullable|integer|between:1960,2100', null, 'Kosongkan bila masih menjabat.'],
                    'deskripsi' => ['textarea', 'Deskripsi', 'nullable|string|max:1000'],
                ],
            ],
            'sertifikat' => [
                'tabel' => 'sertifikat', 'label' => 'Pelatihan & sertifikat', 'satuan' => 'sertifikat', 'ikon' => 'medali', 'urut' => ['tahun', 'desc'],
                'kolom' => ['judul' => 'Pelatihan', 'penyelenggara' => 'Penyelenggara', 'tahun' => 'Tahun', 'jam_pelajaran' => 'JP'], 'cari' => ['judul', 'penyelenggara'],
                'field' => [
                    'judul' => ['text', 'Nama pelatihan / sertifikasi', 'required|string|max:200'],
                    'penyelenggara' => ['text', 'Penyelenggara', 'required|string|max:150'],
                    'tahun' => ['number', 'Tahun', $tahun],
                    'jam_pelajaran' => ['number', 'Jumlah JP', 'nullable|integer|between:1,1000'],
                    'berkas' => ['berkas', 'Scan sertifikat', 'nullable|file|extensions:pdf,jpg,jpeg,png|max:5120', null, 'PDF/JPG/PNG maks 5 MB.'],
                ],
            ],
            'keahlian' => [
                'tabel' => 'keahlian', 'label' => 'Kompetensi', 'satuan' => 'kompetensi', 'ikon' => 'bintang', 'urut' => ['urutan', 'asc'],
                'kolom' => ['nama' => 'Kompetensi', 'kelompok' => 'Kelompok', 'level' => 'Level'], 'cari' => ['nama'],
                'field' => [
                    'nama' => ['text', 'Nama kompetensi', 'required|string|max:80', null, 'Contoh: Pembelajaran berdiferensiasi'],
                    'kelompok' => ['select', 'Kelompok', 'required|in:Pedagogik,Profesional,Teknologi,Kepemimpinan', ['Pedagogik', 'Profesional', 'Teknologi', 'Kepemimpinan']],
                    'level' => ['number', 'Level (0-100)', 'required|integer|between:0,100'],
                    'urutan' => ['number', 'Urutan', 'nullable|integer|between:0,999'],
                ],
            ],
            'testimoni' => [
                'tabel' => 'testimoni', 'label' => 'Testimoni', 'satuan' => 'testimoni', 'ikon' => 'pesan', 'urut' => ['created_at', 'desc'],
                'kolom' => ['nama' => 'Nama', 'peran' => 'Peran', 'isi' => 'Isi'], 'cari' => ['nama', 'isi'],
                'field' => [
                    'nama' => ['text', 'Nama', 'required|string|max:100'],
                    'peran' => ['text', 'Peran', 'required|string|max:100', null, 'Contoh: Alumni 2024, Orang tua siswa, Rekan guru'],
                    'isi' => ['textarea', 'Testimoni', 'required|string|max:500'],
                    'tampil' => ['cek', 'Tampilkan di situs', 'boolean'],
                ],
            ],
        ];
    }

    public static function get(string $kunci): ?array
    {
        return self::semua()[$kunci] ?? null;
    }

    /** Preset warna aksen tampilan (tanpa gradasi). */
    public const WARNA = [
        'hijau' => ['Hijau botol', '#1f5c45', '#e5efe9'],
        'biru' => ['Biru akademik', '#1e4fa3', '#e6edf9'],
        'marun' => ['Marun', '#8c2f39', '#f5e6e8'],
        'ungu' => ['Ungu', '#5b3fa0', '#ece7f6'],
        'teal' => ['Teal', '#0f6b6b', '#e2f1f1'],
        'oranye' => ['Terakota', '#b4501c', '#f8e9df'],
    ];
}
