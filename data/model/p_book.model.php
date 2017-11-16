<?php
/**
 * 预售管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class p_bookModel extends Model {
    const STATE1 = 1;       // 开启
    const STATE0 = 0;       // 关闭

    public function __construct() {
        parent::__construct('p_book_quota');
    }

    /**
     * 预售套餐列表
     *
     * @param array $condition
     * @param string $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getBookQuotaList($condition, $field = '*', $page = null, $order = 'bkq_id desc') {
        return $this->field($field)->where($condition)->order($order)->page($page)->select();
    }

    /**
     * 预售套餐详细信息
     *
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBookQuotaInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 保存预售套餐
     *
     * @param array $insert
     * @param boolean $replace
     * @return boolean
     */
    public function addBookQuota($insert, $replace = false) {
        return $this->insert($insert, $replace);
    }

    /**
     * 编辑预售套餐
     * @param array $update
     * @param array $condition
     * @return array
     */
    public function editBookQuota($update, $condition) {
        return $this->where($condition)->update($update);
    }
    
    /**
     * 查询全部预售商品
     * @param unknown $condition
     * @param unknown $field
     * @param number $page
     * @param string $order
     */
    public function getAllGoodsList($condition, $field, $page = 10, $order = 'goods_id desc') {
        $condition['is_presell|is_book'] = 1;
        return Model('goods')->getGoodsList($condition, $field, '', $order, 0, $page);
    }

    /**
     * 定金预售商品列表
     * @param unknown $condition
     * @param string $field
     */
    public function getBookGoodsList($condition, $field = '*', $page = 10, $order = 'goods_id desc') {
        $condition['is_book'] = 1;
        return Model('goods')->getGoodsList($condition, $field, '', $order, 0, $page);
    }
    
    /**
     * 添加定金预售商品活动
     * @param array $update
     * @param int $goods_id
     * @return boolean
     */
    public function addBookGoodsByGoodsId($update, $goods_id) {
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            QueueClient::push('updateGoodsPromotionPriceByGoodsId', $goods_id);
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 删除定金预售商品活动
     * @param int $goods_id
     */
    public function delBookGoodsByGoodsId($goods_id) {
        $update = array();
        $update['is_book'] = 0;
        $update['book_down_payment'] = 0;
        $update['book_final_payment'] = 0;
        $update['book_down_time'] = 0;
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            QueueClient::push('updateGoodsPromotionPriceByGoodsId', $goods_id);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 全款预售商品列表
     * @param unknown $condition
     * @param string $field
     */
    public function getPersellGoodsList($condition, $field = '*', $page = 10, $order = 'goods_id desc') {
        $condition['is_presell'] = 1;
        return Model('goods')->getGoodsList($condition, $field, '', $order, 0, $page);
    }
    
    /**
     * 添加全款预售商品活动
     * @param array $update
     * @param int $goods_id
     * @return boolean
     */
    public function addPresellGoodsByGoodsId($update, $goods_id) {
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 删除全款预售商品活动
     * @param int $goods_id
     * 20170925潘丙福修改删除全款预售商品活动时需要恢复价格
     */
    public function delPresellGoodsByGoodsId($goods_id) {
        //1查询商品信息
        $presellGoodsInfo = Model()->table('goods')->field('goods_id,is_presell,goods_price,goods_points,old_presell_goods_price,old_presell_goods_points')->find($goods_id);
        if ($presellGoodsInfo['is_presell'] != 1) {
            return false;
        }
        //2修改商品价格
        $update = array();
        $update['is_presell'] = 0;
        $update['presell_deliverdate'] = 0;
        //20170925潘丙福添加开始--更新的字段
        $update['goods_price']                = $presellGoodsInfo['old_presell_goods_price'];
        $update['goods_points']               = $presellGoodsInfo['old_presell_goods_points'];
        $update['old_presell_goods_price']    = 0;
        $update['old_presell_goods_points']   = 0;
        //20170925潘丙福添加结束
        $result = Model('goods')->editGoodsById($update, $goods_id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}
