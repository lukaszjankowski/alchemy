(function() {
    function namespace(string) {
        var object = this;
        var levels = string.split('.');

        for ( var i = 0, l = levels.length; i < l; i++) {
            if ('undefined' === typeof object[levels[i]]) {
                object[levels[i]] = {};
            }
            object = object[levels[i]];
        }

        return object;
    }

    alchemy.namespace = namespace;
}());