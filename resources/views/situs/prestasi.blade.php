@extends('situs.layout', ['judul' => 'Prestasi'])
@section('isi')
<section class="halaman-h"><div class="wrap"><span class="kicker">Prestasi</span><h1>Capaian & penghargaan</h1><p>Prestasi pribadi sebagai pendidik dan prestasi siswa yang saya bimbing.</p></div></section>
<section style="padding-bottom:20px"><div class="wrap">
  @if ($daftar->isEmpty())<div class="kosong">Belum ada prestasi.</div>@endif
  @foreach ($daftar->groupBy('tahun') as $th => $list)
    <h2 style="font-size:24px;margin:26px 0 16px">{{ $th }}</h2>
    <div class="grid g2">@foreach ($list as $p)@include('situs._prestasi')@endforeach</div>
  @endforeach
</div></section>
@endsection
