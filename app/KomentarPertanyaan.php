<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KomentarPertanyaan extends Model
{
    protected $table = 'komentar_pertanyaan';
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function pertanyaan() {
        return $this->belongsTo('App\Pertanyaan');
    }
    
}
