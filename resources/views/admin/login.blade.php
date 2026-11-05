@php [, $aksen, $aksenSoft] = \App\Support\Situs::warna(); @endphp
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex">
<title>Masuk · Admin Portofolio</title>
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
<style>:root { --aksen: {{ $aksen }}; --aksen-soft: {{ $aksenSoft }}; }</style>
</head>
<body>
<div class="masuk">
  <form class="kotak form" method="post" action="{{ route('login.proses') }}">
    @csrf
    <div class="mono">{{ mb_substr(situs('nama'), 0, 1) }}</div>
    <h1 style="font-size:24px">Masuk panel admin</h1>
    <p class="muted" style="margin:6px 0 22px">Kelola portofolio {{ situs('nama') }}.</p>
    @if (session('ok'))<div class="alert ok">{{ session('ok') }}</div>@endif
    @if (session('error'))<div class="alert err" role="alert">{{ session('error') }}</div>@endif
    <div class="f"><label for="username">Username</label><input id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>@error('username')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="f"><label for="password">Kata sandi</label><input id="password" type="password" name="password" autocomplete="current-password" required>@error('password')<div class="galat">{{ $message }}</div>@enderror</div>
    <div class="f"><label class="cek"><input type="checkbox" name="ingat" value="1"> Ingat saya</label></div>
    <button class="btn" style="width:100%;justify-content:center;padding:13px">Masuk</button>
    <p class="kecil" style="text-align:center;margin:18px 0 0"><a href="{{ route('beranda') }}">&larr; Kembali ke situs</a></p>
  </form>
</div>
</body>
</html>
