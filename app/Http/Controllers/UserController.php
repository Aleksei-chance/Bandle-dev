<?php

namespace App\Http\Controllers;

use App\Http\api\Bandle\BlockApi;
use App\Logic\Bandle\BandleLogic;
use App\Logic\User\UserLogic;
use App\Logic\User\UserSettingsLogic;
use App\Models\Bandle;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function MyBandles(Request $request) {
        return UserLogic::load_main($request, 0);
    }

    public function SavedBandles(Request $request) {
        return UserLogic::load_main($request, 1);
    }

    public function settings(Request $request) {
        $user_settings_logic = New UserSettingsLogic;
        return $user_settings_logic->page_load();
    }

    public function bandle($id, Request $request) {

        $bandle_logic = New BandleLogic($id, $request);
        return $bandle_logic->load_item();
    }
}

