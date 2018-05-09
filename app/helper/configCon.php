<?php
$dir = substr($_SERVER["SCRIPT_FILENAME"],0,strrpos($_SERVER["SCRIPT_FILENAME"],"index.php"))."upload";

define("UPLOADDIR",$dir);

define("COMMENT_POINT",5); //评论增加积分

define("COMMENT_CANCEL_POINT",5); //删除评论扣除积分

define("POST_ARTICLE_POINT",5); //发布文章增加积分

define("LOGIN_POINT",5); //登录增加积分

define("COLLECTION_POINT",5); //点赞增加积分

define("COLLECTION_CANCEL_POINT",5); //取消点赞扣除积分

define('ART_KEY','art_key_'); //文章详情缓存key
define('ART_KEY_UROD','art_key_urod_'); //用户发布文章缓存
define('ANS_KEY','ans_key_'); //文章评论缓存key
define('ART_KEY_HOT','art_key_hot'); //文章评论缓存key
define('ART_KEY_SCL','art_key_scl'); //首页滚动文章
define('ART_KEY_COM','art_key_com_'); //文章评论
define('ART_KEY_COM_PAGE','art_key_com_p_'); //文章评论分页
define('USER_EXT','user_ext_'); //用户拓展信息
define('USER_MSG','user_msg_'); //用户拓展信息