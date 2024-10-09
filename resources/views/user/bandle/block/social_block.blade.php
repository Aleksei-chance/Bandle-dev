@if (count($items) > 0)
    <div class="social_link_content">
        @foreach ($items as $item)
            <button class="social_link_item"
                @if ($auth)
                    action="window.open('https://{!! $item['link'] !!}')"
                @else
                    onclick="window.open('https://{!! $item['link'] !!}')"
                @endif
            >
                @if ($item['icon'] != '')
                    <i class="block_icon url_link_icon" style="background-image: url('{!! $item['icon'] !!}')"></i>
                @else
                    <i class="block_icon link_icon"></i>
                @endif
            </button>
        @endforeach
    </div>
@else
    <div class="block_empty">
        <i class="{{ $icon }}"></i>
    </div>
@endif
