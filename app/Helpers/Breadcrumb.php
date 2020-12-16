<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class Breadcrumb
{
    function get($url = null)
    {
        $path_explode = explode('/', $url);
        $path_name = str_replace('-', ' ', $path_explode);
        $breadcrumbs = [];
        foreach ($path_explode as $index => $item) {
            $breadcrumbs[] = [
                'name' => ucwords($path_name[$index]),
                'path' => URL::to(implode('/', array_slice($path_explode, 0, $index + 1)))
            ];
        }
        return $breadcrumbs;
    }
}
