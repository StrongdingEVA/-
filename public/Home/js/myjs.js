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
                callback(data);
            }
        })
    },
});