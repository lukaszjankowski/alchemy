(function() {
    function setup() {
        this.model = alchemy.loginForm.model;
        this.view = $('<form><input type="text" name="username" id="username" value="" /></form>');
        this.model.setView(this.view);
        this.server = sinon.fakeServer.create();
        this.server.respondWith([
            200,
            {
                "Content-Type" : "application/json"
            },
            '{"ok" : "OK"}'
        ]);
        this.successCallback = sinon.spy();
        this.completeCallback = sinon.spy();
        this.errorCallback = sinon.spy();
        this.beforeSendCallback = sinon.spy();
        this.params = {
            url : '/foo',
            success : this.successCallback,
            complete : this.completeCallback,
            error : this.errorCallback,
            before : this.beforeSendCallback
        }
    }

    function teardown() {
        this.server.restore();
    }


    module('LoginFormModelTest', {
        setup : setup,
        teardown : teardown
    });

    test('can create instance', function() {
        equal(alchemy.util.typeOf(this.model), 'object');
    });

    test('calling checkLoginData adds preloader, blocks form and then does reverse on complete', function() {
        this.model.update(this.params);

        ok(this.view.hasClass('ajax-loading'));
        ok(formIsBlocked(this.view));
        
        this.server.respond();
        
        ok(!this.view.hasClass('ajax-loading'));
        ok(formIsNotBlocked(this.view));
    });
    
    function formIsBlocked(form) {
        var isBlocked = true;
        form.find('input').each(function(index, input) {
            if(isBlocked && !$(input).is(':disabled')) {
                isBlocked = false;
            }
        });
        
        return isBlocked;
    }

    function formIsNotBlocked(form) {
        var isNotBlocked = true;
        form.find('input').each(function(index, input) {
            if(isNotBlocked && $(input).is(':disabled')) {
                isNotBlocked = false;
            }
        });
        
        return isNotBlocked;
    }
    
    // var params = {
    // url : '/foo',
    // data : {
    // userName : 'user',
    // password : 'pass'
    // }
    // };
    // params = $.extend(true, {}, this.params);

}());