define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/base/expression/index' + location.search,
                    add_url: 'trouble/base/expression/add',
                    edit_url: 'trouble/base/expression/edit',
                    del_url: 'trouble/base/expression/del',
                    multi_url: 'trouble/base/expression/multi',
                    import_url: 'trouble/base/expression/import',
                    table: 'trouble_expression',
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
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        getexpression: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/base/expression/index' + location.search,
                    
                    table: 'trouble_expression',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                singleSelect: true,
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id'), operate: 'LIKE'},
                     //   {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
             //保存
        	 $(document).on("click",".btn-save",function () {
        	 	var  temp=table.bootstrapTable('getSelections');
   				Fast.api.close(temp); //往父窗口回调参数
        	 });
            //保存
        	 parent.window.$(".layui-layer-iframe").find(".layui-layer-close").on('click',function () {
  				 var  temp=table.bootstrapTable('getSelections');
   				Fast.api.close(temp); //往父窗口回调参数           
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