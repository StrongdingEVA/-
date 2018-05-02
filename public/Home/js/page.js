function loadpage(cfg) {
    this.cfg = $.extend({
        nowPage : 1,
        totalPag : 1,
        sub : 1,
        el: null,
        url: '',
        post_params: {},
        beforeload:function(){},
        callfunc:function(){}
    }, cfg);
    this.cfg.loading = false;
    this.init();
}
loadpage.prototype = {
    init: function () {
        var self = this;
        self.loadbtn();
    },
    loadbtn: function () {
        var self = this;
        nowPage = self.cfg.nowPage;
        totalPage = self.cfg.totalPage;
        sub = self.cfg.sub;
        btns = self.cfg.btns;
        eleName = self.cfg.el;
        btns = (btns % 2) == 0 ? btns -1 : btns;
        htmlStr = "";
        htmlStr += '<ul class="pagination_s">';
        htmlStr += '<input class="totalPage" value="'+ totalPage +'" type="hidden"/>';
        htmlStr += '<input class="sub" value="'+ sub +'" type="hidden"/>';
        if(sub <= 8){
            htmlStr += '<li onclick="a(this)" elename="'+ eleName +'" type="1" url="'+ self.cfg.url +'" data="2"><a href="javascript:void(0)">查看剩余'+ sub +'条回复</a></li>';
            htmlStr += '</ul>';
        }else{
            htmlStr += '<li onclick="a(this)" elename="'+ eleName +'" type="2" url="'+ self.cfg.url +'" data="1"><a href="javascript:void(0)">首页</a></li>';
            if(totalPage <= btns){
                for(var i=1;i<=totalPage;i++){
                    htmlStr += self.cfg.nowPage != i ? '<li onclick="a(this)" ' : '<li ';
                    htmlStr += 'elename="'+ eleName +'" type="2" url="'+ self.cfg.url +'" data="'+ i +'">';
                    htmlStr += '<a href="javascript:void(0)">'+ i +'</a></li>';
                }
            }else{
                if(parseInt(nowPage) <= parseInt(btns - 1) / 2){
                    star = 1;
                    end = btns > totalPage ? totalPage : btns;
                }else{
                    end = parseInt((btns -1) / 2 ) + parseInt(nowPage);
                    end = end > totalPage ? totalPage : end;
                    star = parseInt(end - btns + 1) > 0 ? (end - btns + 1) : 1;
                }

                for(var i=star;i<=end;i++){
                    htmlStr += self.cfg.nowPage != i ? '<li onclick="a(this)" ' : '<li ';
                    htmlStr += 'elename="'+ eleName +'" type="2" url="'+ self.cfg.url +'" data="'+ i +'">';
                    htmlStr += '<a href="javascript:void(0)">'+ i +'</a></li>';
                }
            }
            htmlStr += '<li onclick="a(this)" elename="'+ eleName +'" type="2" url="'+ self.cfg.url +'" data="'+ totalPage +'"><a href="javascript:void(0)">尾页</a></li>';
            htmlStr += '</ul>';
        }
        $("#" + eleName).append(htmlStr);
    },
}

function a(e){
    nextPage = $(e).attr("data");
    eleName = $(e).attr("elename");
    url = $(e).attr("url");
    type = parseInt($(e).attr("type"));
    url = url.substr(0,url.lastIndexOf('/')+1);
    url += nextPage;
    ans = $(e).parents(".answ");
    pa = $(e).parents('.page');
    $.phpajax(url,"get","",true,"json",function(data){
        data = eval("(" + data + ")");
        if(data.status != 0){
            layer.msg("读取消息出错");return;
        }
        message = data.ext.answer;
        str = "";
        for(var i = 0; i<message.length;i++){
            fromUser = message[i].get_from_user_info;
            toUser = message[i].get_to_user_info;
            str += '<div class="response-item child">';
            str += '<div class="response-item-head">';
            str += '<a href="javascript:void(0)">';
            str += '<img class="media-object" src="'+ fromUser.logo +'" alt="">';
            str += '</a>';
            str += '</div>';
            str += '<div class="response-item-info">';
            str += '<div class="info-username">';
            str += '<a href="javascript:void(0)">'+ fromUser.username + '</a>';
            if(toUser.username){
                str += ' 回复 <a href="javascript:void(0)">'+ toUser.username + '</a>';
            }

            str += '</div>';
            str += '<div class="info-username">';
            str += '<span>'+ message[i].article_comment +'</span>';
            str += '</div>';
            str += '<div class="info-username last">';
            str += '<span class="time">'+ message[i].created_at +'</span>';
            str += '<span class="comment createedui" articleId="'+ message[i].article_id +'" commentId="'+ message[i].comment_id +'" this-user="'+  fromUser.id+'" this-username="'+ fromUser.username +'" that-username="'+ fromUser.username +'"><i class="reply"></i>回复</span>';
            str += '</div>';
            str += '</div>';
            str += '</div>';
        }
        type == 2 ? $(ans).prepend(str) : $(pa).before(str).empty();
    })

    $("#" + eleName).empty();
    totalP = $(e).siblings(".totalPage").val();
    s = $(e).siblings(".sub").val();
    new loadpage({
        nowPage: nextPage,
        totalPage:totalP,
        sub:s,
        btns:7,
        el: eleName,
        url: url
    })
}