(function($) {
    'use strict';

    var defaultCfg = {
        type : 'POST',
        dataType : 'json'
    };

    function ajax(p_cfg) {
        checkUserConfig(p_cfg);

        var cfg = extendDefaultCfg(p_cfg);
        $.ajax(cfg);
    }

    function checkUserConfig(cfg) {
        if (!cfg || 'object' != alchemy.util.typeOf(cfg)) {
            throw 'Invalid config';
        }
        if (!cfg.url) {
            throw 'Missing "url" config param';
        }
    }

    function extendDefaultCfg(cfg) {
        var result = $.extend(true, {}, defaultCfg, cfg);

        result.success = defaultOnSuccess(cfg);
        result.error = defaultOnError(cfg);
        result.complete = defaultOnComplete(cfg);

        return result;
    }

    function defaultOnSuccess(cfg) {
        if (cfg.defaultOnSuccess) {
            return function(response, textStatus, jqXHR) {
                cfg.defaultOnSuccess(response, textStatus, jqXHR);
            }
        }

        return function(response, textStatus, jqXHR) {
            if (response.error) {
                alert(response.error);
                return;
            }
            if (cfg.success) {
                cfg.success(response, textStatus, jqXHR);
            }
        }
    }

    function defaultOnError(cfg) {
        if (cfg.defaultOnError) {
            return function(jqXHR, textStatus, errorThrown) {
                cfg.defaultOnError(jqXHR, textStatus, errorThrown);
            }
        }

        return function(jqXHR, textStatus, errorThrown) {
            if (cfg.error) {
                cfg.error(jqXHR, textStatus, errorThrown);
            }
        }
    }

    function defaultOnComplete(cfg) {
        if (cfg.defaultOnComplete) {
            return function(jqXHR, textStatus) {
                cfg.defaultOnComplete(jqXHR, textStatus);
            }
        }

        return function(jqXHR, textStatus) {
            if (cfg.complete) {
                cfg.complete(jqXHR, textStatus);
            }
        }
    }

    alchemy.ajax = ajax;
}(jQuery));