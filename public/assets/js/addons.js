define([], function () {
    require.config({
    paths: {
        'video': '../addons/training/video/video.min',
        'panelslider': '../addons/training/panelslider/jquery.panelslider.min',
        'fullcalendar': '../addons/training/fullcalendar/main.min',
        'fullcalendar-lang': '../addons/training/fullcalendar/locales-all.min',
        'mTips': '../addons/training/mTips/mTips',
        'zTree': '../addons/training/ztree/js/jquery.ztree.core.min',
        'zTree-awesome': '../addons/training/ztree/js/ztree-awesome'
    },
    shim: {
        'video': {
            deps: ["css!../addons/training/video/video-js.min.css"]
        },
        'panelslider': {
            deps: ['jquery']
        },
        'fullcalendar': {
            deps: ['jquery', 'moment', "css!../addons/training/fullcalendar/main.min.css"]
        },
        'fullcalendar-lang': {
            deps: ['fullcalendar']
        },
        'mTips': {
            deps: ["css!../addons/training/mTips/mTips.css"]
        },
        'zTree': {
            deps: ['jquery', "css!../addons/training/ztree/css/zTreeStyle/zTreeStyle.css"]
        },
        'zTree-awesome': {
            deps: ['zTree', "css!../addons/training/ztree/css/zTree.css"]
        },
    }
});
define('global/window', [], function () {
    return window;
});

define('global/document', ['global/window'], function (window) {
    return window.document;
});

require.config({
    paths: {
        'video': '../addons/training/video/video.min',
        'panelslider': '../addons/training/panelslider/jquery.panelslider.min',
        'fullcalendar': '../addons/training/fullcalendar/main.min',
        'fullcalendar-lang': '../addons/training/fullcalendar/locales-all.min',
        'mTips': '../addons/training/mTips/mTips',
        'zTree': '../addons/training/ztree/js/jquery.ztree.core.min',
        'zTree-awesome': '../addons/training/ztree/js/ztree-awesome'
    },
    shim: {
        'video': {
            deps: ["css!../addons/training/video/video-js.min.css"]
        },
        'panelslider': {
            deps: ['jquery']
        },
        'fullcalendar': {
            deps: ['jquery', 'moment', "css!../addons/training/fullcalendar/main.min.css"]
        },
        'fullcalendar-lang': {
            deps: ['fullcalendar']
        },
        'mTips': {
            deps: ["css!../addons/training/mTips/mTips.css"]
        },
        'zTree': {
            deps: ['jquery', "css!../addons/training/ztree/css/zTreeStyle/zTreeStyle.css"]
        },
        'zTree-awesome': {
            deps: ['zTree', "css!../addons/training/ztree/css/zTree.css"]
        },
    }
});
define('global/window', [], function () {
    return window;
});

define('global/document', ['global/window'], function (window) {
    return window.document;
});

if (Config.modulename === 'index' && Config.controllername === 'user' && ['login', 'register'].indexOf(Config.actionname) > -1 && $("#register-form,#login-form").size() > 0) {
    $('<style>.social-login{display:flex}.social-login a{flex:1;margin:0 2px;}.social-login a:first-child{margin-left:0;}.social-login a:last-child{margin-right:0;}</style>').appendTo("head");
    $("#register-form,#login-form").append('<div class="form-group social-login"></div>');
    if (Config.third.status.indexOf("wechat") > -1) {
        $('<a class="btn btn-success" href="' + Fast.api.fixurl('/third/connect/wechat') + '"><i class="fa fa-wechat"></i> 微信登录</a>').appendTo(".social-login");
    }
    if (Config.third.status.indexOf("qq") > -1) {
        $('<a class="btn btn-info" href="' + Fast.api.fixurl('/third/connect/qq') + '"><i class="fa fa-qq"></i> QQ登录</a>').appendTo(".social-login");
    }
    if (Config.third.status.indexOf("weibo") > -1) {
        $('<a class="btn btn-danger" href="' + Fast.api.fixurl('/third/connect/weibo') + '"><i class="fa fa-weibo"></i> 微博登录</a>').appendTo(".social-login");
    }
}

require.config({
    paths: {
        'video': '../addons/training/video/video.min',
        'panelslider': '../addons/training/panelslider/jquery.panelslider.min',
        'fullcalendar': '../addons/training/fullcalendar/main.min',
        'fullcalendar-lang': '../addons/training/fullcalendar/locales-all.min',
        'mTips': '../addons/training/mTips/mTips',
        'zTree': '../addons/training/ztree/js/jquery.ztree.core.min',
        'zTree-awesome': '../addons/training/ztree/js/ztree-awesome'
    },
    shim: {
        'video': {
            deps: ["css!../addons/training/video/video-js.min.css"]
        },
        'panelslider': {
            deps: ['jquery']
        },
        'fullcalendar': {
            deps: ['jquery', 'moment', "css!../addons/training/fullcalendar/main.min.css"]
        },
        'fullcalendar-lang': {
            deps: ['fullcalendar']
        },
        'mTips': {
            deps: ["css!../addons/training/mTips/mTips.css"]
        },
        'zTree': {
            deps: ['jquery', "css!../addons/training/ztree/css/zTreeStyle/zTreeStyle.css"]
        },
        'zTree-awesome': {
            deps: ['zTree', "css!../addons/training/ztree/css/zTree.css"]
        },
    }
});
define('global/window', [], function () {
    return window;
});

define('global/document', ['global/window'], function (window) {
    return window.document;
});

require.config({
    paths: {
        'video': '../addons/training/video/video.min',
        'panelslider': '../addons/training/panelslider/jquery.panelslider.min',
        'fullcalendar': '../addons/training/fullcalendar/main.min',
        'fullcalendar-lang': '../addons/training/fullcalendar/locales-all.min',
        'mTips': '../addons/training/mTips/mTips',
        'zTree': '../addons/training/ztree/js/jquery.ztree.core.min',
        'zTree-awesome': '../addons/training/ztree/js/ztree-awesome'
    },
    shim: {
        'video': {
            deps: ["css!../addons/training/video/video-js.min.css"]
        },
        'panelslider': {
            deps: ['jquery']
        },
        'fullcalendar': {
            deps: ['jquery', 'moment', "css!../addons/training/fullcalendar/main.min.css"]
        },
        'fullcalendar-lang': {
            deps: ['fullcalendar']
        },
        'mTips': {
            deps: ["css!../addons/training/mTips/mTips.css"]
        },
        'zTree': {
            deps: ['jquery', "css!../addons/training/ztree/css/zTreeStyle/zTreeStyle.css"]
        },
        'zTree-awesome': {
            deps: ['zTree', "css!../addons/training/ztree/css/zTree.css"]
        },
    }
});
define('global/window', [], function () {
    return window;
});

define('global/document', ['global/window'], function (window) {
    return window.document;
});

});