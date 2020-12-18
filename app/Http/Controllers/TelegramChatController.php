<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Chat;
use App\Models\Telegram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramChatController extends Controller
{
    private $breadcrumbs;
    private $chat_model;
    private $telegram_model;

    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->chat_model = new Chat();
        $this->telegram_model = new Telegram();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $apps = $user->application()->active();
        $app_ids = $apps->pluck('id');
        $telegrams = $this->telegram_model->active($app_ids)->get();
        $accounts = [];
        $chats = [];
        if ($request->telegram_bot) {
            $accounts = $telegrams->find($request->telegram_bot)->account;
        }
        if ($request->telegram_account) {
            $chats = $accounts->find($request->telegram_account)->chat;
        }

        $data = [
            'title' => "Telegram Chat",
            'breadcrumbs' => $this->breadcrumbs,
            'telegrams' => $telegrams,
            'accounts' => $accounts,
            'chats' => $chats
        ];
        return view('telegram.chat.chat_list', $data);
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
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
