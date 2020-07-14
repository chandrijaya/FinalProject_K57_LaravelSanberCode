<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\User');
    }
}