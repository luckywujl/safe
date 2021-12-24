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
        index:function () {
        		
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
            Controller.api.tree.init(table);
            
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
        	 	//parent.$("#c-user_ids").val($("#c-user_ids").val());
        	 	Fast.api.close($("#c-user_ids").val());//将选中的值返回给调用者
        	 });
        	  parent.window.$(".layui-layer-iframe").find(".layui-layer-close").on('click',function () {
  				 //parent.$("#c-user_ids").val($("#c-user_ids").val());
        	 	Fast.api.close($("#c-user_ids").val());//将选中的值返回给调用者   
			   });
           
        },
        selectadmin:function () {
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
                clickToSelect: true, //是否启用点击选中
    				 singleSelect: true, //是否启用单选
                search:false,
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id'), sortable: true,operate:false,visible:false,},
                        {field: 'jobnumber', title: __('Jobnumber'),operate: 'LIKE',sortable: true},
                        {field: 'nickname', title: __('Nickname'), operate: 'LIKE'},
                        {field: 'username', title: __('Username'),operate: 'LIKE',sortable: true},
                        {field: 'email', title: __('Email'),operate: 'LIKE',sortable: true},
                        {field: 'department_id', title: __('Department_id'),visible:false,operate: 'in'},
                        {field: 'department.name', title: __('Department_id'),operate: 'LIKE'},
                        
                        
                    ]
                ]
            });
            

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            
            parent.window.$(".layui-layer-iframe").find(".layui-layer-close").on('click',function () {
  				 var  temp=table.bootstrapTable('getSelections');
   				Fast.api.close(temp); //往父窗口回调参数           
			   });
			   //保存
        	 $(document).on("click",".btn-save",function () {
        	 	var  temp=table.bootstrapTable('getSelections');
   				Fast.api.close(temp); //往父窗口回调参数  
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