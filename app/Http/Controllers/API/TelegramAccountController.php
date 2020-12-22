<?php

namespace App\Http\Controllers\API;

use App\Helpers\APIModel;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\TelegramAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TelegramAccountController extends Controller
{
    private $telegram_account_model;
    protected $application_model;
    function __construct()
    {
        $this->application_model = new Application();
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
            '*' => ['filled'],
            'telegram_id' => ['required', 'filled', 'exists:telegrams,id,app_id,' . $app->id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        $base_cond = [
            'telegram_id' => $request->telegram_id
        ];
        $api = new APIModel($this->telegram_account_model, $base_cond);

        try {
            $res = $api->get($request->all());
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
            'telegram_id' => ['required', 'filled', 'exists:telegrams,id,deleted_at,NULL'],
            'telegram_user_id' => ['required', 'filled', 'unique:telegram_accounts,telegram_user_id,NULL,id,telegram_id,' . $request->telegram_id . ',deleted_at,NULL'],
            'first_name' => ['required', 'filled'],
            'last_name' => ['required', 'filled'],
            'username' => ['filled', 'unique:telegram_accounts,username,NULL,id,telegram_id,' . $request->telegram_id . ',deleted_at,NULL'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'ok' => false,
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        try {
            $data_insert = [
                'telegram_id' => $request->telegram_id,
                'telegram_user_id' => $request->telegram_user_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'username' => $request->username,
                'created_at' => new \DateTime
            ];
            $res = $this->telegram_account_model->insert($data_insert);
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
        $response = [
            'ok' => true,
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
