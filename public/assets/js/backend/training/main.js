define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function($, undefined, Backend, Table, Form, Template) {
    var zTreeObj=null;
     $('input:radio[name="row[type]"]').click(function(){
     	  
        var type = $(this).val();
        if(type == 'offline'){
            $('.user-select').removeClass('hide');
        }else{
            $('.user-select').addClass('hide');
        }
    })
    var Controller = {
        index: function() {
        		$(".btn-add").data("area",["98%","98%"]);
        		$(".btn-edit").data("area",["98%","98%"]);
        		$(".btn-edit").data("title",'修改');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'training/main/index' + location.search,
                    add_url: 'training/main/add',
                    edit_url: 'training/main/edit',
                    del_url: 'training/main/del',
                    multi_url: 'training/main/multi',
                    import_url: 'training/main/import',
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
                        { field: 'id', title: __('Id'),visible:false },
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'training_course_ids', title: __('Training_course_ids'), visible: false, operate: false, visible: false },
                        { field: 'duration', title: __('Duration'), operate: false, formatter: Controller.api.table.formatter.duration },
                        { field: 'coverimage', title: __('Coverimage'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        { field: 'type', title: __('Type'), operate: 'LIKE',formatter: Table.api.formatter.status ,searchList: {online: __('Online'), offline: __('Offline')}},
                        { field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        { field: 'user_ids', title: __('User_ids'), operate: false, visible: false },
                        { field: 'user_group_ids', title: __('User_group_ids'), operate: false, visible: false },
                        { field: 'admin_id', title: __('Admin_id'), operate: false },
                        { field: 'starttime', title: __('Starttime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'endtime', title: __('Endtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'weigh', title: __('Weigh'),visible:false, operate: false },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["98%","98%"]);
            	$(".btn-editone").data("title",'修改');
            })
            Controller.api.tree.init(table);
        },
        recyclebin: function() {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    'dragsort_url': ''
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: 'training/main/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'id', title: __('Id') },
                        { field: 'name', title: __('Name'), align: 'left' },
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
                            buttons: [{
                                    name: 'Restore',
                                    text: __('Restore'),
                                    classname: 'btn btn-xs btn-info btn-ajax btn-restoreit',
                                    icon: 'fa fa-rotate-left',
                                    url: 'training/main/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'training/main/destroy',
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
        add: function() {
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
            Template.helper("s_to_hs", function(s) {
                var h;
                h = Math.floor(s / 60);
                s = s % 60;
                h += '';
                s += '';
                h = (h.length == 1) ? '0' + h : h;
                s = (s.length == 1) ? '0' + s : s;
                return h + ':' + s;
            });
            Controller.api.events.chooseCourse();
            Controller.api.bindevent();
        },
        edit: function() {
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
            Template.helper("s_to_hs", function(s) {
                var h;
                h = Math.floor(s / 60);
                s = s % 60;
                h += '';
                s += '';
                h = (h.length == 1) ? '0' + h : h;
                s = (s.length == 1) ? '0' + s : s;
                return h + ':' + s;
            });
            Controller.api.events.loadCourse();
            Controller.api.events.chooseCourse();
            Controller.api.bindevent();
        },
        selectuser:function () {
        		
        		/**
 				* 数组相减的方法
 				* @param {Array} a
 				* @param {Array} b
 				*/
				function arrSubtraction(arr1,arr2) {
  					for(var i = arr1.length-1 ; i >= 0 ; i-- ){
    				a = arr1[i];
    				for( var j = arr2.length - 1 ; j >=0 ; j --){
        				b = arr2[j];
        				if( a == b){
            				arr1.splice(i,1);
            				arr2.splice(j,1);
            				break;
        				}
    				}	
    			}
	         	 return arr1;
				}
        	
        	 
        	 
			
        	// 初始化表格参数配置
            Table.api.init();

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
            	 url: 'user/user/index' + location.search,
                extend:{
                	  index_url: 'user/user/index',
                    table: 'user',
                
                },
                toolbar: '#toolbar',
                pk: 'id',
                sortName: 'user.id',
                showToggle: false,
                showColumns: false,
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true,operate:'NOT IN',visible:false,defaultValue:$('#c-user_ids').val()},
                        {field: 'jobnumber', title: __('Jobnumber'),operate: 'LIKE',sortable: true},
                        {field: 'department_id', title: __('Department_id'),visible:false,operate: 'in'},
                        {field: 'department.name', title: __('Department_id'),operate: 'LIKE'},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, 
													buttons: [
 												  		 {
 												  		 	name: 'adduser', 
 												  		 	text: '加入', 
 												  		 	title: '加入', 
 												  		 	icon: 'fa fa-list', 
 												  		 	classname: 'btn btn-xs btn-default btn-click',
                        	       				click: function(btn_operate, rowdata, btn_self){
                        	        						
                        	        						if ($('#c-user_ids').val()=='0'){
        	 															$('#c-user_ids').val(rowdata.id);
        	 														} else {
        	 															$('#c-user_ids').val($("#c-user_ids").val()+','+rowdata.id);
        	 														}
        	 														$(".commonsearch-table input[name=id]").val($('#c-user_ids').val());
        	 														$("#refresh").trigger("click");
        	 														$("#refresh1").trigger("click");
                        	      					},
 												  		 }
														],formatter: Table.api.formatter.operate} ,
                    ]
                ]
            });
            

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api_u.tree.init(table);
            
            Table.api.init();
            var table1 = $("#table1");

            // 初始化表格
            table1.bootstrapTable({
                url: 'user/user/getuser' + location.search,
                extend:{
                	  index_url: 'user/user/getuser',
                    table: 'user',
                
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
                        {field: 'id', title: __('Id'), sortable: true,operate:'in',visible:false,defaultValue:$('#c-user_ids').val()},
                        {field: 'user_jobnumber', title: __('Jobnumber'),operate: 'LIKE',sortable: true},
                        {field: 'user_department_id', title: __('Department_id'),visible:false,operate: 'in'},
                        {field: 'department.name', title: __('Department_id'),operate: 'LIKE'},
                        {field: 'user_nickname', title: __('Nickname'), operate: 'LIKE'},
                        
                    ]
                ]
            });
            

            // 为表格绑定事件
            Table.api.bindevent(table1);
            //Controller.api_u.tree.init(table1);
           //加入人员
        	 $(document).on("click",".btn-adduser",function () {
        	 	var ids = Table.api.selectedids(table);
        	 	if ($('#c-user_ids').val()=='0'){
        	 		$('#c-user_ids').val(ids);
        	 	} else {
        	 		$('#c-user_ids').val($("#c-user_ids").val()+','+ids);
        	 	}
        	 	
        	 	$(".commonsearch-table input[name=id]").val($('#c-user_ids').val());
        	 	$("#refresh").trigger("click");
        	 	$("#refresh1").trigger("click");
        	 });
        	 //删减人员
        	 $(document).on("click",".btn-deluser",function () {
        	 	var ids = Table.api.selectedids(table1);//获取选中的IDS
        	 	var userids  = $('#c-user_ids').val().split(",");//将输入框带过来的IDS数组化
        	 	if (arrSubtraction(userids,ids).length > 0) {
        	 		$('#c-user_ids').val(arrSubtraction(userids,ids));//数组运算相减
        	 	}else {
        	 		$('#c-user_ids').val('0');//数组运算相减
        	 	}
        	 	
        	 	$(".commonsearch-table input[name=id]").val($('#c-user_ids').val());
        	 	$("#refresh").trigger("click");
        	 	$("#refresh1").trigger("click");
        	 });
        	 //保存
        	 $(document).on("click",".btn-save",function () {
        	 	parent.$("#c-user_ids").val($("#c-user_ids").val());
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