<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pertanyaan;
use App\KomentarPertanyaan;

class KomentarPertanyaanController extends Controller
{   
    public static function index($id, Request $request) {
        return view('index');
    
    }

    public static function store($id, Request $request) {
        $data = $request->all();
        unset($data['_token']);
        $Komentar_pertanyaan = KomentarPertanyaan::create([
            'pertanyaan_id' => $id,
            'isi' => $data['komentar_pertanyaan'],
            'user_id' => Auth::id(),
        ]);
        return redirect('/pertanyaan/'.$id.'#comment');
    }

    public function delete($q_id, $qc_id) {
        Alert::warning('Hapus Komentar', 'Apakah anda yakin ingin menghapus komentar pertanyaan?');
        $p_komentar_removed = KomentarPertanyaan::where('id', $qc_id)->forceDelete();
        return redirect('/pertanyaan/'.$q_id);
    }
}