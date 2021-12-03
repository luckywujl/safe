define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Echarts) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/wechat_usersummary/index' + location.search,
                    add_url: 'suisunwechat/wechat_usersummary/add',
                    edit_url: 'suisunwechat/wechat_usersummary/edit',
                    del_url: 'suisunwechat/wechat_usersummary/del',
                    multi_url: 'suisunwechat/wechat_usersummary/multi',
                    table: 'suisunwechat_wechat_usersummary',
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
                        {field: 'time', title: __('Time'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'user_source', title: __('User_source')},
                        {field: 'new_user', title: __('New_user')},
                        {field: 'cancel_user', title: __('Cancel_user')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime}
                    ]
                ]
            });
            table.on('load-success.bs.table', function (e, data) {
                $('#search').trigger('click');
            });

            let chart_data = null;
            let lineChart = Echarts.init(document.getElementById('chart'), 'walden');
            let option = {
                grid: {
                    left: '5%',
                    right: '10%',
                    top: '20%',
                    bottom: '15%',
                    containLabel: true
                },
                tooltip: {
                    show: true,
                    trigger: 'item'
                },
                legend: {
                    show: true,
                    x: 'center',
                    y: '35',
                    icon: 'circle',
                    itemWidth: 10,
                    itemHeight: 10,
                    textStyle: {
                        color: '#1bb4f6'
                    },
                    data: ['粉丝'],
                },
                xAxis: {
                    type: 'category',
                    boundaryGap: false,
                    axisLine: {
                        show: true,
                    },
                    axisTick: {
                        show: false,
                    },
                    splitLine: {
                        show: false,
                    },
                    data: []
                },
                yAxis: {
                    type: 'value',
                    name: '',
                    // min: -50.0,
                    // max: 50.0,
                    axisTick: {
                        show: false,
                    },
                    splitLine: {
                        show: true,
                    }
                },
                series: [
                    {
                        name: '粉丝',
                        type: 'line',
                        symbol: 'circle',
                        symbolSize: 8,
                        itemStyle: {
                            normal: {
                                color: '#3fb1ff',
                            }
                        },
                        label: {
                            normal: {
                                show: true,
                                position: 'top'
                            }
                        },
                        markPoint: {
                            itemStyle: {
                                normal: {
                                    color: '#3fb1ff'
                                }
                            }
                        },
                        smooth: true,
                        data: []
                    },
                ]
            };

            let cur_type = 'add';
            $(".overview-switch-item").click(function (e) {
                $(".overview-switch-item").removeClass('overview-switch-item-active');
                $(this).addClass('overview-switch-item-active');
                cur_type = $(this).data('type');
                changeData();
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.bindevent();

            $("#search").click(function () {
                var starttime = $("#starttime").val();
                var endtime = $("#endtime").val();

                $.get('suisunwechat/wechat_usersummary/getData',{
                    starttime: starttime,
                    endtime: endtime
                },function (res) {
                    if (res.code == 1){
                        chart_data = res.data;
                        changeData();
                    }else{
                        Toastr.error(res.msg);
                    }
                });
            });
            
            function changeData() {
                if (chart_data == null){
                    return;
                }
                var new_data = [];
                var leave_data = [];
                var pure_data = [];
                var date = [];
                for(var i = 0;i < chart_data.cancel_count.length;i++){
                    new_data.push(chart_data.new_count[i]);
                    leave_data.push(chart_data.cancel_count[i]);
                    pure_data.push(chart_data.purecount[i]);
                    date.push(chart_data.date[i]);
                }
                option.xAxis.data = date;

                switch (cur_type) {
                    case 'add':
                        option.series[0].name = '新增人数';
                        option.series[0].data = new_data;
                        break;
                    case 'cancel':
                        option.series[0].name = '取消关注人数';
                        option.series[0].data = leave_data;
                        break;
                    case 'net':
                        option.series[0].name = '净增人数';
                        option.series[0].data = pure_data;
                        break;
                }
                lineChart.setOption(option);
                $("#chart").css('height',250)
                lineChart.resize();
            }
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        init_summary:function () {
            var current_rate = 0;
            $(".btn-get").click(function () {
                current_rate = 0;
                update_process();
                let starttime = $("#starttime").val();
                if(starttime==''){
                    Toastr.error('请选择采集数据的月份');
                    return;
                }
                let starttime_list = starttime.split("-");
                if(starttime_list.length!=2){
                    Toastr.error('日期数据不准确');
                    return;
                }
                var current = new Date();
                if(starttime_list[0]>current.getFullYear() ||Number(starttime_list[1])>(current.getMonth()+1)){
                    Toastr.error('日期数据不准确,选择时间不可大于当前时间');
                    return;
                }
                let d = new Date(starttime_list[0], starttime_list[1],0);
                let day = d.getDate();
                //分割输入
                let time_list = [];
                for (let i=1 ;i<=day;i += 6){
                    let endtime = (i+6)>day?day:(i+6);
                    let isJump = false;
                    if(starttime_list[0]==current.getFullYear() && starttime_list[1]==(current.getMonth()+1) && endtime>(current.getDate()-1)){
                        endtime = current.getDate() -1;
                        isJump = true;
                    }
                    let add_itme = {'start':starttime+'-'+i,'end':starttime+'-'+endtime}
                    time_list.push(add_itme);
                    if(isJump){
                        break;
                    }
                }
                let set_index = time_list.length;
                set_index = 100/set_index;
                time_list.forEach((item)=>{
                    getData(item.start,item.end,set_index);
                });
            });
            function getData(start,end,setindex) {
                Fast.api.ajax({data: {'start':start,'end':end}}, function () {
                    if(current_rate<100){
                        current_rate += setindex;
                        current_rate = current_rate>100?100:current_rate;
                        $(".process-item").css('width',current_rate+'%');
                        $(".process-item-number").find('span').text(current_rate);
                    }
                    return false;
                });//调用接口
            }
            function update_process() {
                $(".process-item").css('width',current_rate+'%');
                $(".process-item-number").find('span').text(current_rate);
            }

            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
        }
    };
    return Controller;
});