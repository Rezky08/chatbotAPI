<?php

namespace App\Http\Controllers;

use App\Helpers\Engine;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApplicationTrainController extends Controller
{
    private $client;
    function __construct()
    {
        $this->client = new Client();
    }

    public function train(Request $request, $id)
    {
        $user = Auth::user();
        $app = $user->application->find($id);
        if (!$app) {
            $response = [
                'error' => 'cannot find your application'
            ];
            return redirect()->back()->with($response)->withInput();
        }
        $engine = new Engine($app);
        $data_prep = $engine->dataPrep();
        $res = $engine->train($data_prep);
        if (!$res) {
            $response = [
                'error' => "Server Error 500"
            ];
            return redirect()->back()->with($response);
        }
        $response = [
            'success' => "Success Train " . $app->client->name
        ];
        return redirect()->back()->with($response);
    }
}
