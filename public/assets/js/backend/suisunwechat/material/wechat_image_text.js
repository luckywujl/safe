requirejs.config({
    paths: {
        vue: "/assets/addons/suisunwechat/libs/vue"
    }
})
define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/material/wechat_image_text/index' + location.search,
                    add_url: 'suisunwechat/material/wechat_image_text/add',
                    edit_url: 'suisunwechat/material/wechat_image_text/edit',
                    del_url: 'suisunwechat/material/wechat_image_text/del',
                    multi_url: 'suisunwechat/material/wechat_image_text/multi',
                    table: 'suisunwechat_imagetext',
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
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title')},
                        {field: 'desc', title: __('Desc')},
                        {field: 'image', title: __('Image'), events: Table.api.events.image, formatter: Table.api.formatter.image},
                        {field: 'url', title: __('Url'), formatter: Table.api.formatter.url},
                        {field: 'createtime', title: __('Createtime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'updatetime', title: __('Updatetime'), operate:'RANGE', addclass:'datetimerange', formatter: Table.api.formatter.datetime},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
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
                url: 'suisunwechat/material/wechat_image_text/recyclebin' + location.search,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 'title', title: __('Title'), align: 'left'},
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
                                    url: 'suisunwechat/material/wechat_image_text/restore',
                                    refresh: true
                                },
                                {
                                    name: 'Destroy',
                                    text: __('Destroy'),
                                    classname: 'btn btn-xs btn-danger btn-ajax btn-destroyit',
                                    icon: 'fa fa-times',
                                    url: 'suisunwechat/material/wechat_image_text/destroy',
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
            Controller.api.meterialdata();
        },
        edit: function () {
            Controller.api.meterialdata();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            },
            meterialdata:function () {
                require(['vue'], (Vue) => {
                    let vue = new Vue({
                        el: '#meterial',
                        data: {
                            list:[],//当前文章数据
                            currentindex:0,//当前操作
                            cdnurl:Config.cdnurl
                        },
                        mounted: function () {
                            this.initdata();
                            this.$nextTick( () => {
                                Controller.api.bindevent();
                            });
                        },
                        methods: {
                            initdata:function () {
                                let initlist = Config.info;
                                if(initlist!='' && initlist!=undefined){
                                    this.list = initlist;
                                }else{
                                    this.additem();
                                }
                            },
                            additem(){
                                let default_data = {
                                    title:'文章标题',
                                    description:'',
                                    image:'',
                                    url:'',
                                };
                                if(this.list.length>=8){
                                    Toastr.error("最多可添加8篇文章");return;
                                }
                                this.list.push(default_data);
                            },
                            deletearticle:function (index) {
                                if(this.list.length<=1){
                                    Toastr.error("最少要有一篇文章");return;
                                }
                                this.currentindex = 0;//重置为0
                                this.list.splice(index,1);
                            },
                            saveall:function () {
                                let _this = this;
                                layer.confirm('确认保存该图文吗?', {icon: 3, title:'提示'}, function(i){
                                    //do something
                                    layer.close(i);
                                    let list = _this.list;
                                    list.forEach((item,index)=>{
                                        if(item.image.indexOf('http')==-1){
                                            list[index].image = _this.cdnurl + item.image
                                        }
                                    });
                                    Fast.api.ajax({data:{list:JSON.stringify(list)}},function () {
                                        //移除这个商品
                                    });//调用接口
                                });
                            }
                        }
                    });

                    //解决直接选择不触发change事件
                    $(document).on('change',"#c-picurl",function (e) {
                        $(this)[0].dispatchEvent(new Event('input'));//触发事件,防止v-model不更新
                        if(vue.list[vue.currentindex].image.indexOf('http')==-1){
                            vue.list[vue.currentindex].image = vue.cdnurl + vue.list[vue.currentindex].image;
                        }
                    });
                    // $(document).on('fa.preview.change',"#p-picurl",function (e) {
                    //     $("#c-album_image")[0].dispatchEvent(new Event('input'));//触发事件,防止v-model不更新
                    // });
                });
            }
        }
    };
    return Controller;
});