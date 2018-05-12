<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Mr.Tin - @if($_SERVER['REQUEST_URI']=='/auth/login')login @else register @endif</title>
    <!-- load stylesheets -->
    {{--<link rel="stylesheet" href="http://fonts.useso.com/css?family=Open+Sans:300,400">    <!-- Google web font "Open Sans" -->    --}}
    <link rel="stylesheet" href="/Auth/font-awesome-4.6.3/css/font-awesome.min.css">            <!-- Font awesome -->
    <link rel="stylesheet" href="/Auth/css/bootstrap.min.css">                                  <!-- Bootstrap style -->
    <link rel="stylesheet" href="/Auth/css/hero-slider-style.css">                              <!-- Hero slider style (https://codyhouse.co/gem/hero-slider/) -->
    <link rel="stylesheet" href="/Auth/css/magnific-popup.css">                                 <!-- Magnific popup style (http://dimsemenov.com/plugins/magnific-popup/) -->
    <link rel="stylesheet" href="/Auth/css/templatemo-style.css">                               <!-- Templatemo style -->

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
    <body>
        <!-- Content -->
        <div class="cd-hero">

            <!-- Navigation -->        
            <div class="cd-slider-nav">
                <nav class="navbar">

                    <button class="navbar-toggler hidden-md-up" type="button" data-toggle="collapse" data-target="#tmNavbar">
                        &#9776;
                    </button>
                    <div class="collapse navbar-toggleable-sm text-xs-center text-uppercase tm-navbar" id="tmNavbar">
                        <ul class="nav navbar-nav">
                            <li class="nav-item @if($_SERVER['REQUEST_URI']=='/auth/login') active selected @endif">
                                <a class="nav-link" href="#0" data-no="1">登录 <span class="sr-only">(current)</span></a>
                            </li>
                            <li class="nav-item @if($_SERVER['REQUEST_URI']=='/auth/register') active selected @endif">
                                <a class="nav-link" href="#0" data-no="2">注册</a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <ul class="cd-hero-slider">  <!-- autoplay -->
                <li @if($_SERVER['REQUEST_URI']=='/auth/login') class="selected" @endif>
                    <div class="cd-full-width">

                        <div class="container-fluid js-tm-page-content" data-page-no="1">

                            <div class="tm-contact-page">

                                <div class="row">

                                    <div class="col-xs-12">
                                        <!-- Form Error List -->
                                        @if (count($errors) > 0)
                                            <h2 class="tm-section-title">错误信息：</h2>
                                            <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p style="color: #ff0000">{{ $error }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (old('error'))
                                            <h2 class="tm-section-title">错误信息：</h2>
                                            <div class="alert alert-danger">
                                                    <p style="color: #ff0000">{{ old('error') }}</p>
                                            </div>
                                        @endif

                                    </div>

                                </div>

                                <!-- contact form -->
                                <div class="row">
                                    <form action="/auth/login" method="post" class="tm-contact-form">
                                        <input type="hidden" type="text" id="_token" name="_token" value="{{ csrf_token() }}">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <input type="email" id="contact_email_login" name="email" class="form-control" placeholder="邮箱"  required/>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" id="contact_password_login" name="password" class="form-control" placeholder="密码"  required/>
                                            </div>
                                
                                        </div>

                                        <div class="col-xs-12">
                                            <button type="submit" class="pull-xs-right tm-submit-btn">Submit</button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>

                    </div> <!-- .cd-full-width -->
                </li>

                <li @if($_SERVER['REQUEST_URI']=='/auth/register') class="selected" @endif>
                    <div class="cd-full-width">

                        <div class="container-fluid js-tm-page-content" data-page-no="2">

                            <div class="tm-contact-page">

                                <div class="row">

                                    <div class="col-xs-12">

                                        <!-- Form Error List -->
                                        @if (count($errors) > 0)
                                            <h2 class="tm-section-title">错误信息：</h2>
                                            <div class="alert alert-danger">
                                                @foreach ($errors->all() as $error)
                                                    <p style="color: #ff0000">{{ $error }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if (old('error'))
                                            <h2 class="tm-section-title">错误信息：</h2>
                                            <div class="alert alert-danger">
                                                <p style="color: #ff0000">{{ old('error') }}</p>
                                            </div>
                                        @endif

                                    </div>

                                </div>

                                <!-- contact form -->
                                <div class="row">
                                    <form action="/auth/register" method="post" class="tm-contact-form">
                                        <input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" id="checkNum" name="check_name" value="{{ old('recodId') }}">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                            <div class="form-group">
                                                <input type="text" id="contact_name" name="username" class="form-control" placeholder="请输入昵称" @if ( old('username') ) value="{{ old('username') }}" @endif required/>
                                            </div>
                                            <div class="form-group">
                                                <input type="email" id="contact_email" name="email" class="form-control" placeholder="请输入邮箱" @if ( old('email') ) value="{{ old('email') }}" @endif required/>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" id="contact_password" name="password" class="form-control" placeholder="请输入密码" @if ( old('password') ) value="{{ old('password') }}" @endif required/>
                                            </div>
                                            <div class="form-group">
                                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="确认密码" @if ( old('password_confirmation') ) value="{{ old('password_confirmation') }}" @endif required/>
                                            </div>
                                            <div class="form-group" style="position: relative">
                                                <input type="text" id="check_number" name="check_number" class="form-control" placeholder="输入验证码"  required/>
                                                <span style="display: block;position: absolute;width:30%;line-height: 51px;height: 51px;background-color: rgba(255,255,255,0.5);top: 0px;right: 0px;"><a id="getCheckNumber" status="0" >获取验证码</a></span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <button type="submit" class="pull-xs-right tm-submit-btn">Submit</button>
                                        </div>
                                    </form>
                                </div>

                            </div>

                        </div>

                    </div> <!-- .cd-full-width -->

                </li>

            </ul> <!-- .cd-hero-slider -->
            
            <footer class="tm-footer">
            
                <div class="tm-social-icons-container">
                    {{--<a href="#" class="tm-social-link"><i class="fa fa-facebook"></i></a>--}}
                    {{--<a href="#" class="tm-social-link"><i class="fa fa-google-plus"></i></a>--}}
                    {{--<a href="#" class="tm-social-link"><i class="fa fa-twitter"></i></a>--}}
                    {{--<a href="#" class="tm-social-link"><i class="fa fa-behance"></i></a>--}}
                    {{--<a href="#" class="tm-social-link"><i class="fa fa-linkedin"></i></a>--}}
                </div>
                
                <p class="tm-copyright-text">Copyright &copy; 2016 Mr.Tin </p>

            </footer>
                    
        </div> <!-- .cd-hero -->
        

        <!-- Preloader, https://ihatetomatoes.net/create-custom-preloading-screen/ -->
        <div id="loader-wrapper">
            
            <div id="loader"></div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>

        </div>
        
        <!-- load JS files -->
        <script src="/Auth/js/jquery-1.11.3.min.js"></script>         <!-- jQuery (https://jquery.com/download/) -->
        <script src="/Auth/js/tether.min.js"></script> <!-- Tether for Bootstrap (http://stackoverflow.com/questions/34567939/how-to-fix-the-error-error-bootstrap-tooltips-require-tether-http-github-h) -->
        <script src="/Auth/js/bootstrap.min.js"></script>             <!-- Bootstrap js (v4-alpha.getbootstrap.com/) -->
        <script src="/Auth/js/hero-slider-main.js"></script>          <!-- Hero slider (https://codyhouse.co/gem/hero-slider/) -->
        <script src="/Auth/js/jquery.magnific-popup.min.js"></script> <!-- Magnific popup (http://dimsemenov.com/plugins/magnific-popup/) -->
        
        <script>

            function adjustHeightOfPage(pageNo) {
               
                // Get the page height
                var totalPageHeight = 15 + $('.cd-slider-nav').height()
                                        + $(".cd-hero-slider li:nth-of-type(" + pageNo + ") .js-tm-page-content").height() + 160
                                        + $('.tm-footer').height();

                // Adjust layout based on page height and window height
                if(totalPageHeight > $(window).height()) 
                {
                    $('.cd-hero-slider').addClass('small-screen');
                    $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", totalPageHeight + "px");
                }
                else 
                {
                    $('.cd-hero-slider').removeClass('small-screen');
                    $('.cd-hero-slider li:nth-of-type(' + pageNo + ')').css("min-height", "100%");
                }

            }

            /*
                Everything is loaded including images.
            */
            $(window).load(function(){
                var t = 40;
                adjustHeightOfPage(1); // Adjust page height

                /* Gallery pop up
                -----------------------------------------*/
                $('.tm-img-gallery').magnificPopup({
                    delegate: 'a', // child items selector, by clicking on it popup will open
                    type: 'image',
                    gallery:{enabled:true}                
                });

                /* Collapse menu after click 
                -----------------------------------------*/
                $('#tmNavbar a').click(function(){
                    $('#tmNavbar').collapse('hide');

                    adjustHeightOfPage($(this).data("no")); // Adjust page height       
                });

                /* Browser resized 
                -----------------------------------------*/
                $( window ).resize(function() {
                    var currentPageNo = $(".cd-hero-slider li.selected .js-tm-page-content").data("page-no");
                    adjustHeightOfPage( currentPageNo );
                });
        
                // Remove preloader
                // https://ihatetomatoes.net/create-custom-preloading-screen/
                $('body').addClass('loaded');

                $("#getCheckNumber").click(function(){
                    //伪造Form表单提交contact_email
                    var email = $("#contact_email").val();
                    var cliBtn = $("#getCheckNumber");
                    if(!email){
                        alert("邮箱不能为空");
                    }

                    if( parseInt($(cliBtn).attr("status")) == 1){
                        return;
                    }
                    $(cliBtn).attr("status",1);
                    setCounDown();
                    $.ajax({
                        url:"/auth/getchecknum",
                        type:"post",
                        data:{email:email,_token:$("#_token").val()},
                        success:function(data){
                            var data = eval("(" + data + ")");
                            console.log(data);
                            if(data.status == -1){
                                alert(data.message);return;
                            }
                            $("#checkNum").val(data.record);
                        }
                    })
                })

                function setCounDown(){
                    $("#getCheckNumber").text('('+t+')秒后重发');
                    if(t == 0){
                        clearTimeout(t_);
                        t = 40;
                        $("#getCheckNumber").text("获取验证码").attr("status",0);
                    }else{
                        var t_ = setTimeout(function(){
                            setCounDown(t);
                            t--;
                        },1000);
                    }
                    
                }
            });
        </script>             

</body>
</html>