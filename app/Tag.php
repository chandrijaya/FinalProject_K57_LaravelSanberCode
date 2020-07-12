<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function pertanyaan(){
        return $this->belongsToMany('App\Pertanyaan', 'tag_pertanyaan', 'pertanyaan_id', 'tag_id');
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($tag) { // before delete() method call this
             $tag->pertanyaan()->detach();
        });
    }
}
