<?php

return [
    'autoload' => false,
    'hooks' => [
        'action_begin' => [
            'geetest',
        ],
        'config_init' => [
            'geetest',
            'third',
        ],
        'response_send' => [
            'loginvideo',
        ],
        'view_filter' => [
            'materials',
            'message',
            'training',
            'trouble',
        ],
        'user_sidenav_after' => [
            'materials',
            'message',
            'training',
            'trouble',
        ],
        'app_init' => [
            'qrcode',
        ],
        'testhook' => [
            'suisunwechat',
        ],
    ],
    'route' => [
        '/kaoshi$' => 'kaoshi/index/index',
        '/kaoshi/logout$' => 'kaoshi/user/logout',
        '/kaoshi/login$' => 'kaoshi/user/login',
        '/kaoshi/changepwd$' => 'kaoshi/user/changepwd',
        '/kaoshi/user$' => 'kaoshi/user/index',
        '/kaoshi/answercard$' => 'kaoshi/exams/answercard',
        '/kaoshi/score$' => 'kaoshi/exams/score',
        '/kaoshi/start$' => 'kaoshi/exams/getquestion',
        '/kaoshi/rank$' => 'kaoshi/exams/rank',
        '/kaoshi/list$' => 'kaoshi/exams/wronglist',
        '/kaoshi/wrong$' => 'kaoshi/exams/getwrong',
        '/kaoshi/study$' => 'kaoshi/user_plan/study',
        '/kaoshi/exam$' => 'kaoshi/user_plan/exam',
        '/kaoshi/studyhistory$' => 'kaoshi/user_plan/studyhistory',
        '/kaoshi/examhistory$' => 'kaoshi/user_plan/examhistory',
        '/materials/$' => 'materials/index/index',
        '/materials/main/[:id]' => 'materials/index/main',
        '/materials/alert/[:msg]' => 'materials/index/alert',
        '/materials/course/[:main_id]/[:course_id]' => 'materials/course/index',
        '/message/$' => 'message/index/index',
        '/message/showmessage/[:id]' => 'message/index/showmessage',
        '/message/alert/[:msg]' => 'message/index/alert',
        '/message/course/[:main_id]/[:course_id]' => 'message/course/index',
        '/qrcode$' => 'qrcode/index/index',
        '/qrcode/build$' => 'qrcode/index/build',
        '/third$' => 'third/index/index',
        '/third/connect/[:platform]' => 'third/index/connect',
        '/third/callback/[:platform]' => 'third/index/callback',
        '/third/bind/[:platform]' => 'third/index/bind',
        '/third/unbind/[:platform]' => 'third/index/unbind',
        '/training/$' => 'training/index/index',
        '/training/main/[:id]' => 'training/index/main',
        '/training/alert/[:msg]' => 'training/index/alert',
        '/training/course/[:main_id]/[:course_id]' => 'training/course/index',
    ],
    'priority' => [],
    'domain' => '',
];
