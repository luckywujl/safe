define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'training/record/index' + location.search,
                    add_url: 'training/record/add',
                    edit_url: 'training/record/edit',
                    del_url: 'training/record/del',
                    multi_url: 'training/record/multi',
                    import_url: 'training/record/import',
                    table: 'training_record',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'createtime',
                columns: [
                    [
                        {checkbox: true},
                        //{field: 'id', title: __('Id')},
                        //{field: 'user_id', title: __('User_id')},
                        //{field: 'training_main_id', title: __('Training_main_id')},
                        //{field: 'training_course_id', title: __('Training_course_id')},
                        {field: 'trainingmain.name', title: __('Trainingmain.name'), operate: 'LIKE'},
                        {field: 'trainingmain.coverimage', title: __('Trainingmain.coverimage'), operate: false, events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'trainingcourse.name', title: __('Trainingcourse.name'), operate: 'LIKE'},
                        {field: 'trainingcourse.speaker', title: __('Trainingcourse.speaker'), operate: 'LIKE'},
                        {field: 'trainingmain.type', title: __('Trainingmain.type'),formatter: Table.api.formatter.status ,searchList: {online: __('Online'), offline: __('Offline')}},
                        {field: 'studytime', title: __('Studytime')},
                        {field: 'progress', title: __('Progress')},
                        {field: 'complete', title: __('Complete'),formatter: Table.api.formatter.status ,searchList: {0: __('未完成'), 1: __('已完成')}},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'company_id', title: __('Company_id')},
                        //{field: 'trainingcourse.id', title: __('Trainingcourse.id')},
                        //{field: 'trainingcourse.training_category_id', title: __('Trainingcourse.training_category_id')},
                        
                        //{field: 'trainingcourse.videofile', title: __('Trainingcourse.videofile'), operate: false},
                        //{field: 'trainingcourse.duration', title: __('Trainingcourse.duration')},
                        //{field: 'trainingcourse.keywords', title: __('Trainingcourse.keywords'), operate: 'LIKE'},
                        
                        //{field: 'trainingcourse.praise', title: __('Trainingcourse.praise')},
                        //{field: 'trainingcourse.playtimes', title: __('Trainingcourse.playtimes')},
                        //{field: 'trainingcourse.admin_id', title: __('Trainingcourse.admin_id')},
                        //{field: 'trainingcourse.createtime', title: __('Trainingcourse.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                       // {field: 'trainingcourse.updatetime', title: __('Trainingcourse.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'trainingcourse.uploadtime', title: __('Trainingcourse.uploadtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'trainingcourse.deletetime', title: __('Trainingcourse.deletetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                       // {field: 'trainingcourse.weigh', title: __('Trainingcourse.weigh')},
                       // {field: 'trainingcourse.status', title: __('Trainingcourse.status'), formatter: Table.api.formatter.status},
                       // {field: 'trainingcourse.company_id', title: __('Trainingcourse.company_id')},
                       // {field: 'trainingmain.id', title: __('Trainingmain.id')},
                       // {field: 'trainingmain.training_category_id', title: __('Trainingmain.training_category_id')},
                        
                        //{field: 'trainingmain.training_course_ids', title: __('Trainingmain.training_course_ids'), operate: 'LIKE'},
                       // {field: 'trainingmain.duration', title: __('Trainingmain.duration')},
                        
                        //{field: 'trainingmain.keywords', title: __('Trainingmain.keywords'), operate: 'LIKE'},
                        //{field: 'trainingmain.user_ids', title: __('Trainingmain.user_ids'), operate: 'LIKE'},
                        //{field: 'trainingmain.user_group_ids', title: __('Trainingmain.user_group_ids'), operate: 'LIKE'},
                        //{field: 'trainingmain.admin_id', title: __('Trainingmain.admin_id'), operate: 'LIKE'},
                        //{field: 'trainingmain.starttime', title: __('Trainingmain.starttime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'trainingmain.endtime', title: __('Trainingmain.endtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'trainingmain.createtime', title: __('Trainingmain.createtime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                        //{field: 'trainingmain.updatetime', title: __('Trainingmain.updatetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                       // {field: 'trainingmain.deletetime', title: __('Trainingmain.deletetime'), operate:'RANGE', addclass:'datetimerange', autocomplete:false, formatter: Table.api.formatter.datetime},
                       // {field: 'trainingmain.weigh', title: __('Trainingmain.weigh')},
                        //{field: 'trainingmain.status', title: __('Trainingmain.status'), formatter: Table.api.formatter.status},
                        
                        //{field: 'trainingmain.company_id', title: __('Trainingmain.company_id')},
                       // {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});