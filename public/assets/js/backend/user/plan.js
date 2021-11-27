define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/plan/index',
                   
                    table: 'user',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'user.id',
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id'), sortable: true},
                        {field: 'jobnumber', title: __('Jobnumber'),operate: 'LIKE',sortable: true},
                        {field: 'department_id', title: __('Department_id'),visible:false,operate: 'in'},
                        {field: 'department.name', title: __('Department_id'),operate: 'LIKE'},
                        {field: 'group.name', title: __('Group_id'),operate: 'LIKE'},
                        //{field: 'username', title: __('Username'), operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'gender', title: __('Gender'),  searchList: {1: __('Male'), 0: __('Female')},formatter: Table.api.formatter.normal},
                        {field: 'email', title: __('Email'), operate: 'LIKE',visible:false},
                        {field: 'mobile', title: __('Mobile'), operate: 'LIKE'},
                        {field: 'avatar', title: __('Avatar'), events: Table.api.events.image, formatter: Table.api.formatter.image, operate: false},
                        //{field: 'level', title: __('Level'), operate: 'BETWEEN', sortable: true},
                        {field: 'score', title: __('Score'), operate: 'BETWEEN', sortable: true},
                        //{field: 'successions', title: __('Successions'), visible: false, operate: 'BETWEEN', sortable: true},
                        //{field: 'maxsuccessions', title: __('Maxsuccessions'), visible: false, operate: 'BETWEEN', sortable: true},
                        //{field: 'logintime', title: __('Logintime'), visible:false,formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        //{field: 'loginip', title: __('Loginip'), formatter: Table.api.formatter.search},
                        {field: 'jointime', title: __('Jointime'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        //{field: 'joinip', title: __('Joinip'), visible:false,formatter: Table.api.formatter.search},
                        {field: 'status', title: __('Status'), formatter: Table.api.formatter.status, searchList: {normal: __('Normal'), hidden: __('Hidden')}},
                        {field: 'operate', title: __('Operate'), table: table, 
													buttons: [
 												  		 {
 												  		 	name: 'main_info', 
 												  		 	text: '培训计划', 
 												  		 	title: '培训计划', 
 												  		 	icon: 'fa fa-list', 
 												  		 	extend: 'data-area=\'["95%","95%"]\'',    //设置最大化
 												  		 	classname: 'btn btn-xs btn-primary btn-dialog',  												  
 												  		 	url: 'training/mainlist/index?user_id={id}',
 												  			},
														
 												  		 {
 												  		 	name: 'kaoshi_info', 
 												  		 	text: '考核计划', 
 												  		 	title: '考核计划', 
 												  		 	icon: 'fa fa-list', 
 												  		 	extend: 'data-area=\'["95%","95%"]\'',    //设置最大化
 												  		 	classname: 'btn btn-xs btn-primary btn-dialog',  												  
 												  		 	url: 'kaoshi/examination/planlist/index?user_id={id}',
 												  			}
														],
														formatter: Table.api.formatter.operate} ,
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            //选择培训
				$(document).on("click",".btn-selectmain",function () {
				  var ids = Table.api.selectedids(table);
				    //弹窗显示批量设置内容
         	  Fast.api.open('user/plan/selectmain?ids='+ids,'选择培训',{//?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['95%', '95%'],
		           callback: function (data) {	
		           //alert(data);
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         $(document).on("click", ".btn-clearmain", function(){
        	 		var ids = Table.api.selectedids(table);
   				layer.confirm('确定要清空所有选中学员的培训计划吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
        				if (ids.length>0) {
          				$.post("user/plan/clearmain", {ids:ids},function(response){
            				if(response.code == 1){
                 				Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				Fast.api.close();
             				}else{
                  			Toastr.error(response.msg)
             				}
             			
             			}, 'json')
             			} else {
             				Toastr.success('没有选择学员');
					  			
         	     			//Fast.api.close();
             			}
           			},function(index){
            			layer.close(index);
        				});
				});
				
				//选择考试
				$(document).on("click",".btn-selectkaoshi",function () {
				  var ids = Table.api.selectedids(table);
				    //弹窗显示批量设置内容
         	  Fast.api.open('user/plan/selectkaoshi?ids='+ids,'选择考试',{//?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['95%', '95%'],
		           callback: function (data) {	
		           //alert(data);
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         //取消考试
	          $(document).on("click", ".btn-clearkaoshi", function(){
        	 		var ids = Table.api.selectedids(table);
   				layer.confirm('确定要清空所有选中学员的考试计划吗?注：对于全体参与的考试，全清无效。', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
        				if (ids.length>0) {
          				$.post("user/plan/clearkaoshi", {ids:ids},function(response){
            				if(response.code == 1){
                 				Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				Fast.api.close();
             				}else{
                  			Toastr.error(response.msg)
             				}
             			
             			}, 'json')
             			} else {
             				Toastr.success('没有选择学员');
					  			
         	     			//Fast.api.close();
             			}
           			},function(index){
            			layer.close(index);
        				});
				});
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        selectmain: function () {
        	 
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'training/main/index' + location.search,
                  
                    table: 'training_main',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id'),visible:false},
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'training_course_ids', title: __('Training_course_ids'), visible: false, operate: false, visible: false },
                        { field: 'duration', title: __('Duration'), operate: false, formatter: Controller.api.table.formatter.duration },
                        { field: 'coverimage', title: __('Coverimage'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        { field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        //{ field: 'user_ids', title: __('User_ids'), operate: false, visible: false },
                        { field: 'user_group_ids', title: __('User_group_ids'), operate: false, visible: false },
                        //{ field: 'admin_id', title: __('Admin_id'), operate: false, visible: false },
                        { field: 'starttime', title: __('Starttime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'endtime', title: __('Endtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        //{ field: 'weigh', title: __('Weigh'), operate: false },
                        //{ field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                        //{ field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api_m.tree.init(table);
            //保存退出
        	 $(document).on("click", ".btn-save", function(){
        	 		var ids = Table.api.selectedids(table);
   				layer.confirm('确定要保存为学员选中的培训计划吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
        				if (ids.length>0) {
          				$.post("user/plan/selectmain", {ids_main:ids,ids_user:Config.ids},function(response){
            				if(response.code == 1){
                 				Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				Fast.api.close();
             				}else{
                  			Toastr.error(response.msg)
             				}
             			
             			}, 'json')
             			} else {
             				Toastr.success('没有选择培训');
					  			
         	     			//Fast.api.close();
             			}
           			},function(index){
            			layer.close(index);
        				});
				});
			 //退出
        	 $(document).on("click", ".btn-cancel", function(){
   				layer.confirm('确定要放弃保存为学员选中的培训计划吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
          				Fast.api.close();
           			},function(index){
            			layer.close(index);
        				});
				});
        },
        
        selectkaoshi: function () {
        	 
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/plan/index' + location.search,
                   
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
                        {field: 'type', title: __('Type'), searchList: {"0":__('Type 0'),"1":__('Type 1')}, formatter: Table.api.formatter.normal},
                        {field: 'limit', title: __('Limit'), searchList: {"0":__('Limit 0'),"1":__('Limit 1')}, formatter: Table.api.formatter.normal},
                        
                        {field: 'times', title: __('Times')},
                        {field: 'hours', title: __('Hours')},

                        {field: 'starttime', title: __('Starttime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'endtime', title: __('Endtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                       // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
           
            Controller.api_k.tree.init(table);
            //保存退出
        	 $(document).on("click", ".btn-save", function(){
        	 		var ids = Table.api.selectedids(table);
   				layer.confirm('确定要保存为学员选中的考试计划吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
        				if (ids.length>0) {
          				$.post("user/plan/selectkaoshi", {ids_kaoshi:ids,ids_user:Config.ids},function(response){
            				if(response.code == 1){
                 				Toastr.success(response.msg);
					  				//parent.$("a.btn-refresh").trigger("click");
         	     				Fast.api.close();
             				}else{
                  			Toastr.error(response.msg)
             				}
             			
             			}, 'json')
             			} else {
             				Toastr.success('没有选择培训');
					  			
         	     			//Fast.api.close();
             			}
           			},function(index){
            			layer.close(index);
        				});
				});
			 //退出
        	 $(document).on("click", ".btn-cancel", function(){
   				layer.confirm('确定要放弃保存为学员选中的考试计划吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
          				Fast.api.close();
           			},function(index){
            			layer.close(index);
        				});
				});
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
                                url: "user/department/jstree",
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
                                    var department_id = Controller.api.tree.getChildNodes(treeNode);
                                    if (treeNode.id == '0') {
                                        $(".commonsearch-table input[name=department_id]").val('');
                                    } else {
                                        $(".commonsearch-table input[name=department_id]").val(department_id);
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'user/user/add?department_id=' + treeNode.id
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
                    },
                    thumb: function(value, row, index) {
                        if (!value && typeof value != "undefined" && value != 0) {
                            return '<img src="/assets/addons/training/images/cover.png" alt="" style="max-height:50px;max-width:80px">';
                        } else {
                            return '<a href="' + value + '" target="_blank"><img src="' + value + '" alt="" style="max-height:60px;max-width:80px"></a>';
                        }
                    }
                }
            },
            video: {
                init() {
                    var player = Video('video', {
                        controls: true, // 是否显示控制条
                        autoplay: false,
                        preload: 'metadata', //预加载
                        language: 'zh-CN', // 设置语言
                        muted: false, // 是否静音
                        fluid: true, // 自适应宽高
                    }, function onPlayerReady() {
                        this.on('loadedmetadata', function() { //成功获取资源长度
                            $("#duration").val(this.duration());
                        });
                    });

                    return player;
                },
                preview() {
                    var url = $("#c-videofile").val();
                    if (url !== "" && url) {
                        player.src(url);
                        player.load(url);
                        $("#preview").show();
                    } else {
                        $("#preview").hide();
                        player.pause();
                    }
                }
            },
            bindevent: function() {
                Form.api.bindevent($("form[role=form]"));
            }
        },
        
        api_m: {
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
                            return '<a href="' + value + '" target="_blank"><img src="' + value + '" alt="" style="max-height:90px;max-width:120px"></a>';
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
        api_k: {
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