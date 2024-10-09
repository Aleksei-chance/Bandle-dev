var TOKEN = $('meta[name="csrf-token"]').attr('content');

function bandle_items_load(type_view, sort) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'User', func: 'items_load', type_view: type_view, sort: sort}
    }).done(function(data){
        $("#bandle_container").html(data);
        bandle_actions(type_view);
    }).fail(function(data){
        
    });
}

function bandle_actions(type_view) {
    let check = 0;

    $(".bundle_action").bind("touchstart mousedown", function(e){
        let id = $(e.currentTarget).attr('id').replaceAll('bandle_', '');
        check = 1;
        Timer = setTimeout(function() {
            if(type_view == 0)
            {
                bandle_renew_item(id);
            }
            check = 0;
        }, 300);
    });


    $(".bundle_action").bind("touchend mouseup", function(e){
        let id = $(e.currentTarget).attr('id').replaceAll('bandle_', '');
        if(check) {
            location.href = "/bandle/"+id;
        }
        clearTimeout(Timer);
    });
}

function bandle_renew_item(id, Func = '') {
    
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'renew_item', id: id, Func: Func}
    }).done(function(data){
        $("#modal").html(data);
    }).fail(function(data){
        
    });
}

function bandle_renew_item_send(id, Func = '') {
    let title = $('#title').val();
    let description = $('#description').val();
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'renew_item_send', id: id, title: title, description: description}
    }).done(function(data){
        if(data > 0) {
            if(Func == "location") {
                location.reload();
            } else {
                bandle_items_load(0);
            }
            $('#bandle_item_renew').modal('hide');
        } else {
            input_error(data);
        }
    }).fail(function(data){
        
    });
}

function bandle_item_add() {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'item_add'}
    }).done(function(data){
        $("#modal").html(data);
    }).fail(function(data){
        
    });
}

function bandle_item_add_send() {
    let title = $('#title').val();
    let description = $('#description').val();
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'item_add_send', title: title, description: description}
    }).done(function(data){
        if(data > 0) {
            bandle_items_load(0);
            $('#bandle_item_and').modal('hide');
        } else {
            input_error(data);
        }
    }).fail(function(data){
        
    });
}

function input_error(data) {
    let massages = data.split('|');
    $.each(massages, function (index, value) { 
        let massage = value.split(':');
        let block = $("#"+massage[0]+'_block');
        block.find("input").addClass('input_error');
        if(block.find("input").val()) {
            block.find("i").removeClass('icon_send').addClass('icon_error');
        }
        block.find(".error_text").show().text(massage[1]);
    });
}

function input_valid(e) {
    let val = $(e).val();
    let block = $(e).parent().parent();
    block.find(".error_text").hide().text("");
    block.find("input").removeClass('input_error');
    block.find("i").removeClass('icon_error');
    if(val != "") {
        block.find("i").removeClass('icon_send');
        block.find("i").addClass('icon_clear');
    } else {
        block.find("i").addClass('icon_send');
        block.find("i").removeClass('icon_clear');
    }
}

function bandle_remove_item(id, Func = '') {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'remove_item', id: id, Func: Func}
    }).done(function(data){
        $("#modal_g").html(data);
    }).fail(function(data){
        location.reload();
    });
}

function bandle_remove_item_send(id, Func = '') {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'remove_item_send', id: id}
    }).done(function(data){
        if(data > 0) {
            $('#bandle_item_remove').modal('hide');
            $('#bandle_item_renew').modal('hide');
            if(Func == "location") {
                location.reload();
            } else {
                bandle_items_load(0);
            }
            
        }
    }).fail(function(data){
        
    });
}

function bandle_block_items_load(id, auth = false) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'blocks_load', id: id}
    }).done(function(data){
        $("#bandle_item_content").html(data);
        if(auth) {
            bandle_block_actions();
        }
    }).fail(function(data){
        
    });
}

function bandle_block_actions() {
    let func = "";
    let func_hold = false;
    let moveX = false;
    let moveY = false;
    let id_active = 0;

    let block = null;
    let content = null;

    let new_position = 0;

    $(".block_action").bind("touchstart mousedown", function(e){
        moveX = moveY = func_hold = check = new_poz = 0;
        id_active = $(e.currentTarget).attr('id').replace('block_', '');
        check = 1;
        func_hold = 0;
        
        let func_element = e.target;
        func = $(func_element).attr('action');
        if(func === undefined) {
            func = $(func_element).parent().attr('onclick');
        }

        block = $('#block_'+id_active);
        content = $('#block_'+id_active+'_content');
        let pozStart_Y = e.pageY;
        let width = $('.bandle_items_list').width();
        let top = block.position().top;
        let block_height = block.height();
        let list_height = $('.bandle_items_list').height();

        let positions = bandle_block_get_positions();

        $("#bandle_item_content").find('.block_remove').each(function() {
            let id_check = $(this).attr('id').replace('block_remove_', '');
            if(id_check != id_active) {
                $('#block_remove_'+id_check).css('width', 0);
                $('#block_remove_'+id_check).css('padding', 0);
                $('#block_remove_'+id_check).parent().css('gap', 0);
            }
        });

        oneSecondTimer = setTimeout(function() {
            if(!moveX) {
                $(document).unbind('touchmove mousemove');
                $(document).bind('touchmove mousemove', function(e) {
                    if(e.pageY < pozStart_Y - 10 || e.pageY > pozStart_Y + 10) {
                        moveY = true;
                    }
                    if((e.pageY < pozStart_Y || e.pageY > pozStart_Y)) {
                        bandle_block_remove_action(id_active);

                        block.height(block_height);
                        content.addClass('bandle_item_absolute');
                        content.css('width', width);

                        let new_poz = top - (pozStart_Y - e.pageY);
                        if(new_poz < 0) {
                            new_poz = 0;
                        } if(new_poz > (list_height - block_height)) {
                            new_poz = (list_height - block_height);
                        }

                        content.css('top', new_poz);
                        
                        let order = block.css('order');

                        $.each(positions, function(key, item) {
                            if(key == parseInt(order) + 1 && new_poz > item.topD) {
                                let move_id = item.id;
                                $('#block_' + move_id).css('order', order);
                                block.css('order', key);
                                new_position = key;
                            }
                            if(key == parseInt(order) - 1 && new_poz < item.topU) {
                                let move_id = item.id;
                                $('#block_' + move_id).css('order', order);
                                block.css('order', key);
                                new_position = key;
                            }
                        });

                        positions = bandle_block_get_positions();
                        
                        
                    }
                });
            }
            func_hold = true;
            func = "";
        }, 300);


        let pozStart_X = e.pageX;
        
        $(document).bind('touchmove mousemove', function(e) {
            if(e.pageX < pozStart_X || e.pageX > pozStart_X) {
                if(e.pageX < pozStart_X - 10 || e.pageX > pozStart_X + 10) {
                    moveX = 1;
                }
                
                let width = pozStart_X - e.pageX;
                if(width > 89) {
                    width = 89;
                }
                $('#block_remove_'+id_active).css('width', width);
                if(width > 0) {
                    $('#block_remove_'+id_active).css('padding', 8);
                    $('#block_remove_'+id_active).parent().css('gap', 8);
                } else {
                    $('#block_remove_'+id_active).css('padding', 0);
                    $('#block_remove_'+id_active).parent().css('gap', 0);
                }
            }
        });

        return false;
    });

    function bandle_block_get_positions() {
        let positions = {};
        $('.block').each(function() {
            let pos_order = $(this).css('order');
            let pos_id = $(this).attr('id').replace('block_', '');
            let pos_height = $(this).height()
            let pos_topD = $(this).position().top - pos_height / 2;
            let pos_topU = $(this).position().top + pos_height / 2;

            positions[pos_order] = {id: pos_id, topD: pos_topD, topU: pos_topU};
        });

        return positions;
    }

    $(document).bind("touchend mouseup", function(e){
        $(document).unbind('touchmove mousemove');
        clearTimeout(oneSecondTimer);

        if(!moveX && !moveY && func_hold) {
            bandle_block_renew_item(id_active);
        } else if(moveX && !moveY) {
            let remove_width = $('#block_remove_'+id_active).css('width').replace('px', '');
            if(remove_width > 59) {
                bandle_block_remove_action(id_active, 'set');
            } else {
                bandle_block_remove_action(id_active);
            }
        } else if(!moveX && moveY) {
            block.parent().height('auto');
            content.removeClass('bandle_item_absolute');
            content.css('width', 'auto');
            content.css('top', 0);
        } else if (!moveX && !moveY && !func_hold && func != "") {
            eval(func);
        }

        if(new_position > 0) {
            bandle_block_position_set(id_active, new_position);
            new_position = 0;
        }

        id_active = 0;
        moveX = moveY = func_hold = false;
        func = "";
    });

    $(document).on("touchcancel", function(e) {
        $(document).unbind('touchmove mousemove');
        clearTimeout(oneSecondTimer);
        id_active = 0;
        moveX = moveY = func_hold = false;
    })

    function bandle_block_remove_action(id, func = 'remove') {
        if(func == 'remove') {
            $('#block_remove_'+id).css('width', 0);
            $('#block_remove_'+id).css('padding', 0);
            $('#block_remove_'+id).parent().css('gap', 0);
        } else if(func == 'set') {
            $('#block_remove_'+id).css('width', 89);
            $('#block_remove_'+id).css('padding', 8);
            $('#block_remove_'+id).parent().css('gap', 8);
        }
    }
}

function bandle_block_position_set(id, position) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'item_position_set', id: id, position: position}
    }).done(function(data){
        console.log(data);
    }).fail(function(data){
        
    });
}


function bandle_block_item_add(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'block_add', id: id}
    }).done(function(data){
        $("#modal").html(data);
        $('#modal').addClass('modal_block_add')
    }).fail(function(data){
        
    });
}

function bandle_block_item_add_send(id, block_type_id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'block_add_send', id: id, block_type_id: block_type_id}
    }).done(function(data){
        if(data > 0)
        {
            bandle_block_items_load(id, 1);
            $('#bandle_block_item_add').modal('hide');
        }
    }).fail(function(data){
        
    });
}

function bandle_block_items_load_content() {
    $(document).find('.block').each(function() {
        let id = $(this).attr('id').replace('block_', '');
        bandle_block_load_item_content(id);
    });
}

function bandle_block_load_item_content(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'load_content', id: id}
    }).done(function(data){
        $("#block_"+id+"_content").html(data);
    }).fail(function(data){
        
    });
}

function bandle_block_renew_item(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'renew_item', id: id}
    }).done(function(data){
        $("#modal").html(data);
    }).fail(function(data){
        
    });
}

function bandle_block_renew_item_send(id) {
    let name = $('#name').val();
    let article = $('#article').val();
    let pronouns = $('#pronouns').val();
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'renew_item_send', id: id, name: name, article: article, pronouns: pronouns}
    }).done(function(data){
        if(data > 0) {
            bandle_block_load_item_content(id);
            $('#bandle_name_block_renew').modal('hide');
        } else {
            input_error(data);
        }
    }).fail(function(data){
        
    });
}

function bandle_block_remove_item(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'item_remove', id: id}
    }).done(function(data){
        $("#modal").html(data);
    }).fail(function(data){
        
    });
}

function bandle_block_remove_item_send(id, bandle_id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'item_remove_send', id: id}
    }).done(function(data){
        console.log(data);
        if(data > 0) {
            $('#bandle_block_item_remove').modal('hide');
            bandle_block_items_load(bandle_id, 1);
        }
    }).fail(function(data){
        
    });
}

function input_action(e) {
    let icon = $(e).children();
    let parent = $(e).parent();
    
    if(icon.hasClass('icon_send')) {
        parent.find("input").trigger('change');
    }
    if(icon.hasClass('icon_clear') || icon.hasClass('input_error')) {
        parent.find("input").val('');
    }
    input_valid(e);
    
}

function bandle_block_link_add_item() {
    $("#link_add_btn").addClass('d_none');
    $("#link_add_input").removeClass('d_none');
}

function bandle_block_link_add_item_send(e, id) {
    let val = $(e).val();
    if(val == "") {
        $("#link_add_btn").removeClass('d_none');
        $("#link_add_input").addClass('d_none');
    } else {
        $.ajax({
            url: "/api",
            method: "post",
            dataType: "html",
            data: {_token: TOKEN, Type: 'BandleBlock', func: 'add_social_link', id: id, link: val}
        }).done(function(data){
            console.log(data);
            if(data > 0) {
                bandle_block_load_item_content(id);
                bandle_block_renew_social_link_content(id);
            }
        }).fail(function(data){
            
        });
    }
}

function bandle_block_renew_social_link(element, id, block_id) {
    let value = $(element).val();
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'renew_social_link', link_id: id, value: value}
    }).done(function(data){
        if(data > 0) {
            bandle_block_load_item_content(block_id);
            bandle_block_renew_social_link_content(block_id);
        }
    }).fail(function(data){
        
    });
}

function bandle_block_renew_social_link_content(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'renew_social_link_content', id: id}
    }).done(function(data){
        $("#social_link_content").html(data);
    }).fail(function(data){
        
    });
}

function bandle_block_contact_add_item() {
    $("#contact_add_btn").addClass('d_none');
    $("#contact_add_input").removeClass('d_none');
}

function select(element) {
    let select = $(element);
    let container = select.parent();
    let menu = select.parent().children('.select_menu');
    menu.removeClass('d_none');

    $(window).on('click', function(e) {
        if(!container.is(e.target) && container.has(e.target).length === 0) {
            menu.addClass('d_none');
            $(document).off('click');
        }
    });
}

function select_send(element) {
    let val = $(element).attr('value');
    let html = $(element).html();

    let container = $(element).parent().parent();
    container.find('.select_value').html(html);
    container.find('input').val(val);
    container.find('.select_menu').addClass('d_none');
    
}

function bandle_block_contact_add_item_send(e, id) {
    let val = $(e).val();
    let type = $("#select_add_contact_input").val();
    if(val == "") {
        $("#contact_add_btn").removeClass('d_none');
        $("#contact_add_input").addClass('d_none');
    } else {
        $.ajax({
            url: "/api",
            method: "post",
            dataType: "html",
            data: {_token: TOKEN, Type: 'BandleBlock', func: 'add_contact_item', id: id, value: val, type: type}
        }).done(function(data){
            if(data > 0) {
                bandle_block_load_item_content(id);
                bandle_block_renew_contact_content(id)
            }
        }).fail(function(data){
            
        });
    }
}

function bandle_block_renew_contact(element, id, block_id) {
    let value = $("#contact_"+id).val();
    let type = $("#select_contact_"+id+"_input").val();
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'add_contact_item', contact_id: id, value: value, type: type}
    }).done(function(data){
        if(data > 0) {
            bandle_block_load_item_content(block_id);
            bandle_block_renew_contact_content(block_id)
        }
    }).fail(function(data){
        
    });
}

function bandle_block_renew_contact_content(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'BandleBlock', func: 'renew_contact_content', id: id}
    }).done(function(data){
        $("#contact_block_content").html(data);
    }).fail(function(data){
        
    });
}

function bandle_item_save(id) {
    $.ajax({
        url: "/api",
        method: "post",
        dataType: "html",
        data: {_token: TOKEN, Type: 'Bandle', func: 'item_save', id: id}
    }).done(function(data){
        $("#bandle_action_btn").html(data);
    }).fail(function(data){
        
    });
}

function add_param_to_url(type, value) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set(type, value);
    window.location.search = urlParams;
}


(
    function (factory) {
        if(typeof module === "object" && typeof module.exports === "object") {
            factory(require("jquery"), window, document);
        }
        else {
            factory(jQuery, window, document);
        }
    }
    (
        function($, window, document, undefined) {
  
            var modals = [],
            getCurrent = function() {
            return modals.length ? modals[modals.length - 1] : null;
            },
            selectCurrent = function() {
                var i,
                        selected = false;
                for (i=modals.length-1; i>=0; i--) {
                    if (modals[i].$blocker) {
                        modals[i].$blocker.toggleClass('current',!selected).toggleClass('behind',selected);
                        selected = true;
                    }
                }
            };
    
            $.modal = function(el, option) {
                this.$body = $('body');
                this.el = $(el);

                if(option == 'show') {
                    this.open();
                    modals.push(this);
                } else if(option == 'hide') {
                    this.close();
                    modals.pop();
                }
            };

            $.modal.prototype = {
                constructor: $.modal,

                open:function() {
                    this.el.show();
                    $(document).off('click').on('click', function(event) {
                        let target = event.target;
                        let curent = getCurrent();
                        if($(target).hasClass('hover_modal')) {
                            curent.close();
                        }
                    });

                    $('button[action~="modal:hide"]').off('click').on('click', function() {
                        $.modal.close();
                    });
                },

                close:function() {
                    modals.pop();
                    this.el.hide();
                }
            }
    
    
            $.modal.close = function(event) {
                var current = getCurrent();
                current.close();
                return current.$elm;
            };
    
        
        
            $.fn.modal = function(options){
            if (this.length === 1) {
                new $.modal(this, options);
            }
            return this;
            };
        }
    )
);