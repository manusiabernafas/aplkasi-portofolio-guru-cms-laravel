<a class="kartu" href="{{ route('karya.detail', $k->slug) }}">
  <img class="sampul" src="{{ gambar($k->sampul) }}" alt="" loading="lazy">
  <div class="isi">
    <span class="label">{{ $k->jenis }}</span>
    <h3>{{ $k->judul }}</h3>
    @if ($k->ringkasan)<p class="muted kecil" style="margin:0">{{ \Illuminate\Support\Str::limit($k->ringkasan, 120) }}</p>@endif
    <div class="meta"><span>{!! ikon('kalender') !!}{{ $k->tahun }}</span>@if ($k->berkas)<span>{!! ikon('unduh') !!}{{ $k->diunduh }} unduhan</span>@endif</div>
  </div>
</a>
