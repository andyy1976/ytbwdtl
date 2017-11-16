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

class mb_feedbackModel extends Model{
    public function __construct(){
        parent::__construct('mb_feedback');
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
     * 站内信列表
     * @param   array $param    条件数组
     * @param   object $page    分页对象调用
     */
    public function listMessage($condition,$page='') {
		
        //得到条件语句
        $condition_str = $this->getCondition($condition);
	

        $param  = array();
        $param['table']     = 'mb_feedback';
        $param['where']     = $condition_str;
        $param['order']     = 'id DESC';
        $message_list       = Db::select($param,$page);
		$paramb = Model("mb_backfeedback");
		if(!empty($message_list)){
		 foreach($message_list as $key=>$v){
			$paramc=$paramb->getbackFeedbackByID($v['id']);
			if($paramc){
				$message_list[$key]['bcontent']=$paramc['content'];
				$message_list[$key]['bftime']=$paramc['ftime'];
				}
			}
		}
        return $message_list;
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
	*  获取单条信息
	*/
	 public function getFeedbackByID($id) {
       $condition = array('id'=> array('in',$id));
	   return $this->where($condition)->find();
    }
	/**
	根据memberid获取单条信息
	*/
	 public function getFeedbackBymID($id) {
       $condition = array('member_id'=> array('in',$id));
	   return $this->where($condition)->find();
    }
	
	    private function getCondition($condition_array){
        $condition_sql =" and mb_feedback.member_id = '{$condition_array['member_id']}'";
	    return $condition_sql;
		}
}
