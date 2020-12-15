<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class TesterController extends Controller
{
    private $user_model;
    protected $clients;
    private $client_model;
    function __construct(ClientRepository $clients)
    {
        $this->clients = $clients;
        $this->client_model = new Client();
        $this->user_model = new User();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $oauth_client_id = $request->get('oauth_client_id');
        dd($this->client_model->find($oauth_client_id)->user);
        // $client = $clients->create([
        //     'user_id' => 1,
        //     'name' => 'chatbottest',
        //     'redirect' => 'http://localhost',
        //     'personal_access_client' => false,
        //     'password_client' => false,
        //     'revoked' => false,
        //     // 'secret' => false,
        //     'created_at' => new \DateTime,
        //     'updated_at' => new \DateTime
        // ]);
        // dd($client->plainSecret);
        // $user->create();
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
