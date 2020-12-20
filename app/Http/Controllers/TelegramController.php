<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Helpers\TelegramBot;
use App\Models\Telegram;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class TelegramController extends Controller
{
    private $breadcrumbs;
    private $telegram_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->telegram_model = new Telegram();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $apps = $user->application;
        $app_ids = $apps->pluck('id')->toArray();
        $telegrams = $this->telegram_model->active($app_ids)->get();
        $data = [
            'title' => "Telegram",
            'telegrams' => $telegrams,
            'apps' => $apps,
            'breadcrumbs' => $this->breadcrumbs
        ];
        return view('telegram.bot.bot_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $rules = [
            'name' => ['required', 'filled'],
            'username' => ['required', 'filled'],
            'token' => ['required', 'filled', 'unique:telegrams,bot_token,NULL,id,deleted_at,NULL'],
            'app' => ['required', 'filled', 'exists:applications,id,user_id,' . $user->id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'error' => collect($validator->errors()->messages())->flatten()->first()
            ];
            return redirect()->back()->with($response);
        }

        // $webhook = env('TELEGRAM_WEBHOOK') . $request->token;
        $webhook = env('TELEGRAM_WEBHOOK');
        $stat_webhook = (new TelegramBot($request->token))->connectWebhook($webhook);
        if (!$stat_webhook) {
            $response = [
                'error' => 'Sorry, cannot add telegram bot now.'
            ];
            return redirect()->back()->with($response);
        }

        try {
            $data_insert = [
                'app_id' => $request->app,
                'bot_token' => $request->token,
                'name' => $request->name,
                'username' => $request->username,
                'webhook' => $webhook,
                'created_at' => new \DateTime
            ];
            $this->telegram_model->insert($data_insert);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success add telegram bot " . $request->name . "!"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function show(Telegram $telegram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function edit(Telegram $telegram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Telegram $telegram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Telegram  $telegram
     * @return \Illuminate\Http\Response
     */
    public function destroy($app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $telegram = $app->telegram->find($id);

        if (!$telegram) {
            $response = [
                'error' => "we cannot find your bot"
            ];
            return redirect()->back()->with($response);
        }

        // Disconnect from webhook
        $stat_webhook = (new TelegramBot($telegram->bot_token))->disconnectWebhook($telegram->webhook);
        if (!$stat_webhook) {
            $response = [
                'error' => 'Sorry, cannot disconnect telegram bot now.'
            ];
            return redirect()->back()->with($response);
        }
        try {
            $telegram->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }

        $response = [
            'success' => "Success disconnect telegram bot " . $telegram->name . "!"
        ];
        return redirect()->back()->with($response);
    }
}
