$(document).ready(function() {

    function loginFormControllerSetUp() {
        this.controller = Object.create(alchemy.loginForm.controller);
        this.viewElement = $('<form><input type="text" name="username" id="username" value="" /></form>');
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

    test('setView is function', function() {
        equal(alchemy.util.typeOf(alchemy.loginForm.controller.setView), 'function');
    });


    module('LoginFormControllerSetViewTest', {
        setup : loginFormControllerSetUp,
        teardown : loginFormControllerTearDown
    });

    test('setting view', function() {
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

    test('should add username to model', function() {
        this.loginInput = this.viewElement.find('input#username').val('lukasz');

        this.controller.handleSubmit(this.event);

        ok(this.model.update.calledWith('lukasz'));
    });

    test('should notify observers', function() {
        this.controller.attachObserver('submitForm', this.model);
        this.loginInput = this.viewElement.find('input#username').val('lukasz');

        this.controller.handleSubmit(this.event);

        ok(this.model.update.called);
        ok(this.model.update.args[0][0], this.loginInput.val());
    });

    test('should not notify observers if empty login given', function() {
        this.controller.attachObserver('submitForm', this.model);

        this.controller.handleSubmit(this.event);

        ok(!this.model.update.called);
    });

}());