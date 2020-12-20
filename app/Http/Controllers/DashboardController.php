<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $breadcrumbs;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
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
        $data = [
            'title' => "Dashboard",
            'breadcrumbs' => $this->breadcrumbs,
            'telegram_count' => 0,
            'account_count' => 0,
            'chat_count' => 0,
            'question_count' => 0,
            'answer_count' => 0,
            'label_count' => 0
        ];
        $apps->map(function ($app, $key) use (&$data) {
            $data['telegram_count'] += $app->telegram->count();
            $data['chat_count'] += $app->chat->count();
            $data['question_count'] += $app->question->count();
            $data['answer_count'] += $app->answer->count();
            $data['label_count'] += $app->label->count();
            $app->telegram->map(function ($telegram, $key) use (&$data) {
                $data['account_count'] += $telegram->account->count();
            });
        });
        return view('dashboard', $data);
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
