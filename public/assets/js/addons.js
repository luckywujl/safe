define([], function () {
    require.config({
    paths: {
        'geetest': '../addons/geetest/js/geetest.min'
    }
});

require(['geetest'], function (Geet) {
    var geetInit = false;
    window.renderGeetest = function () {
        $("input[name='captcha']:visible").each(function () {
            var captcha = $(this);
            var form = captcha.closest('form');
            var parentDom = captcha.parent();
            // 非文本验证码
            if ($("a[data-event][data-url]", parentDom).size() > 0) {
                return;
            }
            parentDom
                .removeClass('input-group')
                .html('<div class="embed-captcha"><input type="hidden" name="captcha" class="form-control" data-msg-required="请完成验证码验证" data-rule="required" /> </div> <p class="wait show" style="min-height:44px;line-height:44px;">正在加载验证码...</p>');

            Fast.api.ajax("/addons/geetest/index/start", function (data) {
                // 参数1：配置参数
                // 参数2：回调，回调的第一个参数验证码对象，之后可以使用它做appendTo之类的事件
                initGeetest({
                    gt: data.gt,
                    https: true,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    product: Config.geetest.product, // 产品形式，包括：float，embed，popup。注意只对PC版验证码有效
                    width: '100%',
                    offline: !data.success // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                }, function (captchaObj) {
                    // 将验证码加到id为captcha的元素里，同时会有三个input的值：geetest_challenge, geetest_validate, geetest_seccode
                    geetInit = captchaObj;
                    captchaObj.appendTo($(".embed-captcha", form));
                    captchaObj.onReady(function () {
                        $(".wait", form).remove();
                    });
                    captchaObj.onSuccess(function () {
                        var result = captchaObj.getValidate();
                        if (result) {
                            $('input[name="captcha"]', form).val('ok');
                        }
                    });
                    captchaObj.onError(function () {
                        geetInit.reset();
                    });
                });
                // 监听表单错误事件
                form.on("error.form", function (e, data) {
                    geetInit.reset();
                });
                return false;
            });
        });
    };
    renderGeetest();
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