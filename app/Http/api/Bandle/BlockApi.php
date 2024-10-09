<?php

namespace App\Http\api\Bandle;

use App\Logic\Bandle\BandleLogic;
use App\Logic\Bandle\BlockLogic;

class BlockApi {
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

        $Block = New BlockLogic($id, $request);
        
        if($id > 0 && $Block->access) {
            if($func == "load_content") {
                echo $Block->load_content();
            } 
            else if($func == "item_position_set") {
                echo $Block->item_position_set();
            }
            else if($func == "renew_item") {
                echo $Block->renew_item();
            }
            else if($func == "renew_item_send") {
                echo $Block->renew_item_send();
            }
            else if($func == "item_remove") {
                echo $Block->item_remove();
            }
            else if($func == "item_remove_send") {
                echo $Block->item_remove_send();
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
// $bandle_id = 0;
// if(isset($request->bandle_id)) {
//     $bandle_id = $request->bandle_id;
// }
// $link_id = 0;
// if(isset($request->link_id)) {
//     $link_id = $request->link_id;
// }
// $contact_id = 0;
// if(isset($request->contact_id)) {
//     $contact_id = $request->contact_id;
// }

// $Bundle = New BandleLogic;
// $Block = New BlockLogic;

// if($func == "load_content" && $id > 0) {
//     echo $Block->load_content($id);
// }

// else if($id > 0 && $Block->access($id)) {
//     else if($func == "renew_item_send") {
//         echo $Block->renew_item_send($id, $request);
//     } 
//     else if($func == "remove_item") {
//         echo $Block->remove_item($id);
//     }
//     else if($func == "remove_item_send") {
//         echo $Block->remove_item_send($id);
//     }
//     else if($func == "add_social_link" && isset($request->link)) {
//         echo $Block->add_social_link($id, $request->link);
//     }
//     else if($func == "renew_social_link_content") {
//         echo $Block->renew_social_link_content($id);
//     }
//     else if($func == "add_contact_item" && isset($request->value) && isset($request->type)) {
//         echo $Block->add_contact_item($id, $request->value, $request->type);
//     }
//     else if($func == "renew_contact_content") {
//         echo $Block->renew_contact_content($id);
//     }
    
//     else {
//         echo 'No func';
//     }
// }

// else if($bandle_id > 0 && $Bundle->access($bandle_id)) {
//     
//     else if($func == "item_add_send" && isset($request->type_id)) {
//         echo $Block->add_item_send($bandle_id, $request->type_id);
//     } 
//     else {
//         echo 'No func';
//     }
// }

// else if($link_id > 0 && $Block->social_link_access($link_id)) {
//     if($func == "renew_social_link") {
//         echo $Block->renew_social_link($link_id, $request->value);
//     } 
//     else {
//         echo 'No func';
//     }
// }

// else if($contact_id > 0 && $Block->contact_access($contact_id)) {
//     if($func == "add_contact_item" && isset($request->type)) {
//         echo $Block->renew_contact_item($contact_id, $request->value, $request->type);
//     } 
//     else {
//         echo 'No func';
//     }
// }

// else {
//     echo 'No func';
// }