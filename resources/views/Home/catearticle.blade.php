@extends("Home.foot")

@extends("Home.header")

@section("content")
	<!-- content-section-starts-here -->
	<div class="main-body">
		<div class="wrap" style="width: 55%;margin: 0px auto">
		<ol class="breadcrumb">
			  <li><a href="/">首页</a></li>
			  <li class="active">{{$cateItem->category_name}}</li>
			</ol>
			<div class="col-md-12 content-left">
				<div class="articles sports">
						<header>
							<h3 class="title-head">{{$cateItem->category_name}}</h3>
						</header>
					@foreach($articleList as $arItem)
						<div class="article">
							<div class="article-left">
								<a href="/article_detail/{{$arItem->id}}"><img src="{{$arItem->article_thumb}}"></a>
							</div>
							<div class="article-right">
								<div class="article-title">
									<p>{{$arItem->created_at}}
										<a class="span_link" href="javascript:void(0)">
											<span class="glyphicon glyphicon-comment"></span>{{$arItem->comments}}
										</a>
										<a class="span_link" href="javascript:void(0)">
											<span class="glyphicon glyphicon-eye-open"></span>{{$arItem->views}}
										</a>
										<a class="span_link" href="#">
											<span class="glyphicon glyphicon-thumbs-up"></span>{{$arItem->collections}}
										</a>
									</p>
									<a class="title" href="/article_detail/{{$arItem->id}}">{{$arItem->article_title}}</a>
								</div>
								<div class="article-text">
									<p>{{$arItem->article_disc}}</p>
									<a href="/article_detail/{{$arItem->id}}"><img src="/Home/images/more.png" alt="" /></a>
									<div class="clearfix"></div>
								</div>
							</div>

							{{--<div class="article-left">--}}
								{{--<a href="single.html"><img src="images/sport1.jpg"></a>--}}
							{{--</div>--}}
							{{--<div class="article-right">--}}
								{{--<div class="article-title">--}}
									{{--<p>On Feb 25, 2015 <a class="span_link" href="#"><span class="glyphicon glyphicon-comment"></span>0 </a><a class="span_link" href="#"><span class="glyphicon glyphicon-eye-open"></span>104 </a><a class="span_link" href="#"><span class="glyphicon glyphicon-thumbs-up"></span>52</a></p>--}}
									{{--<a class="title" href="single.html"> The section of the mass media industry that focuses on presenting</a>--}}
								{{--</div>--}}
								{{--<div class="article-text">--}}
									{{--<p>The standard chunk of Lorem Ipsum used since the 1500s. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" exact original.....</p>--}}
									{{--<a href="single.html"><img src="images/more.png" alt="" /></a>--}}
									{{--<div class="clearfix"></div>--}}
								{{--</div>--}}
							{{--</div>--}}
							<div class="clearfix"></div>
						</div>
					@endforeach
					<div class="page-class">
						{!! $articleList->render() !!}
					</div>
					@if(count($articleList) == 0)
						<div class="article">
							<h3><a href="/articles">暂时没有该类文章，快去发表文章吧~！</a></h3>
						</div>
					@endif

					</div>
				</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- content-section-ends-here -->
@endsection