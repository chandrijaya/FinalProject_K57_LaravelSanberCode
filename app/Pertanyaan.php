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
        public function tags() {
            return $this->belongsToMany('App\Tag', 'tag_pertanyaan', 'pertanyaan_id', 'tag_id');
        }

        public static function boot() {
            parent::boot();
    
            static::deleting(function($pertanyaan) { // before delete() method call this
                 $pertanyaan->tags()->detach();
            });
        }
    }
?>