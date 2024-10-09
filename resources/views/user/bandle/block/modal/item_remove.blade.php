<div class="hover_modal" id="bandle_block_item_remove">
    <div class="remove_modal_body">
        <div class="remove_modal_content">
            <p class="text_black text_center">Delete this block?</p>
        </div>
        <div class="remove_modal_btn_group">
            <button class="remove_modal_btn text_red border_right" onclick="bandle_block_remove_item_send({{ $id }}, {{ $bandle_id }})">Delete</button>
            <button class="remove_modal_btn" action="modal:hide">Cancel</button>
        </div>
    </div>
</div>

<script>
    $('#bandle_block_item_remove').modal('show');
</script>