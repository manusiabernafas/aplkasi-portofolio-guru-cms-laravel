@extends('situs.layout', ['judul' => 'Galeri'])
@section('isi')
<section class="halaman-h"><div class="wrap"><span class="kicker">Galeri</span><h1>Momen di kelas & kegiatan</h1></div></section>
<section style="padding-bottom:20px"><div class="wrap">
  <div class="filter"><a href="{{ route('galeri') }}" class="{{ $album ? '' : 'on' }}">Semua</a>@foreach ($albumAda as $a)<a href="{{ route('galeri', ['album' => $a]) }}" class="{{ $album === $a ? 'on' : '' }}">{{ $a }}</a>@endforeach</div>
  @if ($daftar->isEmpty())<div class="kosong">Belum ada foto.</div>@endif
  <div class="galeri">@foreach ($daftar as $g)<a href="{{ gambar($g->gambar) }}" data-judul="{{ $g->judul }}"><img src="{{ gambar($g->gambar) }}" alt="{{ $g->judul }}" loading="lazy"><span>{{ $g->judul }}</span></a>@endforeach</div>
</div></section>
<dialog class="lightbox" id="lb" onclick="this.close()"><img id="lbImg" alt=""><p id="lbJudul"></p></dialog>
@endsection
@push('skrip')
<script>
document.querySelectorAll('.galeri a').forEach(a => a.addEventListener('click', e => { e.preventDefault(); lbImg.src = a.href; lbImg.alt = a.dataset.judul; lbJudul.textContent = a.dataset.judul; lb.showModal(); }));
</script>
@endpush
