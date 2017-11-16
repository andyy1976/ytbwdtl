<?php
/**
 * 任务计划 - 天执行的任务
 *
 * 
 * @好商城 提供技术支持 授权请购买shopnc授权
 * @license    http://www.33h ao.com
 * @link       交流群号：138 182 377
 */
defined('In33hao') or exit('Access Invalid!');

class countsControl extends BaseCronControl {

    /**
     * 默认方法
     * 每周一零点30分（30 0 * * 1）把商品的编辑次数恢复为0
     */
    public function indexOp()
    {
        $models = Model()->table('goods_common');
        //查询的条件
        $updateWhere = array();
        $updateWhere['edit_counts'] = array('gt', 0);
        //更新的数据
        $updateArray = array();
        $updateArray['edit_counts'] = 0;
        //执行操作
        $models->where($updateWhere)->update($updateArray);
    }
}