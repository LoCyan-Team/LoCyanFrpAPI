<?php
namespace app\controller;

use app\BaseController;

class Pay extends BaseController
{
    public function index()
    {
        if(!isset($_GET["money"]) || !isset($_GET["name"]) || !isset($_GET["type"]) || !isset($_GET["username"])){
            return '参数未定义完全（money，name，type, username）';
        }
        $money = $_GET["money"];
        $name = $_GET["name"];
        $type = $_GET["type"];
        $username = $_GET["username"];
        //生成订单号
        $order_id_main = date('YmdHis') . rand(10000000,99999999);
        $order_id_len = strlen($order_id_main);
        $order_id_sum = 0;
        for($i=0; $i<$order_id_len; $i++){
          $order_id_sum += (int)(substr($order_id_main,$i,1));
        }
        $osn = $order_id_main . str_pad((100 - $order_id_sum % 100) % 100,2,'0',STR_PAD_LEFT);
        
        $tradeid = $osn;
        $sign = $this->sign($money,$name,$type,$tradeid);
        //写入数据库
        
        $not =  htmlspecialchars("&not");
        return 'http://pay.example.cn/submit.php?money=' . $money . '&name=' . $name . $not . 'ify_url=127.0.0.1&out_trade_no=' . $tradeid . '&pid=1044&return_url=http://127.0.0.1/PayReturn&sitename=test&type=' . $type . '&sign=' . $sign . '&sign_type=MD5';
    }

    public function sign($money ,$name ,$type ,$tradeid)
    {
        $str = 'money=' . $money . '&name=' . $name . '&notify_url=127.0.0.1&out_trade_no=' . $tradeid . '&pid=1044&return_url=http://127.0.0.1/PayReturn&sitename=test&type=' . $type . '';
        return md5($str);
    }
}
