(function(window) {
    'use strict';
    var
        debug = false,
        baseName = 'EnlipleBackAd',
        makeMethod = 'make',

        Popunder = function(url,gubun,s,userid) {
            this.__construct(url,gubun,s,userid);
        };
    Popunder.prototype = {
        __construct: function(url,gubun,s,userid) {
            this.url      = url;
            this.s        = s;
            this.gubun    = gubun;
            this.userid    = userid;
            this.register(this.url ,this.s,this.gubun,this.userid);
        },
        register: function(adUrl ,s , gubun,userid){

            if(adUrl != undefined){
                adUrl = adUrl.replace(/&amp;/g ,"&" );
            }
            if(location.href.indexOf("#_enliple") == -1 || location.hash.indexOf("#_enliple") == -1) {
                history.pushState(null, document.title, location.href);
                history.replaceState(null, document.title, location.href + "#_enliple");
            }
            window.addEventListener('hashchange', function(e){
                if(document.URL.indexOf("#_enliple") != -1){
                }else{
                    setTimeout(function(){
                        location.replace(adUrl);
                    },25);
                }
            });
        }
    };
    Popunder[makeMethod] = function(url ,gubun ,s,userid) {
        return new this(url ,gubun,s,userid);
    };
    window[baseName] = Popunder;
})(this);
        		