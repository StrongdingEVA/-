$.extend({
    phpajax:function(url,type,data,is_ansy,dataType,callback){
        is_ansy = is_ansy ? is_ansy : true;
        dataType = dataType ? dataType : "json"
        $.ajax({
            url : url,
            type : type,
            data : data,
            async : is_ansy,
            success : function(data){
                callback && callback(data);
                return data;
            }
        })
    },
     checkLogin:function(redirec,callback){
        redirec = redirec || '/';
        var res = this.phpajax('/checkIsLogin','post','',true,'json',function(res){
            if(callback){
                var res = JSON.parse(res);
                callback(res)
            }else{
                window.location.href = redirec;
            }
        })
     }
});