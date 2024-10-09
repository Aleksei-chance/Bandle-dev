<div class="hover_modal" id="bandle_social_block_renew">
    <div class="modal_body" style="padding: 0 0 8px 0;">
        <div class="modal_header" style="padding: 0 8px">
            <button class="modal_btn_close text_black" action="modal:hide">
                <i class="modal_close"></i>
                Back
            </button>
            <div class="title_with_icon">
                <i class="{{ $icon }}"></i>
                <p class="text_black text_title">{{ $name }}</p>
            </div>
            <div style="width: 70px;"></div>
        </div>
        <div class="modal_content" style="gap: 0" id="social_link_content">
            @include('user.bandle.block.modal.social_block_renew_content')
        </div>
        <button class="modal_main_small" action="modal:hide">Done</button>
    </div>
</div>

<script>
    $('#bandle_social_block_renew').modal('show');
</script>