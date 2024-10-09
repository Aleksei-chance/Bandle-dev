@foreach ($items as $item)
<div class="link_container" id="">
    <x-select_icon id="select_contact_{{ $item['id'] }}" items="{!! $contact_types !!}" selected="{{ $item['contact_type_id'] }}" 
    func="bandle_block_renew_contact(null, {{ $item['id'] }}, {{ $block_id }})"/>
    <label for="link_{{ $item['id'] }}" class="input_block link_field" id="link_block_{{ $item['id'] }}">
        <input type="text" class="contact_input" placeholder="Contact" id="contact_{{ $item['id'] }}"  oninput="input_valid(this)"
        onchange="bandle_block_renew_contact(this, {{ $item['id'] }}, {{ $block_id }})" value="{{ $item['value'] }}">
        <button class="input_btn link_input_btn" onclick="input_action(this);">
            <i class="icon_small icon_send"></i>
        </button>
    </label>
</div>
@endforeach
@if (count($items) < 3)
    <div class="add_field_container" id="contact_add_btn" onclick="bandle_block_contact_add_item()">
        <div class="add_field">
            add field
        </div>
        <i class="block_icon icon_add"></i>
    </div>
    <div class="link_container d_none" id="contact_add_input">
        <x-select_icon id="select_add_contact" items="{!! $contact_types !!}" selected="1"/>
        <label for="contact_add" class="input_block link_field" id="contact_block">
            <input type="text" class="contact_input" placeholder="Contact" id="contact_add"  oninput="input_valid(this)" onchange="bandle_block_contact_add_item_send(this, {{ $block_id }})">
            <button class="input_btn link_input_btn" onclick="input_action(this);">
                <i class="icon_small icon_send"></i>
            </button>
        </label>
    </div>
@endif