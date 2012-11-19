(function($) {
    'use strict';
    
    var defaultCfg = {
        type : 'POST',
        dataType : 'json'
    };
    
    function ajax(p_cfg) {
        if(!p_cfg || 'object' != alchemy.util.typeOf(p_cfg)) {
            throw 'Invalid config';
        }
        if(!p_cfg.url) {
            throw 'Missing "url" config param';
        }
        
        var cfg = extendDefaultCfg(p_cfg);
        $.ajax(cfg);
    }
    
    function extendDefaultCfg(cfg) {
        return $.extend(true, {}, defaultCfg, cfg);
    }
    
    alchemy.ajax= ajax;
}(jQuery));