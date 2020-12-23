<?php

namespace App\Http\Controllers\API;

use App\Helpers\APIModel;
use App\Helpers\Engine;
use App\Helpers\TelegramBot;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Chat;
use App\Models\Telegram;
use App\Models\TelegramAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ApplicationChatController extends Controller
{
    private $chat_model;
    protected $application_model;

    function __construct()
    {
        $this->application_model = new Application();
        $this->chat_model = new Chat();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $client_id = $request->get('oauth_client_id');
        $app = $this->application_model->where('client_id', $client_id)->first();

        $rules = [
            '*' => ['filled']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        $base_cond = [
            'app_id' => $app->id
        ];
        $api = new APIModel($this->chat_model, $base_cond);

        try {
            $res = $api->woGet($request->all())->applicationChat()->get();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if (env('APP_DEBUG')) {
                $response = [
                    'message' => $e->getMessage()
                ];
                return response()->json($response, 500);
            } else {
                $response = [
                    'message' => 'Server Internal Error 500'
                ];
                return response()->json($response, 500);
            }
        }
        return response()->json($res->toArray(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client_id = $request->get('oauth_client_id');
        $app = $this->application_model->where('client_id', $client_id)->first();

        $rules = [
            'text' => ['required', 'filled'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        // insert to db
        try {
            $data_insert = [
                'app_id' => $app->id,
                'text' => $request->text,
                'created_at' => new \DateTime
            ];
            $chat_id = $app->chat()->insertGetId($data_insert);
            $chat = $app->chat->find($chat_id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if (env('APP_DEBUG')) {
                $response = [
                    'ok' => false,
                    'message' => $e->getMessage()
                ];
                return response()->json($response, 500);
            } else {
                $response = [
                    'ok' => false,
                    'message' => 'Server Internal Error 500'
                ];
                return response()->json($response, 500);
            }
        }

        $response = (new Engine($app))->getAnswer($request->text);
        $chat->text_response = $response->message;
        $chat->replied = true;
        $chat->save();
        return response()->json($response, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
