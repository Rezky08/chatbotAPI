<?php

namespace App\Models;

use App\Traits\TableColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TelegramAccount extends Model
{
    use HasFactory, SoftDeletes, TableColumn;
    protected $table = 'telegram_accounts';
    public $relationship = ['telegram', 'chat'];
    protected $hidden = ['client_id', 'deleted_at'];
    protected $fillable = ['telegram_id', 'telegram_user_id', 'first_name', 'last_name', 'username'];


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
