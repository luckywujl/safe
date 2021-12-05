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
                        //{field: 'point_department_id', title: __('Point_department_id')},
                        //{field: 'point_area_id', title: __('Point_area_id')},
                        
                        
                        {field: 'troublearea.area_name', title: __('Point_area_id'), operate: false},
                        //{field: 'troublearea.area_description', title: __('Troublearea.area_description'), operate: 'LIKE'},
                        
                        //{field: 'userdepartment.pid', title: __('Userdepartment.pid')},
                        //{field: 'userdepartment.pname', title: __('Userdepartment.pname'), operate: 'LIKE'},
                       // {field: 'userdepartment.type', title: __('Userdepartment.type'), operate: 'LIKE'},
                        {field: 'userdepartment.name', title: __('Point_department_id'), operate: false},
                        //{field: 'userdepartment.icon', title: __('Userdepartment.icon'), operate: 'LIKE', formatter: Table.api.formatter.icon},
                        //{field: 'userdepartment.keywords', title: __('Userdepartment.keywords'), operate: 'LIKE'},
                        //{field: 'userdepartment.description', title: __('Userdepartment.description'), operate: 'LIKE'},
                        //{field: 'userdepartment.diyname', title: __('Userdepartment.diyname'), operate: 'LIKE'},
                        //{field: 'userdepartment.leader', title: __('Userdepartment.leader'), operate: 'LIKE'},
                        //{field: 'userdepartment.person', title: __('Userdepartment.person'), operate: 'LIKE'},
                        //{field: 'userdepartment.createtime', title: __('Userdepartment.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.updatetime', title: __('Userdepartment.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.weigh', title: __('Userdepartment.weigh')},
                        //{field: 'userdepartment.status', title: __('Userdepartment.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        //{field: 'userdepartment.company_id', title: __('Userdepartment.company_id'), operate: 'LIKE'},
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