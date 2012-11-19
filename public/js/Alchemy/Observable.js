(function() {
    'use strict';
    
    function Observable() {
        this.attachObserver = function(eventType, observer) {
            if ('object' != alchemy.util.typeOf(observer) || 'function' != alchemy.util.typeOf(observer.update)) {
                throw 'Invalid observer given';
            }

            _observers.call(this, eventType).push(observer);
        };

        this.hasObserver = function(eventType, observer) {
            return alchemy.util.indexOf(observer, _observers.call(this, eventType)) >= 0;
        }

        this.notifyObservers = function(eventType, p_argsObj) {
            if (arguments.length < 1 || arguments.length > 2) {
                throw 'Invalid arguments length';
            } else {
                var argsObj = p_argsObj;
            }

            for ( var i = 0, observers = _observers.call(this, eventType), l = observers.length; i < l; i++) {
                try {
                    observers[i].update(argsObj);
                } catch (e) {
                    console.log(e);
                }
            }
        }

        function _observers(eventType) {
            if (!this.observers) {
                this.observers = {};
            }

            if (!this.observers[eventType]) {
                this.observers[eventType] = [];
            }

            return this.observers[eventType];
        }
    }

    alchemy.namespace('util').Observable = Observable;
}());