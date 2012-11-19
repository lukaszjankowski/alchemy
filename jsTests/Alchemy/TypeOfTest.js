module('TypeofTest');

test('typeOf test', function() {
    equal(alchemy.util.typeOf([]), 'array');
    equal(alchemy.util.typeOf(null), 'null');
});
