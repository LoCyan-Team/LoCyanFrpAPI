<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class Account
{
    public function index()
    {
        return '欢迎访问账号控制页面，该页面不可直接访问！';
    }
    public function Info()
    {
        if (Request::get("username") == null) {
        $data = [
            "status"            => -1,
            "message"           => "请提供用户名"
            ];
        return json($data);
        }
        if (Request::get("token") == null) {
        $data = [
            "status"            => -2,
            "message"           => "请提供登录TOKEN"
            ];
        return json($data);
        }
        
        $user = Request::get("username");
        $token = Request::get("token");
        $user_tmp = $this->GetUserName($token);
        if($user !== $user_tmp){
        $data = [
            "status"            => -3,
            "message"           => "登录token校验失败！"
            ];
        return json($data);
        }
        $rs = Db::table("users")->where("username",$user)->find();
        $rs2 = Db::table("tokens")->where("username",$user)->find();
        
        if ($rs == null || $rs2 == null) {
        $data = [
            "status"            => -2,
            "message"           => "用户不存在！"
            ];
        return json($data);
        } else {
        $data = [
            "status"            => 0,
            "username"          => $user,
            "email"             => $rs["email"],
            "token"             => $rs2["token"],
            "traffic"           => $rs["traffic"],
            "avator"            => 'https://cravatar.cn/avatar/' . md5($rs["email"])
            ];
        return json($data);
        }
    }
    public function GetUserName($token = ""){
        if ($token !== ""){ //为系统内部调佣提供条件
            $rs = Db::table("logintoken")->where("token",$token)->find();
            if ($rs == null){
                return -1;
            }
            return $rs["username"];
        }
        $token = Request::get("token");
        $rs = Db::table("logintoken")->where("token",$token)->find();
        if ($rs == null){
            $data = [
                "status"            => -1,
                "message"           => "获取失败"
                ];
            return json($data);
        }
        $data = [
            "status"            => 0,
            "message"           => "查询成功",
            "username"          => $rs["username"]
            ];
        return json($data);
    }
    public function GetUserNameByFrpToken($token){
        $rs = Db::table("tokens")->where("token",$token)->find();
        
        if($rs == null){
            $data = [
                "status"        => -1,
                "message"       => "该TOKEN不存在！"
                ];
            return json($data);
        } else {
            $data = [
                "status"        => 0,
                "message"       => "成功",
                "username"      => $rs["username"]
                ];
            return json($data);
        }
    }
    public function realnamecheck($idcard ,$name){
        if($idcard == "" || $name == ""){
            return '我不知道你是怎么搞到实名认证地址的，但我希望你做个好人不要瞎搞，好嘛';
        }
        // 上游分配的ID
<<<<<<< HEAD
        $appid = '';
        $appkey = '';
=======
        // 这边使用的是华际的API
        $appid = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
        $appkey = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        
        // 签名
        $millisecond = '000';
        $timestamp = time().$millisecond;
        $signStr = $appid.$timestamp.$appkey;
        $sign = md5($signStr);
        
        // 请求方法
        $method = 'POST';
        
        // body参数（POST方法下）
        $bodyParams = array (
            'appid' => $appid,
            'timestamp' => $timestamp,
            'sign' => $sign,
            'idcard' => $idcard,
            'name' => $name
        );
        // url参数拼接
        $url = 'https://api.huajidata.com/id_name/check';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
        
        
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            return "Error: " . curl_error($ch);
        } else {
            return ($data);
        }
        curl_close($ch);
    }
    public function realname(){
        $username = Request::post("username");
        $name = Request::post("name");
        $idcard = Request::post("idcard");
        $key = Request::post("key");
        
        if(!isset($idcard) || !isset($name) || !isset($username) || !isset($key) || $idcard == "" || $name == "" || $username == "" || $key == ""){
            $data = [
                "status"        => false,
                "message"       => "那个，你可以吧信息填完整吗？"
                ];
            return json($data);
        }
        
<<<<<<< HEAD
        if($key !== "LocyanRealname"){
=======
        if($key !== "xxxxxxxxxxxxx"){
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
            $data = [
                "status"        => false,
                "message"       => "密钥错误，请仔细核对您的密钥！"
                ];
            return json($data);
        }
        
        $rs = $this->realnamecheck($idcard, $name);
        $rs_decoded = json_decode($rs, true);
        // 如果结果不是200，则抛出错误信息
        if ($rs_decoded["code"] != 200) {
            $data = [
                "status"        => false,
                "message"       => $rs_decoded["msg"]
                ];
            return json($data);
        }
        // 若data.result 为1则匹配，2则不匹配，3则无信息
        $rs2 = $rs_decoded["data"]["result"];
        if($rs2 == "1"){
            $this->realnamescs($username, $name, $idcard);
            $data = [
                "status"        => true,
                "message"       => "恭喜您，成功完成实名认证，您可以继续接下来的操作！"
                ];
            return json($data);
        } else {
            $data = [
                "status"        => false,
                "message"       => "实名认证失败，请检查您的设置！"
                ];
            return json($data);
        }
    }
    public function realnamescs($username, $name, $idcard){
<<<<<<< HEAD
        $pub_key = "";
=======
        $pub_key = "你的公钥";
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        $key = openssl_pkey_get_public($pub_key);
        openssl_public_encrypt($idcard, $encrypted, $key);
        $encrypted = base64_encode($encrypted);
        $data = [
            "id"            => null,
            "username"      => $username,
            "name"          => $name,
            "idcard"        => $encrypted
            ];
        Db::table("realname")->insert($data);
        $update_data = [
            "group"         => "all_certified"
            ];
        Db::table("users")->where("username", $username)->update($update_data);
        return 0;
    }
    public function GetRealnameStatus(){
        $username = Request::get("username");
        if($username == null){
            return '那个，你可以吧名字写上吗？';
        }
        $rs = Db::table("realname")->where("username",$username)->find();
        if ($rs == null){
            $data = [
                "status"        => false,
                "message"       => "未实名"
                ];
            return json($data);
        } else {
            $data = [
                "status"        => true,
                "message"       => "已实名"
                ];
            return json($data);
        }
    }
    
    // 将身份证进行RSA加密
<<<<<<< HEAD
=======
    // 这一段代码适用于浏览器执行，用于批量加密没有加密过的用户身份数据
    // 即将所有明文存储的数据进行RSA加密后存储
    // 公钥和私钥对请自行生成，并做好保密工作！
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
    public function updaterealname(){
        $rs = Db::table("realname")->select()->toArray();
        foreach ($rs as $r){
            if (substr($r["idcard"], -1) == "X" || substr($r["idcard"], -1) == "x") {
<<<<<<< HEAD
                $pub_key = "";
=======
                $pub_key = "你的公钥";
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
                $key = openssl_pkey_get_public($pub_key);
                openssl_public_encrypt($r["idcard"], $encrypted, $key);
                $encrypted = base64_encode($encrypted);
                $data = [
                    "idcard"        => $encrypted
                    ];
                Db::table("realname")->where("id", $r["id"])->update($data);
            }
        }
        return "转换结束";
    }
}
