define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
          	$(".btn-add").data("area",["85%","85%"]);
        		$(".btn-edit").data("area",["85%","85%"]);
        		$(".btn-edit").data("title",'修改');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'user/department/index' + location.search,
                    add_url: 'user/department/add',
                    edit_url: 'user/department/edit',
                    del_url: 'user/department/del',
                    multi_url: 'user/department/multi',
                    import_url: 'user/department/import',
                    table: 'user_department',
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
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'type', title: __('Type'), operate: 'LIKE',searchList: {"企业部门": __('企业部门'), "施工队": __('施工队')}},
                        {field: 'pname', title: __('Pid'),  formatter:function(value, row, index){
									if (value == undefined) {
										return '无';
									} else {
										return value;
									}
								},operate:false},
                        {field: 'name', title: __('Name'), align: 'left', formatter:function (value, row, index) {
                                return value.toString().replace(/(&|&amp;)nbsp;/g, '&nbsp;');
                            }
                        },
                        
                        
                        {field: 'icon', title: __('Icon'), operate: 'LIKE', operate:false,visible:false,formatter: Table.api.formatter.icon},
                        {field: 'keywords', title: __('Keywords'), operate: 'LIKE'},
                        {field: 'description', title: __('Description'), operate: 'LIKE'},
                        {field: 'diyname', title: __('Diyname'), operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', visible:false,autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', visible:false,autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'weigh', title: __('Weigh'), operate: false},
                        {field: 'status', title: __('Status'), operate: 'LIKE',searchList: {"normal1": __('正常'), "hidden": __('隐藏')}, formatter: Table.api.formatter.status},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["85%","85%"]);
            	$(".btn-editone").data("title",'修改');
            })
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
        		//选择部门负责人
				$(document).on("click",".btn-selectleader",function () {
					var ids = '0';
					if ($("#c-leader").val()!=='') {
						ids = $("#c-leader").val();
					}
				    //弹窗显示学员信息
         	  Fast.api.open('user/selectuser/index?ids='+ids,'选择部门负责人',{//?card_code=" + $(this).attr("id") + "&multiple=" + multiple + "&mimetype=" + mimetype, __('Choose'), {
	           area:['98%', '98%'],
		           callback: function (data) {	
		           $("#c-leader").val(data);
		           $("#c-leader").selectPageRefresh();
		           //alert(data);
		           	
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
	         //选择安全员
				$(document).on("click",".btn-selectperson",function () {
					var ids = '0';
					if ($("#c-person").val()!=='') {
						ids = $("#c-person").val();
					}
				    //弹窗显示学员信息
         	  Fast.api.open('user/selectuser/index?ids='+ids,'选择部门安全员',{
	           area:['98%', '98%'],
		           callback: function (data) {	
		           $("#c-person").val(data);
		           $("#c-person").selectPageRefresh();
		           	
	       	    },function (data) {
	       	    	
	       	    }
	            });
	         });
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                
                Form.api.bindevent($("form[role=form]"));
                var iconlist = [];
                var iconfunc = function () {
                    Layer.open({
                        type: 1,
                        area: ['99%', '98%'], //宽高
                        content: Template('chooseicontpl', {iconlist: iconlist})
                    });
                };
                $(document).on('click', ".btn-search-icon", function () {
                    if (iconlist.length == 0) {
                        $.get(Config.site.cdnurl + "/assets/libs/font-awesome/less/variables.less", function (ret) {
                            var exp = /fa-var-(.*):/ig;
                            var result;
                            while ((result = exp.exec(ret)) != null) {
                                iconlist.push(result[1]);
                            }
                            iconfunc();
                        });
                    } else {
                        iconfunc();
                    }
                });
                $(document).on('click', '#chooseicon ul li', function () {
                    $("input[name='row[icon]']").val('fa fa-' + $(this).data("font"));
                    Layer.closeAll();
                });
                $(document).on('keyup', 'input.js-icon-search', function () {
                    $("#chooseicon ul li").show();
                    if ($(this).val() != '') {
                        $("#chooseicon ul li:not([data-font*='" + $(this).val() + "'])").hide();
                    }
                });
            }
        }
    };
    return Controller;
});