define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
        	 	$(".btn-add").data("area",["80%","90%"]);
        		$(".btn-edit").data("area",["90%","90%"]);
        		$(".btn-edit").data("title",'修改');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'auth/admin/index',
                    add_url: 'auth/admin/add',
                    edit_url: 'auth/admin/edit',
                    del_url: 'auth/admin/del',
                    multi_url: 'auth/admin/multi',
                }
            });

            var table = $("#table");

            //在表格内容渲染完成后回调的事件
            table.on('post-body.bs.table', function (e, json) {
                $("tbody tr[data-index]", this).each(function () {
                    if (parseInt($("td:eq(1)", this).text()) == Config.admin.id) {
                        $("input[type=checkbox]", this).prop("disabled", true);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                columns: [
                    [
                        {field: 'state', checkbox: true, },
                        {field: 'id', title: 'ID'},
                        {field: 'username', title: __('Username')},
                        {field: 'nickname', title: __('Nickname')},
                        {field: 'groups_text', title: __('Group'), operate:false, formatter: Table.api.formatter.label},
                        {field: 'email', title: __('Email')},
                        {field: 'status', title: __("Status"), searchList: {"normal":__('Normal'),"hidden":__('Hidden')}, formatter: Table.api.formatter.status},
                        {field: 'logintime', title: __('Login time'), formatter: Table.api.formatter.datetime, operate: 'RANGE', addclass: 'datetimerange', sortable: true},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: function (value, row, index) {
                                if(row.id == Config.admin.id){
                                    return '';
                                }
                                return Table.api.formatter.operate.call(this, value, row, index);
                            }}
                    ]
                ]
            });
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["80%","90%"]);
            	$(".btn-editone").data("title",'修改');
            })

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
        	   //选择部门负责人
				$(document).on("click",".btn-selectadmin",function () {
					var ids = '0';
					
				    //弹窗显示学员信息
         	  Fast.api.open('user/selectuser/selectadmin','选择人员',{   //?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['95%', '95%'],
		           callback: function (data) {	
		           $("#username").val(data[0]['username']);
		           $("#nickname").val(data[0]['nickname']);
		           $("#email").val(data[0]['email']);
		           
		           //alert(data);
		           	
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
            Form.api.bindevent($("form[role=form]"));
        },
        edit: function () {
            Form.api.bindevent($("form[role=form]"));
        }
    };
    return Controller;
});
