@extends('admin.layout', ['judul' => 'Profil & tampilan'])
@section('aksi')<a class="btn garis" href="{{ route('profil') }}" target="_blank" rel="noopener">{!! ikon('link') !!} Lihat profil</a>@endsection
@section('isi')
@php $v = fn ($k) => old($k, $s[$k]); @endphp
<form class="form" method="post" action="{{ route('admin.profil.simpan') }}" enctype="multipart/form-data" novalidate>
  @csrf
  <div class="grid g2" style="align-items:start">
    <div>
      <div class="panel"><h2>Identitas</h2>
        <div class="f"><label for="nama">Nama lengkap & gelar <span style="color:var(--merah)">*</span></label><input id="nama" name="nama" value="{{ $v('nama') }}" class="@error('nama') is-galat @enderror">@error('nama')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="grid g2" style="gap:0 14px">
          <div class="f"><label for="jabatan">Jabatan <span style="color:var(--merah)">*</span></label><input id="jabatan" name="jabatan" value="{{ $v('jabatan') }}" placeholder="Guru">@error('jabatan')<div class="galat">{{ $message }}</div>@enderror</div>
          <div class="f"><label for="mapel">Mata pelajaran</label><input id="mapel" name="mapel" value="{{ $v('mapel') }}" placeholder="Biologi">@error('mapel')<div class="galat">{{ $message }}</div>@enderror</div>
        </div>
        <div class="f"><label for="sekolah">Sekolah</label><input id="sekolah" name="sekolah" value="{{ $v('sekolah') }}">@error('sekolah')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="grid g3" style="gap:0 14px">
          <div class="f"><label for="nip">NIP / NUPTK</label><input id="nip" name="nip" value="{{ $v('nip') }}">@error('nip')<div class="galat">{{ $message }}</div>@enderror</div>
          <div class="f"><label for="tahun_mulai">Mulai mengajar</label><input id="tahun_mulai" type="number" name="tahun_mulai" value="{{ $v('tahun_mulai') }}" placeholder="{{ date('Y') - 10 }}">@error('tahun_mulai')<div class="galat">{{ $message }}</div>@enderror</div>
          <div class="f"><label for="jumlah_siswa">Siswa diajar</label><input id="jumlah_siswa" type="number" name="jumlah_siswa" value="{{ $v('jumlah_siswa') }}">@error('jumlah_siswa')<div class="galat">{{ $message }}</div>@enderror</div>
        </div>
        <div class="f"><label for="bio_singkat">Bio singkat</label><textarea id="bio_singkat" name="bio_singkat" style="min-height:80px" maxlength="300">{{ $v('bio_singkat') }}</textarea><div class="hint">Satu-dua kalimat di halaman depan (maks 300 karakter).</div>@error('bio_singkat')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="f"><label for="tentang">Tentang saya</label><textarea id="tentang" name="tentang" class="md" style="min-height:220px">{{ $v('tentang') }}</textarea><div class="hint">Tampil di halaman Profil. Mendukung Markdown.</div>@error('tentang')<div class="galat">{{ $message }}</div>@enderror</div>
      </div>
      <div class="panel"><h2>Foto & CV</h2>
        <div class="f"><label for="foto">Foto profil</label>
          @if ($s['foto'])<div class="ada-berkas"><img src="{{ gambar($s['foto']) }}" alt="" style="width:80px;height:100px"></div>@endif
          <input id="foto" type="file" name="foto" accept="image/jpeg,image/png,image/webp"><div class="hint">Potret tegak, JPG/PNG/WEBP maks 2 MB.</div>@error('foto')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="f"><label for="cv">CV (PDF)</label>
          @if ($s['cv'])<div class="ada-berkas">{!! ikon('map') !!} CV tersimpan · <a href="{{ route('cv') }}">unduh</a> <label class="cek kecil"><input type="checkbox" name="hapus_cv" value="1"> Hapus CV</label></div>@endif
          <input id="cv" type="file" name="cv" accept="application/pdf"><div class="hint">PDF maks 5 MB. Pengunjung bisa mengunduh dari halaman Profil.</div>@error('cv')<div class="galat">{{ $message }}</div>@enderror</div>
      </div>
    </div>
    <div>
      <div class="panel"><h2>Kontak & media sosial</h2>
        <div class="grid g2" style="gap:0 14px">
          <div class="f"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ $v('email') }}">@error('email')<div class="galat">{{ $message }}</div>@enderror</div>
          <div class="f"><label for="wa">WhatsApp</label><input id="wa" name="wa" value="{{ $v('wa') }}" placeholder="0812 3456 7890" inputmode="tel">@error('wa')<div class="galat">{{ $message }}</div>@enderror</div>
        </div>
        <div class="f"><label for="alamat">Alamat sekolah</label><input id="alamat" name="alamat" value="{{ $v('alamat') }}">@error('alamat')<div class="galat">{{ $message }}</div>@enderror</div>
        @foreach (['instagram' => 'Instagram', 'youtube' => 'YouTube', 'facebook' => 'Facebook', 'linkedin' => 'LinkedIn', 'tiktok' => 'TikTok'] as $k => $l)
          <div class="f"><label for="{{ $k }}">{{ $l }}</label><input id="{{ $k }}" type="url" name="{{ $k }}" value="{{ $v($k) }}" placeholder="https://">@error($k)<div class="galat">{{ $message }}</div>@enderror</div>
        @endforeach
      </div>
      <div class="panel"><h2>Tampilan situs</h2>
        <div class="f"><label>Warna aksen</label><div class="warna-pilih">@foreach (\App\Support\Konten::WARNA as $k => [$nm, $hex])<label><input type="radio" name="warna" value="{{ $k }}" @checked($v('warna') === $k)><i style="background:{{ $hex }}"></i>{{ $nm }}</label>@endforeach</div>@error('warna')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="f"><label>Menu yang ditampilkan</label>
          @foreach (['tampil_bahan_ajar' => 'Bahan ajar', 'tampil_galeri' => 'Galeri', 'tampil_artikel' => 'Artikel', 'tampil_testimoni' => 'Testimoni di beranda'] as $k => $l)
            <label class="cek" style="margin-bottom:8px"><input type="checkbox" name="{{ $k }}" value="1" @checked(old('_token') ? old($k) : $s[$k])> {{ $l }}</label>
          @endforeach
        </div>
        <div class="f"><label for="judul_situs">Judul situs</label><input id="judul_situs" name="judul_situs" value="{{ $v('judul_situs') }}" placeholder="{{ $s['nama'] }}"><div class="hint">Kosongkan untuk memakai nama Anda.</div>@error('judul_situs')<div class="galat">{{ $message }}</div>@enderror</div>
        <div class="f"><label for="meta_deskripsi">Deskripsi untuk Google</label><textarea id="meta_deskripsi" name="meta_deskripsi" style="min-height:80px" maxlength="300">{{ $v('meta_deskripsi') }}</textarea>@error('meta_deskripsi')<div class="galat">{{ $message }}</div>@enderror</div>
      </div>
      <button class="btn" style="width:100%;justify-content:center;padding:13px;margin-top:18px">{!! ikon('cek') !!} Simpan profil & tampilan</button>
    </div>
  </div>
</form>
<form class="form panel" method="post" action="{{ route('admin.password') }}" style="max-width:560px;margin-top:18px" novalidate>
  @csrf
  <h2>Ganti kata sandi</h2>
  <div class="f"><label for="password_lama">Kata sandi lama</label><input id="password_lama" type="password" name="password_lama" autocomplete="current-password">@error('password_lama')<div class="galat">{{ $message }}</div>@enderror</div>
  <div class="grid g2" style="gap:0 14px">
    <div class="f"><label for="password">Kata sandi baru</label><input id="password" type="password" name="password" autocomplete="new-password">@error('password')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="f"><label for="password_confirmation">Ulangi</label><input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password"></div>
  </div>
  <div class="hint" style="margin:-6px 0 14px">Minimal 8 karakter, berisi huruf dan angka.</div>
  <button class="btn garis">Ganti kata sandi</button>
</form>
@endsection
