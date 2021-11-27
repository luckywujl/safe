define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template', 'echarts', 'echarts-theme'], function ($, undefined, Backend, Table, Form, Template, Echarts) {
    var treeObj = null;
    var Controller = {
        index: function () {   
            Controller.api.tree.filter();
            Controller.api.tree.init();
            Controller.api.tree.expandall();
            
        }, 
        api:{
            tree: {
                init:function(type = ''){
                        require(['zTree', 'zTree-awesome'], function(zTree) {
                            var table = $("#table");
                            treeObj = $.fn.zTree.getZTreeObj("ztree");
                            
                            if(treeObj)
                            {
                                $.fn.zTree.destroy("ztree");// 清除树节点
                            }
                            var setting = {
                                view: {
                                    showIcon: true
                                },
                                data: {
                                    simpleData: {
                                        enable: false
                                    },
                                    render: {
                                        name:function(name,treeNode){
                                            return treeNode.text;
                                        }
                                    }
                                },
                                async: {
                                    enable: true,
                                    contentType: "application/json",
                                    url: "training/index/jstree",
                                    autoParam: ["id", "name"],
                                    otherParam:{ type: type }
                                },
                                callback: {
                                    onAsyncSuccess: function(event, treeId, treeNode, msg) {
                                        var node = zTreeObj.getNodeByParam("checked", true, null);
                                        if (node && node.length >= 1) {
                                           node = node[0];
                                        }
                                        if(node){
                                            zTreeObj.selectNode(node);
                                            $("#tree-title").html(node.text);
                                            Controller.api.table.init(node);
                                            zTreeObj.expandAll(true);
                                            Controller.api.tree.expandall();
                                        }else{
                                            //Toastr.error('未找到培训项目')
                                            Layer.msg('未找到培训项目')
                                        }
                                    },
                                    onClick: function(event, treeId, treeNode, clickFlag) {
                                        if(typeof(treeNode.course_id) !== "undefined" && typeof(treeNode.main_id) !== "undefined"){
                                            $("#tree-title").html(treeNode.text);
														  $("#tree-title_d").html('全部');
														  
                                            table.bootstrapTable('refreshOptions', {queryParams: function(params){
                                                params.order = 'asc';
                                                params.main_id = treeNode.main_id;
                                                params.course_id = treeNode.course_id;
                                                return params;
                                            }});
                                        }else{
                                            zTreeObj.expandNode(treeNode);
                                        }
                                        return false;
                                    }
                                }
                            };
                            zTreeObj = $.fn.zTree.beautify().init($("#ztree"), setting, []);
                        });
                    },
                    getChildNodes(treeNode) {
                        var childNodes = zTreeObj.transformToArray(treeNode);
                        var nodes = new Array();
                        for(i = 0; i < childNodes.length; i++) {
                            nodes[i] = childNodes[i].id;
                        }
                        return nodes.join(",");
                    },
                    filter: function(){
                        $("#selectType li>a").on("click", function(e) {
                            var type = $(this).data('value');
                            var str = $(this).data('text');
                            $("#filter").data('value', type).html(str);
                            Controller.api.tree.init(type);
                        });
                    },
                    expandall: function() {
                        $(document).on("click", "#expandall", function() {
                            var status = $("i", this).hasClass("fa-chevron-right");
                            if (status) {
                                $("i", this).removeClass("fa-chevron-right").addClass("fa-chevron-down");
                                zTreeObj.expandAll(false);
                            } else {
                                $("i", this).removeClass("fa-chevron-down").addClass("fa-chevron-right");
                                zTreeObj.expandAll(true);
                            }
                        });
                        
                        $(document).on("click", "#expandall_d", function() {
                    	 
                        var status_d = $("i", this).hasClass("fa-chevron-right");
                        if (status_d) {
                            $("i", this).removeClass("fa-chevron-right").addClass("fa-chevron-down");
                            zTreeObj_d.expandAll(true);
                        } else {
                            $("i", this).removeClass("fa-chevron-down").addClass("fa-chevron-right");
                            zTreeObj_d.expandAll(false);
                        }
                    });
                    }
            },
            chart:{
                pie(res){
                    var data = [];
                    data['已完成'] = 0;
                    data['学习中'] = 0;
                    data['未学习'] = 0;
                    $.each(res, function (i, item) { 
                        if(item.record!==null && item.record.complete == 0){
                            data['学习中'] += 1;
                        }else if(item.record!==null && item.record.complete == 1){
                            data['已完成'] += 1;
                        }else{
                            data['未学习'] += 1;
                        }
                    });
                    var pieChart = Echarts.init(document.getElementById('pie-chart'), 'walden');
                    var option = {
                        tooltip: {
                            trigger: 'item',
                            formatter: '{a} <br/>{b}: {c} ({d}%)'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 10,
                            data: ['已完成', '学习中', '未学习']
                        },
                        series: [
                            {
                                name: '完成情况',
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
                                data: [
                                    {value: data['已完成'], name: '已完成'},
                                    {value: data['学习中'], name: '学习中'},
                                    {value: data['未学习'], name: '未学习'}
                                ]
                            }
                        ]
                    };
                    // 使用刚指定的配置项和数据显示图表。
                    pieChart.setOption(option);
                },
                line(res){
                    var data = [];
                    $.each(res, function (i, item) { 
                       data.push({'name':item.nickname,'time':item.record ? item.record.studytime : 0})
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
            table:{
                init(treeNode){
                    // 初始化表格参数配置
                    Table.api.init({
                        extend: {
                            index_url: 'training/index/record' + location.search,
                            table: 'training_main',
                        }
                    });

                    var table = $("#table");

                    // 初始化表格
                    table.bootstrapTable({
                        url: $.fn.bootstrapTable.defaults.extend.index_url,
                        height:643,
                        pk: 'id',
                        sortName: 'id',
                        order:'asc',
                        columns: [
                            [
                                { checkbox: true },
                                {field: 'nickname', title: __('Username')}, 
                                {field: 'department_id', title: __('Department_id'),operate:'in',visible:false}, 
                                {field: 'department_name', title: __('Department_name'),operate:false,}, 
                                {field: 'record.total', title: __('Total'),operate:false,formatter: Controller.api.table.formatter.studytime},
                                {field: 'record.studytime', title: __('Studytime'),operate:false,formatter: Controller.api.table.formatter.studytime},
                                {field: 'record.progress', title: __('Progress'),operate:false,formatter: Controller.api.table.formatter.progress},
                                {field: 'record.complete', title: __('Complete'),operate:false,formatter: Controller.api.table.formatter.complete},
                                
                            
                            ]
                        ],
                        queryParams: function(params){
                            params.order = 'asc';
                            params.main_id = treeNode.main_id;
                            params.course_id = treeNode.course_id;
                            return params;
                        },
                        responseHandler:function(res){
                            Controller.api.chart.pie(res.rows);
                            Controller.api.chart.line(res.rows);
                            return res;
                        },
                        //禁用默认搜索
                        search: true,
                        //启用普通表单搜索
                        commonSearch: true,
                        //可以控制是否默认显示搜索单表,false则隐藏,默认为false
                        searchFormVisible: false,
                        //分页大小
                        pageSize: 10,
                        pagination:true,
                        toolbar:false,
                        pageList : [10],
                        showColumns: false,
                        showToggle: false,
                        showExport: true,
                    });

                    // 为表格绑定事件
                    Table.api.bindevent(table);
                    Controller.api_u.tree.init(table);
                }, 
                formatter:{
                    progress: function(value,row,index){
                        var color = row['record']['complete'] ? 'green':'aqua';
                        return '<div class="progress" style="position: relative;background:#eee;">\
                            <div style="position:absolute;top:0;left:0;text-align: center;width:100%;">'+value+'%</div>\
                            <div class="progress-bar progress-bar-'+color+'" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: '+value+'%">\
                            </div>\
                        </div>'
                    },
                    studytime:function(value,row,index) {
                        var h,s;
                        h  =   Math.floor(value/60);
                        s  =   value %60;
                        h    +=    '';
                        s    +=    '';
                        h  =   (h.length==1)?'0'+h:h;
                        s  =   (s.length==1)?'0'+s:s;
                        return h+':'+s;
                    },
                    complete:function(value,row,index) {
                        if(value === 1){
                            return '<small class="label bg-green">已完成</small>'
                        }else{
                            return '<small class="label bg-gray">未完成</small>'
                        }
                    }
                }
            }
        },
        api_u: {
            getQueryVariable(variable){
                var query = window.location.search.substring(1);
                var vars = query.split("&");
                for (var i=0;i<vars.length;i++) {
                    var pair = vars[i].split("=");
                    if(pair[0] == variable){return pair[1];}
                }
                return(false);
            },
            tree: {
                init:function(table){
                    require(['zTree', 'zTree-awesome'], function(zTree) {
                        var setting = {
                            view: {
                                showIcon: true,
                                selectedMulti: false
                            },
                            data: {
                                simpleData: {
                                    enable: true
                                }
                            },
                            async: {
                                enable: true,
                                contentType: "application/json",
                                url: "user/department/jstree",
                                autoParam: ["id", "name"],
                                //otherParam:{ type: '企业部门' }
                            },
                            callback: {
                                onAsyncSuccess: function(event, treeId, treeNode, msg) {
                                    var nodes = zTreeObj_d.getNodes();
                                    if (nodes.length>0) {
                                        zTreeObj_d.selectNode(nodes[0]);
                                    }
                                    zTreeObj_d.expandAll(true);
                                    Controller.api.tree.expandall();
                                },
                                onClick: function(event, treeId, treeNode, clickFlag) {
                                    var department_id = Controller.api.tree.getChildNodes(treeNode);
                                    if (treeNode.id == '0') {
                                        $(".commonsearch-table input[name=department_id]").val('');
                                        $("#tree-title_d").html('全部');
                                    } else {
                                        $(".commonsearch-table input[name=department_id]").val(department_id);
                                        $("#tree-title_d").html(treeNode.name);
                                        
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'user/user/add?department_id=' + treeNode.id
                                    table.bootstrapTable('refresh',options);
                                    
                                }
                            }
                        };
                        zTreeObj_d = $.fn.zTree.beautify().init($("#ztree_d"), setting, []);
                       
                      
                    });
                },
                getChildNodes(treeNode) {
                    var childNodes = zTreeObj_d.transformToArray(treeNode);
                    var nodes = new Array();
                    for(i = 0; i < childNodes.length; i++) {
                        nodes[i] = childNodes[i].id;
                    }
                    return nodes.join(",");
                },
                expandall: function() {
                    $(document).on("click", "#expandall_d", function() {
                    	  alert('');
                        var status = $("i", this).hasClass("fa-chevron-right");
                        if (status) {
                            $("i", this).removeClass("fa-chevron-right").addClass("fa-chevron-down");
                            zTreeObj_d.expandAll(true);
                        } else {
                            $("i", this).removeClass("fa-chevron-down").addClass("fa-chevron-right");
                            zTreeObj_d.expandAll(false);
                        }
                    });
                }
            },
            table: {
                formatter: {
                    checkbox(value, row, index){
                        var choosed = Controller.api.getQueryVariable("choose_id");
                        if(choosed && choosed !== ""){
                            var index = choosed.indexOf(row.id);
                            if(index > -1){
                                return {
                                    checked : true//设置选中
                                };
                            }
                        }
                        
                        return value;
                    },
                    duration: function(value, row, index) {
                        var h,s;
                        h  =   Math.floor(value/60);
                        s  =   value %60;
                        h    +=    '';
                        s    +=    '';
                        h  =   (h.length==1)?'0'+h:h;
                        s  =   (s.length==1)?'0'+s:s;
                        return h+':'+s;
                    },
                    files: function(value, row, index) {
                        var suffix = /[\.]?([a-zA-Z0-9]+)$/.exec(value);
                        suffix = suffix ? suffix[1] : 'file';
                        return '<a href="' + value + '" target="_blank" ><img src="' + value + '" onerror="this.src=\'' + Fast.api.fixurl("ajax/icon") + '?suffix=' + suffix + '\';this.onerror=null;" class="img-responsive"></a>'
                    }
                }
            },
            
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        },
    };
    return Controller;
});
