@extends('situs.layout')
@section('isi')
@php $s = \App\Support\Situs::semua(); $lama = \App\Support\Situs::lamaMengajar(); @endphp
<section class="hero">
  <div class="wrap">
    <div>
      <span class="kicker">{{ trim($s['jabatan'] . ($s['mapel'] ? ' ' . $s['mapel'] : '')) }}{{ $s['sekolah'] ? ' · ' . $s['sekolah'] : '' }}</span>
      <h1>Halo, saya <span>{{ $s['nama'] }}</span></h1>
      <p class="bio">{{ $s['bio_singkat'] }}</p>
      <div class="aksi">
        <a class="btn" href="{{ route('karya') }}">Lihat karya saya {!! ikon('panah') !!}</a>
        @if ($s['cv'])<a class="btn garis" href="{{ route('cv') }}">{!! ikon('unduh') !!} Unduh CV</a>@else<a class="btn garis" href="{{ route('profil') }}">Profil lengkap</a>@endif
      </div>
      <div class="angka">
        @if ($lama)<div><b>{{ $lama }}+</b><span>tahun mengajar</span></div>@endif
        @if ($s['jumlah_siswa'])<div><b>{{ number_format((int) $s['jumlah_siswa'], 0, ',', '.') }}+</b><span>siswa dibimbing</span></div>@endif
        <div><b>{{ $angka['karya'] }}</b><span>karya & publikasi</span></div>
        <div><b>{{ $angka['prestasi'] }}</b><span>prestasi</span></div>
      </div>
    </div>
    <div class="foto">
      @if ($s['foto'])<img src="{{ gambar($s['foto']) }}" alt="Foto {{ $s['nama'] }}">@else<div class="inisial">{{ mb_substr($s['nama'], 0, 1) }}</div>@endif
      <div class="lencana">{!! ikon('medali') !!}<div><b>{{ $angka['sertifikat'] }} sertifikat</b><span>pelatihan & pengembangan diri</span></div></div>
    </div>
  </div>
</section>

@if ($keahlian->isNotEmpty())
<section class="seksi alt">
  <div class="wrap grid g2" style="gap:56px;align-items:center">
    <div><span class="kicker">Tentang saya</span><h2 style="font-size:clamp(28px,3.4vw,40px);margin:14px 0 16px">Mengajar dengan hati, belajar tanpa henti.</h2>
      <div class="prosa" style="font-size:16.5px">{!! md(implode("\n\n", array_slice(preg_split('/\R{2,}/', trim($s['tentang'])), 0, 2))) !!}</div>
      <a class="btn garis kecil" href="{{ route('profil') }}">Selengkapnya {!! ikon('panah') !!}</a></div>
    <div>@foreach ($keahlian as $k)<div class="bar"><div class="nm"><span>{{ $k->nama }}</span><span class="muted">{{ $k->level }}%</span></div><div class="jalur"><i style="width:{{ $k->level }}%"></i></div></div>@endforeach</div>
  </div>
</section>
@endif

@if ($karya->isNotEmpty())
<section class="seksi">
  <div class="wrap">
    <div class="seksi-h"><div><span class="kicker">Karya & publikasi</span><h2>Karya terbaru</h2><p>Penelitian, modul ajar, dan media pembelajaran yang bisa dipelajari dan diunduh.</p></div><a class="btn garis kecil" href="{{ route('karya') }}">Semua karya {!! ikon('panah') !!}</a></div>
    <div class="grid g3">@foreach ($karya as $k)@include('situs._karya')@endforeach</div>
  </div>
</section>
@endif

@if ($prestasi->isNotEmpty())
<section class="seksi alt">
  <div class="wrap">
    <div class="seksi-h"><div><span class="kicker">Prestasi</span><h2>Capaian bersama siswa</h2></div><a class="btn garis kecil" href="{{ route('prestasi') }}">Semua prestasi {!! ikon('panah') !!}</a></div>
    <div class="grid g2">@foreach ($prestasi as $p)@include('situs._prestasi')@endforeach</div>
  </div>
</section>
@endif

@if ($artikel->isNotEmpty())
<section class="seksi">
  <div class="wrap">
    <div class="seksi-h"><div><span class="kicker">Artikel</span><h2>Catatan dari kelas</h2></div><a class="btn garis kecil" href="{{ route('artikel') }}">Semua artikel {!! ikon('panah') !!}</a></div>
    <div class="grid g3">@foreach ($artikel as $a)@include('situs._artikel')@endforeach</div>
  </div>
</section>
@endif

@if ($testimoni->isNotEmpty())
<section class="seksi alt">
  <div class="wrap">
    <div class="seksi-h"><div><span class="kicker">Testimoni</span><h2>Kata mereka</h2></div></div>
    <div class="grid g3">@foreach ($testimoni as $t)<div class="quote"><p>{{ $t->isi }}</p><b>{{ $t->nama }}</b><small>{{ $t->peran }}</small></div>@endforeach</div>
  </div>
</section>
@endif

<section class="seksi">
  <div class="wrap"><div class="cta"><div><h2>Mari berkolaborasi untuk pendidikan yang lebih baik.</h2><p>Undangan narasumber, pelatihan guru, atau diskusi pembelajaran.</p></div>
    <div style="display:flex;gap:12px;flex-wrap:wrap">@if (\App\Support\Situs::waLink())<a class="btn kuning" href="{{ \App\Support\Situs::waLink('Halo ' . $s['nama'] . ', saya ingin berdiskusi.') }}" target="_blank" rel="noopener">{!! ikon('wa') !!} WhatsApp</a>@endif<a class="btn putih" href="{{ route('kontak') }}">{!! ikon('surat') !!} Kirim pesan</a></div></div></div>
</section>
@endsection
