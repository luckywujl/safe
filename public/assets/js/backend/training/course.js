define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'video'], function($, undefined, Backend, Table, Form, Video) {
    var player = null;
    var Controller = {
        index: function() {
            
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'training/course/index' + location.search,
                    add_url: 'training/course/add',
                    edit_url: 'training/course/edit',
                    del_url: 'training/course/del',
                    multi_url: 'training/course/multi',
                    import_url: 'training/course/import',
                    table: 'training_course',
                }
            });

            var table = $("#table");
            
            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'weigh',
                singleSelect: true, //是否启用单选
                columns: [
                    [
                        { checkbox: true },
                        //{ field: 'id', title: __('Id') },
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'videofile', title: __('Videofile'), operate: false, formatter: Controller.api.table.formatter.files },
                        { field: 'duration', title: __('Duration') ,operate: false,formatter: Controller.api.table.formatter.duration},
                        { field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        { field: 'speaker', title: __('Speaker'), operate: 'LIKE' },
                        { field: 'playtimes', title: __('Playtimes') ,operate: false},
                        { field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'weigh', title: __('Weigh'), operate: false },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                        { field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate }
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
        },
        select: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'training/course/select',
                }
            });
            var idArr = [];
            var selectArr = [];
            var table = $("#table");
            table.on('post-body.bs.table',function () {
                var row = table.bootstrapTable('getSelections');
                selectArr = row;
                var choosed = Controller.api.getQueryVariable("choose_id");
                if(choosed && choosed !== ""){
                    //需要转换为数字
                    idArr = choosed.split(",");
                    $.each(idArr, function(i, v) {
                        idArr[i] = Number(v)
                    });
                }

            })
            table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function (e, row) {
                if (e.type == 'check' || e.type == 'uncheck') {
                    row = [row];
                } else {
                    idArr = [];
                    selectArr = [];
                }
                $.each(row, function (i, j) {
                    if (e.type.indexOf("uncheck") > -1) {
                        var index = selectArr.indexOf(j);
                        if (index > -1) {
                            selectArr.splice(index, 1);
                        }
                        var index2 = idArr.indexOf(j.id);
                        if (index2 > -1) {
                            idArr.splice(index2, 1);
                        }
                    } else {
                        selectArr.indexOf(j) == -1 && selectArr.push(j);
                        idArr.indexOf(j.id) == -1 && idArr.push(j.id);
                    }
                });
            });

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                sortName: 'id',
                showToggle: false,
                showExport: false,
                maintainSelected: true,
                columns: [
                    [
                        {field: 'state', checkbox: true, visible: true, operate: false,formatter : Controller.api.table.formatter.checkbox},
                        //{field: 'id', title: __('Id')},
                        
                        { field: 'training_category_id', title: __('Category'), operate: 'in', visible: false },
                        { field: 'name', title: __('Name'), operate: 'LIKE' },
                        { field: 'videofile', title: __('Videofile'), operate: false, formatter: Controller.api.table.formatter.files },
                        { field: 'duration', title: __('Duration') ,operate: false,formatter: Controller.api.table.formatter.duration},
                        { field: 'keywords', title: __('Keywords'), operate: 'LIKE' },
                        { field: 'speaker', title: __('Speaker'), operate: 'LIKE' },
                        { field: 'playtimes', title: __('Playtimes') ,operate: false},
                        { field: 'createtime', title: __('Createtime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        { field: 'updatetime', title: __('Updatetime'), operate: 'RANGE', addclass: 'datetimerange', autocomplete: false, formatter: Table.api.formatter.datetime },
                        //{ field: 'weigh', title: __('Weigh'), operate: false },
                        { field: 'status', title: __('Status'), operate: false, formatter: Table.api.formatter.status },
                    ]
                ]
            });

            // 选中多个
            $(document).on("click", ".btn-choose-multi", function () {
                Fast.api.close({id: idArr.join(","),item: selectArr});
            });

            // 为表格绑定事件
            Table.api.bindevent(table);

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
                url: 'training/course/recyclebin' + location.search,
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
                                    url: 'training/course/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'training/course/destroy',
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
            Controller.api.bindevent();
            player = Controller.api.video.init();
            // 监听事件
            $("#c-videofile").on("change", function() {
                Controller.api.video.preview()
            });
        },
        edit: function() {
            Controller.api.bindevent();
            player = Controller.api.video.init();
            //初始化加载视频预览
            Controller.api.video.preview()

            $("#c-videofile").on("change", function() {
                Controller.api.video.preview()
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
                                url: "training/category/jstree",
                                autoParam: ["id", "name"],
                                otherParam:{ type: 'course' }
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
                                    options.extend.add_url = 'training/course/add?category=' + treeNode.id
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