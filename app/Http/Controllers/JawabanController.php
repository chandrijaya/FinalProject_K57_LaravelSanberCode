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

    // Vote jawaban dan reputasi
    public function vote_jawaban(Request $request) {
        $jawaban_id = $request['jawaban_id'];
        $is_vote = $request['isVote'] === 'true';
        if ($is_vote == 1) {
            $is_vote = 1;
            $reputasi = 10;
        } else {
            $is_vote = -1;
            $reputasi = -1;
        }
        echo $is_vote;
        $update = false;
        $jawaban = Jawaban::find($jawaban_id);
        if (!$jawaban) {
            return null;
        }
        $user = Auth::user();
        $vote = $user->vote_jawaban()->where('jawaban_id', $jawaban_id)->first();
        $user_id_jawaban = $jawaban->user_id;
        $user_id_online = Auth::id();

        if ($vote) {
            $already_vote = $vote->value;
            $update = true;
            if ($already_vote == $is_vote && $user_id_jawaban != $user_id_online) {
                $vote->delete();
                return null;
            }
        } else {
            $vote = new VoteJawaban();
        }
        $vote->value = $is_vote;
        $vote->reputasi = $reputasi;
        $vote->penjawab_id = $user_id_jawaban;
        if ($update && $user_id_jawaban != $user_id_online) {
            $vote->update();
        } elseif ($user_id_jawaban != $user_id_online) {
            $vote->user_id = $user->id;
            $vote->jawaban_id = $jawaban->id;
            $vote->save();
        }
        return null;
    }
}
