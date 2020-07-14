<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];
    
    public function profile() {
        return $this->belongsTo('App\Profile');
    }

    public function pertanyaan() {
        return $this->hasMany('App\Pertanyaan');
    }
    public function vote_pertanyaan() {
        return $this->hasMany('App\VotePertanyaan');
    }
    public function jawaban() {
        return $this->hasMany('App\Jawaban');
    }
    public function vote_jawaban() {
        return $this->hasMany('App\VoteJawaban');
    }

   
}
