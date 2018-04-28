@extends("Home.foot")

@extends("Home.header")

@section('content')
	<!-- 酷播开始 -->
	<style>
		.video { OVERFLOW: hidden; WIDTH: 100%; POSITION: relative}
		.close_light_bg {DISPLAY: none; BACKGROUND: #000; FILTER: alpha(opacity = 95); LEFT: 0px; WIDTH: 100%; POSITION: absolute; TOP: 0px; HEIGHT: 100%; opacity: .95}

		body { padding:10px;}
		div.help { line-height:32px; font-size:14px;}
	</style>
	<script type="text/javascript">
        <!--
        function getLight(pars){if(pars=="open"){close_light(this)}else{close_light(this)}};function thisMovie(movieName){if(navigator.appName.indexOf("Microsoft")!=-1){return window[movieName]}else{return document[movieName]}}
        //-->
	</script>
	<script type="text/javascript" src="http://www.cuplayer.com/CuPlayer/js/stat.js"></script>
	<!-- 酷播结束 -->

<!-- content-section-starts-here -->
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
<script type="text/javascript" charset="utf-8" src="/Home/js/page.js"></script>
<link rel="stylesheet" href="/Home/css/normalize.css">
<style type="text/css">
	#gallery-wrapper {position: relative;max-width: 75%;width: 75%;margin:50px auto;}
	img.thumb {width: 100%;max-width: 100%;height: auto;}
	.white-panel {position: absolute;background: white;border-radius: 5px;box-shadow: 0px 1px 2px rgba(0,0,0,0.3);padding: 10px;}
	.white-panel h1 {font-size: 1em;}
	.white-panel h1 a {color: #A92733;}.white-panel:hover {box-shadow: 1px 1px 10px rgba(0,0,0,0.5);margin-top: -5px;-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;}
	.white-panel p{color: #282828;}
	.is-liked{color:#ff1111}
</style>
<!--[if IE]>
<script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
<![endif]-->
<style>
	.like-ul{width:100%;height: 30px;padding: 3px;overflow: auto;}
	.like-ul li{list-style: none;display: inline-block;float: left;padding: 0px 5px 0px;}
	.like-ul li a{cursor: pointer;text-decoration: none;}
	.like-ul li a:hover{color: #f00;}
	.article-content p{text-indent:3em}
	.acolor{color: #f00;}
	.acolor-h{color: #4b4b4b;}
	.user-info-1{width:100%;margin-bottom: 10px;text-align: center}
	.user-info-2{line-height: 24px;font-size: 16px;margin-bottom: 2px;text-align: center}
	.user-info-2 a{text-decoration: none;cursor: pointer;display: inline-block; font-size: 14px;}
	.force-overflow .categories li{margin-top: 3px}
	.pagination_s{display: inline-block;padding-left: 0;margin: 20px 0;border-radius: 4px;padding: 0;text-align: right;}
	.pagination_s li{text-align: center;}
	.pagination_s a{display: block;min-width:10px;padding-left: 5px;padding-right: 5px;margin: 0px auto;}
	.page{text-align: center}
	.article-thumb{width: 100%;margin: 5px 0px 5px}
</style>
	<div class="main-body">
		@if(old("error"))
		<div id="errDiv" style="position: fixed;width: 300px;left: 45%;z-index:1002;">
			<div class="alert alert-danger" style="text-align: center;font-size: 16px;">
				<p style="color: #ff0000">{{ old('error') }}</p>
				<input type="hidden" id="editorValueHidden" value="{{old("editorValue")}}">
			</div>
		</div>
		@endif
		<div class="wrap">
		<ol class="breadcrumb">
			  <li><a href="/">首页</a></li>
			  <li class="active">文章详情</li>
			</ol>
			<div class="single-page">
			<div class="col-md-2 share_grid">
				<h3>分享</h3>
				<ul>
					<li>
						<a href="#">
							<i class="facebook"></i>
							<div class="views">
								<span>SHARE</span>
								<label>180</label>
							</div>
							<div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="twitter"></i>
							<div class="views">
								<span>TWEET</span>
								<label>355</label>
							</div>
							<div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="linkedin"></i>
							<div class="views">
								<span>SHARES</span>
								<label>28</label>
							</div>
							<div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="pinterest"></i>
							<div class="views">
								<span>PIN</span>
								<label>16</label>
							</div>
							<div class="clearfix"></div>
						</a>
					</li>
					<li>
						<a href="#">
							<i class="email"></i>
							<div class="views">
								<span>Email</span>
							</div>
							<div class="clearfix"></div>
						</a>
					</li>
				</ul>
			</div>
			<div class="col-md-6 content-left single-post">
				<div class="blog-posts">
					<h3 class="post">{{$articleInfo['article_title']}}</h3>
					<div class="last-article">
						<p class="artext">
							{{$articleInfo['article_disc']}}
						</p>
						@if($articleInfo['category'] != 4)
						<div class="article-thumb">
							<img src="{{$articleInfo['article_source_pic']}}" width="100%" alt="图片加载失败！">
						</div>
						@elseif($articleInfo['article_video'])
                            <div class="video" id="CuPlayer"><b><img src="/Home/images/loading.gif"  /> 网页视频播放器加载中，请稍后...<a href="http://www.cuplayer.com/cuplayer" target="_blank">点此升级&gt;&gt;</a></b></div>
                            <!--极酷阳光播放器/代码开始-->
                            <script type="text/javascript" src="/Home/js/swfobject.js"></script>
                            {{--<div class="video" id="CuPlayer"><b><img src="/Home/images/loading.gif"  /> 网页视频播放器加载中，请稍后...<a href="http://www.cuplayer.com/cuplayer" target="_blank">点此升级&gt;&gt;</a></b></div>--}}
                            <script type="text/javascript">
                                var so = new SWFObject("/Home/js/player.swf","myCuPlayer","700","420","9","#000000");
                                so.addParam("allowfullscreen","true");
                                so.addParam("allowscriptaccess","always");
                                so.addParam("wmode","opaque");
                                so.addParam("quality","high");
                                so.addParam("salign","lt");
                                //播放器配置文件-----------------------------
                                so.addVariable("JcScpFile","/Home/js/CuSunV4set.xml"); //配置文件地址
                                //视频文件及略缩图--------------------------
                                //so.addVariable("JcScpServer","rtmp://www.yoursite.com/vod"); //流媒体服务器地址
                                so.addVariable("JcScpVideoPath","{{$articleInfo['article_video']}}"); //视频地址
                                //so.addVariable("JcScpVideoPathHD","http://demo.cuplayer.com/file/test.mp4"); //高清视频地址
                                so.addVariable("JcScpImg","images/startpic.jpg"); //视频图片
                                so.addVariable("JcScpAutoPlay","yes"); //是否自动播放
                                so.addVariable("JcScpStarTime","0"); //起始时间点(暂未启用)
                                so.addVariable("JcScpEndTime","0"); //结束时间点
                                so.addVariable("JcScpCuePointInfo",""); //提示点信息
                                so.addVariable("JcScpCuePointTime",""); //提示点秒数值
                                //-前置Flash广告-----------------------------
                                so.addVariable("ShowJcScpAFront","no"); //是否显示前置广告
                                so.addVariable("JcScpCountDownsPosition","top-left"); //倒计时位置
                                so.addVariable("JcScpCountDowns","5"); //广告倒计时位置
                                so.addVariable("JcScpAFrontW","700"); //前置广告宽度
                                so.addVariable("JcScpAFrontH","420"); //前置广告宽度
                                so.addVariable("JcScpAFrontPath","other/a730x454_01.jpg|other/a730x454_02.jpg"); //前置广告地址
                                so.addVariable("JcScpAFrontLink","http://www.cuplayer.com/CuPlayer/link/L011.html|http://www.cuplayer.com/CuPlayer/link/L012.html"); //前置广告链接
                                //-视频广告参数-----------------------------
                                so.addVariable("ShowJcScpAVideo","no"); //是否显示前置视频广告
                                //so.addVariable("JcScpAVideoServer","rtmp://www.yoursite.com/vod"); //前置视频广告服务器
                                so.addVariable("JcScpAVideoPath","http://promotion.geely.com/xindihao/media/video1.mp4|http://promotion.geely.com/xindihao/media/video2.mp4"); //前置视频广告地址
                                so.addVariable("JcScpAVideoLink","http://www.cuplayer.com/CuPlayer/link/L021.html|http://www.cuplayer.com/CuPlayer/link/L022.html"); //前置视频广告链接
                                //-暂停广告参数-----------------------------
                                so.addVariable("ShowJcScpAPause","no"); //是否显示暂停广告
                                so.addVariable("JcScpAPauseW","300"); //暂停广告地址
                                so.addVariable("JcScpAPauseH","250"); //暂停广告高度
                                so.addVariable("JcScpAPausePath","other/a300x250_01.jpg|other/a300x250_02.jpg"); //暂停广告地址
                                so.addVariable("JcScpAPauseLink","http://www.cuplayer.com/CuPlayer/link/L031.html|http://www.cuplayer.com/CuPlayer/link/L032.html"); //暂停广告链接
                                //-角标广告参数-----------------------------
                                so.addVariable("ShowJcScpACorner","no"); //是否显示角标广告
                                so.addVariable("JcScpACornerW","85"); //角标广告宽度
                                so.addVariable("JcScpACornerH","50"); //角标广告高度
                                so.addVariable("JcScpACornerPath","other/a90x50_01.swf|other/a90x50_01.swf"); //角标广告地址
                                so.addVariable("JcScpACornerPosition","bottom-right"); //角标广告位置
                                so.addVariable("JcScpACornerLink","http://www.cuplayer.com/CuPlayer/link/L041.html|http://www.cuplayer.com/CuPlayer/link/L042.html"); //角标广告链接
                                //-后置广告参数-----------------------------
                                so.addVariable("ShowJcScpAEnd","no"); //是否显示后置广告
                                so.addVariable("JcScpAEndW","400"); //后置广告宽度
                                so.addVariable("JcScpAEndH","300"); //后置广告高度
                                so.addVariable("JcScpAEndPath","other/a400x300_01.swf|other/a400x300_01.swf"); //后置广告地址
                                so.addVariable("JcScpAEndLink","http://www.cuplayer.com/CuPlayer/link/L051.html|http://www.cuplayer.com/CuPlayer/link/L051.html"); //后置广告链接
                                //-滚动文字广告参数-----------------------------
                                so.addVariable("ShowJcScpAMoveText","no"); //是否滚动文字广告
                                //-----------------------------------------
                                so.addVariable("JcScpSharetitle","标题信息"); //视频标题信息
                                so.write("CuPlayer");
                            </script>
                            {{--<script language=javascript src="js/jquery-1.4.2.min.js" type=text/javascript></script>--}}
                            <script language=javascript src="/Home/js/action.js" type=text/javascript></script>
                            <!--极酷阳光播放器/代码结束-->

                        @endif
						<br>
						<span class="article-content">
							{!! $articleInfo['article_content'] !!}
						</span>

						<ul class="like-ul">
							<li>
								<a class="span_link" href="javascript:void(0)">
									<span class="glyphicon glyphicon-comment"></span>{{$articleInfo['comments']}}
								</a>
								<a class="span_link" href="javascript:void(0)">
									<span class="glyphicon glyphicon-eye-open"></span>{{$articleInfo['views']}}
								</a>
								@if($articleInfo['isCollector'] > 0)
									<a class="span_link collecManage" style="color: #f00" type="1" id="cancelCollection" data="{{$articleInfo['id']}}" href="javascript:void(0)">
										<span class="glyphicon glyphicon-thumbs-up"></span>{{$articleInfo['collections']}}
									</a>
								@else
									<a class="span_link collecManage" type="0" id="cancelCollection" data="{{$articleInfo['id']}}" href="javascript:void(0)">
										<span class="glyphicon glyphicon-thumbs-up"></span>{{$articleInfo['collections']}}
									</a>
								@endif
							</li>
						</ul>

						<ul class="categories">
							@foreach($articleInfo['collector'] as $val)
								<li><a href="#"><img style="width: 40px;height: 40px;" src="{{$val['logo']}}" alt="{{$val['username']}}"></a></li>
							@endforeach
						</ul>
						<div class="clearfix"></div>

					<div class="response">
						@if(count($articleComment) > 0)
							<h4>评论</h4>
						@else
							<h4>还没评论哦~~!</h4>
						@endif
						<input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
						@foreach($articleComment as $val)
							{{--<form action="/auth/authanswer" method="post">--}}
							<div class="media response-info" style="position: relative">
								<div class="media-left response-text-left" id="c-{{$val["id"]}}">
									<a href="#" style="width:80px">
										<img class="media-object" src="{{$val["logo"]}}" alt=""/>
									</a>
								</div>
								<h5><a href="#">{{ $val["username"] }}</a></h5>
								<div class="media-body response-text-right">
									{!! $val["article_comment"] !!}
									<ul>
										<li>{{$val["created_at"]}}</li>
										<li><a class="createedui" articleId="{{$val["article_id"]}}" commentId="{{$val["id"]}}" this-user="{{$val["user_id"]}}" href="javascript:void(0);">回复</a></li>
									</ul>
									<div class="answ">
										@if($val["answer"])
											@foreach($val["answer"] as $va)
												<div class="media response-info">
													<div class="media-left response-text-left" id="a-{{$va["id"]}}">
														<a href="#" style="width:30px">
															<img class="media-object" src="{{$val["logo"]}}" alt=""/>
														</a>
													</div>
													<div class="media-body response-text-right">
														<a href="">{{$va["get_from_user_info"]["username"]}}</a>
														@if($va["get_to_user_info"]["username"])
															 回复 <a href="">{{$va["get_to_user_info"]["username"]}}</a>
														@endif
														:
														<span style="text-indent:20px">{{ $va["article_comment"] }}</span>
														<ul>
															<li>{!! $va["created_at"] ? $va["created_at"] : "" !!}</li>
															<li><a class="createedui" articleId="{{$val["article_id"]}}" commentId="{{$val["id"]}}" this-user="{{$va["get_from_user_info"]["id"]}}" href="javascript:void(0);">回复</a></li>
														</ul>
													</div>
													<div class="clearfix"> </div>
												</div>
											@endforeach
										@endif

										@if(isset($val["subAns"]) && $val["subAns"]>0)
											<div id="page-{{$val["id"]}}" class="page">
												<script>
                                                    new loadpage({
                                                        nowPage: {{$val["nowPage"]}},
                                                        totalPage:{{$val["totalPage"]}},
                                                        sub:{{$val["subAns"]}},
                                                        btns:7,
                                                        el: "page-{{$val["id"]}}",
                                                        url: '/getansajax/{{$val["id"]}}/'
                                                    })
												</script>
											</div>
										@endif
									</div>
									<div style="width: 70%;min-height: 1px;display: none;position: fixed;top:1px;z-index:1001">
										<div id="{{$val["id"]}}"></div>
										<div class="input-group mar-top" id="btndiv{{$val["id"]}}" style="margin-top: 20px;display: none;position:absolute;right: 30px">
											<input id="createedui{{$val["id"]}}" data="{{$val["id"]}}" type="button" articleid="{{$articleInfo['id']}}" commentId="0" touserid="0" class="btn btn-primary btn-sm edui" value="回复">
										</div>
									</div>
								</div>
								<div class="clearfix"> </div>
							</div>
							{{--</form>--}}
						@endforeach
					</div>
						<script	>
                            $(function(){

                            })
						</script>
					<div class="page-class">
						{!! $paginator->render() !!}
					</div>

					<div class="coment-form">
						<form action="/auth/authcomment" method="post">
							<input type="hidden" name="articleid" value="{{ $articleInfo['id'] }}">
							<input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
							<div class="grid_3 grid_5" id="editor"></div>
							<script>
								$(function(){
									//实例化编辑器
									//建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
									var ue = UE.getEditor('editor');
								})
							</script>
							<input type="submit" value="发布评论" >
						</form>
					</div>

					<!--related-posts-->
					<div class="row related-posts" id="gallery-wrapper">
						@foreach($articleMastInfo["want"]['data'] as $val)
							<article class="white-panel">
								<a href="/article_detail/{{$val['id']}}">
									<img src="@if($val['article_thumb']) {{$val['article_thumb']}} @else /Home/images/logo.jpg @endif" class="thumb" title="{{$val['article_title']}}">
								</a>
							</article>
						@endforeach
					</div>

					<script>window.jQuery || document.write('<script src="js/jquery-1.11.0.min.js"><\/script>')</script>
					<script src="/Home/js/pinterest_grid.js"></script>
					<script>
						$("#gallery-wrapper").pinterest_grid({
							no_columns: 4,
							padding_x: 10,
							padding_y: 10,
							margin_bottom: 50,
							single_column_breakpoint: 700,
						});
					</script>
					<!--//related-posts-->

					<div class="clearfix"></div>
				</div>
			</div>
	</div>
		<div class="col-md-4 side-bar">
			<div class="first_half">
				<div class="categories">
					<div class="user-info-1">
						<img width="200px" src="{{$articleInfo['getUsername']['logo']}}" alt="">
					</div>
					<div class="user-info-2">
						<a href="">{{$articleInfo['getUsername']['username']}}</a>
					</div>
					<div class="user-info-2">
						@if($foucsInfo["bouth"])
							<a href="javascript:void(0)" id="foucsBouth" class="acolor">相互关注</a>
						@endif
						@if($foucsInfo["single"])
							<a href="javascript:void(0)" type="1" class="acolor isFoucs" data="{{$articleInfo['getUsername']['id']}}">取消关注</a>
						@else
							<a href="javascript:void(0)" type="0" class="acolor-h isFoucs" data="{{$articleInfo-['getUsername']['id']}}">关注</a>
						@endif
					</div>
					<div class="user-info-2">
						<a href="/sendMsg/{{$articleInfo['getUsername']['id']}}">发送私信</a>
					</div>
				</div>
				<div class="categories">
					<header>
						<h3 class="side-title-head">分类</h3>
					</header>
					<ul>
						<li class="active"><a href="/blog/world">世界</a></li>
						<li class="active"><a href="/blog/friend">好友</a></li>
						<li class="active"><a href="/blog/own">我的</a></li>
						<li class="active"><a href="/picturewall/old">热图</a></li>
						<div class="clearfix"></div>
					</ul>
				</div>
				<div class="newsletter">
					<h1 class="side-title-head">Newsletter</h1>
					<p class="sign">Sign up to receive our free newsletters!</p>
					<form>
						<input type="text" class="text" value="Email Address" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email Address';}">
						<input type="submit" value="submit">
					</form>
				</div>
				<div class="list_vertical">
					<section class="accordation_menu">
						<div>
							<h4>关于他</h4>
							<input id="label-1" name="lida" type="radio" checked/>
							<label for="label-1" id="item1"><i class="ferme"> </i>他的文章<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
							<div class="content" id="a1">
								<div class="scrollbar" id="style-2">
									<div class="force-overflow">
										@foreach($articleMastInfo["articleHistory"]['data'] as $item)
											<div class="popular-post-grids">
												<div class="popular-post-grid">
													<div class="post-img">
														<a href="/article_detail/{{ $item['id']}}"><img src="{{$item['article_thumb']}}" alt="" /></a>
													</div>
													<div class="post-text">
														<a class="pp-title" href="/article_detail/{{ $item['id']}}"> {{$item['article_disc']}}</a>
														<p>{{$item['created_at']}}
															<a class="span_link" href="javascript:void(0)">
																<span class="glyphicon glyphicon-comment"></span>{{$item['comments']}}
															</a>
															<a class="span_link" href="javascript:void(0)">
																<span class="glyphicon glyphicon-eye-open"></span>{{$item['views']}}
															</a>
															<a class="span_link" href="javascript:void(0)">
																<span class="glyphicon glyphicon-thumbs-up"></span>{{$item['collections']}}
															</a>
														</p>
													</div>
													<div class="clearfix"></div>
												</div>
											</div>
										@endforeach
										@if(count($articleMastInfo["articleHistory"])==0)
												<div class="article" style="text-align: center">
													<h4><a href="/articles">他并没有发布过文章~！</a></h4>
												</div>
										@endif
									</div>
                                </div>
                              </div>
					 	</div>
						<div>
							  <input id="label-2" name="lida" type="radio"/>
							  <label for="label-2" id="item2"><i class="icon-leaf" id="i2"></i>他的粉丝<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
							  <div class="content" id="a2">
								  <div class="scrollbar" id="style-2">
									  <div class="force-overflow">
										  <ul class="categories">
											  @foreach($articleMastInfo["fans"] as $val)
												  <li>
													  <a href="#">
														  <img style="width: 40px;height: 40px;" src="{{$val['logo']}}" alt="{{$val['username']}}">
													  		{{$val['username']}}
													  </a>
												  </li>
											  @endforeach
											  @if(count($articleMastInfo["fans"])==0)
												  <div class="article" style="text-align: center">
													  <h4><a href="/articles">这个丑逼并没有粉丝~！</a></h4>
												  </div>
											  @endif
										  </ul>
									  </div>
								  </div>
							  </div>
						</div>
						<div>
							<input id="label-3" name="lida" type="radio"/>
							<label for="label-3" id="item3"><i class="icon-trophy" id="i3"></i>他关注了<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
							<div class="content" id="a3">
								<div class="scrollbar" id="style-2">
									<div class="force-overflow">
										<ul class="categories">
										@foreach($articleMastInfo["foucs"] as $val)
											<li>
												<a href="#">
													<img style="width: 40px;height: 40px;" src="{{$val['logo']}}" alt="{{$val['username']}}">
													{{$val['username']}}
												</a>
											</li>
										@endforeach
										</ul>
										@if(count($articleMastInfo["foucs"])==0)
											<div class="article" style="text-align: center">
												<h4><a href="/articles">这个丑逼并没有关注任何人~！</a></h4>
											</div>
										@endif
									</div>
							   </div>
							</div>
						</div>
					</section>
				</div>
		  </div>
		 <div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
	</div>
	</div>
	</div>
	<!-- content-section-ends-here -->
	<script>
		$(function() {
			var idRember = new Array();
			$(".createedui").click(function(){
				id = $(this).attr("commentId");
				thisClass = $("#"+id).attr("class");
				thisUser = $(this).attr("this-user");
				if(thisClass == "edui-default"){
					UE.getEditor(id).setShow();
					//$("#answerBtnDiv").show();
				}else{
					docuHeight = (parseInt($(window).height()) - 400) / 2;
					docuWidth = parseInt($(this).offset().left) - 500;
					$("#"+id).parent().css({"top":docuHeight+"px"}).show();
					var ue = UE.getEditor(id);
					idRember.push(id);
				}
				$("#createedui"+id).attr("commentId",id).attr("toUserId",thisUser);
				$("#btndiv"+id).show();
			});

			$(document).bind("click",function(e){
				classNameClicker = $(e.target).attr("class") === undefined ? "" : $(e.target).attr("class");
				if(classNameClicker.indexOf("edui") > -1){
					return;
				}else{
					for(var i in idRember){
						UE.getEditor(idRember[i]).setHide()
						$("#btndiv"+idRember[i]).hide();
					}
				}
			});

			$(".btn-primary").click(function(){
				if(!UE.getEditor($(this).attr("data")).getContent()){
					alert("内容不能为空");return;
				}
				var formData = '<div sytle="display:none"><form action="/auth/authanswer" method="post">';
				formData += '<input type="hidden" name="article_id" value="'+ $(this).attr("articleid") +'">';
				formData += '<input type="hidden" name="comment_id" value="'+ $(this).attr("commentid") +'">';
				formData += '<input type="hidden" name="to_user_id" value="'+ $(this).attr("touserid") +'">';
				formData += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
				formData += '<input type="hidden" name="article_comment" value="'+ UE.getEditor($(this).attr("data")).getContent() +'">';
				formData += '<input type="submit" id="subid">'
				formData += '</form></div>';
				//$(formData).submit(); //火狐浏览器不支持这种方法  改为下面
				$("body").append(formData);
				$("#subid").trigger("click");
				return true;
				//ajax 方法
				var formDataSerialize = $(formData).serialize();
				para = 'calbackFunction';
				$.phpajax('/auth/authanswer','post',formDataSerialize,true,"json",para);
			});


			$(".collecManage").click(function(){
				articleId = $(this).attr("data");
				type = $(this).attr("type");
				articleId = $(this).attr("data");
				formData = '<form>';
				formData += '<input type="hidden" name="article_id" value="'+ articleId +'">';
				formData += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
				formData += '</form>';
				formDataSerialize = $(formData).serialize();
				urlStr = type == 1 ? "/auth/colletioncancel" : "/auth/colletion";
				$.phpajax(urlStr,"post",formDataSerialize,true,"json",function(data){
					data = eval("("+ data +")");
					if(data.status == -1){
						alert(data.message);return;
					}else if(data.status == -2){
						window.location.href = "/auth/login";
					}else{
						str = '<span class="glyphicon glyphicon-thumbs-up"></span>' + data.ext;
						type = $(".collecManage").attr("type");
						type == 0 ? $(".collecManage").attr("type",1).html(str).css({"color":"#F00"}) : $(".collecManage").attr("type",0).removeClass("acolor").html(str).css({"color":"#C5C5C5"});
					}
				});
			});

			$(".isFoucs").click(function(){
				userId = $(this).attr("data");
				type = $(this).attr("type");
				urlStr = type == 1 ? "/auth/foucsusercancle/"+userId : "/auth/foucsuser/"+userId;
				$.phpajax(urlStr,"get","",true,"json",function(data){
                    data = eval("("+ data +")");
                    if(data.status == -1){
                        alert(data.message);return;
                    }else if (data.status == -2){
                        if(confirm("请先登录后进行操作")){
                            window.location.href = "/auth/login";
                        }
                    }else{
                        type = $(".isFoucs").attr("type");
                        ext = data.ext;
                        //<a href="javascript:void(0)" class="acolor">相互关注</a>  user-info-2
                        type == 0 ? $(".isFoucs").before('<a id="foucsBouth" href="javascript:void(0)" class="acolor">相互关注</a> ') : $("#foucsBouth").remove();
                        type == 0 ? $(".isFoucs").attr("type",1).removeClass("acolor-h").addClass("acolor").text("取消关注") : $(".isFoucs").attr("type",0).removeClass("acolor").addClass("acolor-h").text("关注");
                    }
				});
			});

			$(".viewAnswer").click(function(){
				commentId = $(this).attr("data");
                var htmlStr = "";
                var e = $(this);
				$.phpajax("/getansajax/"+commentId,"get","",true,"json",function(data){
				    data = eval("(" + data + ")");
					if(data.status != 0 ){
					    alert(data.message);return;
					}
					items = data.ext.items;
					total = data.ext.total;
					current_page = data.ext.current_page;
					for(var i =0 in items){
                    	htmlStr += '<div class="media response-info">';
						htmlStr += '<div class="media-left response-text-left" id="a-'+ items[i].id +'">';
						htmlStr += '<a href="#" style="width:30px">';
						htmlStr += '<img class="media-object" src="' + items[i].logo + '" alt=""/>';
						htmlStr += '</a>';
						htmlStr += '</div>';
                        htmlStr += '<div class="media-body response-text-right">';
                        htmlStr += '<a href="">' + items[i].from_user_name + '</a>';
						if(items[i].to_user_name)
                            htmlStr += '回复 <a href="">' + items[i].to_user_name + '</a>';
                        htmlStr += ': <p></p>';
                        htmlStr += '<span style="text-indent:20px"><p>' + items[i].article_comment + '</p></span>';
                        htmlStr += '<ul>';
                        htmlStr += '<li>' + items[i].created_at + '</li>';
                        htmlStr += '<li><a class="createedui" articleId="'+ items[i].article_id +'" commentId="'+ items[i].comment_id +'" this-user="'+ items[i].from_user_id +'" href="javascript:void(0);">回复</a></li>';
                        htmlStr += '</ul>';
                        htmlStr += '</div>';
                        htmlStr += '<div class="clearfix"> </div>';
                        htmlStr += '</div>';
					}
                    $(e).parents(".answ").find(".response-info").remove();
                    $(e).parent('div').before(htmlStr);

                    $(".createedui").click(function(){
                        id = $(this).attr("commentId");
                        thisClass = $("#"+id).attr("class");
                        thisUser = $(this).attr("this-user");
                        if(thisClass == "edui-default"){
                            UE.getEditor(id).setShow();
                            //$("#answerBtnDiv").show();
                        }else{
                            docuHeight = (parseInt($(window).height()) - 400) / 2;
                            docuWidth = parseInt($(this).offset().left) - 500;
                            $("#"+id).parent().css({"top":docuHeight+"px","left":docuWidth+"px"}).show();
                            var ue = UE.getEditor(id);
                            idRember.push(id);
                        }
                        $("#createedui"+id).attr("commentId",id).attr("toUserId",thisUser);
                        $("#btndiv"+id).show();
                    });
				})
			})

			@if(old("error"))
			setTimeout(function(){
				$("#errDiv").hide();
				UE.getEditor('editor').setContent($("#editorValueHidden").val(), 0);
			},2000)
			@endif

			@if(isset($arrFind))
			setTimeout(getPosition,200)
            function getPosition(){
                type = {{$arrFind[1]}}
				id = {{$arrFind[0]}}
				str = type == 1 ? "c-"+id : "a-"+id;
                tops = $("#"+str).offset().top;
                $(document).scrollTop(tops - 50);
            }
			@endif
		});

		function foucsCallback(data){
			if(data.status == -1){
				alert(data.message);return;
			}else if (data.status == -2){
				if(confirm("请先登录后进行操作")){
					window.location.href = "/auth/login";
				}
			}else{
				type = $(".isFoucs").attr("type");
				ext = data.ext;
				//<a href="javascript:void(0)" class="acolor">相互关注</a>  user-info-2
				type == 0 ? $(".isFoucs").before('<a id="foucsBouth" href="javascript:void(0)" class="acolor">相互关注</a> ') : $("#foucsBouth").remove();
				type == 0 ? $(".isFoucs").attr("type",1).removeClass("acolor-h").addClass("acolor").text("取消关注") : $(".isFoucs").attr("type",0).removeClass("acolor").addClass("acolor-h").text("关注");
			}
		}

		function collectionCallBack(data){
			if(data.status == -1){
				alert(data.message);return;
			}else if(data.status == -2){
				window.location.href = "/auth/login";
			}else{
				str = '<span class="glyphicon glyphicon-thumbs-up"></span>' + data.ext;
				type = $(".collecManage").attr("type");
				type == 0 ? $(".collecManage").attr("type",1).html(str).css({"color":"#F00"}) : $(".collecManage").attr("type",0).removeClass("acolor").html(str).css({"color":"#C5C5C5"});
			}
		}
	</script>
@endsection