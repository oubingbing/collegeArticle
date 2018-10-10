<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
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

    <nav class="navbar navbar-inverse">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="/">灯塔笔记</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="{{ asset('/') }}">首页</a></li>
                    <li><a href="{{ asset('/admin') }}">控制台</a></li>
                        <li><a href="{{ asset('login') }}">登录</a></li>
                        <li><a href="{{ asset('register') }}">注册</a></li>
                    <li><a href="{{ asset('contact') }}">联系</a></li>
                    <li><a href="{{ asset('about') }}">公众号</a></li>

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