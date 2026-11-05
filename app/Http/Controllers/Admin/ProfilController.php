<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\Konten;
use App\Support\Situs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfilController extends Controller
{
    public function index()
    {
        return view('admin.profil', ['s' => Situs::semua()]);
    }

    public function simpan(Request $r)
    {
        $data = $r->validate([
            'nama' => 'required|string|max:120', 'jabatan' => 'required|string|max:120', 'mapel' => 'nullable|string|max:120', 'sekolah' => 'nullable|string|max:150',
            'nip' => 'nullable|string|max:30', 'bio_singkat' => 'nullable|string|max:300', 'tentang' => 'nullable|string|max:20000',
            'tahun_mulai' => 'nullable|integer|between:1960,' . date('Y'), 'jumlah_siswa' => 'nullable|integer|between:0,1000000',
            'email' => 'nullable|email|max:150', 'wa' => ['nullable', 'regex:/^[0-9+ ]{8,20}$/'], 'alamat' => 'nullable|string|max:200',
            'instagram' => 'nullable|url:http,https|max:200', 'youtube' => 'nullable|url:http,https|max:200', 'facebook' => 'nullable|url:http,https|max:200',
            'linkedin' => 'nullable|url:http,https|max:200', 'tiktok' => 'nullable|url:http,https|max:200',
            'judul_situs' => 'nullable|string|max:100', 'meta_deskripsi' => 'nullable|string|max:300', 'warna' => 'required|in:' . implode(',', array_keys(Konten::WARNA)),
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', 'cv' => 'nullable|file|extensions:pdf|max:5120',
        ], [], ['wa' => 'nomor WhatsApp', 'bio_singkat' => 'bio singkat', 'tahun_mulai' => 'tahun mulai mengajar', 'jumlah_siswa' => 'jumlah siswa', 'cv' => 'CV']);
        foreach (['tampil_testimoni', 'tampil_bahan_ajar', 'tampil_galeri', 'tampil_artikel'] as $k) {
            $data[$k] = $r->boolean($k) ? '1' : '';
        }
        unset($data['foto'], $data['cv']);
        if ($r->hasFile('foto')) {
            $nama = 'profil/' . Str::random(20) . '.' . $r->file('foto')->extension();
            $r->file('foto')->move(public_path('uploads/profil'), basename($nama));
            if (Situs::get('foto')) {
                @unlink(public_path('uploads/' . Situs::get('foto')));
            }
            $data['foto'] = $nama;
        }
        if ($r->hasFile('cv')) {
            $data['cv'] = basename($r->file('cv')->storeAs('berkas', 'cv-' . Str::random(16) . '.pdf', 'local'));
            if (Situs::get('cv')) {
                Storage::disk('local')->delete('berkas/' . Situs::get('cv'));
            }
        } elseif ($r->boolean('hapus_cv')) {
            Storage::disk('local')->delete('berkas/' . Situs::get('cv'));
            $data['cv'] = '';
        }
        Situs::simpan(array_map(fn ($v) => $v ?? '', $data));
        return back()->with('ok', 'Profil & pengaturan disimpan.');
    }

    public function password(Request $r)
    {
        $r->validate(['password_lama' => 'required|current_password', 'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()]], [], ['password_lama' => 'kata sandi lama', 'password' => 'kata sandi baru']);
        $u = Auth::user();
        $u->password = Hash::make($r->input('password'));
        $u->save();
        return back()->with('ok', 'Kata sandi berhasil diganti.');
    }
}
