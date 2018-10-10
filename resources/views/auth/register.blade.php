@extends('layouts/admin')

@section('content')
    <body class="login-bg">

    <div class="login layui-anim layui-anim-up">
        <div class="message"><a href="{{ asset('/home') }}" style="color: white;">灯塔 - 注册</a></div>
        <div id="darkbannerwrap"></div>

        <form method="POST" class="layui-form">
            {{ csrf_field() }}
            <input name="nickname" placeholder="昵称（必填）"  type="text" lay-verify="required" class="layui-input" >
            <hr class="hr15">
            <input name="phone" placeholder="手机（必填）"  type="text" lay-verify="required" class="layui-input email" >
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码（必填）"  type="password" class="layui-input">
            <hr class="hr15">
            <input name="password_confirmation" lay-verify="required" placeholder="确认密码（必填）"  type="password" class="layui-input">
            <hr class="hr15">
            <input value="注册" lay-submit lay-filter="login" style="width:100%;" type="submit">
            <hr class="hr20" >
        </form>
        <div><a href="{{asset('login')}}">已有账号？快去登录吧</a></div>
    </div>
    <!--<script src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>-->
    <script>
        $(function  () {
            layui.use('form', function(){
                var form = layui.form;
                //监听提交
                form.on('submit(login)', function(data){
                    var fields = data.field;

                    if(fields.password_confirmation !== fields.password){
                        layer.msg('两次输入密码不一致！');
                        return false;
                    }

                    $.post("{{asset('register')}}",fields,function(res){
                        if(res.code === 500){
                            layer.msg(res.message)
                        }else{
                            layer.msg("注册成功");
                            setTimeout(function () {
                                window.location.href = "{{asset('login')}}";
                            },1500)
                        }
                    });

                    return false;
                 });
            });
        })


    </script>
    </body>
@endsection
