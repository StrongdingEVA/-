@foreach($articleList as $val)
    <article class="white-panel locate" url-data="/article_detail/{{$val['id']}}">
        <a class="example-image-link" href="javascript:void(0)" data-lightbox="example-set" data-title="{{$val['article_disc']}}">
            <img style="width: 100%" class="example-image" src="@if($val['article_thumb']) {{$val['article_thumb']}} @else /Home/images/logo.jpg @endif" alt=""/>
        </a>
        <p>
            <img src="{{$val['get_username']['logo']}}" style="height:25px" alt="">
            <span>{{$val['get_username']['username']}}</span>
        </p>
        <p>{{$val['article_title']}}</p>
        <p>
            <a class="span_link article-icon" href="javascript:void(0)">
                <span class="glyphicon glyphicon-comment"></span>{{$val['comments']}}
            </a>
            <a class="span_link article-icon" href="javascript:void(0)">
                <span class="glyphicon glyphicon-eye-open"></span>{{$val['views']}}
            </a>
            <a class="span_link article-icon" type="0" href="javascript:void(0)">
                <span class="glyphicon glyphicon-thumbs-up"></span>{{$val['collections']}}
            </a>
        </p>
        <p class="article-time">{{$val['created_at']}}</p>
    </article>
@endforeach