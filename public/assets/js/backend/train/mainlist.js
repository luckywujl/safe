define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'train/mainlist/index' + location.search,
                    add_url: 'train/mainlist/add',
                    edit_url: 'train/mainlist/edit',
                    del_url: 'train/mainlist/del',
                    multi_url: 'train/mainlist/multi',
                    import_url: 'train/mainlist/import',
                    table: 'training_main',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'training_category_id', title: __('Training_category_id')},
                        {field: 'name', title: __('Name'), operate: 'LIKE'},
                        {field: 'training_course_ids', title: __('Training_course_ids'), operate: 'LIKE'},
                        {field: 'duration', title: __('Duration')},
                        {field: 'coverimage', title: __('Coverimage'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'keywords', title: __('Keywords'), operate: 'LIKE'},
                        {field: 'user_ids', title: __('User_ids'), operate: 'LIKE'},
                        {field: 'user_group_ids', title: __('User_group_ids'), operate: 'LIKE'},
                        {field: 'admin_id', title: __('Admin_id')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), searchList: {"hidden":__('Hidden'),"normal":__('Normal')}, formatter: Table.api.formatter.status},
                        {field: 'company_id', title: __('Company_id')},
                        {field: 'trainingcategory.id', title: __('Trainingcategory.id')},
                        {field: 'trainingcategory.pid', title: __('Trainingcategory.pid')},
                        {field: 'trainingcategory.type', title: __('Trainingcategory.type'), operate: 'LIKE'},
                        {field: 'trainingcategory.name', title: __('Trainingcategory.name'), operate: 'LIKE'},
                        {field: 'trainingcategory.icon', title: __('Trainingcategory.icon'), operate: 'LIKE', formatter: Table.api.formatter.icon},
                        {field: 'trainingcategory.keywords', title: __('Trainingcategory.keywords'), operate: 'LIKE'},
                        {field: 'trainingcategory.description', title: __('Trainingcategory.description'), operate: 'LIKE'},
                        {field: 'trainingcategory.diyname', title: __('Trainingcategory.diyname'), operate: 'LIKE'},
                        {field: 'trainingcategory.createtime', title: __('Trainingcategory.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'trainingcategory.updatetime', title: __('Trainingcategory.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'trainingcategory.weigh', title: __('Trainingcategory.weigh')},
                        {field: 'trainingcategory.status', title: __('Trainingcategory.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        {field: 'trainingcategory.company_id', title: __('Trainingcategory.company_id')},
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
                url: 'train/mainlist/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'name', title: __('Name'), align: 'left'},
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
                                    url: 'train/mainlist/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'train/mainlist/destroy',
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