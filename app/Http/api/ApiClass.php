<?php

namespace App\Http\api;

use Illuminate\Http\Request;
use App\Http\api\Bandle\BandleApi;
use App\Http\api\Bandle\BlockApi;
use App\Http\api\Bandle\UserApi;

class Api {
    public $request;
    private $type;

    function __construct($request)
    {
        $this->request = $request;
    }

    public function connect() {
        $type = $this->get_type();
        
        if($type == 'Bandle') {
            $data = new BandleApi($this->request);
            return $data->connect();
        }
        else if($type == 'User') {
            $data = new UserApi($this->request);
            return $data->connect();
        }
        else if($type == "BandleBlock") {
            $data = new BlockApi($this->request);
            return $data->connect();
        }
        return 'No Type';
    }

    private function get_type()
    {
        $request = $this->request;
        if(isset($request->Type)){
            return $this->type = $request->Type;
        }
        return false;
    }
}