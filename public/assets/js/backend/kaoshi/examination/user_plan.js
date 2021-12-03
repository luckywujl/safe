define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_plan/index' + location.search,
                    //add_url: 'kaoshi/examination/user_plan/add',
                    //edit_url: 'kaoshi/examination/user_plan/edit',
                    //del_url: 'kaoshi/examination/user_plan/del',
                    //multi_url: 'kaoshi/examination/user_plan/multi',
                    table: 'user_plan',
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
                showColumns: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'user.username', title: __('Username'),operate: 'LIKE'},
                        {field: 'user.nickname', title: __('Usernickname'),operate: 'LIKE'},
                        {field: 'plan.plan_name', title: __('Plan_name'),operate: 'LIKE'},
                        {field: 'exams.exam_name', title: __('exam_name'),operate: 'LIKE'},
                        {field: 'userexams.score', title: __('exam_score'),operate: 'LIKE'},
                        {field: 'plan.type', title: __('type'), searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.normal},
                    ]
                ],
                queryParams:function (params) {
                    if(!Fast.api.query('plan_id')){
                        return params;
                    }  
                    var filter = JSON.parse(params.filter);
                    var op = JSON.parse(params.op);
                                     
                    filter.plan_id = Fast.api.query('plan_id');
                    op.company_id = '=';
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;

                },
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        input: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/user_plan/input' + location.search,
                    //add_url: 'kaoshi/examination/user_plan/add',
                    //edit_url: 'kaoshi/examination/user_plan/edit',
                    //del_url: 'kaoshi/examination/user_plan/del',
                    //multi_url: 'kaoshi/examination/user_plan/multi',
                    table: 'user_plan',
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
                showColumns: false,
                showExport: false,
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'user.username', title: __('Username'),operate: 'LIKE'},
                        {field: 'user.nickname', title: __('Usernickname'),operate: 'LIKE'},
                        {field: 'plan.plan_name', title: __('Plan_name'),operate: 'LIKE'},
                        {field: 'exams.exam_name', title: __('exam_name'),operate: 'LIKE'},
                        {field: 'userexams.score', title: __('exam_score'),operate: 'LIKE'},
                        {field: 'plan.type', title: __('type'), searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.normal},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                
                                {
                                    name: 'input',
                                    text: __('录入成绩'),
                                    title: __('录入成绩'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-group',
                                    url: 'kaoshi/examination/user_exams/input?user_plan_id={id}&nickname={user.nickname}'
                                },
                                
                                ],
                            formatter: Table.api.formatter.buttons
                        },
                    ]
                ],
                queryParams:function (params) {
                    if(!Fast.api.query('plan_id')){
                        return params;
                    }  
                    var filter = JSON.parse(params.filter);
                    var op = JSON.parse(params.op);
                                     
                    filter.plan_id = Fast.api.query('plan_id');
                    op.company_id = '=';
                    params.filter = JSON.stringify(filter);
                    params.op = JSON.stringify(op);
                    return params;

                },
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});