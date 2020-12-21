<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\TokenRepository;
use Lcobucci\JWT\Parser;
use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    private $client_model;
    private $token_controller;
    function __construct(AuthorizationServer $server, TokenRepository $tokens, Parser $jwt)
    {
        $this->client_model = new Client();
        $this->token_controller = new AccessTokenController($server, $tokens, $jwt);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServerRequestInterface $request)
    {
        $req_data = $request->getParsedBody();

        $rules = [
            'secret' => ['required', 'filled', 'exists:oauth_clients,secret,revoked,0']
        ];
        $message = [
            'secret.exists' => 'Invalid Secret Key'
        ];
        $validator = Validator::make($req_data, $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }
        $client = $this->client_model->where('secret', $req_data['secret'])->first();
        $req_data = [
            'grant_type' => 'client_credentials',
            'client_id' => $client->id,
            'client_secret' => $req_data['secret'],
            'scope' => 'application',
        ];
        $request = $request->withParsedBody($req_data);
        $res = $this->token_controller->issueToken($request);
        return $res;
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
