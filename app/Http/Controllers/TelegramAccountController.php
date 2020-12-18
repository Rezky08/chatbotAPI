<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Telegram;
use App\Models\TelegramAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TelegramAccountController extends Controller
{
    private $telegram_account_model;
    private $breadcrumbs;
    private $telegram_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->telegram_account_model = new TelegramAccount();
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
        $app_ids = $apps->pluck('id')->toArray();
        $telegrams = $this->telegram_model->active($app_ids)->get();
        if ($request->telegram_bot) {
            $telegrams = $telegrams->where('id', $request->telegram_bot);
        }
        $telegram_ids = $telegrams->pluck('id')->toArray();
        $accounts = $this->telegram_account_model->whereIn('telegram_id', $telegram_ids)->orderBy('telegram_id');
        if ($request->telegram_account) {
            $accounts = $accounts->where('id', $request->telegram_account);
        }
        $accounts = $accounts->get();
        $data = [
            'title' => "Account",
            'breadcrumbs' => $this->breadcrumbs,
            'accounts' => $accounts,
            'telegrams' => $telegrams
        ];
        return view('telegram.account.account_list', $data);
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
