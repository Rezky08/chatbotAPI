<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telegram extends Model
{
    use HasFactory, SoftDeletes;
    public $relationship = ['application', 'account'];
    protected $hidden = ['client_id', 'deleted_at'];


    public function account()
    {
        return $this->hasMany(TelegramAccount::class, 'telegram_id', 'id');
    }
    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
    public function scopeActive($query, $app_ids)
    {
        return $this->whereIn('app_id', $app_ids);
    }
}
