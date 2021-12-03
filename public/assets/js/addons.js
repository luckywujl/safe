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