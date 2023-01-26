<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class Server
{
    public function index(){
        return '此为服务器状态查询API页面！';
    }
    
    public function GetTrafficList(){
        $http = new \app\common\Http;
        $rs = $http->nezhaget("https://status.locyan.cn/api/v1/server/list?tag=中转节点");
        
        //$utils = new \app\common\Utils;
        
        $rs = json_decode($rs, true);
        $traffic_top = Array();
        $front_traffic_out = 0;
        foreach ($rs["result"] as $r){
            $rs1 = $http->nezhaget("https://status.locyan.cn/api/v1/server/details?id=" . $r["id"]);
            $rs1 = json_decode($rs1, true);
            $outt = $rs1["result"][0]["status"]["NetOutTransfer"];
            $intt = $rs1["result"][0]["status"]["NetInTransfer"];
            
            $data = [
                "name"              => $r["name"],
                "out"               => $outt,
                "in"                => $intt
                ];
            array_push($traffic_top, $data);
            // if ($intt > $front_traffic_out) {
            //     array_unshift($traffic_top, $data);
            //     $front_traffic_out = $intt;
            // } else {
            //     array_push($traffic_top, $data);
            //     $front_traffic_out = $intt;
            // }
        }
        array_multisort(array_column($traffic_top,'in'),SORT_DESC,$traffic_top);
        return json($traffic_top);
    }
    
    public function GanshityUpdateIp(){
        $ip = Request::get("ip");
        $key = Request::get("key");
        
<<<<<<< HEAD
        if ($key != "ganshity"){
=======
        if ($key != "xxxxxxxxxx"){
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
            $data = [
                "status"        => false,
                "message"       => "KEY 校验失败"
            ];
            return json($data);
        }
        
        $update_data = [
            "ip"        => $ip
        ];
        
        Db::table("nodes")->where("id", "32")->update($update_data);
        $data = [
            "status"        => true,
            "message"       => "更新成功"
        ];
        return json($data);
    }
    
}