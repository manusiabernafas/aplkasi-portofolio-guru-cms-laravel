@extends('admin.layout', ['judul' => 'Dasbor'])
@section('aksi')<a class="btn garis" href="{{ route('beranda') }}" target="_blank" rel="noopener">{!! ikon('link') !!} Lihat situs</a><a class="btn" href="{{ route('konten.create', 'karya') }}">{!! ikon('plus') !!} Karya baru</a>@endsection
@section('isi')
@php $selesai = collect($kelengkapan)->where(1, true)->count(); $persen = round($selesai / count($kelengkapan) * 100); @endphp
<div class="grid g4">
  <a class="stat" href="{{ route('konten.index', 'karya') }}"><div class="bulat">{!! ikon('buku') !!}</div><div><b>{{ $hitung['karya'] }}</b><span>Karya</span></div></a>
  <a class="stat" href="{{ route('konten.index', 'bahan-ajar') }}"><div class="bulat">{!! ikon('map') !!}</div><div><b>{{ $hitung['bahan-ajar'] }}</b><span>Bahan ajar</span></div></a>
  <div class="stat"><div class="bulat">{!! ikon('unduh') !!}</div><div><b>{{ number_format($unduhan, 0, ',', '.') }}</b><span>Total unduhan</span></div></div>
  <a class="stat" href="{{ route('pesan.index') }}"><div class="bulat">{!! ikon('surat') !!}</div><div><b>{{ $pesanBaru }}</b><span>Pesan belum dibaca</span></div></a>
</div>
<div class="grid g2" style="margin-top:18px;align-items:start">
  <div>
    <div class="panel"><h2>Kelengkapan portofolio</h2>
      <div class="kecil muted">{{ $selesai }} dari {{ count($kelengkapan) }} langkah selesai</div><div class="progres"><i style="width:{{ $persen }}%"></i></div>
      <ul class="cek-list">@foreach ($kelengkapan as [$nm, $ok, $rute])<li><span class="t {{ $ok ? 'ya' : '' }}">@if ($ok){!! ikon('cek') !!}@endif</span>{{ $nm }}@unless ($ok)<a href="{{ str_starts_with($rute, 'admin.') ? route($rute) : route('konten.index', $rute) }}">Lengkapi</a>@endunless</li>@endforeach</ul>
    </div>
    <div class="panel"><h2>Pesan terbaru</h2>
      @if ($pesan->isEmpty())<p class="muted">Belum ada pesan dari pengunjung.</p>@endif
      <ul class="cek-list">@foreach ($pesan as $p)<li class="{{ $p->dibaca ? '' : 'belum' }}"><span style="min-width:0"><b style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $p->subjek }}</b><span class="kecil muted">{{ $p->nama }} · {{ tgl($p->created_at) }}</span></span><a href="{{ route('pesan.show', $p->id) }}">Baca</a></li>@endforeach</ul>
    </div>
  </div>
  <div>
    <div class="panel"><h2>Karya paling banyak diunduh</h2>
      <div class="tabel-wrap"><table><thead><tr><th>Judul</th><th>Dilihat</th><th>Unduh</th></tr></thead><tbody>
      @forelse ($populer as $k)<tr><td>{{ $k->judul }}<div class="kecil muted">{{ $k->jenis }}</div></td><td>{{ $k->dilihat }}</td><td><b>{{ $k->diunduh }}</b></td></tr>@empty<tr><td colspan="3" class="muted">Belum ada karya.</td></tr>@endforelse
      </tbody></table></div>
    </div>
    <div class="panel"><h2>Artikel paling banyak dibaca</h2>
      <div class="tabel-wrap"><table><thead><tr><th>Judul</th><th>Dibaca</th></tr></thead><tbody>
      @forelse ($artikelPopuler as $a)<tr><td>{{ $a->judul }}<div class="kecil muted">{{ tgl($a->terbit_pada) }}</div></td><td><b>{{ $a->dilihat }}</b></td></tr>@empty<tr><td colspan="2" class="muted">Belum ada artikel.</td></tr>@endforelse
      </tbody></table></div>
    </div>
    <div class="panel"><h2>Isi lainnya</h2>
      <div class="grid g3" style="gap:10px">
        <a class="btn garis" href="{{ route('konten.index', 'artikel') }}">{!! ikon('pena') !!} {{ $hitung['artikel'] }} artikel</a>
        <a class="btn garis" href="{{ route('konten.index', 'prestasi') }}">{!! ikon('trofi') !!} {{ $hitung['prestasi'] }} prestasi</a>
        <a class="btn garis" href="{{ route('konten.index', 'galeri') }}">{!! ikon('foto') !!} {{ $hitung['galeri'] }} foto</a>
      </div>
    </div>
  </div>
</div>
@endsection
