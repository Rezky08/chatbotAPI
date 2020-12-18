<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

class ApplicationKeyController extends Controller
{
    private $user_model;
    private $title;
    private $client_repo;
    private $breadcrumbs;
    function __construct(ClientRepository $client_repo, Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->client_repo = $client_repo;
        $this->title = "Application Key";
        $this->user_model = new User();
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

        $data = [
            'title' => $this->title,
            'apps' => $apps,
            'breadcrumbs' => $this->breadcrumbs
        ];
        return view('application.key.key_list', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs

        ];
        return view('application.key.key_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'redirect' => ['required', 'filled', "regex:/^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:\/?#[\]@!\$&'\(\)\*\+,;=.]+$/"],
            'app_name' => ['required', 'filled']
        ];
        $message = [
            'redirect.regex' => "The redirect format is invalid. eg. Https://example.com"
        ];
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        $user = Auth::user();
        try {
            $res = $this->client_repo->create($user->id, $request->app_name, $request->redirect);
            $app_data_insert = [
                'user_id' => $res->user_id,
                'client_id' => $res->id,
                'created_at' => $res->created_at
            ];
            $res = $user->application()->insert($app_data_insert);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Application " . $request->app_name . " has been added"
        ];
        return redirect()->to('/application/key')->with($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
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
        $user = Auth::user();
        $app = $user->application->find($id);
        try {
            $this->client_repo->delete($app->client);
            $app->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Your app secret key has been revoked"
        ];
        return redirect()->back()->with($response);
    }
}
