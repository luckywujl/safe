define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/report/typical/index' + location.search,
                    add_url: 'trouble/report/typical/add',
                    edit_url: 'trouble/report/typical/edit',
                    del_url: 'trouble/report/typical/del',
                    multi_url: 'trouble/report/typical/multi',
                    import_url: 'trouble/report/typical/import',
                    table: 'trouble_main',
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
                        {field: 'main_code', title: __('Main_code'), operate: 'LIKE'},
                        {field: 'point_id', title: __('Point_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'firstduration', title: __('Firstduration'), operate:'BETWEEN'},
                        {field: 'finishduration', title: __('Finishduration'), operate:'BETWEEN'},
                        {field: 'source_type', title: __('Source_type'), searchList: {"0":__('Source_type 0'),"1":__('Source_type 1'),"2":__('Source_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'trouble_type_id', title: __('Trouble_type_id')},
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        {field: 'description', title: __('Description'), operate: 'LIKE'},
                        {field: 'trouble_pic', title: __('Trouble_pic')},
                        {field: 'process_pic', title: __('Process_pic')},
                        {field: 'finish_pic', title: __('Finish_pic')},
                        {field: 'main_status', title: __('Main_status'), searchList: {"0":__('Main_status 0'),"1":__('Main_status 1'),"2":__('Main_status 2'),"3":__('Main_status 3'),"4":__('Main_status 4'),"5":__('Main_status 5'),"6":__('Main_status 6'),"7":__('Main_status 7'),"8":__('Main_status 8'),"9":__('Main_status 9')}, formatter: Table.api.formatter.status},
                        {field: 'informer_name', title: __('Informer_name'), operate: 'LIKE'},
                        {field: 'informer', title: __('Informer'), operate: 'LIKE'},
                        {field: 'recevier', title: __('Recevier'), operate: 'LIKE'},
                        {field: 'liabler', title: __('Liabler'), operate: 'LIKE'},
                        {field: 'processer', title: __('Processer'), operate: 'LIKE'},
                        {field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        {field: 'insider', title: __('Insider'), operate: 'LIKE'},
                        {field: 'together_id', title: __('Together_id')},
                        {field: 'together_code', title: __('Together_code'), operate: 'LIKE'},
                        {field: 'remark', title: __('Remark'), operate: 'LIKE'},
                        {field: 'company_id', title: __('Company_id')},
                        {field: 'troublepoint.id', title: __('Troublepoint.id')},
                        {field: 'troublepoint.point_code', title: __('Troublepoint.point_code'), operate: 'LIKE'},
                        {field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), operate: 'LIKE'},
                        {field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        {field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'troublepoint.point_department_id', title: __('Troublepoint.point_department_id')},
                        {field: 'troublepoint.point_area_id', title: __('Troublepoint.point_area_id')},
                        {field: 'troublepoint.company_id', title: __('Troublepoint.company_id'), operate: 'LIKE'},
                        {field: 'troubletype.id', title: __('Troubletype.id')},
                        {field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        {field: 'troubletype.plan_content', title: __('Troubletype.plan_content'), operate: 'LIKE'},
                        {field: 'troubletype.company_id', title: __('Troubletype.company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        recyclebin: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'trouble/report/typical/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {
                            field: 'deletetime',
                            title: __('Deletetime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            width: '130px',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'trouble/report/typical/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'trouble/report/typical/destroy',
                                    refresh: true
                                }
                            ],
                            formatter: Table.api.formatter.operate
                        }
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