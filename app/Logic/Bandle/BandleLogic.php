<?php

namespace App\Logic\Bandle;

use App\Models\Bandle;
use App\Models\BandleSaveLink;
use App\Models\BandleSortType;
use App\Models\Block;
use App\Models\BlockType;
use App\Models\NameBlock;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BandleLogic {

    public $id = 0;
    public $access = false;

    private $request = null;
    private $sort = 1;
    private $type_view = 0;
    private $func = '';

    private $block_type_id = 0;

    function __construct($id = 0, $request = null) {
        $this->id = $id;
        $this->request = $request;
        if($id > 0 && $this->access())
        {
            $this->access = true;
        }
        $this->set_params($request);
    }

    private function set_params($request) {
        if(isset($request->sort)) {
            $this->sort = $request->sort;
        }
        if(isset($request->type_view)) {
            $this->type_view = $request->type_view;
        }
        if(isset($request->Func)) {
            $this->func = $request->Func;
        }
        if(isset($request->block_type_id)) {
            $this->block_type_id = $request->block_type_id;
        }
    }

    private function access() {
        $bandle = Bandle::query()->find($this->id);
        
        $user_id = Auth::id();
        if($user_id == $bandle->user_id) {
            return true;
        }
        return false;
    }

    public function add_item() {
        return view('user.bandle.modal.add_item');
    }

    public function add_item_send() {
        $validator = Validator::make($this->request->all(), [
            "title" => ["required", 'string', 'max:100']
            , 'description' => ['nullable', 'string']
        ]);
    
        if ($validator->fails()) 
        {
            $messages = $validator->errors()->messages();
            $errors = array();
            foreach($messages as $key => $massage) {
                foreach($massage as $Item) {
                    $errors[] = $key.":".$Item;
                }
            }
            return implode("|", $errors);
        }
    
        $data = $validator->validated();
        $data['user_id'] = Auth::id();
        if($bandle = Bandle::create($data)) {
            return $bandle->id;
        }
        return 0;
    }

    public function renew_item() {
        $arr = array();
        $bandle = Bandle::query()->find($this->id);
        $arr['func'] = $this->func;
    
        if($bandle) {
            return view('user.bandle.modal.renew_item', array_merge($bandle->toArray(), $arr) );
        }
        
        return 0;
    }

    public function renew_item_send() {
        $validator = Validator::make($this->request->all(), [
            "title" => ["required", 'string', 'max:100']
            , 'description' => ['nullable', 'string']
        ]);
    
        if ($validator->fails()) 
        {
            $messages = $validator->errors()->messages();
            $errors = array();
            foreach($messages as $key => $massage) {
                foreach($massage as $Item) {
                    $errors[] = $key.":".$Item;
                }
            }
            return implode("|", $errors);
        }
    
        $data = $validator->validated();
    
        $bandle = Bandle::query()->find($this->id);
        foreach ($data as $key => $item) {
            $bandle->$key = $item;
        }
    
        if($bandle->save()) {
            return 1;
        }
        return 0;
    }

    public function remove_item() {
        $arr = array();
        $arr['func'] = $this->func;
        if($bandle = Bandle::query()->find($this->id)) {
            return view('user.bandle.modal.remove_item', array_merge($bandle->toArray(), $arr));
        }
        return 0;
    }


    public function remove_item_send() {
        $bandle = Bandle::query()->find($this->id);
        $bandle->hidden = 1;
        if($bandle->save()) {
            return 1;
        }
        return 0;
    }

    public function item_save($id) {
        $user_id = Auth::id();
        $link = BandleSaveLink::query()->where('user_id', $user_id)->where('bandle_id', $id)->where('publish', 1)->where('hidden', 0)->first();
        $saved = 0;
        if(!$link) {
            $link = BandleSaveLink::query()->create([
                'user_id' => $user_id
                , 'bandle_id' => $id
            ]);
            $saved = 1;
        } else {
            $link->hidden = 1;
            $link->save();
        }

        if($link) {
            $arr = array(
                'id' => $id
                , 'auth' => (Auth::check() && $this->access($id))
                , 'saved' => $saved
            );
            return view('user.bandle.components.item_action_btn', $arr);
        }
        return 0;
    }

    public function load_item() {
        $user_id = Auth::id();
        $arr = array();
        $arr['auth'] = 0;
        if(Auth::check() && $this->access($this->id)) {
            $arr['auth'] = 1;
        }
        $arr['saved'] = 0;
        if(BandleSaveLink::query()->where('user_id', $user_id)->where('bandle_id', $this->id)->where('publish', 1)->where('hidden', 0)->first()) {
            $arr['saved'] = 1;
        }

        $arr["func"] = 'bandle_block_items_load('.$this->id.', '.$arr['auth'].')'; 
        $arr["type"] = 'bandle';
        $bandle = Bandle::query()->find($this->id);
        return view('user.index', array_merge($bandle->toArray(), $arr));
    }

    public function blocks_load() {
        $arr = array();
        $arr['id'] = $this->id;
        $arr['auth'] = false;
        if(Auth::check() && $this->access()) {
            $arr['auth'] = true;
        }
        
        $arr['items'] = Bandle::query()->find($this->id)->blocks()->get()->toArray();
    
        return view('user.bandle.block.items', $arr);
    }

    public function block_add() {
        $arr['id'] = $this->id;
        $items = BlockType::query()->orderBy('sort', 'asc')->get();
        $arr["items"] = array();
        foreach($items as $item) {
            $limit = BlockType::query()->find($item->id)->limit;
            $count = Bandle::query()->find($this->id)->blocks_count($item->id);
            if($count < $limit || $limit == 0) {
                $arr["items"][] = $item->toArray();
            }
        }
    
        return view('user.bandle.block.modal.block_item_add', $arr);
    }

    public function block_add_send() {
        $user_id = Auth::id();
        $block_type_id = $this->block_type_id;
        $id = $this->id;
        $limit = BlockType::query()->find($block_type_id)->limit;
        $count = Bandle::query()->find($id)->blocks_count($block_type_id);
        $max = Bandle::query()->find($id)->blocks()->max('sort');
        $max++;
        if(($count < $limit || $limit == 0) && $block = Block::create([
            'bandle_id' => $id
            , 'user_id' => $user_id
            , 'block_type_id' => $block_type_id
            , 'sort' => $max
        ])) {
            if($block_type_id == 1 && NameBlock::query()->create([
                'block_id' => $block->id
                , 'user_id' => $user_id
            ])) {
                return 1;
            } else if($block_type_id == 2 || $block_type_id == 3) {
                return 1;
            }
            
        }
        return 0;
    }

    
}