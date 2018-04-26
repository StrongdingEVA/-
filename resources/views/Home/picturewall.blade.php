@extends("Home.header")

@section("content")
<link rel="stylesheet" href="/Home/css/normalize.css">
<style type="text/css">
    #gallery-wrapper {position: relative;max-width: 75%;width: 75%;margin:50px auto;}
    img.thumb {width: 100%;max-width: 100%;height: auto;}
    .white-panel {position: absolute;background: white;border-radius: 5px;box-shadow: 0px 1px 2px rgba(0,0,0,0.3);padding: 10px;}
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
<link rel="stylesheet" href="/Home/css/lightbox.min.css">
<script src="/Home/js/lightbox-plus-jquery.min.js"></script>
<!--['if IE']>
<script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
<!['endif']-->
    <div class="">
        <div class="fixed-btn">
            <a class="go-top" href="/picturewall/new">最新</a>
            <a class="qrcode" href="/picturewall/hot">最热</a>
            <a class="qrcode" href="/picturewall/old">最早</a>
        </div>
    </div>
    <section id="gallery-wrapper">
        @foreach($hotPicItem as $item)
            <article class="white-panel" data="{{$item['id']}}">
                <a class="example-image-link" href="@if($item['path']){{$item['path']}}@else{{$item['thumb']}}@endif" data-lightbox="example-set" data-title="{{$item['disc']}}">
                    <img style="width: 220px" class="example-image" src="@if($item['thumb']){{$item['thumb']}}@else{{$item['path']}}@endif" alt=""/>
                </a>
                <p>
                    <img src="{{$item['get_user']['logo']}}" width="40px" alt="">
                    <span>{{$item['get_user']['username']}}</span>
                    <a class="span_link" style="position: absolute;right: 2px;line-height: 40px" href="javascript:void(0)">
                        <span class="glyphicon-thumbs-up glyphicon @if($item['liked']) is-liked" type="1" @else type="0" @endif data="{{$item['id']}}">{{$item['like']}}</span>
                    </a>
                </p>
                <p>{{$item['disc']}}</p>
                <p>拍摄地点：@if($item['isshowparm']) {{$item['addr']}} @else 未开启定位 @endif</p>
                <p>发布时间：{{$item['created_at']}}</p>
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
        $(window).selectbox({
            "bottom_height":10,
            "page":1,
            "pageCount":{{$pageCount}},
            "type":"{{$type}}",
            "callback":function(e){
                $.phpajax("/picturewall_ajax/"+ e.page +"/{{$type}}","get","",true,"json",function(data){
                    var data = eval("("+ data +")");
                    if(data.status == 1){
                        var ext = data.ext;
                        e.status = 0;
                        var str = "";
                        $.map(ext,function(i){
                            str += '<article class="white-panel" data="'+ i.id +'">';
                            str += '<a class="example-image-link" href="';
                            if(i.path){
                                str += i.path;
                            }else{
                                str += i.thumb
                            }
                            str += '" data-lightbox="example-set" data-title="' + i.disc + '">';
                            str += '<img style="width: 220px" class="example-image" src="';
                            if(i.thumb){
                                str += i.thumb
                            }else{
                                str += i.path
                            }
                            str += '" alt=""/>';
                            str += '</a>';
                            str += '<p>';
                            str += '<img src="'+i.get_user.logo+'" width="40px" alt="">';
                            str += '<span>'+i.get_user.username+'</span>';
                            str += '<a class="span_link" style="position: absolute;right: 2px;line-height: 40px" href="javascript:void(0)">';
                            str += '<span class="glyphicon-thumbs-up glyphicon';
                            if(i.liked)
                                str += ' is-liked" type="1" ';
                            else
                                str += '" type="0" ';
                            str += 'data="'+i.id+'">'+i.like+'</span>';
                            str += '</a>';
                            str += '</p>';
                            str += '<p>'+i.disc+'</p>';
                            str += '<p>拍摄地点：';
                            if(i.isshowparm) {
                                str += i.addr;
                            }else {
                                str += '未开启定位';
                            }
                            str += '</p>';
                            str += '<p>发布时间：'+i.created_at+'</p>';
                            str += '</article>';
                        });
                        $("#gallery-wrapper").append(str);

                        $(".glyphicon").bind("click",function(){
                            picId = $(this).attr("data");
                            type = $(this).attr("type");
                            el = $(this);
                            urlStr = "/dolike/"+picId + "/" + type;
                            $.phpajax(urlStr,"get","",true,"json",function(data){
                                data = eval("("+ data +")");
                                if(data.status == -1){
                                    alert(data.message);return;
                                }else if(data.status == -2){
                                    window.location.href = "/auth/login";
                                }else{
                                    if(type == 1){
                                        $(el).attr("type",0);
                                        $(el).removeClass("is-liked")
                                    }else{
                                        $(el).attr("type",1);
                                        $(el).addClass("is-liked")
                                    }
                                    $(el).text(data.message);
                                }
                            });
                        })
                    }else{
                        alert(data.message);
                    }
                })
            }
        });

        $("#gallery-wrapper").pinterest_grid({
            no_columns: 4,
            padding_x: 10,
            padding_y: 10,
            margin_bottom: 50,
            single_column_breakpoint: 700
        });

        $(".glyphicon").click(function(){
            picId = $(this).attr("data");
            type = $(this).attr("type");
            el = $(this);
            urlStr = "/dolike/"+picId + "/" + type;
            $.phpajax(urlStr,"get","",true,"json",function(data){
                data = eval("("+ data +")");
                if(data.status == -1){
                    alert(data.message);return;
                }else if(data.status == -2){
                    window.location.href = "/auth/login";
                }else{
                    if(type == 1){
                        $(el).attr("type",0);
                        $(el).removeClass("is-liked")
                    }else{
                        $(el).attr("type",1);
                        $(el).addClass("is-liked")
                    }
                    $(el).text(data.message);
                }
            });
        })
    });
</script>
@endsection