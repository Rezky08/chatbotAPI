<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\Client;

class Chat extends Model
{
    use HasFactory, SoftDeletes;
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
    public function telegram()
    {
        return $this->belongsTo(TelegramAccount::class, 'account_id', 'id');
    }
}
