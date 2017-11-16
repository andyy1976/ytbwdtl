<?php
/**
 * 订单管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */
defined('In33hao') or exit('Access Invalid!');
class orderModel extends Model {

    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function getOrderInfo($condition = array(), $extend = array(), $fields = '*', $order = '',$group = '') {
        $order_info = $this->table('orders')->field($fields)->where($condition)->group($group)->order($order)->find();
        if (empty($order_info)) {
            return array();
        }
        if (isset($order_info['order_state'])) {
            $order_info['state_desc'] = orderState($order_info);
        }
        if (isset($order_info['payment_code'])) {
            $order_info['payment_name'] = orderPaymentName($order_info['payment_code']);
        }

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_info['extend_order_common'] = $this->getOrderCommonInfo(array('order_id'=>$order_info['order_id']));
            $order_info['extend_order_common']['reciver_info'] = unserialize($order_info['extend_order_common']['reciver_info']);
            $order_info['extend_order_common']['invoice_info'] = unserialize($order_info['extend_order_common']['invoice_info']);
        }

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $order_info['extend_store'] = Model('store')->getStoreInfo(array('store_id'=>$order_info['store_id']));
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            $order_info['extend_member'] = Model('member')->getMemberInfoByID($order_info['buyer_id']);
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>$order_info['order_id']));
            $order_info['extend_order_goods'] = $order_goods_list;
        }

        return $order_info;
    }

    public function getOrderCommonInfo($condition = array(), $field = '*') {
        return $this->table('order_common')->where($condition)->find();
    }

    public function getOrderPayInfo($condition = array(), $master = false,$lock = false) {
        return $this->table('order_pay')->where($condition)->master($master)->lock($lock)->find();
    }

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $pagesize
     * @param unknown_type $filed
     * @param unknown_type $order
     * @param string $key 以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getOrderPayList($condition, $pagesize = '', $filed = '*', $order = '', $key = '') {
        return $this->table('order_pay')->field($filed)->where($condition)->order($order)->page($pagesize)->key($key)->select();
    }

    //20170418潘丙福添加
    public function getStoreOrderCount($store_id, $order_sn, $buyer_name, $state_type, $query_start_date, $query_end_date, $skip_off)
    {

        $condition = array();
        $condition['store_id'] = $store_id;
        if (preg_match('/^\d{10,20}$/',$order_sn)) {
            $condition['order_sn'] = $order_sn;
        }
        if ($buyer_name != '') {
            $condition['buyer_name'] = $buyer_name;
        }
        if (isset($chain_id)) {
            $condition['chain_id'] = intval($chain_id);
        }
        $allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($state_type, $allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $state_type);
        } else {
            if ($state_type != 'state_notakes') {
                $state_type = 'store_order';
            }
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_start_date);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_end_date);
        $start_unixtime = $if_start_date ? strtotime($query_start_date) : null;
        $end_unixtime = $if_end_date ? strtotime($query_end_date): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($skip_off == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }

        if ($state_type == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_pay') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        // $panModel = Model(order);
        return $this->table('orders')->where($condition)->count();

    }

    /**
     * 取得店铺订单列表
     *
     * @param int $store_id 店铺编号
     * @param string $order_sn 订单sn
     * @param string $buyer_name 买家名称
     * @param string $state_type 订单状态
     * @param string $query_start_date 搜索订单起始时间
     * @param string $query_end_date 搜索订单结束时间
     * @param string $skip_off 跳过已关闭订单
     * @return array $order_list
     */
    public function getStoreOrderList($store_id, $order_sn, $buyer_name, $state_type, $query_start_date, $query_end_date, $skip_off, $fields = '*', $extend = array(), $chain_id = null, $goods_name = null) {
        $condition = array();
        $condition['store_id'] = $store_id;
        if (preg_match('/^\d{10,20}$/',$order_sn)) {
            $condition['order_sn'] = $order_sn;
        }
        //20170422潘丙福添加开始
        if ($goods_name != '') {
            $condition['goods_name'] = array('like', '%'.$goods_name.'%');
        }
        //20170422潘丙福添加结束
        if ($buyer_name != '') {
            $condition['buyer_name'] = $buyer_name;
        }
        if (isset($chain_id)) {
            $condition['chain_id'] = intval($chain_id);
        }
        $allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($state_type, $allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $state_type);
        } else {
            if ($state_type != 'state_notakes') {
                $state_type = 'store_order';
            }
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_start_date);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_end_date);
        $start_unixtime = $if_start_date ? strtotime($query_start_date) : null;
        $end_unixtime = $if_end_date ? strtotime($query_end_date): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($skip_off == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }

        if ($state_type == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_pay') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        //20170623潘丙福添加开始-待发货商品按照付款时间排序
        if ($state_type == 'state_pay') {
            $order_list = $this->getOrderList($condition, 20, $fields, 'payment_time desc','', $extend);
        } else {
            $order_list = $this->getOrderList($condition, 20, $fields, 'order_id desc','', $extend);
        }
        //20170623潘丙福添加结束


        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

            //显示取消订单
            $order_info['if_store_cancel'] = $this->getOrderOperateState('store_cancel',$order_info);
            //显示调整费用
            $order_info['if_modify_price'] = $this->getOrderOperateState('modify_price',$order_info);
			//显示调整订单费用
        	$order_info['if_spay_price'] = $this->getOrderOperateState('spay_price',$order_info);
            //显示发货
            $order_info['if_store_send'] = $this->getOrderOperateState('store_send',$order_info);
            //显示锁定中
            $order_info['if_lock'] = $this->getOrderOperateState('lock',$order_info);
            //显示物流跟踪
            $order_info['if_deliver'] = $this->getOrderOperateState('deliver',$order_info);
            //门店自提订单完成状态
            $order_info['if_chain_receive'] = $this->getOrderOperateState('chain_receive',$order_info);

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

            //取得其它订单类型的信息
            $this->getOrderExtendInfo($order_info);
            $order_list[$key] = $order_info;
        }

        return $order_list;
    }

     //2017.9.8xin增加
     /**
     * 取得店铺订单列表
     *
     * @param int $store_id 店铺编号
     * @param string $order_sn 订单sn
     * @param string $buyer_name 买家名称
     * @param string $state_type 订单状态
     * @param string $query_start_date 搜索订单支付时间
     * @param string $query_end_date 搜索订单支付结束时间
     * @param string $skip_off 跳过已关闭订单
     * @return array $order_list
     */
    public function getStoreOrderList_pay($store_id, $order_sn, $buyer_name, $state_type, $query_start_date, $query_end_date, $skip_off, $fields = '*', $extend = array(), $chain_id = null, $goods_name = null) {
        $condition = array();
        $condition['store_id'] = $store_id;
        if (preg_match('/^\d{10,20}$/',$order_sn)) {
            $condition['order_sn'] = $order_sn;
        }
        //20170422潘丙福添加开始
        if ($goods_name != '') {
            $condition['goods_name'] = array('like', '%'.$goods_name.'%');
        }
        //20170422潘丙福添加结束
        if ($buyer_name != '') {
            $condition['buyer_name'] = $buyer_name;
        }
        if (isset($chain_id)) {
            $condition['chain_id'] = intval($chain_id);
        }
        $allow_state_array = array('state_new','state_pay','state_send','state_success','state_cancel');
        if (in_array($state_type, $allow_state_array)) {
            $condition['order_state'] = str_replace($allow_state_array,
                    array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL), $state_type);
        } else {
            if ($state_type != 'state_notakes') {
                $state_type = 'store_order';
            }
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_start_date);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$query_end_date);
        $start_unixtime = $if_start_date ? strtotime($query_start_date) : null;
        $end_unixtime = $if_end_date ? strtotime($query_end_date): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

        if ($skip_off == 1) {
            $condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
        }

        if ($state_type == 'state_new') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_pay') {
            $condition['chain_code'] = 0;
        }
        if ($state_type == 'state_notakes') {
            $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
            $condition['chain_code'] = array('gt',0);
        }

        //20170623潘丙福添加开始-待发货商品按照付款时间排序
        if ($state_type == 'state_pay') {
            $order_list = $this->getOrderList($condition, 20, $fields, 'payment_time desc','', $extend);
        } else {
            $order_list = $this->getOrderList($condition, 20, $fields, 'order_id desc','', $extend);
        }
        //20170623潘丙福添加结束


        //页面中显示那些操作
        foreach ($order_list as $key => $order_info) {

            //显示取消订单
            $order_info['if_store_cancel'] = $this->getOrderOperateState('store_cancel',$order_info);
            //显示调整费用
            $order_info['if_modify_price'] = $this->getOrderOperateState('modify_price',$order_info);
            //显示调整订单费用
            $order_info['if_spay_price'] = $this->getOrderOperateState('spay_price',$order_info);
            //显示发货
            $order_info['if_store_send'] = $this->getOrderOperateState('store_send',$order_info);
            //显示锁定中
            $order_info['if_lock'] = $this->getOrderOperateState('lock',$order_info);
            //显示物流跟踪
            $order_info['if_deliver'] = $this->getOrderOperateState('deliver',$order_info);
            //门店自提订单完成状态
            $order_info['if_chain_receive'] = $this->getOrderOperateState('chain_receive',$order_info);

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

            //取得其它订单类型的信息
            $this->getOrderExtendInfo($order_info);
            $order_list[$key] = $order_info;
        }

        return $order_list;
    }


    /**
     * 取得订单列表(未被删除)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getNormalOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()){
        $condition['delete_state'] = 0;
        return $this->getOrderList($condition, $pagesize, $field, $order, $limit, $extend);
    }

    /**
     * 取得订单列表(所有)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){

        //20170422潘丙福添加
        if ($condition['goods_name']) {
            //20170422潘丙福添加开始
            //修正where条件
            $condition['orders.store_id'] = $condition['store_id'];
            unset($condition['store_id']);
            $condition['order_goods.goods_name'] = $condition['goods_name'];
            unset($condition['goods_name']);
            //20170422潘丙福添加结束
            $list = $this->table('orders,order_goods')->join('inner')->on('orders.order_id=order_goods.order_id')->field('orders.*')->where($condition)->page($pagesize)->order('orders.order_id desc')->limit($limit)->master($master)->select();
        } else {
            $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        }

        // $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();

        $order_goods = Model('order_goods');//加入的。。。。。。。。。

        //加商品货号字段--20170912潘丙福删除--原因无法处理一订单多商品问题
        // foreach ($list as $key => $v){
        //     $where['order_id'] = $v['order_id'];
        //     $goods_id = Model()->table('order_goods')->field('goods_id')->where($where)->find();
        //     //20170905潘丙福添加开始--从goods_common表获取
        //     $goodsCommonTmp = Model()->table('goods')->field('goods_id,goods_commonid,goods_serial')->find($goods_id['goods_id']);
        //     $where1['goods_commonid'] = $goodsCommonTmp['goods_commonid'];
        //     //20170905潘丙福添加结束
        //     $goods_serial = Model()->table('goods_common')->field('goods_serial')->where($where1)->find();
        //     $list[$key]['goods_serial'] = $goods_serial['goods_serial'];
        // }
        
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
            if (isset($order['order_state'])) {
                $order['state_desc'] = orderState($order);
            }
            if (isset($order['payment_code'])) {
                $order['payment_name'] = orderPaymentName($order['payment_code']);
            }

            // $order_list[$order['order_id']] = $order;
            //20170922潘丙福修改--去掉注释
            if (!empty($extend)){
                $order_list[$order['order_id']] = $order;
            }else{
                $extend[] = 'order_common';
            }
        }
        if (empty($order_list)) $order_list = $list;

        //追加返回订单扩展表信息
        //var_dump($order_list);die;
        if (in_array('order_common',$extend)) {
            $order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
                $order_list[$value['order_id']]['extend_order_common']['order_message'] = $value['order_message'];
            }
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {
                $store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
        if (in_array('member',$extend)) {
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
            }
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
            if (!empty($order_goods_list)) {
                foreach ($order_goods_list as $value) {
                    $order_list[$value['order_id']]['extend_order_goods'][] = $value;  
                    //获取每个订单下总云豆加入的。。。。。。。。。                  
        	        $order_list[$value['order_id']]['order_points']=$order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');                   

                }
            } else {
                $order_list[$value['order_id']]['extend_order_goods'] = array();
            }
        }

        //var_dump($order_list);die;
        return $order_list;
    }

    public function getOrderList2($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){

        //20170422潘丙福添加
        if ($condition['goods_name']) {
            //20170422潘丙福添加开始
            //修正where条件
            $condition['orders.store_id'] = $condition['store_id'];
            unset($condition['store_id']);
            $condition['order_goods.goods_name'] = $condition['goods_name'];
            unset($condition['goods_name']);
            //20170422潘丙福添加结束
            $list = $this->table('orders,order_goods')->join('inner')->on('orders.order_id=order_goods.order_id')->field('orders.*')->where($condition)->page($pagesize)->order('orders.order_id desc')->limit($limit)->master($master)->select();
        } else {
            $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        }

        // $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();

        $order_goods = Model('order_goods');//加入的。。。。。。。。。

        //加商品货号字段--20170912潘丙福删除--原因无法处理一订单多商品问题
        // foreach ($list as $key => $v){
        //     $where['order_id'] = $v['order_id'];
        //     $goods_id = Model()->table('order_goods')->field('goods_id')->where($where)->find();
        //     //20170905潘丙福添加开始--从goods_common表获取
        //     $goodsCommonTmp = Model()->table('goods')->field('goods_id,goods_commonid,goods_serial')->find($goods_id['goods_id']);
        //     $where1['goods_commonid'] = $goodsCommonTmp['goods_commonid'];
        //     //20170905潘丙福添加结束
        //     $goods_serial = Model()->table('goods_common')->field('goods_serial')->where($where1)->find();
        //     $list[$key]['goods_serial'] = $goods_serial['goods_serial'];
        // }
        
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
            if (isset($order['order_state'])) {
                $order['state_desc'] = orderState($order);
            }
            if (isset($order['payment_code'])) {
                $order['payment_name'] = orderPaymentName($order['payment_code']);
            }

            // $order_list[$order['order_id']] = $order;
            //20170922潘丙福修改--去掉注释
            if (!empty($extend)){
                $order_list[$order['order_id']] = $order;
            }else{
                $order_list[$order['order_id']] = $order;
                $extend[] = 'order_common';
            }
        }
        if (empty($order_list)) $order_list = $list;

        //追加返回订单扩展表信息
        //var_dump($order_list);die;
        if (in_array('order_common',$extend)) {
            $order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
                $order_list[$value['order_id']]['extend_order_common']['order_message'] = $value['order_message'];
            }
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {
                $store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
        if (in_array('member',$extend)) {
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
            }
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
            if (!empty($order_goods_list)) {
                foreach ($order_goods_list as $value) {
                    $order_list[$value['order_id']]['extend_order_goods'][] = $value;  
                    //获取每个订单下总云豆加入的。。。。。。。。。                  
                    $order_list[$value['order_id']]['order_points']=$order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');                   

                }
            } else {
                $order_list[$value['order_id']]['extend_order_goods'] = array();
            }
        }

        //var_dump($order_list);die;
        return $order_list;
    }
        /**
     * 取得订单列表(所有)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList1($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){

        //20170422潘丙福添加
        if ($condition['goods_name']) {
            //20170422潘丙福添加开始
            //修正where条件
            $condition['orders.store_id'] = $condition['store_id'];
            unset($condition['store_id']);
            $condition['order_goods.goods_name'] = $condition['goods_name'];
            unset($condition['goods_name']);
            //20170422潘丙福添加结束
            $list = $this->table('orders,order_goods')->join('inner')->on('orders.order_id=order_goods.order_id')->field('orders.*')->where($condition)->page($pagesize)->order('orders.order_id desc')->limit($limit)->master($master)->select();
        } else {
            $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        }

        // $list = $this->table('orders')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();

        $order_goods = Model('order_goods');//加入的。。。。。。。。。

        //加商品货号字段--20170912潘丙福删除--原因无法处理一订单多商品问题
        // foreach ($list as $key => $v){
        //     $where['order_id'] = $v['order_id'];
        //     $goods_id = Model()->table('order_goods')->field('goods_id')->where($where)->find();
        //     //20170905潘丙福添加开始--从goods_common表获取
        //     $goodsCommonTmp = Model()->table('goods')->field('goods_id,goods_commonid,goods_serial')->find($goods_id['goods_id']);
        //     $where1['goods_commonid'] = $goodsCommonTmp['goods_commonid'];
        //     //20170905潘丙福添加结束
        //     $goods_serial = Model()->table('goods_common')->field('goods_serial')->where($where1)->find();
        //     $list[$key]['goods_serial'] = $goods_serial['goods_serial'];
        // }
        
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
            if (isset($order['order_state'])) {
                $order['state_desc'] = orderState($order);
            }
            if (isset($order['payment_code'])) {
                $order['payment_name'] = orderPaymentName($order['payment_code']);
            }

            // $order_list[$order['order_id']] = $order;

            if (!empty($extend)){
                $order_list[$order['order_id']] = $order;
            }else{
                $extend[] = 'order_common';
            }
        }
        if (empty($order_list)) $order_list = $list;

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
                $order_list[$value['order_id']]['extend_order_common']['order_message'] = $value['order_message'];
            }
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
                if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {
                $store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
        if (in_array('member',$extend)) {
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
            }
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
            if (!empty($order_goods_list)) {
                foreach ($order_goods_list as $value) {
                    $order_list[$value['order_id']]['extend_order_goods'][] = $value;  
                    //获取每个订单下总云豆加入的。。。。。。。。。                  
                    $order_list[$value['order_id']]['order_points']=$order_goods->where(array('order_id'=>$value['order_id']))->sum('goods_pay_points');                   

                }
            } else {
                $order_list[$value['order_id']]['extend_order_goods'] = array();
            }
        }

        // print_r(count($order_list));
        return $order_list;
    }

    /**
     * 取得(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount、TakesCount，分别取相应数量缓存，只许传入一个
     * @return array
     */
    public function getOrderCountCache($type, $id, $key) {
        if (!C('cache_open')) return array();
        $type = 'ordercount'.$type;
        $ins = Cache::getInstance('cacheredis');
        $order_info = $ins->hget($id,$type,$key);
        return !is_array($order_info) ? array($key => $order_info) : $order_info;
    }


    /**
     * 设置(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param array $data
     */
    public function editOrderCountCache($type, $id, $data) {
        if (!C('cache_open') || empty($type) || !intval($id) || !is_array($data)) return ;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        $ins->hset($id,$type,$data);
    }

    /**
     * 取得买卖家订单数量某个缓存
     * @param string $type $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount、TakesCount，分别取相应数量缓存，只许传入一个
     * @return int
     */
    public function getOrderCountByID($type, $id, $key) {
        $cache_info = $this->getOrderCountCache($type, $id, $key);

        if (is_string($cache_info[$key])) {
            //从缓存中取得
            $count = $cache_info[$key];
        } else {
            //从数据库中取得
            $field = $type == 'buyer' ? 'buyer_id' : 'store_id';
            $condition = array($field => $id);
            $func = 'getOrderState'.$key;
            $count = $this->$func($condition);
            $this->editOrderCountCache($type,$id,array($key => $count));
        }
        return $count;
    }

    /**
     * 删除(买/卖家)订单全部数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @return bool
     */
    public function delOrderCountCache($type, $id) {
        if (!C('cache_open')) return true;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        return $ins->hdel($id,$type);
    }

    /**
     * 待付款订单数量
     * @param unknown $condition
     * 20170719潘丙福删除--修改当前id的未支付订单超过48小时状态修改为0--移到定时任务中
     */
    public function getOrderStateNewCount($condition = array()) {

        $condition['order_state'] = ORDER_STATE_NEW;
        $condition['chain_code'] = 0;
        return $this->getOrderCount($condition);
    }

    /**
     * 待发货订单数量
     * @param unknown $condition
     */
    public function getOrderStatePayCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_PAY;
        return $this->getOrderCount($condition);
    }

    /**
     * 待收货订单数量
     * @param unknown $condition
     * 20170323潘丙福添加 修改当前id用户的待收货订单超过7天状态修改为40
     */
    public function getOrderStateSendCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SEND;
        return $this->getOrderCount($condition);
    }

    /**
     * 待评价订单数量
     * @param unknown $condition
     */
    public function getOrderStateEvalCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['delete_state'] = 0;
        $condition['evaluation_state'] = 0;
        return $this->getOrderCount($condition);
    }

    /**
     * 待自提订单数量
     * @param unknown $condition
     */
    public function getOrderStateTakesCount($condition = array()) {
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $condition['chain_code'] = array('gt',0);
        return $this->getOrderCount($condition);
    }

    /**
     * 交易中的订单数量
     * @param unknown $condition
     */
    public function getOrderStateTradeCount($condition = array()) {
        $condition['order_state'] = array(array('neq',ORDER_STATE_CANCEL),array('neq',ORDER_STATE_SUCCESS),'and');
        return $this->getOrderCount($condition);
    }

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderCount($condition) {
        if ($condition['goods_name']) {
            //20170422潘丙福添加开始
            //修正where条件
            $condition['orders.store_id'] = $condition['store_id'];
            unset($condition['store_id']);
            $condition['order_goods.goods_name'] = $condition['goods_name'];
            unset($condition['goods_name']);
            //20170422潘丙福添加结束
            return $this->table('orders,order_goods')->join('inner')->on('orders.order_id=order_goods.order_id')->where($condition)->count();
        } else {
            return $this->table('orders')->where($condition)->count();
        }
    }

    /**
     * 取得订单商品表详细信息
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsInfo($condition = array(), $fields = '*', $order = '') {
        return $this->table('order_goods')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 20170323潘丙福添加
     * 取得订单商品表详细信息（二维数组形式）
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsInfoArray($condition = array(), $fields = '*', $order = '') {
        return $this->table('order_goods')->where($condition)->field($fields)->order($order)->select(); 
    }

    /**
     * 20170830潘丙福添加
     * 获取订单商品表goods_id,goods_name字段
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsIdName($condition = array(), $fields = '*', $order = '') {
        return $this->table('order_goods')->where($condition)->field($fields)->order($order)->select(); 
    }

    /**
     * 取得订单商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderGoodsList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'rec_id desc', $group = null, $key = null) {
        return $this->table('order_goods')->field($fields)->where($condition)->limit($limit)->order($order)->group($group)->key($key)->page($page)->select();
    }

    /**
     * 取得订单扩展表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     */
    public function getOrderCommonList($condition = array(), $fields = '*', $order = '', $limit = null) {
        return $this->table('order_common')->field($fields)->where($condition)->order($order)->limit($limit)->select();
    }

    /**
     * 插入订单支付表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderPay($data) {
        return $this->table('order_pay')->insert($data);
    }

    /**
     * 插入订单表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrder($data) {
        $insert = $this->table('orders')->insert($data);
        if ($insert) {
            //更新缓存
            if (C('cache_open')) {
                // QueueClient::push('delOrderCountCache',array('buyer_id'=>$data['buyer_id'],'store_id'=>$data['store_id']));
            }
        }
        return $insert;
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderCommon($data) {
        return $this->table('order_common')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderGoods($data) {
        return $this->table('order_goods')->insertAll($data);
    }
	public function addOrderGood($data) {
        return $this->table('order_goods')->insert($data);
    }

    /**
     * 添加订单日志
     */
    public function addOrderLog($data) {
        $data['log_role'] = str_replace(array('buyer','seller','system','admin'),array('买家','商家','系统','管理员'), $data['log_role']);
        $data['log_time'] = TIMESTAMP;
        return $this->table('order_log')->insert($data);
    }

    /**
     * 更改订单信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrder($data,$condition,$limit = '') {
        $update = $this->table('orders')->where($condition)->limit($limit)->update($data);
        if ($update) {
            //更新缓存
            if (C('cache_open')) {
                // QueueClient::push('delOrderCountCache',$condition);
            }
        }
        return $update;
    }

    /**
     * 更改订单信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderCommon($data,$condition) {
        return $this->table('order_common')->where($condition)->update($data);
    }

    /**
     * 更改订单信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderGoods($data,$condition) {
        return $this->table('order_goods')->where($condition)->update($data);
    }

    /**
     * 更改订单支付信息
     *
     * @param unknown_type $data
     * @param unknown_type $condition
     */
    public function editOrderPay($data,$condition) {
        return $this->table('order_pay')->where($condition)->update($data);
    }

    /**
     * 订单操作历史列表
     * @param unknown $order_id
     * @return Ambigous <multitype:, unknown>
     */
    public function getOrderLogList($condition,$order = '') {
        return $this->table('order_log')->where($condition)->order($order)->select();
    }

    /**
     * 取得单条订单操作记录
     * @param unknown $condition
     * @param string $order
     */
    public function getOrderLogInfo($condition = array(), $order = '') {
        return $this->table('order_log')->where($condition)->order($order)->find();
    }

    /**
     * 返回是否允许某些操作
     * @param unknown $operate
     * @param unknown $order_info
     */
    public function getOrderOperateState($operate,$order_info){

        if (!is_array($order_info) || empty($order_info)) {
            return false;
        }

        if (isset($order_info['if_'.$operate])) {
            return $order_info['if_'.$operate];
        }

        switch ($operate) {

            //买家取消订单
            case 'buyer_cancel':
               $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
                   ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
               break;

           //申请退款
           case 'refund_cancel':
               $state = $order_info['refund'] == 1 && !intval($order_info['lock_state']);
               break;

           //商家取消订单
           case 'store_cancel':
               $state = ($order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] != 'chain') ||
               ($order_info['payment_code'] == 'offline' &&
               in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)));
               break;

           //平台取消订单
           case 'system_cancel':
               $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
               ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
               break;

           //平台收款
           case 'system_receive_pay':
               $state = $order_info['order_state'] == ORDER_STATE_NEW;
               $state = $state && $order_info['payment_code'] == 'online' && $order_info['api_pay_time'];
               break;

           //买家投诉
           case 'complain':
               $state = in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)) ||
                   intval($order_info['finnshed_time']) > (TIMESTAMP - C('complain_time_limit'));
               break;

           case 'payment':
               $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] == 'online';
               break;

            //调整运费
            case 'modify_price':
                $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
                   ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
                $state = floatval($order_info['shipping_fee']) > 0 && $state;
               break;
			   
			//调整商品费用
        	case 'spay_price':
        	    $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
        	       ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
				   $state = floatval($order_info['goods_amount']) > 0 && $state;
        	   break;

            //调整物流单号
            case 'shipping_code':
                $state = !$order_info['lock_state'] && ($order_info['order_state'] == ORDER_STATE_SEND || $order_info['order_state'] == ORDER_STATE_SUCCESS);
                break;

            //20170830潘丙福添加开始--退还运费
            //增加判断is_refund_shipping_fee是否为'0'
            case 'refund_shipping_fee':
                $state = !$order_info['lock_state'] && ($order_info['order_state'] == ORDER_STATE_SEND || $order_info['order_state'] == ORDER_STATE_SUCCESS) && $order_info['is_refund_shipping_fee'] == 0;
                break;

            //发货
            case 'store_send':
                $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY && !$order_info['chain_id'];
                break;

            //收货
            case 'receive':
                $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_SEND;
                break;

            //门店自提完成
            case 'chain_receive':
                $state = !$order_info['lock_state'] && in_array($order_info['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_PAY)) && 
                $order_info['chain_code'];
                break;

            //评价
            case 'evaluation':
           
                if($order_info['is_dm']!=0){
                $state = !$order_info['lock_state'] && !$order_info['evaluation_state'] && $order_info['order_state'] == 50;
                }else{
                $state = !$order_info['lock_state'] && !$order_info['evaluation_state'] && $order_info['order_state'] == ORDER_STATE_SUCCESS;
            }
                break;

            case 'evaluation_again':
                 if($order_info['is_dm']!=0){
                $state = !$order_info['lock_state'] && $order_info['evaluation_state'] && !$order_info['evaluation_again_state'] && $order_info['order_state'] == 50;
                }else{
                $state = !$order_info['lock_state'] && $order_info['evaluation_state'] && !$order_info['evaluation_again_state'] && $order_info['order_state'] == ORDER_STATE_SUCCESS;
            }
               
                break;

            //锁定
            case 'lock':
                $state = intval($order_info['lock_state']) ? true : false;
                break;

            //快递跟踪
            case 'deliver':
                $state = !empty($order_info['shipping_code']) && in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS));
                break;

            //放入回收站
            case 'delete':
                $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS,50)) && $order_info['delete_state'] == 0;
                break;

            //永久删除、从回收站还原
            case 'drop':
            case 'restore':
                $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS,50)) && $order_info['delete_state'] == 1;
                break;

            //分享
            case 'share':
                $state = true;
                break;

        }
        return $state;

    }

    /**
     * 联查订单表订单商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getOrderAndOrderGoodsList($condition, $field = '*', $page = 0, $order = 'rec_id desc') {
        return $this->table('order_goods,orders')->join('inner')->on('order_goods.order_id=orders.order_id')->where($condition)->field($field)->page($page)->order($order)->select();
    }

    /**
     * 订单销售记录 订单状态为20、30、40时
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderAndOrderGoodsSalesRecordList($condition, $field="*", $page = 0, $order = 'rec_id desc') {
        $condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
        return $this->getOrderAndOrderGoodsList($condition, $field, $page, $order);
    }

    /**
     * 取得其它订单类型的信息
     * @param unknown $order_info
     */
    public function getOrderExtendInfo(& $order_info) {
        //取得预定订单数据
        if ($order_info['order_type'] == 2) {
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            //如果是未支付尾款
            if ($result['data']['if_buyer_repay']) {
                $result['data']['order_pay_state'] = false;
            }
            $order_info = $result['data'];
        }
    }
    
    //修改快递公司名称 2017/9/26 xin
    public function editOrder_common($data,$condition){
        $update = $this->table('order_common')->where($condition)->update($data);
        return $update;

    }

}
