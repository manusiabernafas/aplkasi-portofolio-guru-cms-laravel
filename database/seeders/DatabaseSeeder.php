<?php

namespace Database\Seeders;

use App\Models\User;
use App\Support\Situs;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Data contoh portofolio guru Biologi. Gambar & berkas contoh disalin dari
 * database/seeders/aset ke public/uploads dan storage/app/private/berkas.
 * Login admin: admin / admin123 (segera ganti dari menu Profil).
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $aset = __DIR__ . '/aset';
        $data = json_decode(File::get(__DIR__ . '/contoh.json'), true);
        $now = now();

        // salin aset gambar ke public/uploads/contoh/*
        File::ensureDirectoryExists(public_path('uploads'));
        foreach (['karya', 'artikel', 'prestasi', 'galeri', 'profil'] as $dir) {
            File::copyDirectory("{$aset}/{$dir}", public_path("uploads/contoh/{$dir}"));
        }
        foreach (File::files("{$aset}/berkas") as $f) {
            Storage::disk('local')->put('berkas/contoh-' . $f->getFilename(), File::get($f->getPathname()));
        }

        User::updateOrCreate(['username' => 'admin'], [
            'name' => $data['profil']['nama'], 'email' => 'admin@contoh.sch.id', 'password' => Hash::make('admin123'),
        ]);

        Situs::simpan($data['profil'] + ['foto' => 'contoh/profil/foto.jpg', 'cv' => 'contoh-cv.pdf', 'warna' => 'hijau']);

        foreach ($data['karya'] as $i => $k) {
            $n = $i + 1;
            DB::table('karya')->insert($k + [
                'slug' => Str::slug(Str::limit($k['judul'], 80, '')),
                'isi' => $this->uraian($k['jenis'], $k['ringkasan']),
                'sampul' => "contoh/karya/k{$n}.jpg", 'berkas' => "contoh-k{$n}.pdf",
                'tautan' => $i === 3 ? 'https://garuda.kemdikbud.go.id/' : null,
                'diunduh' => [128, 214, 96, 57, 301, 88, 64][$i] ?? 10, 'dilihat' => [412, 655, 380, 240, 870, 330, 270][$i] ?? 50,
                'tampil' => true, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ($data['bahan_ajar'] as $i => $b) {
            DB::table('bahan_ajar')->insert([
                'judul' => $b['judul'], 'slug' => Str::slug($b['judul']), 'kelas' => $b['kelas'], 'mapel' => $b['mapel'], 'deskripsi' => $b['deskripsi'],
                'berkas' => 'contoh-b' . ($i + 1) . '.pdf', 'tautan_video' => $b['video'] ?? null, 'diunduh' => 20 + $i * 17, 'tampil' => true,
                'created_at' => $now->copy()->subDays($i), 'updated_at' => $now,
            ]);
        }

        foreach ($data['artikel'] as $i => $a) {
            DB::table('artikel')->insert($a + [
                'slug' => Str::slug($a['judul']), 'isi' => $this->artikel($a['judul'], $a['ringkasan']), 'sampul' => 'contoh/artikel/a' . ($i + 1) . '.jpg',
                'tampil' => true, 'dilihat' => [520, 340, 410, 190][$i] ?? 30, 'terbit_pada' => $now->copy()->subDays(6 + $i * 23),
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }

        foreach ($data['prestasi'] as $i => $p) {
            DB::table('prestasi')->insert($p + ['gambar' => $i < 4 ? 'contoh/prestasi/p' . ($i + 1) . '.jpg' : null, 'tampil' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        foreach ($data['galeri'] as $i => $g) {
            DB::table('galeri')->insert($g + ['gambar' => 'contoh/galeri/g' . ($i + 1) . '.jpg', 'tanggal' => $now->copy()->subDays(5 + $i * 11)->toDateString(), 'tampil' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        $this->isi('pendidikan', [
            ['jenjang' => 'S2', 'institusi' => 'Universitas Pendidikan Nusantara', 'jurusan' => 'Magister Pendidikan Biologi', 'tahun_mulai' => 2016, 'tahun_selesai' => 2018],
            ['jenjang' => 'PPG', 'institusi' => 'Universitas Pendidikan Nusantara', 'jurusan' => 'Pendidikan Profesi Guru Biologi', 'tahun_mulai' => 2014, 'tahun_selesai' => 2015],
            ['jenjang' => 'S1', 'institusi' => 'Universitas Negeri Cendekia', 'jurusan' => 'Pendidikan Biologi', 'tahun_mulai' => 2005, 'tahun_selesai' => 2009],
        ]);
        $this->isi('pengalaman', [
            ['jabatan' => 'Guru Biologi & Pembina KIR', 'instansi' => 'SMA Negeri 1 Cendekia', 'tahun_mulai' => 2017, 'tahun_selesai' => null, 'deskripsi' => 'Mengajar kelas X sampai XII, membina Kelompok Ilmiah Remaja, dan menjadi koordinator Projek P5.'],
            ['jabatan' => 'Fasilitator Guru Penggerak', 'instansi' => 'Program Pendidikan Guru Penggerak', 'tahun_mulai' => 2024, 'tahun_selesai' => null, 'deskripsi' => 'Mendampingi calon guru penggerak dalam lokakarya dan pendampingan individu.'],
            ['jabatan' => 'Guru Biologi', 'instansi' => 'SMA Swasta Harapan', 'tahun_mulai' => 2011, 'tahun_selesai' => 2017, 'deskripsi' => 'Mengajar Biologi dan merintis kebun sekolah sebagai sumber belajar.'],
        ]);
        $this->isi('sertifikat', [
            ['judul' => 'Pendidikan Guru Penggerak Angkatan 7', 'penyelenggara' => 'Kemendikbudristek', 'tahun' => 2023, 'jam_pelajaran' => 310],
            ['judul' => 'Pembelajaran Berdiferensiasi untuk Guru SMA', 'penyelenggara' => 'Balai Guru Penggerak', 'tahun' => 2024, 'jam_pelajaran' => 32],
            ['judul' => 'Penulisan Karya Tulis Ilmiah bagi Guru', 'penyelenggara' => 'MGMP Biologi Kota', 'tahun' => 2022, 'jam_pelajaran' => 40],
            ['judul' => 'Pemanfaatan Teknologi Digital dalam Pembelajaran', 'penyelenggara' => 'Pusdatin', 'tahun' => 2022, 'jam_pelajaran' => 24],
            ['judul' => 'Bimtek Asesmen Kurikulum Merdeka', 'penyelenggara' => 'Dinas Pendidikan Provinsi', 'tahun' => 2025, 'jam_pelajaran' => 20],
        ]);
        $this->isi('keahlian', [
            ['nama' => 'Pembelajaran berbasis proyek', 'kelompok' => 'Pedagogik', 'level' => 92, 'urutan' => 1],
            ['nama' => 'Pembelajaran berdiferensiasi', 'kelompok' => 'Pedagogik', 'level' => 88, 'urutan' => 2],
            ['nama' => 'Praktikum & kerja ilmiah', 'kelompok' => 'Profesional', 'level' => 95, 'urutan' => 3],
            ['nama' => 'Penelitian tindakan kelas', 'kelompok' => 'Profesional', 'level' => 85, 'urutan' => 4],
            ['nama' => 'Media pembelajaran digital', 'kelompok' => 'Teknologi', 'level' => 80, 'urutan' => 5],
            ['nama' => 'Pendampingan rekan guru', 'kelompok' => 'Kepemimpinan', 'level' => 78, 'urutan' => 6],
        ]);
        $this->isi('testimoni', [
            ['nama' => 'Nadia Putri', 'peran' => 'Alumni 2024, kini kuliah Kedokteran', 'isi' => 'Bu Siti membuat Biologi terasa seperti cerita, bukan hafalan. Praktikum di kebun sekolah itu yang bikin saya yakin memilih jurusan kesehatan.', 'tampil' => true],
            ['nama' => 'Bapak Hendra', 'peran' => 'Orang tua siswa kelas XI', 'isi' => 'Anak saya jadi rajin bertanya di rumah. Komunikasi Bu Siti dengan orang tua juga sangat baik dan terbuka.', 'tampil' => true],
            ['nama' => 'Dewi Kartika, S.Pd.', 'peran' => 'Rekan guru Kimia', 'isi' => 'Kolaborasi proyek lintas mapel bersama Bu Siti selalu rapi dan menyenangkan. Siswa terlibat penuh dari awal sampai pameran.', 'tampil' => true],
        ]);

        DB::table('pesan')->insert([
            ['nama' => 'Rudi Hartono', 'email' => 'rudi@contoh.id', 'subjek' => 'Undangan narasumber workshop PjBL', 'isi' => "Selamat siang Bu Siti,\n\nKami dari MGMP Biologi kabupaten ingin mengundang Ibu sebagai narasumber workshop pembelajaran berbasis proyek pada bulan depan. Apakah Ibu berkenan?\n\nTerima kasih.", 'dibaca' => false, 'ip' => '127.0.0.1', 'created_at' => $now->copy()->subHours(5), 'updated_at' => $now],
            ['nama' => 'Ayu Lestari', 'email' => 'ayu@contoh.id', 'subjek' => 'Izin memakai modul ekosistem', 'isi' => 'Bu, saya guru di sekolah lain. Bolehkah modul ekosistem Ibu saya adaptasi untuk kelas saya? Terima kasih banyak atas berbaginya.', 'dibaca' => true, 'ip' => '127.0.0.1', 'created_at' => $now->copy()->subDays(3), 'updated_at' => $now],
        ]);
    }

    private function isi(string $tabel, array $baris): void
    {
        foreach ($baris as $b) {
            DB::table($tabel)->insert($b + ['created_at' => now(), 'updated_at' => now()]);
        }
    }

    private function uraian(string $jenis, string $ringkasan): string
    {
        return "## Latar belakang\n\n{$ringkasan} Karya ini lahir dari pengamatan di kelas: sebagian siswa kesulitan menghubungkan konsep Biologi dengan kehidupan sehari-hari.\n\n"
            . "## Tujuan\n\n- Meningkatkan pemahaman konsep siswa\n- Melatih keterampilan berpikir kritis dan kerja ilmiah\n- Memberi contoh praktik yang mudah ditiru guru lain\n\n"
            . "## Hasil\n\nSetelah diterapkan, keterlibatan siswa meningkat dan sebagian besar siswa mencapai kriteria ketercapaian tujuan pembelajaran. Rincian data, instrumen, dan lampiran ada pada berkas {$jenis} yang bisa diunduh di halaman ini.\n\n"
            . "> Silakan gunakan dan adaptasi untuk kelas Anda dengan tetap mencantumkan sumber.";
    }

    private function artikel(string $judul, string $ringkasan): string
    {
        return "{$ringkasan}\n\n## Berawal dari kelas\n\nSetiap tahun ajaran baru saya bertemu siswa dengan latar belakang dan kesiapan belajar yang berbeda. Tulisan ini adalah catatan pribadi tentang apa yang saya coba, apa yang berhasil, dan apa yang masih perlu diperbaiki.\n\n"
            . "## Yang saya lakukan\n\n1. **Mulai dari pertanyaan siswa.** Pertemuan pertama selalu saya buka dengan papan pertanyaan.\n2. **Beri pilihan.** Siswa boleh memilih cara menunjukkan pemahaman: poster, video pendek, atau laporan.\n3. **Umpan balik cepat.** Asesmen formatif singkat di akhir pertemuan membantu saya menyesuaikan pertemuan berikutnya.\n\n"
            . "## Pelajaran penting\n\nPerubahan kecil yang konsisten ternyata lebih berdampak daripada program besar yang hanya berjalan sebentar. Semoga catatan ini bermanfaat untuk rekan guru yang sedang mencoba hal serupa.";
    }
}
