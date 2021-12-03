define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/source/index' + location.search,
                    add_url: 'suisunwechat/source/add',
                    edit_url: 'suisunwechat/source/edit',
                    del_url: 'suisunwechat/source/del',
                    multi_url: 'suisunwechat/source/multi',
                    table: 'shop_wechat_source',
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
                        {field: 'eventkey', title: __('Eventkey')},
                        {field: 'name', title: __('Name')},
                        {field: 'type', title: __('Type'), searchList: {"text":__('Type text'),"textandphoto":__('Type textandphoto'),'video':__('Type video'),'image':__('Type image'),'audio':__('Type audio')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
                url: 'suisunwechat/source/recyclebin' + location.search,
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
                                    url: 'suisunwechat/source/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'suisunwechat/source/destroy',
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
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/source/index',
                }
            });

            var table = $("#table");
            var idsArr = [];
            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
                if (e.type == 'check' || e.type == 'uncheck') {
                    row = [row];
                } else {
                    idsArr = [];
                }
                $.each(row, function (i, j) {
                    if (e.type.indexOf("uncheck") > -1) {
                        var index = idsArr.indexOf(j.id);
                        if (index > -1) {
                            idsArr.splice(index, 1);
                        }
                    } else {
                        idsArr.indexOf(j.id) == -1 && idsArr.push(j.id);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                showExport: false,
                columns: [
                    [
                        {field: 'state', checkbox: true,},
                        {field: 'id', title: __('Id')},
                        {field: 'eventkey', title: __('Eventkey')},
                        {field: 'name', title: __('Name')},
                        {field: 'type', title: __('Type'), searchList: {"text":__('Type text'),"textandphoto":__('Type textandphoto'),"image":__('Type image'),"video":__('Type video'),"audio":__('Type audio')}, formatter: Table.api.formatter.normal},
                        {field: 'status', title: __('Status'), searchList: {"0":__('Status 0'),"1":__('Status 1')}, formatter: Table.api.formatter.status},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'operate', title: __('Operate'), events: {
                            'click .btn-chooseone': function (e, value, row, index) {
                                var multiple = Backend.api.query('multiple');
                                multiple = multiple == 'true' ? true : false;
                                Fast.api.close({id: row.id,name:row.name,key:row.eventkey, multiple: multiple});
                            },
                        }, formatter: function () {
                            return '<a href="javascript:;" class="btn btn-danger btn-chooseone btn-xs"><i class="fa fa-check"></i> ' + __('Choose') + '</a>';
                        }
                        }
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                // var urlArr = [];
                // $.each(table.bootstrapTable("getAllSelections"), function (i, j) {
                //     urlArr.push(j.url);
                // });
                var multiple = Backend.api.query('multiple');
                multiple = multiple == 'true' ? true : false;
                Fast.api.close({url: idsArr.join(","), multiple: multiple});
            });
            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                $(".typeselete").on('change',function (e) {
                    let type = $(this).val();
                    $(".source-item").hide();
                    $(".source-"+type).show();
                });
            }
        }
    };
    return Controller;
});