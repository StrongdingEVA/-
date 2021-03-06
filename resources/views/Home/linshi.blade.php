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
</style>
<link rel="stylesheet" href="/Home/css/lightbox.min.css">
<script src="/Home/js/lightbox-plus-jquery.min.js"></script>
<!--[if IE]>
<script src="http://libs.useso.com/js/html5shiv/3.7/html5shiv.min.js"></script>
<![endif]-->
    <section id="gallery-wrapper">
        <video src="/movie/37CAB8258F4F6AA0AFF1B1D6754D6EA1.mp4" controls="controls">
        您的浏览器不支持 video 标签。
        </video>
    </section>

    
{{--<script src="http://libs.baidu.com/jquery/2.1.1/jquery.min.js" type="text/javascript"></script>--}}
<script src="/Home/js/pinterest_grid.js"></script>
<script type="text/javascript" charset="utf-8" src="/Home/js/myjs.js"></script>
<script type="text/javascript">

</script>
@endsection