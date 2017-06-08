<?php

/**
 * Created by PhpStorm.
 * User: sounder
 * Date: 2017/6/8
 * Time: 9:51
 */
class pay
{
    var $nonce_str = "nonce_str";
    var $sign = "sign";
    var $mch_billno = "mch_billno";
    var $mch_id = "mch_id";
    var $wxappid = "wxappid";
    var $send_name = "send_name";
    var $re_openid = "re_openid";
    var $total_amount = "total_amount";
    var $total_num = "total_num";
    var $wishing = "wishing";
    var $client_ip = "client_ip";
    var $act_name = "act_name";
    var $remark = "remark";
    var $scene_id = "scene_id";
    var $risk_info = "risk_info";
    var $consume_mch_id = "consume_mch_id";

    function __construct()
    {
        $this->nonce_str = $this->create_nonce_str(32);
        $this->mch_billno = "10000098201411111234567890";
        $this->mch_id = "10000098";
        $this->wxappid = "wxe1fdb35dcd215a92";
        $this->send_name = "Sounder";
        $this->re_openid = "oxTWIuGaIt6gTKsQRLau2M0yL16E";
        $this->total_amount = "1";
        $this->total_num = "1";
        $this->wishing = "Nice to meet you!";
        $this->client_ip="127.0.0.1";
        $this->act_name = "喜迎高考";
        $this->remark = "";
        $this->scene_id = "PRODUCT_4";
        $this->sign = $this->get_sign();
    }
    public function getXML(){
        $json = json_encode($this);
//        echo $json."<br/>";
        $array = json_decode($json);
        $dom = new DOMDocument("1.0","utf-8");
        $root = $dom->createElement("xml");
        $dom->appendChild($root);
        foreach ($array as $key=>$value){
//            echo $key."=".$value."<br/>";
            $ele = $dom->createElement($key);
            $text = $dom->createTextNode($value);
            $ele->appendChild($text);
            $root->appendChild($ele);
        }
//        header("Content-Type:text/xml");
        return $dom->saveXML();
    }

    function create_nonce_str($length){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for($i=0;$i<$length;$i++){
            $str .= substr($chars,mt_rand(0,strlen($chars)-1),1);
        }
        return $str;
    }
    function get_sign(){
        $buff = "";
        $array = json_decode(json_encode($this),true);
        ksort($array);
        foreach ($array as $key=>$value){
            if($value != null && $value != ""){
                $buff .="&".$key."=".$value;
            }
        }
        $buff = substr($buff,1,strlen($buff)-2);
//        $buff = urlencode($buff);
        $key = "192006250b4c09247ec02edce69f6a2d";
        $sign = $buff."&key=".$key;
        $sign = strtoupper(md5($sign));
        return $sign;
    }
    function sendredpack($url,$data=null){
        $cur = curl_init();
        curl_setopt($cur,CURLOPT_URL,$url);
        curl_setopt($cur, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($cur, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($cur, CURLOPT_RETURNTRANSFER, 1);
        if($data != null){
            curl_setopt($cur,CURLOPT_POST,1);
            curl_setopt($cur,CURLOPT_POSTFIELDS,$data);
        }
        $res = curl_exec($cur);
        curl_close($cur);
        header("Content-Type:text/xml");
        echo $res;
//        return $res;
    }
}
$pay = new pay();
$data = $pay->getXML();
$pay->sendredpack("https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack",$data);
//var_dump(json_decode($res,true));