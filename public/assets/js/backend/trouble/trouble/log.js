define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/trouble/log/index' + location.search,
                    add_url: 'trouble/trouble/log/add',
                    edit_url: 'trouble/trouble/log/edit',
                    del_url: 'trouble/trouble/log/del',
                    multi_url: 'trouble/trouble/log/multi',
                    import_url: 'trouble/trouble/log/import',
                    table: 'trouble_log',
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
                        {field: 'id', title: __('Id')},
                        {field: 'main_id', title: __('Main_id')},
                        {field: 'log_time', title: __('Log_time'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'log_operator', title: __('Log_operator'), operate: 'LIKE'},
                        {field: 'log_content', title: __('Log_content'), operate: 'LIKE'},
                        {field: 'company_id', title: __('Company_id')},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});