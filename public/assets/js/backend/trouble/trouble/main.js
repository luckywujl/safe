define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'template'], function($, undefined, Backend, Table, Form, Template) {

    var Controller = {
        index: function () {
         	
        		$(".btn-edit").data("area",["98%","98%"]);
        		$(".btn-edit").data("title",'查看隐患详情');
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/trouble/main/index' + location.search,
                    
                    edit_url: 'trouble/trouble/main/edit',
                  
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
                        {field: 'troublepoint.point_name', title: __('Point_id')},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'finishtime', title: __('Finishtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'firstduration', title: __('Firstduration'),formatter:function (value,row,index) {
                        		return value;
                        }},
                        {field: 'finishduration', title: __('Finishduration'),formatter:function (value,row,index) {
                        		return value;
                        }},
                        
                        {field: 'troubletype.trouble_type', title: __('Trouble_type_id')},
                        {field: 'trouble_expression', title: __('Trouble_expression'), visible:false,operate: 'LIKE'},
                        {field: 'description', title: __('Description'),visible:false, operate: 'LIKE'},
                        {field: 'trouble_pic', title: __('Trouble_pic'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'process_pic', title: __('Process_pic'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'finish_pic', title: __('Finish_pic'), formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'main_status', title: __('Main_status'), searchList: {"0":__('Main_status 0'),"1":__('Main_status 1'),"2":__('Main_status 2'),"3":__('Main_status 3'),"4":__('Main_status 4'),"5":__('Main_status 5'),"6":__('Main_status 6'),"7":__('Main_status 7'),"8":__('Main_status 8'),"9":__('Main_status 9')}, formatter: Table.api.formatter.status},
                        {field: 'informer_name', title: __('Informer'), operate: 'LIKE'},
                        {field: 'recevier', title: __('Recevier'), operate: 'LIKE'},
                        //{field: 'processer', title: __('Processer'), operate: 'LIKE'},
                        //{field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'department_name', title: __('Troublepoint.point_department_id')},
                        {field: 'department_pname', title: __('上级部门')},
                        {field: 'area_name', title: __('Troublepoint.point_area_id')},
                        {field: 'together_id', title: __('Together_id'),visible:false},
                        {field: 'together_code', title: __('Together_code'), visible:false, operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'troublepoint.id', title: __('Troublepoint.id')},
                        //{field: 'troublepoint.point_code', title: __('Troublepoint.point_code'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_department_id', title: __('Troublepoint.point_department_id')},
                        //{field: 'troublepoint.point_area_id', title: __('Troublepoint.point_area_id')},
                        //{field: 'troublepoint.company_id', title: __('Troublepoint.company_id'), operate: 'LIKE'},
                        //{field: 'troubletype.id', title: __('Troubletype.id')},
                        //{field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), operate: 'LIKE'},
                        //{field: 'troubletype.plan_id', title: __('Troubletype.plan_id')},
                        //{field: 'troubletype.company_id', title: __('Troubletype.company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });
            table.on('post-body.bs.table',function () {
            	$(".btn-editone").data("area",["90%","90%"]);
            	$(".btn-editone").data("title",'查看隐患详情');
            })

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
                        	var pics  = value.split(",");//将字段值数组化
                        	var str ='';
                        	for(j=0;j<pics.length;j++){
                        	   if (str==''){
                        	   	str= '<a href="' + pics[j] + '" target="_blank"><img src="' + pics[j] + '" alt="" style="max-height:60px;max-width:80px"></a>';
                        	   } else{
                        	   	str=str+'&#32;'+'<a href="' + pics[j] + '" target="_blank"><img src="' + pics[j] + '" alt="" style="max-height:60px;max-width:80px"></a>';
                        	   }
                        	}
                            return str;//'<a href="' + value + '" target="_blank"><img src="' + value + '" alt="" style="max-height:60px;max-width:80px"></a>&#32;<a href="' + value + '" target="_blank"><img src="' + value + '" alt="" style="max-height:60px;max-width:80px"></a>'; 
                        }
                    },
                }
            },
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});