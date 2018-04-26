@extends("Home.header")

@section("content")
	<link rel="stylesheet" href="/Home/css/normalize.css">
	<style type="text/css">
		#gallery-wrapper {position: relative;max-width: 75%;width: 75%;margin:50px auto;}
		img.thumb {width: 100%;max-width: 100%;height: auto;}
		.white-panel {position: absolute;background: white;border-radius: 5px;box-shadow: 0px 1px 2px rgba(0,0,0,0.3);padding: 10px;}
		.white-panel p{width:100%;padding: 5px}
		.white-panel h1 {font-size: 1em;}
		.white-panel h1 a {color: #A92733;}.white-panel:hover {box-shadow: 1px 1px 10px rgba(0,0,0,0.5);margin-top: -5px;-webkit-transition: all 0.3s ease-in-out;-moz-transition: all 0.3s ease-in-out;-o-transition: all 0.3s ease-in-out;transition: all 0.3s ease-in-out;}
		.white-panel p{color: #282828;}
		.is-liked{color:#ff1111}
		.fixed-btn {
			position: fixed;
			right: 1%;
			bottom: 5%;
			width: 40px;
			border: 1px solid #eee;
			background-color: white;
			font-size: 24px;
			z-index: 1040;
			-webkit-backface-visibility: hidden;
		}
		.fixed-btn a{
			font-size: 14px;
		}
	</style>

	<div class="">
		<div class="fixed-btn">
			<a class="go-top" href="/picturewall/new">最新</a>
			<a class="qrcode" href="/picturewall/hot">最热</a>
			<a class="qrcode" href="/picturewall/old">最早</a>
		</div>
	</div>
	<section id="gallery-wrapper">
		@foreach($articleList as $val)
			<article class="white-panel">
				<a class="example-image-link" href="/article_detail/{{$val['id']}}" data-lightbox="example-set" data-title="{{$val['article_disc']}}">
					<img style="width: 100%" class="example-image" src="@if($val['article_thumb']) {{$val['article_thumb']}} @else /Home/images/logo.jpg @endif" alt=""/>
				</a>
				<p>
                    <img src="{{$val['get_username']['logo']}}" style="height:25px" alt="">
                    <span>{{$val['get_username']['username']}}</span>
                </p>
				<p>{{$val['article_title']}}</p>
				<p>时间：{{$val['created_at']}}</p>
			</article>
		@endforeach
	</section>

	<section style="width: 73px;position: fixed;right: 45px;top:220px">
		<article class="add-article">
			<a href="/createpicwall">
				<img src="/Home/images/plus23.png" class="thumb">
				<p style="text-align: center">
					<span>发表热图</span>
				</p>
			</a>
		</article>
	</section>

	<script src="/Home/js/pinterest_grid.js"></script>
	<script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
	<script type="text/javascript" src="/Home/js/scroll.js"></script>
	<script type="text/javascript">
        $(function(){
            $("#gallery-wrapper").pinterest_grid({
                no_columns: 6,
                padding_x: 10,
                padding_y: 10,
                margin_bottom: 50,
            });
        });
	</script>
@endsection