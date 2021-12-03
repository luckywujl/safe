define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    $('[name="row[type]"]').on('change',function (e) {
        var type = $(this).val();
        if(type == 0){
            $('.time0').removeClass('hide');
            // $('.time1').addClass('hide');
        }else{
            // $('.time1').removeClass('hide');
            $('.time0').addClass('hide');
        }
    })
    //选择不同的参与对像，决定是否显示选择学员按钮
    $('[name="row[limit]"]').on('change',function (e) {
        var limit = $(this).val();
        if(limit == 1){
            $('.user-select').removeClass('hide');
        }else{
            $('.user-select').addClass('hide');
        }
    })
	//选择线下考核时，显示试卷图片上传栏
	$('[name="row[type]"]').on('change',function (e) {
        var limit = $(this).val();
        if(limit == 2){
            $('.type-select').removeClass('hide');
        }else{
            $('.type-select').addClass('hide');
        }
    })
    $('[name="row[exam_id]').data("params", function (obj) {
        return {custom: {subject_id: $('[name="row[subject_id]').val()}};
    });
    
    //选择参加培训的学员
				$(document).on("click",".btn-selectuser",function () {
					var ids = '0';
					if ($("#c-user_ids").val()!=='') {
						ids = $("#c-user_ids").val();
					}
				    //弹窗显示学员信息
         	  Fast.api.open('training/main/selectuser?ids='+ids,'选择参加学员',{//?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['98%', '98%'],
		           callback: function (data) {	
		           //alert(data);
		           	
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
    var Controller = {
        index: function () {
          	$(".btn-add").data("area",["98%","98%"]);
        		$(".btn-edit").data("area",["98%","98%"]);
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/index' + location.search,
                    add_url: 'kaoshi/examination/plan/add',
                    edit_url: 'kaoshi/examination/plan/edit',
                    del_url: 'kaoshi/examination/plan/del',
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'subject_id', title: __('Subject_id'),operate:'in',visible:false},
                        {field: 'plan_name', title: __('Plan_name')},
                        {field: 'exams.exam_name', title: __('Exam_name')},
                        {field: 'subject.subject_name', title: __('Subject_name')},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'limit', title: __('Limit'), searchList: {"0":__('Limit 0'),"1":__('Limit 1')}, formatter: Table.api.formatter.normal},
                        
                        {field: 'times', title: __('Times')},
                        {field: 'hours', title: __('Hours')},

                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('完成情况'),
                                    title: __('考试情况'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-group',
                                    url: 'kaoshi/examination/user_plan/index/plan_id/{id}'
                                },
                                
                              
                                {
                                    name: 'input',
                                    text: __('录入成绩'),
                                    title: __('录入成绩'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-group',
                                    url: 'kaoshi/examination/user_plan/input/plan_id/{id}'
                                },
                                
                                ],
    										//events: Table.api.events.operate,
    										formatter: function(value, row, index){
        										var that = $.extend({}, this);
        										var table = $(that.table).clone(true);
        										if (row.type != 2){
        										    $(table).data("operate-input", null);
        										}
        										if (row.type == 2){
        										    $(table).data("operate-contact", null);
        										}
        										that.table = table;
        										return Table.api.formatter.operate.call(that, value, row, index);
    										}

                        }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["98%","98%"]);
            	$(".btn-editone").data("title",'编辑');
            })
            Controller.api.tree.init(table);
        },
        study: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/study' + location.search,
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'id', title: __('Id')},
                        {field: 'plan_name', title: __('plan_name')},
                        {field: 'exam_name', title: __('exam_name')},
                        {field: 'subject_name', title: __('subject_name')},
                        {field: 'student_num', title: __('student_num')},
                        {field: 'real_num', title: __('real_num'),operate:false},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('查看详情'),
                                    title: __('参与人员'),
                                    classname: 'btn btn-xs btn-warning btn-dialog',
                                    icon: 'fa fa-address-book-o',
                                    url: 'kaoshi/examination/user_exams/users'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'type', title: __('type'), searchList: {"0":__('type 0'),"1":__('type 1'),"2":__('type 2')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        exam: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/exam' + location.search,
                    multi_url: 'kaoshi/examination/plan/multi',
                    table: 'plan',
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
                        {field: 'id', title: __('Id')},
                        {field: 'plan_name', title: __('plan_name')},
                        {field: 'exam_name', title: __('exam_name')},
                        {field: 'subject_name', title: __('subject_name')},
                        {field: 'student_num', title: __('student_num')},
                        {field: 'real_num', title: __('real_num'),operate:false},
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('查看'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'contact',
                                    text: __('查看详情'),
                                    title: __('参与人员'),
                                    classname: 'btn btn-xs btn-warning btn-addtabs',
                                    icon: 'fa fa-address-book-o',
                                    url: 'kaoshi/examination/user_exams/users'
                                }
                            ],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'type', title: __('type'), searchList: {"0":__('type 0'),"1":__('type 1'),"2":__('type 2')}, formatter: Table.api.formatter.status},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
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
                url: 'kaoshi/examination/plan/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'plan_name', title: __('Plan_name')},
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
                                    url: 'kaoshi/examination/plan/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/plan/destroy',
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
                                url: "kaoshi/subject/jstree",
                                autoParam: ["id", "name"],
                                //otherParam:{ type: '企业部门' }
                            },
                            callback: {
                                onAsyncSuccess: function(event, treeId, treeNode, msg) {
                                    var nodes = zTreeObj.getNodes();
                                    if (nodes.length>0) {
                                        zTreeObj.selectNode(nodes[0]);
                                    }
                                    zTreeObj.expandAll(true);
                                    Controller.api.tree.expandall();
                                },
                                onClick: function(event, treeId, treeNode, clickFlag) {
                                    var subject_id = Controller.api.tree.getChildNodes(treeNode);
                                    if (treeNode.id == '0') {
                                        $(".commonsearch-table input[name=subject_id]").val('');
                                    } else {
                                        $(".commonsearch-table input[name=subject_id]").val(subject_id);
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'kaoshi/examination/plan/add?subject_id=' + treeNode.id
                                    table.bootstrapTable('refresh',options);
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
                expandall: function() {
                    $(document).on("click", "#expandall", function() {
                        var status = $("i", this).hasClass("fa-chevron-right");
                        if (status) {
                            $("i", this).removeClass("fa-chevron-right").addClass("fa-chevron-down");
                            zTreeObj.expandAll(true);
                        } else {
                            $("i", this).removeClass("fa-chevron-down").addClass("fa-chevron-right");
                            zTreeObj.expandAll(false);
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
            },
            formatter: {
                thumb: function (value, row, index) {
                    if(typeof (value) == 'string' && value.length > 0){
                        return '<a href="' + value + '" target="_blank"><img src="' + value +'" alt="" style="max-height:90px;max-width:120px"></a>';
                    }else{
                        return "";
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + value + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
            }
        }
    };
    return Controller;
});