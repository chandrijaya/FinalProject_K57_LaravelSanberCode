<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Pertanyaan;
use App\KomentarJawaban;

class KomentarJawabanController extends Controller
{
    public static function index($id, Request $request) {
        return view('index');
    
    }

    public static function store($q_id, $qa_id, Request $request) {
        $data = $request->all();
        unset($data['_token']);
        $Komentar_pertanyaan = KomentarJawaban::create([
            'Jawaban_id' => $qa_id,
            'isi' => $data['komentar_jawaban'],
            'user_id' => Auth::id(),
        ]);
        return redirect('/pertanyaan/'.$q_id.'#answer');
    }

    public function delete($q_id, $qa_id, $ac_id) {
        Alert::warning('Hapus Komentar', 'Apakah anda yakin ingin menghapus komentar Jawaban?');
        $p_komentar_removed = KomentarJawaban::where('id', $ac_id)->forceDelete();
        return redirect('/pertanyaan/'.$q_id);
    }
}
