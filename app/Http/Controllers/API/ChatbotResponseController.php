<?php

namespace App\Http\Controllers\API;

use App\Helpers\APIModel;
use App\Helpers\Engine;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Chat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ChatbotResponseController extends Controller
{
    protected $application_model;
    function __construct()
    {
        $this->application_model = new Application();
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
            'text' => ['filled', 'required']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        $engine = new Engine($app);

        try {
            $data = [
                'text' => $request->text,
            ];
            $res = $engine->getAnswer($request->text);
            if (!$res) {
                $data['replied'] = false;
            } else {
                $data['replied'] = true;
                $data['text_response'] = $res->message;
            }
            $app->chat()->create($data);
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
        return response()->json($res, 200);
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
