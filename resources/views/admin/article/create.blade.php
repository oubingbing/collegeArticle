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

        .article-label{
            margin-right: 20px;
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
            cursor:pointer
        }

        .picture-item .upload-success{
            background: #009688;
            color: white;
        }

        .picture-item .delete-container{
            width: 150px;
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
            width: 150px;
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
            width: 150px;
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
        <header>
            <h1>写文章</h1>
            <div class="article-item">
                <label class="article-label">
                    标题
                </label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" style="width: 500px">
                </div>
            </div>
            <div class="article-item picture-item">
                <label class="article-label">
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
            </div>
        </header>
        <div id="editormd">
            <textarea id="article" style="display:none;"></textarea>
        </div>
        <header><button class="layui-btn layui-btn-info">提交</button></header>
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
    const IMAGE_URL = 'http://article.qiuhuiyi.cn/';
    const ZONE = "z2";

    $(function() {
        $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

        var testEditor = editormd("editormd", {
            width: "90%",
            height: 640,
            markdown : "",
            path : "/lib/",
            //dialogLockScreen : false,   // 设置弹出层对话框不锁屏，全局通用，默认为 true
            //dialogShowMask : false,     // 设置弹出层对话框显示透明遮罩层，全局通用，默认为 true
            //dialogDraggable : false,    // 设置弹出层对话框不可拖动，全局通用，默认为 true
            //dialogMaskOpacity : 0.4,    // 设置透明遮罩层的透明度，全局通用，默认值为 0.1
            //dialogMaskBgColor : "#000", // 设置透明遮罩层的背景颜色，全局通用，默认为 #fff
            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL : "./php/upload.php?test=dfdf",

            /*
             上传的后台只需要返回一个 JSON 数据，结构如下：
             {
             success : 0 | 1,           // 0 表示上传失败，1 表示上传成功
             message : "提示的信息，上传成功或上传失败及错误信息等。",
             url     : "图片地址"        // 上传成功时才返回
             }
             */
        });

        /**
         * 监听图片上传
         **/
        $("#cover-picture").unbind("change").bind("change",function(){
            var file = this.files[0];
            //console.log($("#img-cover").attr("src"));
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