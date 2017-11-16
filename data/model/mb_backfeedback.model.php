<?php
/**
 * 意见反馈
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */

defined('In33hao') or exit('Access Invalid!');

class mb_backfeedbackModel extends Model{
    public function __construct(){
        parent::__construct('mb_backfeedback');
    }

    /**
     * 列表
     *
     * @param array $condition 查询条件
     * @param int $page 分页数
     * @param string $order 排序
     * @return array
     */
    public function getMbFeedbackList($condition, $page = null, $order = 'id desc'){
        $list = $this->where($condition)->page($page)->order($order)->select();
        return $list;
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function addMbFeedback($param){
        return $this->insert($param);
    }

    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function delMbFeedback($id){
        $condition = array('id' => array('in', $id));
        return $this->where($condition)->delete();
    }
	/**
	* ID 获取单条信息
	*/
	 public function getFeedbackByID($id) {
       $condition = array('id'=> array('in',$id));
	   return $this->where($condition)->find();
    }
	/**
	* back_feed_ID 获取单条信息
	*/
	 public function getbackFeedbackByID($id) {
       $condition = array('feedback_id'=> array('in',$id));
	   return $this->where($condition)->find();
    }
	
	/**
	*  更新记录
	*/
	  public function updateMbFeedback($id,$param){
		  if (is_array($param)){
            $tmp = array();
            foreach ($param as $k => $v){
                $tmp[$k] = $v;
            }
		  }
        return Db::update('mb_backfeedback',$tmp," id='$id' ");
    }
}
