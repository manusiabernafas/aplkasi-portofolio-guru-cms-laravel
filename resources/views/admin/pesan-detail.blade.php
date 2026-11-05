@extends('admin.layout', ['judul' => 'Pesan'])
@section('aksi')<a class="btn garis" href="{{ route('pesan.index') }}">&larr; Semua pesan</a>@endsection
@section('isi')
<div class="panel" style="max-width:820px">
  <h2 style="font-size:20px">{{ $p->subjek }}</h2>
  <p class="muted" style="margin:4px 0 16px">Dari <b>{{ $p->nama }}</b> &lt;{{ $p->email }}&gt; · {{ tgl($p->created_at) }} {{ \Illuminate\Support\Carbon::parse($p->created_at)->format('H:i') }}</p>
  <div class="pesan-isi">{{ $p->isi }}</div>
  <div style="display:flex;gap:10px;margin-top:18px;flex-wrap:wrap">
    <a class="btn" href="mailto:{{ $p->email }}?subject={{ rawurlencode('Re: ' . $p->subjek) }}">{!! ikon('surat') !!} Balas lewat email</a>
    <form method="post" action="{{ route('pesan.destroy', $p->id) }}" data-yakin="Hapus pesan ini?">@csrf @method('DELETE')<button class="btn merah">{!! ikon('hapus') !!} Hapus</button></form>
  </div>
</div>
@endsection
