<?php
/**
 * 发货
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class store_deliverControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index,deliver');
    }

    /**
     * 发货列表
     *
     */
    public function indexOp() {
        $model_order = Model('order');
        if (!in_array($_GET['state'],array('deliverno','delivering','delivered'))) $_GET['state'] = 'deliverno';
        $order_state = str_replace(array('deliverno','delivering','delivered'),
                array(ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS),$_GET['state']);
        $condition = array();
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['order_state'] = $order_state;
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        if (preg_match('/^\d{10,20}$/',$_GET['order_sn'])) {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $order_list = $model_order->getOrderList($condition,5,'*','order_id desc','',array('order_goods','order_common','member'));
        foreach ($order_list as $key => $order_info) {
            foreach ($order_info['extend_order_goods'] as $value) {
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                if ($value['goods_type'] == 5) {
                    $order_info['zengpin_list'][] = $value;
                } else {
                    $order_info['goods_list'][] = $value;
                }
            }

            if (empty($order_info['zengpin_list'])) {
                $order_info['goods_count'] = count($order_info['goods_list']);
            } else {
                $order_info['goods_count'] = count($order_info['goods_list']) + 1;
            }
            $order_list[$key] = $order_info;
        }
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        self::profile_menu('deliver',$_GET['state']);
        Tpl::showpage('store_order.deliver');
    }

    /**
     * 发货
     */
    public function sendOp(){
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0){
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];

        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
        if ($if_allow_send) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }

        if (chksubmit()){
            if($order_info['order_state']=='20'){
                $logic_order = Logic('order');
                $_POST['reciver_info'] = $this->_get_reciver_info();
                //商家获取50%佣金
                //20170714潘丙福添加开始-最新结款方式1买家确认收货商家获取订单金额的50%；2确认收货3天后收到另外的订单金额的50%
                // $store = Model('seller');
                // $rcb   = Model('rcb_log');                      
                // $dat['wallet_frozen']=array('exp','wallet_frozen+'.$order_info['order_amount']*0.5);
                // $dat['wallet_release']=array('exp','wallet_release+'.$order_info['order_amount']*0.5);
                // $log['member_id']=$order_info['store_id'];
                // $log['member_name']=$order_info['store_name'];
                // $log['type']='seller';
                // $log['add_time']=time();
                // $log['available_amount']=$order_info['order_amount']*0.5;
                // $log['freeze_amount']=$order_info['order_amount']*0.5;
                // $log['description']='商家获得50%佣金--订单号：'.$order_id;
                // $rcb->insert($log);                       
                // $upstore=$store->where(array('store_id'=>$order_info['store_id']))->update($dat); 
                $result = $logic_order->changeOrderSend($order_info, 'seller', $_SESSION['seller_name'], $_POST);
                if (!$result['state']) {
                    showMessage($result['msg'],'','html','error');
                } else {
                    showDialog($result['msg'],$_POST['ref_url'],'succ');
                }
            }
        }

        Tpl::output('order_info',$order_info);
        //取发货地址
        $model_daddress = Model('daddress');
        if ($order_info['extend_order_common']['daddress_id'] > 0 ){
            $daddress_info = $model_daddress->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
        }else{
            //取默认地址
            $daddress_info = $model_daddress->getAddressList(array('store_id'=>$_SESSION['store_id']),'*','is_default desc',1);
            $daddress_info = $daddress_info[0];

            //写入发货地址编号
            $this->_edit_order_daddress($daddress_info['address_id'], $order_id);
        }
        Tpl::output('daddress_info',$daddress_info);

        $express_list  = rkcache('express',true);

        //如果是自提订单，只保留自提快递公司
        if ($order_info['extend_order_common']['reciver_info']['dlyp'] != '') {
            foreach ($express_list as $k => $v) {
                if ($v['e_zt_state'] == '0') unset($express_list[$k]);
            }
            $my_express_list = array_keys($express_list);
        } else {
            //快递公司
            $my_express_list = Model()->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');
            if (!empty($my_express_list)){
                $my_express_list = explode(',',$my_express_list);
            }
        }

        Tpl::output('my_express_list',$my_express_list);
        Tpl::output('express_list',$express_list);
        Tpl::showpage('store_deliver.send');
    }

    /**
     * 编辑收货地址
     * @return boolean
     */
    public function buyer_address_editOp() {
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) return false;
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_common_info = $model_order->getOrderCommonInfo($condition);
        if (!$order_common_info) return false;
        $order_common_info['reciver_info'] = @unserialize($order_common_info['reciver_info']);
        Tpl::output('address_info',$order_common_info);

        Tpl::showpage('store_deliver.buyer_address.edit','null_layout');
    }

    /**
     * 收货地址保存
     */
    public function buyer_address_saveOp() {
        $model_order = Model('order');
        $data = array();
        $data['reciver_name'] = $_POST['reciver_name'];
        $data['reciver_info'] = $this->_get_reciver_info();
        $condition = array();
        $condition['order_id'] = intval($_POST['order_id']);
        $condition['store_id'] = $_SESSION['store_id'];
        $result = $model_order->editOrderCommon($data, $condition);
        if($result) {
            echo 'true';
        } else {
            echo 'flase';
        }
    }

    /**
     * 组合reciver_info
     */
    private function _get_reciver_info() {
        $reciver_info = array(
            'address' => $_POST['reciver_area'] . ' ' . $_POST['reciver_street'],
            'phone' => trim($_POST['reciver_mob_phone'] . ',' . $_POST['reciver_tel_phone'],','),
            'area' => $_POST['reciver_area'],
            'street' => $_POST['reciver_street'],
            'mob_phone' => $_POST['reciver_mob_phone'],
            'tel_phone' => $_POST['reciver_tel_phone'],
            'dlyp' => $_POST['reciver_dlyp']
        );
        return serialize($reciver_info);
    }

    /**
     * 选择发货地址
     * @return boolean
     */
    public function send_address_selectOp() {
        Language::read('deliver');
        $address_list = Model('daddress')->getAddressList(array('store_id'=>$_SESSION['store_id']));
        Tpl::output('address_list',$address_list);
        Tpl::output('order_id', $_GET['order_id']);
        Tpl::showpage('store_deliver.daddress.select','null_layout');
    }

    /**
     * 保存发货地址修改
     */
    public function send_address_saveOp() {
        $result = $this->_edit_order_daddress($_POST['daddress_id'], $_POST['order_id']);
        if($result) {
            echo 'true';
        } else {
            echo 'flase';
        }
    }

    /**
     * 修改发货地址
     */
    private function _edit_order_daddress($daddress_id, $order_id) {
        $model_order = Model('order');
        $data = array();
        $data['daddress_id'] = intval($daddress_id);
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        return $model_order->editOrderCommon($data, $condition);
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        Language::read('member_member_index');
        $lang   = Language::getLangContent();

        $order_sn   = $_GET['order_sn'];
        if (!preg_match('/^\d{10,20}$/',$_GET['order_sn'])) showMessage(Language::get('wrong_argument'),'','html','error');
        $model_order    = Model('order');
        $condition['order_sn'] = $order_sn;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || $order_info['shipping_code'] == '') {
            showMessage('未找到信息','','html','error');
        }
        $order_info['state_info'] = orderState($order_info);
        Tpl::output('order_info',$order_info);
        //卖家发货信息
        $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
        Tpl::output('daddress_info',$daddress_info);

        //取得配送公司代码
        $express = rkcache('express',true);
        Tpl::output('e_code',$express[$order_info['extend_order_common']['shipping_express_id']]['e_code']);
        Tpl::output('e_name',$express[$order_info['extend_order_common']['shipping_express_id']]['e_name']);
        Tpl::output('e_url',$express[$order_info['extend_order_common']['shipping_express_id']]['e_url']);
        Tpl::output('shipping_code',$order_info['shipping_code']);

        self::profile_menu('search','search');
        Tpl::showpage('store_deliver.detail');
    }

    /**
     * 延迟收货
     */
    public function delay_receiveOp(){
        $order_id = intval($_GET['order_id']);
        $model_trade = Model('trade');
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['lock_state'] = 0;
        $order_info = $model_order->getOrderInfo($condition);

        //取目前系统最晚收货时间
        $delay_time = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 3600 * 24;
        if (chksubmit()) {
            $delay_date = intval($_POST['delay_date']);
            if (!in_array($delay_date,array(5,10,15))) {
                showDialog(Language::get('wrong_argument'),'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
            }
            $update = $model_order->editOrder(array('delay_time'=>array('exp','delay_time+'.$delay_date*3600*24)),$condition);
            if ($update) {
                //新的最晚收货时间
                $dalay_date = date('Y-m-d H:i:s',$delay_time+$delay_date*3600*24);
                showDialog("成功将最晚收货期限延迟到了".$dalay_date.'&emsp;','','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();',4);
            } else {
                showDialog('延迟失败','','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
            }
        } else {
            $order_info['delay_time'] = $delay_time;
            Tpl::output('order_info',$order_info);
            Tpl::showpage('store_deliver.delay_receive','null_layout');
            exit();
        }
    }

    /**
     * 运单打印
     */
    public function waybill_printOp() {
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0) {
            showMessage(L('param_error'));
        }

        $model_order = Model('order');
        $model_store_waybill = Model('store_waybill');
        $model_waybill = Model('waybill');

        $order_info = $model_order->getOrderInfo(array('order_id' => intval($_GET['order_id'])), array('order_common'));

        $store_waybill_list = $model_store_waybill->getStoreWaybillList(array('store_id' => $order_info['store_id']), 'is_default desc');

        $store_waybill_info = $this->_getCurrentWaybill($store_waybill_list, $_GET['store_waybill_id']);
        if(empty($store_waybill_info)) {
            showMessage('请首先绑定打印模板', urlShop('store_waybill', 'waybill_manage'), '', 'error');
        }

        $waybill_info = $model_waybill->getWaybillInfo(array('waybill_id' => $store_waybill_info['waybill_id']));
        if(empty($waybill_info)) {
            showMessage('请首先绑定打印模板', urlShop('store_waybill', 'waybill_manage'), '', 'error');
        }

        //根据订单内容获取打印数据
        $print_info = $model_waybill->getPrintInfoByOrderInfo($order_info);

        //整理打印模板
        $store_waybill_data = unserialize($store_waybill_info['store_waybill_data']);
        foreach ($waybill_info['waybill_data'] as $key => $value) {
            $waybill_info['waybill_data'][$key]['show'] = $store_waybill_data[$key]['show'];
            $waybill_info['waybill_data'][$key]['content'] = $print_info[$key];
        }

        //使用商家自定义的偏移尺寸
        $waybill_info['waybill_pixel_top'] = $store_waybill_info['waybill_pixel_top'];
        $waybill_info['waybill_pixel_left'] = $store_waybill_info['waybill_pixel_left'];

        Tpl::output('waybill_info', $waybill_info);
        Tpl::output('store_waybill_list', $store_waybill_list);
        Tpl::showpage('waybill.print', 'null_layout');
    }

    /**
     * 获取当前打印模板
     */
    private function _getCurrentWaybill($store_waybill_list, $store_waybill_id) {
        if(empty($store_waybill_list)) {
            return false;
        }

        $store_waybill_id = intval($store_waybill_id);

        $store_waybill_info = null;

        //如果指定模板使用指定的模板，未指定使用默认模板
        if($store_waybill_id > 0) {
            foreach ($store_waybill_list as $key => $value) {
                if($store_waybill_id == $value['store_waybill_id']) {
                    $store_waybill_info = $store_waybill_list[$key];
                    break;
                }
            }
        }

        if(empty($store_waybill_info)) {
            $store_waybill_info = $store_waybill_list[0];
        }

        return $store_waybill_info;
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type,$menu_key='') {
        Language::read('member_layout');
        $menu_array     = array();
        switch ($menu_type) {
            case 'deliver':
                $menu_array = array(
                array('menu_key'=>'deliverno',          'menu_name'=>Language::get('nc_member_path_deliverno'), 'menu_url'=>'index.php?act=store_deliver&op=index&state=deliverno'),
                array('menu_key'=>'delivering',         'menu_name'=>Language::get('nc_member_path_delivering'),    'menu_url'=>'index.php?act=store_deliver&op=index&state=delivering'),
                array('menu_key'=>'delivered',      'menu_name'=>Language::get('nc_member_path_delivered'), 'menu_url'=>'index.php?act=store_deliver&op=index&state=delivered'),
                );
                break;
            case 'search':
                $menu_array = array(
                1=>array('menu_key'=>'nodeliver',           'menu_name'=>Language::get('nc_member_path_deliverno'), 'menu_url'=>'index.php?act=store_deliver&op=index&state=nodeliver'),
                2=>array('menu_key'=>'delivering',          'menu_name'=>Language::get('nc_member_path_delivering'),    'menu_url'=>'index.php?act=store_deliver&op=index&state=delivering'),
                3=>array('menu_key'=>'delivered',       'menu_name'=>Language::get('nc_member_path_delivered'), 'menu_url'=>'index.php?act=store_deliver&op=index&state=delivered'),
                4=>array('menu_key'=>'search',      'menu_name'=>Language::get('nc_member_path_deliver_info'),  'menu_url'=>'###'),
                );
                break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
	public function senddmOp(){   //修改地面商家订单状态，改为已经发货，并跳转到链接
	      $model_order = Model('order');
	      $id = isset($_GET['order_id'])?$_GET['order_id']:'';
		  if(!empty($id)){  //查询产品信息(是否是地面商家产品),如果是则更改为已经发货状态
         $data = array('order_state'=>'40');
         $model_order->table('orders')->where(array('order_id'=>$id))->update($data);
		 echo "<script>window.alert('确认订单成功，请等待买家确认消费，来完成订单');</script>";
		 echo "<script>window.location.href='index.php?act=store_order&op=index&skip_off=0'</script>";
		  }else{
			 showMessage('参数错误','','html','error');
			 }
		}
	public function sendzjdmOp(){
		  $model_order = Model('order');
		  $store = Model('seller');
	      $id = isset($_GET['order_id'])?$_GET['order_id']:'';
		  if(!empty($id)){  //交易资金入库并打钱给商家
		   
       $ydmbs = Model()->table('orders')->where(array('order_id'=>$id))->find();
	   $xukoumoney = $ydmbs['order_amount']*0.05;
  if($ydmbs['order_state']==20&&$ydmbs['is_dm']==1){
       $store = Model('seller');
       $rcb   = Model('rcb_log');                     //冻结钱包加订单的50%--新结算方式
       $pd_log=Model("points_log");          //日志文件
	   $store_id_m = Model()->table('store')->field('member_id,member_name')->where(array('store_id'=>$ydmbs['store_id']))->find();

            $dat['wallet_frozen']=array('exp','wallet_frozen+'.$ydmbs['order_amount']*0.5);
            $log['available_amount']=$ydmbs['order_amount']-$xukoumoney;
            $log['description']='地面买家扫码或直接支付收钱--商家获得全部货款-订单号：'.$ydmbs['order_sn'];
            $dat['wallet_release']=array('exp','wallet_release+'.$ydmbs['order_amount']-$xukoumoney);
            $log['member_id']=$ydmbs['store_id'];
            $log['member_name']=$ydmbs['store_name'];
            $log['type']='seller';
            $log['add_time']=time();
            $log['freeze_amount']=0;
            $rcb->insert($log);                       
            $upstore=$store->where(array('store_id'=>$ydmbs['store_id']))->update($dat);
			if($upstore){
            //扣除购买该订单产品所需要的云豆
            $data = array();
            $data['order_id'] = $id;
            $data['log_role'] = $store_id_m['member_name'];
            $data['log_msg'] = $store_id_m['member_name']."确认订单";
            $data['log_user'] = $store_id_m['member_name'];
            $data['log_orderstate'] = 50;
            $model_order->addOrderLog($data);
			 $pdc=array();
            $model_store_cost = Model('store_cost');
            $pdc['cost_store_id']=$ydmbs['store_id'];
            $pdc['cost_seller_id']=$ydmbs['buyer_id'];
            $pdc['cost_price']= $xukoumoney;
            $pdc['cost_time']=time();
            $pdc['cost_state']=1;
           $pdc['cost_remark']='订单'.$ydmbs['order_sn'].',平台代付云豆扣除手续费'.$xukoumoney;
           $insert=$model_store_cost->insert($pdc);
           $sjdata = array();  //给商家添加云豆及日志
           $bs = round($xukoumoney*2,0);//四舍五入
           $sjdata['member_points'] = array("exp","member_points+$bs");
           $sjupdatee = Model()->table('member')->where(array('member_id'=>$_SESSION['member_id']))->update($sjdata);
		   $datas = array();  //给消费者添加云豆及日志
		   $sjyd = $ydmbs['order_amount']*0.5;
           $datas['member_points'] = array("exp","member_points+$sjyd");
           $updatee = Model()->table('member')->where(array('member_id'=>$ydmbs['buyer_id']))->update($datas);   //给消费会员添加云豆

            if($updatee){     //更新日志表  
              $pd['pl_memberid']= $ydmbs['buyer_id'];
              $pd['pl_membername']= $ydmbs['buyer_name'];
              //$pd['lg_type']='order_pay';
              $pd['pl_points']=$sjyd;
              $pd['pl_adminname']='';
              $pd['pl_addtime']=time();
              $pd['pl_desc']='订单：'.$ydmbs['order_sn'].',获得平台赠送云豆';
              $insert=$pd_log->insert($pd);

          }
		  give_dmchief($ydmbs['buyer_id'],$sjyd,$ydmbs['order_sn'],1);    //给会员提成及分润
          give_dmchief($store_id_m['member_id'],$xukoumoney,$ydmbs['order_sn'],2);    //给店铺商家提成及分
		   
			
			}
 
 
           }
		  
         $datam = array('order_state'=>'50');
         $model_order->table('orders')->where(array('order_id'=>$id))->update($datam);
		 echo "<script>window.alert('交易完成');</script>";
		 echo "<script>window.location.href='index.php?act=store_order&op=index&skip_off=0'</script>";
		  }else{
			 showMessage('参数错误','','html','error');
			 }
		
		}
}
