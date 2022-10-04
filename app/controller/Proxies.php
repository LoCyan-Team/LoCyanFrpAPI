<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class Proxies{
    public function index()
    {
        return '欢迎访问隧道控制页面，该页面不可直接访问！';
    }
    public function add()
    {
        $username = Request::get("username");
        $name = Request::get("name");
        $key = Request::get("key");
        $ip = Request::get("ip");
        $type = Request::get("type");
        $lp = Request::get("lp");
        $rp = Request::get("rp");
        $ue = Request::get("ue");
        $uz = Request::get("uz");
        $id = Request::get("id");
        $url = Request::get("url");
        
        if ($username == null || $name == null || $key == null || $ip == null || $type == null || $lp == null || $rp == null || $ue == null || $uz == null || $id == null){
            $data = [
                "status"                => -2,
                "message"               => "信息不完整"
                ];
            return json($data);
        }
        
        if ($type == 1) {
            $type = "tcp";
        } elseif ($type == 2) {
            $type = "udp";
        } elseif ($type == 3) {
            $type = "http";
        } elseif ($type == 4) {
            $type = "https";
        } elseif ($type == 5) {
            $type = "xtcp";
        } elseif ($type == 6) {
            $type = "stcp";
        } else {
            $data = [
                "status"             => -1,
                "message"            => "类型错误"
                ];
            return json($data);
        }
        if ($ue == "1"){
            $ue = "true";
        } else {
            $ue = "false";
        }
        
        if ($uz == "1"){
            $uz = "true";
        } else {
            $uz = "false";
        }
        
        //判断端口是否被占用
        $rs_port = Db::table("proxies")->where("remote_port",$rp)->where("node",$id)->find();
        if ($rs_port != null) {
            $data = [
                "status"            => -3,
                "message"           => "端口已被占用"
                ];
            return json($data);
        }
        
        if ($rp > 0 && $rp < 10000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp > 30000 && $rp < 31000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp > 25565 && $rp < 25656){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        $insert_data = [
            "id"                    => null,
            "username"              => $username,
            "proxy_name"            => $name,
            "proxy_type"            => $type,
            "local_ip"              => $ip,
            "local_port"            => $lp,
            "remote_port"           => $rp,
            "use_encryption"        => $ue,
            "use_compression"       => $uz,
            "domain"                => $url,
            "locations"              => null,
            "host_header_rewrite"   => null,
            "sk"                    => null,
            "lastupdate"            => time(),
            "node"                  => $id,
            "status"                => 0
            ];
        Db::table("proxies")->insert($insert_data);
        $data = [
            "status"             => 0,
            "message"            => "添加成功"
            ];
        return json($data);
    }
    public function remove(){
        $id = Request::post("proxyid");
        $username = Request::post("username");
        $token = Request::post("token");
        $rs = Db::table("proxies")->where("id",$id)->find();
        
        if($rs == null){
            $data = [
                "status"             => -1,
                "message"            => "隧道不存在"
                ];
            return json($data);
        }
        $username1 = $this->GetUserName($token);
        if($username != $username1){
            $data = [
                "status"             => -2,
                "message"            => "用户名与密钥不符合"
                ];
            return json($data);
        }
        
        Db::table("proxies")->where("id",$id)->delete();
        $data = [
            "status"             => 0,
            "message"            => "删除成功"
            ];
        return json($data);
    }
    public function GetUserName($token){
        $rs = Db::table("tokens")->where("token",$token)->find();
        return $rs["username"];
    }
}

?>