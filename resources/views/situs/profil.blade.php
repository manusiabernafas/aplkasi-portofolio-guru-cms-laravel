@extends('situs.layout', ['judul' => 'Profil'])
@section('isi')
@php $s = \App\Support\Situs::semua(); @endphp
<section class="halaman-h"><div class="wrap"><span class="kicker">Profil</span><h1>{{ $s['nama'] }}</h1><p>{{ trim($s['jabatan'] . ($s['mapel'] ? ' ' . $s['mapel'] : '')) }}{{ $s['sekolah'] ? ' · ' . $s['sekolah'] : '' }}{{ $s['nip'] ? ' · NIP ' . $s['nip'] : '' }}</p></div></section>
<section style="padding-bottom:40px"><div class="wrap grid dua">
  <div>
    <div class="prosa">{!! md($s['tentang']) !!}</div>
    @if ($s['cv'])<a class="btn" href="{{ route('cv') }}">{!! ikon('unduh') !!} Unduh CV (PDF)</a>@endif
  </div>
  <div class="foto" style="max-width:340px;align-self:start">@if ($s['foto'])<img src="{{ gambar($s['foto']) }}" alt="Foto {{ $s['nama'] }}">@else<div class="inisial">{{ mb_substr($s['nama'], 0, 1) }}</div>@endif</div>
</div></section>
<section class="seksi alt"><div class="wrap grid g2 lebar">
  <div><span class="kicker">Pendidikan</span><h2 style="font-size:30px;margin:12px 0 28px">Riwayat pendidikan</h2>
    @if ($pendidikan->isEmpty())<p class="muted">Belum diisi.</p>@endif
    <div class="garis-waktu">@foreach ($pendidikan as $p)<div class="item"><div class="th">{{ $p->tahun_mulai ? $p->tahun_mulai . ' – ' : '' }}{{ $p->tahun_selesai ?: 'sekarang' }}</div><h3>{{ $p->jenjang }} · {{ $p->institusi }}</h3>@if ($p->jurusan)<div class="muted">{{ $p->jurusan }}</div>@endif</div>@endforeach</div></div>
  <div><span class="kicker">Pengalaman</span><h2 style="font-size:30px;margin:12px 0 28px">Pengalaman mengajar</h2>
    @if ($pengalaman->isEmpty())<p class="muted">Belum diisi.</p>@endif
    <div class="garis-waktu">@foreach ($pengalaman as $p)<div class="item"><div class="th">{{ $p->tahun_mulai }} – {{ $p->tahun_selesai ?: 'sekarang' }}</div><h3>{{ $p->jabatan }}</h3><div class="muted">{{ $p->instansi }}</div>@if ($p->deskripsi)<p class="kecil" style="margin:6px 0 0">{{ $p->deskripsi }}</p>@endif</div>@endforeach</div></div>
</div></section>
@if ($keahlian->isNotEmpty())
<section class="seksi"><div class="wrap"><div class="seksi-h"><div><span class="kicker">Kompetensi</span><h2>Bidang yang saya kuasai</h2></div></div>
  <div class="grid g2" style="gap:24px 56px">@foreach ($keahlian as $kel => $daftar)<div><h3 style="margin-bottom:16px">{{ $kel }}</h3>@foreach ($daftar as $k)<div class="bar"><div class="nm"><span>{{ $k->nama }}</span><span class="muted">{{ $k->level }}%</span></div><div class="jalur"><i style="width:{{ $k->level }}%"></i></div></div>@endforeach</div>@endforeach</div>
</div></section>
@endif
@if ($sertifikat->isNotEmpty())
<section class="seksi alt"><div class="wrap"><div class="seksi-h"><div><span class="kicker">Pengembangan diri</span><h2>Pelatihan & sertifikat</h2><p>Total {{ $sertifikat->sum('jam_pelajaran') }} JP dari {{ $sertifikat->count() }} kegiatan.</p></div></div>
  <div class="grid g2">@foreach ($sertifikat as $c)<div class="pres"><div class="medali">{!! ikon('medali') !!}</div><div><h3>{{ $c->judul }}</h3><div class="muted kecil">{{ $c->penyelenggara }} · {{ $c->tahun }}{{ $c->jam_pelajaran ? ' · ' . $c->jam_pelajaran . ' JP' : '' }}</div></div></div>@endforeach</div>
</div></section>
@endif
@endsection
