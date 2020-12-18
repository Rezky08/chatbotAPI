<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramAccount extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'telegram_accounts';
    public function chat()
    {
        return $this->hasMany(Chat::class, 'account_id', 'id');
    }
    function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    public function telegram()
    {
        return $this->belongsTo(Telegram::class, 'telegram_id', 'id');
    }
}
