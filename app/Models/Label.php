<?php

namespace App\Models;

use App\Traits\TableColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Label extends Model
{
    use HasFactory, SoftDeletes, TableColumn;
    protected $fillable = ['label_name'];
    public $relationship = ['application', 'question', 'answer'];
    protected $hidden = ['client_id', 'deleted_at'];

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
