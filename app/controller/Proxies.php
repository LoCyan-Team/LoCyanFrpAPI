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
    
    public function GetProxiesList(){
        // 获取需要参数
        $username = Request::get("username");
        $token = Request::get("token");
        
        // 鉴权
        $rs = Db::table("logintoken")->where("username", $username)->find();
        $token_verify = $rs["token"];
        if ($token_verify != $token){
            // 不匹配就返回掉
            $data = [
                "status"        => -1,
                "message"       => "用户登录TOKEN校验失败！请重新登录获取token"
                ];
            return json($data);
        } else {
            // 反之则匹配
            
            // 获取隧道列表
            $rs = Db::table("proxies")->where("username", $username)->select()->toArray();
            $indata = [];
            foreach ($rs as $r){
                if ($r["proxy_type"] == "http" || $r["proxy_type"] == "https"){
                    // HTTP(S) 隧道需要返回domain字段
                    // str_replace('["', "", str_replace('"]', "", $r["domain"]))
                    // 先替换"]为空值，然后替换["为空值，返回纯域名
                    $indata_tmp = [
                        "id"                => $r["id"],
                        "proxy_name"        => $r["proxy_name"],
                        "proxy_type"        => $r["proxy_type"],
                        "local_ip"          => $r["local_ip"],
                        "local_port"        => $r["local_port"],
                        "remote_port"       => $r["remote_port"],
                        "use_compression"   => $r["use_compression"],
                        "use_encryption"    => $r["use_encryption"],
                        "domain"            => str_replace('["', "", str_replace('"]', "", $r["domain"])),
                        "node"              => $r["node"]
                        ];
                } else {
                    $indata_tmp = [
                        "id"                => $r["id"],
                        "proxy_name"        => $r["proxy_name"],
                        "proxy_type"        => $r["proxy_type"],
                        "local_ip"          => $r["local_ip"],
                        "local_port"        => $r["local_port"],
                        "remote_port"       => $r["remote_port"],
                        "use_compression"   => $r["use_compression"],
                        "use_encryption"    => $r["use_encryption"],
                        "domain"            => "",
                        "node"              => $r["node"]
                        ];
                }
                // if完成以后吧indata的数据加到data中
                array_push($indata,$indata_tmp);
            }
            // 循环完成以后将生成的数据转换为json输出
            $data = [
                "status"            => 0,
                "message"           => "获取成功",
                "count"             => count($rs),
                "proxies"           => $indata
                ];
            return json($data);
        }
    }
    
    public function GetProxiesListByNode(){
        // 获取需要参数
        $username = Request::get("username");
        $node = Request::get("node");
        
        // 获取隧道列表
        $rs = Db::table("proxies")->where("username", $username)->where("node", $node)->select()->toArray();
        $indata = [];
        foreach ($rs as $r){
            if ($r["proxy_type"] == "http" || $r["proxy_type"] == "https"){
                // HTTP(S) 隧道需要返回domain字段
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => str_replace('["', "", str_replace('"]', "", $r["domain"])),
                    "node"              => $r["node"]
                    ];
            } else {
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => "",
                    "node"              => $r["node"]
                    ];
            }
            // if完成以后吧indata的数据加到data中
            array_push($indata,$indata_tmp);
        }
        // 循环完成以后将生成的数据转换为json输出
        $data = [
            "status"            => 0,
            "message"           => "获取成功",
            "count"             => count($rs),
            "proxies"           => $indata
            ];
        return json($data);
    }
    public function GetProxiesListByUsername(){
        // 获取需要参数
        $username = Request::get("username");
        
        // 获取隧道列表
        $rs = Db::table("proxies")->where("username", $username)->select()->toArray();
        $indata = [];
        foreach ($rs as $r){
            if ($r["proxy_type"] == "http" || $r["proxy_type"] == "https"){
                // HTTP(S) 隧道需要返回domain字段
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => str_replace('["', "", str_replace('"]', "", $r["domain"])),
                    "node"              => $r["node"]
                    ];
            } else {
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => "",
                    "node"              => $r["node"]
                    ];
            }
            // if完成以后吧indata的数据加到data中
            array_push($indata,$indata_tmp);
        }
        // 循环完成以后将生成的数据转换为json输出
        $data = [
            "status"            => 0,
            "message"           => "获取成功",
            "count"             => count($rs),
            "proxies"           => $indata
            ];
        return json($data);
    }
    public function GetProxiesListByID(){
        // 获取需要参数
        $username = Request::get("username");
        $id = Request::get("id");
        
        // 获取隧道列表
        $rs = Db::table("proxies")->where("username", $username)->where("id", $id)->select()->toArray();
        $indata = [];
        foreach ($rs as $r){
            if ($r["proxy_type"] == "http" || $r["proxy_type"] == "https"){
                // HTTP(S) 隧道需要返回domain字段
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => str_replace('["', "", str_replace('"]', "", $r["domain"])),
                    "node"              => $r["node"]
                    ];
            } else {
                $indata_tmp = [
                    "id"                => $r["id"],
                    "proxy_name"        => $r["proxy_name"],
                    "proxy_type"        => $r["proxy_type"],
                    "local_ip"          => $r["local_ip"],
                    "local_port"        => $r["local_port"],
                    "remote_port"       => $r["remote_port"],
                    "use_compression"   => $r["use_compression"],
                    "use_encryption"    => $r["use_encryption"],
                    "domain"            => "",
                    "node"              => $r["node"]
                    ];
            }
            // if完成以后吧indata的数据加到data中
            array_push($indata,$indata_tmp);
        }
        // 循环完成以后将生成的数据转换为json输出
        $data = [
            "status"            => 0,
            "message"           => "获取成功",
            "count"             => count($rs),
            "proxies"           => $indata
            ];
        return json($data);
    }
    public function GetServerList(){
        $rs = Db::table("nodes")->select()->toArray();
        $data = [];
        foreach ($rs as $r){
            $indata = [
                "id"            => $r["id"],
                "name"          => $r["name"],
                "description"   => $r["description"],
                "ip"            => $r["ip"],
                "hostname"      => $r["hostname"],
                "status"        => $r["status"]
                ];
            array_push($data,$indata);
        }
        return json($data);
    }
    
    public function GetServerInfoByNode(){
        $node = Request::get("node");
        $rs = Db::table("nodes")->where("id", $node)->find();
        $data = [
            "id"            => $rs["id"],
            "name"          => $rs["name"],
            "description"   => $rs["description"],
            "ip"            => $rs["ip"],
            "hostname"      => $rs["hostname"],
            "port"          => $rs["port"],
            "status"        => $rs["status"]
            ];
        return json($data);
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
        $token = Request::get("token");
        
        if ($this->GetUserName($token) != $username){
            $data = [
                "status"            => -5,
                "message"           => "登录TOKEN校验失败！",
                ];
            return json($data);
        }
        
        $rs = Db::table("realname")->where("username", $username)->find();
        if ($rs == null){
            $data = [
                "status"            => -8,
                "message"           => "你还没有进行实名认证，新面板实名认证正在开发中，请前往旧面板进行实名认证后重试",
                ];
            return json($data);
        }
        
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
        
        //判断隧道名是否被使用
        $rs_name = Db::table("proxies")->where("proxy_name", $name)->where("node", $id)->find();
        if($rs_name != null){
            $data = [
                "status"                => -6,
                "message"               => "隧道名被占用"
                ];
            return json($data);
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
        
        if ($rp >= 0 && $rp <= 10000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp >= 30000 && $rp <= 31000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp >= 25565 && $rp <= 25656){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($type == "http" || $type == "https"){
            if($url == null){
                $data = [
                    "status"                => -2,
                    "message"               => "请填写url参数"
                    ];
                return json($data);
            }
        }
        if ($type == "http" || $type == "https"){
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
                "domain"                => '["' . $url . '"]',
                "locations"             => "",
                "host_header_rewrite"   => "",
                "sk"                    => "",
                "lastupdate"            => time(),
                "node"                  => $id,
                "status"                => 0
                ];
            Db::table("proxies")->insert($insert_data);
            $data = [
                "status"             => true,
                "message"            => "添加成功"
                ];
            return json($data);
        } else {
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
                "domain"                => "",
                "locations"             => "",
                "host_header_rewrite"   => "",
                "sk"                    => "",
                "lastupdate"            => time(),
                "node"                  => $id,
                "status"                => 0
                ];
            Db::table("proxies")->insert($insert_data);
            $data = [
                "status"             => true,
                "message"            => "添加成功"
                ];
            return json($data);
        }
    }
    public function update(){
        // 和insert一样的参数
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
        $token = Request::get("token");
        $proxyid = Request::get("proxyid");
        // 校验登录token
        if ($this->GetUserName($token) != $username){
            $data = [
                "status"            => -5,
                "message"           => "登录TOKEN校验失败！",
                ];
            return json($data);
        }
        
        // 校验信息完整度，不校验域名，在后面校验
        if ($username == null || $name == null || $key == null || $ip == null || $type == null || $lp == null || $rp == null || $ue == null || $uz == null || $id == null || $proxyid == null){
            $data = [
                "status"                => -2,
                "message"               => "信息不完整"
                ];
            return json($data);
        }
        
        // 隧道格式翻译部分
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
            // 找不到对应的格式，抛出错误
            $data = [
                "status"             => -1,
                "message"            => "类型错误"
                ];
            return json($data);
        }
        // 是否压缩，是否加密翻译部分，2.0以后默认不加密不压缩
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
        
        // 判断隧道是否存在（理论上不会不存在，但写出bug了就不知道了）
        $rs_name = Db::table("proxies")->where("id", $proxyid)->find();
        if($rs_name == null ){
            $data = [
                "status"                => -6,
                "message"               => "隧道不存在！"
                ];
            return json($data);
        }
        
        // 如果节点ID更改就检测有无名称重复
        if ($rs_name["node"] != $id){
            // 查验隧道名在新的节点中存不存在
            $rs = Db::table("proxies")->where("proxy_name", $name)->where("node", $id)->find();
            if ($rs){
                $data = [
                    "status"                => -7,
                    "message"               => "隧道名已被占用"
                    ];
                return json($data);
            }
        }
        
        //判断端口是否被占用
        $rs_port = Db::table("proxies")->where("remote_port", $rp)->where("node",$id)->find();
        // 如果远程端口存在记录，且用户名不一致，则重复，抛出错误，若用户名一致则代表未修改远程端口，无须理会
        if ($rs_port != null && $rs_port["username"] != $username) {
            $data = [
                "status"            => -3,
                "message"           => "端口已被占用"
                ];
            return json($data);
        }
        
        // 系统保留端口校验
        if ($rp >= 0 && $rp <= 10000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp >= 30000 && $rp <= 31000){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        if ($rp >= 25565 && $rp <= 25656){
            $data = [
                "status"            => -4,
                "message"           => "系统保留端口"
                ];
            return json($data);
        }
        
        // http或https协议校验域名是否为空
        if ($type == "http" || $type == "https"){
            if($url == null){
                $data = [
                    "status"                => -2,
                    "message"               => "请填写url参数"
                    ];
                return json($data);
            }
        }
        if ($type == "http" || $type == "https"){
            $update_data = [
                "proxy_name"            => $name,
                "proxy_type"            => $type,
                "local_ip"              => $ip,
                "local_port"            => $lp,
                "remote_port"           => $rp,
                "use_encryption"        => $ue,
                "use_compression"       => $uz,
                "domain"                => '["' . $url . '"]',
                "locations"             => "",
                "host_header_rewrite"   => "",
                "sk"                    => "",
                "lastupdate"            => time(),
                "status"                => 0,
                "node"                  => $id
                ];
            // $rs_name["id"] 用于操作的隧道ID
            Db::table("proxies")->where("id", $rs_name["id"])->update($update_data);
            $data = [
                "status"             => true,
                "message"            => "修改成功"
                ];
            return json($data);
        } else {
            $update_data = [
                "proxy_name"            => $name,
                "proxy_type"            => $type,
                "local_ip"              => $ip,
                "local_port"            => $lp,
                "remote_port"           => $rp,
                "use_encryption"        => $ue,
                "use_compression"       => $uz,
                "domain"                => "",
                "locations"             => "",
                "host_header_rewrite"   => "",
                "sk"                    => "",
                "lastupdate"            => time(),
                "status"                => 0,
                "node"                  => $id
                ];
            // $rs_name["id"] 用于操作的隧道ID
            Db::table("proxies")->where("id", $rs_name["id"])->update($update_data);
            $data = [
                "status"             => true,
                "message"            => "修改成功"
                ];
            return json($data);
        }
    }
    public function remove(){
        $id = Request::get("proxyid");
        $username = Request::get("username");
        $token = Request::get("token");
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
            "status"             => true,
            "message"            => "删除成功"
            ];
        return json($data);
    }
    
    public function GetOnlineNumber(){
        $rs = Db::table("status")->where("id", 1)->find();
        $data = [
            "onlineuser"        => $rs["onlineuser"],
            "onlineproxy"       => $rs["onlineproxy"],
            "updatetime"        => $rs["time"]
            ];
            
        return json($data);
    }
    
    public function GetOnlineStatus(){
        $http = new \app\common\Http;
        $user = Request::get("user");
        $proxy_name = Request::get("proxyname");
        $node = Request::get("node");
        $type = Request::get("type");
        
        if ($user == null || $proxy_name == null || $node == null || $type == null){
            $data = [
                "status"        => false,
                "message"       => "参数不完整！"
                ];
            return json($data);
        }
        
        $rs = Db::table("nodes")->where("id", $node)->find();
        if ($rs == null){
            $data = [
                "status"            => false,
                "message"           => "节点不存在!"
                ];
            return json($data);
        }
        
        $domain = $rs["ip"];
        
        $rs = $http->get("http://admin:Locyan666@" . $domain . ":8233/api/proxy/" . $type . "/" . $user . "." . $proxy_name);
        if ($rs == "no proxy info found"){
            $data = [
                "status"            => false,
                "message"           => "隧道不存在!"
                ];
            return json($data);
        }
        
        $rs = json_decode($rs, true);
        
        if ($rs == null){
            $data = [
                "status"            => false,
                "message"           => "查询失败，服务器连接超时，请检查该服务器是否在维护"
                ];
            return json($data);
        }
        
        if ($rs["status"] == null || $rs["status"] == ""){
            $data = [
                "status"            => true,
                "message"           => "查询成功",
                "online"            => "offline"
                ];
            return json($data);
        }
        
        $data = [
            "status"            => true,
            "message"           => "查询成功",
            "online"            => $rs["status"]
            ];
        return json($data);
    }
    
    public function GetConfigFile(){
        // login: logintoken
        $username = Request::get("username");
        $token = Request::get("token");
        $node = Request::get("node");
        
        if ($this->GetUserName($token) != $username){
            $data = [
                "status"        => false,
                "message"       => "用户登录失效！"
            ];
            return json($data);
        }
        
        if ($username == "" || $token == "" || $node == "" || $username == null || $token == null || $node == null){
            $data = [
                "status"        => false,
                "message"       => "参数不全"
            ];
            return json($data);
        }
        
        $proxies = Db::table("proxies")->where("username", $username)->where("node", $node)->select()->toArray();
        if ($proxies == null) {
            $data = [
                "status"        => false,
                "message"       => "没有任何隧道在此节点！"
            ];
            return json($data);
        }
        $node = Db::table("nodes")->where("id", $node)->find();
        if ($node == null){
            $data = [
                "status"        => false,
                "message"       => "节点不存在！"
            ];
            return json($data);
        }
        
        $token_info = Db::table("tokens")->where("username", $username)->find();
        $node_info = "[common]
server_addr = {$node['hostname']}
server_port = {$node['port']}
tcp_mux = true
protocol = tcp
user = {$token_info['token']}
token = LoCyanToken
dns_server = 114.114.114.114";
        $proxies_info ="";
        foreach ($proxies as $p){
            
            if ($p["proxy_type"] == "http" || $p["proxy_type"] == "https"){
                $domain = str_replace('["', "", str_replace('"]', "", $p['domain']));
                $proxies_info_tmp = "[{$p['proxy_name']}]
privilege_mode = true
type = {$p['proxy_type']}
local_ip = {$p['local_ip']}
local_port = {$p['local_port']}
remote_port = {$p['remote_port']}
custom_domains = {$domain}
proxy_protocol_version = v2";
                $proxies_info = $proxies_info . "\n\n" . $proxies_info_tmp;
            } else {
                $proxies_info_tmp = "[{$p['proxy_name']}]
privilege_mode = true
type = {$p['proxy_type']}
local_ip = {$p['local_ip']}
local_port = {$p['local_port']}
remote_port = {$p['remote_port']}
proxy_protocol_version = v2";
                $proxies_info = $proxies_info . "\n\n" . $proxies_info_tmp;
            }
        }
        $config = $node_info . $proxies_info;
        $data = [
            "status"        => true,
            "message"       => "获取成功",
            "config"        => $config
        ];
        return json($data);
    }
    
    public function GetUserName($token){
        $rs = Db::table("logintoken")->where("token",$token)->find();
        
        if($rs == null){
            return "该TOKEN不存在！";
        } else {
            return $rs["username"];
        }
    }
    
    public function CloseProxyRemote(){
        $http = new \app\common\Http;
        $username = Request::get("username");
        $token = Request::get("token");
        $id = Request::get("proxyid");
        
        $rs = Db::table("logintoken")->where("token", $token)->find();
        if ($rs == null){
            $data = [
                "status"        => false,
                "message"       => "登录TOKEN校验失败"
            ];
            return json($data);
        }
        
        if ($rs["username"] != $username){
            $data = [
                "status"        => false,
                "message"       => "登录TOKEN校验失败"
            ];
            return json($data);
        }
        
        $rs = Db::table("proxies")->where("id", $id)->find();
        $user_data = Db::table("tokens")->where("username", $username)->find();
<<<<<<< HEAD
        $rs = $http->get("http://admin:YourPassWord@{$rs['ip']}:8233/api/client/close/{$user_data['token']}");
=======
        $rs = $http->get("http://admin:YourFrpPass@{$rs['ip']}:8233/api/client/close/{$user_data['token']}");
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        $rs = json_decode($rs, true);
        
        if ($rs == null){
            $data = [
                "status"        => false,
                "message"       => "无法访问目标服务器"
            ];
            return json($data);
        }
        
        if ($rs["status"] == 200){
            $data = [
                "status"        => true,
                "message"       => "下线成功"
            ];
            return json($data);
        } else {
            $data = [
                "status"        => false,
                "message"       => $rs["message"]
            ];
            return json($data);
        }
    }
}
?>