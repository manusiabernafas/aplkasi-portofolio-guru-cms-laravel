@extends('admin.layout', ['judul' => $d['label']])
@section('aksi')
  @if (! empty($d['publik']))<a class="btn garis" href="{{ url(str_contains($d['publik'], '{') ? explode('/', $d['publik'])[0] : $d['publik']) }}" target="_blank" rel="noopener">{!! ikon('link') !!} Lihat</a>@endif
  <a class="btn" href="{{ route('konten.create', $jenis) }}">{!! ikon('plus') !!} Tambah {{ $d['satuan'] }}</a>
@endsection
@section('isi')
@if ($jenis === 'galeri')
<details class="panel" style="margin-bottom:18px" @if ($errors->has('foto') || $errors->has('foto.*') || $errors->has('album')) open @endif>
  <summary style="cursor:pointer;font-weight:700">Unggah banyak foto sekaligus</summary>
  <form class="form grid g2" method="post" action="{{ route('galeri.massal') }}" enctype="multipart/form-data" style="margin-top:14px;align-items:end">
    @csrf
    <div class="f"><label for="album">Album</label><input id="album" name="album" value="{{ old('album') }}" placeholder="Contoh: Kegiatan Kelas" required>@error('album')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="f"><label for="foto">Foto (maks 30, masing-masing 3 MB)</label><input id="foto" type="file" name="foto[]" accept="image/jpeg,image/png,image/webp" multiple required>@error('foto')<div class="galat">{{ $message }}</div>@enderror @foreach ($errors->get('foto.*') as $m)<div class="galat">{{ $m[0] }}</div>@endforeach</div>
    <div><button class="btn">{!! ikon('unduh') !!} Unggah</button></div>
  </form>
</details>
@endif
<div class="panel">
  <form class="cari" method="get" style="margin-bottom:12px"><input type="search" name="q" value="{{ $cari }}" placeholder="Cari {{ $d['satuan'] }}" aria-label="Cari"><button class="btn garis">Cari</button></form>
  @if ($daftar->isEmpty())
    <div class="kosong">{{ $cari ? 'Tidak ada hasil untuk "' . $cari . '".' : 'Belum ada ' . $d['satuan'] . '. Klik Tambah untuk mulai mengisi.' }}</div>
  @else
  <div class="tabel-wrap"><table>
    <thead><tr>@foreach ($d['kolom'] as $k => $l)<th class="{{ $loop->first ? '' : 'hp-sembunyi' }}">{{ $l }}</th>@endforeach @if (isset($d['field']['tampil']))<th>Status</th>@endif<th></th></tr></thead>
    <tbody>
    @foreach ($daftar as $row)
      <tr>
        @foreach ($d['kolom'] as $k => $l)
          <td class="{{ $loop->first ? '' : 'hp-sembunyi' }}">
            @if (($d['field'][$k][0] ?? null) === 'gambar')<img class="thumb" src="{{ gambar($row->$k) }}" alt="">
            @elseif ($k === 'terbit_pada' || $k === 'tanggal'){{ $row->$k ? tgl($row->$k) : '-' }}
            @elseif ($k === 'level')<b>{{ $row->$k }}%</b>
            @elseif ($k === 'kategori' && $jenis === 'prestasi')<span class="label {{ $row->$k === 'siswa' ? 'kuning' : '' }}">{{ $row->$k === 'siswa' ? 'Siswa' : 'Guru' }}</span>
            @elseif ($loop->first)<b>{{ \Illuminate\Support\Str::limit($row->$k, 80) }}</b>
            @else{{ \Illuminate\Support\Str::limit((string) ($row->$k ?? '-'), 60) ?: '-' }}@endif
          </td>
        @endforeach
        @if (isset($d['field']['tampil']))
          <td><form method="post" action="{{ route('konten.toggle', [$jenis, $row->id]) }}">@csrf<button class="label {{ $row->tampil ? '' : 'abu' }}" style="border:0;cursor:pointer;font:inherit;font-size:12px;font-weight:700" title="Klik untuk mengubah">{{ $row->tampil ? 'Tampil' : 'Disembunyikan' }}</button></form></td>
        @endif
        <td class="aksi">
          <a class="btn garis kecil" href="{{ route('konten.edit', [$jenis, $row->id]) }}">{!! ikon('edit') !!}<span class="hp-sembunyi"> Ubah</span></a>
          <form method="post" action="{{ route('konten.destroy', [$jenis, $row->id]) }}" data-yakin="Hapus {{ $d['satuan'] }} ini? Berkas terkait ikut terhapus.">@csrf @method('DELETE')<button class="btn merah kecil ikon" aria-label="Hapus">{!! ikon('hapus') !!}</button></form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table></div>
  @include('situs._pagin', ['p' => $daftar])
  @endif
</div>
@endsection
