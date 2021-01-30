<?php

namespace App\Models;

use App\Traits\TableColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Client;

class Application extends Model
{
    use HasFactory, SoftDeletes, TableColumn;
    public $relationship = ['user', 'client', 'chat', 'telegram', 'question', 'answer', 'label'];
    protected $hidden = ['client_id', 'deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id')->where('revoked', 0);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class, 'app_id', 'id');
    }

    public function telegram()
    {
        return $this->hasMany(Telegram::class, 'app_id', 'id');
    }
    public function telegram_account()
    {
        return $this->hasManyThrough(TelegramAccount::class, Telegram::class, 'app_id', 'telegram_id', 'id', 'id');
    }
    public function question()
    {
        return $this->hasMany(Question::class, 'app_id', 'id');
    }
    public function answer()
    {
        return $this->hasMany(Answer::class, 'app_id', 'id');
    }
    public function label()
    {
        return $this->hasMany(Label::class, 'app_id', 'id');
    }
}
