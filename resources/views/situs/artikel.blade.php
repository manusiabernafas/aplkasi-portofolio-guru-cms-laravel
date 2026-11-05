@extends('situs.layout', ['judul' => 'Artikel'])
@section('isi')
<section class="halaman-h"><div class="wrap"><span class="kicker">Artikel</span><h1>Catatan dari kelas</h1><p>Refleksi, tips belajar, dan praktik baik pembelajaran.</p></div></section>
<section style="padding-bottom:20px"><div class="wrap">
  <div class="filter"><a href="{{ route('artikel') }}" class="{{ $kategori ? '' : 'on' }}">Semua</a>@foreach ($kategoriAda as $k)<a href="{{ route('artikel', ['kategori' => $k]) }}" class="{{ $kategori === $k ? 'on' : '' }}">{{ $k }}</a>@endforeach</div>
  @if ($daftar->isEmpty())<div class="kosong">Belum ada artikel.</div>@endif
  <div class="grid g3">@foreach ($daftar as $a)@include('situs._artikel')@endforeach</div>
  @include('situs._pagin', ['p' => $daftar])
</div></section>
@endsection
