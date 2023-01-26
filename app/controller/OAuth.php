<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class OAuth
{
    public function http($url, $post = '', $cookie = '', $headers = '', $returnHeader = 0)
    {
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
    	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
    	curl_setopt($curl, CURLOPT_REFERER, $url);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    	if($post) {
    		curl_setopt($curl, CURLOPT_POST, 1);
    		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    	}
    	if($cookie) {
    		curl_setopt($curl, CURLOPT_COOKIE, $cookie);
    	}
    	if($headers) {
    		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    	}
    	curl_setopt($curl, CURLOPT_HEADER, 0);
    	curl_setopt($curl, CURLOPT_TIMEOUT, 60);
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	$data = curl_exec($curl);
    	$httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    	if(curl_errno($curl)) {
    		$httpCode = curl_error($curl);
    	}
    	curl_close($curl);
    	return [
    		'status' => $httpCode,
    		'body'   => $data
    	];
    }

    public function connect(){
<<<<<<< HEAD
        $rs = $this->http("");
=======
        $rs = $this->http("https://u.813078.cn/connect.php?act=login&appid=1060&appkey=&type=qq&redirect_uri=https://api.freenat.gq/OAuth/login_return");
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        $url = json_decode($rs["body"], true);
        $login_url = $url["url"];
        
        echo("<script>location='" . $login_url . "'</script>");
    }
    public function login_return(){
        $code = Request::get("code");
<<<<<<< HEAD
        $rs = $this->http("");
=======
        $rs = $this->http("https://u.813078.cn/connect.php?act=callback&appid=1060&appkey=&type=qq&code=" . $code);
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        
        $rs = json_decode($rs["body"], true);
        
        if (!isset($rs["nickname"])){
            return 'TOKEN过期，请重新登录';
        }
        
        $username = $rs["nickname"];
        $info = Db::table("users")->where("username",$username)->find();
        if ($info == null){
            return '用户不存在，请确保QQ与平台用户名一致';
        }
        $email = $info["email"];
<<<<<<< HEAD
        echo ("");
    }
    public function DingTalkLogin(){
        $data = urlencode("https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=&response_type=code&scope=snsapi_login&state=true&redirect_uri=");
=======
        echo ("<script>location='https://www.freenat.gq/?action=qqauth&username=" . $username . "&email=" . $email . "&code=" . $code . "'</script>");
    }
    public function DingTalkLogin(){
        $data = urlencode("https://oapi.dingtalk.com/connect/oauth2/sns_authorize?appid=&response_type=code&scope=snsapi_login&state=true&redirect_uri=https://api.freenat.gq/OAuth/DingTalkReturn");
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        return ('
        <script src="https://g.alicdn.com/dingding/dinglogin/0.0.5/ddLogin.js"></script>
        
        <center><div id="login_container"></div></center>
        
<script>
        var obj = DDLogin({
             id:"login_container",
             goto: "' . $data . '",
             style: "border:none;background-color:#FFFFFF;",
             width : "365",
             height: "400"
         });
         
         var hanndleMessage = function (event) {
         var origin = event.origin;
         console.log("origin", event.origin);
         if( origin == "https://login.dingtalk.com" ) { //判断是否来自ddLogin扫码事件。
        
         var loginTmpCode = event.data; //拿到loginTmpCode后就可以在这里构造跳转链接进行跳转了
        
         console.log("loginTmpCode", loginTmpCode);
<<<<<<< HEAD
         window.location.href="https://api.xxx.cn/OAuth/DingTalkReturn?code=" + loginTmpCode
=======
         window.location.href="https://api.freenat.gq/OAuth/DingTalkReturn?code=" + loginTmpCode
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
    } }; if (typeof window.addEventListener != "undefined") { window.addEventListener("message", hanndleMessage, false); } else if (typeof window.attachEvent != "undefined") { window.attachEvent("onmessage", hanndleMessage); }
</script>
        ');
    }
    public function DingTalkReturn(){
        $code = Request::get("code");
        $http = new \app\common\Http;
<<<<<<< HEAD
        $rs = $http -> get("https://oapi.dingtalk.com/gettoken?appkey=&appsecret=");
=======
        $rs = $http -> get("https://oapi.dingtalk.com/gettoken?appkey=&appsecret=");
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        $rs = json_decode($rs, true);
        
        $tmp_auth_code = [
            "tmp_auth_code"         => $code
            ];
        
        $rs = $http -> post("https://oapi.dingtalk.com/sns/get_persistent_code?access_token=" . $rs["access_token"], $tmp_auth_code);
        return $rs;
    }
    
}