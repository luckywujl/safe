define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function($, undefined, Backend, Table, Form, Template) {
    var zTreeObj=null;
    var Controller = {
        index: function() {      		
            // 初始化表格参数配置
            Table.api.init();
            var table = $("#table");
            // 初始化表格
             //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#nickname").text(data.extend);
               
            });
            table.bootstrapTable({
                url: 'training/mainlist/getmain' + location.search,
                extend:{
                	  index_url: 'training/mainlist/getmain',
                    table: 'training_main',
                
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
                        { checkbox: true },
                        { field: 'id', title: __('Id'),visible:false },
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        //{ field: 'training_course_ids', title: __('Training_course_ids'), visible: false, operate: false, visible: false },
                        { field: 'duration', title: __('Duration'), operate: false, formatter: Controller.api.table.formatter.duration },
                        { field: 'coverimage', title: __('Coverimage'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        //{ field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        //{ field: 'user_ids', title: __('User_ids'), operate: false, visible: false },
                        //{ field: 'user_group_ids', title: __('User_group_ids'), operate: false, visible: false },
                        //{ field: 'admin_id', title: __('Admin_id'), operate: false, visible: false },
                        { field: 'starttime', title: __('Starttime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'endtime', title: __('Endtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        //{ field: 'weigh', title: __('Weigh'),visible:false, operate: false },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                       // { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            
            Controller.api.tree.init(table);
            
            Table.api.init();
            var table1 = $("#table1");

            // 初始化表格
            //当表格数据加载完成时
            table.on('load-success.bs.table', function (e, data) {
                //这里可以获取从服务端获取的JSON数据
                console.log(data);
                //这里我们手动设置底部的值
                $("#nickname_1").text(data.extend);
               
            });
            table1.bootstrapTable({
                url: 'training/mainlist/index' + location.search,
                extend:{
                	  index_url: 'training/mainlist/index',
                    table: 'training_main',
                
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
                        { checkbox: true },
                        { field: 'id', title: __('Id'),visible:false },
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        //{ field: 'training_course_ids', title: __('Training_course_ids'), visible: false, operate: false, visible: false },
                        { field: 'duration', title: __('Duration'), operate: false, formatter: Controller.api.table.formatter.duration },
                        { field: 'coverimage', title: __('Coverimage'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        //{ field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        //{ field: 'user_ids', title: __('User_ids'), operate: false, visible: false },
                        //{ field: 'user_group_ids', title: __('User_group_ids'), operate: false, visible: false },
                        //{ field: 'admin_id', title: __('Admin_id'), operate: false, visible: false },
                        { field: 'starttime', title: __('Starttime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'endtime', title: __('Endtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        //{ field: 'weigh', title: __('Weigh'),visible:false, operate: false },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                       // { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });
            

            // 为表格绑定事件
            Table.api.bindevent(table1);
            //选择培训
        	 $(document).on("click", ".btn-selectmain", function(){
        	 		var ids = Table.api.selectedids(table);
        				if (ids.length>0) {
          				$.post("training/mainlist/selectmain", {ids_main:ids,ids_user:Config.ids},function(response){
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
             				Toastr.success('没有选择要参加的培训');
         	     			//Fast.api.close();
             			}
        		
				});
				//退出培训
        	 $(document).on("click", ".btn-delmain", function(){
        	 		var ids = Table.api.selectedids(table1);
        				if (ids.length>0) {
          				$.post("training/mainlist/delmain", {ids_main:ids,ids_user:Config.ids},function(response){
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
             				Toastr.success('没有选择要退出的培训');
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
  
        api: {
            events: {
                loadCourse: function() {
                    var idStr = $('#training_course_ids').val();
                    $.getJSON("training/course/getCourse", { ids: idStr },
                        function(data, textStatus, jqXHR) {
                            $("#choosecourse").html(Template('choosecoursetpl', { item: data }));
                            Controller.api.events.removeCourse();
                        }
                    );
                },
                chooseCourse: function() {
                    $(".btn-course").on('click', function() {
                        var that = this;
                        var admin_id = $(this).data("admin-id") ? $(this).data("admin-id") : '';
                        var choose_id = $('#training_course_ids').val();
                        var url = "training/course/select";
                        parent.Fast.api.open(url + "?choose_id=" + choose_id, __('Choose'), {
                            area: ['80%', '800px'],
                            callback: function(data) {
                                $('#training_course_ids').val(data.id);
                                $("#choosecourse").html(Template('choosecoursetpl', { item: data.item }));
                                Controller.api.events.removeCourse();
                            }
                        });
                        return false;
                    });
                },
                removeCourse: function() {
                    $(".btn-remove").on("click", function() {
                        var idStr = $('#training_course_ids').val();
                        var removeID = $(this).closest("tr").data("courseid");
                        var idArr = idStr.split(',');
                        var index = idArr.indexOf(removeID.toString());
                        if (index > -1) {
                            idArr.splice(index, 1);
                        }
                        $('#training_course_ids').val(idArr.join(","));
                        if ($(this).closest("table").find("tr[data-courseid]").length <= 1) {
                            $(this).closest("table").remove();
                        } else {
                            $(this).closest("tr").remove();
                        }

                    })
                }
            },
            tree: {
                init: function(table) {
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
                                url: "training/category/jstree",
                                autoParam: ["id", "name"],
                                otherParam:{ type: 'main' }
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
                                    var category_id = Controller.api.tree.getChildNodes(treeNode);
                                    if (treeNode.id == '0') {
                                        $(".commonsearch-table input[name=training_category_id]").val('');
                                    } else {
                                        $(".commonsearch-table input[name=training_category_id]").val(category_id);
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'training/main/add?category=' + treeNode.id
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