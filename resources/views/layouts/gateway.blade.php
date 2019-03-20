<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta name="keywords" content="bingshop,yuki,叶子,校园小情书,微信小程序商城,小程序商城,小程序开发者论坛,灯塔笔记,灯塔,笔记,yezi,bingshop安装教程">
    <meta name="description" content="小程序爱好者，讨论自己热爱的代码人生，渴望像先辈们那样创造美好的事物">
    <meta property="og:title" content="开源小程序社区论坛">
    <meta property="og:description" content="渴望创造美好的事物">
    <meta property="og:url" content="https://qiuhuiyi.cn">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>灯塔笔记</title>
    <link rel="stylesheet" href="{{asset('css/font.css')}}">
    <link rel="shortcut icon" href="{{ asset('img/logo.jfif') }}" type="image/x-icon">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <style>
        .footer{
            background: #EEEEEE;
            text-align: center;
            padding: 5px;
        }
    </style>
</head>
<body>
<div id="app">

    <nav class="navbar navbar-inverse" style="background: #009688;">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/" style="color: white">灯塔笔记</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ asset('/') }}" style="color: white">首页</a></li>
                    <li><a href="{{ asset('/') }}" style="color: white">bingshop</a></li>
                    <li><a href="{{ asset('/') }}" style="color: white">校园小情书</a></li>
                    @if (session("customer_id"))
                        <li><a href="{{ asset('/admin') }}" style="color: white">{{session('customer_name')}}</a></li>
                        <li><a href="{{ asset('/admin') }}" style="color: white">控制台</a></li>
                        <li><a href="{{ asset('/logout') }}" style="color: white">退出</a></li>
                    @else
                        <li><a href="{{ asset('login') }}" style="color: white">登录</a></li>
                        <li><a href="{{ asset('register') }}" style="color: white">注册</a></li>
                    @endif
                    <li><a href="{{ asset('contact') }}" style="color: white">联系</a></li>
                </ul>
            </div>
        </div>
    </nav>
    @yield('content')

    <footer class="footer navbar-fixed-bottom">
        <div class="container footer">
            <a href="http://www.miitbeian.gov.cn/">@2016-2018 灯塔笔记 | 粤ICP备16004706号-1</a>
        </div>
    </footer>
</div>

<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>