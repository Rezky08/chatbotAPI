<?php

namespace App\Http\Controllers;

use App\Helpers\Breadcrumb;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class QuestionController extends Controller
{
    private $breadcrumbs;
    private $question_model;
    function __construct(Request $request)
    {
        $this->breadcrumbs = (new Breadcrumb)->get($request->path());
        $this->question_model = new Question();
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
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $user = Auth::user();
        $apps = $user->application;
        $app = $apps->find($id);
        $questions = [];
        if ($app) {
            $questions = $app->question;
        }
        $data = [
            'title' => "Question",
            'breadcrumbs' => $this->breadcrumbs,
            'questions' => $questions,
            'apps' => $apps,
            'app' => $app
        ];
        return view('question.question_list', $data);
    }

    public function bulkStore(Request $request, $id)
    {
        $rules = [
            'question_file' => ['required', 'filled', 'file', 'mimes:xlsx,xls,txt'],
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $response = [
                'error' => $validator->errors()->first()
            ];
            return redirect()->back()->with($response)->withErrors($validator->errors())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        //
    }
}
