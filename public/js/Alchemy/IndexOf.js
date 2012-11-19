alchemy.namespace('util').indexOf = function(needle, haystack) {
    if ('array' !== alchemy.util.typeOf(haystack)) {
        return -1;
    }

    for ( var i = 0, l = haystack.length; i < l; i++) {
        if (needle == haystack[i]) {
            return i;
        }
    }

    return -1;
};