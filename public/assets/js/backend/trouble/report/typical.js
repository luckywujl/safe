define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/report/typical/index' + location.search,
                    
                    table: 'trouble_main',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                searchFormVisible:true,
					 search: false, //快速搜索
                searchFormTemplate: 'customformtpl',
                //pk: 'trouble_expression',
                //sortName: 'nubmer',
                columns: [
                    [
                        {checkbox: true},
                        
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        {field: 'number', title: __('Number'), sortable:true,operate: false},
                        
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
       
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});