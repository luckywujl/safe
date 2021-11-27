define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init();
            var table = $("#table");
            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#nickname").text(data.extend);
               
            });
            // 初始化表格
            table.bootstrapTable({
                url: 'kaoshi/examination/planlist/getplan' + location.search,
                extend:{
                	  index_url: 'kaoshi/examination/planlist/getplan' ,
                    table: 'kaoshi_plan',
                
                },
                toolbar: '#toolbar',
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showColumns: false,
                search:false,
                searchFields: 'hidden',
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'subject_id', title: __('Subject_id'),visible:false},
                        //{field: 'exam_id', title: __('Exam_id')},
                        {field: 'plan_name', title: __('Plan_name'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'hours', title: __('Hours')},
                        {field: 'times', title: __('Times')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'limit', title: __('Limit'), searchList: {"0":__('Limit 0'),"1":__('Limit 1')}, formatter: Table.api.formatter.normal},
                        //{field: 'company_id', title: __('Company_id'), operate: 'LIKE'},
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            Table.api.init();
            var table1 = $("#table1");
            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#nickname_1").text(data.extend);
               
            });

            // 初始化表格
            table1.bootstrapTable({
                url: 'kaoshi/examination/planlist/index' + location.search,
                extend:{
                	  index_url: 'kaoshi/examination/planlist/index' ,
                    table: 'kaoshi_plan',
                
                },
                toolbar: '#toolbar1',
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showColumns: false,
                search:false,
                searchFields: 'hidden',
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        //{field: 'subject_id', title: __('Subject_id')},
                        //{field: 'exam_id', title: __('Exam_id')},
                        {field: 'plan_name', title: __('Plan_name'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'hours', title: __('Hours')},
                        {field: 'times', title: __('Times')},
                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'limit', title: __('Limit'), searchList: {"0":__('Limit 0'),"1":__('Limit 1')}, formatter: Table.api.formatter.normal},
                        //{field: 'company_id', title: __('Company_id'), operate: 'LIKE'},
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            

            // 为表格绑定事件
            Table.api.bindevent(table1);
            //选择培训
        	 $(document).on("click", ".btn-selectplan", function(){
        	 		var ids = Table.api.selectedids(table);
        				if (ids.length>0) {
          				$.post("kaoshi/examination/planlist/selectplan", {ids_plan:ids,ids_user:Config.ids},function(response){
            				if(response.code == 1){
                 				//Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				//Fast.api.close();
         	     				$("#refresh").trigger("click");
        	 						$("#refresh1").trigger("click");
             				}else{
                  			Toastr.error(response.msg)
             				}
             			}, 'json')
             			} else {
             				Toastr.success('没有选择要参加的考试');
         	     			//Fast.api.close();
             			}
        		
				});
				//退出培训
        	 $(document).on("click", ".btn-delplan", function(){
        	 		var ids = Table.api.selectedids(table1);
        				if (ids.length>0) {
          				$.post("kaoshi/examination/planlist/delplan", {ids_plan:ids,ids_user:Config.ids},function(response){
            				if(response.code == 1){
                 				//Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				//Fast.api.close();
         	     				$("#refresh").trigger("click");
        	 						$("#refresh1").trigger("click");
             				}else{
                  			Toastr.error(response.msg)
             				}
             			}, 'json')
             			} else {
             				Toastr.success('没有选择要退出的考试');
         	     			//Fast.api.close();
             			}
        		
				});
			 //退出
        	 $(document).on("click", ".btn-cancel", function(){
          		Fast.api.close();	
				});
			//确定
        	 $(document).on("click", ".btn-save", function(){
          		Fast.api.close();	
				});
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
                url: 'kaoshi/examination/planlist/recyclebin' + location.search,
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
                                    url: 'kaoshi/examination/planlist/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/planlist/destroy',
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
                    duration: function(value, row, index) {
                        var h, s;
                        h = Math.floor(value / 60);
                        s = value % 60;
                        h += '';
                        s += '';
                        h = (h.length == 1) ? '0' + h : h;
                        s = (s.length == 1) ? '0' + s : s;
                        return h + ':' + s;
                    },
                    thumb: function(value, row, index) {
                        if (!value && typeof value != "undefined" && value != 0) {
                            return '<img src="/assets/addons/training/images/cover.png" alt="" style="max-height:50px;max-width:80px">';
                        } else {
                            return '<a href="' + value + '" target="_blank"><img src="' + value + '" alt="" style="max-height:60px;max-width:80px"></a>';
                        }
                    },
                }
            },
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"), null, null, function(success, error) {
                    if ($('#c-user_ids').val() == '' && $('#c-user_group_ids').val() == '') {
                        Layer.alert("指定学员和指定角色必须填写任意其中一个！");
                    } else {
                        Form.api.submit(this, success, error);
                    }
                    return false;
                });
            }
        },
    };
    return Controller;
});