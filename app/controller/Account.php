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
            "token"             => $rs2["token"]
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
        $token = Request::post("token");
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
    public function realnamecheck($idcard ,$name){
        if($idcard == "" || $name == ""){
            return '我不知道你是怎么搞到实名认证地址的，但我希望你做个好人不要瞎搞，好嘛';
        }
        // 云市场分配的密钥Id
        $secretId = '';
        // 云市场分配的密钥Key
        $secretKey = '';
        $source = 'market';
        
        // 签名
        $datetime = gmdate('D, d M Y H:i:s T');
        $signStr = sprintf("x-date: %s\nx-source: %s", $datetime, $source);
        $sign = base64_encode(hash_hmac('sha1', $signStr, $secretKey, true));
        $auth = sprintf('hmac id="%s", algorithm="hmac-sha1", headers="x-date x-source", signature="%s"', $secretId, $sign);
        
        // 请求方法
        $method = 'POST';
        // 请求头
        $headers = array(
            'X-Source' => $source,
            'X-Date' => $datetime,
            'Authorization' => $auth,
            
        );
        // 查询参数
        $queryParams = array (
        
        );
        // body参数（POST方法下）
        $bodyParams = array (
            'idcard' => $idcard,
            'name' => $name,
        );
        // url参数拼接
        $url = 'https://service-isr6xhvr-1308811306.sh.apigw.tencentcs.com/release/id_name/check';
        if (count($queryParams) > 0) {
            $url .= '?' . http_build_query($queryParams);
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_map(function ($v, $k) {
            return $k . ': ' . $v;
        }, array_values($headers), array_keys($headers)));
        if (in_array($method, array('POST', 'PUT', 'PATCH'), true)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($bodyParams));
        }
        
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
        
        if($key !== ""){
            return '密钥错误，请仔细核对您的密钥！';
        }
        
        if(!isset($idcard) || !isset($name)){
            return '那个，你可以吧信息填完整吗？';
        }
        $rs = $this->realnamecheck($idcard, $name);
        $rs_decoded = json_decode($rs,true);
        if($rs_decoded["msg"] !== ""){
            return $rs_decoded["msg"];
        }
        $rs2 = json_encode($rs_decoded["data"]["result"]);
        if($rs2 == "1"){
            $this->realnamescs($username,$name,$idcard);
            return '恭喜您，成功完成实名认证，您可以继续接下来的操作！';
        } else {
            return '实名认证失败，请检查您的设置！';
        }
    }
    public function realnamescs($username, $name, $idcard){
        $data = [
            "id"            => null,
            "username"      => $username,
            "name"          => $name,
            "idcard"        => $idcard
            ];
        Db::table("realname")->insert($data);
        return 0;
    }
    public function GetRealnameStatus(){
        $username = Request::get("username");
        if($username == null){
            return '那个，你可以吧名字写上吗？';
        }
        $rs = Db::table("realname")->where("username",$username)->find();
        if ($rs == null){
            return -1;
        } else {
            return 0;
        }
    }
}
