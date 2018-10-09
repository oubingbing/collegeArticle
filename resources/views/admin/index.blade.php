@extends('layouts/admin')
<link rel="stylesheet" href="{{asset('css/markdown/style.css')}}" />
<link rel="stylesheet" href="{{asset('css/markdown/editormd.css')}}" />
<link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" />
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
        margin-top: 5px;
    }

    .note .note-item{
        width: 90%;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
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
        padding: 10px;
    }

    .note-title .title-left{
        width: 80%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .note-title .title-right{
        width: 20%;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
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
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
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
        margin-bottom: 10px;
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

    .moveCategory{
        background: #F0F8FF;
    }

    .page-content .content-header{
        width: 100%;
        height: 70px;
        background: white;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
    }

    .content-header .header-left{
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
        align-items: center;
    }

    .header-left .title{
        margin-left: 30px;
    }

    .content-header .header-right{
        width: 50%;
        display: flex;
        flex-direction: row;
        justify-content: flex-end;
        align-items: center;
    }

    .header-right .operate-button{
        margin-right: 30px;
    }

    .operate-button .save-button{
        margin-right: 5px;
    }

    .operate-button button{
        padding-top: 8px;
        padding-bottom: 8px;
        padding-left: 20px;
        padding-right: 20px;
        color: #009688;
        border-style:solid;
        border-width:1px;
        border-color: #009688;
        background: white;
        border-radius: 5px;
    }

    .delete-note{
        margin-left: 20px;
    }

    .image-container{
        width: 100%;
        height: 150px;
        background: white;
        display: flex;
        flex-direction: row;
        align-items: center;
        border-bottom-style:solid;
        border-width:1px;
        border-color: #E1E1E1;
    }

    .image-container .label{
        margin-left: 30px;
        width: 10%;
    }

    .image-container .image-content{
        width: 75%;
        display: flex;
        flex-direction: row;
        justify-content: flex-start;
    }

    .image-content img{
        width: 100px;
        height:100px;
    }

    .image-content .cover-container{
        margin-right: 10px;
    }

    .cover-container .delete-container{
        width: 100px;
        height: 100px;
        z-index: 20;
        position: absolute;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background:rgba(2,2,2,0.6);
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        color: #009688;
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
          <div class="create-container" v-cloak>
              <div class="create-header" v-on:click="createDir()">
                  <img src="{{asset('images/file.png')}}" alt="">
                  <span class="create-title">新建笔记本</span>
              </div>
              <div class="my-dir">
                  <div class="title"><span class="title-label">我的笔记本</span></div>
                  <div class="note">
                      <div class="note-item" v-for="category in noteCategories">
                          <div class="note-title"
                                @mouseenter="enterNoteCategory(category.id)"
                                @mouseleave="leaveNoteCategory()"
                                v-bind:class="{moveCategory:category.showBackgroud}"
                                v-on:click="showNoteList(category.id)">
                              <div class="title-left">
                                  <img src="{{asset('images/book.png')}}" alt="">
                                  <span class="title-label">@{{ category.name }}</span>
                              </div>
                              <div class="title-right" v-if="category.showBackgroud == true">
                                  <img src="{{asset('images/edit-category.png')}}" style="width: 25px;height: 20px" alt="">
                                  <img src="{{asset('images/delete-category.png')}}" style="width: 25px;height: 20px" alt="" v-on:click="deleteCategory(category.id)">
                              </div>
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
    <div class="page-content" v-cloak>
        <div class="layui-tab tab" lay-filter="xbs_tab" lay-allowclose="false">
            <div class="content-header">
                <div class="header-left">
                    <h2 class="title">@{{note.title}}</h2>
                </div>
                <div class="header-right">
                    <div class="operate-button">
                        <button class="save-button" v-on:click="saveEdit()" v-show="showSave">保存</button>
                        <button class="edit-button" v-on:click="showEditMd()" v-show="showEdit">编辑</button>
                        <button class="edit-button delete-note" v-show="showDelete" v-on:click="deleteNote()">删除</button>
                    </div>
                </div>
            </div>

            <div class="image-container">
                <div class="label"><h3>封面图片</h3></div>
                <div class="image-content">
                    <div v-for="image in coverPictures" class="cover-container" @mouseenter="enterCover(image.name)" @mouseleave="leaveCover(image.name)">
                        <div class="delete-container" v-show="image.show" v-on:click="deleteImage(image.name)">
                            <img src="{{asset('images/remove-img.png')}}" alt="删除" style="width: 30px;height: 30px">
                        </div>
                        <img v-bind:src="image.image">
                    </div>
                    <img src="{{asset('images/select-image.png')}}" alt="" onclick="javascript:$('#cover-picture').click()">
                    <input type="file" id="cover-picture" style="display: none" class="layui-input" @change="selectCoverPicture($event)"/>
                </div>
            </div>

            <div class="content-body">
                <div id="editormd" v-show="showMd">
                    <textarea style="display:none;"></textarea>
                </div>
                <div id="viewMd" style="height: 800px" v-show="!showMd">
                    <textarea style="display:none;"></textarea>
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
<script type="text/javascript" src="https://unpkg.com/qiniu-js@2.0/dist/qiniu.min.js"></script>
<script type="text/javascript" src="{{asset('js/upload.js')}}"></script>
<script src="{{asset('js/markdown/editormd.js')}}"></script>
<script src="{{asset('lib/marked.min.js')}}"></script>
<script src="{{asset('lib/prettify.min.js')}}"></script>
<script src="{{asset('lib/raphael.min.js')}}"></script>
<script src="{{asset('lib/underscore.min.js')}}"></script>
<script src="{{asset('lib/sequence-diagram.min.js')}}"></script>
<script src="{{asset('lib/flowchart.min.js')}}"></script>
<script src="{{asset('lib/jquery.flowchart.min.js')}}"></script>
<script type="text/javascript" src="{{asset('js/note-index.js')}}"></script>
<script type="text/javascript">
    const token = "{{$token}}";
    const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
    const ZONE = "z2";
    let editorMd = '';
    let viewMd = '';

    $(function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        editorMd = editormd("editormd", {
            width: "100%",
            height: 800,
            markdown : "",
            path : "/lib/",
            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL : "{{asset('/admin/article/image_upload')}}",
        });


        $("#submit").on('click',function () {
            let title = $("#title").val();
            let content = $("#article").val();
            let cover = $("#img-cover").attr("src");
            let articleType = $("input[type='radio']:checked").val();

            $.post("{{asset('admin/article/create')}}",{title:title,content:content,cover:cover,article_type:articleType},function(result){
                console.log(result);
            });
        });

    });
</script>
</body>
@stop