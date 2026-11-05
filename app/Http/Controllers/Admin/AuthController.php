<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function form()
    {
        return Auth::check() ? redirect()->route('admin.dasbor') : view('admin.login');
    }

    public function masuk(Request $r)
    {
        $data = $r->validate(['username' => 'required|string|max:50', 'password' => 'required|string|max:100']);
        $kunci = Str::lower($data['username']) . '|' . $r->ip();
        if (RateLimiter::tooManyAttempts($kunci, 5)) {
            return back()->withInput($r->only('username'))->with('error', __('auth.throttle', ['seconds' => RateLimiter::availableIn($kunci)]));
        }
        if (! Auth::attempt($data, $r->boolean('ingat'))) {
            RateLimiter::hit($kunci, 60);
            return back()->withInput($r->only('username'))->with('error', __('auth.failed'));
        }
        RateLimiter::clear($kunci);
        $r->session()->regenerate();
        return redirect()->intended(route('admin.dasbor'));
    }

    public function keluar(Request $r)
    {
        Auth::logout();
        $r->session()->invalidate();
        $r->session()->regenerateToken();
        return redirect()->route('login')->with('ok', 'Anda sudah keluar.');
    }
}
