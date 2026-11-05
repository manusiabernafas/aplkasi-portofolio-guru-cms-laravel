<div class="pres">
  @if ($p->gambar)<img src="{{ gambar($p->gambar) }}" alt="" loading="lazy">@else<div class="medali">{!! ikon('trofi') !!}</div>@endif
  <div>
    <span class="label {{ $p->kategori === 'siswa' ? 'kuning' : '' }}">{{ $p->kategori === 'siswa' ? 'Siswa bimbingan' : 'Prestasi guru' }}</span>
    <h3 style="margin-top:8px">{{ $p->peringkat }} · {{ $p->judul }}</h3>
    <div class="muted kecil">Tingkat {{ $p->tingkat }} · {{ $p->tahun }}</div>
    @if ($p->deskripsi)<p class="kecil" style="margin:8px 0 0">{{ $p->deskripsi }}</p>@endif
  </div>
</div>
