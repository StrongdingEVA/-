<!DOCTYPE html>
<html>
<head>
    <title>懒人日志</title>
    <link href="/Home/css/bootstrap.css" rel='stylesheet' type='text/css' />
    <script type="text/javascript" src="/Home/js/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/Home/js/blog.js"></script>
    <link href="/Home/css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link rel="stylesheet" href="/Home/css/my.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content="text/html; charset=utf-8" />
    <meta name="keywords" content="懒人,日志,个人博客" />
    <script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
    <script type="text/javascript" src="/Home/js/bootstrap.js"></script>
    <script type="text/javascript" src="/Home/js/layer.js"></script>
    <script type="text/javascript" src="/Home/js/responsiveslides.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
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
        .top-menu .dropdown-menu li{
            display: block;
            margin-right:0px;
        }
    </style>
</head>
<body>
<!-- header-section-starts-here -->
<div class="header">
    <div class="header-top">
        <div class="wrap">
            <input type="hidden" id="userLogin" value="{{count($userInfo)}}">
            <div class="top-menu">
                <ul>
                    <li><a href="/">{{$userInfo ? $userInfo['username'] : '游客'}}&nbsp;&nbsp;<img src="{{$userInfo ? $userInfo['logo'] : '/upload/userimg/default.png'}}" height="28px" alt="{{$userInfo ? $userInfo['username'] : '游客'}}"></a></li>
                    <li><a href="/publish">发布文章</a></li>
                    <li>
                        <a href="#" id="dropdown-toggle" class="dropdown-toggle"  data-toggle="dropdown">
                            我的消息
                        </a>
                    </li>
                    @if($userInfo)
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
                    //var t =setInterval(loadMessage,15000);
                    function loadMessage(){
                        $.phpajax("/getmsg","get","",true,"json",function(data){
                            data = eval("("+ data +")");
                            if(data.status == -1){
                                clearInterval(t);
                                return;
                            }
                            if(data.ext.length){
                                htmlStr = "";
                                htmlStr += '<ul class="dropdown-menu" style="top:42px;left:160px">';
                                for(var i = 0 in data.ext){
                                    htmlStr += '<li><a href="/article_detail/'+ data.ext[i].article_id +'/'+ data.ext[i].comment_id +'/'+ data.ext[i].ans_id +'/' + data.ext[i].comtype + '">'+ data.ext[i].message_disc +'</a></li>';
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
                            <span class="sr-only">导航</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                    </div>
                    <!--/.navbar-header-->

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="/blog/world">世界</a></li>
                            <li class="active"><a href="/blog/friend">好友</a></li>
                            <li class="active"><a href="/blog/own">我的</a></li>
                            <li class="active"><a href="/blog/own">收藏</a></li>
                            <li class="active"><a href="/picturewall/old">热图</a></li>
                            <div class="clearfix"></div>
                        </ul>
                        <div class="search">
                            <!-- start search-->
                            <div class="search-box">
                                <div id="sb-search" class="sb-search">
                                    <form>
                                        <input class="sb-search-input" placeholder="搜索用户/文章" value="{{$search}}" type="search" name="search" id="search">
                                        <input class="sb-search-submit" type="button" onclick="javascript:void(window.location.href='/blog/{{$key}}}/'+$('#search').val())" value="">
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
            @foreach($scrollArticle as $item)
            <div class="marquee1">
                <a class="breaking" href="/article_detail/{{$item['id']}}">>>{{$item['article_title']}}</a></div>
            @endforeach
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
        <script type="text/javascript" src="/Home/js/jquery.marquee.min.js"></script>
        <script>
            $('.marquee').marquee({ pauseOnHover: true });
        </script>
    </div>
</div>
    @yield("content")
</body>
</html>