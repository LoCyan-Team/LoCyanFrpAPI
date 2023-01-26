<?php
namespace app\controller;

use app\BaseController;

class Pay extends BaseController
{
    // 便捷对接码支付
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
        return 'https://pay.locyan.cn/submit.php?money=' . $money . '&name=' . $name . $not . 'ify_url=https://api.locyanfrp.cn/Pay/notify&out_trade_no=' . $tradeid . '&pid=1000&return_url=https://api.locyanfrp.cn/Pay/PayReturn&sitename=test&type=' . $type . '&sign=' . $sign . '&sign_type=MD5';
    }

    public function sign($money ,$name ,$type ,$tradeid)
    {
        //echo("money={$money}&name={$name}{$not}ify_url=127.0.0.1&out_trade_no={$tradeid}&pid=1001&return_url=127.0.0.1&sitename=test&type={$type}CFI4720P");
<<<<<<< HEAD
        $str = 'money=' . $money . '&name=' . $name . '&notify_url=https://api.locyanfrp.cn/Pay/notify&out_trade_no=' . $tradeid . '&pid=1000&return_url=https://api.locyanfrp.cn/Pay/PayReturn&sitename=test&type=' . $type . '';
=======
        $str = 'money=' . $money . '&name=' . $name . '&notify_url=https://api.locyanfrp.cn/Pay/notify&out_trade_no=' . $tradeid . '&pid=1000&return_url=https://api.locyanfrp.cn/Pay/PayReturn&sitename=test&type=' . $type . 'xxxxxxxxxxxxxxxxxxxxx';
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        return md5($str);
    }
    
    public function notify(){
        return 'success';
    }
    
    public function PayReturn(){
        if (!isset($_GET["trade_no"])) {
            exit('没有任何数据！');
        }
        $status = $_GET["trade_status"];
        $tradeid = $_GET["trade_no"];
        $tradeid_inside = $_GET["out_trade_no"];
        $money = $_GET["money"];
        $name = $_GET["name"];
        
        echo '<h2>购买成功，感谢您的购买！</h2>';
        echo '<p>交易金额：' . $money . '</p>';
        echo '<p>支付系统内部订单号：' . $tradeid_inside . ' -> 请妥善保管！</p>';
        echo '<p>微信/支付宝 内部订单号：' . $tradeid . '</p>';
        echo '<p>商品名称：' . $name . '</p>';
        echo("<p></p>");
        echo("<p>请您在游戏内输入指令：</p>");
        echo("<p>/lhp check-pay " . $tradeid_inside . "</p>");
        echo("<p>获取您交易的物品</p>");
        
        if($status == "TRADE_SUCCESS"){
            echo '<p>交易状态：成功</p>';
        } else {
            echo '<p>交易状态：失败</p>';
        }
    }
}
