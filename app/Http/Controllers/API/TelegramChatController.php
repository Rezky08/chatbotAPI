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

class TelegramChatController extends Controller
{
    private $chat_model;
    protected $application_model;
    private $telegram_account_model;
    private $telegram_model;
    function __construct()
    {
        $this->application_model = new Application();
        $this->chat_model = new Chat();
        $this->telegram_model = new Telegram();
        $this->telegram_account_model = new TelegramAccount();
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
            $res = $api->woGet($request->all())->telegramChat()->get();
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
    public function store(Request $request, $client_id, $telegram_bot_token)
    {

        $request->request->add(['bot_token' => $telegram_bot_token]);
        $rules = [
            'bot_token' => ['required', 'filled', 'exists:telegrams,bot_token,deleted_at,NULL'],
            'message.text' => ['required', 'filled'],
            'message.from' => ['required', 'filled'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }
        $app = $this->application_model->where('client_id', $client_id)->first();
        if (!$app) {
            $response = [
                'message' => 'invalid Client Id'
            ];
            return response()->json($response, 400);
        }
        $telegram = $app->telegram->where('bot_token', $telegram_bot_token)->first();
        if (!$telegram) {
            $response = [
                'message' => 'invalid Bot Token'
            ];
            return response()->json($response, 400);
        }

        $telegram_account_columns = $this->telegram_account_model->getTableColumns();
        $excluded = ['id'];
        $only_same = array_intersect_key($request->message['from'], array_flip($telegram_account_columns));
        $only_same = array_diff_key($only_same, array_flip($excluded));

        $account_data = $only_same;

        // validate account
        $account = $this->telegram_account_model->firstOrCreate(
            [
                'telegram_id' => $telegram->id,
                'telegram_user_id' => $request->message['from']['id']
            ],
            $account_data
        );
        Log::debug($request);

        // insert to db
        try {
            $data_insert = [
                'account_id' => $account->id,
                'app_id' => $telegram->application->id,
                'text' => $request->message['text'],
                'created_at' => new \DateTime
            ];
            $chat_id = $account->chat()->insertGetId($data_insert);
            $chat = $account->chat->find($chat_id);
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

        $res = (new Engine($app))->getAnswer($request->message['text']);
        $sendReply = (new TelegramBot($telegram_bot_token))->sendMessage($res->message, $request->message['chat']['id']);

        $chat->text_response = $res->message;
        if ($sendReply) {
            $chat->replied = true;
        }
        $chat->save();

        $response = [
            'message' => 'success'
        ];
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
