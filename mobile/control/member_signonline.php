<?php
/**
 * 在线签约
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_signonlineControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }
    
    /**
     * 步骤一
     */
    public function signOnlineStep1Op() {

        $id = intval(trim($_GET['id']));
        if ( $id > 0) {
            $signOnlineInfo = Model()->table('sign_online')->find(intval(trim($_GET['id'])));
            if (!$signOnlineInfo) {
                output_error('非法数据,疑似hacker');
            }
        } else if ($id == 0) {
            $signOnlineInfo = '初次填写且为第一步';
        } else {
            output_error('非法数据,疑似hacker');
        }
        output_data(array('signOnlineInfo' => $signOnlineInfo));
    }

    /**
     * 步骤二
     */
    public function signOnlineStep2Op() {

        $id = intval(trim($_GET['id']));
        if ( $id > 0) {
            $signOnlineInfo = Model()->table('sign_online')->field('id')->find(intval(trim($_GET['id'])));
            if (!$signOnlineInfo) {
                output_error('非法数据,疑似hacker');
            }
        } else if ($id == 0) {
            $signOnlineInfo = '初次填写且为第一步';
        } else {
            output_error('非法数据,疑似hacker');
        }
        output_data(array('signOnlineInfo' => $signOnlineInfo));
    }

    /**
     * 步骤三
     */
    public function signOnlineStep3Op() {

        $id = intval(trim($_GET['id']));
        if ( $id > 0) {
            $signOnlineInfo = Model()->table('sign_online')->find(intval(trim($_GET['id'])));
            if (!$signOnlineInfo) {
                output_error('非法数据,疑似hacker');
            }
        } else if ($id == 0) {
            $signOnlineInfo = '初次填写且为第一步';
        } else {
            output_error('非法数据,疑似hacker');
        }
        //加盟方开户行信息以及银行卡号
        $update_member_info = Model()->table('member')->find($signOnlineInfo['update_member_id']);
        $signOnlineInfo['update_member_bankcard']    = $update_member_info['member_bankcard'];
        $signOnlineInfo['update_member_bankaddress'] = $update_member_info['member_bankaddress'];
        //招商方开户行信息以及银行卡号
        $submit_member_info = Model()->table('member')->find($signOnlineInfo['submit_member_id']);
        $signOnlineInfo['submit_member_bankcard']    = $submit_member_info['member_bankcard'];
        $signOnlineInfo['submit_member_bankaddress'] = $submit_member_info['member_bankaddress'];
        //调整时间显示
        $add_time                   = $signOnlineInfo['add_time'];
        $signOnlineInfo['add_time'] = date('Y年m月d日', $signOnlineInfo['add_time']);
        $signOnlineInfo['end_time'] = date('Y年m月d日', $add_time+3600*24*356*5);
        $signOnlineInfo['pay_percent'] = round( $signOnlineInfo['update_amount_first']/$signOnlineInfo['update_amount_total'] * 100 , 2) . "％";
        output_data(array('signOnlineInfo' => $signOnlineInfo));
    }
    
    /**
     * 提交数据数据信息公共保存方法
     */
    public function saveStep1Op() {
        //二次校验提交数据
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
        array("input"=>$_POST["submit_member_truename"], "require"=>"true", "message"=>"招商方真实名字必填"),
        array("input"=>$_POST["update_member_id"], "require"=>"true",  "validator"=>"number", "message"=>"加盟会员ID必填且必须为纯数字"),
        array("input"=>$_POST["update_member_truename"], "require"=>"true", "message"=>"加盟会员真实名字必填"),
        array("input"=>$_POST["update_member_mobile"],  "require"=>"true", "validator"=>"mobile", "message"=>"加盟会员手机号必填"),
        array("input"=>$_POST["update_level"],  "require"=>"true", "message"=>"代理级别必选！"),
        array("input"=>$_POST["update_level_detail"],  "require"=>"true", "message"=>"加盟区域详情必填！"),
        array("input"=>$_POST["document_type"],  "require"=>"true", "message"=>"证件类型必选！"),
        array("input"=>$_POST["document_number"],  "require"=>"true", "validator"=>"number", "message"=>"证件号码必填！"),
        array("input"=>$_POST["update_amount_total"],  "require"=>"true", "validator"=>"currency", "message"=>"加盟总费用必填！"),
        array("input"=>$_POST["update_amount_first"],  "require"=>"true", "validator"=>"currency", "message"=>"首期加盟费用必填！"),
        array("input"=>$_POST["update_amount_last"],  "require"=>"true", "validator"=>"currency", "message"=>"剩余加盟费用必填！"),
        array("input"=>$_POST["update_amount_last_date"], "validator"=>"number", "message"=>"剩余加盟费用缴费期限必须为纯数字！")
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            //统一回复信息
            output_error('提交数据验证失败,疑似hacker');
            // output_error($error);
        } else{
            //通过判断signonline_id是否有值来判断是insert还是update操作
            $operate       = null;
            $signonline_id = intval($_POST['signonline_id']);
            $data = array();
            if ($signonline_id > 0) {
                $operate     = 'update';
                $data['id']  =  $signonline_id;
            } else {
                $operate     = 'insert';
            }
            // var_dump($_POST);exit;
            //判断要升级的会员id是否存在
            $update_member_id_info = Model()->table('member')->field('member_id,member_name,member_level,member_truename,member_mobile,member_provinceid,member_cityid,member_areaid')->find(trim($_POST["update_member_id"]));
            if (!$update_member_id_info) {
                output_error('要升级的会员ID不存在！请重新确认');
            }
            //读取缓存地理位置文件
            $cacheArea = F('area');
            $cacheArea = $cacheArea['name'];
            //开始组装数据
            //招商方真实姓名
            $data['submit_member_truename'] = trim($_POST["submit_member_truename"]);
            //招商方手机号
            $data['submit_member_mobile'] = $this->member_info['member_mobile'];
            //信息提交人id
            $data['submit_member_id']       = $this->member_info['member_id'];
            //证件类型
            $data['document_type']          = trim($_POST["document_type"]);
            //证件号码
            $data['document_number']        = trim($_POST["document_number"]);
            //要升级的会员id
            $data['update_member_id']       = trim($_POST["update_member_id"]);
            //要升级的会员姓名
            $data['update_member_truename'] = trim($_POST["update_member_truename"]);
            //要升级的会员手机号
            $data['update_member_mobile']   = trim($_POST["update_member_mobile"]);
            //升级代理等级
            $data['update_level']           = trim($_POST["update_level"]);
            //升级代理等级详细信息
            $data['update_level_detail']    = trim($_POST["update_level_detail"]);
            //原代理等级
            $data['old_level']              = $update_member_id_info['member_level'];
            //原代理等级详细信息
            $data['old_level_detail']       = $cacheArea[$update_member_id_info['member_provinceid']].'-'.$cacheArea[$update_member_id_info['member_cityid']].'-'.$cacheArea[$update_member_id_info['member_areaid']];
            //加盟总费用
            $data['update_amount_total']    = trim($_POST["update_amount_total"]);
            //首期加盟费用
            $data['update_amount_first']    = trim($_POST["update_amount_first"]);
            //剩余加盟费用
            $data['update_amount_last']     = $data['update_amount_total']-$data['update_amount_first'];
            //剩余加盟费用缴费期限
            if (trim($_POST["update_amount_first"]) > 0) {
                $data['update_amount_last_date']    = trim($_POST["update_amount_last_date"]);
            }
            //提交时间
            $data['add_time']               = time();
            $result = Model()->table('sign_online')->$operate($data);
            if ($result) {
                if ($operate == 'update') {
                    output_data(array('signonline_id' => $signonline_id));
                } else {
                    output_data(array('signonline_id' => $result));
                }    
            } else {
                output_error('抱歉，信息提交失败！');
            }
        }
    }
    
    /**
     * 上传凭证图片保存程序
     */
    public function saveStep4Op()
    {
        if ($_GET['id'] != $_POST['signOnline_id']) {
            output_error('提交数据验证失败,疑似hacker');
        }
        $tmp = Model()->table('sign_online')->find($_POST['signOnline_id']);
        if (!$tmp) {
            output_error('查无此数据！');
        }
        if ($tmp['update_status'] == 2) {
            output_error('该申请已经通过审核！');
        }
        //二次数据校验
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
        array("input"=>$_POST["idcard_positive_image"], "require"=>"true", "message"=>"加盟人身份证正面照片必填！"),
        array("input"=>$_POST["idcard_opposite_image"], "require"=>"true", "message"=>"加盟人身份证反面照片必填！"),
        array("input"=>$_POST["authorization_image"], "require"=>"true", "message"=>"招商方授权照片必填！"),
        array("input"=>$_POST["payment_image"],  "require"=>"true", "message"=>"加盟人打款凭证必填！")
        );
        $error = $obj_validate->validate();
        if ($error != '') {
            //统一回复信息
            output_error('二次校验数据验证失败,疑似hacker');
        }
        //整理数据
        $updateData = array();
        $updateData['update_status']         =  0;
        $updateData['id']                    =  $_POST['signonline_id'];    
        //加盟人身份证正面照片
        $updateData['idcard_positive_image'] =  $_POST['idcard_positive_image'];
        //加盟人身份证反面照片
        $updateData['idcard_opposite_image'] =  $_POST['idcard_opposite_image'];
        //招商方授权照片
        $updateData['authorization_image']   =  $_POST['authorization_image'];
        //加盟人打款凭证
        $updateData['payment_image']         =  $_POST['payment_image'];
        //加盟人营业执照
        if ($_POST['business_licence_image']) {
            $updateData['business_licence_image']  =  $_POST['business_licence_image'];
        }
        //加盟人组织机构代码证
        if ($_POST['organization_code_image']) {
            $updateData['organization_code_image']  =  $_POST['organization_code_image'];
        }
        //加盟人税务登记证
        if ($_POST['tax_registration_image']) {
            $updateData['tax_registration_image']  =  $_POST['tax_registration_image'];
        }

        $result = Model()->table('sign_online')->update($updateData);
        
        if ($result) {
            //给加盟方发送短信
            $message1 = "尊敬的会员：{$tmp['update_member_id']}，您的加盟申请总部已经收到，我们会在3个工作日内审核，请您耐心等待！";
            signonlineSend($tmp['update_member_mobile'], $message1);
            //给招商方发送短信
            $message2 = "尊敬的会员：{$tmp['submit_member_id']}，您提交的升级申请总部已经收到，我们会在3个工作日内审核，请您耐心等待！";
            signonlineSend($tmp['submit_member_mobile'], $message2);
            
            output_data(array('signonline_id' => $updateData['id']));
        } else {
            output_error('抱歉，信息提交失败！');
        }
    }

    /**
     * 检测id是否正确
     */
    public function checkidOp()
    {
        $getId  = $_GET['id'];
        $postId = $_POST['signOnline_id'];
        if ($getId != $postId) {
            output_error('提交数据验证失败,疑似hacker');
        } else {
            output_data('1');
        }
    }

}
