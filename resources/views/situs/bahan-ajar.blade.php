@extends('situs.layout', ['judul' => 'Bahan ajar'])
@section('isi')
<section class="halaman-h"><div class="wrap"><span class="kicker">Untuk siswa</span><h1>Bahan ajar</h1><p>Materi, lembar kerja, dan video pembelajaran. Pilih kelasmu lalu unduh materinya.</p></div></section>
<section style="padding-bottom:20px"><div class="wrap">
  <form method="get" class="form" style="display:flex;gap:10px;max-width:520px;margin-bottom:18px">@if ($kelas)<input type="hidden" name="kelas" value="{{ $kelas }}">@endif<input type="search" name="q" value="{{ $cari }}" placeholder="Cari judul atau mapel" aria-label="Cari bahan ajar"><button class="btn">Cari</button></form>
  <div class="filter"><a href="{{ route('bahan-ajar') }}" class="{{ $kelas ? '' : 'on' }}">Semua kelas</a>@foreach ($kelasAda as $k)<a href="{{ route('bahan-ajar', ['kelas' => $k]) }}" class="{{ $kelas === $k ? 'on' : '' }}">Kelas {{ $k }}</a>@endforeach</div>
  @if ($daftar->isEmpty())<div class="kosong">Bahan ajar tidak ditemukan.</div>@endif
  <div class="grid" style="gap:14px">
    @foreach ($daftar as $b)
      <div class="baris"><div class="ikon-b">{!! ikon('map') !!}</div>
        <div class="teks"><h3>{{ $b->judul }}</h3><div class="muted kecil">Kelas {{ $b->kelas }} · {{ $b->mapel }}{{ $b->berkas ? ' · ' . $b->diunduh . ' unduhan' : '' }}</div>@if ($b->deskripsi)<p class="kecil" style="margin:6px 0 0">{{ $b->deskripsi }}</p>@endif</div>
        <div class="tombol">@if ($b->tautan_video)<a class="btn garis kecil" href="{{ $b->tautan_video }}" target="_blank" rel="noopener nofollow">{!! ikon('video') !!} Video</a>@endif @if ($b->berkas)<a class="btn kecil" href="{{ route('unduh', ['bahan-ajar', $b->id]) }}">{!! ikon('unduh') !!} Unduh</a>@endif</div></div>
    @endforeach
  </div>
</div></section>
@endsection
