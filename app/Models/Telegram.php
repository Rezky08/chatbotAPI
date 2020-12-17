<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Telegram extends Model
{
    use HasFactory, SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function account()
    {
        return $this->hasMany(TelegramAccount::class, 'telegram_id', 'id');
    }
}
