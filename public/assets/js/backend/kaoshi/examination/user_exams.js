define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/index' + location.search,
                    add_url: 'kaoshi/examination/user_exams/add',
                    edit_url: 'kaoshi/examination/user_exams/edit',
                    del_url: 'kaoshi/examination/user_exams/del',
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'score', title: __('Score')},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'lasttime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'answercard',
                                    text: __('答题卡'),
                                    title: __('答题卡'),
                                    classname: 'btn btn-xs btn-primary btn-addtabs',
                                    icon: 'fa fa-file-text-o',
                                    url: 'kaoshi/examination/user_exams/answercard'
                                },
                            ],
                        }
                    ]
                ],
                queryParams:function (params) {
                    if(!Fast.api.query('ids')){
                        return params;
                    }
                    var filter = JSON.parse(params.filter);
                    var op = JSON.parse(params.op);

                    filter.user_plan_id = Fast.api.query('ids');
                    op.user_plan_id = '=';
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;

                },
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        users: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/users/ids/'+Fast.api.query('ids') + location.search,
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                
                columns: [
                    [
                        {field: 'id', title: __('Id')},
                        {field: 'nickname', title: __('nickname')},
                        {field: 'status', title: __('status'), searchList: {"0":__('status 0'),"1":__('status 1'),"":__('未开始')}, formatter: Table.api.formatter.status},
                        {field: 'score', title: __('maxscore')},
                        {field: 'times', title: __('times')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'lasttime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'list',
                                    text: __('答题记录'),
                                    title: __('答题记录'),
                                    classname: 'btn btn-xs btn-primary btn-addtabs',
                                    icon: 'fa fa-list',
                                    url: 'kaoshi/examination/user_exams/index'
                                },
                            ],
                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        studyrank: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/studyrank' + location.search,
                    add_url: 'kaoshi/examination/user_exams/add',
                    edit_url: 'kaoshi/examination/user_exams/edit',
                    del_url: 'kaoshi/examination/user_exams/del',
                    multi_url: 'kaoshi/examination/user_exams/multi',
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'nickname', title: __('nickname')},
                        {field: 'score', title: __('score')},
                        {field: 'ranking', title: __('ranking')},
                        
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        examrank: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_exams/examrank' + location.search,
                    table: 'user_exams',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                search:false,
                showToggle: false,
                showExport: false,
                commonSearch: false,
                columns: [
                    [
                        {field: 'nickname', title: __('nickname')},
                        {field: 'score', title: __('score')},
                        {field: 'ranking', title: __('ranking')},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        answercard: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});