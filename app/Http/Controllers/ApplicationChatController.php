<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApplicationChatController extends Controller
{
    private $breadcrumbs;
    private $chat_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->chat_model = new Chat();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id = null)
    {
        $user = Auth::user();
        $apps = $user->application;
        $chats = [];
        $app = null;
        if ($id) {
            $app = $apps->find($id);
            if ($app) {
                $chats = $app->chat()->applicationChat()->paginate();
            } else {
                $response = [
                    'error' => 'Cannot find your application'
                ];
                return redirect()->to('application/chat')->with($response);
            }
        }


        $data = [
            'title' => 'Application Chat',
            'breadcrumbs' => $this->breadcrumbs,
            'chats' => $chats,
            'apps' => $apps,
            'app' => $app,
            'number' => $chats ? $chats->firstItem() : 0
        ];
        return view('application.chat.chat_list', $data);
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
