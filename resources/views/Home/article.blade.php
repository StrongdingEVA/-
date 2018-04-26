@extends("Home.foot")

@extends("Home.header")

@section('content')
    <style>
        .mar-top{ margin-top: 20px;}
        .span-color{color: #f00;}
    </style>
    <link rel="stylesheet" type="text/css" href="/Home/css/uploadifive.css">
    <script src="/Home/js/jquery.uploadifive.min.js" type="text/javascript"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.config.js"></script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/ueditor.all.min.js"> </script>
    <script type="text/javascript" charset="utf-8" src="/ueditor/lang/zh-cn/zh-cn.js"></script>
    <div class="main-body">
        <div class="wrap">
            <form action="/createarticle" method="post" enctype="multipart/form-data">
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
                <h2 class="heading text-center">添加文章</h2>

                <div class="form-group mar-top">
                    <input class="form-control input-lg onblur" data="title" type="text" name="article_title" @if ( old('article_title') ) value="{{ old('article_title') }}" @endif placeholder="文章标题" required>
                </div>


                <div class="form-group mar-top">
                    <select class="form-control" name="category">
                        @foreach($cateInfo as $val)
                            <option value="{{$val->id}}" @if($val->id == old("category")) selected @endif value="">{{$val->category_name}}</option>
                        @endforeach
                    </select>
                </div>


                <div class="input-group mar-top" id="select-file-div">
                    <div id="queue"></div>
                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                    <input type="hidden" name="article_thumb" @if ( old('article_thumb') ) value="{{ old('article_thumb') }}" @endif id="article_thumb">
                    <input type="hidden" name="article_video" @if ( old('article_video') ) value="{{ old('article_video') }}" @endif id="article_video">
                    @if ( old('article_thumb') ) <img src="{{ old('article_thumb') }}" alt=""> @endif
                </div>

                <script>
                    $(function() {
                        <?php $timestamp = time();?>
                        $('#file_upload').uploadifive({
                            'auto'             : true,
                            'formData'         : {
                                'timestamp' : '<?php echo $timestamp;?>',
                                'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
                            },
                            'queueID'          : 'queue',
                            'uploadScript'     : '/uploadimg',
                            'onUploadComplete' : function(file, data) {
                                data = JSON.parse(data);
                                if(data.status == -1){
                                    alert(data.message);return;
                                }

                                var type = $('[name="category"]').val();
                                if(type == 4){ //录像
                                    $("#select-file-div").append("<p>视频上传成功...</p>");
                                    $("#article_video").val(data.src);
                                }else{
                                    articleThumb = document.getElementById("#articleThumb");
                                    if(articleThumb === null){
                                        str = '<img id="thumb" src="'+ data.src +'" width="100%" alt="图片加载失败"/>';
                                        $("#select-file-div").append(str);
                                    }
                                    $("#article_thumb").val(data.src);
                                }
                            },
                        });
                    });
                </script>

                <div class="grid_3 grid_5">
                    <textarea name="article_disc" id="article_disc" placeholder="文章简介" class="form-control onblur" cols="30" rows="4" required>@if(old("article_disc")) {{old("article_disc")}}} @endif</textarea>
                </div>
                
                <div class="grid_3 grid_5" id="editor"></div>
                <script>
                    $(function(){
                        //实例化编辑器
                        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                        var ue = UE.getEditor('editor');
                    })
                </script>

                <div class="form-group">
                    <div class="col-sm-10" style="padding-left: 0px">
                        <div class="checkbox">
                            <label>
                                <input name="is_show" type="checkbox" @if ( old('is_show') == "on" ) checked @endif checked>让大家也看到
                            </label>
                        </div>
                    </div>
                </div>

                <div class="input-group mar-top" style="width: 100%;overflow: auto" id="select-file-div">
                    <input type="submit" style="position: absolute;right: 20px" class="btn btn-primary btn-sm" value="发布">
                </div>
            </div>
            <!--short-codes-evds-->
            </form>
        </div>
    </div>
@endsection