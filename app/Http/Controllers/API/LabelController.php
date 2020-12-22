<?php

namespace App\Http\Controllers\API;

use App\Helpers\APIModel;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Label;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    private $label_model;
    protected $application_model;
    function __construct()
    {
        $this->application_model = new Application();
        $this->label_model = new Label();
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
            '*' => ['filled']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        $base_cond = [
            'app_id' => $app->id
        ];
        $api = new APIModel($this->label_model, $base_cond);

        try {
            $res = $api->get($request->all());
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
        return response()->json($res->toArray(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $client_id = $request->get('oauth_client_id');
        $app = $this->application_model->where('client_id', $client_id)->first();

        $rules = [
            'label_name' => ['required', 'filled', 'unique:labels,label_name,NULL,id,deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'ok' => false,
                'message' => $validator->errors()->first()
            ];
            return response()->json($response, 400);
        }

        try {
            $data_insert = [
                'app_id' => $app->id,
                'label_name' => $request->label_name,
                'created_at' => new \DateTime
            ];
            $res = $this->label_model->insert($data_insert);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            if (env('APP_DEBUG')) {
                $response = [
                    'ok' => false,
                    'message' => $e->getMessage()
                ];
                return response()->json($response, 500);
            } else {
                $response = [
                    'ok' => false,
                    'message' => 'Server Internal Error 500'
                ];
                return response()->json($response, 500);
            }
        }
        $response = [
            'ok' => true,
            'message' => 'success'
        ];
        return response()->json($response, 200);
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
