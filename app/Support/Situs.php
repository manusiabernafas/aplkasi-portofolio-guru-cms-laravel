<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

/** Pengaturan situs & data profil guru (tabel pengaturan, kunci-nilai). */
class Situs
{
    public const DEFAULT = [
        'nama' => 'Nama Guru, S.Pd.',
        'jabatan' => 'Guru',
        'mapel' => '',
        'sekolah' => '',
        'nip' => '',
        'bio_singkat' => '',
        'tentang' => '',
        'foto' => '',
        'cv' => '',
        'tahun_mulai' => '',
        'jumlah_siswa' => '',
        'email' => '',
        'wa' => '',
        'alamat' => '',
        'instagram' => '',
        'youtube' => '',
        'facebook' => '',
        'linkedin' => '',
        'tiktok' => '',
        'judul_situs' => '',
        'meta_deskripsi' => '',
        'warna' => 'hijau',
        'tampil_testimoni' => '1',
        'tampil_bahan_ajar' => '1',
        'tampil_galeri' => '1',
        'tampil_artikel' => '1',
    ];

    private static ?array $cache = null;

    public static function semua(): array
    {
        return self::$cache ??= array_merge(self::DEFAULT, DB::table('pengaturan')->pluck('nilai', 'kunci')->map(fn ($v) => (string) $v)->all());
    }

    public static function get(string $k): string
    {
        return self::semua()[$k] ?? '';
    }

    public static function simpan(array $data): void
    {
        foreach ($data as $k => $v) {
            DB::table('pengaturan')->updateOrInsert(['kunci' => $k], ['nilai' => (string) $v]);
        }
        self::$cache = null;
    }

    public static function warna(): array
    {
        return Konten::WARNA[self::get('warna')] ?? Konten::WARNA['hijau'];
    }

    public static function lamaMengajar(): ?int
    {
        $t = (int) self::get('tahun_mulai');
        return $t ? max(1, (int) date('Y') - $t) : null;
    }

    /** Nomor WA ke format wa.me (08xx -> 628xx) */
    public static function waLink(?string $pesan = null): string
    {
        $n = preg_replace('/\D/', '', self::get('wa'));
        if (str_starts_with($n, '0')) {
            $n = '62' . substr($n, 1);
        }
        return $n ? 'https://wa.me/' . $n . ($pesan ? '?text=' . rawurlencode($pesan) : '') : '';
    }
}
