@extends('situs.layout', ['judul' => 'Kontak'])
@section('isi')
@php $s = \App\Support\Situs::semua(); @endphp
<section class="halaman-h"><div class="wrap"><span class="kicker">Kontak</span><h1>Mari terhubung</h1><p>Untuk undangan narasumber, kolaborasi, atau pertanyaan seputar pembelajaran.</p></div></section>
<section style="padding-bottom:20px"><div class="wrap grid dua kiri atas">
  <div class="grid" style="gap:14px">
    @if ($s['email'])<a class="baris" href="mailto:{{ $s['email'] }}" style="text-decoration:none;color:inherit"><div class="ikon-b">{!! ikon('surat') !!}</div><div class="teks"><h3>Email</h3><div class="muted">{{ $s['email'] }}</div></div></a>@endif
    @if (\App\Support\Situs::waLink())<a class="baris" href="{{ \App\Support\Situs::waLink() }}" target="_blank" rel="noopener" style="text-decoration:none;color:inherit"><div class="ikon-b">{!! ikon('wa') !!}</div><div class="teks"><h3>WhatsApp</h3><div class="muted">{{ $s['wa'] }}</div></div></a>@endif
    @if ($s['alamat'])<div class="baris"><div class="ikon-b">{!! ikon('pin') !!}</div><div class="teks"><h3>Alamat</h3><div class="muted">{{ $s['alamat'] }}</div></div></div>@endif
  </div>
  <form class="form kartu" method="post" action="{{ route('kontak.kirim') }}" style="padding:28px" novalidate>
    @csrf
    @if (session('ok'))<div class="alert ok" role="status">{{ session('ok') }}</div>@endif
    @if (session('error'))<div class="alert err" role="alert">{{ session('error') }}</div>@endif
    <h2 style="font-size:24px;margin-bottom:20px">Kirim pesan</h2>
    <div class="grid g2" style="gap:0 16px">
      <div class="f"><label for="nama">Nama</label><input id="nama" name="nama" value="{{ old('nama') }}" maxlength="100" required>@error('nama')<div class="galat">{{ $message }}</div>@enderror</div>
      <div class="f"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" maxlength="150" required>@error('email')<div class="galat">{{ $message }}</div>@enderror</div>
    </div>
    <div class="f"><label for="subjek">Subjek</label><input id="subjek" name="subjek" value="{{ old('subjek') }}" maxlength="150" required>@error('subjek')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="f"><label for="isi">Pesan</label><textarea id="isi" name="isi" maxlength="3000" required>{{ old('isi') }}</textarea>@error('isi')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="hp" aria-hidden="true"><label for="situs_web">Jangan diisi</label><input id="situs_web" name="situs_web" tabindex="-1" autocomplete="off"></div>
    <button class="btn">{!! ikon('surat') !!} Kirim pesan</button>
  </form>
</div></section>
@endsection
