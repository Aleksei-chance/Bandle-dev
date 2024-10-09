<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\api\Api;

class ApiController extends Controller
{
    public function Api(Request $request) {
        $api = new Api($request);
        return $api->connect();
    }
}
