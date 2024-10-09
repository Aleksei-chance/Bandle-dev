<?php

namespace App\Http\api\Bandle;

use App\Logic\User\UserLogic;

class UserApi {
    public $request;
    private $func = "";
    private $id = 0;

    function __construct($request)
    {
        $this->request = $request;
    }

    private function set_params() {
        $request = $this->request;
        if(isset($request->func)) {
            $this->func = $request->func;
        }
        if(isset($request->id)) {
            $this->id = $request->id;
        }
    }

    public function connect() {
        $this->set_params();
        $request = $this->request;
        $func = $this->func;
        $id = $this->id;

        $User = new UserLogic($id, $request);
        if($func == "items_load" && isset($request->type_view)) {
            echo $User->load_items();
        } 
        else {
            echo 'No func';
        }
    }
}