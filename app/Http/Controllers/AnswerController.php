<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Imports\AnswerImport;
use App\Models\Answer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class AnswerController extends Controller
{
    private $breadcrumbs;
    private $answer_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->answer_model = new Answer();
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
        $labels = $app->label;
        $data = [
            'title' => 'Add Answer ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'labels' => $labels,
            'method' => 'POST'
        ];
        return view('data.answer.answer_form', $data);
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
            'label_id' =>  ['required', 'filled', 'exists:labels,id,app_id,' . $app->id . ',deleted_at,NULL'],
            'text' => ['required', 'filled', 'unique:answers,text,NULL,id,app_id,' . $app->id . ',label_id,' . $request->label_id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $res = $this->answer_model->firstOrCreate(
                [
                    'app_id' => $app->id,
                    'label_id' => $request->label_id,
                    'text' => $request->text,
                ],
                [
                    'created_at' => new \DateTime
                ]
            );
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Add Answer"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $user = Auth::user();
        $apps = $user->application;
        $app = $apps->find($id);
        $answers = [];
        if ($app) {
            $answers = $app->answer()->paginate(15);
        }
        $data = [
            'title' => "Answer",
            'breadcrumbs' => $this->breadcrumbs,
            'answers' => $answers,
            'apps' => $apps,
            'app' => $app,
            'number' => $answers ? $answers->firstItem() : 0
        ];
        return view('data.answer.answer_list', $data);
    }

    public function bulkStore(Request $request, $id)
    {
        $rules = [
            'answer_file' => ['required', 'filled', 'file', 'mimes:xlsx,xls,txt,csv'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'error' => $validator->errors()->first()
            ];
            return redirect()->back()->with($response)->withErrors($validator->errors())->withInput();
        }
        $user = Auth::user();
        $app = $user->application->find($id);
        try {
            $answer_import = new AnswerImport($app);
            $answers = Excel::import($answer_import, $request->file('answer_file'));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Import Data"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $answer = $app->answer->find($id);
        if (!$answer) {
            $response = [
                'error' => "Answer not Found"
            ];
            return redirect()->to('answer/' . $app_id)->with($response);
        }
        $labels = $app->label;
        $data = [
            'title' => 'Edit Answer ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'labels' => $labels,
            'answer' => $answer,
            'method' => 'PUT'
        ];
        return view('data.answer.answer_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $answer = $app->answer->find($id);
        if (!$answer) {
            $response = [
                'error' => "Answer not Found"
            ];
            return redirect()->to('answer/' . $app_id)->with($response);
        }
        $rules  = [
            'label_id' =>  ['required', 'filled', 'exists:labels,id,app_id,' . $app->id . ',deleted_at,NULL'],
            'text' => ['required', 'filled', 'unique:answers,text,' . $id . ',id,app_id,' . $app->id . ',label_id,' . $request->label_id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $answer->label_id = $request->label_id;
            $answer->text = $request->text;
            $answer->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Edit Answer"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function destroy($app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $answer = $app->answer->find($id);
        if (!$answer) {
            $response = [
                'error' => "Answer not Found"
            ];
            return redirect()->to('answer/' . $app_id)->with($response);
        }
        try {
            $answer->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Delete Answer"
        ];
        return redirect()->back()->with($response);
    }
}
