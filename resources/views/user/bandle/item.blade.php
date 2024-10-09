<div class="bandle_item">
    <div class="bandle_head">
        <button class="modal_btn_close text_black"
            @if ($auth)
                onclick="location.href='/MyBandles'" 
            @else
                onclick="location.href='/SavedBandles'" 
            @endif
        style="width: 70px;">
            <i class="modal_close"></i>
            @if (Auth::check())
                Back
            @else
                Auth
            @endif
            
        </button>
        <p class="text_black text_title">{{ $title }}</p>
        <div style="width: 70px;" class="btn_zero">
            @if ($auth)
                <button class="btn_bandle_edit" onclick="bandle_renew_item({{ $id }}, 'location')"></button>
            @endif
        </div>
    </div>
    <div class="bandle_item_content" id="bandle_item_content">
        
    </div>
    <div class="modal_header" style="justify-content: center;" id="bandle_action_btn">
        @include('user.bandle.components.item_action_btn')
    </div>
</div>

