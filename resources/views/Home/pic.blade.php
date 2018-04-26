@extends("Home.foot")

@extends("Home.header")

@section('content')
    <div class="copyrights">Collect from <a href="http://www.cssmoban.com/" >企业网站模板</a></div>
	<!-- content-section-starts-here -->
	<div class="main-body">
		<div class="wrap" style="width: 100%">
			<div class="col-md-12 content-left">
				<div class="articles">
					@foreach($pic as $arItem)
						<div class="article">
							<div class="article-left" style="width: 80%;float: none;margin: 0px auto">
								<img src="{{$arItem->picname}}">
							</div>
							<div class="clearfix"></div>
						</div>
					@endforeach
					{!! $pic->render() !!}
				</div>
				</div>
			<div class="clearfix"></div>
		</div>
	</div>
	<!-- content-section-ends-here -->
	<!-- footer-section-starts-here -->
@endsection