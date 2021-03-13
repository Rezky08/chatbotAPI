<?php

namespace App\Models;

use App\Traits\TableColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use HasFactory, SoftDeletes, TableColumn;
    protected $hidden = ['client_id', 'deleted_at'];

    public $relationship = ['telegram', 'application'];
    protected $fillable = ['text', 'text_response', 'replied'];

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
