@php
  $s = \App\Support\Situs::semua(); [$wNama, $aksen, $aksenSoft] = \App\Support\Situs::warna();
  $judulSitus = $s['judul_situs'] ?: $s['nama'];
  $menu = array_filter([
    ['beranda', 'Beranda', true], ['profil', 'Profil', true], ['karya', 'Karya', true],
    ['bahan-ajar', 'Bahan ajar', (bool) $s['tampil_bahan_ajar']], ['prestasi', 'Prestasi', true],
    ['galeri', 'Galeri', (bool) $s['tampil_galeri']], ['artikel', 'Artikel', (bool) $s['tampil_artikel']], ['kontak', 'Kontak', true],
  ], fn ($m) => $m[2]);
  $inisial = collect(preg_split('/\s+/', preg_replace('/^(Dr|Dra|Drs|Prof|Hj|H)\.?\s+/i', '', $s['nama'])))->take(2)->map(fn ($p) => mb_substr($p, 0, 1))->implode('');
@endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ isset($judul) ? $judul . ' · ' . $judulSitus : $judulSitus . ($s['jabatan'] ? ' · ' . $s['jabatan'] : '') }}</title>
<meta name="description" content="{{ $deskripsi ?? ($s['meta_deskripsi'] ?: $s['bio_singkat']) }}">
<meta property="og:title" content="{{ $judul ?? $judulSitus }}"><meta property="og:description" content="{{ $deskripsi ?? ($s['meta_deskripsi'] ?: $s['bio_singkat']) }}">
<meta property="og:image" content="{{ $ogImage ?? gambar($s['foto'] ?: null, 'img/sampul.svg') }}"><meta property="og:type" content="website">
<link rel="stylesheet" href="{{ asset('css/situs.css') }}">
<link rel="icon" href="{{ asset('favicon.ico') }}">
<style>:root { --aksen: {{ $aksen }}; --aksen-soft: {{ $aksenSoft }}; }</style>
</head>
<body>
<header class="nav">
  <div class="wrap">
    <a class="logo" href="{{ route('beranda') }}"><span class="mono">{{ $inisial ?: 'G' }}</span><span><b>{{ $s['nama'] }}</b><small>{{ trim($s['jabatan'] . ($s['mapel'] ? ' ' . $s['mapel'] : '')) }}</small></span></a>
    <nav class="menu" id="menu" aria-label="Menu utama">
      @foreach ($menu as [$r, $l])
        <a href="{{ route($r) }}" class="{{ request()->routeIs($r, $r . '.*') ? 'on' : '' }}">{{ $l }}</a>
      @endforeach
    </nav>
    @if (\App\Support\Situs::waLink())<a class="btn kecil hubungi" href="{{ \App\Support\Situs::waLink('Halo ' . $s['nama'] . ', saya melihat portofolio Anda.') }}" target="_blank" rel="noopener">{!! ikon('wa') !!} Hubungi</a>@endif
    <button class="tombol-menu" type="button" aria-label="Buka menu" aria-controls="menu" onclick="document.getElementById('menu').classList.toggle('buka')">{!! ikon('menu') !!}</button>
  </div>
</header>
<main>@yield('isi')</main>
<footer>
  <div class="wrap">
    <div style="max-width:380px">
      <a class="logo" href="{{ route('beranda') }}"><span class="mono">{{ $inisial ?: 'G' }}</span><span><b>{{ $s['nama'] }}</b><small>{{ $s['sekolah'] }}</small></span></a>
      <p class="muted kecil" style="margin-top:14px">{{ $s['bio_singkat'] }}</p>
      <div class="sosmed">
        @foreach (['instagram' => 'ig', 'youtube' => 'yt', 'facebook' => 'fb', 'linkedin' => 'in', 'tiktok' => 'tt'] as $k => $ic)
          @if ($s[$k])<a href="{{ $s[$k] }}" target="_blank" rel="noopener" aria-label="{{ ucfirst($k) }}">{!! ikon($ic) !!}</a>@endif
        @endforeach
        @if ($s['email'])<a href="mailto:{{ $s['email'] }}" aria-label="Email">{!! ikon('surat') !!}</a>@endif
      </div>
    </div>
    <nav aria-label="Tautan kaki" style="display:grid;grid-template-columns:repeat(2,auto);gap:8px 40px">
      @foreach ($menu as [$r, $l])<a href="{{ route($r) }}" style="color:var(--tinta);text-decoration:none;font-weight:600">{{ $l }}</a>@endforeach
    </nav>
  </div>
  <div class="wrap muted kecil" style="margin-top:36px">&copy; {{ date('Y') }} {{ $s['nama'] }}. Seluruh hak cipta dilindungi.</div>
</footer>
@stack('skrip')
</body>
</html>
