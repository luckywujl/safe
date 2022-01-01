define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Template, Echarts) {

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
                        
                        {field: 'kind', title: __('Kind'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), operate: 'LIKE'},
                        {field: 'expression', title: __('Expression'), operate: 'LIKE'},
                        {field: 'number', title: __('Number'), sortable:true,operate: false},
                        
                    ]
                ],
                responseHandler:function(res){
                    Controller.api.chart.pie(res.rows);
                    Controller.api.chart.line(res.rows);
                    return res;
                },
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

        },
       
        api: {
            chart:{
                pie(res){
                    
                    var data = [];
                    $.each(res, function (i, item) { 
                        data[item.kind] =0
                    });
                    //data['人的不安全因素']=0,
                    //data['5']=0,
                    //data['6']=0,
                    $.each(res, function (i, item) { 
                        if(item.kind!==null){
                            data[item.kind] += item.number;
                        }
                    });
                    var kk = [];
                    var val = [];
                    for(var key in data){
                        var obj = new Object();
                        obj ={
                            value:data[key],
                            name:key
                        }
                       
                        val.push(obj);


                        kk.push(key);
                    }
                    var pieChart = Echarts.init(document.getElementById('pie-chart'), 'walden');
                    var option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b}: {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 10,
                            data: kk
                        },
                        series: [
                            {
                                name: '隐患分类',
                                type: 'pie',
                                radius: ['50%', '70%'],
                                avoidLabelOverlap: false,
                                label: {
                                    normal: {
                                        show: false,
                                        position: 'center'
                                    },
                                    emphasis: {
                                        show: true,
                                        textStyle: {
                                            fontSize: '30',
                                            fontWeight: 'bold'
                                        }
                                    }
                                },
                                labelLine: {
                                    normal: {
                                        show: false
                                    }
                                },
                                data:val //[
                                    
                                //     {value: data['人的不安全因素'], name: '人的不安全因素'},
                                //     {value: data['物的不安全状态'], name: '物的不安全状态'},
                                //     {value: data['6'], name: '6'}
                                // ]
                            }
                        ]
                    };
                    // 使用刚指定的配置项和数据显示图表。
                    pieChart.setOption(option);
                },
                line(res){
                    var data = [];
                    $.each(res, function (i, item) { 
                        data.push({'name':item.type,'time':item.number ? item.number : 0})
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
                                    y: 'time'
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