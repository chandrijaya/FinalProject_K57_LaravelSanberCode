<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\JawabanController;
use App\Profile;
use App\User;
use App\Tag;
use App\Pertanyaan;
use App\Jawaban;
use App\VoteJawaban;
use App\VotePertanyaan;
use App\KomentarPertanyaan;
use App\KomentarJawaban;


class PertanyaanController extends Controller {
    public function __construct() {
        $this->middleware('auth', ['except' => ['index', 'show']]); //index tidak diberi authentication
    }

    // Menampilkan semua pertanyaan dengan eloquent
    public function index() {
        $pertanyaan = Pertanyaan::get();
        // dd($pertanyaan->tags);
        $vote = new VotePertanyaan;
        return view('pertanyaan.index', compact('pertanyaan', 'vote'));
    }

    // Menampilkan pertanyaan tertentu
    public static function show($id) {
        $daftar_jawaban = Jawaban::where('pertanyaan_id', $id)->get();
        $pertanyaan = Pertanyaan::find($id);

        $komentar_pertanyaan = KomentarPertanyaan::where('pertanyaan_id', $id)->get();
        $komentar_jawaban = KomentarJawaban::get();

        $vote = new VotePertanyaan;
        $vote_jawaban = new VoteJawaban;
        
        $jawaban = Jawaban::get();
        $vote_pertanyaan = Pertanyaan::get();
        $p_id = Pertanyaan::where('id', $id)->value('user_id');
        $reputasi = Profile::where('id', $p_id)->value('reputasi');
        // $cek1 = Jawaban::where('pertanyaan_id', $id)->pluck('isi');
        // $cek2 = Jawaban::where('pertanyaan_id', $id)->pluck('id');
        // dd(count($cek2));
        // $cek = array_combine($cek1, $cek2);
        // dd($cek);
        return view('pertanyaan.index_by_id', ['daftar_jawaban' => $daftar_jawaban, 
                                'pertanyaan' => $pertanyaan,
                                'komentar_pertanyaan' => $komentar_pertanyaan, 
                                'vote' => $vote, 
                                'vote_jawaban' => $vote_jawaban,
                                'reputasi' => $reputasi, 
                                'komentar_jawaban' => $komentar_jawaban]);
    }


    // Buat pertanyaan
    public function create() {
        return view('pertanyaan.form');
    }

    public function store(Request $request) {
        $data = $request->all();
        // unset($data['_token']);
        $pertanyaan = Pertanyaan::create([
            'judul' => $data['judul'],
            'isi' => $data['isi'],
            'user_id' => Auth::id()
        ]);
        $tagArr = explode(',', $request->tags);
        $tagsMulti  = [];
        foreach($tagArr as $strTag){
            $tagArrAssc["nama"] = $strTag;
            $tagsMulti[] = $tagArrAssc;
        }
        // Create Tags baru
        foreach($tagsMulti as $tagCheck){
            $tag = Tag::firstOrCreate($tagCheck);
            $pertanyaan->tags()->attach($tag->id);
        }
        Alert::success('Menambah Pertanyaan', 'Anda berhasil menambah sebuah pertanyaan');
        return redirect('/pertanyaan'); 
    }

    // Edit Pertanyaan
    public function edit($id) {
        $pertanyaan = Pertanyaan::find($id);
        return view('pertanyaan.form_update', compact('pertanyaan'));
    }
    public function update($id, Request $request) {
        $data = $request->all();
        unset($data['_token']);
        $pertanyaan = Pertanyaan::where('id', $id)
            ->update([
            'judul' => $data['judul'],
            'isi' => $data['isi']
        ]);
        Alert::success('ubah Pertanyaan', 'Anda berhasil merubah sebuah pertanyaan');
        return redirect('/pertanyaan'); 
    }

    // Hapus pertanyaan
    public function delete($id) {
        Alert::warning('Hapus Pertanyaan', 'Apakah anda yakin ingin menghapus pertanyaan?');
        $cek_vote = VotePertanyaan::where('pertanyaan_id', $id)->first();
        if ($cek_vote != null) {
            $min_reputasi = VotePertanyaan::where('pertanyaan_id', $id)->value('reputasi');
            $p_id = VotePertanyaan::where('pertanyaan_id', $id)->value('user_id');
            $update_reputasi = Profile::where('id', $p_id)->decrement('reputasi', $min_reputasi);
            $vote_pertanyaan_removed = VotePertanyaan::where('pertanyaan_id', $id)->forceDelete();
        }
        $jawaban_removed = Jawaban::where('pertanyaan_id', $id)->forceDelete();
        $komentar_pertanyaan = KomentarPertanyaan::where('pertanyaan_id', $id)->forceDelete();
        $cek = Pertanyaan::find($id)->forceDelete();
        $pertanyaan_removed = Pertanyaan::where('id', $id)->forceDelete();
        return redirect('/pertanyaan');
    }

    // Upvote pertanyaan dan reputasi
    public function vote(Request $request) {
        $voter_rep = Profile::where('id', Auth::id())->value('reputasi');
        $pertanyaan_id = $request['pertanyaan_id'];
        $is_vote = $request['isVote'] === 'true';
        if ($is_vote == 1) {
            $is_vote = 1;
            $reputasi = 10;
        } else {
            if ($voter_rep < 15){
                return $cek['error'];
            }
            else {
            $is_vote = -1;
            $reputasi = -1;
        }
            }
            
        echo $is_vote;
        $update = false;
        $pertanyaan = Pertanyaan::find($pertanyaan_id);
        if (!$pertanyaan) {
            return null;
        }
        $user = Auth::user();
        $vote = $user->vote_pertanyaan()->where('pertanyaan_id', $pertanyaan_id)->first();
        $user_id_pertanyaan = $pertanyaan->user_id;
        $user_id_online = Auth::id();

        if ($vote) {
            $already_vote = $vote->value;
            $update = true;
            if ($already_vote == $is_vote && $user_id_pertanyaan != $user_id_online) {
                $vote->delete();
                if ($is_vote == -1) {
                    $update_reputasi = Profile::where('id', $user_id_pertanyaan)->increment('reputasi', 1);
                }
                else {
                    $update_reputasi = Profile::where('id', $user_id_pertanyaan)->decrement('reputasi', 10);
                }
                return null;
            }
        } else {
            $vote = new VotePertanyaan();
        }
        $vote->value = $is_vote;
        $vote->reputasi = $reputasi;
        $vote->penanya_id = $user_id_pertanyaan;
        if ($update && $user_id_pertanyaan != $user_id_online) {
            $vote->update();
            if ($is_vote != -1) {
                $update_reputasi = Profile::where('id', $user_id_pertanyaan)->increment('reputasi', 11);
            }
            else {
                $update_reputasi = Profile::where('id', $user_id_pertanyaan)->decrement('reputasi', 11);
            }
        } elseif ($user_id_pertanyaan != $user_id_online) {
            $vote->user_id = $user->id;
            $vote->pertanyaan_id = $pertanyaan->id;
            $vote->save();
            if ($is_vote != -1) {
                $update_reputasi = Profile::where('id', $user_id_pertanyaan)->increment('reputasi', 10);
            }
            else {
                $update_reputasi = Profile::where('id', $user_id_pertanyaan)->decrement('reputasi', 1);
            }
        }
        return null;
    }
}
