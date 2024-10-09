@if ($auth)
    <button class="bandle_add_btn text_black" onclick="bandle_block_item_add({{ $id }})">
        <i class="bandle_add_btn_icon"></i>
        Add
    </button>
@elseif (Auth::check())
    <button class="bandle_add_btn text_black" onclick="bandle_item_save({{ $id }});">
        @if ($saved)
            Saved
        @else
            Save
        @endif
    </button>
@else
    <button class="bandle_add_btn text_black" onclick="location.href='/auth'">
        Auth
    </button>
@endif