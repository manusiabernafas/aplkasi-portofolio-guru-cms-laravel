@extends('situs.layout', ['judul' => 'Karya & publikasi'])
@section('isi')
<section class="halaman-h"><div class="wrap"><span class="kicker">Karya & publikasi</span><h1>Karya saya</h1><p>Penelitian tindakan kelas, artikel, buku, modul ajar, dan media pembelajaran. Silakan dipelajari dan diunduh.</p></div></section>
<section style="padding-bottom:20px"><div class="wrap">
  <div class="filter"><a href="{{ route('karya') }}" class="{{ $jenis ? '' : 'on' }}">Semua</a>@foreach ($jenisAda as $j => $n)<a href="{{ route('karya', ['jenis' => $j]) }}" class="{{ $jenis === $j ? 'on' : '' }}">{{ $j }} ({{ $n }})</a>@endforeach</div>
  @if ($daftar->isEmpty())<div class="kosong">Belum ada karya untuk kategori ini.</div>@endif
  <div class="grid g3">@foreach ($daftar as $k)@include('situs._karya')@endforeach</div>
  @include('situs._pagin', ['p' => $daftar])
</div></section>
@endsection
