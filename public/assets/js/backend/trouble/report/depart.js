define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Template, Echarts) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/report/depart/index' + location.search,
                    add_url: 'trouble/report/depart/add',
                    edit_url: 'trouble/report/depart/edit',
                    del_url: 'trouble/report/depart/del',
                    multi_url: 'trouble/report/depart/multi',
                    import_url: 'trouble/report/depart/import',
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
                columns: [
                    [
                        {checkbox: true},
                        
                        {field: 'department_name', title: __('Department_name')},
                        {field: 'number', title: __('Number')},
                        {field: 'department_pname', title: __('Department_pname')},
                        {field: 'area_name', title: __('area_name')},
                        
                    ]
                ],
                responseHandler:function(res){
                    Controller.api.chart.line(res.rows);
                    return res;
                },
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
                url: 'trouble/report/depart/recyclebin' + location.search,
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
                                    url: 'trouble/report/depart/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'trouble/report/depart/destroy',
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
            chart:{
                line(res){
                    var data = [];
                    $.each(res, function (i, item) { 
                        data.push({'name':item.department_name,'number':item.number ? item.number : 0})
                    });

                    // 基于准备好的dom，初始化echarts实例
                    var lineChart = Echarts.init(document.getElementById('line-chart'), 'walden');

                    // 指定图表的配置项和数据
                    var option = {
                        dataset: [{
                            // 按行的 key-value 形式（对象数组），这是个比较常见的格式。
                            source: data
                        }],
                        xAxis: {
                            type: 'category'
                        },
                        yAxis: {
                            type: 'value'
                        },
                        series: [
                            {
                                type: 'bar',
                                encode: {
                                    x: 'name',
                                    y: 'number'
                                }
                            }
                        ]
                    };

                    // 使用刚指定的配置项和数据显示图表。
                    lineChart.setOption(option);
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});