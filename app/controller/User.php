<?php
namespace app\controller;

use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class User
{
    public function index($id = 1)
    {
        return "此页面为API的登录页，不可直接访问";
    }
    
    public function DoLogin(){
        $name = Request::get("username");
        $pwd = Request::get("password");
        
        // 适配邮箱登录
        if (strstr($name, "@") != ""){
            $login_info = Db::table("users")->where("email",$name)->find();
        } else {
            $login_info = Db::table("users")->where("username",$name)->find();
        }
        
        if($login_info == null){
            $data = [
                "status"            => -1,
                "message"           => "登录失败，账号或密码错误"
                ];
            return json($data);
        }
        $a = password_verify($pwd ,$login_info["password"]);
        if($a == 1){
            if ($login_info["status"] != 0){
                $status_return = [
                    "status"        => -2,
                    "message"       => "账号已被禁用"
                    ];
                return json($status_return);
            }
            
            if (Db::table("logintoken")->where("username",$login_info["username"])->find() != null){
                Db::table("logintoken")->where("username",$login_info["username"])->delete();
            }
            $token = md5(sha1($login_info["username"] . strval(time())));
            $insert_data = [
                "id"                => null,
                "username"          => $login_info["username"],
                "token"             => $token
                ];
            Db::table("logintoken")->insert($insert_data);
            $rs = Db::table("users")->where("username",$login_info["username"])->find();
            $token_tmp = Db::table("tokens")->where("username",$login_info["username"])->find();
            $limit_tmp = Db::table("limits")->where("username",$login_info["username"])->find();
            if ($limit_tmp == null){
                $limit_out = 1280;
                $limit_in = 1280;
            } else {
                $limit_out = $limit_tmp["outbound"];
                $limit_in = $limit_tmp["inbound"];
            }
            $userdata = [
                "username"          => $login_info["username"],
                "email"             => $rs["email"],
                "frptoken"          => $token_tmp["token"],
                "traffic"           => $rs["traffic"],
                "inbound"           => $limit_in,
                "outbound"          => $limit_out
            ];
            $data = [
                "status"            => 0,
                "message"           => "登录成功",
                "token"             => $token,
                "userdata"          => $userdata
                ];
            return json($data);
        } else {
            $data = [
                "status"            => -1,
                "message"           => "登录失败，账号或密码错误"
                ];
            return json($data);
        }
    }
    
    public function DoReg(){
            $name = Request::post("username");
            $pwd = Request::post("password");
            $pwd2 = Request::post("confirmpwd");
            $email = Request::post("email");
            $qq = Request::post("qq");
            $code_temp = Request::post("verify");
            $code1 = Db::table("verify")->where("email",$email)->find();
            
            if ($pwd != $pwd2){
                $data = [
                    "status"            => false,
                    "message"           => "注册失败，你这俩密码咋还不一样呢？"
                    ];
                return json($data);
            }
            
            if($code1 == null){
                $data = [
                    "status"            => false,
                    "message"           => "注册失败，清先获取验证码"
                    ];
                return json($data);
            }
            $code = $code1["code"];
//             $pub_key = "-----BEGIN PUBLIC KEY-----
// MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAys+3MzKwPR4ykeoMbQud 4IJ6P8K4L9ajF+rxsQsJxYlfrE4GVTtyKHgPA/c/7r1hCLPwcdYO/izCDXTJBCLR QUFqOHZ8nWbDR3feO3grEUMG1rq+gQW/aIiPwP2OtueEthRUVrqvmLxlkjmJho8E E93hR/ACVcJ1xpHNqEYaPgBaD12Q/rkcsmjEFcS+X4GEnBUeRi5xrmForIE4v/ef K2sx1FG4KnIHtEjUk0z93XTQPHj36nF0qlTF/VqHWMPj9SRdgmSsWHyz7OrP3Mv2 pxgyLwtZsxw+mUS2M9GO0NhUZ2eooHEmkR4fx/jwOUaQ9I8uTqfINHtBL/R6ixoo WQIDAQAB
// -----END PUBLIC KEY-----";
//             openssl_public_encrypt($pwd, $encrypted, $pub_key);
            $password = password_hash($pwd,PASSWORD_BCRYPT);
            $a = Db::table("users")->where("username",$name)->find();
            if ($a != null) {
                Db::table("verify")->where("email",$email)->delete();
                $data = [
                    "status"            => false,
                    "message"           => "注册失败，该用户名已存在！"
                    ];
                return json($data);
            }
            
            $b = Db::table("users")->where("email",$email)->find();
            if ($b != null) {
                Db::table("verify")->where("email",$email)->delete();
                $data = [
                    "status"            => false,
                    "message"           => "注册失败，该邮箱已存在！请更换邮箱后重新获取验证码！"
                    ];
                return json($data);
            }
            
            if($code != $code_temp){
                $data = [
                    "status"            => false,
                    "message"           => "注册失败，验证码不正确！"
                    ];
                return json($data);
            }
            
            $data = [
                "id"                => null,
                "username"          => $name,
                "password"          => $password,
                "email"             => $email,
                "traffic"           => 4096,
                "proxies"           => -1,
                "group"             => "default",
                "regtime"           => time(),
                "status"            => 0
             ];
        Db::table("users")->insert($data);
        Db::table("verify")->where("email",$email)->delete();
        Session::set("status",true);
        $data_token = [
            "id"                => null,
            "username"          => $name,
            "token"             => md5(sha1(md5($name . $password . time() . mt_rand(0, 9999999)))),
            "status"            => 0
            ];
        Db::table("tokens")->insert($data_token);
        $data_qq = [
            "id"                => null,
            "username"          => $name,
            "qq"                => $qq,
            ];
        Db::table("qq")->insert($data_qq);
        $data = [
            "status"            => true,
            "message"           => "注册成功，欢迎使用LoCyan Frp Experience"
            ];
        return json($data);
    }
    public function SendRegCode(){
        $email = Request::post("email");
        if ($email == null){
            $data = [
                "status"            => false,
                "message"           => "邮箱不可为空！"
                ];
            return json($data);
        }
        $info = Db::table("verify")->where("email", $email)->find();
        if($info != null){
            Db::table("verify")->where("email",$email)->delete();
        }
        $code = strval(rand(100000,999999));
        $data = [
            "id"        => null,
            "email"     => $email,
            "code"      => $code
            ];
        Db::table("verify")->insert($data);
        $mailer_sender = new \app\common\Utils;
        $mail = $mailer_sender->sendMail($email ,$code);
        if ($mail == 0) {
            $data = [
                "status"            => true,
                "message"           => "邮件发送成功，请注意查收，若没有看到邮件，请检查收件箱或点击重新发送"
                ];
            return json($data);
        } else {
            //Session::set("status",false);
            //Session::set("ErrorInfo","邮箱格式填写不正确或者服务器宕机，请稍后再试！");
            $data = [
                "status"            => false,
                "message"           => "发送失败，邮箱格式填写不正确或者服务器宕机，请稍后再试！"
                ];
            return json($data);
        }
    }
    public function CheckSign(){
        $token = Request::post("token");
        $rs = Db::table("logintoken")->where("token", $token)->find();
        if(!$rs){
            $data = [
                "status"        => false,
                "message"       => "登录token不存在！"
                ];
            return json($data);
        }
        $username = $rs["username"];
        $rs = Db::table("sign")->where("username", $username)->find();
        if (!$rs){
            $data = [
                "status"        => true,
                "message"       => "未签到"
                ];
            return json($data);
        }
        $signdate = $rs["signdate"];
        if(Intval(date("Ymd")) >= Intval(date("Ymd", $signdate)) + 1){
            $data = [
                "status"        => true,
                "message"       => "未签到"
                ];
            return json($data);
        } else {
            $data = [
                "status"        => true,
                "message"       => "已签到"
                ];
            return json($data);
        }
    }
    public function DoSign(){
        $token = Request::post("token");
        $rs = Db::table("logintoken")->where("token", $token)->find();
        if(!$rs){
            $data = [
                "status"        => false,
                "message"       => "登录token不存在！"
                ];
            return json($data);
        }
        $username = $rs["username"];
        $rs = Db::table("sign")->where("username", $username)->find();
        if (!$rs){
            $rand = mt_rand(1, 2000);
            $insert = [
                "id"            => null,
                "username"      => $username,
                "signdate"      => time(),
                "totalsign"     => 1,
                "totaltraffic"  => $rand
                ];
            Db::table("sign")->insert($insert);
            $rs = Db::table("users")->where("username", $username)->find();
            $update = [
                "traffic"      => $rs["traffic"] + $rand * 1024
            ];
            Db::table("users")->where("username", $username)->update($update);
            $data = [
                "status"        => true,
                "message"       => "签到成功，这是你第一次签到，获得" . $rand . "GiB流量，感谢你选择LCF！"
                ];
            return json($data);
        }
        $signdate = $rs["signdate"];
        if(Intval(date("Ymd")) >= Intval(date("Ymd", $signdate)) + 1){
            $rand = mt_rand(1, 2000);
            // 更新sign表
            $update = [
                "signdate"      => time(),
                "totalsign"     => $rs["totalsign"] + 1,
                "totaltraffic"  => $rs["totaltraffic"] + $rand
            ];
            Db::table("sign")->where("username", $username)->update($update);
            
            // 更新users表
            $rs = Db::table("users")->where("username", $username)->find();
            $update = [
                "traffic"      => $rs["traffic"] + $rand * 1024
            ];
            Db::table("users")->where("username", $username)->update($update);
            $data = [
                "status"        => true,
                "message"       => "签到成功，获得" . $rand . "GiB流量"
                ];
            return json($data);
        } else {
            $data = [
                "status"        => false,
                "message"       => "你今天已经签到过了哦~"
                ];
            return json($data);
        }
        
        
    }
}
?>