define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	   $(".btn-add").data("area",["98%","98%"]);
        		$(".btn-edit").data("area",["98%","98%"]);
        		$(".btn-edit").data("title",'修改');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/trouble/dispatch/index' + location.search,
                    edit_url: 'trouble/trouble/dispatch/edit',
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
                        {field: 'troublepoint.point_code', title: __('Troublepoint.point_code'), operate: 'LIKE'},
                        {field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'firtstduration', title: __('Firtstduration')},
                        //{field: 'finishduration', title: __('Finishduration')},
                        {field: 'source_type', title: __('Source_type'), searchList: {"0":__('Source_type 0'),"1":__('Source_type 1'),"2":__('Source_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        {field: 'trouble_expression', title: __('Trouble_expression'), operate: 'LIKE'},
                        {field: 'description', title: __('Description'), operate: 'LIKE',visible:false},
                        //{field: 'trouble_pic', title: __('Trouble_pic')},
                        //{field: 'process_pic', title: __('Process_pic')},
                        //{field: 'finish_pic', title: __('Finish_pic')},
                        {field: 'main_status', title: __('Main_status'), searchList: {"0":__('Main_status 0'),"1":__('Main_status 1')}, formatter: Table.api.formatter.status},
                        {field: 'informer', title: __('Informer'), operate: 'LIKE'},
                       // {field: 'recevier', title: __('Recevier'), operate: 'LIKE'},
                       // {field: 'processer', title: __('Processer'), operate: 'LIKE'},
                       // {field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        //{field: 'together_id', title: __('Together_id')},
                       // {field: 'together_code', title: __('Together_code'), operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'troublepoint.id', title: __('Troublepoint.id')},
                        
                        //{field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        {field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
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
            	$(".btn-editone").data("area",["98%","98%"]);
            	$(".btn-editone").data("title",'修改');
            })
            $(document).on("click", ".btn-verify", function () {
                //在table外不可以使用添加.btn-change的方法
                //只能自己调用Table.api.multi实现
                var ids = Table.api.selectedids(table);
    								layer.confirm('确定要将选中的隐患信息派单吗?', {btn: ['是','否'] },
       							 function(index){
        					    layer.close(index);
          						  $.post("trouble/trouble/dispatch/verify", {ids:ids , action:'success', reply:''},function(response){
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
            
         $(document).on("click", ".btn-cancelverify", function () {
              //在table外不可以使用添加.btn-change的方法
                //只能自己调用Table.api.multi实现
              var ids = Table.api.selectedids(table);
    			  layer.confirm('确定要将选中的隐患信息取消派单吗?', {btn: ['是','否'] },
       					function(index){
        					   layer.close(index);
          					$.post("trouble/trouble/dispatch/cancelverify", {ids:ids , action:'success', reply:''},function(response){
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
                url: 'trouble/trouble/dispatch/recyclebin' + location.search,
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
                                    url: 'trouble/trouble/dispatch/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'trouble/trouble/dispatch/destroy',
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