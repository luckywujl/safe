define(['jquery', 'bootstrap', 'backend', 'table', 'form','selectpage'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	$(".btn-add").data("area",["90%","90%"]);
        		$(".btn-edit").data("area",["90%","90%"]);
        		$(".btn-edit").data("title",'修改');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/trouble/recevie/index' + location.search,
                    add_url: 'trouble/trouble/recevie/add',
                    edit_url: 'trouble/trouble/recevie/edit',
                    del_url: 'trouble/trouble/recevie/del',
                    multi_url: 'trouble/trouble/recevie/multi',
                    import_url: 'trouble/trouble/recevie/import',
                    table: 'trouble_main',
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
                        
                        {field: 'main_code', title: __('Main_code'), operate: 'LIKE'},
                        
                        {field: 'source_type', title: __('Source_type'), searchList: {"0":__('Source_type 0'),"1":__('Source_type 1'),"2":__('Source_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'informer_name', title: __('Informer'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'troublepoint.point_code', title: __('Troublepoint.point_code'), operate: 'LIKE'},
                        {field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), operate: 'LIKE'},
                        
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'firtstduration', title: __('Firtstduration')},
                        //{field: 'finishduration', title: __('Finishduration')},
                        
                        {field: 'kind', title: __('Kind'), operate: 'LIKE'},
                        {field: 'type', title: __('Type'), operate: 'LIKE'},
                        {field: 'expression', title: __('Expression'), operate: 'LIKE'},
                        {field: 'troublelevel.trouble_level', title: __('Troublelevel.trouble_level'), sortable:true,operate: 'LIKE'},
                        {field: 'description', title: __('Description'), operate: 'LIKE',visible:false},
                        //{field: 'trouble_pic', title: __('Trouble_pic')},
                        //{field: 'process_pic', title: __('Process_pic')},
                        //{field: 'finish_pic', title: __('Finish_pic')},
                        {field: 'main_status', title: __('Main_status'), searchList: {"0":__('Main_status 0'),"1":__('Main_status 1'),"2":__('Main_status 2'),"3":__('Main_status 3'),"4":__('Main_status 4'),"5":__('Main_status 5'),"6":__('Main_status 6'),"7":__('Main_status 7'),"9":__('Main_status 9')}, formatter: Table.api.formatter.status},
                        
                       // {field: 'recevier', title: __('Recevier'), operate: 'LIKE'},
                       // {field: 'processer', title: __('Processer'), operate: 'LIKE'},
                       // {field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        //{field: 'together_id', title: __('Together_id')},
                       // {field: 'together_code', title: __('Together_code'), operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'troublepoint.id', title: __('Troublepoint.id')},
                        
                        //{field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'department_name', title: __('Troublepoint.point_department_id')},
                        {field: 'department_pname', title: __('上级部门')},
                        {field: 'area_name', title: __('Troublepoint.point_area_id')},
                        //{field: 'troublepoint.company_id', title: __('Troublepoint.company_id'), operate: 'LIKE'},
                        //{field: 'troubletype.id', title: __('Troubletype.id')},
                        //{field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        //{field: 'troubletype.plan_id', title: __('Troubletype.plan_id')},
                        //{field: 'troubletype.company_id', title: __('Troubletype.company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["90%","90%"]);
            	$(".btn-editone").data("title",'修改');
            })
            //接警
            $(document).on("click", ".btn-verify", function () {
                var ids = Table.api.selectedids(table);
    				layer.confirm('确定要将选中的隐患信息进行接警操作吗?', {btn: ['是','否'] },
       			    function(index){
        			layer.close(index);
                    $.ajax({
                        url:"trouble/trouble/recevie/verify", 
                        type: 'post',  
						dataType: 'json',
                        data:{ids:ids },
                        success:function(ret){
                            $(".btn-refresh").trigger('click');	
                            Toastr.success(ret.msg);
                            //alert(ret.data);
							$.ajax({
								url: "trouble/trouble/recevie/send_t",
								type: 'post',
								dataType: 'json',
                                // async:true,
							    data: {data:ret.data},
								success: function (ret) {
								}, error: function (e) {
									
								}
							});	
                    
                },error:function(e) {
                    Toastr.error(ret.msg)
                }
            });
          
             },
        function(index){
            layer.close(index);
        }
          );
            });
         //取消报警   
         $(document).on("click", ".btn-cancelverify", function () {
              //在table外不可以使用添加.btn-change的方法
                //只能自己调用Table.api.multi实现
              var ids = Table.api.selectedids(table);
    			  layer.confirm('确定要将选中的隐患信息取消接警吗?', {btn: ['是','否'] },
       					function(index){
        					   layer.close(index);
          					$.post("trouble/trouble/recevie/cancelverify", {ids:ids , action:'success', reply:''},function(response){
             					if(response.code == 1){
                 	   			Toastr.success(response.msg)
                    				$(".btn-refresh").trigger('click');
                				}else{
                    				Toastr.error(response.msg)
                				}
            				}, 'json')
        					},
        			function(index){
            		layer.close(index);
       			}			
    			);
            });
            //并案处理
        $(document).on("click", ".btn-together", function () {
                temp=table.bootstrapTable('getSelections');
    				 Fast.api.open('trouble/trouble/recevie/together?point_id='+temp[0]['point_id']+'&code='+temp[0]['main_code']+'&id='+temp[0]['id']+'&informer='+temp[0]['informer'],'选择并入的警情信息',{
	             area:['80%', '80%'],
		           callback: function (data) {	
		           
	       	    },function (data) {
	       	    	
	       	    }
	            });
            });
            Controller.api.bindevent();
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
                url: 'trouble/trouble/recevie/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        { checkbox: true },
                        { field: 'main_code', title: __('Main_code') },
                        { field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), align: 'left' },
                        {
                            field: 'updatetime',
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
                                    url: 'trouble/trouble/recevie/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'trouble/trouble/recevie/destroy',
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
            parent.window.$(".layui-layer-iframe").find(".layui-layer-close").on('click',function () {
  				   parent.$("a.btn-refresh").trigger("click");
			 });
			 
        },
        together: function () {
        	
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/trouble/recevie/together' + location.search,
                    
                    table: 'trouble_main',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                showToggle: false,
                showColumns: false,
                clickToSelect: true, //是否启用点击选中
    				 singleSelect: true, //是否启用单选
                search:false,
                commonSearch:false,
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        
                        {field: 'main_code', title: __('Main_code'), operate: 'LIKE'},
                        {field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        {field: 'source_type', title: __('Source_type'), searchList: {"0":__('Source_type 0'),"1":__('Source_type 1'),"2":__('Source_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'informer_name', title: __('Informer'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'troublepoint.point_code', title: __('Troublepoint.point_code'), operate: 'LIKE'},
                        {field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), operate: 'LIKE'},
                        
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'firtstduration', title: __('Firtstduration')},
                        //{field: 'finishduration', title: __('Finishduration')},
                        
                        
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        {field: 'description', title: __('Description'), operate: 'LIKE',visible:false},
                        //{field: 'trouble_pic', title: __('Trouble_pic')},
                        //{field: 'process_pic', title: __('Process_pic')},
                        //{field: 'finish_pic', title: __('Finish_pic')},
                        {field: 'main_status', title: __('Main_status'), searchList: {"0":__('Main_status 0'),"1":__('Main_status 1'),"2":__('Main_status 2'),"3":__('Main_status 3'),"4":__('Main_status 4'),"5":__('Main_status 5'),"6":__('Main_status 6'),"7":__('Main_status 7'),"9":__('Main_status 9')}, formatter: Table.api.formatter.status},
                        
                       // {field: 'recevier', title: __('Recevier'), operate: 'LIKE'},
                       // {field: 'processer', title: __('Processer'), operate: 'LIKE'},
                       // {field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        //{field: 'together_id', title: __('Together_id')},
                       // {field: 'together_code', title: __('Together_code'), operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'troublepoint.id', title: __('Troublepoint.id')},
                        
                        //{field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'department_name', title: __('Troublepoint.point_department_id')},
                        {field: 'department_pname', title: __('上级部门')},
                        {field: 'area_name', title: __('Troublepoint.point_area_id')},
                        //{field: 'troublepoint.company_id', title: __('Troublepoint.company_id'), operate: 'LIKE'},
                        //{field: 'troubletype.id', title: __('Troubletype.id')},
                        //{field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        //{field: 'troubletype.plan_id', title: __('Troubletype.plan_id')},
                        //{field: 'troubletype.company_id', title: __('Troubletype.company_id'), operate: 'LIKE'},
                        
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            //执行并案
	         $(document).on("click", ".btn-together", function () {
                var ids = Table.api.selectedids(table);
    					layer.confirm('确定将隐患告警信息并入此条警情?', {btn: ['是','否'] },
       				function(index){
        					layer.close(index);
          				$.post("trouble/trouble/recevie/dotogether", {ids:ids,maincode:Config.maincode,mainid:Config.mainid,informer:Config.informer,action:'success', reply:''},function(response){
             			  if(response.code == 1){
                 	     		parent.Toastr.success(response.msg)
                       		parent.$(".btn-refresh").trigger('click');
                       		Fast.api.close();
                    		}else{
                      		Toastr.error(response.msg)
                    		}
                  	}, 'json')
             		},
        				function(index){
            			layer.close(index);
        				}
          			);
            });
            Controller.api.bindevent();
        },
        add: function () {
        		//选择隐患点
				$(document).on("click",".btn-selectpoint",function () {
				    //弹窗显示学员信息
         	  Fast.api.open('trouble/base/point/getpoint','选择隐患点',{
	           area:['98%', '98%'],
		           callback: function (data) {	
		           $("#c-point_id").val(data);
		           $("#c-point_id").selectPageRefresh();
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         //选择常用隐患现象
				$(document).on("click",".btn-selectexpression",function () {
				    //弹窗显示学员信息
         	  Fast.api.open('trouble/base/expression/getexpression','选择隐患现象',{
	           area:['50%', '80%'],
		           callback: function (data) {	
		           if ($("#c-trouble_expression").val()=='') {
		           		$("#c-trouble_expression").val(data[0].name);
		           }else {
		           		$("#c-trouble_expression").val($("#c-trouble_expression").val()+'/'+data[0].name);
		           }
		           
		           
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
            Controller.api.bindevent();
        },
        edit: function () {
        	//选择隐患点
				$(document).on("click",".btn-selectpoint",function () {
				    //弹窗显示学员信息
         	  Fast.api.open('trouble/base/point/getpoint','选择隐患点',{
	           area:['98%', '98%'],
		           callback: function (data) {	
		           $("#c-point_id").val(data);
		           $("#c-point_id").selectPageRefresh();
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         //选择常用隐患现象
				$(document).on("click",".btn-selectexpression",function () {
				    //弹窗显示学员信息
         	  Fast.api.open('trouble/base/expression/getexpression','选择隐患现象',{
	           area:['50%', '80%'],
		           callback: function (data) {	
		           if ($("#c-trouble_expression").val()=='') {
		           		$("#c-trouble_expression").val(data[0].trouble_expression);
		           }else {
		           		$("#c-trouble_expression").val($("#c-trouble_expression").val()+'/'+data[0].trouble_expression);
		           }
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         //在编辑介面接警
	         $(document).on("click", ".btn-ver", function () {
	         	var ids = $("#c-id").val();
                //在table外不可以使用添加.btn-change的方法
                //只能自己调用Table.api.multi实现
                //var ids = Table.api.selectedids(table);
    								layer.confirm('确定对该条隐患告警信息进行接警接操作?', {btn: ['是','否'] },
       							 function(index){
        					    layer.close(index);
          						  $.post("trouble/trouble/recevie/verify", {ids:ids , action:'success', reply:''},function(response){
             				   if(response.code == 1){
                 	   Toastr.success(response.msg)
                     parent.$(".btn-refresh").trigger('click');
                     Fast.api.close();
                }else{
                    Toastr.error(response.msg)
                }
            }, 'json')
             },
        function(index){
            layer.close(index);
        }
          );
            });
            //在编辑介面下取消接警
            $(document).on("click", ".btn-cancelver", function () {
              //在table外不可以使用添加.btn-change的方法
                //只能自己调用Table.api.multi实现
              var ids = $("#c-id").val();
    			  layer.confirm('确定要将该条隐患信息取消接警吗?', {btn: ['是','否'] },
       					function(index){
        					   layer.close(index);
          					$.post("trouble/trouble/recevie/cancelverify", {ids:ids , action:'success', reply:''},function(response){
             					if(response.code == 1){
                 	   			Toastr.success(response.msg)
                    				parent.$(".btn-refresh").trigger('click');
                    				Fast.api.close();
                				}else{
                    				Toastr.error(response.msg)
                				}
            				}, 'json')
        					},
        			function(index){
            		layer.close(index);
       			}			
    			);
            });
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});