@php
  $s = \App\Support\Situs::semua(); [, $aksen, $aksenSoft] = \App\Support\Situs::warna();
  $pesanBaru = \Illuminate\Support\Facades\DB::table('pesan')->where('dibaca', false)->count();
  $jenisAktif = request()->route('jenis');
  $grup = [
    'Portofolio' => ['karya', 'bahan-ajar', 'artikel', 'prestasi', 'galeri', 'testimoni'],
    'Riwayat' => ['pendidikan', 'pengalaman', 'sertifikat', 'keahlian'],
  ];
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex">
<title>{{ $judul ?? 'Panel' }} · Admin Portofolio</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}">
<style>:root { --aksen: {{ $aksen }}; --aksen-soft: {{ $aksenSoft }}; }</style>
</head>
<body>
<div class="app">
  <aside class="side" id="side">
    <a class="merek" href="{{ route('admin.dasbor') }}"><span class="mono">{{ mb_substr($s['nama'], 0, 1) }}</span><span><b>Portofolio</b><small>Panel guru</small></span></a>
    <a class="nav {{ request()->routeIs('admin.dasbor') ? 'on' : '' }}" href="{{ route('admin.dasbor') }}">{!! ikon('home') !!} Dasbor</a>
    <a class="nav {{ request()->routeIs('pesan.*') ? 'on' : '' }}" href="{{ route('pesan.index') }}">{!! ikon('surat') !!} Pesan masuk @if ($pesanBaru)<span class="jml">{{ $pesanBaru }}</span>@endif</a>
    <a class="nav {{ request()->routeIs('admin.profil') ? 'on' : '' }}" href="{{ route('admin.profil') }}">{!! ikon('user') !!} Profil & tampilan</a>
    @foreach ($grup as $g => $daftar)
      <div class="grup">{{ $g }}</div>
      @foreach ($daftar as $j) @php $d = \App\Support\Konten::get($j); @endphp
        <a class="nav {{ $jenisAktif === $j ? 'on' : '' }}" href="{{ route('konten.index', $j) }}">{!! ikon($d['ikon']) !!} {{ $d['label'] }}</a>
      @endforeach
    @endforeach
    <div class="bawah">
      <a class="nav" href="{{ route('beranda') }}" target="_blank" rel="noopener">{!! ikon('link') !!} Lihat situs</a>
      <form method="post" action="{{ route('logout') }}">@csrf<button class="nav" type="submit">{!! ikon('keluar') !!} Keluar</button></form>
    </div>
  </aside>
  <main class="utama">
    <div class="atas">
      <button class="tombol-menu" type="button" aria-label="Buka menu" aria-controls="side" onclick="document.getElementById('side').classList.toggle('buka')">{!! ikon('menu') !!}</button>
      <h1>{{ $judul ?? 'Panel' }}</h1>
      @yield('aksi')
    </div>
    @if (session('ok'))<div class="alert ok" role="status">{!! ikon('cek') !!} {{ session('ok') }}</div>@endif
    @if (session('error'))<div class="alert err" role="alert">{{ session('error') }}</div>@endif
    @if ($errors->any())<div class="alert err" role="alert">Periksa kembali isian yang ditandai merah.</div>@endif
    @yield('isi')
  </main>
</div>
<script>
document.addEventListener('click', e => { const s = document.getElementById('side'); if (s.classList.contains('buka') && !s.contains(e.target) && !e.target.closest('.tombol-menu')) s.classList.remove('buka'); });
document.querySelectorAll('form[data-yakin]').forEach(f => f.addEventListener('submit', e => { if (!confirm(f.dataset.yakin)) e.preventDefault(); }));
</script>
@stack('skrip')
</body>
</html>
