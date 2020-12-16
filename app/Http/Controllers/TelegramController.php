<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Telegram;
use Illuminate\Http\Request;

class TelegramController extends Controller
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
        $data = [
            'title' => "Telegram",
            'telegrams' => [],
            'breadcrumbs' => $this->breadcrumbs
        ];
        return view('telegram.telegram_list', $data);
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
        dd($request->all());
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
    public function destroy(Telegram $telegram)
    {
        //
    }
}
