<?php
namespace app\controller;

use think\facade\Request;
use think\facade\Db;

class Status {
    public function index(){
        $rs = Db::table("todaytraffic")->select()->toArray();
        $a = [];
        foreach ($rs as $r){
            $a[$r["user"]] = $r["traffic"];
        }
        return json($a);
    }

    public function Check(){
        $username = Request::get("username");
        $rs = Db::table("todaytraffic")->select()->toArray();
        $a = [];
        foreach ($rs as $r){
            $a[$r["user"]] = $r["traffic"];
        }
        $traffic = $a[$username];
        $b = [
            "username"          => $username,
            "traffic"           => $traffic
        ];
        return json($b);
    }
}