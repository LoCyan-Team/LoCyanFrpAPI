<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class Encode
{
    public function index(){
        return "你要，冲Q币吗？";
    }
    
    // RSA
    public function genkeys(){
        $keys = new \app\common\Utils;
        $rs = $keys->genkeys();
        $rs = explode("|", $rs);
        $pub = $rs[0];
        $pri = $rs[1];
        return $pub . "\n" . $pri;
    }
    
    public function RSAEncode()
    {
        $data = Request::get("data");
<<<<<<< HEAD
        $pub_key = "";
=======
        $pub_key = "你的公钥";
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
    $key = openssl_pkey_get_public($pub_key);
    if (!$key){
        return "公钥不可用";
    }
    openssl_public_encrypt($data, $encrypted, $key);
    return base64_encode($encrypted);
    }
    
    public function RSADecode()
    {
        $data = Request::get("data");
        $data = str_replace(' ','+', $data);
<<<<<<< HEAD
        $pri_key = "";
=======
        $pri_key = "你的私钥";
>>>>>>> 8ca5007b9f4f577222552c4c5fab57ad06dc886f
        $key = openssl_pkey_get_private($pri_key);
        if (!$key) {
            return '私钥不可用';
        }
        
        $return_de = openssl_private_decrypt(base64_decode($data), $decrypted, $key);
        if (!$return_de) {
            return('解密失败,请检查RSA秘钥');
        }
        return $decrypted;
    }
    // PHP HASH
    public function encode(){
        try {
            $method = Request::get("method");
        } catch (\Throwable $th) {
            echo "Error";
        }
        if ($method=="encode"){
            $password = Request::get("password");
            echo password_hash($password, PASSWORD_BCRYPT);
        } elseif ($method=="decode") {
                $password = Request::get("password");
                $str = Request::get("str");
                //str是加密后的密文，password是原文
                if (password_verify($password,$str)) { 
                Utils::sendsuccessful("verify successfully");
                } else { Utils::sendwrong("verify failed");
            }
        } else {
            echo 'error';
        }
    }
}