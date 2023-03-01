<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    
    protected $fillable = [
        'content',
        'question_id',
        'answer_id',
        'created_by',
        'edit_by',
        'status',
        'accepted'
    ];
    public $timestamps = true;
    
    protected $appends = [
        'created_at_for_humans',
        'created_at_formatted'
    ];
    
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
    public function getCreatedAtForHumansAttribute(){
        
        return $this->created_at->diffForHumans();
    }
    public function getCreatedAtFormattedAttribute(){
        
        return $this->created_at->format('M d, Y').' at '.$this->created_at->format('H:i');
    }
    
}
