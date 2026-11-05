@extends('admin.layout', ['judul' => ($row->id ? 'Ubah ' : 'Tambah ') . $d['satuan']])
@section('aksi')<a class="btn garis" href="{{ route('konten.index', $jenis) }}">&larr; Kembali</a>@endsection
@section('isi')
<form class="form panel" method="post" enctype="multipart/form-data" action="{{ $row->id ? route('konten.update', [$jenis, $row->id]) : route('konten.store', $jenis) }}" style="max-width:900px" novalidate>
  @csrf @if ($row->id) @method('PUT') @endif
  @php $baris = []; @endphp
  @foreach ($d['field'] as $f => $def)
    @php [$tipe, $label] = $def; $opsi = $def[3] ?? null; $hint = $def[4] ?? null; $nilai = old($f, $row->$f ?? ''); $wajib = str_contains($def[2], 'required') && ! ($tipe === 'gambar' && $row->id); @endphp
    @if ($tipe === 'cek')
      <div class="f"><input type="hidden" name="{{ $f }}" value="0"><label class="cek"><input type="checkbox" name="{{ $f }}" value="1" @checked(old($f, $row->$f ?? 1))> {{ $label }}</label></div>
      @continue
    @endif
    <div class="f">
      <label for="{{ $f }}">{{ $label }}@if ($wajib) <span style="color:var(--merah)">*</span>@endif</label>
      @switch($tipe)
        @case('textarea')<textarea id="{{ $f }}" name="{{ $f }}" class="@error($f) is-galat @enderror">{{ $nilai }}</textarea>@break
        @case('markdown')
          <div class="tab-md" role="tablist"><button type="button" class="on" data-mode="tulis">Tulis</button><button type="button" data-mode="lihat">Pratinjau</button></div>
          <textarea id="{{ $f }}" name="{{ $f }}" class="md @error($f) is-galat @enderror">{{ $nilai }}</textarea>
          <div class="pratinjau" hidden></div>
          <div class="hint">Format Markdown: <code>## Subjudul</code>, <code>**tebal**</code>, <code>- daftar</code>, <code>[teks](https://tautan)</code>.</div>
          @break
        @case('select')
          <select id="{{ $f }}" name="{{ $f }}" class="@error($f) is-galat @enderror">
            <option value="">Pilih {{ strtolower($label) }}</option>
            @foreach ($opsi as $v => $t) @php $v = array_is_list($opsi) ? $t : $v; @endphp<option value="{{ $v }}" @selected((string) $nilai === (string) $v)>{{ $t }}</option>@endforeach
          </select>@break
        @case('number')<input id="{{ $f }}" type="number" name="{{ $f }}" value="{{ $nilai }}" inputmode="numeric" class="@error($f) is-galat @enderror">@break
        @case('date')<input id="{{ $f }}" type="date" name="{{ $f }}" value="{{ $nilai ? \Illuminate\Support\Carbon::parse($nilai)->format('Y-m-d') : '' }}" class="@error($f) is-galat @enderror">@break
        @case('url')<input id="{{ $f }}" type="url" name="{{ $f }}" value="{{ $nilai }}" placeholder="https://" class="@error($f) is-galat @enderror">@break
        @case('gambar')
          @if ($row->$f ?? null)<div class="ada-berkas"><img src="{{ gambar($row->$f) }}" alt="">@unless (str_contains($def[2], 'required'))<label class="cek kecil"><input type="checkbox" name="hapus_{{ $f }}" value="1"> Hapus gambar</label>@endunless</div>@endif
          <input id="{{ $f }}" type="file" name="{{ $f }}" accept="image/jpeg,image/png,image/webp" class="@error($f) is-galat @enderror">
          <div class="hint">JPG, PNG, atau WEBP. {{ $row->$f ?? null ? 'Pilih file baru untuk mengganti.' : '' }}</div>@break
        @case('berkas')
          @if ($row->$f ?? null)<div class="ada-berkas">{!! ikon('map') !!} Berkas tersimpan ({{ strtoupper(pathinfo($row->$f, PATHINFO_EXTENSION)) }}) <label class="cek kecil"><input type="checkbox" name="hapus_{{ $f }}" value="1"> Hapus berkas</label></div>@endif
          <input id="{{ $f }}" type="file" name="{{ $f }}" class="@error($f) is-galat @enderror">@break
        @default<input id="{{ $f }}" name="{{ $f }}" value="{{ $nilai }}" class="@error($f) is-galat @enderror">
      @endswitch
      @if ($hint)<div class="hint">{{ $hint }}</div>@endif
      @error($f)<div class="galat">{{ $message }}</div>@enderror
    </div>
  @endforeach
  <div style="display:flex;gap:10px;flex-wrap:wrap"><button class="btn">{!! ikon('cek') !!} Simpan</button><a class="btn garis" href="{{ route('konten.index', $jenis) }}">Batal</a></div>
</form>
@endsection
@push('skrip')
<script>
document.querySelectorAll('.tab-md').forEach(tab => {
  const ta = tab.nextElementSibling, pv = ta.nextElementSibling;
  tab.addEventListener('click', async e => {
    const b = e.target.closest('button'); if (!b) return;
    tab.querySelectorAll('button').forEach(x => x.classList.toggle('on', x === b));
    const lihat = b.dataset.mode === 'lihat';
    ta.hidden = lihat; pv.hidden = !lihat;
    if (lihat) {
      pv.textContent = 'Memuat pratinjau...';
      const fd = new FormData(); fd.append('isi', ta.value); fd.append('_token', document.querySelector('input[name=_token]').value);
      const r = await fetch(@json(route('pratinjau')), { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      pv.innerHTML = r.ok ? (await r.text()) || '<p class="muted">Belum ada isi.</p>' : '<p class="galat">Pratinjau gagal dimuat.</p>';
    }
  });
});
</script>
@endpush
