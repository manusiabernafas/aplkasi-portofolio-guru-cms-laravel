@extends('admin.layout', ['judul' => 'Pesan masuk'])
@section('isi')
<div class="panel">
  @if ($daftar->isEmpty())<div class="kosong">Belum ada pesan. Pesan dari halaman Kontak akan muncul di sini.</div>@else
  <div class="tabel-wrap"><table><thead><tr><th>Pengirim</th><th>Subjek</th><th class="hp-sembunyi">Tanggal</th><th></th></tr></thead><tbody>
  @foreach ($daftar as $p)
    <tr class="{{ $p->dibaca ? '' : 'belum' }}"><td>{{ $p->nama }}<div class="kecil muted hp-sembunyi">{{ $p->email }}</div></td><td>{{ \Illuminate\Support\Str::limit($p->subjek, 60) }}</td><td class="hp-sembunyi kecil">{{ tgl($p->created_at) }}</td><td class="aksi"><a class="btn garis kecil" href="{{ route('pesan.show', $p->id) }}">Baca</a></td></tr>
  @endforeach
  </tbody></table></div>
  @include('situs._pagin', ['p' => $daftar])
  @endif
</div>
@endsection
