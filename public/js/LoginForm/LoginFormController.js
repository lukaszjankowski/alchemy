var controller = (function() {
    'use strict';

    function setView(element) {
        var handler = this.handleSubmit.bind(this);
        element.on('submit', handler);
        this.view = element;
        model.setView(element);
    }

    function setModel(p_model) {
        model = p_model;
    }

    function handleSubmit(e) {
        e.preventDefault();

        if (this.view) {
            var loginInput = this.view.find('input#username');
            var userName = loginInput.val();

            if (!userName) {
                return;
            }

            this.notifyObservers('submitForm', userName);
        }
    }

    return {
        setView : setView,
        setModel : setModel,
        handleSubmit : handleSubmit
    };
}());

var observable = new alchemy.util.Observable();
alchemy.namespace('loginForm').controller = $.extend(true, controller, observable);