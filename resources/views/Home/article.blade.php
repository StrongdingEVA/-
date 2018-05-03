@extends("Home.foot")

@extends("Home.header")

@section('content')
    <style>
        .mar-top{ margin-top: 20px;}
        .span-color{color: #f00;}
        .input-group .thumb{width: 25%;float: left;margin: 10px}
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
                    <div class="layer.msg layer.msg-danger">
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

                <div class="input-group mar-top" style="width:100%" id="select-file-div">
                    <div id="queue"></div>
                    <input id="file_upload" name="file_upload" type="file" multiple="true">
                    <input type="hidden" name="article_thumb" id="article_thumb" @if ( old('article_thumb') ) value="{{ old('article_thumb') }}" @endif id="article_thumb">
                    @if ( old('article_thumb') ) <img src="{{ old('article_thumb') }}" alt=""> @endif
                </div>

                <script>
                    $(function() {
                        <?php $timestamp = time();?>
                        $('#file_upload').uploadifive({
                            'auto'             : true,
                            'buttonText'       : '选择',
                            'buttonClass'      : 'upfiveBtn',
                            'removeCompleted' : true,
                            'removeTimeout' : 1,
                            'uploadLimit'   : 3,
                            'fileSizeLimit' : 5000,
                            'fileTypeExts'     : 'jpg,png,jpeg,gif,mp4',
                            'formData'         : {
                                'timestamp' : '<?php echo $timestamp;?>',
                                'token'     : '<?php echo md5('unique_salt' . $timestamp);?>'
                            },
                            'queueID'          : 'queue',
                            'uploadScript'     : '/uploadimg',
                            'onUploadComplete' : function(file, data) {
                                data = JSON.parse(data);
                                if(data.status == -1){
                                    layer.msg(data.message);return;
                                }

                                var str = '<img class="thumb" src="'+ data.src +'" alt="图片加载失败"/>';
                                $("#select-file-div").append(str);
                                var  articleThumb = $("#article_thumb").val();
                                var src = articleThumb ? articleThumb + ',' + data.src : data.src;
                                $("#article_thumb").val(src);
                            }
                        });
                    });
                </script>
                
                <div class="grid_3 grid_5" style="height: 300px" id="editor"></div>
                <script>
                    $(function(){
                        //实例化编辑器
                        //建议使用工厂方法getEditor创建和引用编辑器实例，如果在某个闭包下引用该编辑器，直接调用UE.getEditor('editor')就能拿到相关的实例
                        var ue = UE.getEditor('editor',{wordCount:false,elementPathEnabled:false});
                    })
                </script>

                <div class="form-group" style="overflow: auto">
                    <div class="col-sm-10" style="padding-left: 0px">
                        <div class="checkbox">
                            <label>
                                <input name="is_show" type="checkbox" @if ( old('is_show') == "on" ) checked @endif checked>向世界公布
                            </label>
                        </div>
                    </div>
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