<?php
    namespace App;
    use Illuminate\Database\Eloquent\Model;

    class Jawaban extends Model {
        protected $table = 'jawaban';
        protected $guarded = [];
        public function user() {
            return $this->belongsTo('App\User');
        }
        public function pertanyaan() {
            return $this->belongsTo('App\Pertanyaan');
        }
        public function vote_jawaban() {
            return $this->hasMany('App\VoteJawaban');
        }
        public function komentar_jawaban() {
            return $this->hasMany('App\KomentarJawaban');
        }
    }

?>