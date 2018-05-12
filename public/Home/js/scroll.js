(function ($) {
    $.fn.selectbox = function (options) {
        var opts = $.extend({}, $.fn.selectbox.defaults, options);
        return this.each(function () {
            var self = $(this);
            self.status = 0;
            self.page = opts.page;
            self.type = opts.type;
            self.pageCount = opts.pageCount;
            self.bottom_height = opts.bottom_height;
            $(self).scroll(function(){
                var tab_name = self[0].tagName;
                var s_top = $(self).scrollTop();
                if(tab_name === undefined){ //window
                    scrollheight = $(window).scrollTop() + $(window).height();
                    height = $(document).height();
                    distence = parseInt(height) - parseInt(scrollheight);
                    if(distence <= 500 && self.status == 0){
                        self.page = self.page + 1;
                        if(self.page <= self.pageCount){
                            self.status = 1;
                            opts.callback(self);
                        }
                    }
                }else{
                    var e_height = $(self).height();
                    var e_chirld =$(self).children();
                    var a_height = 0;
                    e_chirld.map(function(e){
                        a_height += $(e_chirld[e]).height();
                    })
                    if(a_height - s_top < self.bottom_height && self.status == 0){
                        self.status = 1;
                        opts.callback(self);
                    }
                }
            })
        });
    };
    $.fn.selectbox.defaults = {
    };
})(jQuery);