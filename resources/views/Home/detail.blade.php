@extends("Home.foot")

@extends("Home.header")

@section('content')
<link rel="stylesheet" href="/Home/css/detail.css">
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
<script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
<script type="text/javascript" charset="utf-8" src="/Home/js/page.js"></script>
<script src="/Home/js/pinterest_grid.js"></script>
	<div class="main-body">
		@if(old("error"))
		<div id="errDiv" style="position: fixed;width: 300px;left: 45%;z-index:1002;">
			<div class="layer.msg layer.msg-danger" style="text-align: center;font-size: 16px;">
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
							<div class="article-thumb">
								@foreach($articleInfo['article_source_pic'] as $item)
									<img src="{{$item}}" width="100%" alt="图片加载失败！">
								@endforeach
							</div>
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

							@if($articleInfo['collector'])
							<ul class="categories">
								<h6>他们已经收藏了哦~</h6>
								@foreach($articleInfo['collector'] as $val)
									<li><a href="#"><img style="width: 40px;height: 40px;" src="{{$val['logo']}}" alt="{{$val['username']}}"></a></li>
								@endforeach
							</ul>
							@endif
							<div class="clearfix"></div>

						<div class="response">
							<input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
							@foreach($articleComment as $val)
								<div class="response-item" id="c-{{$val['id']}}">
									<div class="response-item-head">
										<a href="javascript:void(0)">
											<img class="media-object" src="{{$val["logo"]}}" alt="">
										</a>
									</div>
									<div class="response-item-info">
										<div class="info-username">
											<a href="">{{ $val["username"] }}</a>
										</div>
										<div class="info-username">
											<span>{!! $val["article_comment"] !!}</span>
										</div>
										<div class="info-username last">
											<span class="time">{{$val["created_at"]}}</span>
											<span class="comment createedui" articleId="{{$val["article_id"]}}" commentId="{{$val["id"]}}" this-user="{{$val["user_id"]}}" this-username="{{$val["username"]}}" that-username="{{$val['username']}}"><i class="reply"></i>回复</span>
										</div>
									</div>
								</div>

								@if($val["answer"])
									@foreach($val["answer"] as $va)
									<div class="response-item child" id="a-{{$va['id']}}">
										<div class="response-item-head">
											<a href="javascript:void(0)">
												<img class="media-object" src="{{$va['get_from_user_info']['logo']}}" alt="">
											</a>
										</div>
										<div class="response-item-info">
											<div class="info-username">
												<a href="javascript:void(0)">{{$va["get_from_user_info"]["username"]}}</a>
												@if($va["get_to_user_info"]["username"])
													 回复
													<a href="javascript:void(0)">{{$va["get_to_user_info"]["username"]}}</a>
												@endif
											</div>
											<div class="info-username">
												<span>{!! $va["article_comment"] !!}</span>
											</div>
											<div class="info-username last">
												<span class="time">{!! $va["created_at"] ? $va["created_at"] : "1970-01-01 08:00" !!}</span>
												<span class="comment createedui" articleId="{{$val["article_id"]}}" commentId="{{$val["id"]}}" this-user="{{$va["get_from_user_info"]["id"]}}" this-username="{{$va["get_from_user_info"]["username"]}}" that-username="{{$va["get_to_user_info"]["username"]}}"><i class="reply"></i>回复</span>
											</div>
										</div>
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
							@endforeach
						</div>
						<div class="page-class">
							@if($paginator)
								{!! $paginator->render() !!}
							@endif
						</div>

						<div class="coment-form">
							<form action="/auth/authcomment" method="post">
								<input type="hidden" name="articleid" value="{{ $articleInfo['id'] }}">
								<input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
								<div class="grid_3 grid_5" id="editor"></div>
								<input type="submit" value="发布评论" >
							</form>
						</div>

						<div class="row related-posts" id="gallery-wrapper">
							@foreach($articleMastInfo["want"] as $val)
								<article class="white-panel">
									<a href="/article_detail/{{$val['id']}}">
										<img src="@if($val['article_thumb']) {{$val['article_thumb']}} @else /Home/images/logo.jpg @endif" class="thumb" title="{{$val['article_title']}}">
									</a>
									<p>{{$val['article_title']}}</p>
								</article>
							@endforeach
						</div>
						<div class="clearfix"></div>
					</div>
					</div>
				</div>
				<div class="col-md-4 side-bar">
						<div class="first_half">
							<div class="categories">
								<div class="user-info-1">
									<img width="200px" src="{{$articleInfo['get_username']['logo']}}" alt="">
								</div>
								<div class="user-info-2">
									<a href="">{{$articleInfo['get_username']['username']}}</a>
								</div>
								<div class="user-info-2">
									@if($userInfo && $articleInfo['user_id'] != $userInfo['id'])
										@if($foucsInfo["bouth"])
											<a href="javascript:void(0)" id="foucsBouth" class="acolor">相互关注</a>
										@endif
										@if($foucsInfo["single"])
											<a href="javascript:void(0)" type="1" class="acolor isFoucs" data="{{$articleInfo['get_username']['id']}}">取消关注</a>
										@else
											<a href="javascript:void(0)" type="0" class="isFoucs" data="{{$articleInfo['get_username']['id']}}">关注</a>
										@endif
									@endif
								</div>
								{{--@if($userInfo && $articleInfo['user_id'] != $userInfo['id'])--}}
									{{--<div class="user-info-2">--}}
										{{--<a href="/sendMsg/{{$articleInfo['get_username']['id']}}">发送私信</a>--}}
									{{--</div>--}}
								{{--@endif--}}
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
							<div class="list_vertical">
								<section class="accordation_menu">
									<div>
										<?php $str = $articleInfo['user_id'] == @$userInfo['id'] ? '我':'他';?>
										<h4>关于{{$str}}</h4>
										<input id="label-1" name="lida" type="radio" checked/>
										<label for="label-1" id="item1"><i class="ferme"> </i>{{$str}}的文章<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
										<div class="content" id="a1">
											<div class="scrollbar" id="style-2">
												<div class="force-overflow">
													@foreach($articleMastInfo["articleHistory"] as $item)
														<div class="popular-post-grids">
															<div class="popular-post-grid" url-data="/article_detail/{{ $item['id']}}">
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
																<h4><a href="/articles">{{$str}}并没有发布过文章~！</a></h4>
															</div>
													@endif
												</div>
											</div>
										  </div>
									</div>
									<div>
										  <input id="label-2" name="lida" type="radio"/>
										  <label for="label-2" id="item2"><i class="icon-leaf" id="i2"></i>{{$str}}的粉丝<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
										  <div class="content" id="a2">
											  <div class="scrollbar" id="style-2">
												  <div class="force-overflow">
													  @if($articleMastInfo["fans"])
													  <ul class="categories">
														  @foreach($articleMastInfo["fans"] as $val)
															  <li>
																  <a href="#">
																	  <img style="width: 40px;height: 40px;" src="{{$val['logo']}}" alt="{{$val['username']}}">
																		{{$val['username']}}
																  </a>
															  </li>
														  @endforeach
													  </ul>
														  @else
														  <div class="article" style="text-align: center">
															  <h4><a href="/articles">并没有粉丝~！</a></h4>
														  </div>
													  @endif
												  </div>
											  </div>
										  </div>
									</div>
									<div>
										<input id="label-3" name="lida" type="radio"/>
										<label for="label-3" id="item3"><i class="icon-trophy" id="i3"></i>{{$str}}关注了<i class="icon-plus-sign i-right1"></i><i class="icon-minus-sign i-right2"></i></label>
										<div class="content" id="a3">
											<div class="scrollbar" id="style-2">
												<div class="force-overflow">
													@if($articleMastInfo["foucs"])
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
														@else
														<div class="article" style="text-align: center">
															<h4><a href="/articles">丑逼并没有关注任何人~！</a></h4>
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

	<div id="ansForm" style="display: none;position: relative;">
		<div id="ansFormUe" class="ansform" style="width: 100%;height:90px"></div>
		<div class="input-group mar-top ansBtnDiv">
			<input type="button" class="btn btn-primary btn-sm edui" value="回复">
		</div>
	</div>

	<script>
		$(function() {
            $("#gallery-wrapper").pinterest_grid({
                no_columns: 6,
                padding_x: 10,
                padding_y: 10,
                margin_bottom: 50,
                single_column_breakpoint: 700,
            });
            var ue = UE.getEditor('editor',{wordCount:false,elementPathEnabled:false});
            var ueAns = UE.getEditor('ansFormUe',{initialFrameHeight:90,toolbarTopOffset:90,wordCount:false,elementPathEnabled:false,toolbars: [[
                'bold', 'italic', 'underline', 'fontborder', 'emotion',
            ]]});
			$(document).on('click','.createedui',function(){
			    var that = this;
			    $.checkLogin('/',function(res){
                    if(parseInt(res.status) == 0){
                        ueAns.articleid = $(that).attr('articleid');
                        ueAns.commentid = $(that).attr('commentid');
                        ueAns.touserid = $(that).attr('this-user');
                        ueAns.tousername = $(that).attr('this-username');
                        ueAns.thatusername = $(that).attr('that-username');
                        $(that).parents('.response-item').after($('#ansForm').show());
                    }else{
                        layer.msg(res.message);
                        setTimeout(function(){
                            window.location.href = '/auth/login';
                        },1000)
                    }
				});
			});

			$(".btn-primary").click(function(){
			    var that = this;
			    var content = UE.getEditor('ansFormUe').getContent().trim();
				if(!content){
					layer.msg("内容不能为空");return;
				}
				var formData = '<div sytle="display:none"><form id="tempForm" action="answer" method="post">';
				formData += '<input type="hidden" name="article_id" value="'+ ueAns.articleid +'">';
				formData += '<input type="hidden" name="comment_id" value="'+ ueAns.commentid +'">';
				formData += '<input type="hidden" name="to_user_id" value="'+ ueAns.touserid +'">';
				formData += '</form></div>';
				//$(formData).submit(); //火狐浏览器不支持这种方法  改为下面
				$("body").append(formData);
				//ajax 方法
				var formDataSerialize = $("#tempForm").serialize();
				formDataSerialize += '&article_comment=' + content
				para = 'calbackFunction';
				$.phpajax('/answer','post',formDataSerialize,true,"json",function(res){
				    var res = JSON.parse(res);
				    if(parseInt(res.status) == 0){
						var str = '';
                        	str += '<div class="response-item child">';
							str += '<div class="response-item-head">';
							str += '<a href="javascript:void(0)">';
							str += '<img class="media-object" src="{{$userInfo ? $userInfo['logo'] : ''}}" alt="">';
							str += '</a>';
							str += '</div>';
							str += '<div class="response-item-info">';
							str += '<div class="info-username">';
							str += '<a href="javascript:void(0)">{{$userInfo ? $userInfo["username"] : ''}}</a>';
							var toname = ueAns.tousername != '{{$userInfo ? $userInfo['username'] : ''}}' ? ueAns.tousername : ueAns.tousername != ueAns.thatusername ? ueAns.thatusername : '';
							str += ' 回复<a href="javascript:void(0)">'+ toname +'</a>';
							str += '</div>';
							str += '<div class="info-username">';
							str += '<span>'+ content +'</span>';
							str += '</div>';
							str += '<div class="info-username last">';
							str += '<span class="time">{{date('Y-m-d H:i:s',time())}}</span>';
							str += '<span class="comment createedui" articleId="'+ ueAns.articleid +'" commentId="'+ ueAns.commentid +'" this-user="{{$userInfo ? $userInfo['id'] : ''}}" this-username="{{$userInfo ? $userInfo['username'] : ''}}"><i class="reply"></i>回复</span>';
							str += '</div></div></div>';
						$(that).parents('#ansForm').before(str);
						$('#ansForm').hide();
					}else{
				        layer.msg(res.message);
					}
				});
			});


			$(".collecManage").click(function(){
			    var that = this;
                $.checkLogin('/',function(res){
                    if(parseInt(res.status) == 0){
                        articleId = $(that).attr("data");
                        type = $(that).attr("type");
                        formData = '<form>';
                        formData += '<input type="hidden" name="article_id" value="'+ articleId +'">';
                        formData += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                        formData += '</form>';
                        formDataSerialize = $(formData).serialize();
                        urlStr = type == 1 ? "/auth/colletioncancel" : "/auth/colletion";
                        $.phpajax(urlStr,"post",formDataSerialize,true,"json",function(data){
                            data = eval("("+ data +")");
                            if(data.status == -1){
                                layer.msg(data.message);return;
                            }else if(data.status == -2){
                                layer.msg(data.message);
                            }else{
                                str = '<span class="glyphicon glyphicon-thumbs-up"></span>' + data.ext;
                                type = $(".collecManage").attr("type");
                                type == 0 ? $(".collecManage").attr("type",1).html(str).css({"color":"#F00"}) : $(".collecManage").attr("type",0).removeClass("acolor").html(str).css({"color":"#C5C5C5"});
                            }
                        });
                    }else{
                        layer.msg(res.message);
                        setTimeout(function(){
                            window.location.href = '/auth/login';
                        },1000)
                    }
                });
			});

			$(".isFoucs").click(function(){
				var userId = $(this).attr("data");
				var type = $(this).attr("type");
				var urlStr = type == 1 ? "/nofoucs/"+userId : "/foucs/"+userId;

                $.checkLogin('/',function(res){
                    if(parseInt(res.status) == 0){
                        $.phpajax(urlStr,"get","",true,"json",function(data){
                            var ext = data.ext;
                            if(type == 0){//关注
                                ext && $(".isFoucs").before('<a id="foucsBouth" href="javascript:void(0)" class="acolor">相互关注</a> ')
                                $(".isFoucs").attr("type",1).removeClass("acolor-h").addClass("acolor").text("取消关注");
							}else{//取消关注
                                $("#foucsBouth").remove();
                                $(".isFoucs").attr("type",0).removeClass("acolor").addClass("acolor-h").text("关注");
							}
                        });
                    }else{
                        layer.msg(res.message);
                        setTimeout(function(){
                            window.location.href = '/auth/login';
                        },1000)
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
					    layer.msg(data.message);return;
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

			blog.bindDump('.popular-post-grid')
		});

		function foucsCallback(data){
			if(data.status == -1){
				layer.msg(data.message);return;
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
				layer.msg(data.message);return;
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