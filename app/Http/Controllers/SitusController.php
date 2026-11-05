<?php

namespace App\Http\Controllers;

use App\Support\Situs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;

/** Halaman publik portofolio. */
class SitusController extends Controller
{
    public function beranda()
    {
        return view('situs.beranda', [
            'karya' => DB::table('karya')->where('tampil', true)->orderByDesc('tahun')->orderByDesc('id')->limit(3)->get(),
            'prestasi' => DB::table('prestasi')->where('tampil', true)->orderByDesc('tahun')->limit(4)->get(),
            'artikel' => Situs::get('tampil_artikel') ? DB::table('artikel')->where('tampil', true)->where('terbit_pada', '<=', now())->orderByDesc('terbit_pada')->limit(3)->get() : collect(),
            'testimoni' => Situs::get('tampil_testimoni') ? DB::table('testimoni')->where('tampil', true)->latest()->limit(3)->get() : collect(),
            'keahlian' => DB::table('keahlian')->orderBy('urutan')->limit(6)->get(),
            'angka' => [
                'karya' => DB::table('karya')->where('tampil', true)->count(),
                'prestasi' => DB::table('prestasi')->where('tampil', true)->count(),
                'sertifikat' => DB::table('sertifikat')->count(),
            ],
        ]);
    }

    public function profil()
    {
        return view('situs.profil', [
            'pendidikan' => DB::table('pendidikan')->orderByDesc('tahun_selesai')->get(),
            'pengalaman' => DB::table('pengalaman')->orderByRaw('tahun_selesai IS NOT NULL')->orderByDesc('tahun_mulai')->get(),
            'sertifikat' => DB::table('sertifikat')->orderByDesc('tahun')->get(),
            'keahlian' => DB::table('keahlian')->orderBy('urutan')->get()->groupBy('kelompok'),
        ]);
    }

    public function karya(Request $r)
    {
        $jenis = $r->query('jenis');
        $q = DB::table('karya')->where('tampil', true)->orderByDesc('tahun')->orderByDesc('id');
        if ($jenis) {
            $q->where('jenis', $jenis);
        }
        return view('situs.karya', [
            'daftar' => $q->paginate(9)->withQueryString(),
            'jenis' => $jenis,
            'jenisAda' => DB::table('karya')->where('tampil', true)->select('jenis', DB::raw('COUNT(*) n'))->groupBy('jenis')->pluck('n', 'jenis'),
        ]);
    }

    public function karyaDetail(string $slug)
    {
        $k = DB::table('karya')->where(['slug' => $slug, 'tampil' => true])->first() ?? abort(404);
        DB::table('karya')->where('id', $k->id)->increment('dilihat');
        return view('situs.karya-detail', ['k' => $k, 'lain' => DB::table('karya')->where('tampil', true)->where('id', '!=', $k->id)->orderByDesc('tahun')->limit(3)->get()]);
    }

    public function bahanAjar(Request $r)
    {
        abort_unless(Situs::get('tampil_bahan_ajar'), 404);
        $kelas = $r->query('kelas');
        $q = DB::table('bahan_ajar')->where('tampil', true)->orderBy('kelas')->orderByDesc('id');
        if ($kelas) {
            $q->where('kelas', $kelas);
        }
        $cari = trim((string) $r->query('q'));
        if ($cari !== '') {
            $q->where(fn ($w) => $w->where('judul', 'like', "%{$cari}%")->orWhere('mapel', 'like', "%{$cari}%"));
        }
        return view('situs.bahan-ajar', ['daftar' => $q->get(), 'kelas' => $kelas, 'cari' => $cari, 'kelasAda' => DB::table('bahan_ajar')->where('tampil', true)->distinct()->orderBy('kelas')->pluck('kelas')]);
    }

    public function prestasi()
    {
        return view('situs.prestasi', ['daftar' => DB::table('prestasi')->where('tampil', true)->orderByDesc('tahun')->orderByDesc('id')->get()]);
    }

    public function galeri(Request $r)
    {
        abort_unless(Situs::get('tampil_galeri'), 404);
        $album = $r->query('album');
        $q = DB::table('galeri')->where('tampil', true)->orderByDesc('tanggal')->orderByDesc('id');
        if ($album) {
            $q->where('album', $album);
        }
        return view('situs.galeri', ['daftar' => $q->get(), 'album' => $album, 'albumAda' => DB::table('galeri')->where('tampil', true)->distinct()->orderBy('album')->pluck('album')]);
    }

    public function artikel(Request $r)
    {
        abort_unless(Situs::get('tampil_artikel'), 404);
        $kat = $r->query('kategori');
        $q = DB::table('artikel')->where('tampil', true)->where('terbit_pada', '<=', now())->orderByDesc('terbit_pada');
        if ($kat) {
            $q->where('kategori', $kat);
        }
        return view('situs.artikel', ['daftar' => $q->paginate(6)->withQueryString(), 'kategori' => $kat, 'kategoriAda' => DB::table('artikel')->where('tampil', true)->distinct()->orderBy('kategori')->pluck('kategori')]);
    }

    public function artikelDetail(string $slug)
    {
        $a = DB::table('artikel')->where(['slug' => $slug, 'tampil' => true])->where('terbit_pada', '<=', now())->first() ?? abort(404);
        DB::table('artikel')->where('id', $a->id)->increment('dilihat');
        return view('situs.artikel-detail', ['a' => $a, 'lain' => DB::table('artikel')->where('tampil', true)->where('terbit_pada', '<=', now())->where('id', '!=', $a->id)->orderByDesc('terbit_pada')->limit(3)->get()]);
    }

    public function kontak()
    {
        return view('situs.kontak');
    }

    public function kirimPesan(Request $r)
    {
        // Honeypot: kolom tersembunyi yang hanya diisi bot
        if ($r->filled('situs_web')) {
            return back()->with('ok', 'Pesan terkirim. Terima kasih!');
        }
        $kunci = 'kontak:' . $r->ip();
        if (RateLimiter::tooManyAttempts($kunci, 3)) {
            return back()->withInput()->with('error', 'Terlalu banyak pesan. Coba lagi dalam ' . ceil(RateLimiter::availableIn($kunci) / 60) . ' menit.');
        }
        $data = $r->validate([
            'nama' => 'required|string|min:3|max:100',
            'email' => 'required|email:rfc|max:150',
            'subjek' => 'required|string|min:3|max:150',
            'isi' => 'required|string|min:10|max:3000',
        ], [], ['isi' => 'pesan']);
        RateLimiter::hit($kunci, 600);
        DB::table('pesan')->insert($data + ['ip' => $r->ip(), 'dibaca' => false, 'created_at' => now(), 'updated_at' => now()]);
        return redirect()->route('kontak')->with('ok', 'Pesan terkirim. Terima kasih, saya akan membalas melalui email secepatnya.');
    }

    /** Unduh berkas karya / bahan ajar sambil menghitung jumlah unduhan. */
    public function unduh(string $jenis, int $id)
    {
        $tabel = ['karya' => 'karya', 'bahan-ajar' => 'bahan_ajar'][$jenis] ?? abort(404);
        $row = DB::table($tabel)->where(['id' => $id, 'tampil' => true])->first() ?? abort(404);
        abort_unless($row->berkas && Storage::disk('local')->exists('berkas/' . $row->berkas), 404);
        DB::table($tabel)->where('id', $id)->increment('diunduh');
        $ext = pathinfo($row->berkas, PATHINFO_EXTENSION);
        return Storage::disk('local')->download('berkas/' . $row->berkas, \Illuminate\Support\Str::slug($row->judul) . '.' . $ext);
    }

    public function cv()
    {
        $cv = Situs::get('cv');
        abort_unless($cv && Storage::disk('local')->exists('berkas/' . $cv), 404);
        return Storage::disk('local')->download('berkas/' . $cv, 'CV-' . \Illuminate\Support\Str::slug(Situs::get('nama')) . '.pdf');
    }

    public function sitemap()
    {
        $url = [route('beranda'), route('profil'), route('karya'), route('prestasi'), route('kontak')];
        foreach (['bahan_ajar' => 'bahan-ajar', 'galeri' => 'galeri', 'artikel' => 'artikel'] as $k => $r) {
            if (Situs::get('tampil_' . $k)) {
                $url[] = route($r);
            }
        }
        foreach (DB::table('karya')->where('tampil', true)->pluck('slug') as $s) {
            $url[] = route('karya.detail', $s);
        }
        foreach (DB::table('artikel')->where('tampil', true)->where('terbit_pada', '<=', now())->pluck('slug') as $s) {
            $url[] = route('artikel.detail', $s);
        }
        $xml = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($url as $u) {
            $xml .= '<url><loc>' . e($u) . '</loc></url>';
        }
        return response($xml . '</urlset>', 200, ['Content-Type' => 'application/xml']);
    }

    public function robots()
    {
        return response("User-agent: *\nDisallow: /admin\nSitemap: " . route('sitemap') . "\n", 200, ['Content-Type' => 'text/plain']);
    }
}
