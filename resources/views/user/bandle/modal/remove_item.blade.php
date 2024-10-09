<div class="hover_modal" id="bandle_item_remove">
    <div class="remove_modal_body">
        <div class="remove_modal_content">
            <p class="text_black text_center">Delete this Bandle?</p>
        </div>
        <div class="remove_modal_btn_group">
            <button class="remove_modal_btn text_red border_right" onclick="bandle_remove_item_send({!! $id !!}, '{{ $func }}')">Delete</button>
            <button class="remove_modal_btn" action="modal:hide" >Cancel</button>
        </div>
    </div>
</div>


<script>
    $('#bandle_item_remove').modal('show');
</script>