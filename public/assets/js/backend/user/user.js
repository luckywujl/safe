define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/user/index',
                    add_url: 'user/user/add',
                    edit_url: 'user/user/edit',
                    department_url: 'user/user/edit',
                    group_url: 'user/user/edit',
                    del_url: 'user/user/del',
                    multi_url: 'user/user/multi',
                    import_url: 'user/user/import',
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
                        {field: 'username', title: __('Username'), operate: 'LIKE'},
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
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            //批量修改部门
				$(document).on("click",".btn-batch",function () {
				  var ids = Table.api.selectedids(table);
				    //弹窗显示批量设置内容
         	  Fast.api.open('user/user/batch?ids='+ids,'批量修改',{//?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['60%', '70%'],
		           callback: function (data) {	
		           //alert(data);
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        batch: function () {
        	//设置组别
        	$(document).on("click", ".btn-setgroup", function(){
        		layer.confirm('确定要做这样的批量修改吗?', {btn: ['确定','取消'] }, function(index){
        			layer.close(index);
          			$.post("user/user/batch", {type:1,ids:Config.ids,content:$("#c-group_id").val()},function(response){
            			if(response.code == 1){
                 			Toastr.success(response.msg)
					  			parent.$("a.btn-refresh").trigger("click");
         	     			Fast.api.close();
             			}else{
                  		Toastr.error(response.msg)
             			}
             		}, 'json')
           		},function(index){
            		layer.close(index);
        			});
				});
				
				//设置组别
        	 $(document).on("click", ".btn-setdepartment", function(){
        	 	layer.confirm('确定要做这样的批量修改吗?', {btn: ['确定','取消'] }, function(index){
        			layer.close(index);
          			$.post("user/user/batch", {type:2,ids:Config.ids,content:$("#c-department_id").val()},function(response){
            			if(response.code == 1){
                 			Toastr.success(response.msg)
					  			parent.$("a.btn-refresh").trigger("click");
         	     			Fast.api.close();
             			}else{
                  		Toastr.error(response.msg)
             			}
             		}, 'json')
           		},function(index){
            		layer.close(index);
        			});
				});
				
				//设置状态
        	 $(document).on("click", ".btn-setstatus", function(){
        		//设置组别
   				layer.confirm('确定要做这样的批量修改吗?', {btn: ['确定','取消'] }, function(index){
        				layer.close(index);
          				$.post("user/user/batch", {type:3,ids:Config.ids,content:$('input[name="row[status]"]:checked').val()},function(response){
            				if(response.code == 1){
                 				Toastr.success(response.msg)
					  				parent.$("a.btn-refresh").trigger("click");
         	     				Fast.api.close();
             				}else{
                  			Toastr.error(response.msg)
             				}
             			}, 'json')
           			},function(index){
            			layer.close(index);
        				});
				});
				//关闭退出
        	 $(document).on("click", ".btn-close", function(){
         	Fast.api.close(); 	
			});
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
        }
    };
    return Controller;
});