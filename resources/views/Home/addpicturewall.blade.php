@extends("Home.foot")

@extends("Home.header")

@section('content')
    <style>
        .mar-top{ margin-top: 20px;}
        .span-color{color: #f00;}
        .pic-par{background: url(/Home/images/shadow.png) repeat-x;display: block;color:#fff;width: 100%;margin: 5px 0px 5px}
        .pic-par span{font-size: 12px}
        .pic-info{min-height:20px;position: absolute;bottom: 0px;width: 100%;}
    </style>
    <script src="/Home/js/jquery.uploadifive.min.js" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="/Home/css/uploadifive.css">
    <div class="main-body">
        <div class="wrap">
            <form action="/createpicwall" method="post" enctype="multipart/form-data">
                <!--short-codes-starts-->
                <div class="short-codes" style="width: 50%;margin: 0px auto">
                    @if (count($errors) > 0)
                        <h2 class="tm-section-title">错误信息：</h2>
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p style="color: #ff0000">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    <input type="hidden" type="text" name="_token" value="{{ csrf_token() }}">
                    <h2 class="heading text-center">发布我的照片</h2>

                    <div class="form-group mar-top" id="select-file-div" style="position: relative">
                        <div id="queue"></div>
                        <input id="path" type="file" name="path" multiple="false">
                        <input type="hidden" id="path-pic" name="thumb" @if ( old('path') ) value="{{ old('path') }}" @endif required>
                        @if ( old('path') ) <img id="thumb" width="100%" src="{{ old('path') }}" alt=""> @endif
                        <div class="pic-info">

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-10" style="padding-left: 0px">
                            <div class="checkbox">
                                <label>
                                    <input name="isshowparm" type="checkbox" @if ( old('isshowparm') == "on" ) checked @endif>是否显示照片参数
                                </label>
                            </div>
                        </div>
                    </div>

                    <script>
                        $(function() {
                            <?php $timestamp = time();?>
                            $('#path').uploadifive({
                                'auto'             : true,
                                'formData'         : {
                                    'timestamp' : '<?php echo $timestamp;?>',
                                    'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
                                },
                                'queueID'          : 'queue',
                                'uploadScript'     : '/uploadimgwall',
                                'onUploadComplete' : function(file, data) {
                                    data = JSON.parse(data);console.log(data);
                                    if(data.status == -1){
                                        alert(data.message);return;
                                    }

                                    $("#thumb").remove();
                                    ext = data.ext;
                                    str = '<img id="thumb" width="100%" src="'+ ext.src +'" alt="图片加载失败"/>';
                                    $("#select-file-div").append(str);

                                    htmlStr = "";
                                    htmlStr += '<div class="pic-par">&nbsp;';
                                    htmlStr += '<span>光圈：'+ ext.fNumber +'</span>&nbsp;';
                                    htmlStr += '<span>器材：'+ ext.model +'</span>&nbsp;';
                                    htmlStr += '<span>器材品牌：'+ ext.make +'</span>&nbsp;';
                                    htmlStr += '<span>焦距：'+ ext.focalLength +'</span>&nbsp;';
                                    htmlStr += '<span>详细地址：'+ ext.addr +'</span>&nbsp;';
                                    htmlStr += '</div>';
                                    $(".pic-info").empty().append(htmlStr);
                                    $("#path-pic").val(data.ext.src);
                                }
                            });
                        });
                    </script>

                    <div class="grid_3 grid_5">
                        <textarea name="disc" id="disc" placeholder="为你的图片陪上点文字吧~" class="form-control onblur" cols="30" rows="4" required>@if(old("disc")) {{old("disc")}}} @endif</textarea>
                    </div>

                    <div class="input-group mar-top" style="width: 100%;overflow: auto" id="select-file-div">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">发布</button>
                    </div>
                </div>
                <!--short-codes-evds-->
            </form>
        </div>
    </div>
@endsection