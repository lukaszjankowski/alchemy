module('IndexOfTest');

test('should return -1 if haystack is not an array', function() {
    equal(alchemy.util.indexOf('foo', {}), -1);
});

test('should return -1 if needle not found in haystack', function() {
    equal(alchemy.util.indexOf('foo', []), -1);
});

test('should return correct offset if needle is found in haystack', function() {
    equal(alchemy.util.indexOf('foo', [
        'foo'
    ]), 0);
    equal(alchemy.util.indexOf('bar', [
        'foo',
        'bar',
        'baz'
    ]), 1);
});