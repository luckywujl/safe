define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/base/point/index' + location.search,
                    add_url: 'trouble/base/point/add',
                    edit_url: 'trouble/base/point/edit',
                    del_url: 'trouble/base/point/del',
                    multi_url: 'trouble/base/point/multi',
                    import_url: 'trouble/base/point/import',
                    table: 'trouble_point',
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
                        {field: 'point_code', title: __('Point_code'), operate: 'LIKE'},
                        {field: 'point_name', title: __('Point_name'), operate: 'LIKE'},
                        {field: 'point_description', title: __('Point_description'), operate: 'LIKE'},
                        {field: 'point_address', title: __('Point_address'), operate: 'LIKE'},
                        {field: 'point_position', title: __('Point_position'), operate: 'LIKE'},
                        {field: 'point_department_id', title: __('Point_department_id')},
                        //{field: 'company_id', title: __('Company_id'), operate: 'LIKE'},
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