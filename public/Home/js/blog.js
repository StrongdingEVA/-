/**
 * Created by Administrator on 2018/4/27.
 */
var blog = {
    _init:function (options) {
        options = options || {};
    },
    request_:function(param,url,type,sync,c){
        var that = this;
        $.ajax({
            url:url,
            data:param,
            type:type,
            async:sync || true,
            success:function(res){
                if(typeof(res) != 'object'){
                    var res = eval('(' + res + ')');
                }
                c && c(res);
            }
        })
    },

    isMobile:function(mobile){
        if(!mobile){return false}
        var reg = new RegExp(/^1[34578]\d{9}$/);
        if(!reg.test(mobile)){
            return false;
        }
        return true;
    },

    countDown:function(ele,t,text){
        $(ele).attr('disabled',true);
        var t = t || 60;
        var text = text || '获取验证码';
        var d = setInterval(function () {
            t = t - 1;
            $(ele).text('(' + t + 'S)');
            if(t == 0){
                clearInterval(d);
                $('ele').text(text);
                $(ele).attr('disabled',false);
            }
        },1000)
    },

    bindDump:function(ele){
        $(document).on('click',ele,function(){
            var url = $(this).attr('url-data');
            if(url){
                window.location.href = url;
            }
        })
    }
}
