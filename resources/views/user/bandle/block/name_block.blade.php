@if ($name != "")
    <div class="name_block_content_small">
        <div class="name_block_name_group">
            <h3 class="name_block_name">{{ $name }}</h3>
            <p class="name_block_pronouns">{{ $pronouns }}</p>
        </div>
        <p class="name_block_article">{{ $article }}</p>
    </div>
@else
    <div class="block_empty">
        <i class="name_card_icon"></i>
    </div>
@endif
