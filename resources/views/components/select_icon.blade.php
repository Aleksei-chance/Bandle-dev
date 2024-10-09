@php
    $items = json_decode($items, true);
@endphp
<div class="select_container">
    <input type="hidden" id="{{ $id }}_input" value="{{ $items[$selected]['id'] }}">
    <div class="select_content view_hover" onclick="select(this)">
        <div class="select_value select_icon">
            <i class="{{ $items[$selected]['icon'] }}"></i>
        </div>
        <i class="drop_down_icon"></i>
    </div>
    <div class="select_menu d_none">
        @foreach ($items as $item)
            <div class="select_menu_item view_hover" value="{{ $item['id'] }}" onclick="select_send(this); {!! $func ?? '' !!}">
                <i class="select_menu_icon {{ $item['icon'] }}"></i>
            </div>
        @endforeach
    </div>
</div>