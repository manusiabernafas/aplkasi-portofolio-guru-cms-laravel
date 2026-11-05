@extends('situs.layout', ['judul' => $k->judul, 'deskripsi' => $k->ringkasan, 'ogImage' => gambar($k->sampul)])
@section('isi')
<section class="halaman-h"><div class="wrap"><a href="{{ route('karya') }}" class="kecil" style="font-weight:700;text-decoration:none">&larr; Semua karya</a>
  <div style="margin-top:16px"><span class="label">{{ $k->jenis }}</span></div><h1 style="max-width:900px">{{ $k->judul }}</h1>
  <div class="meta" style="margin-top:16px"><span>{!! ikon('kalender') !!}{{ $k->tahun }}</span><span>{!! ikon('mata') !!}{{ $k->dilihat }} kali dilihat</span>@if ($k->berkas)<span>{!! ikon('unduh') !!}{{ $k->diunduh }} unduhan</span>@endif</div></div></section>
<section><div class="wrap grid dua atas">
  <div>
    @if ($k->ringkasan)<p style="font-size:19px;font-weight:600;margin:0 0 24px">{{ $k->ringkasan }}</p>@endif
    <div class="prosa">{!! md($k->isi) !!}</div>
  </div>
  <aside class="kartu lekat"><img class="sampul" src="{{ gambar($k->sampul) }}" alt="">
    <div class="isi">
      @if ($k->berkas)<a class="btn" href="{{ route('unduh', ['karya', $k->id]) }}">{!! ikon('unduh') !!} Unduh berkas</a>@endif
      @if ($k->tautan)<a class="btn garis" href="{{ $k->tautan }}" target="_blank" rel="noopener nofollow">{!! ikon('link') !!} Buka tautan</a>@endif
      @if (! $k->berkas && ! $k->tautan)<p class="muted kecil" style="margin:0">Berkas tidak tersedia untuk diunduh.</p>@endif
    </div>
  </aside>
</div></section>
@if ($lain->isNotEmpty())<section class="seksi"><div class="wrap"><div class="seksi-h"><div><span class="kicker">Lainnya</span><h2>Karya lain</h2></div></div><div class="grid g3">@foreach ($lain as $k)@include('situs._karya')@endforeach</div></div></section>@endif
@endsection
