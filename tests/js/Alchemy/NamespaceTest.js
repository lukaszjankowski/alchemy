module('NamespaceTest', {
    teardown : function() {
        delete alchemy.nstest;
    }
});

test('should create non-existent object', function() {
    var actual = alchemy.namespace('nstest');

    equal(typeof actual, 'object');
});

test('should not overwrite existing objects', function() {
    alchemy.nstest = {
        nested : {}
    };

    var actual = alchemy.namespace('nstest.nested');

    strictEqual(actual, alchemy.nstest.nested);
});

test('only create missing parts', function() {
    var existing = {};
    alchemy.nstest = {
        nested : {
            existing : existing
        }
    };

    var actual = alchemy.namespace('nstest.nested.ui');

    strictEqual(alchemy.nstest.nested.existing, existing);
    equal(typeof alchemy.nstest.nested.ui, 'object');
});

test('namespacing in other objects', function() {
    var other = {
        namespace : alchemy.namespace
    };

    other.namespace('nstest.nested');

    equal(typeof other.nstest.nested, 'object');
    equal(typeof alchemy.nstest, 'undefined');
});
