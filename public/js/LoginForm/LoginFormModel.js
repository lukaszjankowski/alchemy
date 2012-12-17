var model = (function() {
    'use strict';

    var view;
    
    function update(params) {
        alchemy.ajax({
            url : '/common/authCheck',
            data : {
                username : params.username,
                password : params.password
            },
            beforeSend : beforeSendHandler,
            complete : completeHandler,
            success : successHandler
        });
    }

    function setView(p_view) {
        view = p_view;
    }

    function beforeSendHandler() {
        view.addClass('ajax-loading');
        view.find('input').each(function(index, input) {
           $(input).attr('disabled', 'disabled'); 
        });
    }
    
    function completeHandler() {
        view.removeClass('ajax-loading');
        view.find('input').each(function(index, input) {
            $(input).removeAttr('disabled'); 
        });
    }

    function successHandler(response) {
        //alert(response);
    }
    
    return {
        setView : setView,
        update : update
    };
}());

var observer = new alchemy.util.Observer();
alchemy.namespace('loginForm').model = $.extend(true, model, observer);