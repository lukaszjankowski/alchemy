function setup() {
    this.observer1 = {};
    this.observer1.update = sinon.spy();
    this.observer2 = {};
    this.observer2.update = sinon.spy();
}

module('AddObserverTest', {
    setup : setup
});

test('should store observer', function() {
    var observable = new alchemy.util.Observable();

    observable.attachObserver('event', this.observer1);
    observable.attachObserver('other', this.observer2);

    ok(observable.hasObserver('event', this.observer1));
    ok(observable.hasObserver('other', this.observer2));
});

test('should not accept uncallable observer', function() {
    var observable = new alchemy.util.Observable();

    raises(function() {
        observable.attachObserver();
    });

    raises(function() {
        observable.attachObserver({});
    });

    raises(function() {
        observable.attachObserver('foo');
    });

    raises(function() {
        observable.attachObserver([]);
    });

});


module('NotifyObserversTest', {
    setup : setup
});

test('should notify observers', function() {
    var observable = new alchemy.util.Observable();

    observable.attachObserver('event', this.observer1);
    observable.attachObserver('other', this.observer2);
    observable.notifyObservers('event');

    ok(this.observer1.update.called);
    ok(!this.observer2.update.called);
});

test('should pass through arguments', function() {
    var observable = new alchemy.util.Observable();
    var event = 'testEvent';
    observable.attachObserver(event, this.observer1);
    var argsObj = {
        foo : 'foo'
    }

    observable.notifyObservers(event, argsObj);

    deepEqual(this.observer1.update.args[0][0], argsObj);
});

test('should accept only 1 or 2 arguments', function() {
    var observable = new alchemy.util.Observable();

    raises(function() {
        observable.notifyObservers();
    });

    raises(function() {
        observable.notifyObservers('testEvent', this.observer1, this.observer2);
    });
});

test('should notify all observers even if one throws exception', function() {
    var observable = new alchemy.util.Observable();

    observable.attachObserver('event', this.observer1);
    observable.attachObserver('event', this.observer2);
    observable.notifyObservers('event');

    ok(this.observer2.update.called);
});

test('should call observers in order they were added', function() {
    var observable = new alchemy.util.Observable();

    observable.attachObserver('event', this.observer2);
    observable.attachObserver('event', this.observer1);
    observable.notifyObservers('event');

    ok(this.observer2.update.calledBefore(this.observer1.update));
    ok(this.observer1.update.calledAfter(this.observer2.update));
});
