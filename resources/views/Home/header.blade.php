<!DOCTYPE html>
<html>
<head>
    <title>Mr.Tin Blog</title>
    <link href="/Home/css/bootstrap.css" rel='stylesheet' type='text/css' />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script type="text/javascript" src="/Home/js/jquery.min.js"></script>
    <!-- Custom Theme files -->
    <link href="/Home/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <!-- Custom Theme files -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=utf-8" />
    <meta name="keywords" content="Express News Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template,
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <!-- for bootstrap working -->
    <script type="text/javascript" src="/Home/js/bootstrap.js"></script>
    <!-- //for bootstrap working -->
    <!-- web-fonts -->
    {{--<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>--}}
    {{--<link href='https://fonts.googleapis.com/css?family=Varela+Round' rel='stylesheet' type='text/css'>--}}
    <script type="text/javascript" src="/Home/js/responsiveslides.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Home/js/socket.io.js"></script>
    <script>

    </script>
    <script type="text/javascript" src="/Home/js/move-top.js"></script>
    <script type="text/javascript" src="/Home/js/easing.js"></script>
    <!--/script-->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event){
                event.preventDefault();
                $('html,body').animate({scrollTop:$(this.hash).offset().top},900);
            });
        });
    </script>
    <style>
        a{text-decoration: none}
        a:hover{text-decoration: none}
        .aclass{display: block;margin: 0px auto;}
        .caption a{min-height: 20px;}
        .page-class{width: 100%;margin-top: 100px}
    </style>
</head>
<body>
<!-- header-section-starts-here -->
<div class="header">
    <div class="header-top">
        <div class="wrap">
            <input type="hidden" id="userLogin" value="{{count(@\Illuminate\Support\Facades\Auth::user())}}">
            <div class="top-menu">
                <ul>
                    <li><a href="/">{{@\Illuminate\Support\Facades\Auth::user() ? @\Illuminate\Support\Facades\Auth::user()->username : '游客'}}&nbsp;&nbsp;<img src="{{@\Illuminate\Support\Facades\Auth::user() ? @\Illuminate\Support\Facades\Auth::user()->logo : '/upload/userimg/default.png'}}" height="28px" alt="{{@\Illuminate\Support\Facades\Auth::user() ? @\Illuminate\Support\Facades\Auth::user()->username : '游客'}}"></a></li>
                    <li><a href="/articles">发布文章</a></li>
                    <li><a href="contact.html">我的收藏</a></li>
                    <li><a href="about.html">关于我们</a></li>
                    <li>
                        <a href="#" id="dropdown-toggle" class="dropdown-toggle" data-toggle="dropdown">
                            我的消息
                        </a>
                    </li>
                    @if(count(@\Illuminate\Support\Facades\Auth::user())>0)
                        <li><a href="/auth/logout">退出</a></li>
                    @else
                        <li><a href="/auth/login">登录</a></li>
                        <li><a href="/auth/login">注册</a></li>
                    @endif
                </ul>
            </div>
            <script>
                $(function(){
                    loadMessage();
                    var t =setInterval(loadMessage,15000);
                    function loadMessage(){
                        $.phpajax("/getusermessage","get","",true,"json",function(data){
                            data = eval("("+ data +")");
                            if(data.status == -1){
                                clearInterval(t);
                                return;
                            }
                            if(data.ext.length){
                                htmlStr = "";
                                htmlStr += '<ul class="dropdown-menu">';
                                for(var i = 0 in data.ext){
                                    htmlStr += '<li><a href="/article_detail_2/'+ data.ext[i].etc +'/'+ data.ext[i].comval +'/'+ data.ext[i].comtype +'/' + data.ext[i].id + '">'+ data.ext[i].message_disc +'</a></li>';
                                }
                                htmlStr += '</ul>';
                                $(".dropdown-menu").remove();
                                $("#dropdown-toggle").html('我的消息<b class="caret"></b>').after(htmlStr);
                            }
                        });
                    }
                })
            </script>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="logo text-center">
            <a href="index.html"><img src="/Home/images/logo.jpg" alt="" /></a>
        </div>
        <div class="navigation">
            <nav class="navbar navbar-default" role="navigation">
                <div class="wrap">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
                    <!--/.navbar-header-->

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li @if($actionLi == 0) class="active" @endif><a href="/">综合</a></li>
                            @foreach($cateInfo as $val)
                                <li><a href="/categoryarticle/{{$val['id']}}" @if($actionLi == $val['id']) class="active" @endif>{{$val['category_name']}}</a></li>
                            @endforeach
                            <li @if($actionLi == 5) class="active" @endif><a href="/picturewall/old">热图</a></li>
                            <div class="clearfix"></div>
                        </ul>
                        <div class="search">
                            <!-- start search-->
                            <div class="search-box">
                                <div id="sb-search" class="sb-search">
                                    <form>
                                        <input class="sb-search-input" placeholder="Enter your search term..." type="search" name="search" id="search">
                                        <input class="sb-search-submit" type="submit" value="">
                                        <span class="sb-icon-search"> </span>
                                    </form>
                                </div>
                            </div>
                            <!-- search-scripts -->
                            <script type="text/javascript" src="/Home/js/classie.js"></script>
                            <script type="text/javascript" src="/Home/js/uisearch.js"></script>
                            <script>
                                new UISearch( document.getElementById( 'sb-search' ) );
                            </script>
                            <!-- //search-scripts -->
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <!--/.navbar-collapse-->
                <!--/.navbar-->
        </div>
        </nav>
    </div>
</div>

<!-- header-section-ends-here -->
<div class="wrap">
    <div class="move-text">
        <div class="breaking_news">
            <h2>最新动态</h2>
        </div>
        <div class="marquee">
            <div class="marquee1"><a class="breaking" href="single.html">>>The standard chunk of Lorem Ipsum used since the 1500s is reproduced..</a></div>
            <div class="marquee2"><a class="breaking" href="single.html">>>At vero eos et accusamus et iusto qui blanditiis praesentium voluptatum deleniti atque..</a></div>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <script type="text/javascript" src="/Home/js/jquery.marquee.min.js"></script>
        <script>
            $('.marquee').marquee({ pauseOnHover: true });
            //@ sourceURL=pen.js
        </script>
    </div>
</div>

    @yield("content")

<!---->
</body>
<script>
    //连接socket服务器
    var socket = io('http://118.31.20.94:11223');
    socket.on('connection', function (data) {
        console.log(222222,data);
    });

    socket.on('disconnect', function(data){
        console.log(111111,data);return;
    });
</script>
</html>