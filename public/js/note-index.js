new Vue({
    el: '#app',
    data: {
        name:'bingbing',
        noteCategories:[],
        showCreateCategory:false,
        showCreateNote:false,
        categoryName:'',
        noteName:'',
        note:'',
        showSave:true,
        showEdit:false
    },
    created:function () {
        this.getCategories();
    },
    methods:{
        createDir:function () {
            this.showCreateCategory = true;
        },

        enterNoteCategory:function(id){
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == id){
                    item.showBackgroud = true;
                }else{
                    item.showBackgroud = false;
                }
                return item;
            })
        },

        leaveNoteCategory:function () {

        },

        /**
         * 获取笔记本列表
         * */
        getCategories:function () {
            let categoryData = this.noteCategories;
            let _this = this;
            axios.get("admin/note_category/list",{}).then( res=> {
                if(res.data.code != 500){
                    this.noteCategories = res.data.map(function (item) {
                        item = _this.formatSingleNoteCateGory(item);
                        categoryData.push(item);
                        return item;
                    })
                }else{
                    layer.msg(res.data.message);
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        /**
         * 格式化单挑笔记簿
         *
         * */
        formatSingleNoteCateGory:function (category) {
            let _this = this;
            category.notes.map(function (note) {
                return _this.formatSingleNote(note);
            });
            category.showNotes = false;
            category.showBackgroud = false;
            return category;
        },

        formatSingleNote:function (note) {
            note.tap=false;
            note.enter=false;
            note.showCreateNote=false;
            return note;
        },

        /**
         * 新建笔记本
         *
         * */
        createNote:function (id) {
            let note = this.noteName;
            if(note == '' || note == undefined){
                layer.msg("名字不能为空");
                return false
            }
            let _this = this;
            axios.post("{{ asset('admin/note/create') }}",{title:note,category_id:id}).then( res=> {
                console.log(res.data.code);
                if(res.data.code == 200){
                    _this.hiddenCreateNote(id,_this);
                }else{
                    layer.msg("新建失败");
                }
            }).catch(function (error) {
                console.log(error);
            });
        },

        /**
         * 隐藏新建笔记的输入框
         *
         * */
        hiddenCreateNote:function (id) {
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == id){
                    item.showCreateNote = false;
                }
                return item;
            })
        },

        /**
         * 显示创建笔记的按钮
         *
         * */
        showCreateNoteButton:function(id){
            this.showCreateCategory = false;
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == id){
                    item.showCreateNote = true;
                }else{
                    item.showCreateNote = false;
                }

                return item;
            })
        },
        /**
         * 新建笔记簿
         * */
        createCategory:function () {
            let categoryName = this.categoryName;
            if(categoryName == '' || categoryName == undefined){
                layer.msg("名字不能为空");
                return false
            }

            let _this = this;
            axios.post("{{ asset('admin/note_category/create') }}",{name:categoryName}).then( res=> {
                console.log(res.data);
                if(res.data.code == 200){
                    _this.showCreateCategory = false;
                    _this.categoryName = '';
                    let categoryData = _this.noteCategories;
                    categoryData.push(_this.formatSingleNoteCateGory(res.data.data));
                    _this.noteCategories = categoryData;
                }else{
                    layer.msg("新建失败");
                }
            }).catch(function (error) {
                console.log(error);
            });

        },
        showNoteList:function (id) {
            this.showCreateCategory = false;
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == id){
                    if(item.showNotes == true){
                        item.showNotes = false;
                    }else{
                        item.showNotes = true;
                    }

                }

                return item;
            })
        },

        /**
         * 监控鼠标进入事件，改变背景颜色
         *
         * @param categoryId
         * @param noteId
         * @author 叶子
         **/
        enterNote:function (categoryId,noteId) {
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == categoryId){
                    item.notes = item.notes.map(function (sub) {
                        if(sub.id == noteId && sub.tap == false){
                            sub.enter = true;
                        }else{
                            sub.enter = false;
                        }
                        return sub;
                    })
                }
                return item;
            })
        },

        /**
         * 监听鼠标移
         * */
        leaveNote:function () {
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                item.notes = item.notes.map(function (sub) {
                    sub.enter = false;
                    return sub;
                });
                return item;
            })
        },

        /**
         * 监控鼠标点击笔记改变背景颜色
         *
         * @author yezi
         * @param categoryId
         * @param noteId
         */
        openNote:function(categoryId,noteId){
            let categoryData = this.noteCategories;
            this.noteCategories = categoryData.map(function (item) {
                if(item.id == categoryId){
                    item.notes = item.notes.map(function (sub) {
                        if(sub.id == noteId){
                            sub.tap = true;
                        }else{
                            sub.tap = false;
                        }
                        sub.enter = false;
                        return sub;
                    })
                }else{
                    item.notes = item.notes.map(function (sub) {
                        sub.tap = false;
                        sub.enter = false;
                        return sub;
                    })
                }
                return item;
            });

            let _this = this;

            //获取文章
            axios.get(`admin/note/${categoryId}/${noteId}`,{}).then( res=> {
                if(res.data.code != 500){
                    _this.note = res.data;

                    $(function() {
                        viewMd = editormd.markdownToHTML("viewMd", {
                            markdown: _this.note.content,//+ "\r\n" + $("#append-test").text(),
                            htmlDecode: "style,script,iframe",  // you can filter tags decode
                            tocm: true,    // Using [TOCM]
                            tocContainer: "#custom-toc-container", // 自定义 ToC 容器层
                            emoji: true,
                            taskList: true,
                            tex: true,  // 默认不解析
                            flowChart: true,  // 默认不解析
                            sequenceDiagram: true,  // 默认不解析
                        });
                    });
                }else{
                    layer.msg(res.data.message);
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        /**
         * 保存编辑内容
         */
        saveEdit:function () {
            let _this = this;
            this.note.content = editorMd.getValue();

            axios.post(`admin/note/update/${this.note.id}`,{content:this.note.content}).then( res=> {
                console.log(res.data);
                if(res.data.code != 500){

                }else{
                    layer.msg("新建失败");
                }
            }).catch(function (error) {
                console.log(error);
            });
        }
    }
})