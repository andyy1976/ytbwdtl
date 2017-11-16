<?php
/**
 * 前台登录 退出操作
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 登录
     */
    public function indexOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

        $model_member = Model('member');
       $map_id = $_POST['map_id'];
        $login_info = array();
        $login_info['user_name'] = $_POST['username'];
        $login_info['password'] = md5($_POST['password']);
        $member_info = $model_member->login($login_info);
        if(isset($member_info['error'])) {
            output_error($member_info['error']);
        } else {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token,'map_id'=>$map_id));
            } else {
                output_error('登录失败');
            }
        }
    }

    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $client;
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }

    /**
     * 注册
     */
     public function registerOp(){
        $model_member   = Model('member');
        $map_id = isset($_POST['map_id'])?$_POST['map_id']:'';     //地面商家参数
        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['member_pid'] = $_POST['member_pid'];
        $register_info['member_mobile'] = $_POST['mobile'];
        $register_info['member_mobile_bind'] = 1;
        $register_info['free']=!empty($_POST['free'])?$_POST['free']:null;
        $mem = $model_member->where(array('member_mobile'=>$_POST['mobile']))->find();
        $volis = $model_member->getMemberCommonInfo(array('auth_code'=>$_POST['auth_code']));

        if($mem){
            output_error('您的手机号码已经被使用请更换手机号码注册');
        }
        if(empty($volis)){
            output_error('验证码错误或已过期，重新输入');
        }
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'userid' => $member_info['member_id'], 'key' => $token ,'map_id'=>$map_id));
            } else {
                output_error('注册失败1');
            }
        } else {
            output_error($member_info['error']);
        }

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
                $member_info['area_name']=($s==0)?$member_pid.$ars[$s].'代理商':$area_info['area_name'].$ars[$s].'代理商';               
                echo json_encode($member_info);
                exit;
            }
            else{$s++;continue;}
        }
    }
}
