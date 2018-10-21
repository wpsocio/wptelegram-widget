(function( $, I18n ) {
	'use strict';

	var app1 = {};
    app1.configure = function(){
        app1.metabox = $( '#cmb2-metabox-wptelegram_widget' );
        app1.bot_token = app1.metabox.find('#bot_token');
        app1.username = app1.metabox.find('#username');
    };
    app1.init = function () {
        app1.configure();
        app1.metabox.on( 'blur', '#bot_token,#username', app1.handle_blur );
        app1.metabox.on( 'click', '#button-bot_token,#button-username', app1.send_test );
    };
    app1.send_test = function( evt, params ){
        if ( !app1.bot_token.val().trim() || !app1.validate('bot_token') ) {
            alert(I18n.empty_bot_token);
            return;
        }

        var id = $(this).attr('data-id');

        if ( 'username' == id  ) {
            if (!app1.username.val().trim()) {
                alert(I18n.empty_username);
                return;
            }else if (!app1.validate('username')){
                return;
            }
        } 

    	var val = app1[id].val().replace(/[\s@]/g,'');
    	var regex;
    	switch (id) {
		    case 'bot_token':
		        app1.test_bot_token(val);
		        break;
		    case 'username':
			    var token = app1.bot_token.val();
		        app1.test_username('@'+val,token);
		        break;
		}
    };
    app1.test_bot_token = function( bot_token ){
    	app1.send_ajax_request( bot_token, '/getMe', {}, app1.handle_bot_token_test );
    };
    app1.test_username = function( username, bot_token ){
        
    	var new_text = prompt(I18n.test_message_prompt,I18n.test_message_text);
    	if (null == new_text)
    		return;
    	app1.send_ajax_request( bot_token, '/sendMessage', {chat_id:username,text:new_text}, app1.handle_username_test );
    };
    app1.send_ajax_request = function( bot_token, endpoint, data, response_handler ){
    	var url = 'https://api.telegram.org/bot' + bot_token + endpoint;
        $.ajax({
            type: "POST",
            contentType: "application/json; charset=utf-8",
            url: url,
            dataType: "json",
            crossDomain:true,
            data: JSON.stringify( data ),
            complete: response_handler
        });
    };
    app1.handle_blur = function ( evt ) {
        app1.metabox.find('.info').addClass("hidden").siblings().remove();
    	var id = $(this).attr('id');
        var val = app1[id].val();
    	if ( '' != val && app1.validate(id) && 'username' == id ) {
    		app1.get_chat_member_count('@'+val);
    	}
    };
    app1.get_chat_member_count = function (username) {
    	app1.send_ajax_request( app1.bot_token.val(), '/getChatMembersCount', {chat_id:username}, app1.handle_chat_member_count );
    };
    app1.handle_username_test = function( jqXHR, textStatus ) {

        app1.metabox.find("#username-chat-table").removeClass("hidden");

        var col1 = '<td>' + app1.username.val() + '</td>';

        if ( undefined == jqXHR || '' == jqXHR.responseText ) {
            col2 = '<td colspan="3"><span style="color:#f10e0e;">'+I18n.error+' '+I18n.could_not_connect+'</span></td>';
            var col3 = col4 = '';
        }else if ( true == JSON.parse( jqXHR.responseText ).ok ){
            var result = JSON.parse(jqXHR.responseText).result;
            var title = result.chat.title;
            var col2 = '<td>' + title + '</td>';
            var col3 = '<td>' + result.chat.type + '</td>';
            var col4 = '<td>'+I18n.success+'</td>';
        }else{
            var col2 = '<td>' + JSON.parse( jqXHR.responseText ).description + '</td>';
            var col3 = '<td>' + I18n.error + ' ' + jqXHR.status + '</td>';
            var col4 = '<td>' + I18n.failure + '</td>';
        }
        var tr = '<tr class="wptelegram-widget-temp-rows">'
                + col1
                + col2
                + col3
                + col4
                + '</tr>';
        app1.metabox.find('#username-chat-table tbody').append(tr);
    };
    app1.handle_bot_token_test = function( jqXHR, textStatus ) {
        var elem = app1.metabox.find('#bot_token-info');
        elem.removeClass("hidden");

        if ( undefined == jqXHR  || '' == jqXHR.responseText ) {
            elem.text('');
            elem.append('<span>'+I18n.error+' '+I18n.could_not_connect+'</span>');
        }else if ( true == JSON.parse( jqXHR.responseText ).ok ){
            var result = JSON.parse( jqXHR.responseText ).result;
            
            elem.text( result.first_name + ' ' + ( undefined == result.last_name ? ' ' :  result.last_name ) + '(@' + result.username + ')' );
        }else{
            elem.text(I18n.error + ' ' + jqXHR.status + ' (' + jqXHR.statusText + ')');
        }
    };
    app1.handle_chat_member_count = function ( jqXHR, textStatus ) {
    	if ( undefined == jqXHR || '' == jqXHR.responseText ) {
            return;
        }
        var elem = app1.metabox.find("#username-info");
        elem.removeClass("hidden");
        elem.siblings().remove();
        var info;
        if ( true == JSON.parse( jqXHR.responseText ).ok ){
            var result = JSON.parse( jqXHR.responseText ).result;
            
            info = ' <b style="color:#bb0f3b;">' + result + '</b>';
        }else{
            elem.addClass("hidden");
            info = ' <b style="color:#f10e0e;">' + I18n.error + ' ' + jqXHR.status + ' (' + JSON.parse( jqXHR.responseText ).description + ')' + '</b>';
        }
        elem.parent().append(info);
    };
    app1.validate = function( id ){
		var val = app1.metabox.find('#'+id).val().replace(/[\s@]/g,'');
		app1.metabox.find('#'+id).val(val);
    	var regex;
    	switch (id) {
		    case 'bot_token':
		        regex = new RegExp(/^\d{9}:[\w-]{35}$/);
		        break;
		    case 'username':
		        regex = new RegExp(/^[a-z]\w{3,30}[^\W_]$/i);
		        break;
		}
		if ( regex.test( val ) ) {
	        app1.metabox.find('#'+id+'-err').addClass("hidden");
	        return true;
	    } else {
	        app1.metabox.find('#'+id+'-err').removeClass("hidden");
	        return false;
	    }
    };

    var app2 = {};
    app2.configure = function(){
        app2.metabox = $( '#wptelegram_widget_messages' );
        app2.post_url = app2.metabox.find('#post_url');
        app2.num_messages = app2.metabox.find('#num_messages');
        app2.submit = app2.metabox.find('#submit-pull');
    };
    app2.init = function () {
        app2.configure();
        app2.submit.click( app2.pull_messages );
    };
    app2.pull_messages = function(e){
        e.preventDefault();

        var data = {
            action: 'wptelegram_widget_pull_messages',
            nonce: app2.metabox.find('#ajax_nonce').val(),
            post_url: app2.post_url.val(),
            num_messages: app2.num_messages.val()
        };
        app2.send_ajax_request( data, app2.handle_ajax_response );
    };
    app2.send_ajax_request = function( data, response_handler ){
        $.ajax({
            contentType: "application/json; charset=utf-8",
            url: ajaxurl,
            dataType: 'JSON',
            data: data,
            complete: response_handler,
            success: function() {
                window.setTimeout(function(){location.reload()},2000)
            }
        });
    };
    app2.handle_ajax_response = function(jqXHR){
        app2.submit.siblings().remove();
        var msg;
        if ( undefined == jqXHR  || '' == jqXHR.responseText ) {
            msg = I18n.could_not_connect;
        } else if ( undefined != JSON.parse( jqXHR.responseText ).data ){
            msg = JSON.parse( jqXHR.responseText ).data;
        }else{
            msg = jqXHR.statusText;
        }
        app2.submit.parent().append('&nbsp;<span>'+msg+'</span>');
    }

    $( document ).ready( app1.init );
    $( document ).ready( app2.init );
})( jQuery, wptelegram_widget_I18n );
