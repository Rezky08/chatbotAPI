<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['text', 'app_id', 'label_id'];
    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }

    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id', 'id');
    }
}
