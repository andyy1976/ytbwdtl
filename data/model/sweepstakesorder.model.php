<?php
/**
 * 云豆礼品购物车
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class sweepstakesorderModel extends Model{
    private $product_sn;    //订单编号
    /**
     * 生成云豆兑换订单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @return string
     */
    public function point_snOrder($member_id) {
        return $this->product_sn =  mt_rand(10,99)
        . sprintf('%010d',time() - 946656000)
        . sprintf('%03d', (float) microtime() * 1000)
        . sprintf('%03d', (int) $member_id % 1000);
    }
    /**
     * 通过状态标识获取兑换订单状态
     */
    public function getPointOrderStateBySign(){
        $pointorderstate_arr = array();
        $pointorderstate_arr['canceled'] = array(2,'已取消');
        $pointorderstate_arr['waitship'] = array(20,'待发货');
        $pointorderstate_arr['waitreceiving'] = array(30,'待收货');
        $pointorderstate_arr['finished'] = array(40,'已完成');
        return $pointorderstate_arr;
    }
    /**
     * 通过状态值获取兑换订单状态
     */
    public function getPointOrderState($order_state){
        $pointorderstate_arr = array();
        $pointorderstate_arr[2] = array('canceled','已取消');
        $pointorderstate_arr[20] = array('waitship','待发货');
        $pointorderstate_arr[30] = array('waitreceiving','待收货');
        $pointorderstate_arr[40] = array('finished','已完成');
        if ($pointorderstate_arr[$order_state]){
            return $pointorderstate_arr[$order_state];
        } else {
            return array('unknown','未知');
        }
    }
    /**
     * 新增兑换礼品订单
     * @param array $param
     * @return bool
     */
    public function addPointOrder($param) {
        if (!$param){
            return false;
        }
        $result = $this->table('sweepstakes_orders')->insert($param);
        //清除相关缓存
        if ($result && $param['point_buyerid'] > 0){
            wcache($param['point_buyerid'], array('pointordercount' => null), 'm_pointorder');
        }
        return $result;
    }
    /**
     * 批量添加订单云豆礼品
     * @param array $param  订单礼品信息
     * @return bool
     */
    public function addPointOrderProdAll($param) {
        if (!$param){
            return false;
        }
        $result = $this->table('points_ordergoods')->insertAll($param);
        return $result;
    }

    /**
     * 查询兑换订单礼品列表
     * @param array  $where 查询条件
     * @param string $field 查询字段
     * @param mixed $page 分页
     * @param int $limit 查询条数
     * @param string $order 排序
     * @param string $group 分组
     * @return bool
     */
    public function getPointOrderGoodsList($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        $ordergoods_list = array();
        if (is_array($page)){
            if ($page[1] > 0){
                $ordergoods_list = $this->table('points_ordergoods')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                $ordergoods_list = $this->table('points_ordergoods')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            $ordergoods_list = $this->table('points_ordergoods')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
        if ($ordergoods_list){
            foreach ($ordergoods_list as $k=>$v){
                $v['point_goodsimage_old'] = $v['point_goodsimage'];
                $v['point_goodsimage_small'] = pointprodThumb($v['point_goodsimage'], 'small');
                $v['point_goodsimage'] = pointprodThumb($v['point_goodsimage'], 'mid');
                $ordergoods_list[$k] = $v;
            }
        }
        return $ordergoods_list;
    }

    /**
     * 删除兑换订单信息
     * @param array  $where 查询条件
     * @return bool
     */
    public function delPointOrder($where){
        return $this->table('sweepstakes_orders')->where($where)->delete();
    }
    /**
     * 删除兑换订单礼品信息
     * @param array  $where 查询条件
     * @return bool
     */
    public function delPointOrderGoods($where){
        return $this->table('points_ordergoods')->where($where)->delete();
    }
    /**
     * 删除兑换订单地址信息
     * @param array  $where 查询条件
     * @return bool
     */
    public function delPointOrderAddress($where){
        return $this->table('points_orderaddress')->where($where)->delete();
    }

    /**
     * 添加云豆兑换订单地址
     * @param array $param  订单收货地址信息
     * @return bool
     */
    public function addPointOrderAddress($param){
        if (!$param){
            return false;
        }
        return $this->table('points_orderaddress')->insert($param);
    }
    //20171017潘炳福添加开始--添加抽奖订单地址
    public function addPointOrderAddress1($param){
        if (!$param){
            return false;
        }
        return $this->table('sweepstakes_orderaddress')->insert($param);
    }

    /**
     * 查询兑换订单详情
     * @param array  $where 查询条件
     * @param string $field 查询字段
     * @param string $order 排序
     * @param string $group 分组
     */
    public function getPointOrderInfo($where, $field = '*', $order = '', $group = ''){
        $order_info = $this->table('sweepstakes_orders')->field($field)->where($where)->group($group)->order($order)->find();
        $point_orderstate_arr = $this->getPointOrderState($order_info['order_state']);
        $order_info['point_orderstatetext'] = $point_orderstate_arr[1];
        $order_info['point_orderstatesign'] = $point_orderstate_arr[0];
        //是否可以发货
        $tmp = $this->allowPointOrderShip($order_info);
        $order_info['point_orderallowship'] = $tmp['state'];
        unset($tmp);
        //是否可以修改发货信息
        $tmp = $this->allowEditPointOrderShip($order_info);
        $order_info['point_orderalloweditship'] = $tmp['state'];
        unset($tmp);
        //是否允许取消
        $tmp = $this->allowPointOrderCancel($order_info);
        $order_info['point_orderallowcancel'] = $tmp['state'];
        unset($tmp);
        //是否允许删除
        $tmp = $this->allowPointOrderDelete($order_info);
        $order_info['point_orderallowdelete'] = $tmp['state'];
        unset($tmp);
        //允许确认收货
        $tmp = $this->allowPointOrderReceiving($order_info);
        $order_info['point_orderallowreceiving'] = $tmp['state'];
        unset($tmp);
        return $order_info;
    }

    /**
     * 查询兑换订单收货人地址信息
     */
    public function getPointOrderAddressInfo($where, $field = '*', $order = '', $group = ''){
        return $this->table('sweepstakes_orderaddress')->field($field)->where($where)->group($group)->order($order)->find();
    }

    /**
     * 查询兑换订单列表
     */
    public function getPointOrderList($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                $order_list = $this->table('sweepstakes_orders')->field($field)->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                $order_list = $this->table('sweepstakes_orders')->field($field)->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            $order_list = $this->table('sweepstakes_orders')->field($field)->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
        foreach ($order_list as $k=>$v){
            //订单状态
            $point_orderstate_arr = $this->getPointOrderState($v['order_state']);
            $v['point_orderstatetext'] = $point_orderstate_arr[1];
            $v['point_orderstatesign'] = $point_orderstate_arr[0];
            //是否可以发货
            $tmp = $this->allowPointOrderShip($v);
            $v['point_orderallowship'] = $tmp['state'];
            unset($tmp);
            //是否可以修改发货信息
            $tmp = $this->allowEditPointOrderShip($v);
            $v['point_orderalloweditship'] = $tmp['state'];
            unset($tmp);
            //是否允许取消
            $tmp = $this->allowPointOrderCancel($v);
            $v['point_orderallowcancel'] = $tmp['state'];
            unset($tmp);
            //是否允许删除
            $tmp = $this->allowPointOrderDelete($v);
            $v['point_orderallowdelete'] = $tmp['state'];
            unset($tmp);
            //允许确认收货
            $tmp = $this->allowPointOrderReceiving($v);
            $v['point_orderallowreceiving'] = $tmp['state'];
            unset($tmp);
            $order_list[$k] = $v;
        }
        return $order_list;
    }

    /**
     * 是否可以发货
     * @param array $pointorder_info 兑换订单详情
     * @return array
     */
    public function allowPointOrderShip($pointorder_info){
        if (!$pointorder_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        if ($pointorder_info['order_state'] == $pointorderstate_arr['waitship'][0]){
            return array('state'=>true);
        } else {
            return array('state'=>false);
        }
    }

    /**
     * 是否可以修改发货信息
     * @param array $pointorder_info 兑换订单详情
     * @return array
     */
    public function allowEditPointOrderShip($pointorder_info){
        if (!$pointorder_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        if ($pointorder_info['order_state'] == $pointorderstate_arr['waitreceiving'][0]){
            return array('state'=>true);
        } else {
            return array('state'=>false);
        }
    }

    /**
     * 是否可以确认收货
     * @param array $pointorder_info 兑换订单详情
     * @return array
     */
    public function allowPointOrderReceiving($pointorder_info){
        if (!$pointorder_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        if ($pointorder_info['order_state'] == $pointorderstate_arr['waitreceiving'][0]){
            return array('state'=>true);
        } else {
            return array('state'=>false);
        }
    }

    /**
     * 是否可以发货
     * @param array $pointorder_info 兑换订单详情
     * @return array
     */
    public function allowPointOrderCancel($pointorder_info){
        if (!$pointorder_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        if ($pointorder_info['order_state'] == $pointorderstate_arr['waitship'][0]){
            return array('state'=>true);
        } else {
            return array('state'=>false);
        }
    }

    /**
     * 是否可以发货
     * @param array $pointorder_info 兑换订单详情
     * @return array
     */
    public function allowPointOrderDelete($pointorder_info){
        if (!$pointorder_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        if ($pointorder_info['order_state'] == $pointorderstate_arr['canceled'][0]){
            return array('state'=>true);
        } else {
            return array('state'=>false);
        }
    }

    /**
     * 云豆礼品兑换订单信息修改
     *
     * @param   array $param 修改信息数组
     * @param   array $condition
     */
    public function editPointOrder($where,$param) {
        if(empty($param)) {
            return false;
        }
        return $this->table('sweepstakes_orders')->where($where)->update($param);
    }

    /**
     * 取消兑换商品订单
     */
    public function cancelPointOrder($point_orderid, $member_id = 0) {
        $point_orderid = intval($point_orderid);
        if($point_orderid <= 0) {
            return array('state'=>false,'msg'=>'参数错误');
        }

        //获取订单状态
        $pointorderstate_arr = $this->getPointOrderStateBySign();

        $where = array();
        $where['id'] = $point_orderid;
        if ($member_id > 0){
            $where['member_id'] = $member_id;
        }
        $where['order_state'] = $pointorderstate_arr['waitship'][0];//未发货时可取消
        //查询兑换信息
        $order_info = $this->getPointOrderInfo($where,'id,member_id');
        if (!$order_info){
            return array('state'=>false,'msg'=>'兑换订单信息错误');
        }
        $result = $this->editPointOrder(array('id'=>$order_info['id'],'member_id'=>$order_info['member_id']),array('order_state'=>$pointorderstate_arr['canceled'][0]));
        if ($result){
            return array('state'=>true,'data'=>array('order_info'=>$order_info));
        } else {
            return array('state'=>true,'msg'=>'取消失败');
        }
    }

    /**
     * 兑换订单确认收货
     */
    public function receivingPointOrder($point_orderid, $member_id = 0){
        $point_orderid = intval($point_orderid);
        if($point_orderid <= 0) {
            return array('state'=>false,'msg'=>'参数错误');
        }

        //获取订单状态
        $pointorderstate_arr = $this->getPointOrderStateBySign();

        $where = array();
        $where['id'] = $point_orderid;
        if ($member_id > 0){
            $where['member_id'] = $member_id;
        }
        $where['order_state'] = $pointorderstate_arr['waitreceiving'][0];//已发货待收货
        //更新运费
        $result = $this->editPointOrder($where,array('order_state'=>$pointorderstate_arr['finished'][0]));
        
        if ($result){
            return array('state'=>true);
        } else {
            return array('state'=>true,'msg'=>'确认收货失败');
        }
    }

    /**
     * 查询兑换订单总数
     */
    public function getPointOrderCount($where, $group = ''){
        return $this->table('points_order')->where($where)->group($group)->count();
    }

    /**
     * 查询云豆兑换商品订单及商品列表
     */
    public function getPointOrderAndGoodsList($where, $field = '*', $page = 0, $limit = 0,$order = '', $group = '') {
        if (is_array($page)){
            if ($page[1] > 0){
                return $this->table('points_ordergoods,points_order')->field($field)->join('left')->on('points_ordergoods.point_orderid=points_order.point_orderid')->where($where)->group($group)->page($page[0],$page[1])->limit($limit)->order($order)->select();
            } else {
                return $this->table('points_ordergoods,points_order')->field($field)->join('left')->on('points_ordergoods.point_orderid=points_order.point_orderid')->where($where)->group($group)->page($page[0])->limit($limit)->order($order)->select();
            }
        } else {
            return $this->table('points_ordergoods,points_order')->field($field)->join('left')->on('points_ordergoods.point_orderid=points_order.point_orderid')->where($where)->group($group)->page($page)->limit($limit)->order($order)->select();
        }
    }

    /**
     * 查询云豆兑换商品订单及商品详细
     */
    public function getPointOrderAndGoodsInfo($where, $field = '*', $order = '', $group = '') {
        return $this->table('points_ordergoods,points_order')->field($field)->join('left')->on('points_ordergoods.point_orderid=points_order.point_orderid')->where($where)->group($group)->order($order)->find();
    }

    /**
     * 查询会员已经兑换商品数
     * @param int $member_id 会员编号
     */
    public function getMemberPointsOrderGoodsCount($member_id){
        $info = rcache($member_id, 'm_pointorder', 'pointordercount');
        if (empty($info['pointordercount']) && $info['pointordercount'] !== 0) {
            //获取兑换订单状态
            $pointorderstate_arr = $this->getPointOrderStateBySign();

            $where = array();
            $where['point_buyerid'] = $member_id;
            $where['order_state'] = array('neq',$pointorderstate_arr['canceled'][0]);
            $list = $this->getPointOrderAndGoodsList($where,'SUM(point_goodsnum) as goodsnum');
            $pointordercount = 0;
            if ($list){
                $pointordercount = intval($list[0]['goodsnum']);
            }
            wcache($member_id, array('pointordercount' => $pointordercount), 'm_pointorder');
        } else {
            $pointordercount = intval($info['pointordercount']);
        }
        return $pointordercount;
    }
    /**
     * 创建云豆礼品兑换订单
     */
    public function createOrder($post_arr, $pointprod_arr, $member_info){
        //验证是否存在收货地址
        $address_options = intval($post_arr['address_options']);
        if ($address_options <= 0){
            return array('state'=>false,'msg'=>'请选择收货人地址');
        }
        $address_info = Model('address')->getOneAddress($address_options);
        if (empty($address_info)){
            return array('state'=>false,'msg'=>'收货人地址信息错误');
        }

        //获取兑换订单状态
        $pointorderstate_arr = $this->getPointOrderStateBySign();
        //新增兑换订单
        $order_array        = array();
        $order_array['point_ordersn']       = $this->point_snOrder($member_info['member_id']);
        $order_array['point_buyerid']       = $member_info['member_id'];
        $order_array['point_buyername']     = $member_info['member_name'];
        $order_array['point_buyeremail']    = $member_info['member_email'];
        $order_array['point_addtime']       = time();
        $order_array['point_allpoint']      = $pointprod_arr['pgoods_pointall'];
        //20170720潘丙福添加开始--充值手机号码
        if (trim($post_arr['point_chargephone'])) {
            $order_array['point_chargephone']  = trim($post_arr['point_chargephone']);
        }
        //20170720潘丙福添加结束
        $order_array['point_ordermessage']  = trim($post_arr['pcart_message']);
        $order_array['order_state']    = $pointorderstate_arr['waitship'][0];
        $order_id = $this->addPointOrder($order_array);
        if (!$order_id){
            return array('state'=>false,'msg'=>'兑换操作失败');
        }

        //扣除会员云豆
        $insert_arr = array();
        $insert_arr['pl_memberid'] = $member_info['member_id'];
        $insert_arr['pl_membername'] = $member_info['member_name'];
        $insert_arr['pl_points'] = -$pointprod_arr['pgoods_pointall'];
        $insert_arr['point_ordersn'] = $order_array['point_ordersn'];
        Model('points')->savePointsLog('pointorder',$insert_arr,true);

        //添加订单中的礼品信息
        $model_pointprod = Model('pointprod');

        if($pointprod_arr['pointprod_list']) {
            $output_goods_name = array();//输出显示的对话礼品名称数组
            foreach ($pointprod_arr['pointprod_list'] as $v) {
                $tmp = array();
                $tmp['point_orderid']       = $order_id;
                $tmp['point_goodsid']       = $v['pgoods_id'];
                $tmp['point_goodsname']     = $v['pgoods_name'];
                $tmp['point_goodspoints']   = $v['pgoods_points'];
                $tmp['point_goodsnum']      = $v['quantity'];
                $tmp['point_goodsimage']    = $v['pgoods_image_old'];
                $order_goods_array[] = $tmp;

                //输出显示前3个兑换礼品名称
                if (count($output_goods_name)<3){
                    $output_goods_name[] = $v['pgoods_name'];
                }
                unset($tmp);

                //更新云豆礼品库存
                $tmp = array();
                $tmp['pgoods_id'] = $v['pgoods_id'];
                $tmp['pgoods_storage'] = array('exp',"pgoods_storage-{$v['quantity']}");
                $tmp['pgoods_salenum'] = array('exp',"pgoods_salenum+{$v['quantity']}");
                $pointprod_uparr[] = $tmp;
                unset($tmp);
            }

            //批量新增兑换订单礼品
            $this->addPointOrderProdAll($order_goods_array);
            //更新兑换礼品库存
            foreach ($pointprod_uparr as $v){
                $model_pointprod->editPointProd($v,array('pgoods_id'=>$v['pgoods_id']));
            }
        }

        //清除购物车信息
        Model('pointcart')->delPointCart(array('pmember_id'=>$member_info['member_id']),$member_info['member_id']);

        //保存买家收货地址,添加订单收货地址
        if ($address_info){
            $address_array      = array();
            $address_array['point_orderid']     = $order_id;
            $address_array['point_truename']    = $address_info['true_name'];
            $address_array['point_areaid']      = $address_info['area_id'];
            $address_array['point_areainfo']    = $address_info['area_info'];
            $address_array['point_address']     = $address_info['address'];
            $address_array['point_telphone']    = $address_info['tel_phone'];
            $address_array['point_mobphone']    = $address_info['mob_phone'];
            $this->addPointOrderAddress($address_array);
        }

        return array('state'=>true,'data'=>array('order_id'=>$order_id));
    }
    /**
     * 删除兑换订单
     */
    public function delPointOrderByOrderID($order_id){
        $order_id = intval($order_id);
        if ($order_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //获取订单状态
        $pointorderstate_arr = $this->getPointOrderStateBySign();

        //删除操作
        $where = array();
        $where['id'] = $order_id;
        $where['order_state'] = $pointorderstate_arr['canceled'][0];//只有取消的订单才能删除
        $result = $this->delPointOrder($where);
        if($result) {
            //删除兑换地址信息
            $this->delPointOrderAddress(array('point_orderid'=>$order_id));
            return array('state'=>true);
        } else {
            return array('state'=>false,'msg'=>'删除失败');
        }
    }
    /**
     * 兑换订单发货
     */
    public function shippingPointOrder($order_id,$postarr,$order_info = array()){
        $order_id = intval($order_id);
        if ($order_id <= 0){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //获取订单状态
        $pointorderstate_arr = $this->getPointOrderStateBySign();

        //查询订单信息
        $where = array();
        $where['id'] = $order_id;
        $where['order_state'] = array('in',array($pointorderstate_arr['waitship'][0],$pointorderstate_arr['waitreceiving'][0]));//待发货和已经发货状态

        //如果订单详情不存在，则查询
        if (!$order_info){
            $order_info = $this->getPointOrderInfo($where,'order_state');
        }
        if (!$order_info){
            return array('state'=>false,'msg'=>'参数错误');
        }
        //更新发货信息
        $update_arr = array();
        if ($order_info['order_state'] == $pointorderstate_arr['waitship'][0]){
            $update_arr['order_state']   = $pointorderstate_arr['waitreceiving'][0]; //已经发货,待收货
        }
        $update_arr['shippingcode'] = $postarr['shippingcode'];
        $update_arr['shipping_ecode'] = $postarr['e_code'];
        $result = $this->editPointOrder($where, $update_arr);
        if ($result){
            return array('state'=>true);
        }else {
            return array('state'=>false,'msg'=>'发货修改失败');
        }
    }
}
