<?php

namespace App\Logic\Bandle;

use App\Models\Bandle;
use App\Models\Block;
use App\Models\BlockType;
use App\Models\Contact;
use App\Models\ContactType;
use App\Models\NameBlock;
use App\Models\SocialLink;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BlockLogic {

    public $id = 0;
    public $access = false;

    private $request = null;

    private $position = 0;
    private $content_id = 0;
    
    private $name = '';
    private $article = '';
    private $pronouns = '';

    

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
        if(isset($request->position)) {
            $this->position = $request->position;
        }
        if(isset($request->name)) {
            $this->name = $request->name;
        }
        if(isset($request->article)) {
            $this->article = $request->article;
        }
        if(isset($request->pronouns)) {
            $this->pronouns = $request->pronouns;
        }
    }

    private function access() {
        $block = Block::query()->find($this->id);
        
        $user_id = Auth::id();
        if($user_id == $block->user_id) {
            return true;
        }
        return false;
    }

    public function load_content() {
        $block = Block::query()->find($this->id);
        $bandle_id = $block->bandle_id;
        $BandleLogic = New BandleLogic;
        $BandleLogic = New BandleLogic($bandle_id, null);
        $auth = (Auth::check() && $BandleLogic->access);

        $block_type_id = $block->block_type_id;
        $icon = BlockType::query()->find($block_type_id)->icon;
        $arr = array();
        if($block_type_id == 1) {
            $arr['auth'] = $auth;
            $content = $block->name_content()->toArray();
            return view('user.bandle.block.name_block', array_merge($arr, $content));

        } else if($block_type_id == 2) {
            $arr = array(
                'id' => $this->id
                , 'icon' => $icon
                , 'items' => $block->social_links()->get()->toArray()
                , 'auth' => $auth
            );

            return view('user.bandle.block.social_block', $arr);
        } else if ($block_type_id == 3) {
            $contacts = $block->contacts()->get();
            $items = array();
            foreach($contacts as $item) {
                $icon = ContactType::query()->find($item->contact_type_id)->icon;
                $items[] = array(
                    'id' => $item->id
                    , 'icon' => $icon
                    , 'type' => $item->contact_type_id
                    , 'value' => $item->value
                );
            }

            $arr = array(
                'id' => $this->id
                , 'icon' => $icon
                , 'items' => $items
                , 'auth' => $auth
            );

            return view('user.bandle.block.contact_block', $arr);
        }
        return 0;
    }

    public function item_position_set() {

        $position = $this->position;

        $user_id = Auth::id();
        $block_old = Block::query()->find($this->id);
        $sort_old = $block_old->sort;
        $bandle_id = $block_old->bandle_id;
        $ids = array();
        if($position > $sort_old) {
            for($i = $sort_old + 1; $i <= $position; $i++) {
                $ids[] = Block::query()->where('bandle_id', $bandle_id)->where('user_id', $user_id)->where('sort', $i)->where('publish', 1)->where('hidden', 0)->value('id');
            }
            foreach($ids as $item) {
                $block = Block::query()->find($item);
                $sort = $block->sort;
                $block->sort = $sort - 1;
                $block->save();
            }
            $block = Block::query()->find($this->id);
            $block->sort = $position;
            $block->save();
        }
        else if($position < $sort_old) {
            for($i = $sort_old - 1; $i >= $position; $i = $i - 1) {
                $ids[] = Block::query()->where('bandle_id', $bandle_id)->where('user_id', $user_id)->where('sort', $i)->where('publish', 1)->where('hidden', 0)->value('id');
            }
            foreach($ids as $item) {
                $block = Block::query()->find($item);
                $sort = $block->sort;
                $block->sort = $sort + 1;
                $block->save();
            }
            $block = Block::query()->find($this->id);
            $block->sort = $position;
            $block->save();
        }

        return 1;
    }

    public function renew_item() {
        $id = $this->id;
        $block_type_id = Block::query()->find($id)->block_type_id;
        $block_type = BlockType::query()->find($block_type_id);
        if($block_type_id == 1) {
            $content = Block::query()->find($id)->name_content()->toArray();
            $content['block_id'] = $id;
            return view('user.bandle.block.modal.name_block_renew', $content);
        } else if ($block_type_id == 2) {
            $content = array(
                'block_id' => $id
                , 'icon' => $block_type->icon
                , 'name' => $block_type->name
                , 'items' => Block::query()->find($id)->social_links()->get()->toArray()
            );
            return view('user.bandle.block.modal.social_block_renew', $content);
        } else if($block_type_id == 3) {
            
            $contact_types = ContactType::get();
            $contact_items = array();
            foreach($contact_types as $item) {
                $contact_items[$item->id] = array(
                    'id' => $item->id
                    , 'icon' => $item->icon
                    , 'name' => $item->name
                );
            }
            
            $content = array(
                'block_id' => $id
                , 'icon' => $block_type->icon
                , 'name' => $block_type->name
                , 'contact_types' => json_encode($contact_items)
                , 'items' => Block::query()->find($id)->contacts()->get()->toArray()
            );
            return view('user.bandle.block.modal.contact_block_renew', $content);
        }
        return 0;
    } 

    public function renew_item_send() {
        $block_type_id = Block::query()->find($this->id)->block_type_id;
        if($block_type_id == 1) {
            $this->content_id = Block::query()->find($this->id)->name_content()->id;
            return $this->name_block_renew();
        }
        return 0;
    } 

    public function name_block_renew() {
        $request = $this->request;
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'string', 'max:100']
            , 'article' => ['nullable', 'string', 'max:50']
            , 'pronouns' => ['nullable', 'string', 'max:25']
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

        $name_block = NameBlock::query()->find($this->content_id);
        foreach ($data as $key => $item) {
            $name_block->$key = $item;
        }
    
        if($name_block->save()) {
            return 1;
        }
        return 0;
    }

    public function item_remove() {
        $arr = array(
            'id' => $this->id
            , 'bandle_id' => Block::query()->find($this->id)->bandle_id
        );
        return view('user.bandle.block.modal.item_remove', $arr);
    }

    public function item_remove_send() {
        $user_id = Auth::id();
        $block = Block::query()->find($this->id);
        $block->hidden = 1;
        $sort = $block->sort;
        $bandle_id = $block->bandle_id;
        if($block->save()) {

            $blocks = Block::query()->select('id')->where('sort', '>', $sort)->where('bandle_id', $bandle_id)
            ->where('user_id', $user_id)->where('publish', 1)->where('hidden', 0)->get()->toArray();

            foreach($blocks as $item) {
                $block = Block::query()->find($item['id']);
                $sort = $block->sort;
                $block->sort = $sort - 1;
                $block->save();
            }

            $block_type_id = Block::query()->find($this->id)->block_type_id;
            if($block_type_id == 1) {
                $name_block_id = Block::query()->find($this->id)->name_content()->id;
                $name_block = NameBlock::query()->find($name_block_id);
                $name_block->hidden = 1;
                if($name_block->save()) {
                    return 1;
                }
            } else if($block_type_id == 2 || $block_type_id == 3) {
                return 1;
            }
        }
        return 0;
    }

    // public function add_social_link($id, $link) {
    //     $count = Block::query()->find($id)->social_links_count();
    //     $max = Block::query()->find($id)->social_links()->max('sort');
    //     $max++;

    //     $link = $this->parse_link($link);
    //     $icon = $this->get_icon_by_link($link);

    //     if($count < 6 && SocialLink::query()->create([
    //         'block_id' => $id
    //         , 'user_id' => Auth::id()
    //         , 'icon' => $icon
    //         , 'link' => $link
    //         , 'sort' => $max
    //     ])) {
    //         return 1;
    //     }
    //     return 0;
    // }

    // public function parse_link($link) {
    //     $url = parse_url($link);
    //     if(isset($url['host'])) {
    //         $link = $url['host'];
    //         if(isset($url['path'])) {
    //             $link .= $url['path'];
    //         }
    //         if(isset($url['query'])) {
    //             $link .= '?'.$url['query'];
    //         }
    //     }
    //     return $link;
    // }

    // public function get_icon_by_link($link) {
    //     $icon = $host = "";
    //     $url = parse_url($link);
    //     if(isset($url['host'])) {
    //         $host = $url['host'];
    //     } else {
    //         $arr = explode('/', $link);
    //         $host = $arr[0];
    //     }
    //     $url = 'http://www.google.com/s2/favicons?domain='.$host.'&sz=128';
        
    //     try {
    //         if(file_get_contents($url) != false) 
    //         {
    //             $icon = $url;
    //         }
    //     } catch (Exception $e) {
    //         $icon = '';
    //     }
        
    //     return $icon;
    // }

    // public function social_link_access($link_id) {
    //     $social_link = SocialLink::query()->find($link_id)->user_id;
    //     $user_id = Auth::id();
    //     if($social_link == $user_id) {
    //         return 1;
    //     }
    //     return 0;
    // }

    // public function renew_social_link($link_id, $value) {
    //     $social_link = SocialLink::query()->find($link_id);
    //     if($value != "") {
    //         $link = $this->parse_link($value);
    //         $icon = $this->get_icon_by_link($link);

    //         $social_link->link = $link;
    //         $social_link->icon = $icon;
    //         if($social_link->save()) {
    //             return 1;
    //         }
    //     } else {
    //         $social_link->hidden = 1;
    //         if($social_link->save()) {
    //             return 1;
    //         }
    //     }
    //     return 0;
    // }

    // public function renew_social_link_content($id) {
    //     $content = array(
    //         'block_id' => $id
    //         , 'items' => Block::query()->find($id)->social_links()->get()->toArray()
    //     );
    //     return view('user.bandle.block.modal.social_block_renew_content', $content);
    // }

    // public function add_contact_item($id, $value, $contact_type_id) {
    //     $count = Block::query()->find($id)->contacts_count();
    //     $max = Block::query()->find($id)->contacts()->max('sort');
    //     $max++;

    //     if($count < 3 && Contact::query()->create([
    //         'block_id' => $id
    //         , 'user_id' => Auth::id()
    //         , 'contact_type_id' => $contact_type_id
    //         , 'value' => $value
    //         , 'sort' => $max
    //     ])) {
    //         return 1;
    //     }
    //     return 0;
    // }
    

    // public function contact_access($contact_id) {
    //     $contact_id = Contact::query()->find($contact_id)->user_id;
    //     $user_id = Auth::id();
        
    //     if($contact_id == $user_id) {
    //         return 1;
    //     }
    //     return 0;
    // }

    // public function renew_contact_item($contact_id, $value, $type) {
    //     $contact = Contact::query()->find($contact_id);
    //     if($value != "") {
    //         $contact->contact_type_id = $type;
    //         $contact->value = $value;
    //         if($contact->save()) {
    //             return 1;
    //         }
    //     } else {
    //         $contact->hidden = 1;
    //         if($contact->save()) {
    //             return 1;
    //         }
    //     }
    //     return 0;
    // }

    // public function renew_contact_content($id) {
    //     $contact_types = ContactType::get();
    //     $contact_items = array();
    //     foreach($contact_types as $item) {
    //         $contact_items[$item->id] = array(
    //             'id' => $item->id
    //             , 'icon' => $item->icon
    //             , 'name' => $item->name
    //         );
    //     }
    //     $content = array(
    //         'block_id' => $id
    //         , 'contact_types' => json_encode($contact_items)
    //         , 'items' => Block::query()->find($id)->contacts()->get()->toArray()
    //     );
    //     return view('user.bandle.block.modal.contact_block_renew_content', $content);
    // }

    
}