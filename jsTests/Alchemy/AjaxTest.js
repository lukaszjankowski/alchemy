module('AjaxTest');

test('object exists', function() {
    equal(typeof alchemy.ajax, 'function');
});

test('throws error when no crucial config parameters given', function() {
    raises(function() {
        alchemy.ajax();
    }, function(e) {
        return e == 'Invalid config';
    });

    raises(function() {
        var cfg = {};
        alchemy.ajax(cfg);
    }, function(e) {
        return e == 'Missing "url" config param';
    })
});


function setup() {
    this.server = sinon.fakeServer.create();
}

function teardown() {
    this.server.restore();
}

module('sending requests tests', {
    setup : setup,
    teardown : teardown
});

test('sends request with default params', function() {
    var dataObj = {
        bar : 'foobar',
        baz : 'foobaz'
    };
    var cfg = {
        url : '/foo',
        data : dataObj
    };

    alchemy.ajax(cfg);
    this.server.respond();

    equal(this.server.requests[0].method, 'POST');
    equal(this.server.requests[0].async, true);
    equal(this.server.requests[0].requestHeaders['Content-Type'], "application/x-www-form-urlencoded;charset=utf-8");
    equal(this.server.requests[0].requestHeaders['Accept'], "application/json, text/javascript, */*; q=0.01");
    equal(this.server.requests[0].requestBody, $.param(cfg.data));

});

test('sends request with default params overriden', function() {
    var dataObj = {
        bar : 'foobar',
        baz : 'foobaz'
    };
    var cfg = {
        url : '/foo',
        type : 'GET',
        async : false,
        dataType : 'html',
        data : dataObj
    };

    alchemy.ajax(cfg);

    equal(this.server.requests[0].method, 'GET');
    equal(this.server.requests[0].async, false);
    equal(this.server.requests[0].requestHeaders['Accept'], "text/html, */*; q=0.01");
    equal(this.server.requests[0].url, '/foo?' + $.param(cfg.data));
});


module('response tests', {
    setup : function() {
      setup.call(this);
      this.clock = sinon.useFakeTimers();
    },
    teardown : function() {
        this.clock.restore();
    }
});

test('callbacks called on success request', function() {
    var successSpy = sinon.spy();
    var completeSpy = sinon.spy();
    var errorSpy = sinon.spy();
    var cfg = {
        url : '/foo',
        success : successSpy,
        complete : completeSpy,
        error : errorSpy
    };

    alchemy.ajax(cfg);
    this.server.respondWith([
        200,
        {},
        '{"ok" : "OK"}'
    ]);
    this.server.respond();

    ok(completeSpy.called);
    ok(successSpy.called);
    ok(!errorSpy.called);
    ok(successSpy.calledWith({
        ok : "OK"
    }));
});

test('callbacks called on error request', function() {
    var successSpy = sinon.spy();
    var completeSpy = sinon.spy();
    var errorSpy = sinon.spy();
    var cfg = {
        url : '/foo',
        success : successSpy,
        complete : completeSpy,
        error : errorSpy
    };

    alchemy.ajax(cfg);
    this.server.respondWith([
        400,
        {},
        ''
    ]);
    this.server.respond();

    ok(completeSpy.called);
    ok(!successSpy.called);
    ok(errorSpy.called);
});

test('timeout', function() {
    this.server.respondWith([
        200,
        {},
        ''
    ]);
    var successSpy = sinon.spy();
    var completeSpy = sinon.spy();
    var errorSpy = sinon.spy();
    var cfg = {
        timeout : 10000,
        url : '/foo',
        success : successSpy,
        complete : completeSpy,
        error : errorSpy
    };

    alchemy.ajax(cfg);

    this.clock.tick(9995);
    ok(!successSpy.called);
    ok(!completeSpy.called);
    ok(!errorSpy.called);
    equal(this.server.requests[0].readyState, 1);

    this.clock.tick(10005);
    this.server.respond();

    ok(completeSpy.called);
    ok(!successSpy.called);
    ok(errorSpy.called);
    equal(this.server.requests[0].readyState, 0);
});
