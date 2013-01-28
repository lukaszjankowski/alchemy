(function() {
    function setup() {
        this.model = alchemy.loginForm.model;
        this.view = $('<form><input type="text" name="username" id="username" value="" />'
            + '<input type="text" name="password" id="password" value="" /></form>');
        this.model.setView(this.view);
        this.server = sinon.fakeServer.create();
        this.server.respondWith([
            200,
            {
                "Content-Type" : "application/json"
            },
            '{"error" : "", "result" : { "result" : { "ok" : "OK"} } }'
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
        this.model.update({});

        ok(this.view.hasClass('ajax-loading'));
        ok(formIsBlocked(this.view));

        this.server.respond();

        ok(!this.view.hasClass('ajax-loading'));
        ok(!formIsBlocked(this.view));
    });

    function formIsBlocked(form) {
        var isBlocked = true;
        form.find('input').each(function(index, input) {
            if (isBlocked && !$(input).is(':disabled')) {
                isBlocked = false;
            }
        });

        return isBlocked;
    }

    test('validation error is set in view when invalid credentials provided and removed' + ' when correct provided',
        function() {
            this.server.respondWith([
                200,
                {
                    "Content-Type" : "application/json"
                },
                '{"error" : "", "result" : { "result" : "RESULT_NOT_OK" } }'
            ]);

            this.model.update({});
            this.server.respond();

            equal(this.view.find('span.validationError').size(), 1);

            this.server.respondWith([
                200,
                {
                    "Content-Type" : "application/json"
                },
                '{"error" : "", "result" : { "result" : "RESULT_OK" } }'
            ]);

            this.model.update({});
            this.server.respond();

            equal(this.view.find('span.validationError').size(), 0);
        });

}());