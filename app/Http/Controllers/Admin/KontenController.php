<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Konten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * CRUD umum untuk semua jenis konten yang didefinisikan di App\Support\Konten.
 * Gambar disimpan di public/uploads/{jenis} (tampil di web), berkas unduhan di
 * storage/app/private/berkas (hanya lewat route unduh yang menghitung jumlah unduhan).
 */
class KontenController extends Controller
{
    private function def(string $jenis): array
    {
        return Konten::get($jenis) ?? abort(404);
    }

    public function index(Request $r, string $jenis)
    {
        $d = $this->def($jenis);
        $q = DB::table($d['tabel'])->orderBy($d['urut'][0], $d['urut'][1])->orderByDesc('id');
        $cari = trim((string) $r->query('q'));
        if ($cari !== '') {
            $q->where(fn ($w) => collect($d['cari'])->each(fn ($c) => $w->orWhere($c, 'like', "%{$cari}%")));
        }
        return view('admin.konten.index', ['jenis' => $jenis, 'd' => $d, 'daftar' => $q->paginate(15)->withQueryString(), 'cari' => $cari]);
    }

    public function create(string $jenis)
    {
        $d = $this->def($jenis);
        return view('admin.konten.form', ['jenis' => $jenis, 'd' => $d, 'row' => (object) ['id' => null, 'tampil' => 1, 'tahun' => date('Y'), 'terbit_pada' => date('Y-m-d'), 'tanggal' => date('Y-m-d'), 'level' => 80]]);
    }

    public function edit(string $jenis, int $id)
    {
        $d = $this->def($jenis);
        return view('admin.konten.form', ['jenis' => $jenis, 'd' => $d, 'row' => DB::table($d['tabel'])->find($id) ?? abort(404)]);
    }

    public function store(Request $r, string $jenis)
    {
        return $this->simpan($r, $jenis, null);
    }

    public function update(Request $r, string $jenis, int $id)
    {
        return $this->simpan($r, $jenis, $id);
    }

    private function simpan(Request $r, string $jenis, ?int $id)
    {
        $d = $this->def($jenis);
        $lama = $id ? (DB::table($d['tabel'])->find($id) ?? abort(404)) : null;
        $aturan = [];
        $label = [];
        foreach ($d['field'] as $f => [$tipe, $lbl, $rule]) {
            // gambar wajib hanya saat membuat baru
            $aturan[$f] = str_replace('required_without:id', $id ? 'nullable' : 'required', $rule);
            $label[$f] = strtolower($lbl);
        }
        $data = $r->validate($aturan, [], $label);
        if (isset($data['tahun_mulai'], $data['tahun_selesai']) && $data['tahun_selesai'] && $data['tahun_selesai'] < $data['tahun_mulai']) {
            return back()->withInput()->withErrors(['tahun_selesai' => 'Tahun selesai tidak boleh sebelum tahun mulai.']);
        }
        if (array_key_exists('urutan', $data) && $data['urutan'] === null) {
            $data['urutan'] = 0;
        }
        foreach ($d['field'] as $f => [$tipe]) {
            if ($tipe === 'cek') {
                $data[$f] = $r->boolean($f);
            } elseif ($tipe === 'gambar') {
                unset($data[$f]);
                if ($r->hasFile($f)) {
                    $nama = Str::random(24) . '.' . $r->file($f)->extension();
                    $r->file($f)->move(public_path('uploads/' . $jenis), $nama);
                    $data[$f] = $jenis . '/' . $nama;
                    if ($lama && $lama->$f) {
                        @unlink(public_path('uploads/' . $lama->$f));
                    }
                } elseif ($r->boolean('hapus_' . $f) && $lama) {
                    @unlink(public_path('uploads/' . $lama->$f));
                    $data[$f] = null;
                }
            } elseif ($tipe === 'berkas') {
                unset($data[$f]);
                if ($r->hasFile($f)) {
                    $data[$f] = basename($r->file($f)->storeAs('berkas', Str::random(24) . '.' . strtolower($r->file($f)->getClientOriginalExtension()), 'local'));
                    if ($lama && $lama->$f) {
                        Storage::disk('local')->delete('berkas/' . $lama->$f);
                    }
                } elseif ($r->boolean('hapus_' . $f) && $lama) {
                    Storage::disk('local')->delete('berkas/' . $lama->$f);
                    $data[$f] = null;
                }
            }
        }
        if (! empty($d['slug'])) {
            $data['slug'] = $this->slugUnik($d['tabel'], $data[$d['slug']], $id);
        }
        if ($jenis === 'artikel' && empty($data['terbit_pada'])) {
            $data['terbit_pada'] = now();
        }
        $data['updated_at'] = now();
        if ($id) {
            DB::table($d['tabel'])->where('id', $id)->update($data);
        } else {
            $id = DB::table($d['tabel'])->insertGetId($data + ['created_at' => now()]);
        }
        return redirect()->route('konten.index', $jenis)->with('ok', ucfirst($d['satuan']) . ' disimpan.');
    }

    private function slugUnik(string $tabel, string $judul, ?int $id): string
    {
        $dasar = Str::slug(Str::limit($judul, 80, '')) ?: 'konten';
        $slug = $dasar;
        for ($i = 2; DB::table($tabel)->where('slug', $slug)->when($id, fn ($q) => $q->where('id', '!=', $id))->exists(); $i++) {
            $slug = $dasar . '-' . $i;
        }
        return $slug;
    }

    public function destroy(string $jenis, int $id)
    {
        $d = $this->def($jenis);
        $row = DB::table($d['tabel'])->find($id) ?? abort(404);
        foreach ($d['field'] as $f => [$tipe]) {
            if ($tipe === 'gambar' && $row->$f) {
                @unlink(public_path('uploads/' . $row->$f));
            } elseif ($tipe === 'berkas' && $row->$f) {
                Storage::disk('local')->delete('berkas/' . $row->$f);
            }
        }
        DB::table($d['tabel'])->where('id', $id)->delete();
        return back()->with('ok', ucfirst($d['satuan']) . ' dihapus.');
    }

    public function toggle(string $jenis, int $id)
    {
        $d = $this->def($jenis);
        abort_unless(isset($d['field']['tampil']), 404);
        $row = DB::table($d['tabel'])->find($id) ?? abort(404);
        DB::table($d['tabel'])->where('id', $id)->update(['tampil' => ! $row->tampil]);
        return back()->with('ok', $row->tampil ? 'Disembunyikan dari situs.' : 'Ditampilkan di situs.');
    }

    /** Unggah banyak foto galeri sekaligus. */
    public function galeriMassal(Request $r)
    {
        $r->validate(['album' => 'required|string|max:80', 'foto' => 'required|array|max:30', 'foto.*' => 'image|mimes:jpg,jpeg,png,webp|max:3072'], [], ['foto.*' => 'foto', 'foto' => 'foto']);
        foreach ($r->file('foto') as $f) {
            $nama = Str::random(24) . '.' . $f->extension();
            $judul = Str::of(pathinfo($f->getClientOriginalName(), PATHINFO_FILENAME))->replace(['-', '_'], ' ')->limit(140)->title();
            $f->move(public_path('uploads/galeri'), $nama);
            DB::table('galeri')->insert(['judul' => (string) $judul, 'album' => $r->input('album'), 'gambar' => 'galeri/' . $nama, 'tanggal' => now()->toDateString(), 'tampil' => true, 'created_at' => now(), 'updated_at' => now()]);
        }
        return redirect()->route('konten.index', 'galeri')->with('ok', count($r->file('foto')) . ' foto ditambahkan ke album ' . $r->input('album') . '.');
    }

    /** Pratinjau markdown (dipanggil dari tombol Pratinjau di form). */
    public function pratinjau(Request $r)
    {
        return response(md((string) $r->input('isi')));
    }
}
