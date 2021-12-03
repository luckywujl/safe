requirejs.config({
    paths: {
        vue: "/assets/addons/suisunwechat/libs/vue",
        sortable: "/assets/addons/suisunwechat/libs/Sortable/Sortable"
    }
})
define(['jquery', 'bootstrap', 'backend', 'table', 'form', 'vue', 'sortable'], function ($, undefined, Backend, Table, Form, Vue, Sortable) {
    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'suisunwechat/wechat/index' + location.search,
                    add_url: 'suisunwechat/wechat/add',
                    edit_url: 'suisunwechat/wechat/edit',
                    del_url: 'suisunwechat/wechat/del',
                    multi_url: 'suisunwechat/wechat/multi',
                    table: 'suisunwechat_wechat',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [{
                        checkbox: true
                    },
                        {
                            field: 'id',
                            title: __('Id')
                        },
                        {
                            field: 'name',
                            title: __('Name')
                        },
                        {
                            field: 'type',
                            title: __('菜单类型'),
                            searchList: {"base": __('基本菜单'), "conditional": __('个性化菜单')},
                            formatter: Table.api.formatter.normal
                        },
                        {
                            field: 'status',
                            title: __('发布状态'),
                            searchList: {"0": __('未发布'), "1": __('已发布')},
                            formatter: Table.api.formatter.normal
                        },
                        {
                            field: 'createtime',
                            title: __('Createtime'),
                            operate: 'RANGE',
                            addclass: 'datetimerange',
                            formatter: Table.api.formatter.datetime
                        },
                        {
                            field: 'operate',
                            title: __('Operate'),
                            table: table,
                            events: Table.api.events.operate,
                            formatter: Table.api.formatter.operate,
                            buttons: [
                                {
                                    name: 'menu',
                                    title: __('管理菜单'),
                                    text: __('管理菜单'),
                                    extend: 'data-toggle="tooltip"',
                                    url: function (row) {
                                        return "suisunwechat/wechat/menu/id/" + row.id
                                    },
                                    classname: 'btn btn-xs btn-info btn-addtabs'
                                }
                            ]
                        }
                    ]
                ]
            });

            $(".btn-getmenu").click(function (row) { //同步微信菜单
                //按钮【按钮一】的回调
                layer.confirm('是否拉取微信公众号后台菜单,拉取菜单将覆盖现有发布的菜单,请慎重操作?', {icon: 3, title: '提示'}, function (index) {
                    //do something
                    layer.close(index);
                    let url = 'suisunwechat/wechat/getmenu';
                    Fast.api.ajax({url: url}, function () {
                        table.bootstrapTable('refresh', {pageNumber: 1});
                    });//调用接口
                });
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        menu: function () {
            var vm = new Vue({
                el: '#menu',
                data: {
                    button: [],//一级菜单
                    current_index: -1,//当前编辑数据
                    current_sub_index: -1,//当前编辑数据
                    current_data: false,//当前操作数据
                    is_menu_sort_init: false,
                    menu_sort: false,
                    sub_menu_sort: false,
                },
                mounted: function () {
                    this.init(); //初始化数据
                },
                watch:{
                    current_index:function (value) {
                        this.initsubmenusorttable();
                    }
                },
                methods: {
                    init: function () {
                        this.button = JSON.parse(Config.button);
                        this.initmenusorttable();
                    },
                    addmenu: function () {
                        let default_menu = {
                            'name': '菜单名称',
                            'type': 'click',
                            'key': '',
                            'url': '',
                            'appid': '',
                            'pagepath': '',
                            'sub_button': []
                        };
                        this.button.push(default_menu);
                    },
                    addsubmenu: function (index) {
                        let default_menu = {
                            'name': '菜单名称',
                            'type': 'click',
                            'key': '',
                            'url': '',
                            'appid': '',
                            'pagepath': ''
                        };
                        let sub_button = this.button[index].sub_button;
                        if (sub_button.length < 5) {
                            sub_button.push(default_menu);
                        }
                    },
                    deletemenu: function () {
                        if (this.current_sub_index > -1) { //删除子菜单
                            let sub_button = this.button[this.current_index].sub_button;
                            sub_button.splice(this.current_sub_index, 1);
                            this.current_sub_index = -1;
                            this.current_data = this.button[this.current_index];
                            return;
                        }
                        this.button.splice(this.current_index, 1);
                        this.current_data = false;//请空数据
                    },
                    tabmenu: function (index) { //操作菜单
                        this.current_index = index;
                        this.current_sub_index = -1;
                        this.current_data = this.button[this.current_index];
                    },
                    tabsubmenu: function (index) { //操作菜单
                        this.current_sub_index = index;
                        this.current_data = this.button[this.current_index].sub_button[index];
                    },
                    selectkeyword: function () {
                        Fast.api.open('suisunwechat/source/select', "选择关键词", {
                            callback: (res) => {
                                this.current_data.key = res.key;
                            }
                        });
                    },
                    initmenusorttable() {
                        let _this = this;
                        this.$nextTick(function () {
                            //绑定拖动排序
                            var container = document.getElementById("menu-preview-group");
                            var sort = Sortable.create(container, {
                                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                                handle: ".menu-preview-item", // Restricts sort start click/touch to the specified element
                                draggable: ".menu-preview-item", // Specifies which items inside the element should be sortable
                                filter: ".filter-sort",
                                get: function (e) {
                                    console.log(e);
                                },
                                onUpdate: function (evt/**Event*/) {
                                    //修改items数据顺序
                                    _this.nodereset(evt, container);
                                    // 更新items数组
                                    let item = _this.button.splice(evt.oldIndex, 1)
                                    _this.button.splice(evt.newIndex, 0, item[0])
                                    _this.current_index = evt.newIndex;
                                    // 下一个tick就会走patch更新
                                }
                            });
                        });
                    },
                    initsubmenusorttable() {
                        let _this = this;
                        this.$nextTick(function () {
                            if (_this.sub_menu_sort) {
                                _this.sub_menu_sort.destroy();
                                _this.sub_menu_sort = false;
                            }
                            //绑定拖动排序
                            var page = document.getElementById("menu-preview-sub");
                            _this.sub_menu_sort = Sortable.create(page, {
                                animation: 150, // ms, animation speed moving items when sorting, `0` — without animation
                                handle: ".menu-preview-sub-item", // Restricts sort start click/touch to the specified element
                                draggable: ".menu-preview-sub-item", // Specifies which items inside the element should be sortable
                                filter: ".filter-sort",
                                onUpdate: function (evt/**Event*/) {
                                    _this.nodereset(evt, page);
                                    // 更新items数组
                                    let item = _this.button[_this.current_index].sub_button.splice(evt.oldIndex, 1);
                                    _this.button[_this.current_index].sub_button.splice(evt.newIndex, 0, item[0]);
                                    _this.current_sub_index = evt.newIndex;
                                    //下一个tick就会走patch更新
                                }
                            });
                        });
                    },
                    nodereset(evt, none) { //节点还原
                        let newIndex = evt.newIndex,
                            oldIndex = evt.oldIndex,
                            $li = none.children[newIndex],
                            $oldLi = none.children[oldIndex];
                        // 先删除移动的节点
                        none.removeChild($li)
                        // 再插入移动的节点到原有节点，还原了移动的操作
                        if (newIndex > oldIndex) {
                            none.insertBefore($li, $oldLi)
                        } else {
                            none.insertBefore($li, $oldLi.nextSibling)
                        }
                    },
                    save: function (type) {
                        Fast.api.ajax({
                            loading: true,
                            type: 'POST',
                            data: {
                                rule: this.button,
                                type: type
                            }
                        });
                    }
                }
            });
        },
        fans: function () {
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
                //监听类型
                $("#c-type").change(function (e) {
                    inittype();
                });
                inittype();

                function inittype() {
                    let type = $("#c-type").val();
                    if (type == 'conditional') {
                        $("#conditional").show();
                    } else {
                        $("#conditional").hide();
                    }
                }
            }
        }
    };
    return Controller;
});