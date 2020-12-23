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

    public function disconnectWebhook($current_webhook)
    {

        $url_get_info = env('TELEGRAM_API') . $this->bot_token . "/getWebhookInfo";
        $url_delete_webhook = env('TELEGRAM_API') . $this->bot_token . "/deleteWebhook";
        try {
            $res = $this->client->get($url_get_info);
            $webhook_info = json_decode($res->getBody()->getContents());
            if ($webhook_info->result->url == $current_webhook) {
                $res = $this->client->get($url_delete_webhook);
            }
            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
    public function sendMessage($text, $chat_id)
    {
        $url = env('TELEGRAM_API') . $this->bot_token . "/sendMessage";
        $data = [
            'chat_id' => $chat_id,
            'text' => $text
        ];
        try {
            $res = $this->client->get($url, ['query' => $data]);
            $webhook_info = json_decode($res->getBody()->getContents());
            return true;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }
    }
}
