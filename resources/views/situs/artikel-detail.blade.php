@extends('situs.layout', ['judul' => $a->judul, 'deskripsi' => $a->ringkasan, 'ogImage' => gambar($a->sampul)])
@section('isi')
<article>
<section class="halaman-h"><div class="wrap" style="max-width:820px"><a href="{{ route('artikel') }}" class="kecil" style="font-weight:700;text-decoration:none">&larr; Semua artikel</a>
  <div style="margin-top:16px"><span class="label kuning">{{ $a->kategori }}</span></div><h1>{{ $a->judul }}</h1>
  <div class="meta" style="margin-top:16px"><span>{!! ikon('user') !!}{{ situs('nama') }}</span><span>{!! ikon('kalender') !!}{{ tgl($a->terbit_pada) }}</span><span>{!! ikon('mata') !!}{{ $a->dilihat }} kali dibaca</span></div></div></section>
<div class="wrap" style="max-width:820px">
  @if ($a->sampul)<img src="{{ gambar($a->sampul) }}" alt="" style="border-radius:24px;width:100%;aspect-ratio:16/8;object-fit:cover;margin-bottom:34px">@endif
  <div class="prosa">{!! md($a->isi) !!}</div>
</div>
</article>
@if ($lain->isNotEmpty())<section class="seksi"><div class="wrap"><div class="seksi-h"><div><span class="kicker">Baca juga</span><h2>Artikel lainnya</h2></div></div><div class="grid g3">@foreach ($lain as $a)@include('situs._artikel')@endforeach</div></div></section>@endif
@endsection
