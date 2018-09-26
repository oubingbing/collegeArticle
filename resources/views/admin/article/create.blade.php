<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>写文章</title>
    <link rel="stylesheet" href="{{asset('css/markdown/style.css')}}" />
    <link rel="stylesheet" href="{{asset('css/markdown/editormd.css')}}" />
    <link rel="shortcut icon" href="https://pandao.github.io/editor.md/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="{{ asset('css/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/xadmin.css') }}">
    <style>
        .article-item{
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .picture-item .upload-button{
            padding: 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            text-align: center;
            width: 190px;
        }

        .picture-item .upload-none{
            background: darkgray;
            color: #009688;
            cursor:pointer
        }

        .picture-item .upload-success{
            background: #009688;
            color: white;
        }

        .picture-item .delete-container{
            width: 190px;
            height: 120px;
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

        .picture-item img{
            width: 190px;
            height: 120px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .picture-item .upload-button{
            padding: 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            text-align: center;
        }

        .picture-item .upload-none{
            background: darkgray;
            color: #009688;
            cursor:pointer;
            width: 190px;
        }

        .picture-item .upload-success{
            background: #009688;
            color: white;
        }
    </style>
</head>
<body>
<div id="app">
    <div id="layout" class="x-body">

        <form class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label"><span class="x-red">*</span>类型</label>
                <div class="layui-input-block">
                    <input type="radio" name="article-type" value="2" lay-skin="primary" title="笔记" checked="">
                    <input type="radio" name="article-type" value="1" lay-skin="primary" title="大学成长记">
                </div>
            </div>
            <div class="layui-form-item article-item picture-item">
                <label for="phone" class="layui-form-label">
                    封面
                </label>
                <div class="layui-input-inline">
                    <div class="image-container">
                        <div class="delete-container" style="display: none;">
                            <img id="delete-cover" style="width: 25px;height: 25px;" src="{{asset('images/delete.png')}}" alt="">
                        </div>
                        <img src="" id="img-cover">
                    </div>
                    <div>
                        <div class="upload-button upload-none" onclick="javascript:$('#cover-picture').click()">上传封面</div>
                        <div class="upload-button upload-success" style="display: none" onclick="javascript:$('#cover-picture').click()">上传成功</div>
                    </div>
                    <input type="file" id="cover-picture" style="display: none" class="layui-input"/>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    可为空
                </div>
            </div>
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>标题
                </label>
                <div class="layui-input-inline">
                    <input type="text"
                           id="title"
                           required=""
                           lay-verify="required"
                           autocomplete="off"
                           style="width: 500px"
                           class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux" style="margin-left: 320px">
                    <span class="x-red">*</span>必填
                </div>
            </div>
            <div class="layui-form-item">
                <label for="L_email" class="layui-form-label">
                    <span class="x-red">*</span>内容
                </label>
                <div class="layui-input-inline" id="editormd">
                    <textarea id="article" style="display:none;"></textarea>
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>
                </div>
            </div>
        </form>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button  class="layui-btn" lay-filter="add" lay-submit="" id="submit">
                提交
            </button>
        </div>
    </div>

</div>
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript" src="https://unpkg.com/qiniu-js@2.0/dist/qiniu.min.js"></script>
<script type="text/javascript" src="{{asset('js/upload.js')}}"></script>
<script src="{{asset('js/markdown/editormd.js')}}"></script>
<script src="{{ asset('lib/layui/layui.js') }}" charset="utf-8"></script>
<script type="text/javascript" src="{{ asset('js/xadmin.js') }}"></script>
<script type="text/javascript">
    const token = "{{$token}}";
    const IMAGE_URL = "{{env('QI_NIU_DOMAIN')}}";
    const ZONE = "z2";

    $(function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});
        var testEditor = editormd("editormd", {
            width: "90%",
            height: 640,
            markdown : "",
            path : "/lib/",
            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL : "{{asset('/admin/article/image_upload')}}",
        });


        $("#submit").on('click',function () {
            var title = $("#title").val();
            var content = $("#article").val();
            var cover = $("#img-cover").attr("src");
            var articleType = $("input[type='radio']:checked").val();

            $.post("{{asset('admin/article/create')}}",{title:title,content:content,cover:cover,article_type:articleType},function(result){
                console.log(result);
            });
        });

        /**
         * 监听图片上传
         **/
        $("#cover-picture").unbind("change").bind("change",function(){
            var file = this.files[0];
            uploadPicture(file,function (res) {
                $("#img-cover").attr("src",IMAGE_URL+res.key);
                $(".upload-success").css("display","");
                $(".upload-none").css("display","none");
            },function (res) {
                var total = res.total;
                console.log(total)
            },function (res) {
                console.log("出错了")
            },ZONE);
        });

        /**
         * 监听封面图片的删除icon
         */
        $(".image-container").on({
            mouseover : function(){
                if($("#img-cover").attr("src") != ''){
                    $(".delete-container").css("display","");
                }
            } ,
            mouseout : function(){
                $(".delete-container").css("display","none");
            }
        });

        $("#delete-cover").on('click',function () {
            $("#img-cover").attr("src","");
            $(".delete-container").css("display","none");
            $(".upload-none").css("display","");
            $(".upload-success").css("display","none");
            layer.msg("封面已移除");
        })

    });
</script>
</body>
</html>