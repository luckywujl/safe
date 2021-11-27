define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {
    $(document).on("fa.event.appendfieldlist", ".btn-append", function(){
        Form.events.selectpicker($(".fieldlist"));
    });
    $('[name="row[type]"]').on('change',function (e) {
        var type = $(this).val();
        if(type == 1){
            $('.number').removeAttr('readonly');
            $('.btn-select-questions').addClass('hide');
        }else{
            $('.btn-select-questions').removeClass('hide');
            $('.number').attr('readonly',true);
        }
    })
    $(function () {
        $("body").delegate(".scoreset","input",function(){
            setscore()
        });


    });

    function setscore() {
        var score = getscore();
        if(isNaN(score)){
            score = 0;
        }
        $('[name="row[score]"]').val(score);
    }


    function getscore(length) {
        var score = 0;
        $('.mark').each(function () {

            score+=$(this).val() * $(this).parent().prev().children('.number').val();
        });

        return score;
    }
    var Controller = {

        index: function () {

            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'kaoshi/examination/exams/index' + location.search,
                    add_url: 'kaoshi/examination/exams/add',
                    edit_url: 'kaoshi/examination/exams/edit',
                    del_url: 'kaoshi/examination/exams/del',
                    multi_url: 'kaoshi/examination/exams/multi',
                    getquestion_url: 'kaoshi/examination/exams/getquestion',
                    table: 'exams',
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
                        {
                            field: 'buttons',
                            width: "120px",
                            title: __('预览'),
                            operate:false,
                            table: table,
                            events: Table.api.events.operate,
                            buttons: [
                                {
                                    name: 'questions',
                                    text: __('预览'),
                                    title: __('随机考题预览'),
                                    classname: 'btn btn-xs btn-primary btn-dialog',
                                    icon: 'fa fa-eye',
                                    url: 'kaoshi/examination/exams/getquestion/type/{type}'
                                },],
                            formatter: Table.api.formatter.buttons
                        },
                        {field: 'id', title: __('Id')},
                        {field: 'admin.username', title: __('username')},
                        {field: 'subject_id', title: __('Subject_id'),operate:'in',visible:false},
                        {field: 'subject.subject_name', title: __('Subject_name')},
                        {field: 'exam_name', title: __('Exam_name')},
                        {field: 'score', title: __('Score')},
                        {field: 'pass', title: __('Pass')},
                        {field: 'type', title: __('Type'), searchList: {"1":__('Type 1'),"2":__('Type 2')}, formatter: Table.api.formatter.normal},
                        {field: 'keyword', title: __('Keyword')},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
            Controller.api.tree.init(table);
            table.on('post-body.bs.table', function (e, settings, json, xhr) {
                $(".btn-editone").data("area", ["100%","100%"]);
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
                url: 'kaoshi/examination/exams/recyclebin' + location.search,
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
                                    url: 'kaoshi/examination/exams/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'kaoshi/examination/exams/destroy',
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
        add: function (form) {
            $("body").delegate(".btn-select-questions","click",function(){

                var url = $(this).attr('data-url');
                var select_name =$(this).attr('data-select-name');
                var subject_id = $('[name="row[subject_id]"]').val();
                var type = $('[name="'+select_name+'[type]"]').val();
                var level = $('[name="'+select_name+'[level]"]').val();
                var question_ids = $('[name="'+select_name+'[question_ids]"]').val();
                if(!subject_id) {
                    Toastr.error('请选择考试科目！');
                    return false;
                }
                url+='?subject_id='+subject_id+'&type='+type+'&level='+level+'&question_ids='+question_ids;
                var title = $(this).attr('title');
                window.top.Fast.api.open(url,title,{
                    callback:function (ids) {
                        $('[name="'+select_name+'[question_ids]"]').val(ids);
                        if(ids.length>0)
                            ids = ids.split(',');
                        $('[name="'+select_name+'[number]"]').val(ids.length);
                        setscore();
                        refresh('row[settingdata]')
                    },
                    area:['60%','50%']
                })
            })
            Controller.api.bindevent();
        },
        edit: function () {
            $("body").delegate(".btn-select-questions","click",function(){

                var url = $(this).attr('data-url');
                var select_name =$(this).attr('data-select-name');
                var subject_id = $('[name="row[subject_id]"]').val();
                var type = $('[name="'+select_name+'[type]"]').val();
                var level = $('[name="'+select_name+'[level]"]').val();
                var question_ids = $('[name="'+select_name+'[question_ids]"]').val();
                if(!subject_id) {
                    Toastr.error('请选择考试科目！');
                    return false;
                }
                url+='?subject_id='+subject_id+'&type='+type+'&level='+level+'&question_ids='+question_ids;
                var title = $(this).attr('title');
                window.top.Fast.api.open(url,title,{
                    callback:function (ids) {
                        $('[name="'+select_name+'[question_ids]"]').val(ids);
                        if(ids.length>0)
                            ids = ids.split(',');
                        $('[name="'+select_name+'[number]"]').val(ids.length);
                        setscore()
                        refresh('row[settingdata]')
                    },
                    area:['60%','50%']
                })
            })
            Controller.api.bindevent();
        },
        getquestion:function () {
            var type = Fast.api.query('type');
            if(type==2)
            {
                $('.type-tips').html('自定义组卷，试卷预览');
            }else{
                $('.type-tips').html('仅供参考!每次考题都将随机生成！');
            }


            Controller.api.bindevent();
        },
        select_question:function () {
            var ids = [];
            var url = document.location.href;
            var subject_id = Fast.api.query('subject_id');
            var type = Fast.api.query('type');
            var level = Fast.api.query('level');
            var question_ids = Fast.api.query('question_ids');
            var all_ids = [];
            $.ajax({
                url:url,
                type:'post',
                data: {subject_id:subject_id,type:type,level:level},
                success:function (data,ret) {

                    for(var i=0;i<data.length;i++){
                        all_ids.push(data[i]['id']);
                        var html_str =  '<div class="select-only row"><div class="question-msg col-xs-12 col-sm-10"><div>'+
                            data[i]['question']+'</div>';
                        if(data[i]['annex'] && data[i]['annex'].length > 0){
                            html_str+='<div class="question-annex"><img class="question-img" src="'+data[i]['annex']+'" alt=""></div>'
                        }
                        html_str+='<div><b>答案:'+data[i]['answer']+'</b></div></div><div class="select-btn-box col-xs-12 col-sm-2 ">'+
                            '<input type="checkbox" class="select-checkbox" value="'+data[i]['id']+'" name="ids[]"></div></div>'
                        $('.select-box').append(html_str);

                    }
                    if(question_ids && question_ids.length > 0){

                        question_ids = question_ids.split(',');
                        for (var i=0;i<question_ids.length;i++){
                            if(all_ids.indexOf(parseInt(question_ids[i]))>-1)
                                checkbox_checked($('[value="'+question_ids[i]+'"]'));
                        }
                    }
                    if(data.length == 0){
                        $('.select-box').append("暂无该题型！");
                        Toastr.error('暂无该题型！');
                        setTimeout(function(){ Fast.api.close('')}, 1500);
                    }
                }});

            $("body").delegate(".select-only","click",function(){
                var checkbox_dom = $(this).children('.select-btn-box').children('.select-checkbox');
                checkbox_checked(checkbox_dom);


            })
            $("body").delegate(".select-checkbox","click",function(){
                checkbox_checked($(this));

            })
            Form.api.bindevent($("form[role=form]"), function(data, ret){
                console.log(ids);
                $('.select-checkbox').each(function () {
                    if($(this).prop("checked")){
                        ids.push($(this).val());
                    }
                })
                Fast.api.close(ids.join(','));

            })

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
                                url: "kaoshi/subject/jstree",
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
                                    var subject_id = Controller.api.tree.getChildNodes(treeNode);
                                    if (treeNode.id == '0') {
                                        $(".commonsearch-table input[name=subject_id]").val('');
                                    } else {
                                        $(".commonsearch-table input[name=subject_id]").val(subject_id);
                                    }
                                    var options = table.bootstrapTable('getOptions');
                                    options.extend.add_url = 'kaoshi/examination/exams/add?subject_id=' + treeNode.id
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
            },
            formatter: {
                thumb: function (value, row, index) {
                    if(typeof (value) == 'string' && value.length > 0){
                        return '<a href="' + value + '" target="_blank"><img src="' + value +'" alt="" style="max-height:90px;max-width:120px"></a>';
                    }else{
                        return "";
                    }
                },
                url: function (value, row, index) {
                    return '<a href="' + value + '" target="_blank" class="label bg-green">' + value + '</a>';
                },
            }
        }
    };
    return Controller;
});

function checkbox_checked(obj) {
    var checkbox_dom = obj;
    if(checkbox_dom.prop("checked")){
        checkbox_dom.removeAttr('checked');
    }
    else
    {
        checkbox_dom.prop("checked","checked");

    }
}

function refresh(name) {
    var data = {};
    var textarea = $("textarea[name='" + name + "']");
    var container = textarea.closest("dl");
    var template = container.data("template");
    $.each($("input,select,textarea", container).serializeArray(), function (i, j) {
        var reg = /\[(\w+)\]\[(\w+)\]$/g;
        var match = reg.exec(j.name);
        if (!match)
            return true;
        match[1] = "x" + parseInt(match[1]);
        if (typeof data[match[1]] == 'undefined') {
            data[match[1]] = {};
        }
        data[match[1]][match[2]] = j.value;
    });
    var result = template ? [] : {};
    $.each(data, function (i, j) {
        if (j) {
            if (!template) {
                if (j.key != '') {
                    result[j.key] = j.value;
                }
            } else {
                result.push(j);
            }
        }
    });
    textarea.val(JSON.stringify(result));
};

function changeSet(obj,select_name) {

    $('[name="'+select_name+'[number]"]').val(0);
    $('[name="'+select_name+'[question_ids]"]').val('');
}
