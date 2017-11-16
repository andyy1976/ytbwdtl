<?php
/**
 * 申请管理界面
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */

defined('In33hao') or exit('Access Invalid!');

class sign_onlineControl extends SystemControl{
    const EXPORT_SIZE = 1000;

    private $_links = array(
        array('url'=>'act=sign_online&op=sign_online_uncheck','text'=>'待审核列表'),
        array('url'=>'act=sign_online&op=sign_online_fail','text'=>'审核失败列表'),
        array('url'=>'act=sign_online&op=sign_online_success','text'=>'审核成功列表'),
        array('url'=>'act=sign_online&op=sign_online_all','text'=>'内推升级所有申请')
    );

    public function __construct(){
        parent::__construct();
        Language::read('store,store_grade');
    }

    public function indexOp() {
        $this->sign_online_uncheckOp();
    }

    /**
     * 会员内推升级待审核列表
     */
    public function sign_online_uncheckOp(){
        Tpl::output('type', 'uncheck');
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'sign_online_uncheck'));
        Tpl::setDirquna('shop');
        Tpl::showpage('sign_online.index');
    }

    /**
     * 会员内推升级审核失败列表
     */
    public function sign_online_failOp(){
        Tpl::output('type', 'fail');
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'sign_online_fail'));
        Tpl::setDirquna('shop');
        Tpl::showpage('sign_online.index');
    }

    /**
     * 会员内推升级审核成功列表
     */
    public function sign_online_successOp(){
        Tpl::output('type', 'success');
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'sign_online_success'));
        Tpl::setDirquna('shop');
        Tpl::showpage('sign_online.index');
    }

    /**
     * 会员内推升级审核所有列表
     */
    public function sign_online_allOp(){
        Tpl::output('type', 'all');
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'sign_online_all'));
        Tpl::setDirquna('shop');
        Tpl::showpage('sign_online.index');
    }      

    /**
     * 输出XML数据
     */
    public function get_signonline_xmlOp() {

        $model_sign_online = Model('sign_online');
        // 设置页码参数名称
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        switch (trim($_GET['type'])) {
            case 'uncheck':
                $condition['update_status'] = array('eq', 0);
            break;
            case 'fail':
                $condition['update_status'] = array('eq', 1);
            break;
            case 'success':
                $condition['update_status'] = array('eq', 2);
            break;
            case 'all':
                $condition['update_status'] = array('egt', 0);
            break;
            default:
                $condition['update_status'] = array('eq', 0);
            break;
        }
        $order = '';
        $param = array('id', 'update_member_id', 'update_member_truename', 'update_member_mobile', 'update_level', 'update_level_detail', 'update_amount_total', 'update_amount_first', 'update_amount_last', 'update_amount_last_date', 'submit_member_id', 'add_time');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];

        //待审核列表
        $signOnline_list = $model_sign_online->getList($condition, $page, $order);
        $data = array();
        $data['now_page']   = $model_sign_online->shownowpage();
        $data['total_num']  = $model_sign_online->gettotalnum();
        $update_level_array = $this->get_update_level_state();
        foreach ($signOnline_list as $value) {
            $param = array();
            if($value['update_status'] == 0) {
                $operation = "<a class='btn orange' href=\"index.php?act=sign_online&op=sign_online_detail&id=". $value['id'] ."\"><i class=\"fa fa-check-circle\"></i>审核</a>";
            } else {
               $operation = "<a class='btn orange' href=\"index.php?act=sign_online&op=sign_online_detail&id=". $value['id'] ."\"><i class=\"fa fa-check-circle\"></i>查看</a>";
            }
            $param['operation']               = $operation;
            $param['update_member_id']        = $value['update_member_id'];
            $param['update_member_truename']  = $value['update_member_truename'];
            $param['update_member_mobile']    = $value['update_member_mobile'];
            $param['update_level']            = $update_level_array[$value['update_level']];
            $param['jupdate_level_detail']    = $value['update_level_detail'];
            $param['update_amount_total']     = ncPriceFormat($value['update_amount_total']);
            $param['update_amount_first']     = ncPriceFormat($value['update_amount_first']);
            $param['update_amount_last']      = ncPriceFormat($value['update_amount_last']);
            $param['update_amount_last_date'] = $value['update_amount_last_date'];
            $param['submit_member_id']        = $value['submit_member_id'];
            switch ($value['update_status']) {
                case '0':
                    $value['update_status'] = '待审核';
                break;
                case '1':
                    $value['update_status'] = '审核失败';
                break;
                case '2':
                    $value['update_status'] = '审核成功';
                break;
            }
            $param['update_status']           = $value['update_status'];         
            $param['add_time']                = date('Y-m-d', $value['add_time']);
            $data['list'][$value['id']]       = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 审核详细页
     */
    public function sign_online_detailOp(){
        $update_level_array = $this->get_update_level_state();
        $model_store_joinin = Model('sign_online');
        $joinin_detail = $model_store_joinin->getOne(array('id'=>$_GET['id']));
        $joinin_detail_title = '审核';
        if ($joinin_detail['update_status'] > 0) {
            $joinin_detail_title = '查看';
        }
        $joinin_detail['old_level']    = $update_level_array[$joinin_detail['old_level']];
        $joinin_detail['update_level'] = $update_level_array[$joinin_detail['update_level']];
        Tpl::output('joinin_detail_title', $joinin_detail_title);
        Tpl::output('joinin_detail', $joinin_detail);
        Tpl::setDirquna('shop');
        Tpl::showpage('sign_online.detail');
    }

    /**
     * 审核操作
     */
    public function store_joinin_verifyOp() {

        $model_sign_online = Model('sign_online');

        $joinin_detail = $model_sign_online->getOne(array('id'=>$_POST['id']));

        if ($joinin_detail['update_status'] > 0) {
            showMessage('参数错误','');
        }
        $param = array();
        $param['update_status']      = $_POST['verify_type'] === 'pass' ? '2' : '1';
        $param['operation_message'] = $_POST['joinin_message'];
        $param['operation_time']    = time();

        $result = $model_sign_online->modify($param, array('id'=>$_POST['id']));
        if ($result && $param['update_status'] == 2) {

            //给加盟方发送短信
            $message1 = "尊敬的会员：{$joinin_detail['update_member_id']}，您的加盟申请(申请ID:{$joinin_detail['id']})通过审核！";
            signonlineSend($joinin_detail['update_member_mobile'], $message1);
            //给招商方发送短信
            $message2 = "尊敬的会员：{$joinin_detail['submit_member_id']}，您提交的升级申请(申请ID:{$joinin_detail['id']})通过审核！";
            signonlineSend($joinin_detail['submit_member_mobile'], $message2);

            showMessage('加盟申请成功','index.php?act=sign_online&op=sign_online_uncheck');
        } else if ($result && $param['update_status'] == 1) {
            //给加盟方发送短信
            $message1 = "尊敬的会员：{$joinin_detail['update_member_id']}，您的加盟申请(申请ID:{$joinin_detail['id']})未通过审核！";
            signonlineSend($joinin_detail['update_member_mobile'], $message1);
            //给招商方发送短信
            $message2 = "尊敬的会员：{$joinin_detail['submit_member_id']}，您提交的升级申请(申请ID:{$joinin_detail['id']})未通过审核，请及时修改再次提交审核！";
            signonlineSend($joinin_detail['submit_member_mobile'], $message2);

            showMessage('加盟申请拒绝','index.php?act=sign_online&op=sign_online_uncheck');
        } else {
            showMessage('系统异常','index.php?act=sign_online&op=sign_online_uncheck');
        }
    }

    /**
     * 加盟等级获取
     */
    private function get_update_level_state() {
        $update_level_array = array(
            0 => '见习会员',
            1 => '激活会员',
            2 => '端口代',
            3 => '区/县代',
            4 => '市代',
            5 => '省代'
        );
        return $update_level_array;
    }
}
