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
        $password_temp = Db::table("users")->where("username",$name)->find();
        if($password_temp == null){
            $data = [
                "status"            => -1,
                "message"           => "登录失败，账号或密码错误"
                ];
            return json($data);
        }
        $a = password_verify($pwd ,$password_temp["password"]);
        if($a == 1){
            
            $rs_status = Db::table("users")->where("username",$name)->find();
            if ($rs_status["status"] != 0){
                $status_return = [
                    "status"        => -2,
                    "message"       => "账号已被禁用"
                    ];
                return json($status_return);
            }
            
            if (Db::table("logintoken")->where("username",$name)->find() !== null){
                Db::table("logintoken")->where("username",$name)->delete();
            }
            $token = md5(sha1($name . strval(time())));
            $insert_data = [
                "id"                => null,
                "username"          => $name,
                "token"             => $token
                ];
            Db::table("logintoken")->insert($insert_data);
            $data = [
                "status"            => 0,
                "message"           => "登录成功",
                "token"             => $token
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
}
?>
