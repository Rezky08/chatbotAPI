<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Client;

class Application extends Model
{
    use HasFactory, SoftDeletes;
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function scopeActive()
    {
        return $this->with('client')->get()->where('revoke', 0);
    }

    public function chat()
    {
        return $this->hasMany(Chat::class, 'app_id', 'id');
    }

    public function telegram()
    {
        return $this->hasMany(Telegram::class, 'app_id', 'id');
    }
}
