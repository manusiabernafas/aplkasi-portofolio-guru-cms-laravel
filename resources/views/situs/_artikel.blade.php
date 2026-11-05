<a class="kartu" href="{{ route('artikel.detail', $a->slug) }}">
  <img class="sampul" src="{{ gambar($a->sampul) }}" alt="" loading="lazy">
  <div class="isi">
    <span class="label kuning">{{ $a->kategori }}</span>
    <h3>{{ $a->judul }}</h3>
    @if ($a->ringkasan)<p class="muted kecil" style="margin:0">{{ \Illuminate\Support\Str::limit($a->ringkasan, 120) }}</p>@endif
    <div class="meta"><span>{!! ikon('kalender') !!}{{ tgl($a->terbit_pada) }}</span><span>{!! ikon('mata') !!}{{ $a->dilihat }}</span></div>
  </div>
</a>
