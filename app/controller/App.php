<?php
namespace app\controller;

use think\facade\Request;

class App {
    public function index(){
        $data = [
            "status"                => 0,
            "contents"              => "暑假结束了，你的作业做完了吗[滑稽]"
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
            if (Request::get("pyversion") == "V1.0.0"){
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
                    "version"               => "V1.0.0", //FRP.exe版本号
                    "download_link_cpp"         => "https://daiyangcheng-1304984587.cos.accelerate.myqcloud.com/LoCyanFrpApplication%20For%20PyV1.0.0.1.zip",
                    "download_link_py"      => "https://daiyangcheng-1304984587.cos.accelerate.myqcloud.com/LoCyanFrpApplication%20For%20PyV1.0.0.2.zip"
                    ];
                return json($data);
            }
        }
    }
}