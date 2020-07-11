<?php
    namespace App;
    use Illuminate\Database\Eloquent\Model;

    class Pertanyaan extends Model {
        protected $table = 'pertanyaan';
        protected $guarded = [];
        public function user() {
            return $this->belongsTo('App\User');
        }
        public function jawaban() {
            return $this->hasMany('App\Jawaban');
        }
        public function vote_pertanyaan() {
            return $this->hasMany('App\VotePertanyaan');
        }
        public function komentar_pertanyaan() {
            return $this->hasMany('App\KomentarPertanyaan');
        }
    }
?>