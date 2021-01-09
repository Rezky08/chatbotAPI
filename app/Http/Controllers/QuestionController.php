<?php

namespace App\Http\Controllers;

use App\Exports\QuestionExport;
use App\Helpers\Breadcrumb;
use App\Helpers\Engine;
use App\Imports\QuestionImport;
use App\Models\Question;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

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
    public function create($id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        $labels = $app->label;
        $data = [
            'title' => 'Add Question ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'labels' => $labels,
            'method' => 'POST'
        ];
        return view('data.question.question_form', $data);
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
            'text' => ['required', 'filled', 'unique:questions,text,NULL,id,app_id,' . $app->id . ',label_id,' . $request->label_id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $res = $this->question_model->firstOrCreate(
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
            'success' => "Success Add Question"
        ];
        return redirect()->back()->with($response);
    }

    public function export($id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        return Excel::download(new QuestionExport($id), $app->client->name . ".xlsx");
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
        return view('data.question.question_list', $data);
    }

    public function bulkStore(Request $request, $id)
    {
        $rules = [
            'question_file' => ['required', 'filled', 'file', 'mimes:xlsx,xls,txt,csv'],
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
            $question_import = new QuestionImport($app);
            $questions = Excel::import($question_import, $request->file('question_file'));
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

    public function preprocessing($id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        if (!$app) {
            return redirect('/question');
        }

        $engine = new Engine($app);
        $res = $engine->getTextPreprocessed();
        if (!$res) {
            $response = [
                'error' => "Cannot get Text Preprocessed"
            ];
            return redirect('/question')->with($response);
        }
        $data = $res['message'];
        try {
            foreach ($data as $item) {
                $question = $this->question_model->find($item->id);
                $question->preprocessed = $item->text;
                $question->save();
            }
            $response = [
                'success' => "Success Get Text Preprocessed"
            ];
            return redirect('question/' . $id)->with($response);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $question = $app->question->find($id);
        if (!$question) {
            $response = [
                'error' => "Question not Found"
            ];
            return redirect()->to('question/' . $app_id)->with($response);
        }
        $labels = $app->label;
        $data = [
            'title' => 'Edit Question ' . $app->client->name,
            'breadcrumbs' => $this->breadcrumbs,
            'labels' => $labels,
            'question' => $question,
            'method' => 'PUT'
        ];
        return view('data.question.question_form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $question = $app->question->find($id);
        if (!$question) {
            $response = [
                'error' => "Question not Found"
            ];
            return redirect()->to('question/' . $app_id)->with($response);
        }
        $rules  = [
            'label_id' =>  ['required', 'filled', 'exists:labels,id,app_id,' . $app->id . ',deleted_at,NULL'],
            'text' => ['required', 'filled', 'unique:questions,text,' . $id . ',id,app_id,' . $app->id . ',label_id,' . $request->label_id . ',deleted_at,NULL']
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }

        try {
            $question->label_id = $request->label_id;
            $question->text = $request->text;
            $question->save();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Edit Question"
        ];
        return redirect()->back()->with($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy($app_id, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($app_id);
        $question = $app->question->find($id);
        if (!$question) {
            $response = [
                'error' => "Question not Found"
            ];
            return redirect()->to('question/' . $app_id)->with($response);
        }
        try {
            $question->delete();
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $response = [
                'error' => "Internal Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Delete Question"
        ];
        return redirect()->back()->with($response);
    }
}
