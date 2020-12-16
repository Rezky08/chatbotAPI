<?php

namespace App\Helpers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class TelegramBot
{
    private $bot_token;
    private $client;
    function __construct(String $bot_token)
    {
        $this->bot_token = $bot_token;
        $this->client = new Client();
    }

    public function connectWebhook($webhook)
    {
        $url = env('TELEGRAM_API') . $this->bot_token . "/setWebhook?url=" . $webhook;
        try {
            $res = $this->client->get($url);
            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
