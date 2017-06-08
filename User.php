<?php
/**
 * Created by PhpStorm.
 * User: sounder
 * Date: 2017/6/7
 * Time: 18:04
 */
class User{
    var $APP_ID = "wxe1fdb35dcd215a92";
    var $SECRET = "381d465f47ce918b6ed7d28c96a250ac";
    var $Access_Token;

    public function getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->APP_ID."&secret=".$this->SECRET;
        echo $url;
        echo "<br/>";
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $json = json_decode($data,true);
        curl_close($curl);
        $this->Access_Token = $json['access_token'];
        echo "<br/>Token-->".$this->Access_Token."<br/>";
        return $json['access_token'];
    }
    public function getUserList(){
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$this->Access_Token&next_openid=";
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        $json = json_decode($data,true);
        curl_close($curl);
        var_dump($json);
    }
    public function sendredpac(){
        $dom = new DOMDocument('1.0','utf-8');
        $no1 = $dom->createElement("root");
        $rootText = $dom->createTextNode("root");
        $no1->appendChild($rootText);
        $dom->appendChild($no1);
        header("Content-Type:text/xml");
        echo $dom->saveXML();
    }
}
$user = new User();
//$user->getAccessToken();
//$user->getUserList();
$user->sendredpac();