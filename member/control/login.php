<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377 
 */



defined('In33hao') or exit('Access Invalid!');

class loginControl extends BaseLoginControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录操作
     *
     */
    public function indexOp(){
        Language::read("home_login_index,home_login_register");
        
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        $member_ss = $model_member->getMemberInfo(array('member_name'=>$_POST['username']));
            if($member_ss['member_login'] ===1){
                showDialog('親,您的账号已经登陆了。如果不是本人登陆的请使用手机更改密码','','error');
            }
        //检查登录状态
        session_unset();          
        $model_member->checkloginMember();
        
        if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
            $script = "document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&nchash=".getNchash()."&t=' + Math.random();";
        }
        $result = chksubmit(false,C('captcha_status_login'),'num');
        if($_GET['admin']==1){$result=true;$_POST['ref_url']='index.php?act=index';}

        if ($result !== false){
            if ($result === -11){
                showDialog($lang['login_index_login_illegal'],'','error',$script);
            }elseif ($result === -12){
                showDialog($lang['login_index_wrong_checkcode'],'','error',$script);
            }
        
        
            
            $login_info = array();
            if($_GET['admin']==1){
                $login_info['user_name'] = $_GET['user_name'];
                $login_info['password'] = $_GET['password'];
            }else{
                $login_info['user_name'] = $_POST['user_name'];
                $login_info['password'] = md5($_POST['password']);
            }
            
            $member_info = $model_member->login($login_info);
            if(isset($member_info['error'])) {
                showDialog($member_info['error'],'','error',$script);
            }
 
            // 自动登录
            $member_info['auto_login'] = $_POST['auto_login'];
            //生成session
            // print_r($member_info);exit;
            
            $model_member->createSession($member_info, true);
            if ($_GET['inajax'] == 1){
                showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
            } else {

                $host=$_SERVER["HTTP_HOST"];
                redirect('http://'.$host);
            }
        }else{

            //登录表单页面
            $_pic = @unserialize(C('login_pic'));

            if ($_pic[0] != ''){
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
            }else{
                Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
            }
            // echo $_GET['ref_url'];exit;
            if(empty($_GET['ref_url'])) {
                $ref_url = getReferer();

                if (!preg_match('/act=login&op=logout/', $ref_url)) {
                 $_GET['ref_url'] = $ref_url;
                }
            }
            Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
            if ($_GET['inajax'] == 1){
                Tpl::showpage('login_inajax','null_layout');
            }else{
                Tpl::showpage('login');
            }
        }
    }

    /**
     * 退出操作
     *
     * @param int $id 记录ID
     * @return array $rs_row 返回数组形式的查询结果
     */
    public function logoutOp(){
        Language::read("home_login_index");
        $lang   = Language::getLangContent();
        $model_member = Model('member');
        $ss = $model_member->where(array('member_id'=>$_SESSION['member_id']))->update(array('member_login'=>0));
        echo $ss['member_id'];
        if($ss){
        // 清理COOKIE
        setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
        setNcCookie('auto_login', '', -3600);
        setNcCookie('cart_goods_num','',-3600);
        session_unset();
        session_destroy();
        
        if(empty($_GET['ref_url'])){
            $ref_url = getReferer();
        }else {
            $ref_url = $_GET['ref_url'];
        }
        redirect(LOGIN_SITE_URL . '/index.php?act=login&ref_url='.urlencode($ref_url));
    }
    }

    /**
     * 会员注册页面
     *
     * @param
     * @return
     */
    public function registerOp() {
        Language::read("home_login_register");
        
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        $model_member->checkloginMember();
        Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);
        Tpl::showpage('register');
    }
	/*会员注册检测手机号是否可以使用
	  *修改by：李志军*/
	public function check_moblieOp() {
            /**
            * 实例化模型
            */
			$model_member   = Model('member');
            $check_member_phone  = $model_member->getMemberInfo(array('member_mobile'=>$_GET['phone']));
            if(is_array($check_member_phone) && count($check_member_phone)>0) {
                echo 'false';
				return false;
            } else {
                echo 'true';
				return true;
            }
    }
	
    /**
     * 会员添加操作
     *
     * @param
     * @return
     */
    public function usersaveOp() {
        //重复注册验证
        // if (process::islock('reg')){
        //     showDialog(Language::get('nc_common_op_repeat'));
        // }
        Language::read("home_login_register");
        $lang   = Language::getLangContent();
        $model_member   = Model('member');
        $model_member->checkloginMember();
         if(empty($_POST['sms_captcha'])){
            showMessage('短信验证码不能为空');
        }
        $code = $model_member->getMemberCommonInfo(array('auth_code'=>$_POST['sms_captcha']));
        $member_par = $model_member->where(array('member_pid'=>$_POST['member_pid']))->find();
        if(!$code){
            showMessage('短信验证码错误');  
        }
        if($tel){
            showMessage('您的手机号码已经被使用了请更换其他手机号码');
        }

        // $result = chksubmit(true,C('captcha_status_register'),'num');
        $result = chksubmit(true,false,'num');

        if ($result){
            if ($result === -11){
                showDialog($lang['invalid_request'],'','error');
            }elseif ($result === -12){
                showDialog($lang['login_usersave_wrong_code'],'','error');
            }
        } else {
            showDialog($lang['invalid_request'],'','error');
        }
        $register_info = array();
        $register_info['username'] = $_POST['user_name'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['member_pid'] = $_POST['member_pid'];
        $register_info['member_mobile'] = $_POST['phone'];
        $register_info['member_paypwd']=$_POST['paypwd'];
		//添加奖励云豆ID BY 33HAO.COM
		$register_info['inviter_id'] = intval(base64_decode($_COOKIE['uid']))/1;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
            process::addprocess('reg');

            $_POST['ref_url']   = (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : urlMember('member_information', 'member'));
            if ($_GET['inajax'] == 1){
                showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
            } else {
                redirect($_POST['ref_url']);
            }
        } else {
            showDialog($member_info['error']);
        }
    }
    /**
     * 会员名称检测
     *
     * @param
     * @return
     */
    public function check_memberOp() {
            /**
            * 实例化模型
            */
			
            $model_member   = Model('member');

            $check_member_name  = $model_member->getMemberInfo(array('member_name'=>$_GET['username']));
            if(is_array($check_member_name) && count($check_member_name)>0) {
                echo 'false';
				return false;
            } else {
                echo 'true';
				return true;
            }
    }

    /**
     * 电子邮箱检测
     *
     * @param
     * @return
     */
    public function check_emailOp() {
        $model_member = Model('member');
        $check_member_email = $model_member->getMemberInfo(array('member_email'=>$_GET['email']));
        if(is_array($check_member_email) && count($check_member_email)>0) {
            echo 'false';
        } else {
            echo 'true';
        }
    }

    /**
     * 忘记密码页面
     */
    public function forget_passwordOp(){
        /**
         * 读取语言包
         */
        Language::read('home_login_register');
        $_pic = @unserialize(C('login_pic'));
        if ($_pic[0] != ''){
            Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
        }else{
            Tpl::output('lpic',UPLOAD_SITE_URL_HTTPS.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
        }
        Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));
        Tpl::showpage('find_password');
    }

    /**
     * 找回密码的发邮件处理
     */
       public function find_passwordOp(){
        // Language::read('home_login_register');
        // $lang   = Language::getLangContent();

        $member_model   = Model('member');
        $member_common  = Model('member_common');
        if (chksubmit()){
        
        if(strlen($_POST['member_tel']) !=11){
                showMessage("手机号码没有19位请重新填写");
        }
        if(empty($_POST['member_tel'])){
            showMessage('请填写手机号，手机号码不能为空');
        }
        
        $member = $member_model->getMemberInfo(array('member_mobile'=>$_POST['member_tel']));
        
        if(empty($member) or !is_array($member)){
            process::addprocess('forget');
            showMessage('你的手机号码空');
        }

        if(strtoupper($_POST['member_tel'])!=strtoupper($member['member_mobile'])){
             process::addprocess('forget');
            showMessage($lang['login_password_email_not_exists'],'','error');
        }
        //限制短信发送
        $member_common_info = $member_common->where(array('member_id'=>$member['member_id']))->find();
        if (!empty($member_common_info['send_mb_time'])) {

            if (TIMESTAMP - $member_common_info['send_mb_time'] < 58) {
                showMessage('请60秒以后再次重置密码');
                exit;
            } else {
                if ($member_common_info['send_mb_times'] >= 15) {
                    showMessage('您今天发送短信已超过15条，今天将无法再次发送');
                    exit;
                    
                }
            }                
            
        }
       
        $member_common->where(array('member_id'=>$member['member_id']))->update(array('send_mb_time'=>TIMESTAMP,'send_mb_times'=>array('exp','send_mb_times+1')));
        //产生密码
        $new_password   = $this->getRandChar(8);
        if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
            showMessage('密码修改失败','','error');
        }

        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
        $param = array();
        $param['site_name'] = C('site_name');
        $param['user_name'] = $member['member_mobile'];
        $param['new_password'] = $new_password;
        // $param['site_url'] = SHOP_SITE_URL;
        $subject    = ncReplaceText($tpl_info['title'],$param);
        $message    = ncReplaceText($tpl_info['content'],$param);

        $sms = new Sms();
        $result = $sms->send($member["member_mobile"],$message);
        
        if($result){

        showMessage('新密码已经发送至您的手机，请尽快登录并更改密码！','','succ','',5);
    }
    }
}
    /**
     * 邮箱绑定验证
     */
    public function bind_emailOp() {
       $model_member = Model('member');
       $uid = @base64_decode($_GET['uid']);
       $uid = decrypt($uid,'');
       list($member_id,$member_email) = explode(' ', $uid);

       if (!is_numeric($member_id)) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $member_info = $model_member->getMemberInfo(array('member_id'=>$member_id),'member_email');
       if ($member_info['member_email'] != $member_email) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));
       if (empty($member_common_info) || !is_array($member_common_info)) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }
       if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24*3600) {
           showMessage('验证失败',SHOP_SITE_URL,'html','error');
       }

       $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1));
       if (!$update) {
           showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
       }

       $data = array();
       $data['auth_code'] = '';
       $data['send_acode_time'] = 0;
       $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
       if (!$update) {
           showDialog('系统发生错误，如有疑问请与管理员联系');
       }
       showMessage('邮箱设置成功','index.php?act=member_security&op=index');

    }
    public function checkpidOP(){
        $member_pid=$_POST['pid'];
        $member=Model('member');
        $area=Model('area');
        $ars=array('端口','县','市','省');
        $member_info=$member->where(array('member_id'=>$member_pid))->field('portid,member_areaid,member_cityid,member_provinceid,member_name')->find();
        $s=0;
        foreach ($member_info as $key => $ar) {
            
            if(!empty($ar)){
                $area_info=$area->getAreaInfo(array('area_id'=>$ar),'area_name');
                $member_info['area_name']=($s==0)?$member_info['portid'].$ars[$s].'代理商':$area_info['area_name'].$ars[$s].'代理商';               
                echo json_encode($member_info);
                exit;
            }
            else{$s++;continue;}
        }
    }
    //生成8位随机数
    function getRandChar($length){
       $str = null;
       $strPol = "0123456789abcdefghijklmnopqrstuvwxyz";
       $max = strlen($strPol)-1;

       for($i=0;$i<$length;$i++){
        $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
       }

       return $str;
    }
 
}
