define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/base/point/index' + location.search,
                    add_url: 'trouble/base/point/add',
                    edit_url: 'trouble/base/point/edit',
                    del_url: 'trouble/base/point/del',
                    multi_url: 'trouble/base/point/multi',
                    import_url: 'trouble/base/point/import',
                    table: 'trouble_point',
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
                        //{field: 'id', title: __('Id'),visable:false,operate:false},
                        {field: 'point_code', title: __('Point_code'), operate: 'LIKE'},
                        {field: 'point_name', title: __('Point_name'), operate: 'LIKE'},
                        {field: 'point_description', title: __('Point_description'), operate: 'LIKE'},
                        {field: 'point_address', title: __('Point_address'), operate: 'LIKE'},
                        {field: 'point_position', title: __('Point_position'), operate: 'LIKE'},
                        {field: 'point_department_id', title: __('Point_department_id'),visible:false,operate: 'in'},
                        //{field: 'point_area_id', title: __('Point_area_id')},
                        
                        
                        {field: 'troublearea.area_name', title: __('Point_area_id'), operate: false},
                        //{field: 'troublearea.area_description', title: __('Troublearea.area_description'), operate: 'LIKE'},
                        
                        //{field: 'userdepartment.pid', title: __('Userdepartment.pid')},
                        //{field: 'userdepartment.pname', title: __('Userdepartment.pname'), operate: 'LIKE'},
                       // {field: 'userdepartment.type', title: __('Userdepartment.type'), operate: 'LIKE'},
                        {field: 'userdepartment.name', title: __('Point_department_id'), operate: false},
                        //{field: 'userdepartment.icon', title: __('Userdepartment.icon'), operate: 'LIKE', formatter: Table.api.formatter.icon},
                        //{field: 'userdepartment.keywords', title: __('Userdepartment.keywords'), operate: 'LIKE'},
                        //{field: 'userdepartment.description', title: __('Userdepartment.description'), operate: 'LIKE'},
                        //{field: 'userdepartment.diyname', title: __('Userdepartment.diyname'), operate: 'LIKE'},
                        //{field: 'userdepartment.leader', title: __('Userdepartment.leader'), operate: 'LIKE'},
                        //{field: 'userdepartment.person', title: __('Userdepartment.person'), operate: 'LIKE'},
                        //{field: 'userdepartment.createtime', title: __('Userdepartment.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.updatetime', title: __('Userdepartment.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.weigh', title: __('Userdepartment.weigh')},
                        //{field: 'userdepartment.status', title: __('Userdepartment.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        //{field: 'userdepartment.company_id', title: __('Userdepartment.company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
        },
        getpoint: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'trouble/base/point/getpoint' + location.search,
                    
                    table: 'trouble_point',
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
                        //{field: 'id', title: __('Id'),visable:false,operate:false},
                        {field: 'point_code', title: __('Point_code'), operate: 'LIKE'},
                        {field: 'point_name', title: __('Point_name'), operate: 'LIKE'},
                        {field: 'point_description', title: __('Point_description'), operate: 'LIKE'},
                        {field: 'point_address', title: __('Point_address'), operate: 'LIKE'},
                        {field: 'point_position', title: __('Point_position'), operate: 'LIKE'},
                        {field: 'point_department_id', title: __('Point_department_id'),visible:false,operate: 'in'},
                        //{field: 'point_area_id', title: __('Point_area_id')},
                        
                        
                        {field: 'troublearea.area_name', title: __('Point_area_id'), operate: false},
                        //{field: 'troublearea.area_description', title: __('Troublearea.area_description'), operate: 'LIKE'},
                        
                        //{field: 'userdepartment.pid', title: __('Userdepartment.pid')},
                        //{field: 'userdepartment.pname', title: __('Userdepartment.pname'), operate: 'LIKE'},
                       // {field: 'userdepartment.type', title: __('Userdepartment.type'), operate: 'LIKE'},
                        {field: 'userdepartment.name', title: __('Point_department_id'), operate: false},
                        //{field: 'userdepartment.icon', title: __('Userdepartment.icon'), operate: 'LIKE', formatter: Table.api.formatter.icon},
                        //{field: 'userdepartment.keywords', title: __('Userdepartment.keywords'), operate: 'LIKE'},
                        //{field: 'userdepartment.description', title: __('Userdepartment.description'), operate: 'LIKE'},
                        //{field: 'userdepartment.diyname', title: __('Userdepartment.diyname'), operate: 'LIKE'},
                        //{field: 'userdepartment.leader', title: __('Userdepartment.leader'), operate: 'LIKE'},
                        //{field: 'userdepartment.person', title: __('Userdepartment.person'), operate: 'LIKE'},
                        //{field: 'userdepartment.createtime', title: __('Userdepartment.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.updatetime', title: __('Userdepartment.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'userdepartment.weigh', title: __('Userdepartment.weigh')},
                        //{field: 'userdepartment.status', title: __('Userdepartment.status'), operate: 'LIKE', formatter: Table.api.formatter.status},
                        //{field: 'userdepartment.company_id', title: __('Userdepartment.company_id'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            //保存
        	 $(document).on("click",".btn-save",function () {
        	 	var ids = Table.api.selectedids(table);
        	 	Fast.api.close(ids);//将选中的值返回给调用者
        	 });

        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
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
                                        $(".commonsearch-table input[name=point_department_id]").val('');
                                    } else {
                                        $(".commonsearch-table input[name=point_department_id]").val(department_id);
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'trouble/base/point/add?department_id=' + treeNode.id
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
        }
    };
    return Controller;
});