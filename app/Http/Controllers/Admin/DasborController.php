<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Konten;
use Illuminate\Support\Facades\DB;

class DasborController extends Controller
{
    public function index()
    {
        $hitung = [];
        foreach (['karya', 'bahan-ajar', 'artikel', 'prestasi', 'galeri', 'sertifikat'] as $k) {
            $hitung[$k] = DB::table(Konten::get($k)['tabel'])->count();
        }
        return view('admin.dasbor', [
            'hitung' => $hitung,
            'pesanBaru' => DB::table('pesan')->where('dibaca', false)->count(),
            'pesan' => DB::table('pesan')->latest()->limit(5)->get(),
            'unduhan' => (int) DB::table('karya')->sum('diunduh') + (int) DB::table('bahan_ajar')->sum('diunduh'),
            'dibaca' => (int) DB::table('artikel')->sum('dilihat') + (int) DB::table('karya')->sum('dilihat'),
            'populer' => DB::table('karya')->orderByDesc('diunduh')->limit(5)->get(['judul', 'jenis', 'diunduh', 'dilihat']),
            'artikelPopuler' => DB::table('artikel')->orderByDesc('dilihat')->limit(5)->get(['judul', 'dilihat', 'terbit_pada']),
            'kelengkapan' => $this->kelengkapan(),
        ]);
    }

    /** Checklist kelengkapan profil agar portofolio terlihat profesional. */
    private function kelengkapan(): array
    {
        $s = \App\Support\Situs::semua();
        return [
            ['Foto profil', (bool) $s['foto'], 'admin.profil'],
            ['Tentang saya', strlen($s['tentang']) > 50, 'admin.profil'],
            ['Kontak (email/WA)', (bool) ($s['email'] || $s['wa']), 'admin.profil'],
            ['Riwayat pendidikan', DB::table('pendidikan')->exists(), 'pendidikan'],
            ['Pengalaman mengajar', DB::table('pengalaman')->exists(), 'pengalaman'],
            ['Minimal 3 karya', DB::table('karya')->count() >= 3, 'karya'],
            ['CV (PDF)', (bool) $s['cv'], 'admin.profil'],
        ];
    }
}
