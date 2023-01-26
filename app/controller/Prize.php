<?php
namespace app\controller;

use app\BaseController;
use think\facade\Db;
use think\facade\Request;

class Prize extends BaseController
{
    public function index(){
        return "欢迎访问LCF抽奖API页面，该页面直接访问无效！";
    }
    
    public function JoinPrize(){
        $id = Request::get("id");
        $username = Request::get("username");
        $rs = Db::table("realname")->where("username", $username)->find();
        if ($rs =- null){
            $data = [
                "status"        => false,
                "message"       => "请先完成实名认证后在参与！"
            ];
            return json($data);
        }
        $rs = Db::table("prize")->where("id", $id)->find();
        if ($rs == null){
            $data = [
                "status"        => false,
                "message"       => "参与失败，该活动不存在！"
            ];
            return json($data);
        }
        
        // 能够找到，说明已经参与了
        if (strstr($rs["username"], $username) != ""){
            $data = [
                "status"        => false,
                "message"       => "操作失败，您已经参与该活动了！"
            ];
            return json($data);
        }
        
        if ($rs["username"] == "" || $rs["username"] == null) {
            $data = [
                "username"        => $username
            ];
        } else {
            $data = [
                "username"        => $rs["username"] . "|" . $username
                ];
        }
        Db::table("prize")->where("id", $id)->update($data);
        $data = [
            "status"        => true,
            "message"       => "参与成功！"
        ];
        return json($data);
    }
    
    public function GetPrizes(){
        $rs = Db::table("prize")->select()->toArray();
        return json($rs);
    }
}