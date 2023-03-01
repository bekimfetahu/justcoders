<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    
    public $timestamps = true;
    
    protected $appends = [
        'created_at_for_humans'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

     public function tag()
    {
        return $this->hasMany(Tag::class);
    }
    public function getCreatedAtForHumansAttribute(){
        
        return $this->created_at->diffForHumans();
    }
}
