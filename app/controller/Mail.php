<?php
namespace app\controller;

use app\BaseController;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use think\facade\Request;


class Mail
{
    public function index()
    {
        return '欢迎使用LoCyan发信接口！';
    }
    
<<<<<<< HEAD
=======
    // 一个简易的发信接口
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
    public function SendMail()
    {
        $toemail = Request::get("address");
        $subject = Request::get("subject");
        $message = Request::get("message");
        $key = Request::get("key");
<<<<<<< HEAD
        if ($key != "Locyan666") {
=======
        if ($key != "xxxxxxxxxx") {
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
            return '密钥匹配失败！' . $message;
        }
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
<<<<<<< HEAD
        $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "admin@locyan.cn";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">
        $mail->Password = "";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="co  lor:#333333;">
        $mail->SMTPSecure = "tls";// 使用ssl协议方式</span><span style="color:#333333;">
        $mail->Port = 587;// 163邮箱的ssl协议方式端口号是465/994
        $mail->SMTPDebug = true;
        $mail->setFrom("admin@locyan.cn", "LoCyan Team");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail, 'User');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addReplyTo("admin@locyan.cn", "Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
=======
        $mail->Host = "smtp.xxx.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "xxx@locyan.cn";// 发送方的163邮箱用户名，就是你申请163的SMTP服务使用的163邮箱</span><span style="color:#333333;">
        $mail->Password = "xxxxxxxxxxxxxxx";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！</span><span style="co  lor:#333333;">
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式</span><span style="color:#333333;">
        $mail->Port = 465;// 163邮箱的ssl协议方式端口号是465/994
        $mail->SMTPDebug = true;
        $mail->setFrom("xxx@locyan.cn", "YourName");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
        $mail->addAddress($toemail, 'User');// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
        $mail->addReplyTo("xxx@locyan.cn", "Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        //$mail->addAttachment("bug0.jpg");// 添加附件
        $mail->Subject = "{$subject}";// 邮件标题
        $mail->Body = "{$message}";// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用
        $statue = $mail->send();
    }
}
