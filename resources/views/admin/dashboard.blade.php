@extends('layouts/admin')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/element-ui/lib/theme-chalk/index.css">
    <div class="x-body layui-anim layui-anim-up" id="app">
        <blockquote class="layui-elem-quote">你好，小程序注册之后还需要经过叶子的审核才能用，请加叶子微信：13425144866</blockquote>
        <fieldset class="layui-elem-field">
            <legend>用户数据统计</legend>
            <div class="layui-field-box">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <div class="layui-card-body">
                            <div class="layui-carousel x-admin-carousel x-admin-backlog" lay-anim="" lay-indicator="inside" lay-arrow="none" style="width: 100%; height: 90px;">
                                <div carousel-item="">
                                    <ul class="layui-row layui-col-space10 layui-this">
                                        <li class="layui-col-xs2" style="text-align: center">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>今日新增人数</h3>
                                                <p>
                                                    <cite></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2" style="text-align: center">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>今日浏览人数</h3>
                                                <p>
                                                    <cite></cite></p>
                                            </a>
                                        </li>
                                        <li class="layui-col-xs2" style="text-align: center">
                                            <a href="javascript:;" class="x-admin-backlog-body">
                                                <h3>总人数</h3>
                                                <p>
                                                    <cite></cite></p>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <fieldset class="layui-elem-field">
            <legend>小程序信息</legend>
            <div class="layui-field-box">

            </div>
        </fieldset>
    </div>
    <script src="https://cdn.bootcss.com/vue/2.5.16/vue.min.js"></script>
    <script src="https://cdn.bootcss.com/axios/0.17.1/axios.min.js"></script>
    <script src="https://unpkg.com/element-ui/lib/index.js"></script>
    <script>
        $(document).ready(function(){
            $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});


        });
    </script>
    <script>
        var app = new Vue({
            el: '#app',
            data: {

            },
            created:function () {

            },
            methods:{

            },
        });
    </script>
    @endsection