<?php
$code = $_GET['code'];  
$state = $_GET['state'];  

/*根据code获取用户openid* 
$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx72e1ef917e46fc68&secret=eb209bfaa8effa31f4508cea9788f5d0&code=".$code."&grant_type=authorization_code";  
$abs = file_get_contents($url);  
$obj=json_decode($abs);  
$access_token = $obj->access_token;  
$openid = $obj->openid;  
$abs_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN"; 
$abs_url_data = file_get_contents($abs_url);  
$obj_data=json_decode($abs_url_data);  
echo $OpenId = $obj_data->openid;  
echo $NickName = $obj_data->nickname;  */
/*$appid = 'wxf5d107dd7e58f2dd';
$appsecret = '1c7bfcdc4e72b8d4a22497470dcca79f';
$code = $_GET["code"]; 
$get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$appsecret.'&code='.$code.'&grant_type=authorization_code';
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$get_token_url); 
curl_setopt($ch,CURLOPT_HEADER,0); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 ); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); 
$res = curl_exec($ch); 
curl_close($ch); 
$json_obj = json_decode($res,true); 
//根据openid和access_token查询用户信息 
$access_token = $json_obj['access_token']; 
$openid = $json_obj['openid'];
echo $openid; */
$appid = 'wxf5d107dd7e58f2dd';
$appsecret = '1c7bfcdc4e72b8d4a22497470dcca79f';
$code = $_GET['code'];//获取code
$weixin =  file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code");//通过code换取网页授权access_token
$jsondecode = json_decode($weixin); //对JSON格式的字符串进行编码
$array = get_object_vars($jsondecode);//转换成数组
$openid = $array['openid'];//输出openid
var_dump($openid);
?>
