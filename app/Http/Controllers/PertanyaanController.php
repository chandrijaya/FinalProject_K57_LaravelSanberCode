<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        if ($jawaban->first() == null) {
            $reputasi_jawaban = 0;
        
        } else {
            foreach ($jawaban as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_jawaban[$nama] = $vote_jawaban->where('penjawab_id', $value->user_id)->get()->sum('reputasi');
                $best_jawaban = Jawaban::where([
                    ['user_id', $value->user_id],
                    ['is_selected', 1]])->first();
                if ($best_jawaban != null) {
                    $reputasi_jawaban[$nama] += 15;
                }
            }
        }

        if ($vote_pertanyaan->first() == null) {
            $reputasi_pertanyaan = null;
        } else {
            foreach ($vote_pertanyaan as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_pertanyaan[$nama] =  $vote->where('penanya_id', $value->user_id)->sum('reputasi');
            } 
        }
        return view('pertanyaan.index_by_id', ['daftar_jawaban' => $daftar_jawaban, 
                                'pertanyaan' => $pertanyaan,
                                'komentar_pertanyaan' => $komentar_pertanyaan, 
                                'vote' => $vote, 
                                'vote_jawaban' => $vote_jawaban, 
                                'reputasi_jawaban' => $reputasi_jawaban, 
                                'reputasi_pertanyaan' => $reputasi_pertanyaan,
                                'best_jawaban' => $best_jawaban,
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
        $vote_pertanyaan_removed = VotePertanyaan::where('pertanyaan_id', $id)->forceDelete();
        $jawaban_removed = Jawaban::where('pertanyaan_id', $id)->forceDelete();
        $komentar_pertanyaan = KomentarPertanyaan::where('pertanyaan_id', $id)->forceDelete();
        $cek = Pertanyaan::find($id)->forceDelete();
        $pertanyaan_removed = Pertanyaan::where('id', $id)->forceDelete();
        return redirect('/pertanyaan');
    }

    // Upvote pertanyaan dan reputasi
    public function vote(Request $request) {
        $pertanyaan_id = $request['pertanyaan_id'];
        $is_vote = $request['isVote'] === 'true';
        if ($is_vote == 1) {
            $is_vote = 1;
            $reputasi = 10;
        } else {
            $is_vote = -1;
            $reputasi = -1;
        }

        // hitung reputasi
        $vote = new VotePertanyaan;
        $vote_jawaban = new VoteJawaban;
        $nama = "";
        $jawaban = Jawaban::get();
        $vote_pertanyaan = Pertanyaan::get();
        

        if ($vote_pertanyaan->first() == null) {
            $reputasi_pertanyaan = null;
        } else {
            foreach ($vote_pertanyaan as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_pertanyaan[$nama] =  $vote->where('penanya_id', $value->user_id)->sum('reputasi');
                
            } 
        }
        
        if ($jawaban->first() == null) {
            $reputasi_jawaban = 0;
            $reputasi_user = $reputasi_pertanyaan[$nama];
                   
        } else {
            foreach ($jawaban as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_jawaban[$nama] = $vote_jawaban->where('penjawab_id', $value->user_id)->get()->sum('reputasi');         
            }
            $reputasi_user = $reputasi_pertanyaan[$nama] + $reputasi_jawaban[$nama];
        }
        
        // end hitung reputasi
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
                return null;
            }
        } else {
            $vote = new VotePertanyaan();
        }
        
        $vote->value = $is_vote;
        $vote->reputasi = $reputasi;
        $vote->penanya_id = $user_id_pertanyaan;
        if ($update && $user_id_pertanyaan != $user_id_online) {
            if ($reputasi_user >= 15 && $is_vote == -1) {
                $vote->update();
            } elseif ($is_vote == 1) {
                $vote->update();
            } else {
                return view('pertanyaan.index');
            }
        } elseif ($user_id_pertanyaan != $user_id_online) {
            $vote->user_id = $user->id;
            $vote->pertanyaan_id = $pertanyaan->id;
            if ($reputasi_user >= 15 && $is_vote == -1) {
                $vote->save();
                
            } elseif ($is_vote == 1) {
                $vote->save();
                
            } else {
                
                return view('pertanyaan.index');
            }
        }
        return null;
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

        // hitung reputasi
        $vote = new VotePertanyaan;
        $vote_jawaban = new VoteJawaban;
        $nama = "";
        $jawaban = Jawaban::get();
        $vote_pertanyaan = Pertanyaan::get();

        if ($vote_pertanyaan->first() == null) {
            $reputasi_pertanyaan = null;
        } else {
            foreach ($vote_pertanyaan as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_pertanyaan[$nama] =  $vote->where('penanya_id', $value->user_id)->sum('reputasi');
            } 
        }

        if ($jawaban->first() == null) {
            $reputasi_jawaban = 0;
            $reputasi_user = $reputasi_pertanyaan[$nama];
                   
        } else {
            foreach ($jawaban as $key => $value) {
                $nama = User::where('id', $value->user_id)->value('name');
                $reputasi_jawaban[$nama] = $vote_jawaban->where('penjawab_id', $value->user_id)->get()->sum('reputasi');         
            }
            $reputasi_user = $reputasi_pertanyaan[$nama] + $reputasi_jawaban[$nama];
        }
        // end hitung reputasi

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
            if ($reputasi_user >= 15 && $is_vote == -1) {
                $vote->update();
            } elseif ($is_vote == 1) {
                $vote->update();
            } else {
                return view('pertanyaan.index');
            }
        } elseif ($user_id_jawaban != $user_id_online) {
            $vote->user_id = $user->id;
            $vote->jawaban_id = $jawaban->id;
            if ($reputasi_user >= 15 && $is_vote == -1) {
                $vote->save();
            } elseif ($is_vote == 1) {
                $vote->save();
            } else {
                return view('pertanyaan.index');
            }
        }
        return null;
    }
}
