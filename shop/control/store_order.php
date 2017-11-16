<?php
/**
 * 卖家实物订单管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class store_orderControl extends BaseSellerControl {
    
    const EXPORT_SIZE = 300;

    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    /**
     * 订单列表
     *
     */
    public function indexOp() {
        $model_order = Model('order');
        if (!$_GET['state_type']) {
            $_GET['state_type'] = 'store_order';
        }

        if($_GET['state_type'] == 'state_pay'){
            $order_list = $model_order->getStoreOrderList_pay($_SESSION['store_id'], $_GET['order_sn'], $_GET['buyer_name'], $_GET['state_type'], $_GET['query_start_date'], $_GET['query_end_date'], $_GET['skip_off'], '*', array('order_goods','order_common','member'), null,$_GET['goods_name']);

        }else{
           $order_list = $model_order->getStoreOrderList($_SESSION['store_id'], $_GET['order_sn'], $_GET['buyer_name'], $_GET['state_type'], $_GET['query_start_date'], $_GET['query_end_date'], $_GET['skip_off'], '*', array('order_goods','order_common','member'), null,$_GET['goods_name']);
        }


        //20170623潘丙福添加开始-返回当前查询的订单状态
        Tpl::output('stateType',$_GET['state_type']);
        //20170623潘丙福添加结束
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        self::profile_menu('list',$_GET['state_type']);
        Tpl::showpage('store_order.index');
    }

    /**
     * 卖家订单详情
     *
     */
    public function show_orderOp() {
        Language::read('member_member_index');
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods','member'));
        if (empty($order_info)) {
            showMessage(Language::get('store_order_none_exist'),'','html','error');
        }

        //取得订单其它扩展信息
        $model_order->getOrderExtendInfo($order_info);

        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
            Tpl::output('refund_all',$refund_all);
        }

        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示调整费用
        $order_info['if_modify_price'] = $model_order->getOrderOperateState('modify_price',$order_info);

        //显示取消订单
        $order_info['if_store_cancel'] = $model_order->getOrderOperateState('store_cancel',$order_info);

        //显示发货
        $order_info['if_store_send'] = $model_order->getOrderOperateState('store_send',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);

        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_TIME * 3600;
        }

        //显示快递信息
        if ($order_info['shipping_code'] != '') {
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //取得订单操作日志
        $order_log_list = $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']),'log_id asc');
        Tpl::output('order_log_list',$order_log_list);

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $last_log = end($order_log_list);
            if ($last_log['log_orderstate'] == ORDER_STATE_CANCEL) {
                $order_info['close_info'] = $last_log;
            }
        }
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            //处理消费者保障服务
            if (trim($value['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$value['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $value['contractlist'][] = $contract_item[$gcti_v];
                }
            }
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

        Tpl::output('order_info',$order_info);

        //发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }

        Tpl::showpage('store_order.show');
    }

    /**
     * 卖家订单状态操作
     *
     */
    public function change_stateOp() {
        $state_type = $_GET['state_type'];
        $order_id   = intval($_GET['order_id']);

        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition);

        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

        if ($_GET['state_type'] == 'order_cancel') {
            $result = $this->_order_cancel($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'modify_price') {
            $result = $this->_order_ship_price($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'spay_price') {
			$result = $this->_order_spay_price($order_info,$_POST);
    	} elseif ($_GET['state_type'] == 'shipping_code') {
            $result = $this->_order_shipping_code($order_info,$_POST);
        } elseif ($_GET['state_type'] == 'refund_shipping_fee') {
            $result = $this->_order_refund_shipping_fee($order_info,$_POST);
        }
	
        if (!$result['state']) {
            showDialog($result['msg'],'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();',5);
        } else {
            showDialog($result['msg'],'reload','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
        }
    }

    /**
     * 取消订单
     * @param unknown $order_info
     */
    private function _order_cancel($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.cancel','null_layout');
            exit();
         } else {
             $if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
             if (!$if_allow) {
                 return callback(false,'无权操作');
             }
             if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
                 $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
                 return callback(false,'该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');

             }
             $msg = $post['state_info1'] != '' ? $post['state_info1'] : $post['state_info'];
             if ($order_info['order_type'] == 2) {
                 //预定订单
                 return Logic('order_book')->changeOrderStateCancel($order_info,'seller',$_SESSION['seller_name'], $msg);
             } else {
                 $cancel_condition = array();
                 if ($order_info['payment_code'] != 'offline') {
                     $cancel_condition['order_state'] = ORDER_STATE_NEW;
                 }
                 return $logic_order->changeOrderStateCancel($order_info,'seller',$_SESSION['seller_name'], $msg,true,$cancel_condition);
             }
         }
    }

    /**
     * 修改运费
     * @param unknown $order_info
     */
    private function _order_ship_price($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('modify_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderShipPrice($order_info,'seller',$_SESSION['seller_name'],$post['shipping_fee']);
        }
    }
    /**
     * 20170830潘丙福添加--退还运费
     * @param unknown $order_info
     */
    private function _order_refund_shipping_fee($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        if(!chksubmit()) {
            Tpl::output('order_info',$order_info);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.refund_shipping_fee','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('refund_shipping_fee',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderRefundShippingFee($order_info,'seller',$_SESSION['member_name'],trim($post['shipping_fee']));
        }
    }
	/**
	 * 修改商品价格
	 * @param unknown $order_info
	 */
	private function _order_spay_price($order_info, $post) {
        $model_order = Model('order');
	    $logic_order = Logic('order');
	    if(!chksubmit()) {
	        Tpl::output('order_info',$order_info);
	        Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_spay_price','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('spay_price',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作');
            }
            return $logic_order->changeOrderSpayPrice($order_info,'seller',$_SESSION['member_name'],$post['goods_amount']); 
	    }
	}

    /**
     * 修改物流单号
     * @param unknown $order_info
     * 20170613潘丙福添加
     */
    private function _order_shipping_code($order_info, $post) {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $shipping_express_id = Model()->table('order_common')->where(array('order_id'=>$order_info['order_id']))->field('shipping_express_id')->find();
        $shipping_express_id = $shipping_express_id['shipping_express_id'];
        $express_list = Model('express')->getExpressList();
        if(!chksubmit()) {
            Tpl::output('shipping_express_id',$shipping_express_id);
            Tpl::output('order_info',$order_info);
            Tpl::output('express_list',$express_list);
            Tpl::output('order_id',$order_info['order_id']);
            Tpl::showpage('store_order.edit_shipping_code','null_layout');
            exit();
        } else {
            $if_allow = $model_order->getOrderOperateState('shipping_code',$order_info);
            if (!$if_allow) {
                return callback(false,'无权操作11111');
            }
            return $logic_order->changeOrderShippingCode($order_info,'seller',$_SESSION['member_name'],$post['shipping_code'],$post); 
        }
    }

    /**
     * 打印发货单
     */
    public function order_printOp() {
        Language::read('member_printorder');

        $order_id   = intval($_GET['order_id']);
        if ($order_id <= 0){
            showMessage(Language::get('wrong_argument'),'','html','error');
        }
        $order_model = Model('order');
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $order_model->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info)){
            showMessage(Language::get('member_printorder_ordererror'),'','html','error');
        }
        Tpl::output('order_info',$order_info);

        //卖家信息
        $model_store    = Model('store');
        $store_info     = $model_store->getStoreInfoByID($order_info['store_id']);
        if (!empty($store_info['store_label'])){
            if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_label'])){
                $store_info['store_label'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_info['store_label'];
            }else {
                $store_info['store_label'] = '';
            }
        }
        if (!empty($store_info['store_stamp'])){
            if (file_exists(BASE_UPLOAD_PATH.DS.ATTACH_STORE.DS.$store_info['store_stamp'])){
                $store_info['store_stamp'] = UPLOAD_SITE_URL.DS.ATTACH_STORE.DS.$store_info['store_stamp'];
            }else {
                $store_info['store_stamp'] = '';
            }
        }
        Tpl::output('store_info',$store_info);

        //订单商品
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $goods_new_list = array();
        $goods_all_num = 0;
        $goods_total_price = 0;
        if (!empty($order_info['extend_order_goods'])){
            $goods_count = count($order_goods_list);
            $i = 1;
            foreach ($order_info['extend_order_goods'] as $k => $v){
                $v['goods_name'] = str_cut($v['goods_name'],100);
                $goods_all_num += $v['goods_num'];
                $v['goods_all_price'] = ncPriceFormat($v['goods_num'] * $v['goods_price']);
                $goods_total_price += $v['goods_all_price'];
                $goods_new_list[ceil($i/4)][$i] = $v;
                $i++;
            }
        }
        //优惠金额
        $promotion_amount = $goods_total_price - $order_info['goods_amount'];
        //运费
        $order_info['shipping_fee'] = $order_info['shipping_fee'];
        Tpl::output('promotion_amount',$promotion_amount);
        Tpl::output('goods_all_num',$goods_all_num);
        Tpl::output('goods_total_price',ncPriceFormat($goods_total_price));
        Tpl::output('goods_list',$goods_new_list);
        Tpl::showpage('store_order.print',"null_layout");
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string    $menu_type  导航类型
     * @param string    $menu_key   当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_type='',$menu_key='') {
        Language::read('member_layout');
        switch ($menu_type) {
            case 'list':
            $menu_array = array(
            array('menu_key'=>'store_order',        'menu_name'=>Language::get('nc_member_path_all_order'), 'menu_url'=>'index.php?act=store_order'),
            array('menu_key'=>'state_new',          'menu_name'=>Language::get('nc_member_path_wait_pay'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_new'),
            array('menu_key'=>'state_pay',          'menu_name'=>Language::get('nc_member_path_wait_send'), 'menu_url'=>'index.php?act=store_order&op=index&state_type=state_pay'),
            array('menu_key'=>'state_notakes',      'menu_name'=>'待自提', 'menu_url'=>'index.php?act=store_order&op=index&state_type=state_notakes'),
            array('menu_key'=>'state_send',         'menu_name'=>Language::get('nc_member_path_sent'),      'menu_url'=>'index.php?act=store_order&op=index&state_type=state_send'),
            array('menu_key'=>'state_success',      'menu_name'=>Language::get('nc_member_path_finished'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_success'),
            array('menu_key'=>'state_cancel',       'menu_name'=>Language::get('nc_member_path_canceled'),  'menu_url'=>'index.php?act=store_order&op=index&state_type=state_cancel'),
            );
            break;
        }
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }

    //20170419潘丙福添加  获取条件
    public function panGetCondition($panCon)
    {
        $condition = array();
        foreach ($panCon as $key => $value) {
            if (strtolower($key) == 'act' || strtolower($key) == 'op') {
                continue;
            }
            switch (strtolower($key)) {
                case 'query_start_date':
                    $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$panCon['query_start_date']);
                    $start_unixtime = $if_start_time ? strtotime($panCon['query_start_date']) : null;
                    break;
                case 'query_end_date':
                    $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$panCon['query_end_date']);
                    $end_unixtime = $if_end_time ? strtotime($panCon['query_end_date']) : null;
                    break;
                case 'buyer_name':
                    if (trim($panCon[$key])) {
                        $condition['buyer_name'] = array('like', trim($panCon[$key]));
                    }
                    break;
                case 'order_sn':
                    if (trim($panCon[$key])) {
                        $condition['order_sn'] = array('like', trim($panCon[$key]));
                    }
                    break;
                case 'state_type':
                    if (strtolower($panCon[$key]) == 'state_new') {
                        $condition['order_state'] = array('eq', '10');
                    } else if (strtolower($panCon[$key]) == 'state_pay') {
                        $condition['order_state'] = array('eq', '20');
                    } else if (strtolower($panCon[$key]) == 'state_send') {
                        $condition['order_state'] = array('eq', '30');
                    } else if (strtolower($panCon[$key]) == 'state_success') {
                        $condition['order_state'] = array('eq', '40');
                    } else if (strtolower($panCon[$key]) == 'state_notakes') {
                        $condition['chain_code'] = array('gt', '0');
                    }
                    break;
                case 'skip_off':
                    if (trim($panCon[$key])) {
                        $condition['order_state'] = array('gt', '0');
                    }
                    break;
                case 'goods_name':
                    if (trim($panCon[$key])) {
                        $condition['goods_name'] = array('like', '%'.trim($panCon[$key]).'%');
                    }
                    break;
            }
        }
        if ($start_unixtime || $end_unixtime) {
            //20171026潘丙福添加开始-判断订单状态进而确定采用哪个时间payment_time支付时间，add_time订单生成时间
            if ($panCon['state_type'] == 'state_pay') {
                $condition['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
            } else {
                $condition['add_time']     = array('time',array($start_unixtime,$end_unixtime));  
            }          
        }

        return $condition;
    }

    //20170419潘丙福添加  导出excel表格
    public function orderall_exportOp()
    {
        $condition = $this->panGetCondition($_GET);
        $model_order = Model('order');
        $condition['store_id'] = $_SESSION['store_id'];
        //20170621潘丙福添加开始--获取当前店铺的供应商列表
        $supListTmp = Model()->table('store_supplier')->where(array('sup_store_id' => $_SESSION['store_id']))->select();
        //整理数组格式
        $supList = array();
        foreach ($supListTmp as $supKey => $supValue) {
            $supList[$supValue['sup_id']] = $supValue;
        }
        //20170621潘丙福添加结束
        if (!is_numeric($_GET['curpage'])){
           
            //店铺ID获取
            $count = $model_order->getOrderCount($condition);

            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_order->getOrderList($condition,'','*', 'payment_time desc,order_id desc',self::EXPORT_SIZE, array('order_goods','order_common'));
                $this->createExcel($data, $supList);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            if (! $limit1) {
                $data = $model_order->getOrderList($condition,'','*', 'payment_time desc,order_id desc', "{$limit2}",array('order_goods','order_common'));
            }else {
                $data = $model_order->getOrderList($condition,'','*', 'payment_time desc,order_id desc',"{$limit1},{$limit2}",array('order_goods','order_common'));                
            }
            $this->createExcel($data, $supList);
        }
        //var_dump($data);
    }

    private function createExcel($data = array(), $supList = array()){

        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'用户ID');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'支付时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单金额(元)');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单来源');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品SKU');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品名称');
        //20170622潘丙福添加开始
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'供货商名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'额外供货商信息');
        //20170622潘丙福添加结束
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'单件商品价格');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'单件商品云豆');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'购买数量');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收件人姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收件人电话');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收件人地址');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收件人身份信息');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单拆分');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'卖家留言');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品货号');

        foreach ((array)$data as $k=>$order_info){

            foreach ((array)$order_info['extend_order_goods'] as $key1 => $value1) {
                if (count($order_info['extend_order_goods']) > 1) {
                    $list['order_chaifen'] = '订单拆分';
                } else {
                    $list['order_chaifen'] = '';   
                }
                $list['buyer_id'] = $order_info['buyer_id'];
                $list['add_time'] = $order_info['add_time'] ? date('Y-m-d H:i:s', $order_info['add_time']) : '数据异常';
                $list['payment_time'] = $order_info['payment_time'] ? date('Y-m-d H:i:s', $order_info['payment_time']) : ' ';
                $list['order_sn'] = $order_info['order_sn'];
                $list['order_amount'] = ncPriceFormat($order_info['order_amount']);
                if ($order_info['shipping_fee']) {
                    $list['order_amount'] .= '(含运费'.ncPriceFormat($order_info['shipping_fee']).')';
                }
                $list['order_from'] = str_replace(array(1,2), array('PC端','移动端'),$order_info['order_from']);

                //20170622潘丙福添加开始-20170912添加字段goods_commonid
                $supId = Model()->table('goods')->field('goods_commonid,sup_id,ext_sup')->find($value1['goods_id']);
                if ( $supId['sup_id'] == 0 ) {
                    $list['sup_name'] = '无';
                } else {
                    $list['sup_name'] = $supList[$supId['sup_id']]['sup_name'];
                }
                $list['ext_sup'] = $supId['ext_sup'];
                //20170622潘丙福添加结束
                //20170912潘丙福添加开始-获取商品货号
                $goodsSerialInfo      = Model()->table('goods_common')->field('goods_commonid,goods_serial')->find($supId['goods_commonid']);
                $list['goods_serial'] = $goodsSerialInfo['goods_serial'];
                //20170912潘丙福添加结束
                $list['goods_id'] = $value1['goods_id'];
                $list['goods_name'] = $value1['goods_name'];
                $list['goods_price'] = $value1['goods_price'];
                $list['goods_points'] = $value1['goods_points'];
                $list['goods_num'] = $value1['goods_num'];

                $list['reciver_name'] = $order_info['extend_order_common']['reciver_name'];
                $list['mob_phone'] = $order_info['extend_order_common']['reciver_info']['mob_phone'];
                $list['address'] = $order_info['extend_order_common']['reciver_info']['address'];
                $list['buyer_cardid'] = $order_info['buyer_cardid'];
                $list['order_message'] = $order_info['extend_order_common']['order_message'];

                $tmp = array();
                $tmp[] = array('data'=>$list['buyer_id']);
                $tmp[] = array('data'=>$list['add_time']);
                $tmp[] = array('data'=>$list['payment_time']);
                $tmp[] = array('data'=>$list['order_sn']);
                $tmp[] = array('data'=>$list['order_amount']);
                $tmp[] = array('data'=>$list['order_from']);
                $tmp[] = array('data'=>$list['goods_id']);
                $tmp[] = array('data'=>$list['goods_name']);
                //20170622潘丙福添加开始
                $tmp[] = array('data'=>$list['sup_name']);
                $tmp[] = array('data'=>$list['ext_sup']);
                //20170622潘丙福添加结束
                $tmp[] = array('data'=>$list['goods_price']);
                $tmp[] = array('data'=>$list['goods_points']);
                $tmp[] = array('data'=>$list['goods_num']);
                $tmp[] = array('data'=>$list['reciver_name']);
                $tmp[] = array('data'=>$list['mob_phone']);
                $tmp[] = array('data'=>$list['address']);
                $tmp[] = array('data'=>$list['buyer_cardid']);
                $tmp[] = array('data'=>$list['order_chaifen']);
                $tmp[] = array('data'=>$list['order_message']);
                $tmp[] = array('data'=>$list['goods_serial']);
                $excel_data[] = $tmp;
            }   
 
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('商家订单',CHARSET));
        $excel_obj->generateXML('order-'.$_GET['curpage'].'-'.date('Y-m-d-H',time()));

    }

    //20170816潘丙福添加批量导入物流单号
    public function importshowOp()
    {
        Tpl::showpage('store_order_send.import');
    }

    public function doimportOp()
    {
        $file   = $_FILES['csv'];
        /**
         * 上传文件存在判断
         */
        if(empty($file['name'])){
            showMessage('请选择要上传的xlsx、xls、csv文件','','html','error');
        }
        /**
         * 文件来源判定
         */
        if(!is_uploaded_file($file['tmp_name'])){
            showMessage('请上传合法文件','','html','error');
        }
        /**
         * 文件类型判定
         */
        $file_name_array    = explode('.',$file['name']);
        $fileExtension      = strtolower(trim($file_name_array[count($file_name_array)-1]));
        if($fileExtension != 'csv' && $fileExtension != 'xls' && $fileExtension != 'xlsx'){
            showMessage('上传文件类型不正确（只允许上传xlsx、xls、csv文件）'.$fileExtension,'','html','error');
        }
        /**
         * 文件大小判定
         */
        if($file['size'] > intval(ini_get('upload_max_filesize'))*1024*1024){
            showMessage('您上传的文件过大','','html','error');
        }

        $dir = dirname(__FILE__);
        $filename = $file['tmp_name'];
        require $dir.'/../../data/resource/phpexcel/PHPExcel/IOFactory.php';
        $objPHPExcelReader = PHPExcel_IOFactory::load($filename);  //加载临时文件
        //加载模型
        $model_order = Model('order');
        foreach($objPHPExcelReader->getWorksheetIterator() as $sheet)  //循环读取sheet
        {
            foreach($sheet->getRowIterator() as $row)  //逐行处理
            {
                if($row->getRowIndex()<2){
                    continue;
                }
                $tmpArray = array();
                foreach($row->getCellIterator() as $cell)  //逐列读取
                {
                    $tmpArray[] = trim($cell->getValue()); //获取cell中数据
                }
                //重新整理数组
                $updateArray = array();
                $updateArray['order_sn']            = $tmpArray[0];
                $updateArray['shipping_express_id'] = $tmpArray[1];
                $updateArray['shipping_code']       = $tmpArray[2];
                if ($updateArray['order_sn'] <= 0 && $updateArray['shipping_express_id'] <= 0 && $updateArray['shipping_code'] <= 0){
                    //这里在思考要不要记录错误信息
                    continue;
                }
                $model_order = Model('order');
                $condition = array();
                $condition['order_sn'] = $updateArray['order_sn'];
                $condition['store_id'] = $_SESSION['store_id'];
                $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
                $if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
                if ($if_allow_send) {
                    //这里在思考要不要记录错误信息
                    continue;
                }
                if($order_info['order_state']=='20'){
                    $logic_order = Logic('order');
                    $updateArray['reciver_info'] = null;
                    $result = $logic_order->changeOrderSend($order_info, 'seller', $_SESSION['seller_name'], $updateArray);
                    if (!$result['state']) {
                        //这里在思考要不要记录错误信息
                        continue;
                    }
                }
            }
        }
        showMessage('批量导入物流单号成功!','index.php?act=store_order&op=index&state_type=state_pay');
    }
}
