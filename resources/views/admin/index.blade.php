@extends('layouts/admin')
<style>
    .create-container{
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .create-header{
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        padding-top: 10px;
        padding-bottom: 10px;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
        cursor:pointer;
    }

    .create-header img{
        width: 35px;
        height: 35px;
    }

    .create-header .create-title{
        padding-left: 5px;
    }

    .create-container .my-dir{
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        background-color: white;
    }

    .my-dir .title{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding-top: 10px;
        padding-bottom: 10px;
        background: #009688;
        color: white;
    }

    .title .title-label{
        width: 80%;
    }

    .my-dir .note{
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .note .note-item{
        width: 90%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        padding-top: 15px;
        align-items: center;
    }

    .note-item img{
        width: 20px;
        height:20px;
        padding-right: 5px;
    }

    .note-item .note-title{
        width: 100%;
        display: flex;
        flex-direction: row;
        align-items: center;
        cursor:pointer;
    }

    .selectTitle{
        background: red;
    }

    .note-title .title-label{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .note-item .note-content{
        width: 90%;
        display: flex;
        flex-direction: column;
        margin-top: 5px;
    }

    .note-content .content-title{
        padding: 10px;
        color: gray;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor:pointer;
    }

    .content-title img{
        width: 20px;
        height: 20px;
    }

    .note-content .add-icon{
        display: flex;
        flex-direction: column;
        border-style:dashed;
        border-width:1px;
        border-color: #E1E1E1;
        margin-top:20px;
        padding-top: 5px;
        padding-bottom: 5px;
        align-items: center;
        justify-content: center;
        border-radius: 2px;
    }

    .note-content .enter
    {
        background-color:#F2F2F2;
    }

    [v-cloak] {
        display: none;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .4s;
    }
    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    .tap{
        background:#009688;
    }
    .titleColor{
        color: white;
    }

    .create-category{
        width: 100%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
        padding: 5px;
    }

    .create-category button{
        margin-left: 3px;
    }

</style>
@section('content')
<body>
<div id="app">
    <!-- 顶部开始 -->
    <div class="container" style="background: #009688">
        <div class="logo"><a href="./index.html">大学灯塔</a></div>
        <div class="left_open">
            <i title="展开左侧栏" class="iconfont">&#xe699;</i>
        </div>
        <ul class="layui-nav right" lay-filter="">
          <li class="layui-nav-item">
            <a href="javascript:;">叶子</a>
            <dl class="layui-nav-child"> <!-- 二级菜单 -->
              <dd><a href="{{asset('logout')}}">退出</a></dd>
            </dl>
          </li>
          <li class="layui-nav-item to-index"><a href="/">前台首页</a></li>
        </ul>

    </div>
    <!-- 顶部结束 -->
    <!-- 中部开始 -->
     <!-- 左侧菜单开始 -->
    <div class="left-nav">
      <div id="side-nav">
        <ul id="nav">
            <li>
                <a href="javascript:;">
                    <i class="iconfont">&#xe6b8;</i>
                    <cite>文章</cite>
                    <i class="iconfont nav_right">&#xe697;</i>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a _href="{{ asset('admin/article/create') }}">
                            <i class="iconfont">&#xe6a7;</i>
                            <cite>新建文章</cite>
                        </a>
                    </li >
                </ul>
            </li>
        </ul>

          <div class="create-container" v-cloak>
              <div class="create-header" v-on:click="createDir()">
                  <img src="{{asset('images/file.png')}}" alt="">
                  <span class="create-title">新建笔记本</span>
              </div>
              <div class="my-dir">
                  <div class="title"><span class="title-label">我的笔记本</span></div>
                  <div class="note">
                      <div class="note-item" v-for="category in noteCategories">
                          <div class="note-title" v-on:click="showNoteList(category.id)">
                              <img src="{{asset('images/book.png')}}" alt="">
                              <span class="title-label">@{{ category.name }}</span>
                          </div>
                          <transition name="fade">
                              <div class="note-content" v-if="category.showNotes == true">
                                  <div class="content-title"
                                        @mouseenter="enterNote(category.id,note.id)"
                                        @mouseleave="leaveNote()"
                                       v-bind:class="{tap:note.tap,enter:note.enter}"
                                       v-on:click="openNote(category.id,note.id)"
                                       v-for="note in category.notes">
                                      <span v-bind:class="{titleColor:note.tap}">@{{ note.title }}</span>
                                  </div>

                                  <div class="create-dialog create-note" v-if="category.showCreateNote">
                                      <div class="create-category">
                                          <div class="layui-input-inline">
                                              <input type="text"
                                                     id="name_name"
                                                     required=""
                                                     lay-verify="required"
                                                     autocomplete="off"
                                                     v-model="noteName"
                                                     placeholder="笔记"
                                                     class="layui-input">
                                          </div>
                                          <div class="layui-form-mid layui-word-aux">
                                              <button  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="createNote(category.id)">
                                                  提交
                                              </button>
                                          </div>
                                      </div>
                                  </div>

                                  <div class="content-title add-icon" v-on:click="showCreateNoteButton(category.id)">
                                      <img src="{{asset('images/add-note.png')}}" alt="">
                                  </div>

                              </div>
                          </transition>
                      </div>
                  </div>

              <div class="create-dialog" v-if="showCreateCategory">
                  <div class="create-category">
                      <div class="layui-input-inline">
                          <input type="text"
                                 id="title"
                                 required=""
                                 lay-verify="required"
                                 autocomplete="off"
                                 v-model="categoryName"
                                 placeholder="笔记簿"
                                 class="layui-input">
                      </div>
                      <div class="layui-form-mid layui-word-aux">
                          <button  class="layui-btn" lay-filter="add" lay-submit="" v-on:click="createCategory">
                              提交
                          </button>
                      </div>
                  </div>
              </div>

              </div>
          </div>

      </div>
    </div>


    <!-- <div class="x-slide_left"></div> -->
    <!-- 左侧菜单结束 -->
    <!-- 右侧主体开始 -->
    <div class="page-content">
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
          <ul class="layui-tab-title">
            <li class="home"><i class="layui-icon">&#xe68e;</i>我的桌面</li>
          </ul>
          <div class="layui-tab-content">
            <div class="layui-tab-item layui-show">
                <iframe src='{{ asset('admin/dashboard') }}' frameborder="0" scrolling="yes" class="x-iframe"></iframe>
            </div>
          </div>
        </div>
    </div>
    <div class="page-content-bg"></div>
    <!-- 右侧主体结束 -->
    <!-- 中部结束 -->
    <!-- 底部开始 -->
    <div class="footer">
        <div class="copyright">@2016-2018 大学灯塔 | 粤ICP备16004706号-1</div>
    </div>
    <!-- 底部结束 -->
</div>
</body>
<script>
    new Vue({
        el: '#app',
        data: {
            name:'bingbing',
            noteCategories:[],
            showCreateCategory:false,
            showCreateNote:false,
            categoryName:'',
            noteName:''
        },
        created:function () {
            this.getCategories();
        },
        methods:{
            createDir:function () {
                this.showCreateCategory = true;
            },

            /**
             * 获取笔记本列表
             * */
            getCategories:function () {
                let categoryData = this.noteCategories;
                let _this = this;
                axios.get("{{ asset('admin/notes') }}",{}).then( res=> {
                    console.log(res.data.data);
                    if(res.data.code == 200){
                        this.noteCategories = res.data.data.map(function (item) {
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
                })
            }
        }
    })
</script>
@stop