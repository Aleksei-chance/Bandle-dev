<?php

namespace App\Http\api\Bandle;

use App\Logic\Bandle\BandleLogic;

class BandleApi {
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

        $Bundle = New BandleLogic($id, $request);
        if($func == "item_add") {
            return $Bundle->add_item();
        } 
        else if($func == "item_add_send") {
            return $Bundle->add_item_send();
        } 
        else if($func == "item_save" && $id > 0) {
            echo $Bundle->item_save($id);
        } 

        else if($id > 0 && $Bundle->access) {
            if($func == "renew_item") {
                echo $Bundle->renew_item();
            } 
            else if($func == "renew_item_send") {
                echo $Bundle->renew_item_send();
            } 
            else if($func == "remove_item") {
                echo $Bundle->remove_item();
            } 
            else if($func == "remove_item_send") {
                echo $Bundle->remove_item_send();
            } 
            else if($func == "blocks_load") {
                echo $Bundle->blocks_load();
            } 
            else if($func == "block_add") {
                echo $Bundle->block_add();
            } 
            else if($func == "block_add_send") {
                echo $Bundle->block_add_send();
            }  
        
            else {
                echo 'No func';
            }
        }
        
        else {
            echo 'No func';
        }
    }
}



