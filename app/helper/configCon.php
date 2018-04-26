<?php
$dir = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"index.php"))."upload";

define("UPLOADDIR",$dir);

define("COMMENT_POINT",5); //评论增加积分

define("COMMENT_CANCEL_POINT",5); //删除评论扣除积分

define("POST_ARTICLE_POINT",5); //发布文章增加积分

define("LOGIN_POINT",5); //登录增加积分

define("COLLECTION_POINT",5); //点赞增加积分

define("COLLECTION_CANCEL_POINT",5); //取消点赞扣除积分
