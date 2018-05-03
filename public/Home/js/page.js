function loadpage(cfg) {
    this.cfg = $.extend({
        sub : 1,
        el: null,
        url: '',
        model:1,
        cacheData:new Object(),
        nowPage : 1,
        totalPag : 1,
        post_params: {},
        beforeload:function(){},
        callfunc:function(){}
    }, cfg);
    this.cfg.loading = true;
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
            self.cfg.model = 1;
            htmlStr += '<li class="ansBtns" elename="'+ eleName +'" data="'+ (nowPage + 1) +'" oft="'+ sub +'"><a href="javascript:void(0)">查看剩余'+ sub +'条回复</a></li>';
            htmlStr += '</ul>';
        }else{
            self.cfg.model = 2;
            htmlStr += '<li class="ansBtns" elename="'+ eleName +'" data="1"><a href="javascript:void(0)">首页</a></li>';
            if(totalPage <= btns){
                for(var i=1;i<=totalPage;i++){
                    htmlStr += parseInt(nowPage) != i ? '<li class="ansBtns" ' : '<li class="active" ';
                    htmlStr += 'elename="'+ eleName +'" data="'+ i +'">';
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
                    htmlStr += parseInt(nowPage) != i ? '<li class="ansBtns" ' : '<li class="active" ';
                    htmlStr += 'elename="'+ eleName +'" data="'+ i +'">';
                    htmlStr += '<a href="javascript:void(0)">'+ i +'</a></li>';
                }
            }
            htmlStr += '<li class="ansBtns" elename="'+ eleName +'" data="'+ totalPage +'"><a href="javascript:void(0)">尾页</a></li>';
            htmlStr += '</ul>';
        }
        $("#" + eleName).append(htmlStr);
        self.btnEvnt();
    },
    btnEvnt:function(){
        var that = this;
        $(document).on('click','.ansBtns',function(){
            var e = $(this);
            var data_ = $(this).attr('data');
            var oft = $(this).attr('oft') || 5;
            var url = that.cfg.url + data_ + '/' + oft;
            if(!that.cfg.cacheData[url]) {
                $.phpajax(url,"get","",true,"json",function(data){
                    data = eval("(" + data + ")");
                    if(data.status != 0){
                        layer.msg("读取消息出错");return;
                    }
                    if(!that.cfg.cacheData[url]){
                        that.cfg.cacheData[url] = data.ext.answer;
                    }
                    var message = data.ext.answer;
                    that.createHtml(message,e);
                })
            }else{
                var message = that.cfg.cacheData[url]
                that.createHtml(message,e);
            }
        })
    },
    createHtml:function(message,e){
        var str = '';
        for(var i = 0; i<message.length;i++){
            var fromUser = message[i].get_from_user_info;
            var toUser = message[i].get_to_user_info;
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
        var ans = $(e).parents(".answ");
        var pa = $(e).parents('.page');

        $(e).parents('.pagination_s').find('li').removeClass('active').removeClass('ansBtns').addClass('ansBtns');
        $(e).removeClass('ansBtns').addClass('active');
        if(this.cfg.model == 1){
            $(pa).before(str).empty();
        }else{
            $(pa).prevAll('.child').remove();
            $(pa).before(str);
        }
    }
}