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
        padding: 5px;
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
    <div class="left-nav" style="width: 300px;max-width: 300px;">
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
                                   v-for="note in category.note_list">
                                  <small v-bind:class="{titleColor:note.tap}">@{{ note.title }}</small>
                              </div>
                              <div class="content-title add-icon"><img src="{{asset('images/add-note.png')}}" alt=""></div>
                          </div>
                          </transition>
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
    let categories = [
        {
            id:1,
            name:"java",
            note_list:[
                {id:1,title:"springboot安装教程springboot",tap:false,enter:false},
                {id:2,title:"java多态",tap:false,enter:false},
                {id:3,title:"多线程",tap:false,enter:false}
            ]
        },
        {
            id:2,
            name:"php",
            note_list:[
                {id:1,title:"laravel部署教程",tap:false,enter:false},
                {id:2,title:"php魔术方法",tap:false,enter:false},
                {id:3,title:"php环境变量",tap:false,enter:false}
            ]
        },
        {
            id:3,
            name:"前端技术整合",
            note_list:[
                {id:1,title:"vue.js",tap:false,enter:false}
            ]
        }
    ];

    new Vue({
        el: '#app',
        data: {
            name:'bingbing',
            noteCategories:[]
        },
        created:function () {
            let categoryData = this.noteCategories;
            categories.map(function (item) {
                item.showNotes = false;
                categoryData.push(item);
            });
            this.noteCategories = categoryData;
        },
        methods:{
            createDir:function () {
                console.log("新建笔记");
            },
            showNoteList:function (id) {
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
                        item.note_list = item.note_list.map(function (sub) {
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
                    item.note_list = item.note_list.map(function (sub) {
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
                        item.note_list = item.note_list.map(function (sub) {
                            if(sub.id == noteId){
                                sub.tap = true;
                            }else{
                                sub.tap = false;
                            }
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