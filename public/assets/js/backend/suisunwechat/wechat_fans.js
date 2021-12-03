define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/wechat_fans/index' + location.search,
                    add_url: 'suisunwechat/wechat_fans/add',
                    edit_url: 'suisunwechat/wechat_fans/edit',
                    del_url: 'suisunwechat/wechat_fans/del',
                    multi_url: 'suisunwechat/wechat_fans/multi',
                    table: 'suisunwechat_wechat_fans',
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
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'headimgurl', title: __('Headimgurl'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'openid', title: __('Openid')},
                        {field: 'tagdata', title: __('Tag')},
                        {field: 'sex', title: __('Sex')},
                        {field: 'country', title: __('Country')},
                        {field: 'province', title: __('Province')},
                        {field: 'city', title: __('City')},
                        {field: 'subscribe', title: __('Subscribe'), searchList: {"0":__('Subscribe 0'),"1":__('Subscribe 1')}, formatter: Table.api.formatter.normal},
                        {field: 'subscribe_time', title: __('Subscribe_time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ],
                queryParams: function (params) {
                    params.tagid = Config.tagid;
                    return params;
                }
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

            $(".btn-sync").click(function () {
                Toastr.info('同步中，请稍候...');
                $.post('suisunwechat/wechat_fans/sync',{},function (res) {
                    if (res.code == 1){
                        Toastr.info('同步完成');
                        table.bootstrapTable('refresh',{});
                    }else{
                        Toastr.error(res.msg);
                    }
                });
            });
            $(".btn-exchange").click(function () {
                var ids = Table.api.selectedids(table);
                if (ids == ''){
                    Toastr.error('请选择用户');
                    return;
                }
                Fast.api.open('suisunwechat/wechat_fans/changetag?ids=' + ids, __('Exchange'),{});
            });
            $(".btn-remove").click(function () {
                var ids = Table.api.selectedids(table);
                if (ids == ''){
                    Toastr.error('请选择用户');
                    return;
                }
                let pindex = layer.open({
                    type: 1,
                    area: ['400px', '150px'],
                    shade: 0,
                    title: __('Remove Tag'),
                    btn: ['确认', '取消'],
                    content: '确认移除所选用户的标签？',
                    yes: function (index, layero) {
                        $.post('suisunwechat/wechat_fans/removetag',{
                            ids: ids,
                            tagid: Config.tagid
                        },function (res) {
                            if (res.code == 1){
                                table.bootstrapTable('refresh',{});
                                Toastr.info('移除成功');
                                layer.close(pindex);
                            }else{
                                Toastr.error(res.msg);
                            }
                        });

                    }
                });
            });
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