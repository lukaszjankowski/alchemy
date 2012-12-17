(function() {

    function loginFormControllerSetUp() {
        this.controller = Object.create(alchemy.loginForm.controller);
        this.viewElement = $('<form><input type="text" name="username" id="username" value="" />'
            + '<input type="text" name="password" id="password" value="" /></form>');
        sinon.spy(this.viewElement, 'click');
        this.model = {
            setView : sinon.spy(),
            update : sinon.spy()
        };
        this.controller.setModel(this.model);
        this.controller.attachObserver('submitForm', this.model);
    }

    function loginFormControllerTearDown() {
        this.viewElement.click.restore();
    }


    module('LoginFormControllerTest');

    test('controller should be an object', function() {
        equal(alchemy.util.typeOf(alchemy.loginForm.controller), 'object');
    });

    test('setView should be a function', function() {
        equal(alchemy.util.typeOf(alchemy.loginForm.controller.setView), 'function');
    });


    module('LoginFormControllerSetViewTest', {
        setup : loginFormControllerSetUp,
        teardown : loginFormControllerTearDown
    });

    test('should correctly set view', function() {
        this.controller.setView(this.viewElement);

        notEqual(this.viewElement, undefined);
        strictEqual(this.controller.view, this.viewElement);
    });

    test('should handle submit event', function() {
        var stub = sinon.stub(this.controller, 'handleSubmit');

        this.controller.setView(this.viewElement);
        this.viewElement.trigger('submit');

        ok(stub.called);
        ok(stub.calledOn(this.controller));
    });


    module('LoginFormControllerHandleSubmitTest', {
        setup : function() {
            loginFormControllerSetUp.call(this);
            this.event = {
                preventDefault : sinon.spy()
            };
            this.controller.setView(this.viewElement);
        },
        teardown : loginFormControllerTearDown
    });

    test('should prevent default action', function() {
        this.controller.handleSubmit(this.event);

        ok(this.event.preventDefault.called);
    });

    test('should pass form values to model', function() {
        var loginInput = this.viewElement.find('input#username').val('lukasz');
        var passwordInput = this.viewElement.find('input#password').val('qw12qw');

        this.controller.handleSubmit(this.event);

        ok(this.model.update.calledWith({
            'username' : loginInput.val(),
            'password' : passwordInput.val()
        }));
    });

    test('should not notify observers if empty login or password given', function() {
        this.controller.attachObserver('submitForm', this.model);

        this.controller.handleSubmit(this.event);

        ok(!this.model.update.called);
    });

}());