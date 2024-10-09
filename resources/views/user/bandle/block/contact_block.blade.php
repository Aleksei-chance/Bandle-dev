@if (count($items) > 0)
    <div class="contact_content">
        @foreach ($items as $item)
            <button class="contact_item {{ $item['icon'] }}"
            @if ($item['type'] == 1 && $auth)
                action="location.href='mailto:{{ $item['value'] }}'"
            @elseif($item['type'] == 2 && $auth)
                action="location.href='tel:{{ $item['value'] }}'"
            @elseif($item['type'] == 3 && $auth)
                action="location.href='{{ $item['value'] }}'"
            @elseif ($item['type'] == 1)
                onclick="location.href='mailto:{{ $item['value'] }}'"
            @elseif($item['type'] == 2)
                onclick="location.href='tel:{{ $item['value'] }}'"
            @elseif($item['type'] == 3)
                onclick="location.href='{{ $item['value'] }}'"
            @endif
            ></button>
        @endforeach
    </div>
@else
    <div class="block_empty">
        <i class="{{ $icon }}"></i>
    </div>
@endif