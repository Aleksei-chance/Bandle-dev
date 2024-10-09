<?php

namespace App\Logic\User;

use App\Models\BandleSortType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserLogic {
    public $id = 0;

    private $sort = 1;
    private $type_view = 0;

    function __construct($id = 0, $request = null)
    {
        $this->id = $id;
        $this->set_params($request);
    }

    private function set_params($request)
    {
        if(isset($request->sort)) {
            $this->sort = $request->sort;
        }
        if(isset($request->type_view)) {
            $this->type_view = $request->type_view;
        }
    }

    public static function load_main($request, $type_view) {
        
        $sort_types = array();
        if($type_view == 1) {
            $sort_types = BandleSortType::get();
        }

        $sort_id = 1;
        if(isset($request->sort))
        {
            $sort_id = $request->sort;
        }

        $arr = array(
            'type' => 'bandle_list'
            , 'type_view' => $type_view
            , 'sort_types' => $sort_types
            , 'sort' => $sort_id
        );

        return view('user.index', $arr);
    }

    public function load_items() {
        
        $sort = BandleSortType::query()->find($this->sort);
        $sort_value = explode('|', $sort->value);

        $type_view = $this->type_view;
        $user_id = Auth::id();
        $items = array();
        $add_avalable = false;
        
        if($type_view == 0) {
            $limit = Auth::user()->bandle_limit;
            $items = User::query()->find($user_id)->bandles()->get()->toArray();
            $add_avalable = false;
            if(count($items) < $limit) {
                $add_avalable = true;
            }
        } else if($type_view == 1) {
            $items = User::query()->find($user_id)->bandles_saved()->orderBy($sort_value[0], $sort_value[1])->get()->toArray();
        }

        $arr = array(
            'items' => $items
            , 'add_avalable' => $add_avalable
            , 'type_view' => $type_view
        );
        return view('user.bandle.items', $arr);
    }
}