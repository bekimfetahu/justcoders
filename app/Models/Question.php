<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

     public function tag()
    {
        return $this->hasMany(Tag::class);
    }
}
