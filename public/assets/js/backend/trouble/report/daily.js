define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/report/daily/index' + location.search,
                    add_url: 'trouble/report/daily/add',
                    edit_url: 'trouble/report/daily/edit',
                    del_url: 'trouble/report/daily/del',
                    multi_url: 'trouble/report/daily/multi',
                    import_url: 'trouble/report/daily/import',
                    table: 'trouble_main',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                searchFormVisible:true,
					 search: false, //快速搜索
                searchFormTemplate: 'customformtpl',
                pk: 'id',
                sortName: 'id', 
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        {field: 'main_code', title: __('Main_code'), sortable:true,operate: 'LIKE'},
                        {field: 'source_type', title: __('Source_type'),sortable:true, searchList: {"0":__('Source_type 0'),"1":__('Source_type 1'),"2":__('Source_type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'troubletype.trouble_type', title: __('Troubletype.trouble_type'), sortable:true,operate: 'LIKE'},
                        //{field: 'point_id', title: __('Point_id')},
                        {field: 'troublepoint.point_code', title: __('Troublepoint.point_code'),sortable:true, operate: 'LIKE'},
                        {field: 'troublepoint.point_name', title: __('Troublepoint.point_name'), sortable:true,operate: 'LIKE'},
                        {field: 'createtime', title: __('Createtime'),sortable:true, operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'),sortable:true, operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'finishtime', title: __('Finishtime'),sortable:true, operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'firstduration', title: __('Firstduration'), sortable:true,operate:'BETWEEN'},
                        {field: 'finishduration', title: __('Finishduration'), sortable:true,operate:'BETWEEN'},
                        
                        //{field: 'trouble_type_id', title: __('Trouble_type_id')},
                        {field: 'trouble_expression', title: __('Trouble_expression'), visible:false,operate: 'LIKE'},
                        {field: 'description', title: __('Description'), visible:false, operate: 'LIKE'},
                        {field: 'trouble_pic', title: __('Trouble_pic'), visible:false,formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'process_pic', title: __('Process_pic'), visible:false,formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'finish_pic', title: __('Finish_pic'), visible:false,formatter: Controller.api.table.formatter.thumb, operate: false },
                        {field: 'main_status', title: __('Main_status'), sortable:true,searchList: {"0":__('Main_status 0'),"1":__('Main_status 1'),"2":__('Main_status 2'),"3":__('Main_status 3'),"4":__('Main_status 4'),"5":__('Main_status 5'),"6":__('Main_status 6'),"7":__('Main_status 7'),"8":__('Main_status 8'),"9":__('Main_status 9')}, formatter: Table.api.formatter.status},
                        {field: 'informer_name', visible:false,title: __('Informer_name'), operate: 'LIKE'},
                        //{field: 'informer', title: __('Informer'), operate: 'LIKE'},
                        {field: 'recevier', visible:false, title: __('Recevier'), operate: 'LIKE'},
                        //{field: 'liabler', title: __('Liabler'), operate: 'LIKE'},
                        //{field: 'processer', title: __('Processer'), operate: 'LIKE'},
                        //{field: 'checker', title: __('Checker'), operate: 'LIKE'},
                        //{field: 'insider', title: __('Insider'), operate: 'LIKE'},
                        //{field: 'together_id', title: __('Together_id')},
                        {field: 'together_code', title: __('Together_code'), operate: 'LIKE'},
                        {field: 'remark', title: __('Remark'), visible:false,operate: 'LIKE'},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'troublepoint.id', title: __('Troublepoint.id')},
                        
                        //{field: 'troublepoint.point_description', title: __('Troublepoint.point_description'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_address', title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'troublepoint.point_address', visible:false,title: __('Troublepoint.point_address'), operate: 'LIKE'},
                        //{field: 'troublepoint.point_position', title: __('Troublepoint.point_position'), operate: 'LIKE'},
                        {field: 'department_name', title: __('Troublepoint.point_department_id')},
                        {field: 'department_pname', title: __('上级部门')},
                        {field: 'area_name', title: __('Troublepoint.point_area_id')},
                        //{field: 'troublepoint.company_id', title: __('Troublepoint.company_id'), operate: 'LIKE'},
                        //{field: 'troubletype.id', title: __('Troubletype.id')},
                        
                        //{field: 'troubletype.plan_content', title: __('Troubletype.plan_content'), operate: 'LIKE'},
                        //{field: 'troubletype.company_id', title: __('Troubletype.company_id'), operate: 'LIKE'},
                        //{field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
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
                url: 'trouble/report/daily/recyclebin' + location.search,
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
                                    url: 'trouble/report/daily/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'trouble/report/daily/destroy',
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