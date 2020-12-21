<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes;
    public $relationship = ['telegram', 'application'];

    public function application()
    {
        return $this->belongsTo(Application::class, 'app_id', 'id');
    }
    public function telegram()
    {
        return $this->belongsTo(TelegramAccount::class, 'account_id', 'id');
    }
    public function scopeApplicationChat()
    {
        return $this->where('account_id', NULL);
    }
    public function scopeTelegramChat()
    {
        return $this->where('account_id', '!=', NULL);
    }
}
