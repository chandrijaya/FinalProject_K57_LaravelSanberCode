<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Jawaban;
use App\Pertanyaan;
use App\VoteJawaban;
use App\KomentarJawaban;

class JawabanController extends Controller
{
    public static function index($id, Request $request) {
        $jawaban = Jawaban::where('pertanyaan_id', $id)->get();
        $pertanyaan = Pertanyaan::find($id);
        return view('jawaban.index', ['isi' => $jawaban, 'id' => $id, 'pertanyaan' => $pertanyaan]);
    }
    public static function store($id, Request $request) {
        $data = $request->all();
        unset($data['_token']);
        $jawaban = Jawaban::create([
            'pertanyaan_id' => $id,
            'isi' => $data['isi'],
            'user_id' => Auth::id(),
            'is_selected' => 0
        ]);
        return redirect('/pertanyaan/'.$id.'#answer');
    }

    public function delete($q_id, $qa_id) {
        $komentar_jawaban = KomentarJawaban::where('jawaban_id', $qa_id)->forceDelete();
        $vote_pertanyaan_removed = VoteJawaban::where('jawaban_id', $qa_id)->forceDelete();
        $jawaban_removed = Jawaban::where('id', $qa_id)->forceDelete();
        return redirect('/pertanyaan/'.$q_id);
    }

    public static function selected($q_id, $qa_id) {
        // Kembalikan jawaban lain menjadi biasa
        $jawaban = Jawaban::where([
            ['pertanyaan_id', $q_id],
            ['is_selected', 1]
        ])->update([
            'is_selected' => 0,
        ]);
        // Buat jawaban dipilih menjadi best
        $jawaban = Jawaban::where('id', $qa_id)
            ->update([
            'is_selected' => 1,
        ]);
        return redirect('/pertanyaan/'.$q_id);
    }

    public static function unselected($q_id, $qa_id) {
        // Kembalikan jawaban dipilih menjadi biasa
        $jawaban = Jawaban::where('id', $qa_id)
            ->update([
            'is_selected' => 0,
        ]);
        return redirect('/pertanyaan/'.$q_id);
    }
    public static function komentar($id, Request $request) {
        $data = $request->all();
        // dd($data);
        unset($data['_token']);
        $jawaban = KomentarJawaban::create([
            'jawaban_id' => $data['jawaban_id'],
            'isi' => $data['komentar'],
            'user_id' => Auth::id(),
        ]);
        return redirect('/pertanyaan/'.$id);
    }
}
