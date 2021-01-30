<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Label;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    private $breadcrumbs;
    private $label_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->label_model = new Label();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        $data = [
            'title' => 'Add Label ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'method' => 'POST'
        ];
        return view('data.label.label_form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        $rules  = [
            'label_name' =>  ['required', 'filled', 'unique:labels,label_name,NULL,id,app_id,' . $app->id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
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
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Add Label"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {

        $user = Auth::user();
        $apps = $user->application;
        $app = $apps->find($id);
        $labels = [];
        if ($app) {
            $labels = $app->label()->paginate();
        }
        $data = [
            'title' => "Label",
            'breadcrumbs' => $this->breadcrumbs,
            'labels' => $labels,
            'apps' => $apps,
            'app' => $app,
            'number' => $labels ? $labels->firstItem() : 0
        ];
        return view('data.label.label_list', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $label = $app->label->find($id);
        $data = [
            'title' => 'Add Label ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'method' => 'PUT',
            'label' => $label
        ];
        return view('data.label.label_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $rules  = [
            'label_name' =>  ['required', 'filled', 'unique:labels,label_name,' . $id . ',id,app_id,' . $app->id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $label = $this->label_model->find($id);
            $label->label_name = $request->label_name;
            $label->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Update Label"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($app_id, $id)
    {

        $user = Auth::user();
        $app = $user->application->find($app_id);
        $label = $app->label->find($id);
        if (!$label) {
            $response = [
                'error' => "Label not Found"
            ];
            return redirect()->to('label/' . $app_id)->with($response);
        }
        try {
            $label->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Delete Label"
        ];
        return redirect()->back()->with($response);
    }
}
