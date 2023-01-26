<?php
namespace app\controller;

use think\facade\Request;
use think\facade\Db;

class App {
    public function index(){
        $data = [
            "status"                => 0,
            "contents"              => "为了保障我们的资金充足，欢迎大家来乐青云：https://cloud.locyan.cn 选购服务器，业界低价！     原香港九龙服务器因为服务商服务态度问题已经更换为遛弯一家的香港百兆，原九龙用户可以再次获取配置文件使用！"
            ];
        return json($data);
    }
    public function update(){
        if (Request::get("pyversion") == null) {
            $data = [
                "status"                => -1,
                "message"               => "信息不完整"
                ];
            return json($data);
        } else {
            //检查Core.exe版本 版本对的上就不需要更新，对不上就要更新
            if (Request::get("pyversion") == "V1.0.0.2 Fix11"){
                $data = [
                    "status"                => 0,
                    "needupdate"            => 1
                    ];
                return json($data);
            } else {
                $data = [
                    "status"                => 0,
                    "needupdate"            => 0,
                    "important"             => 1,
                    "contents"              => "乐青映射第一个C++版本软件",
                    "version"               => "V1.0.0.2 Fix11", //FRP.exe版本号
                    "file_name"             => "LoCyanFrpApplication For PyV1.0.0.2-v3 CDN.zip",
                    "download_link_cpp"         => "https://daiyangcheng-1304984587.cos.accelerate.myqcloud.com/LoCyanFrpApplication%20For%20PyV1.0.0.1.zip",
                    "download_link_py"      => "https://daiyangcheng-1304984587.cos.accelerate.myqcloud.com/LoCyanFrpApplication%20For%20PyV1.0.0.2-v3%20CDN.zip"
                    ];
                return json($data);
            }
        }
    }
    public function SubmitComment(){
        $username = Request::get("username");
        $comment = Request::get("comment");
        if ($comment == null or $comment == ""){
            $data = [
                "status"        => false,
                "message"       => "评论不可为空！"
            ];
            return json($data);
        }
        $insert_data = [
            "id"        => null,
            "username"  => $username,
            "comment"   => $comment,
            "time"      => time()
        ];
        Db::table("newyear")->insert($insert_data);
        $data = [
            "status"        => true,
            "message"       => "评论成功！"
        ];
        return json($data);
    }
    public function GetComments(){
        $rs = Db::table("newyear")->select()->toArray();
        return json($rs);
    }
    public function GetSoftWares(){
        $data = [
            ["sname"     => "LoCyanFrpMSApp（推荐）", "description"     => "使用 C++ 编写，兼容性更佳，适合新手，但可能存在些许 Bug！", "download"        => "https://download.locyan.cn/LoCyanFrpMSApp/Lastet"],
            ["sname"     => "LoCyanPythonApp", "description"     => "更加稳定，但不再维护！", "download"       => "https://download.locyan.cn/d/LoCyanFRP-PY/LoCyanFrpV1.0.0.2-Fix12.zip"],
            ["sname"     => "LoCyanFrpPureApp", "description"     => "公版 Frp，适合懂电脑的人使用！小白不会用造成的后果请自行承担，若在群内反复询问将进行禁言或踢出等操作！下载需使用魔法！", "download"     => "https://github.com/LoCyan-Team/LoCyanFrpPureApp"]
        ];
        return json($data);
    }
}