<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['label_name'];
    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
    public function question()
    {
        return $this->hasMany(Question::class, 'label_id', 'id');
    }
    public function answer()
    {
        return $this->hasMany(Answer::class, 'label_id', 'id');
    }
}
