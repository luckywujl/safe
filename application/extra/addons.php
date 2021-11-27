<?php

return [
    'autoload' => false,
    'hooks' => [
        'view_filter' => [
            'training',
        ],
        'user_sidenav_after' => [
            'training',
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
        '/training/$' => 'training/index/index',
        '/training/main/[:id]' => 'training/index/main',
        '/training/alert/[:msg]' => 'training/index/alert',
        '/training/course/[:main_id]/[:course_id]' => 'training/course/index',
    ],
    'priority' => [],
    'domain' => '',
];
