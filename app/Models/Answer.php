<?php

namespace App\Models;

use App\Traits\TableColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use HasFactory, SoftDeletes, TableColumn;
    protected $fillable = ['text', 'app_id', 'label_id'];
    public $relationship = ['application', 'label'];
    protected $hidden = ['client_id', 'deleted_at'];


    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }

    public function label()
    {
        return $this->belongsTo(Label::class, 'label_id', 'id');
    }
}
