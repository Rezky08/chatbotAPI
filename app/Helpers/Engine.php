<?php

namespace App\Helpers;

use App\Models\Application;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Illuminate\Support\Facades\Log;
use stdClass;

class Engine
{
    protected $app;
    private $client;
    private $headers;
    function __construct(Application $app)
    {
        $this->app = $app;
        $this->client = new Client();
        $this->headers = [
            'Accept' => 'Application/json'
        ];
    }
    public function dataPrep()
    {
        $data = [
            'id' => $this->app->client_id,
            'token' => env('APP_KEY'),
            'questions' => [],
            'answers' => [],
            'labels' => [],

        ];
        $questions = $this->app->question->where('label_id', '!=', null);
        $answers = $this->app->answer->where('label_id', '!=', null);

        $labels = $this->app->label;
        $questions->map(function ($item) use (&$data) {
            $data['questions'][] = [
                'text'  => $item->text,
                'label' => $item->label->label_name
            ];
            $data['labels'][] = [
                'name' => $item->label->label_name
            ];
        });
        $answers->map(function ($item) use (&$data) {
            $data['answers'][] = [
                'text'  => $item->text,
                'label' => $item->label->label_name
            ];
        });
        $data['labels'] = collect($data['labels'])->unique('name')->values()->toArray();
        // $labels->map(function ($item) use (&$data) {
        //     $data['labels'][] = [
        //         'name' => $item->label_name
        //     ];
        // });
        return $data;
    }
    public function getAnswer($text)
    {
        $data = [
            'id' => $this->app->client_id,
            'token' => env('APP_KEY'),
            'text' => $text
        ];

        try {
            $url = env('ENGINE_API') . 'getChat';
            $res = $this->client->post($url, ['json' => $data, 'headers' => $this->headers]);
            $response = $res->getBody()->getContents();
            $response = json_decode($response);
        } catch (BadResponseException $e) {
            $res = $e->getResponse();
            $responseBodyAsString = $res->getBody()->getContents();
            if ($res->getStatusCode() == 400) {
                Log::error($responseBodyAsString);
                $response = json_decode($responseBodyAsString);
            } else {
                return false;
            }
        }
        return $response;
    }
    public function train($dataPrep)
    {
        try {
            try {
                $url = env('ENGINE_API') . 'train';
                $res = $this->client->post($url, ['json' => $dataPrep, 'headers' => $this->headers]);
                $response = $res->getBody()->getContents();
                $response = json_decode($response);
                $res = collect($response)->values()->first();
                $response = [
                    'ok' => true,
                    'message' => collect($res)->first()
                ];
            } catch (ClientException $e) {
                $res = $e->getResponse();
                $responseBodyAsString = $res->getBody()->getContents();
                if ($res->getStatusCode() == 400) {
                    Log::error($responseBodyAsString);
                    $response = json_decode($responseBodyAsString);
                    $res = collect($response)->values()->first();
                    $response = [
                        'ok' => false,
                        'message' => collect($res)->first()
                    ];
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return false;
        }

        return $response;
    }
}
