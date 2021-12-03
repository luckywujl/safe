define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/kaoshirecord/index' + location.search,
                    add_url: 'kaoshi/examination/kaoshirecord/add',
                    edit_url: 'kaoshi/examination/kaoshirecord/edit',
                    del_url: 'kaoshi/examination/kaoshirecord/del',
                    multi_url: 'kaoshi/examination/kaoshirecord/multi',
                    import_url: 'kaoshi/examination/kaoshirecord/import',
                    table: 'kaoshi_user_plan',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        //{field: 'user_id', title: __('User_id')},
                        //{field: 'plan_id', title: __('Plan_id')},
                        {field: 'kaoshiplan.plan_name', title: __('Kaoshiplan.plan_name'), operate: 'LIKE'},
                        {field: 'kaoshiuserexams.score', title: __('Kaoshiuserexams.score')},
                        
                        {field: 'kaoshiuserexams.starttime', title: __('Kaoshiuserexams.starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        //{field: 'kaoshiuserexams.usetime', title: __('Kaoshiuserexams.usetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'kaoshiuserexams.id', title: __('Kaoshiuserexams.id')},
                        //{field: 'kaoshiuserexams.user_plan_id', title: __('Kaoshiuserexams.user_plan_id')},
                        //{field: 'kaoshiuserexams.scorelistdata', title: __('Kaoshiuserexams.scorelistdata'), operate: 'LIKE'},
                        
                      
                        
                        
                        //{field: 'kaoshiuserexams.lasttime', title: __('Kaoshiuserexams.lasttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                       // {field: 'kaoshiuserexams.company_id', title: __('Kaoshiuserexams.company_id'), operate: 'LIKE'},
                        //{field: 'kaoshiplan.id', title: __('Kaoshiplan.id')},
                        //{field: 'kaoshiplan.subject_id', title: __('Kaoshiplan.subject_id')},
                        //{field: 'kaoshiplan.exam_id', title: __('Kaoshiplan.exam_id')},
                        //{field: 'kaoshiplan.plan_name', title: __('Kaoshiplan.plan_name'), operate: 'LIKE'},
                       // {field: 'kaoshiplan.type', title: __('Kaoshiplan.type')},
                        //{field: 'kaoshiplan.hours', title: __('Kaoshiplan.hours')},
                        //{field: 'kaoshiplan.times', title: __('Kaoshiplan.times')},
                        //{field: 'kaoshiplan.starttime', title: __('Kaoshiplan.starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'kaoshiplan.endtime', title: __('Kaoshiplan.endtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'kaoshiplan.createtime', title: __('Kaoshiplan.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'kaoshiplan.updatetime', title: __('Kaoshiplan.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'kaoshiplan.deletetime', title: __('Kaoshiplan.deletetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'kaoshiplan.limit', title: __('Kaoshiplan.limit')},
                        //{field: 'kaoshiplan.company_id', title: __('Kaoshiplan.company_id'), operate: 'LIKE'},
                       // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});