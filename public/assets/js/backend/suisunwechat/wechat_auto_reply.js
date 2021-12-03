define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/wechat_auto_reply/index' + location.search,
                    add_url: 'suisunwechat/wechat_auto_reply/add',
                    edit_url: 'suisunwechat/wechat_auto_reply/edit',
                    del_url: 'suisunwechat/wechat_auto_reply/del',
                    multi_url: 'suisunwechat/wechat_auto_reply/multi',
                    table: 'wechat_auto_reply',
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
                        {field: 'keywordcontent', title: __('Keywordcontent')},
                        {field: 'eventkey', title: __('Eventkey')},
                        {field: 'events.name', title: __('响应关键词')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });



            $(".selectkeyword").click(function (e) {
                let input_data = $(this).data('input-id');
                let text_data = $(this).data('text-id');
                Fast.api.open('suisunwechat/source/select', "选择关键词", {
                    callback: (res) => {
                        let item = {
                            'key':res.key,
                            'name':res.name,
                        };
                        $("#"+input_data).val( JSON.stringify(item));
                        $("#"+text_data).text(res.key + "   "+res.name);
                    }
                });
            });


            $(".attentionsave").click(function (e) {
                Fast.api.ajax({
                    url:'suisunwechat/wechat_auto_reply/save_config',
                    loading: true,
                    type: 'POST',
                    data: {
                        rule:{'attention_reply': $("#attention").val()}
                    }
                });
            });
            $(".defaultsave").click(function (e) {
                Fast.api.ajax({
                    url:'suisunwechat/wechat_auto_reply/save_config',
                    loading: true,
                    type: 'POST',
                    data: {
                        rule: {'default_reply': $("#default").val()}
                    }
                });
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