<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PesanController extends Controller
{
    public function index()
    {
        return view('admin.pesan', ['daftar' => DB::table('pesan')->orderBy('dibaca')->latest()->paginate(20)]);
    }

    public function show(int $id)
    {
        $p = DB::table('pesan')->find($id) ?? abort(404);
        DB::table('pesan')->where('id', $id)->update(['dibaca' => true]);
        return view('admin.pesan-detail', ['p' => $p]);
    }

    public function destroy(int $id)
    {
        DB::table('pesan')->where('id', $id)->delete();
        return redirect()->route('pesan.index')->with('ok', 'Pesan dihapus.');
    }
}
