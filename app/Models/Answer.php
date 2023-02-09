<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{

    public $timestamps = true;

    public function parent()
    {
        return $this->belongsTo(Answer::class, 'answer_id');
    }

    public function children()
    {
        return $this->hasMany(Answer::class, 'answer_id');
    }

    public function user()
    {
      return $this->belongsTo(User::class, 'created_by');
    }

}
