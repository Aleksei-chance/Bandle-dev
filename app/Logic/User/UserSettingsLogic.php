<?php

namespace App\Logic\User;

class UserSettingsLogic {
    public function page_load() {
        $arr = array(
            
        );

        return view('user.index', $arr);
    }
}