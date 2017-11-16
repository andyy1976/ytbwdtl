<?php
/**
 * 第三方账号登录
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');
class connectControl extends mobileHomeControl {
    public function __construct() {
        parent::__construct();
    }

    /**
     * 登录开关状态
     */
    public function get_stateOp() {
        $logic_connect_api = Logic('connect_api');
        $state_array = $logic_connect_api->getStateInfo();
        $map_id=$_GET['map_id'];  //地面商家
		if(!empty($map_id)){
			$store_model = Model('store'); //获取店铺中的会员
			$member = $store_model->getStoreInfoByID($map_id);
			$state_array['member_pid'] = $member['member_id'];
			$state_array['map_id']=$member['store_id'];
			}
        $key = $_GET['t'];
        if(trim($key) != '' && array_key_exists($key,$state_array)){
           if(!empty($map_id)){
            output_data($state_array);
           }else{
            output_data($state_array[$key]);
           }

        } else {
            output_data($state_array);
        }
    }
    /**
     * WAP页面微信登录回调
     */
    public function indexOp(){
        $logic_connect_api = Logic('connect_api');
        if(!empty($_GET['code'])) {
            $code = $_GET['code'];
            $client = 'wap';
            $user_info = $logic_connect_api->getWxUserInfo($code,'wap');
            if(!empty($user_info['unionid'])){
                $unionid = $user_info['unionid'];
                $model_member = Model('member');
                $member = $model_member->getMemberInfo(array('weixin_unionid'=> $unionid));
                $state_data = array();
                $token = 0;
                if(!empty($member)) {//会员信息存在时自动登录
                    $token = $logic_connect_api->getUserToken($member, $client);
                } else {//自动注册会员并登录
                    $info_data = $logic_connect_api->wxRegister($user_info, $client);
                    $token = $info_data['token'];
                    $member = $info_data['member'];
                    $state_data['password'] = $member['member_passwd'];
                }
                if($token) {
                    $state_data['key'] = $token;
                    $state_data['username'] = $member['member_name'];
                    $state_data['userid'] = $member['member_id'];
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                } else {
                    output_error('会员登录失败');
                }
            } else {
                output_error('微信登录失败');
            }
        } else {
            $_url = $logic_connect_api->getWxOAuth2Url();
            @header("location: ".$_url);
        }
    }
    /**
     * QQ互联获取应用唯一标识
     */
    public function get_qq_appidOp(){
        output_data(C('app_qq_akey'));
    }
    /**
     * 请求QQ互联授权
     */
    public function get_qq_oauth2Op(){
        $logic_connect_api = Logic('connect_api');
        $qq_url = $logic_connect_api->getQqOAuth2Url('api');
        @header("location: ".$qq_url);
    }
    /**
     * QQ互联获取回调信息
     */
    public function get_qq_infoOp(){
        $code = $_GET['code'];
        $token = $_GET['token'];
        $client = $_GET['client'];
        $logic_connect_api = Logic('connect_api');
        $user_info = $logic_connect_api->getQqUserInfo($code,$client,$token);
        if(!empty($user_info['openid'])){
            $qqopenid = $user_info['openid'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_qqopenid'=> $qqopenid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->qqRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                if($client == 'wap'){
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                }
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('QQ互联登录失败');
        }
    }
    /**
     * 新浪微博获取应用唯一标识
     */
    public function get_sina_appidOp(){
        output_data(C('app_sina_akey'));
    }
    /**
     * 请求新浪微博授权
     */
    public function get_sina_oauth2Op(){
        $logic_connect_api = Logic('connect_api');
        $sina_url = $logic_connect_api->getSinaOAuth2Url('api');
        @header("location: ".$sina_url);
    }
    /**
     * 新浪微博获取回调信息
     */
    public function get_sina_infoOp(){
        $code = $_GET['code'];
        $client = $_GET['client'];
        $sina_token['access_token'] = $_GET['accessToken'];
        $sina_token['uid'] = $_GET['userID'];
        $logic_connect_api = Logic('connect_api');
        $user_info = $logic_connect_api->getSinaUserInfo($code,$client,$sina_token);
        if(!empty($user_info['id'])){
            $sinaopenid = $user_info['id'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('member_sinaopenid'=> $sinaopenid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->sinaRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                if($client == 'wap'){
                    redirect(WAP_SITE_URL.'/tmpl/member/member.html?username='.$state_data['username'].'&key='.$state_data['key']);
                }
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('新浪微博登录失败');
        }
    }
    /**
     * 微信获取应用唯一标识
     */
    public function get_wx_appidOp(){
        output_data(C('app_weixin_appid'));
    }
    /**
     * 微信获取回调信息
     */
    public function get_wx_infoOp(){
        $code = $_GET['code'];
        $access_token = $_GET['access_token'];
        $openid = $_GET['openid'];
        $client = $_GET['client'];
        $logic_connect_api = Logic('connect_api');
        if(!empty($code)) {
            $user_info = $logic_connect_api->getWxUserInfo($code,'api');
        } else {
            $user_info = $logic_connect_api->getWxUserInfoUmeng($access_token, $openid);
        }
        if(!empty($user_info['unionid'])){
            $unionid = $user_info['unionid'];
            $model_member = Model('member');
            $member = $model_member->getMemberInfo(array('weixin_unionid'=> $unionid));
            $state_data = array();
            $token = 0;
            if(!empty($member)) {//会员信息存在时自动登录
                $token = $logic_connect_api->getUserToken($member, $client);
            } else {//自动注册会员并登录
                $info_data = $logic_connect_api->wxRegister($user_info, $client);
                $token = $info_data['token'];
                $member = $info_data['member'];
                $state_data['password'] = $member['member_passwd'];
            }
            if($token) {
                $state_data['key'] = $token;
                $state_data['username'] = $member['member_name'];
                $state_data['userid'] = $member['member_id'];
                output_data($state_data);
            } else {
                output_error('会员登录失败');
            }
        } else {
            output_error('微信登录失败');
        }
    }
    /**
     * 获取手机短信验证码
     */
    public function get_sms_captchaOp(){
        $sec_key = $_GET['sec_key'];
        $sec_val = $_GET['sec_val'];
        
        $phone = $_GET['phone'];
        $log_type = $_GET['type'];//短信类型:1为注册,2为登录,3为找回密码
        $state_data = array(
            'state' => false,
            'msg' => '验证码或手机号码不正确'
            );
        
        $result = Model('apiseccode')->checkApiSeccode($sec_key,$sec_val);
        if ($result && strlen($phone) == 11){
            $logic_connect_api = Logic('connect_api');
            $state_data = $logic_connect_api->sendCaptcha($phone, $log_type);
        }
        $this->connect_output_data($state_data);
    }
    //手机发送验证码
   public function get_passwordOp(){               
        $phone = $_POST['phone'];
        $log_type = 3;//短信类型:1为注册,2为登录,3为找回密码        
        if (strlen($phone) != 11){
            echo '1';exit;
        }       
        $state = true;      
        $model_member = Model('member');
        $common=Model('member_common');
        $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
        $common_= $common->where(array('member_id'=>$member['member_id']))->find();
        $time=time();
        // if($common_['send_acode_time']-$time<120){
        //     echo '6';exit;
        // }
        if(isset($_SESSION['codes']) && !empty($_SESSION['codes']) && $time -$_SESSION['codes'] < 120){

            echo '6';exit();
        }
        $captcha = rand(100000, 999999);
        $log_msg = '【'.C('site_name').'】您于'.date("Y-m-d");
        switch ($log_type) {                
            case '3':                    
            if(empty($member)) {//检查手机号是否已绑定会员
                $state = false;
                $msg = '当前手机号未注册，请检查号码是否正确。';
            }
            $log_msg .= '申请重置登录密码，验证码为：'.$captcha.'。';
                $log_array['member_id'] = $member['member_id'];
                $log_array['member_name'] = $member['member_name'];
                break;
            default:
                $state = false;
                $msg = '参数错误';
                break;
        }
        if($state == true){
            $sms = new Sms();
            $result = $sms->send($phone,$log_msg);
           
            if($result){
               $time = time();
               $_SESSION['codes'] = $time;
               $code_info=$common->where(array('member_id'=>$member['member_id']))->find();
               $acode=array('auth_code'=>$captcha,'send_acode_time'=>time(),'send_mb_time'=>time(),'send_acode_times'=>array('exp','send_acode_times+1'));
               
               if(empty($code_info)){
                    $acode['member_id']=$member['member_id'];
                    $insert=$common->insert($acode);
               }else{
                 $comupdate=$common->where(array('member_id'=>$member['member_id']))->update($acode);
               }             
              
               echo '2';                  
            } else {
                    $state = false;
                    echo '3';exit;
            }
        }
        else{
            echo '4';exit;
        }                  
    }
    //手机发送登入密码
    public function reset_passwordOp(){ 
    	$model_member = Model('member');              
        $pwd = $_POST['pwd'];
        $model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['keys'];				
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		       
        //$member_info =$member->getMemberInfoByID($mb_user_token_info['member_id']);
        $update=$model_member->where("member_id=$mb_user_token_info[member_id]")->update(array('member_passwd'=>md5($pwd))); 
        if($update){echo 1;}else{echo 0;}
    }
    //手机发送支付密码
    public function get_paywordOp(){               
        $phone = $_POST['phone'];        
        $log_type = 3;//短信类型:1为注册,2为登录,3为找回密码        
        if (strlen($phone) != 11){
            echo '1';exit;
        }       
        $state = true;      
        $model_member = Model('member');
        $member = $model_member->getMemberInfo(array('member_mobile'=> $phone));
        $captcha = rand(100000, 999999);
        $log_msg = '【'.C('site_name').'】您于'.date("Y-m-d");
        switch ($log_type) {                
            case '3':                                
            $log_msg .= '申请重置支付密码，新密码：'.$captcha.'。';
                $log_array['member_id'] = $member['member_id'];
                $log_array['member_name'] = $member['member_name'];
                break;
            default:
                $state = false;
                $msg = '参数错误';
                break;
        }
        if($state == true){
            $sms = new Sms();
            $result = $sms->send($phone,$log_msg);
            if($result){
               $update=$model_member->where("member_mobile=$phone")->update(array('member_paypwd'=>md5($captcha)));
               if($update){echo '2';}                  
            } else {
                    $state = false;
                    //$msg = '手机短信发送失败';
                    echo '3';exit;
            }
        }                  
    }
    //手机绑定
   public function mobile_blingOp(){               
        $phone = $_POST['mobile'];        
        //$log_type = 3;//短信类型:1为注册,2为登录,3为找回密码   
            
        $model_common = Model('member_common');
        $model_mb_user_token = Model('mb_user_token');                
        $key = $_POST['key'];               
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);                      
        $model_member = Model('member');
        $time = time();
        if(isset($_SESSION['codes']) && !empty($_SESSION['codes']) && $time -$_SESSION['codes'] < 120){
            echo '6';exit();
        } 
        $member = $model_member->getMemberInfo(array('member_id'=> $mb_user_token_info['member_id']));
        $other=$model_member->where(array('member_mobile'=>$phone))->select(); 
        if($other){echo '4';exit();}
        $captcha = rand(100000, 999999);
        $log_msg = '【'.C('site_name').'】您于'.date("Y-m-d").'验证码：'.$captcha.'。';                                                                 
        $sms = new Sms();
        $result = $sms->send($phone,$log_msg);
          $s = array(
                'auth_code'=>$captcha,
                'send_acode_time'=>TIMESTAMP,
                );
        if($result){
            $sa = $model_member->addMemberCommon($s);
            $update=$model_common->where(array('member_id'=>$member['member_id']))->update(array('auth_code'=>$captcha,'send_acode_time'=>time()));    
            if($update && $sa){
                echo '2';
                 $time = time();
               $_SESSION['codes'] = $time;
            }else{                  
                echo '3';
            }                 
        }else{                  
             echo '3';
        }
                          
    }
     //手机绑定第二步
    public function mobile_bling_twoOp(){               
        $phone = $_POST['phone'];        
        //$log_type = 3;//短信类型:1为注册,2为登录,3为找回密码                
        $model_common = Model('member_common');
        $model_mb_user_token = Model('mb_user_token');	              
		$key = $_POST['key'];
		$auth_code = $_POST['auth_code'];	
        $time = time();
        if(isset($_SESSION['codes']) && !empty($_SESSION['codes']) && $time -$_SESSION['codes'] < 120){

            echo '6';exit();
        } 			
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);		              
        $model_member = Model('member');        
        $member =$model_member->getMemberInfo(array('member_id'=> $mb_user_token_info['member_id']));                       
        $common_info=$model_common->where(array('member_id'=>$member['member_id']))->find();         
        if($common_info['auth_code']==$auth_code){

        	$update=$model_member->where(array('member_id'=>$member['member_id']))->update(array('member_mobile'=>$phone,'member_mobile_bind'=>1));

        	if($update){
                echo '2';
                $time = time();
               $_SESSION['codes'] = $time;
            }else{echo '4';}
        }else{
        	echo '3';
        }  
                       
    }
    /**
     * 验证手机验证码
     */
    public function check_sms_captchaOp(){
        $phone = $_GET['phone'];
        $captcha = $_GET['captcha'];
        $log_type = $_GET['type'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->checkSmsCaptcha($phone, $captcha, $log_type);
        $this->connect_output_data($state_data, 1);
    }
    /**
     * 手机注册
     */
    public function sms_registerOp(){
        $phone = $_POST['phone'];
        $captcha = $_POST['captcha'];
        $password = $_POST['password'];
        $client = $_POST['client'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->smsRegister($phone, $captcha, $password, $client);
        $this->connect_output_data($state_data);
    }
    /**
     * 手机找回密码
     */
    public function find_passwordOp(){
        $phone = $_POST['phone'];
        $captcha = $_POST['captcha'];
        $password = $_POST['password'];
        $client = $_POST['client'];
        $logic_connect_api = Logic('connect_api');
        $state_data = $logic_connect_api->smsPassword($phone, $captcha, $password, $client);
        $this->connect_output_data($state_data);
    }
    /**
     * 格式化输出数据
     */
    public function connect_output_data($state_data, $type = 0){
        if($state_data['state']){
            unset($state_data['state']);
            unset($state_data['msg']);
            if ($type == 1){
                $state_data = 1;
            }
            output_data($state_data);
        } else {
            output_error($state_data['msg']);
        }
    }
    //重置支付密码
    public function paypaswdOp(){
        $key = $_POST['key'];
        $model_mb_user_token = Model('mb_user_token');               
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        $member=Model('member');       
        $info=$member->where(array('member_id'=>$mb_user_token_info['member_id']))->find();
        if($info['member_paypwd']!=md5($_POST['member_oldpaypwd'])){echo '1';exit;}
        if($info['member_paypwd']==md5($_POST['member_newpaypwd'])){echo '2';exit;}
        $update=$member->where(array('member_id'=>$mb_user_token_info['member_id']))->update(array('member_paypwd'=>md5($_POST['member_newpaypwd'])));
        if($update){
            echo '3';
        }else{
            echo '4';
        }
    }
    //通过绑定手机号码，找回登陆密码
    public function findpasswordOp(){
          $model_member= Model('member');
          $common= Model('member_common');
             $key= $_POST['key'];
           $phone= $_POST['phone'];
            $code= $_POST['code'];
         $userpwd= $_POST['userpwd'];
            $type= $_POST['type'];
         if (strlen($phone) != 11){
            echo '5';exit;
         }                     
         $member_info = $model_member->getMemberInfo(array('member_mobile'=> $phone));

         $code_info=$common->where(array('member_id'=>$member_info['member_id'],'auth_code'=>$code))->find();
         
         if(empty($code_info)){echo '1';exit;}
         elseif($code_info['send_acode_time']-time()>180){echo '2';exit;}
         else{
            if($type=='1'||$type=='3'){$paswd['member_passwd']=md5($userpwd);}
            if($type=='2'){$paswd['member_paypwd']=md5($userpwd);}
            $update=$model_member->where(array('member_id'=>$member_info['member_id']))->update($paswd);
            if($update){echo '3';exit;}
            else{echo '4';exit;}
         }
    }
}
