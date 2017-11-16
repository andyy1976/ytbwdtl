<?php
/**
 * 预存款管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class predepositControl extends SystemControl{
    const EXPORT_SIZE = 500;
    public function __construct(){
        parent::__construct();
        Language::read('predeposit');
    }

    public function indexOp() {
        $this->predepositOp();
    }

    /**
     * 充值列表
     */
    public function predepositOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('pd.list');
    }

    /**
     * 充值编辑(更改成收到款)
     */
    public function recharge_editOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        //查询充值信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_id'] = $id;
        $condition['pdr_payment_state'] = 0;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (empty($info)){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        if (!chksubmit()) {
            //显示支付接口列表
            $payment_list = Model('payment')->getPaymentOpenList();
            //去掉预存款和货到付款
            foreach ($payment_list as $key => $value){
                if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
                    unset($payment_list[$key]);
                }
            }
            Tpl::output('payment_list',$payment_list);
            Tpl::output('info',$info);
            Tpl::setDirquna('shop');
            Tpl::showpage('pd.edit');
            exit();
        }

        //取支付方式信息
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $_POST['payment_code'];
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(!$payment_info || $payment_info['payment_code'] == 'offline' || $payment_info['payment_code'] == 'offline') {
            showMessage(L('payment_index_sys_not_support'),'','html','error');
        }

        $condition = array();
        $condition['pdr_sn'] = $info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = strtotime($_POST['payment_time']);
        $update['pdr_payment_code'] = $payment_info['payment_code'];
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $_POST['trade_no'];
        $update['pdr_admin'] = $this->admin_info['name'];
        $log_msg = L('admin_predeposit_recharge_edit_state').','.L('admin_predeposit_sn').':'.$info['pdr_sn'];

        try {
            $model_pd->beginTransaction();
            //更改充值状态
            $state = $model_pd->editPdRecharge($update,$condition);
            if (!$state) {
                throw Exception(Language::get('predeposit_payment_pay_fail'));
            }
            //变更会员预存款
            $data = array();
            $data['member_id'] = $info['pdr_member_id'];
            $data['member_name'] = $info['pdr_member_name'];
            $data['amount'] = $info['pdr_amount'];
            $data['pdr_sn'] = $info['pdr_sn'];
            $data['admin_name'] = $this->admin_info['name'];
            $data['split_id']=$split_id;
            //新增充值类型
            $data['pdr_type']=$info['pdr_type'];
            $model_pd->changePd('recharge',$data);
            $model_pd->commit();
            $this->log($log_msg,1);
            //记录消费日志
            QueueClient::push('addConsume', array('member_id'=>$info['pdr_member_id'],'member_name'=>$info['pdr_member_name'],
            'consume_amount'=>$info['pdr_amount'],'consume_time'=>TIMESTAMP,'consume_remark'=>'管理员更改充值单['.$info['pdr_sn'].']状态,充值成功'));
            showMessage(Language::get('admin_predeposit_recharge_edit_success'),'index.php?act=predeposit&op=predeposit');
        } catch (Exception $e) {
            $model_pd->rollback();
            $this->log($log_msg,0);
            showMessage($e->getMessage(),'index.php?act=predeposit&op=predeposit','html','error');
        }
    }

    /**
     * 充值查看
     */
    public function recharge_infoOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        //查询充值信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_id'] = $id;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (empty($info)){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        Tpl::output('info',$info);
        Tpl::setDirquna('shop');
        Tpl::showpage('pd.info', 'null_layout');

    }

    /**
     * 充值删除
     */
    public function recharge_delOp(){
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_pd = Model('predeposit');
            $model_upload = Model('upload');
            $condition['pdr_payment_state'] = 0;
            $condition['pdr_id'] = $id;
            $result = $model_pd->delPdRecharge($condition);
            $this->log('充值申请删除[ID:'.$id.']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 预存款日志
     */
    public function pd_log_listOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('pd_log.list');
    }

    /**
     * 提现列表
     */
    public function pd_cash_listOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.list');
    }

    // 待审核列表
    public function pd_cash_list_viewOp()
    {
        Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.approve');
    }

    // 待核准列表
    public function pd_cash_list_reviewOp()
    {
        Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.review');
    }


    // 易宝提现审核列表数据
    public function get_cash_xml_dataOp()
    {

        $model_pd = Model('predeposit');

        $condition = array();

        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;

        // 起始时间
        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        // 会员名称
        if (!empty($_GET['member_name'])){
            $condition['pdc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }

        // 会员ID
        if (!empty($_GET['member_id'])){
            $condition['pdc_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }

        // 收款人姓名
        if (!empty($_GET['user_name'])){
            $condition['pdc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }

        // 支付状态
        // <option value="0">未支付</option>
        // <option value="1">已付款</option>
        // <option value="2">财务处理中</option>
        // <option value="3">其他</option>
        if ($_GET['pdc_payment_state'] != ''){
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        } else {
            $condition['pdc_payment_state'] = '0';
        }

        // 提现类别
        // <option value="2">充值提现</option>
        // <option value="3">分销提现</option>
        // <option value="1">余额提现</option>
        // <option value="4">商家提现</option>
        // <option value="5">省代余额提现</option>
        // <option value="6">代理余额提现</option>
        if($_GET['predeposit_type']!=''){
            $condition['predeposit_type'] = $_GET['predeposit_type'];
        }

        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }


        // 易宝渠道
        $condition['type'] = 'ybzf';

        $order = '';
        $param = array(
            'pdc_id',
            'pdc_sn',
            'pdc_member_id',
            'pdc_member_name',
            'pdc_amount',
            'pdc_add_time',
            'pdc_bank_name',
            'pdc_bank_no',
            'pdc_bank_user',
            'pdc_payment_state',
            'pdc_payment_time',
            'pdc_payment_admin',
            'type',
        );

        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
        $getPdCashMoneySum = $model_pd->getPdCashMoneySum($condition, 'pdc_amount');
        $cash_list = $model_pd->getPdCashList($condition, $page, '*', $order);
        $data = array();

        // echo '~~~~';
        // print_r($getPdCashMoneySum);
        // exit;

        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        $data['count'] = $getPdCashMoneySum;

        foreach ($cash_list as $value) {
            
            $row = array();

            $row['operation'] = "";

            // 未支付 == 0
            if ($value['pdc_payment_state'] == 0) {
                $row['operation'] .= "<a class='btn red' href=\"javascript:void(0)\" onclick=\"fg_delete('" 
                . $value['pdc_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }

            $row['operation'] .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('cash_info','查看提现编号“"
            . $value['pdc_sn'] ."”的明细', 'index.php?act=predeposit&op=pd_cash_view&id="
            . $value['pdc_id'] ."', 640)\" ><i class='fa fa-list-alt'></i>查看</a>";

            $row['pdc_id'] = $value['pdc_id'];
            $row['pdc_sn'] = $value['pdc_sn'];
            $row['pdc_member_id'] = $value['pdc_member_id'];
            $row['pdc_member_name'] = "<img src=".getMemberAvatarForID($value['pdc_member_id'])
                ." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src="
                .getMemberAvatarForID($value['pdc_member_id']).">\")'>" .$value['pdc_member_name'];

            $row['pdc_amount'] = ncPriceFormat($value['pdc_amount']);
            $row['pdc_add_time'] = date('Y-m-d', $value['pdc_add_time']);
            $row['pdc_bank_name'] = $value['pdc_bank_name'];
            $row['pdc_bank_no'] = $value['pdc_bank_no'];
            $row['pdc_bank_user'] = $value['pdc_bank_user'];
            //$row['pdc_payment_state'] = $value['pdc_payment_state'] == '0' ? '未支付' : '已支付';
            
            if($value['pdc_payment_state'] == '0'){
                $row['pdc_payment_state'] ='未支付';
            } elseif($value['pdc_payment_state'] == '1'){
                $row['pdc_payment_state'] ='已支付';       
            } elseif($value['pdc_payment_state'] == '2'){
                $row['pdc_payment_state'] ='财务处理中'; 
            } elseif($value['pdc_payment_state'] == '4'){
                $row['pdc_payment_state'] ='待核准'; // 已初审，待核准
            } elseif($value['pdc_payment_state'] == '5'){
                $row['pdc_payment_state'] ='待打款'; // 已核准，待打款
            } elseif($value['pdc_payment_state'] == '6'){
                $row['pdc_payment_state'] ='打款失败'; // 打款失败
            }

            $row['pdc_payment_time'] = $value['pdc_payment_time'] > 0 ? date('Y-m-d', $value['pdc_payment_time']) : '';
            $row['pdc_payment_admin'] = $value['pdc_payment_admin'];

            switch ($value['type']) {
                case 'ybzf':
                    $row['type'] = '易宝';
                    break;
                case 'tlzf':
                    $row['type'] = '通联';
                    break;
                default:
                    $row['type'] = '其它';
                    break;
            }

            $data['list'][$value['pdc_id']] = $row;
        }
      
        // print_r($data); exit;

        echo Tpl::flexigridXML($data);
        exit();
    }

    // 初审通过，申请核准
    public function do_apply_approveOp()
    {
        $ids = $_POST['ids'];
        $model_pd = Model('predeposit');
        $data['pdc_payment_state'] = '4'; // 初审通过，申请核准
        $result = $model_pd->editPdCash($data, $condition = array(
            'pdc_id' => array('IN', explode(',', $ids))
        ));
        echo $result ? '1' : '0';
        // var_dump($ids);
        // var_dump($result);
    }

    // 核准通过，通知第三方代付
    public function do_approveOp()
    {
        $ids = $_POST['ids'];
        $model_pd = Model('predeposit');

        // 。。。。
        $list = $model_pd->getPdCashList($condition = array(
            'pdc_id' => array('IN', explode(',', $ids))
        ));

        // echo number_format(8,2);
        // echo "\n";
        // echo number_format(10, 2, '.', '');
        // echo "\n";
        // echo number_format(10.01, 2, '.', '');
        // print_r($list); exit;

        if (!$list) {
            echo "0";
            exit;
        }

        include BASE_PATH . '/api/ybdf/SendTransferBatch.php';
        $a = new Model_Yibao();
        $items = array();
        foreach ($list as $key => $item) {

            $bank_Name = '农业银行';
            
            if ($item['pdc_bank_no'] == '6222021901020570295') {
                $bank_Name = '工商银行';
            }

            $items[] = array(
                'order_Id'         => $item['pdc_id'],
                'amount'           => number_format($item['pdc_amount'],2,'.',''), // 打款金额
                'account_Name'     => $item['pdc_bank_user'], // 帐户名称
                'account_Number'   => $item['pdc_bank_no'], // 帐户号 '621347129472' . $key, //
                // 'bank_Code'        => 'ABC', // 收款银行编号
                'bank_Name'        => $bank_Name, // 收款银行全称
                'fee_Type'         => 'SOURCE', // 手续费收取方式 - 取值：“SOURCE” 商户承担 “TARGET”用户承担
                'urgency'          => '0', // 加急 - 只能填写 0 或者 1，最终是否实 时出款取决于商户是否开通该银 行的实时出款。
                'branch_Bank_Name' => '', // 收款银行支行名称
                'province'         => '', // 收款行省份编码
                'city'             => '', // 收款行城市编码
            );
        }

        // print_r($items);
        // exit;

        // 批量提交第三方代付
        // 打款批次号: 不区分产品,必须唯一 必须为 15 位的数字串
        $batch_No = $a->create_batchno();
        $xml = $a->request($items, $batch_No);
        $hmac = $a->Hmacsafe($xml);
        if ($hmac == "SUCCESS") {
            // echo  '验签成功' . $hmac;
            // ... 

            $arr      = json_decode(json_encode((array) simplexml_load_string($xml)), true);
            $cmd       = $arr['cmd'];
            $ret_Code  = $arr['ret_Code'];
            $mer_Id    = $arr['mer_Id'];
            $batch_No  = $arr['batch_No'];
            $total_Amt = $arr['total_Amt'];
            $total_Num = $arr['total_Num'];
            $r1_Code   = $arr['r1_Code'];

            $data = array();
            $data['yb_batch_no'] = $batch_No;

            if ($ret_Code == '1') { // 请求成功

                $data['pdc_payment_state'] = '5'; // 5 - 核准通过，通知第三方代付
                $condition = array(
                    'pdc_id' => array('IN', explode(',', $ids))
                );
                $result = $model_pd->editPdCash($data, $condition);

                echo '1';

            }

            else {
                echo $arr['error_Msg'];
            }

            // print_r($arr);



            exit;

        }

        else {
            // 验证签名失败
            echo 'verify signature failed';
            exit;
            // echo  '验签失败' . $hmac;
        }

        // echo "<br>";
        // echo "<textarea name='name' rows='15' cols='120' wrap='hard'>" 
        // . mb_convert_encoding($data,'utf-8','gbk') 
        // . "</textarea>";
        // echo "\n\n\n" . mb_convert_encoding($data,'utf-8','gbk') ."\n\n\n";

        
        // ....
        // echo $result ? '1' : '0';
        // var_dump($ids);
        // var_dump($result);
    }



    /**
     * 删除提现记录
     */
    public function pd_cash_delOp(){
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_pd = Model('predeposit');
            $condition = array();
            $condition['pdc_id'] = $id;
            $condition['pdc_payment_state'] = 0;
            $info = $model_pd->getPdCashInfo($condition);
            if (!$info) {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
            try {
                $result = $model_pd->delPdCash($condition);
                if (!$result) {
                    throw new Exception(Language::get('admin_predeposit_cash_del_fail'));
                }
                //退还冻结的预存款
                $model_member = Model('member');
                $member_info = $model_member->getMemberInfo(array('member_id'=>$info['pdc_member_id']));
                //扣除冻结的预存款
                $admininfo = $this->getAdminInfo();
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['predeposit_type'] = $info['predeposit_type'];//加入的。。。。。。。
                if($info['predeposit_type']==1){
                    $data['amount'] = $info['pdc_amount'] / 0.87;
                }elseif($info['predeposit_type']==3){
                    $data['amount'] = $info['pdc_amount'] * 1.01;
                }else{
                    $data['amount'] = $info['pdc_amount'];
                }                
                $data['order_sn'] = $info['pdc_sn'];
                $data['admin_name'] = $admininfo['name'];
                $model_pd->changePd('cash_del',$data);
                $model_pd->commit();

                $this->log('提现申请删除[ID:'.$id.']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } catch (Exception $e) {
                $model_pd->commit();
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 更改提现为支付状态
     */
    public function pd_cash_payOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=pd_cash_list','','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_id'] = $id;
        $condition['pdc_payment_state'] = 0;
        $info = $model_pd->getPdCashInfo($condition);
        if (!is_array($info) || count($info)<0){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=pd_cash_list','','error');
        }

        //查询用户信息
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('member_id'=>$info['pdc_member_id']));

        $update = array();
        $admininfo = $this->getAdminInfo();
        $update['pdc_payment_state'] = 1;
        $update['pdc_payment_admin'] = $admininfo['name'];
        $update['pdc_payment_time'] = TIMESTAMP;
        $log_msg = L('admin_predeposit_cash_edit_state').','.L('admin_predeposit_cs_sn').':'.$info['pdc_sn'];

        try {
            $model_pd->beginTransaction();
            $result = $model_pd->editPdCash($update,$condition);
            if (!$result) {
                throw new Exception(Language::get('admin_predeposit_cash_edit_fail'));
            }
            //扣除冻结的预存款
            $data = array();
            $data['member_id'] = $member_info['member_id'];
            $data['member_name'] = $member_info['member_name'];
            $data['predeposit_type'] = $info['predeposit_type'];//加入的。。。。。。。
            //判断提现类型加入的。。。。。。。。。
            if($info['predeposit_type']==1){
                $data['amount'] = $info['pdc_amount'] / 0.87;
            }elseif($info['predeposit_type']==3){
                $data['amount'] = $info['pdc_amount'] * 1.01;
            }else{
                $data['amount'] = $info['pdc_amount'];
            }            
            $data['order_sn'] = $info['pdc_sn'];
            $data['admin_name'] = $admininfo['name'];
            $model_pd->changePd('cash_pay',$data);
            $model_pd->commit();
            $this->log($log_msg,1);
            showMessage(Language::get('admin_predeposit_cash_edit_success'),'index.php?act=predeposit&op=pd_cash_list');
        } catch (Exception $e) {
            $model_pd->rollback();
            $this->log($log_msg,0);
            showMessage($e->getMessage(),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }
    }

    /**
     * 查看提现信息
     */
    public function pd_cash_viewOp(){
        $id = intval($_GET['id']);
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_id'] = $id;
        $info = $model_pd->getPdCashInfo($condition);

            if($info['pdc_payment_state'] == '0'){
                $info['pdc_payment_state'] ='未支付';
            } elseif($info['pdc_payment_state'] == '1'){
                $info['pdc_payment_state'] ='已支付';       
            } elseif($info['pdc_payment_state'] == '2'){
                $info['pdc_payment_state'] ='财务处理中'; 
            } elseif($info['pdc_payment_state'] == '4'){
                $info['pdc_payment_state'] ='待核准'; // 已初审，待核准
            } elseif($info['pdc_payment_state'] == '5'){
                $info['pdc_payment_state'] ='待打款'; // 已核准，待打款
            } elseif($info['pdc_payment_state'] == '6'){
                $info['pdc_payment_state'] ='打款失败'; // 打款失败
            }
        
        Tpl::output('info',$info);
        Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.view', 'null_layout');
    }


    /**
     * 导出预存款充值记录
     *
     */
    public function export_step1Op(){
        $condition = array();
        if ($_GET['member_name']) {
            $condition['pdr_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['member_id']) {
            $condition['pdr_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['pdr_payment_state'] != '') {
            $condition['pdr_payment_state'] = $_GET['pdr_payment_state'] == 1 ? 1 : 0;
        }
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('pdr_id', 'pdr_sn', 'pdr_member_id', 'pdr_member_name', 'pdr_amount', 'pdr_add_time', 'pdr_payment_name', 'pdr_trade_sn', 'pdr_payment_state', 'pdr_payment_time', 'pdr_admin');
        if (in_array($$_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['pdr_id'] = array('in', $id_array);
        }
        $model_pd = Model('predeposit');
        if (!is_numeric($_GET['curpage'])){
            $count = $model_pd->getPdRechargeCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=predeposit&op=predeposit');
                Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_pd->getPdRechargeList($condition,'','*','pdr_id desc',self::EXPORT_SIZE);
                $rechargepaystate = array(0=>'未支付',1=>'已支付',2=>'财务处理中');
                foreach ($data as $k=>$v) {
                    $data[$k]['pdr_payment_state'] = $rechargepaystate[$v['pdr_payment_state']];
                }
                $this->createExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_pd->getPdRechargeList($condition,'','*',$order,"{$limit1},{$limit2}");
            $rechargepaystate = array(0=>'未支付',1=>'已支付',2=>'财务处理中');
            foreach ($data as $k=>$v) {
                $data[$k]['pdr_payment_state'] = $rechargepaystate[$v['pdr_payment_state']];
            }
            $this->createExcel($data);
        }
    }

    /**
     * 生成导出预存款充值excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_no'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_member'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_ctime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_ptime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_pay'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_paystate'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_memberid'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['pdr_sn']);
            $tmp[] = array('data'=>$v['pdr_member_name']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdr_add_time']));
            if (intval($v['pdr_payment_time'])) {
                if (date('His',$v['pdr_payment_time']) == 0) {
                   $tmp[] = array('data'=>date('Y-m-d',$v['pdr_payment_time']));
                } else {
                   $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdr_payment_time']));
                }
            } else {
                $tmp[] = array('data'=>'');
            }
            $tmp[] = array('data'=>$v['pdr_payment_name']);
            $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['pdr_amount']));
            $tmp[] = array('data'=>$v['pdr_payment_state']);
            $tmp[] = array('data'=>$v['pdr_member_id']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_yc_yckcz'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_yc_yckcz'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 导出预存款提现记录
     *
     */
    public function export_cash_step1Op(){

        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;

        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if (!empty($_GET['predeposit_type'])){
            $condition['predeposit_type'] =$_GET['predeposit_type'];
        }

        if ($_GET['pdc_payment_state'] == ''){
            $condition['pdc_payment_state'] = 0;
        }else{
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        }

        

        // print_r($condition);
        // exit;

        $order = '';
        // $param = array('pdr_id', 'pdr_sn', 'pdr_member_id', 'pdr_member_name', 'pdr_amount', 'pdr_add_time', 'pdr_payment_name', 'pdr_trade_sn', 'pdr_payment_state', 'pdr_payment_time', 'pdr_admin');
        // if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
        //     $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        // }
        $model_pd = Model('predeposit');
        $count = $model_pd->getPdCashCount($condition);
        $amout = $model_pd->where($condition)->sum('pdc_amount');
        $array = array();
        //2017-7-18 修改导出条数 一次500
        $data = $model_pd->where($condition)->limit(1000)->field('pdc_member_id,pdc_bank_user,pdc_bank_no,pdc_amount,pdc_sn')->select();
        // if (!empty($_GET['type'])){
        //     $condition['type'] = $_GET['type'];
        // }
        $this->createCashExcel($data,$count,$amout,$condition['predeposit_type'], $condition['type']);    
    }

    /**
     * 生成导出预存款提现excel
     *
     * @param array $data
     */
    private function createCashExcel($data = array(),$page,$amout,$predeposit_type, $pd_cash_type=null){
        Language::read('export');
        import('libraries.excel');
        ini_set('display_errors', 'Off');
         ini_set('max_execution_time', '3000');                                        // 就是这里需要手动改变下php.ini中的运存的大小和超时时间的长短
         ini_set('memory_limit', '1024M');
        $excel_obj = new Excel();
        $excel_data = array();
        $pd_charge=Model('pd_cash');
        // if($predeposit_type==2){
            $excel_data[0][] = array('data'=>'代付模板');
            $excel_data[1][] = array('data'=>'模板ID号');
            $excel_data[1][] = array('data'=>'企业编码');
            $excel_data[1][] = array('data'=>'企业批次号');
            $excel_data[2][] = array('data'=>'100');
            $excel_data[2][] = array('data'=>'64005');
            $excel_data[2][] = array('data'=>'64005'.date('Ymd',strtotime("-1 day")));
            $excel_data[3][] = array('data'=>'日期');
            $excel_data[3][] = array('data'=>'序号');
            $excel_data[3][] = array('data'=>'明细数目');
            $excel_data[3][] = array('data'=>'金额(单位:元)');
            $excel_data[4][] = array('data'=> date('Y/m/d H:i',strtotime("-1 day")));
            $excel_data[4][] = array('data'=>'01');
            $excel_data[4][] = array('data'=>$page);
            $excel_data[4][] = array('data'=>$amout);
            $excel_data[5][] = array('data'=>'明细信息');
            $excel_data[6][] = array('data'=>'明细序号');
            $excel_data[6][] = array('data'=>'收款人开户行名称');
            $excel_data[6][] = array('data'=>'开户行行号');
            $excel_data[6][] = array('data'=>'收款人银行账号');
            $excel_data[6][] = array('data'=>'户名');
            $excel_data[6][] = array('data'=>'金额(单位:元)');
            $excel_data[6][] = array('data'=>'手机号');
            $excel_data[6][] = array('data'=>'企业流水号');
            $excel_data[6][] = array('data'=>'备注');
            $mid='1';
            foreach ((array)$data as $k=>$v){
                // $bankname=$this->getBankInfo($v['pdc_bank_no']);
                // $str1='农业';
                // if(strpos($bankname,$str1)===false){
                //    continue;
                // }
                if($v['pdc_member_id']=='10088'){
                    continue;
                }
                if ($pd_cash_type) {
                    $update=$pd_charge->where(array(
                        'pdc_sn'=>$v['pdc_sn'], 
                        'type'=>'tlzf'
                    ))->update(array('pdc_payment_state'=>'2'));
                } else {
                    $update=$pd_charge->where(array(
                        'pdc_sn'=>$v['pdc_sn']
                    ))->update(array('pdc_payment_state'=>'2'));
                }

                $tmp = array();
                $hid=sprintf("%05d",$mid);
                $tmp[] = array('data'=>$hid);
                $tmp[] = array('data'=>'农业银行');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>$v['pdc_bank_no']);
                $tmp[] = array('data'=>$v['pdc_bank_user']);
                $tmp[] = array('data'=>$v['pdc_amount']);
                $tmp[] = array('data'=>'');
                $tid=sprintf("%04d",$mid);
                $tmp[] = array('data'=>date('Ymd',strtotime("-1 day")).$tid);
                $tmp[] = array('data'=>$v['pdc_member_id']);
                $mid++;
                $excel_data[] = $tmp;
            }
             $excel_data = $excel_obj->charset($excel_data,CHARSET);
             $excel_obj->addArray($excel_data);
             $excel_obj->addWorksheet($excel_obj->charset(L('exp_tx_title'),CHARSET));
             $excel_name=array('1'=>'云豆余额提现','2'=>'充值余额提现','3'=>'分销余额提现','4'=>'商家提现','5'=>'省代余额提现','6'=>'代理余额提现');
             $excel_obj->generateXML($excel_name[$predeposit_type]);
        // }else{
        //     foreach ((array)$data as $k=>$v){


        //         $update=$pd_charge->where(array('pdc_sn'=>$v['pdc_sn'] ))->update(array('pdc_payment_state'=>'2'));


        //         $tmp = array();
        //         $tmp[] = array('data'=>$k);
        //         $tmp[] = array('data'=>$v['pdc_bank_no']);
        //         $tmp[] = array('data'=>$v['pdc_bank_user']);
        //         $tmp[] = array('data'=>$v['pdc_amount']);
        //         if($k==0){
        //            $tmp[] = array('data'=>'工资');  
        //         }
        //         $excel_data[] = $tmp;
        //     }
        //      $excel_data = $excel_obj->charset($excel_data,CHARSET);
        // $excel_obj->addArray($excel_data);
        // $excel_obj->addWorksheet($excel_obj->charset(L('exp_tx_title'),CHARSET));
        // if($predeposit_type=='1')
        // {
        //     $name='余额提现';
        // }elseif($predeposit_type=='3'){
        //     $name='分销提现';
        // }elseif($predeposit_type=='4'){
        //     $name='商家提现';
        // }elseif($predeposit_type=='5'){
        //     $name='省代余额提现';
        // }elseif($predeposit_type=='4'){
        //     $name='代理余额提现';
        // }
        // $excel_obj->generateXML($name.'-'.date('Y-m-d-H',time()));
        // }
       
    }
    /**
     * 导出预存款明细excel
     *
     * @param array $data
     */
    private function createmxExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_member'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_ctime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_av_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_freeze_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_system'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_mshu'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['lg_member_name']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['lg_add_time']));
            if (floatval($v['lg_av_amount']) == 0){
                $tmp[] = array('data'=>'');
            } else {
                $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['lg_av_amount']));
            }
            if (floatval($v['lg_freeze_amount']) == 0){
                $tmp[] = array('data'=>'');
            } else {
                $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['lg_freeze_amount']));
            }
            $tmp[] = array('data'=>$v['lg_admin_name']);
            $tmp[] = array('data'=>$v['lg_desc']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_mx_rz'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_mx_rz'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 输出充值XML数据
     */
    public function get_xmlOp() {
        $model_pd = Model('predeposit');
        $condition = array();
        if ($_GET['member_name']) {
            $condition['pdr_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['member_id']) {
            $condition['pdr_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['pdr_payment_state'] != '') {
            $condition['pdr_payment_state'] = $_GET['pdr_payment_state'] == 1 ? 1 : 0;
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array(
            'pdr_id',
            'pdr_sn',
            'pdr_member_id',
            'pdr_member_name',
            'pdr_amount',
            'pdr_add_time',
            'pdr_payment_name',
            'pdr_trade_sn',
            'pdr_payment_state',
            'pdr_payment_time',
            'pdr_admin'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $recharge_list = $model_pd->getPdRechargeList($condition,$page,'*',$order);

        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        foreach ($recharge_list as $value) {
            $param = array();
            $operation = '';
            if ($value['pdr_payment_state'] == 0) {
                $operation .= "<a class='btn red' href=\"JavaScript:void(0);\" onclick=\"fg_delete('" . $value['pdr_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }
            $operation .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('recharge_info','查看充值编号“". $value['pdr_sn'] ."”的明细','index.php?act=predeposit&op=recharge_info&id=".$value['pdr_id']."', '640')\"><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['pdr_id'] = $value['pdr_id'];
            $param['pdr_sn'] = $value['pdr_sn'];
            $param['pdr_member_id'] = $value['pdr_member_id'];
            $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['pdr_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['pdr_member_id']).">\")'>" .$value['pdr_member_name'];
            $param['pdr_amount'] = ncPriceFormat($value['pdr_amount']);
            $param['pdr_add_time'] = date('Y-m-d H:i:s', $value['pdr_add_time']);
            $param['pdr_payment_name'] = $value['pdr_payment_name'];
            $param['pdr_trade_sn'] = $value['pdr_trade_sn'];
            $param['pdr_payment_state'] = $value['pdr_payment_state'] == '0' ? '未支付' : '已支付';
            $param['pdr_payment_time'] = $value['pdr_payment_time'] > 0 ? date('Y-m-d H:i:s', $value['pdr_payment_time']) : '';
            $param['pdr_admin'] = $value['pdr_admin'];
            if($value['pdr_type']=='1'){
                $param['pdr_type'] = '兑换云豆5%的';
            }elseif($value['pdr_type']=='2'){
                $param['pdr_type'] = '兑换云豆8%的';
            }else{
                $param['pdr_type'] = '充值';
            }
            $data['list'][$value['pdr_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 输出提现XML数据
     */
    public function get_cash_xmlOp() {

        $model_pd = Model('predeposit');

        $condition = array();

        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;

        // 起始时间
        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        // 会员名称
        if (!empty($_GET['member_name'])){
            $condition['pdc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }

        // 会员ID
        if (!empty($_GET['member_id'])){
            $condition['pdc_member_id'] = $_GET['member_id'];
        }

        // 收款人姓名
        if (!empty($_GET['user_name'])){
            $condition['pdc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }

        // 支付状态
        // <option value="0">未支付</option>
        // <option value="1">已付款</option>
        // <option value="2">财务处理中</option>
        // <option value="3">其他</option>
        if ($_GET['pdc_payment_state'] != ''){
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        }

        if ($_GET['type'] != ''){
            $condition['type'] = $_GET['type'];
        }

        // 提现类别
        // <option value="2">充值提现</option>
        // <option value="3">分销提现</option>
        // <option value="1">余额提现</option>
        // <option value="4">商家提现</option>
        // <option value="5">省代余额提现</option>
        // <option value="6">代理余额提现</option>
        if($_GET['predeposit_type']!=''){
            $condition['predeposit_type'] = $_GET['predeposit_type'];
        }


        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }

        $order = '';
        $param = array(
            'pdc_id',
            'pdc_sn',
            'pdc_member_id',
            'pdc_member_name',
            'pdc_amount',
            'pdc_add_time',
            'pdc_bank_name',
            'pdc_bank_no',
            'pdc_bank_user',
            'pdc_payment_state',
            'pdc_payment_time',
            'pdc_payment_admin',
        );

        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }

        $page = $_POST['rp'];
        $cash_list = $model_pd->getPdCashList($condition, $page, '*', $order);
        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();

        foreach ($cash_list as $value) {
            
            $row = array();

            $row['operation'] = "";

            if ($value['pdc_payment_state'] == 0) {
                $row['operation'] .= "<a class='btn red' href=\"javascript:void(0)\" onclick=\"fg_delete('" 
                . $value['pdc_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }

            $row['operation'] .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('cash_info','查看提现编号“"
            . $value['pdc_sn'] ."”的明细', 'index.php?act=predeposit&op=pd_cash_view&id="
            . $value['pdc_id'] ."', 640)\" ><i class='fa fa-list-alt'></i>查看</a>";

            $row['pdc_id'] = $value['pdc_id'];
            $row['pdc_sn'] = $value['pdc_sn'];
            $row['pdc_member_id'] = $value['pdc_member_id'];
            $row['pdc_member_name'] = "<img src=".getMemberAvatarForID($value['pdc_member_id'])
                ." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src="
                .getMemberAvatarForID($value['pdc_member_id']).">\")'>" .$value['pdc_member_name'];

            $row['pdc_amount'] = ncPriceFormat($value['pdc_amount']);
            $row['pdc_add_time'] = date('Y-m-d', $value['pdc_add_time']);
            $row['pdc_bank_name'] = $value['pdc_bank_name'];
            $row['pdc_bank_no'] = $value['pdc_bank_no'];
            $row['pdc_bank_user'] = $value['pdc_bank_user'];
            //$row['pdc_payment_state'] = $value['pdc_payment_state'] == '0' ? '未支付' : '已支付';


            // if ($row['pdc_payment_state'] == '1') {
            //     $row['yb_error_info'] = mb_convert_encoding($value['yb_error_info'], 'utf-8', 'gbk') . '~~~' .$row['pdc_payment_state'];
            // } else {
                
            // }

            
            $pdc_payment_state = $value['pdc_payment_state'];

            if($value['pdc_payment_state'] == '0'){
                $row['pdc_payment_state'] ='未支付';
            } elseif($value['pdc_payment_state'] == '1'){
                $row['pdc_payment_state'] ='已支付';       
            } elseif($value['pdc_payment_state'] == '2'){
                $row['pdc_payment_state'] ='财务处理中'; 
            } elseif($value['pdc_payment_state'] == '4'){
                $row['pdc_payment_state'] ='待核准'; // 已初审，待核准
            } elseif($value['pdc_payment_state'] == '5'){
                $row['pdc_payment_state'] ='待打款'; // 已核准，待打款
            } elseif($value['pdc_payment_state'] == '6'){
                $row['pdc_payment_state'] ='打款失败'; // 打款失败
            }

            $row['pdc_payment_time'] = $value['pdc_payment_time'] > 0 ? date('Y-m-d H:i:s', $value['pdc_payment_time']) : '';
            $row['pdc_payment_admin'] = $value['pdc_payment_admin'];

            switch ($value['type']) {
                case 'ybzf':
                    $row['type'] = '易宝';
                    break;
                case 'tlzf':
                    $row['type'] = '通联';
                    break;
                default:
                    $row['type'] = '其它';
                    break;
            }
            

            if ($pdc_payment_state == '1') {
                $row['yb_error_info'] = $value['yb_error_info']; //mb_convert_encoding($value['yb_error_info'], 'gbk', 'utf-8');
            } else {
                $row['yb_error_info'] = $value['yb_error_info'];
            }

            $data['list'][$value['pdc_id']] = $row;
        }
      
        // print_r($data); exit;

        echo Tpl::flexigridXML($data);
        exit();
    }

    /**
     * 输出预存款明细XML数据
     */
    public function get_log_xmlOp() {
        $model_pd = Model('predeposit');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('lg_id', 'lg_member_id', 'lg_member_name', 'lg_av_amount', 'lg_freeze_amount', 'lg_add_time', 'lg_desc', 'lg_admin_name');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $log_list = $model_pd->getPdLogList($condition,$page,'*',$order);
        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        foreach ($log_list as $value) {
            $param = array();
            $param['operation'] = "--";
            $param['lg_id'] = $value['lg_id'];
            $param['lg_member_id'] = $value['lg_member_id'];
            $param['lg_member_name'] = "<img src=".getMemberAvatarForID($value['lg_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['lg_member_id']).">\")'>" .$value['lg_member_name'];
            $param['lg_av_amount'] = ncPriceFormat($value['lg_av_amount']);
            $param['lg_freeze_amount'] = ncPriceFormat($value['lg_freeze_amount']);
            $param['lg_add_time'] = date('Y-m-d', $value['lg_add_time']);
            $param['lg_desc'] = $value['lg_desc'];
            $param['lg_admin_name'] = $value['lg_admin_name'];
            $data['list'][$value['lg_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    
    //账户明细
    public function account_infoOp(){

        Tpl::setDirquna('shop');
        Tpl::showpage('account.list');

    }
    public function accountt_xmlOp(){
        $model_pd = Model('predeposit');
        $pd_cash=Model('pd_cash');
        $condition = array(); 
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;  
        $page = $_POST['rp'];
        
        if(!is_null($end_unixtime)&&!is_null($start_unixtime)&&$_GET['predeposit_type']!=''){  
        $condition['predeposit_type']=$_GET['predeposit_type'];  
        $condition['pdc_payment_state']='2';
        $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        $cash_Total = $model_pd->getPdCashTotal($condition);
        
        $param = array();
        $data = array(); 
        $cash_sum=$model_pd->getPdCashMoneySum($condition,'pdc_amount');  //本次计算总额
        $cash_count=$model_pd->getPdcaseCount($condition);               //本次总记录数
        if(intval($cash_count)!=0){
        foreach($cash_Total as $k=>$v){
            $aid[]=$v['pdc_id'];
            }
        
         $allid=json_encode($aid);
         $data['now_page'] = $pd_cash->shownowpage();
         $data['total_num'] = $pd_cash->gettotalnum();
         $param['operation'] .= "<a class='btn green' onclick='chuli()' ><i class='fa fa-trash-o'></i>处理</a>";
         $value['lg_id']='1';
         $param['pdr_id'] = "<input type=text value=".$allid." id=allid></input>";
         $param['pdr_member_id'] = '所有会员ID';
         $param['pdr_member_name'] = '所有会员姓名';
         $param['pdr_amount'] = $cash_sum;
         $param['pdr_add_time'] = date('Y-m-d', $start_unixtime)."-". date('Y-m-d', $end_unixtime);
         $param['pdr_desc'] = '当日总充值金额'.$cash_sum.',当日总充值笔数:'.$cash_count;
         $data['list'][$value['lg_id']] = $param;
         }
        }
         echo Tpl::flexigridXML($data);exit();
    }
    public function account_xmlOp() {
        
        $pd_log=Model('pd_log');
        $pd_cash=Model('pd_cash');
        $rcb_log=Model('rcb_log');
        $point_log=Model('points_log');
        $member=Model('member');
        $order=Model('orders');
        $pd_recharge=Model('pd_recharge');
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;                      
        $page = $_POST['rp'];
        if ($_GET['pdr_payment_state'] != '') {
            if($_GET['pdr_payment_state']=='0'){      //充值记录
                if(!empty($_GET['member_id'])){
                    $where['pdr_member_id']= $_GET['member_id'];
                }
                if(!empty($_GET['member_name'])){
                    $where['pdr_member_name']= $_GET['member_name'];
                }                
                $where['pdr_payment_state']='1';
                $where['pdr_type']='NULL';
                $where['pdr_payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
               
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount'); 
                    $count1=$pd_recharge->where($where)->count('pdr_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总充值金额'.$count.',当日总充值笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
                // print_r($data);
                       
            }elseif($_GET['pdr_payment_state']=='1'){  //消费记录
                $where['buyer_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['buyer_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['order_state']=array('gt','10');
                $where['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                 
                $param = array();
                $data = array();
                $value['lg_id']='1';
                $data['now_page'] = $order->shownowpage();
                $data['total_num'] = $order->gettotalnum();
                $data['count']=$order->where($where)->sum('order_amount');  
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$order->where($where)->sum('order_amount');   
                    $count1=$order->where($where)->count('order_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总消费金额'.$count.',当日总消费笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;       
                }
               
            }elseif($_GET['pdr_payment_state']=='10'){  //会员激活记录
                $where['member_level']='1';
                $where['member_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $data['count']=$member->where($where)->count('member_id');
                $data['count']=$data['count']*250;
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['member_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    // $count=$pd_log->where($where)->sum('lg_av_amount'); 
                    $count1=$member->where($where)->count('member_id');
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count1;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总激活人数'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }           
               
            }elseif($_GET['pdr_payment_state']=='17'){  //端口激活记录
                $where['member_level']='2';
                $where['member_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $data['count']=$member->where($where)->count('member_id');
                // $data['count']=$data['count']*250;
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['member_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    // $count=$pd_log->where($where)->sum('lg_av_amount'); 
                    $count1=$member->where($where)->count('member_id');
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count1;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总激活端口人数'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }           
               
            }elseif($_GET['pdr_payment_state']=='2'){          //分成记录
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_desc']=array(array('like','%代理提成%'),array('like','%消费提成%'),'or');
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_log->shownowpage();
                $data['total_num'] = $pd_log->gettotalnum();
                $data['count']=$pd_log->where($where)->sum('lg_av_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');  
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总分成金额'.$count.',当日总分成笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='3'){       //消费分成
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='chief';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_log->shownowpage();
                $data['total_num'] = $pd_log->gettotalnum();
                $data['count']=$pd_log->where($where)->sum('lg_av_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');
                    $count1=$pd_log->where($where)->count('lg_av_amount');    
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总消费分成金额:'.$count.',当日总消费分成笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='4'){       //分销分成
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='distribution';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_log->shownowpage();
                $data['total_num'] = $pd_log->gettotalnum();
                $data['count']=$pd_log->where($where)->sum('lg_av_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总分销分成金额'.$count.',当日总消费分成笔数:'.$count1;;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='5'){       //商家分成
                $where['member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['type']='seller';
                $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $rcb_log_info=$rcb_log->where($where)->page($page)->select();
            }elseif($_GET['pdr_payment_state']=='6'){       //充值提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='2';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总充值提现金额'.$count.',当日总充值提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='7'){       //余额提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='1';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总余额提现金额'.$count.',当日总余额提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='8'){       //分销提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='3';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
               $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总分销提现金额'.$count.',当日总分销提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='9'){       //商家提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='4';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总商家提现金额'.$count.',当日总商家提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='11'){       //充值余额转云豆
                $where['pl_memberid']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pl_membername']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pl_stage']='buy_points';
                $where['pl_addtime'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $point_log->shownowpage();
                $data['total_num'] = $point_log->gettotalnum();
                $data['count']=$point_log->where($where)->sum('pl_counter');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pl_addtime']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$point_log->where($where)->sum('pl_points'); 
                    $count1=$point_log->where($where)->count('pl_points');
                    $count2= $point_log->where($where)->sum('pl_counter');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日充值转云豆，云豆总数为：'.$count.',当日总充值转云豆笔数:'.$count1.',当日总充值转云豆金额:'.$count2;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='12'){       //充值手续费
                $where['pl_memberid']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pl_membername']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pl_stage']='rechart';
                $where['pl_addtime'] = array('time',array($start_unixtime,$end_unixtime));

                $param = array();
                $data = array(); 
                $data['now_page'] = $point_log->shownowpage();
                $data['total_num'] = $point_log->gettotalnum();
                $data['count']=$point_log->where($where)->sum('pl_counter');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pl_addtime']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$point_log->where($where)->sum('pl_counter'); 
                    $count1=$point_log->where($where)->count('pl_counter');

                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日充值手续费总数为：'.$count.',当日总充值手续费笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='13'){       //5%购买云豆
                $where['pdr_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdr_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pdr_type']=1;
                $where['pdr_payment_state']=1;
                $where['pdr_payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount'); 
                    $count1=$pd_recharge->where($where)->count('pdr_amount');

                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日5%购买总金额为：'.$count.',当日5%购买总笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='14'){       //8%购买云豆
                $where['pdr_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdr_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pdr_type']=2;
                $where['pdr_payment_state']=1;
                $where['pdr_payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount'); 
                    $count1=$pd_recharge->where($where)->count('pdr_amount');

                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日8%购买总金额为：'.$count.',当日8%购买总笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='15'){       //省代余额提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='5';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总省代余额提现金额'.$count.',当日总省代余额提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='16'){       //代理余额提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='6';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_cash->shownowpage();
                $data['total_num'] = $pd_cash->gettotalnum();
                $data['count']=$pd_cash->where($where)->sum('pdc_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdc_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_cash->where($where)->sum('pdc_amount'); 
                    $count1=$pd_cash->where($where)->count('pdc_amount');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总代理余额提现金额'.$count.',当日总代理余额提现笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='24'){          //卡分润
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_desc']=array(array('like','%注册分润%'),array('like','%注册分润提成可提现%'),'or');
                $where['lg_type']='agent';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_log->shownowpage();
                $data['total_num'] = $pd_log->gettotalnum();
                $data['count']=$pd_log->where($where)->sum('lg_av_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');  
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日卡分润分成金额'.$count.',当日卡分润分成笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='18'){          //端口各级代理分成
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='port_split';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_log->shownowpage();
                $data['total_num'] = $pd_log->gettotalnum();
                $data['count']=$pd_log->where($where)->sum('lg_av_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');  
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日升级端口代理分成金额'.$count.',当日升级端口代理分成笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='19'){          //随行付每日充值金额
                $where['pdr_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdr_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pdr_payment_name']='随行付';
                $where['pdr_payment_state']='1';
                $where['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount');  
                    $count1=$pd_recharge->where($where)->count('pdr_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '随行付当日充值金额'.$count.',随行付当日充值笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='20'){          //随行付每日购买商品金额
                $where['buyer_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['buyer_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['payment_code']='vbill';
                $where['order_state']=array('gt','10');
                $where['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $order->shownowpage();
                $data['total_num'] = $order->gettotalnum();
                $data['count']=$order->where($where)->sum('order_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$order->where($where)->sum('order_amount');  
                    $count1=$order->where($where)->count('order_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '随行付当日购买商品金额'.$count.',随行付当日购买商品笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='21'){          //银联支付每日充值金额
                $where['pdr_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdr_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pdr_payment_name']='银联支付';
                $where['pdr_payment_state']='1';
                $where['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount');  
                    $count1=$pd_recharge->where($where)->count('pdr_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '银联支付当日充值金额'.$count.',银联支付当日充值笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='22'){          //银联支付每日购买商品金额
                $where['buyer_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['buyer_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['payment_code']='unionpay';
                $where['order_state']=array('gt','10');
                $where['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $order->shownowpage();
                $data['total_num'] = $order->gettotalnum();
                $data['count']=$order->where($where)->sum('order_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$order->where($where)->sum('order_amount');  
                    $count1=$order->where($where)->count('order_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '银联支付当日购买商品金额'.$count.',银联支付当日购买商品笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='23'){          //乐支付每日充值金额
                $where['pdr_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdr_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['pdr_payment_name']='乐支付';
                $where['pdr_payment_state']='1';
                $where['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $data['now_page'] = $pd_recharge->shownowpage();
                $data['total_num'] = $pd_recharge->gettotalnum();
                $data['count']=$pd_recharge->where($where)->sum('pdr_amount');
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['pdr_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_recharge->where($where)->sum('pdr_amount');  
                    $count1=$pd_recharge->where($where)->count('pdr_amount');   
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '银联支付当日充值金额'.$count.',银联支付当日充值笔数:'.$count1;
                    $data['list'][$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }
        }
        // if($_GET['pdr_payment_state']=='5'){
        //     $data['now_page'] = $rcb_log->shownowpage();
        //     $data['total_num'] = $rcb_log->gettotalnum();
        //     foreach ($rcb_log_info as $value) {
        //         $param = array();
        //         $param['pdr_id'] = $value['id'];
        //         $param['pdr_member_id'] = $value['member_id'];
        //         $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['member_id']).">\")'>" .$value['member_name'];
        //         $param['pdr_amount'] = ncPriceFormat($value['freeze_amount']);
        //         $param['pdr_add_time'] = date('Y-m-d', $value['add_time']);
        //         $param['pdr_desc'] = $value['description'];
        //         $data['list'][$value['id']] = $param;
        //     }

        // }elseif($_GET['pdr_payment_state']=='6' || $_GET['pdr_payment_state']=='7' || $_GET['pdr_payment_state']=='8' || $_GET['pdr_payment_state']=='9'){
        //     $data['now_page'] = $pd_cash->shownowpage();
        //     $data['total_num'] = $pd_cash->gettotalnum();
        //     foreach ($pd_cash_info as $value) {
        //         $param = array();
        //         $param['pdr_id'] = $value['pdc_id'];
        //         $param['pdr_member_id'] = $value['pdc_member_id'];
        //         $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['pdc_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['pdc_member_id']).">\")'>" .$value['pdc_member_name'];
        //         $param['pdr_amount'] = ncPriceFormat($value['pdc_amount']);
        //         $param['pdr_add_time'] = date('Y-m-d', $value['pdc_add_time']);
        //         switch ($value['predeposit_type']) {
        //             case '1':
        //                 $value['predeposit_type']='余额提现';
        //                 break;
        //             case '2':
        //                 $value['predeposit_type']='充值提现';
        //                 break;
        //             case '3':
        //                 $value['predeposit_type']='分销提现';
        //                 break;
        //             default:
        //                 $value['predeposit_type']='商家提现';
        //                 break;
        //         }
        //         $param['pdr_desc'] = $value['predeposit_type'];
        //         $data['list'][$value['pdc_id']] = $param;
        //     }

        // }

        echo Tpl::flexigridXML($data);exit();
    }
    
    /**
     * 导出账户明细记录
     *
     */
    public function export_step2Op(){
        $pd_log=Model('pd_log');
        $pd_cash=Model('pd_cash');
        $rcb_log=Model('rcb_log');
        $order=Model('orders');
        $member=Model('member');
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($_GET['pdr_payment_state'] != '') {
            if($_GET['pdr_payment_state']=='0' || $_GET['pdr_payment_state']=='1' || $_GET['pdr_payment_state']=='2' || $_GET['pdr_payment_state']=='3' || $_GET['pdr_payment_state']=='4' || $_GET['pdr_payment_state']=='10'){      //充值记录
                $count=($end_unixtime-$start_unixtime)/86400;                
            }
            elseif($_GET['pdr_payment_state']=='5'){       //商家分成
                $where['member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['type']='seller';
                $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $count=$rcb_log->where($where)->count();
            }elseif($_GET['pdr_payment_state']=='6'){       //充值提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='2';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $count=$pd_cash->where($where)->count();
            }elseif($_GET['pdr_payment_state']=='7'){       //余额提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='1';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $count=$pd_cash->where($where)->count();
            }elseif($_GET['pdr_payment_state']=='8'){       //分销提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='3';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $count=$pd_cash->where($where)->count();
            }elseif($_GET['pdr_payment_state']=='9'){       //商家提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='4';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $count=$pd_cash->where($where)->count();
            }
        }

        if (!is_numeric($_GET['curpage'])){
           
            $array = array();
           
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=predeposit&op=predeposit');
                Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');

        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            if ($_GET['pdr_payment_state'] != '') {
            if($_GET['pdr_payment_state']=='0'){      //充值记录
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='recharge';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount'); 
                    $count1=$pd_log->where($where)->count('lg_av_amount');  
                    $param['pdr_id'] = $value['lg_id'];
                    $param['pdr_member_id'] = isset($_GET['member_id'])?$_GET['member_id']:'';
                    $param['pdr_member_name'] = isset($_GET['member_name'])?$_GET['member_name']:'';
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = $start_unixtime;
                    $param['pdr_desc'] = '当日总充值金额'.$count.',当日总充值笔数:'.$count1;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;
                }             
            }elseif($_GET['pdr_payment_state']=='1'){  //消费记录
                $where['buyer_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['buyer_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['order_state']=array('gt','10');
                $where['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
                 
                $param = array();
                $data = array();
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['payment_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$order->where($where)->sum('order_amount');   
                    $count1=$order->where($where)->count('order_amount');  
                    $param['pdr_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_name'] = isset($_GET['member_name'])?$_GET['member_name']:false;
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = $start_unixtime;
                    $param['pdr_desc'] = '当日总消费金额'.$count.',当日总消费笔数:'.$count1;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;       
                }          
            }elseif($_GET['pdr_payment_state']=='10'){          //会员激活记录
                $where['member_level']=array('gt','0');
                $where['member_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array();
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['member_level']=array('gt','0');
                    $where['member_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    // $count=$pd_log->where($where)->sum('lg_av_amount'); 
                    $count1=$member->where($where)->count('member_id');  
                    $param['pdr_id'] = $_GET['member_id'];
                    $param['pdr_member_id'] = $_GET['member_id'];
                    $param['pdr_member_name'] = $_GET['member_name'];
                    $param['pdr_amount'] = $count1;
                    $param['pdr_add_time'] = $start_unixtime;
                    $param['pdr_desc'] = '当日总激活人数'.$count1;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='2'){          //分成记录
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_desc']=array(array('like','%代理提成%'),array('like','%每日赠送%'),array('like','%消费提成%'),'or');
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');  
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_name'] =isset($_GET['member_name'])?$_GET['member_name']:false;
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总分成金额'.$count.',当日总分成笔数:'.$count1;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
               
            }elseif($_GET['pdr_payment_state']=='3'){       //消费分成
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='chief';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');
                    $count1=$pd_log->where($where)->count('lg_av_amount');    
                    $param['pdr_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_name'] = isset($_GET['member_name'])?$_GET['member_name']:false;
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总消费分成金额:'.$count.',当日总消费分成笔数:'.$count1;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='4'){       //分销分成
                $where['lg_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['lg_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['lg_type']='distribution';
                $where['lg_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $param = array();
                $data = array(); 
                $value['lg_id']='1';
                for($start_unixtime;$start_unixtime<=$end_unixtime;$start_unixtime=$start_unixtime+86400){
                    $where['lg_add_time']=array('between',array($start_unixtime,$start_unixtime+86400));
                    $count=$pd_log->where($where)->sum('lg_av_amount');
                    $count1=$pd_log->where($where)->count('lg_av_amount');   
                    $param['pdr_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_id'] = isset($_GET['member_id'])?$_GET['member_id']:false;
                    $param['pdr_member_name'] = isset($_GET['member_name'])?$_GET['member_name']:false;
                    $param['pdr_amount'] = $count;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime);
                    $param['pdr_desc'] = '当日总分销分成金额'.$count.',当日总消费分成笔数:'.$count1;;
                    $data[$value['lg_id']] = $param;
                    $value['lg_id']++;
                }
            }elseif($_GET['pdr_payment_state']=='5'){       //商家分成
                $where['member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['type']='seller';
                $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $rcb_log_info=$rcb_log->where($where)->page($page)->select();
            }elseif($_GET['pdr_payment_state']=='6'){       //充值提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='2';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $pd_cash_info=$pd_cash->where($where)->page($page)->select();
            }elseif($_GET['pdr_payment_state']=='7'){       //余额提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='1';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $pd_cash_info=$pd_cash->where($where)->page($page)->select();
            }elseif($_GET['pdr_payment_state']=='8'){       //分销提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='3';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $pd_cash_info=$pd_cash->where($where)->page($page)->select();
            }elseif($_GET['pdr_payment_state']=='9'){       //商家提现
                $where['pdc_member_id']= array('like', '%' . $_GET['member_id'] . '%');
                $where['pdc_member_name']= array('like', '%' . $_GET['member_name'] . '%');
                $where['predeposit_type']='4';
                $where['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
                $pd_cash_info=$pd_cash->where($where)->page($page)->select();
            }
        }
        
        if($_GET['pdr_payment_state']=='5'){
            foreach ($rcb_log_info as $value) {
                $param = array();
                $param['pdr_id'] = $value['id'];
                $param['pdr_member_id'] = $value['member_id'];
                $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['member_id']).">\")'>" .$value['member_name'];
                $param['pdr_amount'] = ncPriceFormat($value['freeze_amount']);
                $param['pdr_add_time'] = date('Y-m-d', $value['add_time']);
                $param['pdr_desc'] = $value['description'];
                $data[$value['id']] = $param;
            }

        }elseif($_GET['pdr_payment_state']=='6' || $_GET['pdr_payment_state']=='7' || $_GET['pdr_payment_state']=='8' || $_GET['pdr_payment_state']=='9'){
            foreach ($pd_cash_info as $value) {
                $param = array();
                $param['pdr_id'] = $value['pdc_id'];
                $param['pdr_member_id'] = $value['pdc_member_id'];
                $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['pdc_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['pdc_member_id']).">\")'>" .$value['pdc_member_name'];
                $param['pdr_amount'] = ncPriceFormat($value['pdc_amount']);
                $param['pdr_add_time'] = date('Y-m-d', $value['pdc_add_time']);
                switch ($value['predeposit_type']) {
                    case '1':
                        $value['predeposit_type']='余额提现';
                        break;
                    case '2':
                        $value['predeposit_type']='充值提现';
                        break;
                    case '3':
                        $value['predeposit_type']='分销提现';
                        break;
                    default:
                        $value['predeposit_type']='商家提现';
                        break;
                }
                $param['pdr_desc'] = $value['predeposit_type'];
                $data[$value['pdc_id']] = $param;
            }
        }   
            $this->createExcel1($data);
        }
    }

    /**
     * 生成导出账户明细excel
     *
     * @param array $data
     */
    private function createExcel1($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'金额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'说明');
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['pdr_member_id']);
            $tmp[] = array('data'=>$v['pdr_member_name']);
            $tmp[] = array('data'=>$v['pdr_amount']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdr_add_time']));
            $tmp[] = array('data'=>$v['pdr_desc']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_yc_yckcz'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_yc_yckcz'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }
    
    //代理团队人数
    public function agent_countOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('agent.count');
    }
    
    public function agent_xmlOp(){
        $member=Model('member');
        $area=Model('area');
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        $param=array();
        $data=array();
        if ($_GET['pdr_payment_state'] != '') {
            if($_GET['pdr_payment_state']=='1'){
                $where['member_level']='5';
                $member_proviced=$member->where($where)->page($_REQUEST['rp'])->select();
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_provinceid'=>$value['member_provinceid'],'member_level'=>'1','member_time'=>array('between',array($start_unixtime,$end_unixtime))))->count();
                    $area_info=$area->where(array('area_id'=>$value['member_provinceid']))->find();                       
                    $param['pdr_id'] = $key;
                    $param['pdr_member_id'] = $value['member_id'];
                    $param['pdr_member_name'] = $area_info['area_name'];
                    $param['pdr_amount'] = $member_info;
                    $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                    $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$member_info;
                    $data['list'][$key] = $param;  
                } 
            }elseif($_GET['pdr_payment_state']=='2'){
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_cityid,count(*) as pannum')->group('member_cityid')->page($_REQUEST['rp'])->order('pannum desc')->select();
                $data['now_page'] = $member->shownowpage();
                $tmp = $member->where($where)->field('member_cityid,count(*) as pannum')->group('member_cityid')->order('pannum desc')->select();
                $data['total_num'] = count($tmp);
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_cityid'=>$value['member_cityid'],'member_level'=>4))->find();
                    if(empty($value['member_cityid']) || empty($member_info)){
                       continue;
                    }else{                        
                        $area_info=$area->where(array('area_id'=>$value['member_cityid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = $area_info['area_name'];
                        $param['pdr_amount'] = $value['pannum'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$value['pannum'];
                        $data['list'][$key] = $param;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='3'){

                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_areaid,count(*) as pannum')->group('member_areaid')->page($_REQUEST['rp'])->order('pannum desc')->select();
                $data['now_page'] = $member->shownowpage();
                $tmp = $member->where($where)->field('member_areaid,count(*) as pannum')->group('member_areaid')->order('pannum desc')->select();
                $data['total_num'] = count($tmp);
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_areaid'=>$value['member_areaid'],'member_level'=>3))->find();
                    if(empty($value['member_areaid']) || empty($member_info)){
                       continue;
                    }else{                        
                        $area_info=$area->where(array('area_id'=>$value['member_areaid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = $area_info['area_name'];
                        $param['pdr_amount'] = $value['pannum'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$value['pannum'];
                        $data['list'][$key] = $param;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='4'){
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('portid,count(*) as pannum')->group('portid')->page($_REQUEST['rp'])->order('pannum desc')->select();
                $data['now_page'] = $member->shownowpage();
                $tmp = $member->where($where)->field('portid,count(*) as pannum')->group('portid')->order('pannum desc')->select();
                $data['total_num'] = count($tmp);
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('portid'=>$value['portid'],'member_level'=>2))->find();
                    if(empty($value['portid']) || empty($member_info)){
                        $data['total_num'] -= 1;
                        continue;
                    }else{                        
                        // $area_info=$area->where(array('area_id'=>$value['member_cityid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = '端口：'.$member_info['member_id'];
                        $param['pdr_amount'] = $value['pannum'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = '端口：'.$member_info['member_id'].'该段时间内总发展人数:'.$value['count(*)'];
                        $data['list'][$key] = $param;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='5'){
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_pid,count(*) as pannum')->group('member_pid')->page($_REQUEST['rp'])->order('pannum desc')->select();
                $data['now_page']  = $member->shownowpage();
                $tmp = $member->where($where)->field('member_pid,count(*) as pannum')->group('member_pid')->order('pannum desc')->select();
                $data['total_num'] = count($tmp);
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_pid'=>$value['member_pid'],'member_level'=>1))->find();
                    if(empty($value['member_pid']) || empty($member_info)){
                       continue;
                    }else{                        
                        // $area_info=$area->where(array('area_id'=>$value['member_cityid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = '会员：'.$member_info['member_id'];
                        $param['pdr_amount'] = $value['pannum'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = '会员：'.$member_info['member_id'].'该段时间内总发展人数:'.$value['pannum'];
                        $data['list'][$key] = $param;
                    }
                    
                }
                
            }
        }
        echo Tpl::flexigridXML($data);exit();
    }
    
    /**
     * 导出团队新增人数记录
     *
     */
    public function export_agentOp(){
        $member=Model('member');
        $area=Model('area');
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        $param=array();
        $data=array();
        $id=1;
        if ($_GET['pdr_payment_state'] != '') {
            if($_GET['pdr_payment_state']=='1'){
                
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                 $pagesize = $_POST['rp'];
                // $where['member_time']=array('between',array($start_unixtime,$end_unixtime));
                $where['member_level']='5';
                $member_proviced=$member->where($where)->page($pagesize)->select();
               
                foreach ($member_proviced as $key => $value) {
                        $member_info=$member->where(array('member_provinceid'=>$value['member_provinceid'],'member_level'=>'1','member_time'=>array('time',array($start_unixtime,$end_unixtime))))->count();
                           
                        $area_info=$area->where(array('area_id'=>$value['member_provinceid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $value['member_id'];
                        $param['pdr_member_name'] = $area_info['area_name'];
                        $param['pdr_amount'] = $member_info;
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$member_info;
                        $data[$id] = $param;
                        $id++;
                  
                    
                }
            }elseif($_GET['pdr_payment_state']=='2'){
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_cityid,count(*)')->group('member_cityid')->select();
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_cityid'=>$value['member_cityid'],'member_level'=>4))->find();
                    if(empty($value['member_cityid']) || empty($member_info)){
                       continue;
                    }else{                        
                        $area_info=$area->where(array('area_id'=>$value['member_cityid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = $area_info['area_name'];
                        $param['pdr_amount'] = $value['count(*)'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$value['count(*)'];
                        $data[$id] = $param;
                        $id++;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='3'){
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_areaid,count(*)')->group('member_areaid')->select();
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_areaid'=>$value['member_areaid'],'member_level'=>3))->find();
                    if(empty($value['member_areaid']) || empty($member_info)){
                       continue;
                    }else{                        
                        $area_info=$area->where(array('area_id'=>$value['member_areaid']))->find();                       
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = $area_info['area_name'];
                        $param['pdr_amount'] = $value['count(*)'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = $area_info['area_name'].'该段时间内总发展人数:'.$value['count(*)'];
                        $data[$id] = $param;
                        $id++;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='4'){
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('portid,count(*)')->group('portid')->select();
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('portid'=>$value['portid'],'member_level'=>2))->find();
                    if(empty($value['portid']) || empty($member_info)){
                       continue;
                    }else{                        
                            
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = '端口：'.$member_info['member_id'];
                        $param['pdr_amount'] = $value['count(*)'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = '端口：'.$member_info['member_id'].'该段时间内总发展人数:'.$value['count(*)'];
                        $data[$id] = $param;
                        $id++;
                    }
                    
                }
                
            }elseif($_GET['pdr_payment_state']=='5'){
                $data['now_page'] = $member->shownowpage();
                $data['total_num'] = $member->gettotalnum();
                $where['member_time']=array('time',array($start_unixtime,$end_unixtime));
                $member_proviced=$member->where($where)->field('member_pid,count(*)')->group('member_pid')->select();
                foreach ($member_proviced as $key => $value) {
                    $member_info=$member->where(array('member_pid'=>$value['member_pid'],'member_level'=>1))->find();
                    if(empty($value['member_pid']) || empty($member_info)){
                       continue;
                    }else{                        
                                             
                        $param['pdr_id'] = $key;
                        $param['pdr_member_id'] = $member_info['member_id'];
                        $param['pdr_member_name'] = '会员：'.$member_info['member_id'];
                        $param['pdr_amount'] = $value['count(*)'];
                        $param['pdr_add_time'] = date('Y-m-d', $start_unixtime).'到'.date('Y-m-d',$end_unixtime);
                        $param['pdr_desc'] = '会员：'.$member_info['member_id'].'该段时间内总发展人数:'.$value['count(*)'];
                        $data[$id] = $param;
                        $id++;
                    }
                    
                }
                
            }
            $this->agentExcel1($data);
        }
    }

    /**
     * 生成导出代理团队人数excel
     *
     * @param array $data
     */
    private function agentExcel1($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'代理/会员');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'发展人数（个）');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'说明');
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['pdr_member_id']);
            $tmp[] = array('data'=>$v['pdr_member_name']);
            $tmp[] = array('data'=>$v['pdr_amount']);
            $tmp[] = array('data'=>$v['pdr_add_time']);
            $tmp[] = array('data'=>$v['pdr_desc']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('团队人数统计',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('团队人数统计',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /*新增财务批量管理方法*/
    public function plcld_cash_listOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('account.listt');
    }
    
    public function pd_clsuoyouOp(){
        $model_pd = Model('predeposit');
         $mobile_phone = !empty($_POST['mobile_field']) ? $_POST['mobile_field'] : '';
          $new_ks=str_replace("[","",$mobile_phone);
          $new_kss=str_replace("]","",$new_ks);
         $pieces = explode(",", $new_kss); 
          foreach($pieces as $k=>$v){
              $value2 = preg_replace('/\D/s', '',$v); 
              $condition['pdc_id']=$value2;
              $data = array(
              'pdc_payment_state'=>'1'
                );
         $model_pd->editPdCash($data,$condition);
              }
                echo 1;
              exit();
    }
    
    /**
     * 20170516潘丙福添加数据恢复功能
     */
    public function datarecoveryOp() {
        $condition = array();
        if (empty($_GET['stime']) | empty($_GET['etime']) | empty($_GET['predeposit_type']) | empty($_GET['pdc_payment_state'])) {
            echo 1;
            exit;
        }

        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['predeposit_type'])){
            $condition['predeposit_type'] =$_GET['predeposit_type'];
        }
        if (!empty($_GET['pdc_payment_state'])){
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        }

        $panPredepositModel = Model('predeposit');

        $panArray = array('pdc_payment_state'=>'0');

        if ($panPredepositModel->table('pd_cash')->where($condition)->update($panArray)) {

            $condition['pdc_payment_state'] = '0';
            echo $panPredepositModel->table('pd_cash')->where($condition)->count();
        }
        
    }
    //生成随机数
   public function randomkeys($length)   
    {   
        $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
        for($i=0;$i<$length;$i++)   
        {   
            $key .= $pattern{mt_rand(0,35)};    
        }   
        return $key;   
    }
    public function getBankInfo($card)
    {
        $bankList = include __DIR__ . '/return.banklist.php';

        $card_8 = substr($card, 0, 8);  
        if (isset($bankList[$card_8])) {  
            return $bankList[$card_8];  
        }

        $card_6 = substr($card, 0, 6);  
        if (isset($bankList[$card_6])) {  
            return $bankList[$card_6];  
        }

        $card_5 = substr($card, 0, 5);  
        if (isset($bankList[$card_5])) {  
            return $bankList[$card_5];   
        }

        $card_4 = substr($card, 0, 4);  
        if (isset($bankList[$card_4])) {  
            return $bankList[$card_4];  
        }

        return '银行卡';

    }
//各级代理冻结代理金额
    public function agent_frozenOp(){
        Tpl::setDirquna('shop');
        Tpl::showpage('agent_frozen');
    }
    /**
     * 输出XML数据
     */
    public function agentfrozen_xmlOp() {
        $model_member = Model('member');
        $statistics=Model('statistics');
        $area=Model('area');
        // $member_grade = $model_member->getMemberGradeArr();
        $province=array();
        $condition = array();
        if($_GET['member_id']!=''){

            $condition['member_id'] = $_GET['member_id'];
        }
        if($_GET['member_name']!=''){
            $condition['member_name'] =array('like', '%' . $_GET['member_name'] . '%');
        }

        if($_GET['query_year']!='' && $_GET['query_month']!=''){
            $condition['lg_add_time']=strtotime($_GET['query_year'].'-'.$_GET['query_month']);
        }
        $on = 'member.member_provinceid=area.area_id';
        $member_info=Model()->table('member,area')->on($on)->where(array('member_level'=>'5'))->select();
        // $member_info=Model()->$model_member->where(array('member_level'=>'5'))->select();
        $member_id='';
        foreach ($member_info as $key => $value) {
            $member_id=$member_id.','.$value['member_id'];
            $area_id=$area_id.','.$value['member_provinceid'];
            $province[$value['member_id']]=$value['member_provinceid'];
            $area_name[$value['member_id']]=$value['area_name'];
        }
        $member_id=trim($member_id,',');
        
        
        $statistics_info=$statistics->where(array('lg_member_id'=>array('in',$member_id),'lg_add_time'=>$condition['lg_add_time'],'lg_type'=>'freeze_statis'))->select();
        // print_r($statistics_info);
        $data = array();
        $data['now_page'] = $model_member->shownowpage();
        $data['total_num'] = $model_member->gettotalnum();
        foreach ($statistics_info as $value) {
            $param = array();
            $param['operation'] = "<a class='btn blue' href='index.php?act=predeposit&op=export_frozen&member_id=" . $value['lg_member_id'] . "&addtime=" . $value['lg_add_time'] . "&member_proviced=".$province[$value['lg_member_id']]."'><i class='fa fa-pencil-square-o'></i>导出</a>";
            $param['member_id'] = $value['lg_member_id'];
            $param['member_name'] = $area_name[$value['lg_member_id']];
            $data['list'][$value['lg_member_id']] = $param;
        }
        // print_r($data);
        echo Tpl::flexigridXML($data);exit();
    }
    //导出代理冻结钱包
    public function export_frozenOp(){
         header("Content-type: text/html; charset=utf-8");
        $member=Model('member');
        $statistics=Model('statistics');
        $area=Model('area');

        $id=$_GET['member_id'];
        $add_time=$_GET['addtime'];
        // $add_time='1496246400';
        $member_proviced=$_GET['member_proviced'];
        // $member_proviced='4';
        //查询该省所有的代理
        
        $agent_info=$member->where(array('member_level'=>array('gt','1'),'member_provinceid'=>$member_proviced))->field('member_id,member_level,member_provinceid,member_cityid,member_areaid,portid')->select();
        
        $level=array('5'=>'member_provinceid','4'=>'member_cityid','3'=>'member_areaid','2'=>'portid');
        $agent_member_id=array();
        $agent_area_id=array();
        $agent_area=array();
        $agent=array();
        $agent_area_name=array();
        foreach ($agent_info as $key => $value) {
            $agent_member_id[]=$value['member_id'];
            
            if($value['member_level']=='2'){
                $agent[$value['member_id']]['area_id']=$value['portid'];
            }else{
                $agent_area_id[]=$value[$level[$value['member_level']]];
                $agent_area_name[$value[$level[$value['member_level']]]]=$value['member_id'];
            }
            $agent[$value['member_id']]['member_level']=$value['member_level'];
            
        }
        // print_r($agent_area_id);
        //查询代理名称
        $area_id=implode(',',$agent_area_id);
        
        $area_info=$area->where(array('area_id'=>array('in',$area_id)))->field('area_id,area_name')->select();
        foreach ($area_info as $key => $value) {
           
            $agent[$agent_area_name[$value['area_id']]]['area_id']=$value['area_name'];                       
        }
        // print_r($agent);exit;
        $agent_id=implode(',',$agent_member_id);
       
        $statistics_info=$statistics->where(array('lg_type'=>'freeze_statis','lg_member_id'=>array('in',$agent_id),'lg_add_time'=>$add_time))->select();
        foreach ($statistics_info as $key => $value) {
            $agent[$value['lg_member_id']]['member_id']=$value['lg_member_id'];
            $agent[$value['lg_member_id']]['member_name']=$value['lg_member_name'];
            $agent[$value['lg_member_id']]['frozen_amount']=$value['total_number'];

        }
       
        $this->agentfrozenExcel($agent,$id);
        
    }

    private function agentfrozenExcel($data = array()){
        
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'账号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'金额数');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'代理名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'代理等级');
        $level_name=array('5'=>'省代理','4'=>'市代理','3'=>'区县代理','2'=>'端口代理');
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['member_id']);
            $tmp[] = array('data'=>$v['member_name']);
            $tmp[] = array('data'=>$v['frozen_amount']);
            $tmp[] = array('data'=>$v['area_id']);
            $tmp[] = array('data'=>$level_name[$v['member_level']]);
            if($v['member_level']==5){
             $agent_name_1=$v['area_id'];
            }
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_yc_yckcz'),CHARSET));

         $excel_obj->generateXML($agent_name_1);
    }
        public function error_listOp(){

       Tpl::setDirquna('shop');
        Tpl::showpage('error.list');

    }   
    //异常数据查看
    public function error_xmlOP(){
        $member=Model('member');
        $pd_recharge=Model('pd_recharge');
        $pd_cash=Model('pd_cash');
        if($_GET['member_id']){
            $cash_where['predeposit_type']='2';
            $cash_where['pdc_member_id']=$_GET['member_id'];
            $pd_cash_info=$pd_cash->where($cash_where)->field('pdc_member_id,sum(pdc_amount) as amount')->find();

            $recharge_where['pdr_payment_state']='1';
            $recharge_where['pdr_member_id']=$_GET['member_id'];
            $recharge_where['pdr_type']='0';
            $recharge_info=$pd_recharge->where($recharge_where)->field('pdr_member_id,sum(pdr_amount) as amount')->find();
            $member_info=$member->where(array('member_id'=>$_GET['member_id']))->find();
            $param = array();
            $data = array();
            $lg['lg_id']='1';
            $data['now_page'] = '1';
            $data['total_num'] = '1';
            $data['count']='1';
            $param['operation'] = "<a class='btn blue' href='index.php?act=predeposit&op=member_info&member_id=" . $_GET['member_id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
            $param['id'] = $lg['lg_id'];
            $param['pdr_member_id'] = $_GET['member_id'];
            $param['pdc_amount'] = $pd_cash_info['amount'];
            $param['pdr_amount'] = $recharge_info['amount'];
            $param['pdr_desc'] = '总共充值为：'.$recharge_info['amount'].',总提现金额为：'.$pd_cash_info['amount'].',现云豆数为：'.$member_info['member_points'];
            $data['list'][$lg['lg_id']] = $param;
            $lg['lg_id']++;        
        }else{
            $pdr_payment_state=$_GET['pdr_payment_state'];
            //判断类型 1 有提现无充值的   2  提现比充值多  3 有提现无云豆
            if($pdr_payment_state=='1'){

                $money_min=$_GET['money_min'];
                $money_max=$_GET['money_max'];
                $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
                $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
                $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
                $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
                
                $cash_where['predeposit_type']='2';
                $cash_where['pdc_add_time']=array('time',array($start_unixtime,$end_unixtime));
                
                $pd_cash_info=$pd_cash->where($cash_where)->group('pdc_member_id')->field('pdc_member_id,sum(pdc_amount) as amount')->select();     
                
                foreach ($pd_cash_info as $key => $value) {
                    $pdr_memberid[]=$value['pdc_member_id'];
                    $pd_cash_member[$value['pdc_member_id']]=$value['amount'];
                }

                $start_unixtime_1 = $start_unixtime;
                $end_unixtime_1 = $end_unixtime;
                $recharge_where['pdr_add_time']=array('time',array($start_unixtime_1,$end_unixtime_1));
                $recharge_where['pdr_payment_state']='1';
                $recharge_where['pdr_type']='0';
                
                $recharge_info=$pd_recharge->where($recharge_where)->group('pdr_member_id')->field('pdr_member_id,sum(pdr_amount) as amount')->select();
                
                foreach ($recharge_info as $key => $value) {
                    $recharge_memberid[]=$value['pdr_member_id'];
                    $recharge_info_member[$value['pdr_member_id']]=$value['amount'];
                }
                //判断有提现，却没有充值的会员id
                $diff_memberid=array_diff($pdr_memberid,$recharge_memberid);
                $param = array();
                $data = array();
                $lg['lg_id']='1';
                $data['now_page'] = '1';
                $data['total_num'] = '1';
                $data['count']='1';
                foreach ($diff_memberid as $key => $diff) {
                    if($money_min && $money_max){
                        if($pd_cash_member[$diff]<$money_min || $pd_cash_member[$diff]>$money_max){
                            continue;
                        }
                    }
                    $param['operation'] = "<a class='btn blue' href='index.php?act=predeposit&op=member_info&member_id=" . $diff . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
                    $param['id'] = $lg['lg_id'];
                    $param['pdr_member_id'] = $diff;
                    $param['pdc_amount'] = $pd_cash_member[$diff];
                    $param['pdr_amount'] = $recharge_info_member[$diff];
                    $param['pdr_desc'] = '时间'.date('Y-m-d', $start_unixtime).'-'.date('Y-m-d', $end_unixtime);
                    $data['list'][$lg['lg_id']] = $param;
                    $lg['lg_id']++;       
                }
            }
            //提现比充值多
            if($pdr_payment_state=='2'){

                $money_min=$_GET['money_min'];
                $money_max=$_GET['money_max'];
                $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
                $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
                $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
                $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
                
                $cash_where['predeposit_type']='2';
                $cash_where['pdc_add_time']=array('time',array($start_unixtime,$end_unixtime));
                
                $pd_cash_info=$pd_cash->where($cash_where)->group('pdc_member_id')->field('pdc_member_id,sum(pdc_amount) as amount')->select();     
                
                foreach ($pd_cash_info as $key => $value) {
                    $pdr_memberid[]=$value['pdc_member_id'];
                    $pd_cash_member[$value['pdc_member_id']]=$value['amount'];
                }

                $start_unixtime_1 = $start_unixtime;
                $end_unixtime_1 = $end_unixtime;
                $recharge_where['pdr_add_time']=array('time',array($start_unixtime_1,$end_unixtime_1));
                $recharge_where['pdr_payment_state']='1';
                $recharge_where['pdr_type']='0';
                 
                $recharge_info=$pd_recharge->where($recharge_where)->group('pdr_member_id')->field('pdr_member_id,sum(pdr_amount) as amount')->select();
               
                foreach ($recharge_info as $key => $value) {
                    $recharge_memberid[]=$value['pdr_member_id'];
                    $recharge_info_member[$value['pdr_member_id']]=$value['amount'];
                }               
                //判断充值比提现少
                // $diff_memberid=array_diff($pdr_memberid,$recharge_memberid);
                foreach ($pd_cash_member as $key => $value) {
                    $recharge_amount=ceil($recharge_info_member[$key]*0.95);
                    // echo $recharge_info_member[$key].'-'.$recharge_amount.'-'.$value.'</br>';
                    if($value>$recharge_amount){
                        
                        $diff_memberid[]=$key;
                    }
                }
                $param = array();
                $data = array();
                $lg['lg_id']='1';
                $data['now_page'] = '1';
                $data['total_num'] = '1';
                $data['count']='1';
                foreach ($diff_memberid as $key => $diff) {
                    if($money_min && $money_max){
                        if($pd_cash_member[$diff]<$money_min || $pd_cash_member[$diff]>$money_max){
                            continue;
                        }
                    }
                    $param['operation'] = "<a class='btn blue' href='index.php?act=predeposit&op=member_info&member_id=" . $diff . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
                    $param['id'] = $lg['lg_id'];
                    $param['pdr_member_id'] = $diff;
                    $param['pdc_amount'] = $pd_cash_member[$diff];
                    $param['pdr_amount'] = $recharge_info_member[$diff];
                    $param['pdr_desc'] = '时间'.date('Y-m-d', $start_unixtime).'-'.date('Y-m-d', $end_unixtime);
                    $data['list'][$lg['lg_id']] = $param;
                    $lg['lg_id']++;       
                }
            }
            //有提现无云豆
            if($pdr_payment_state=='3'){
                $money_min=$_GET['money_min'];
                $money_max=$_GET['money_max'];
                $points=$_GET['points'];
                $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
                $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
                $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
                $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
                
                $cash_where['predeposit_type']=array('in','1,2');
                $cash_where['pdc_add_time']=array('time',array($start_unixtime,$end_unixtime));
                
                $pd_cash_info=$pd_cash->where($cash_where)->group('pdc_member_id')->field('pdc_member_id,sum(pdc_amount) as amount')->select();     
                
                foreach ($pd_cash_info as $key => $value) {
                    $pdr_memberid[]=$value['pdc_member_id'];
                    $pd_cash_member[$value['pdc_member_id']]=$value['amount'];
                }
                $member_where['member_id']=array('in',implode(',',$pdr_memberid));
                if($points){
                    $member_where['member_points']=array('lt',$points);
                }else{
                    $member_where['member_points']='0';
                }
                $member_info=$member->where($member_where)->select();
                $param = array();
                $data = array();
                $lg['lg_id']='1';
                $data['now_page'] = '1';
                $data['total_num'] = '1';
                $data['count']='1';
                foreach ($member_info as $key => $diff) {
                    if($money_min && $money_max){
                        if($pd_cash_member[$diff['member_id']]<$money_min || $pd_cash_member[$diff['member_id']]>$money_max){
                            continue;
                        }
                    }
                    $param['operation'] = "<a class='btn blue' href='index.php?act=predeposit&op=member_info&member_id=" . $diff['member_id'] . "'><i class='fa fa-pencil-square-o'></i>查看</a>";
                    $param['id'] = $lg['lg_id'];
                    $param['pdr_member_id'] = $diff['member_id'];
                    $param['pdc_amount'] = $pd_cash_member[$diff['member_id']];
                    $param['pdr_amount'] = '';
                    $param['pdr_desc'] = '时间'.date('Y-m-d', $start_unixtime).'-'.date('Y-m-d', $end_unixtime);
                    $data['list'][$lg['lg_id']] = $param;
                    $lg['lg_id']++;       
                }
            } 
        }
        
        echo Tpl::flexigridXML($data);exit();       
    }
    //查看会员所有信息
    public function member_infoOP(){

        $member=Model('member');
        $pd_recharge=Model('pd_recharge');
        $points_log=Model('points_log');
        $pd_cash=Model('pd_cash');
        $member_id=$_GET['member_id'];
        //查询会员目前云豆、充值余额、云豆余额
        $member_info=$member->where(array('member_id'=>$member_id))->field('member_points,available_predeposit,member_predeposit')->find();
        //查询该会员的充值购买总云豆数和手续费
        $points_info=$points_log->where(array('pl_memberid'=>$member_id,'pl_stage'=>'rechart'))->field('sum(pl_points) as points_amount,sum(pl_counter) as counter')->find();
        //查询该会员的充值总金额
        $recharge_info=$pd_recharge->where(array('pdr_member_id'=>$member_id,'pdr_payment_state'=>'1','pdr_type'=>'0'))->sum('pdr_amount');
       
        //查询该会员提现记录
        $cash_info=$pd_cash->where(array('pdc_member_id'=>$member_id))->group('predeposit_type')->field('sum(pdc_amount) as amount,predeposit_type')->select();
        // print_r($cash_info);exit;
        foreach ($cash_info as $key => $value) {
            //总云豆余额提现
            if($value['predeposit_type']=='1'){
                $cash_info['available_cash']=$value['amount'];
            }
            //总充值提现金额
            if($value['predeposit_type']=='2'){
                $cash_info['predeposit_cash']=$value['amount'];
            }
            // if($value['predeposit_type']=='3'){
            //     $available_cash=$value['pdc_amount'];
            // }
        }

        Tpl::output('cash_info',$cash_info);
        Tpl::output('recharge_info',$recharge_info);
        Tpl::output('points_info',$points_info);
        Tpl::output('member_info',$member_info);
        Tpl::setDirquna('shop');
        Tpl::showpage('member.info');
    }       
}
