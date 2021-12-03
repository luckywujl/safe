define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/wechat_broadcasting/index' + location.search,
                    add_url: 'suisunwechat/wechat_broadcasting/add',
                    edit_url: 'suisunwechat/wechat_broadcasting/edit',
                    del_url: 'suisunwechat/wechat_broadcasting/del',
                    multi_url: 'suisunwechat/wechat_broadcasting/multi',
                    table: 'suisunwechat_broadcasting',
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
                        {field: 'msgid', title: __('Msgid')},
                        {field: 'type', title: __('Type'), searchList: {"text":__('Type text'),"textandphoto":__('Type textandphoto'),"image":__('Type image'),"video":__('Type video'),"voice":__('Type voice')}, formatter: Table.api.formatter.normal},
                        {field: 'tags', title: __('Tags')},
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
                url: 'suisunwechat/wechat_broadcasting/recyclebin' + location.search,
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
                                    url: 'suisunwechat/wechat_broadcasting/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'suisunwechat/wechat_broadcasting/destroy',
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
            $("input[name='row[target]']").change(function () {
                var type = $(this).val();
                $(".target-item").hide();
                $(".target-"+type).show();
            });
            $(".bt-preivew").click(function () {
                if ($("#c-preview").val() == ''){
                    Toastr.error('请选择预览人');
                    return;
                }
                $.post('suisunwechat/wechat_broadcasting/preview',{
                    id: $("#c-preview").val(),
                    type: $("#c-type").val(),
                    content: $("#c-content").val(),
                    imagetext: $("#c-imagetext").val(),
                    image: $("#c-image").val(),
                    video: $("#c-video").val(),
                    audio: $("#c-audio").val(),
                    article: $("#c-article").val()
                },function (res) {
                    if (res.code == 1){
                        Toastr.info('预览成功');
                    }else{
                        Toastr.error(res.msg);
                    }
                });
            });
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                $("#c-type").change(function (e) {
                    let type = $(this).val();
                    $(".source-item").hide();
                    $(".source-"+type).show();
                });
            }
        }
    };
    return Controller;
});