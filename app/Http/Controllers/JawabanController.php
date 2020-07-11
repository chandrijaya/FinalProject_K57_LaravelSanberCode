<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Jawaban;
use App\Pertanyaan;

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
        ]);
        return redirect('/pertanyaan/'.$id);
    }
}
