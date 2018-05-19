<?php
namespace smtpmail;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14
 * Time: 21:43
 */
class SendMail{
    public static function send($to,$title,$content){
        if(!$to || !$title || !$content){
            return false;
        }
        //******************** 配置信息 ********************************
        $smtpserver = SMTP_SERVER;//SMTP服务器
        $smtpserverport = SMTP_PORT;//SMTP服务器端口
        $smtpusermail = SMTP_USER_MAIL;//SMTP服务器的用户邮箱
        $smtpemailto = $to;//发送给谁
        $smtpuser = SMTP_USER;//SMTP服务器的用户帐号，注：部分邮箱只需@前面的用户名
        $smtppass = SMTP_PASS;//SMTP服务器的用户密码
        $mailtitle = $title;//邮件主题
        $mailcontent = $content;//邮件内容
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        //************************ 配置信息 ****************************
        $smtp = new Smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
        $smtp->debug = true;//是否显示发送的调试信息
        return $smtp->sendmail($smtpemailto, $smtpusermail, $mailtitle, $mailcontent, $mailtype);
    }
}