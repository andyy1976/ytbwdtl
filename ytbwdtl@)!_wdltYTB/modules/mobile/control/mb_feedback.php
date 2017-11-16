<?php
/**
 * 合作伙伴管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class mb_feedbackControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('mobile');
    }

    public function indexOp() {
        $this->flistOp();
    }
    /**
     * 意见反馈
     */
    public function flistOp(){
        $model_mb_feedback = Model('mb_feedback');
        $list = $model_mb_feedback->getMbFeedbackList(array(), 10);

        Tpl::output('list', $list);
        Tpl::output('page', $model_mb_feedback->showpage());
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_feedback.index');
    }

    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_mb_feedback = Model('mb_feedback');
		$model_mb_backfeedback = Model('mb_backfeedback');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('id', 'content', 'ftime', 'member_name', 'member_id');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $inform_list = $model_mb_feedback->getMbFeedbackList($condition, $page, $order);
        $data = array();
        $data['now_page'] = $model_mb_feedback->shownowpage();
        $data['total_num'] = $model_mb_feedback->gettotalnum();
        foreach ($inform_list as $value) {
			$inform_listt=$model_mb_backfeedback->getbackFeedbackByID($value['id']);
            $param = array();
            $param['operation'] = "<a class='btn orange' href='javascript:void(0);' onclick=\"fg_verify('" . $value['id'] . "')\"><i class='fa fa-check-square'></i>回复</a><a class='btn red' href=\"javascript:void(0);\" onclick=\"fg_del('".$value['id']."')\"><i class='fa fa-trash-o'></i>删除</a>";
            $param['id'] = $value['id'];
            $param['content'] = $value['content'];
            $param['ftime'] = date('Y-m-d H:i:s', $value['ftime']);
            $param['member_name'] = $value['member_name'];
            $param['member_id'] = $value['member_id'];
			$param['admin_name']=$inform_listt['admin_name'];
			$param['admin_ip']=$inform_listt['admin_ip'];
			$param['fftime']='';
			if(!empty( $inform_listt['ftime'])){
			$param['fftime']=date('Y-m-d H:i:s', $inform_listt['ftime']);
			}
			$param['backcontent']=$inform_listt['content'];
            $data['list'][$value['id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 删除
     */
    public function delOp(){
        $ids = explode(',', $_GET['id']);
        if (count($ids) == 0){
            exit(json_encode(array('state'=>false,'msg'=>L('wrong_argument'))));
        }
        $model_mb_feedback = Model('mb_feedback');
        $result = $model_mb_feedback->delMbFeedback($ids);
        if ($result){
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        }else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }
	 /**
     * 审核商品
     */
 public function goods_verifyOp(){
        /*if (chksubmit()) {
            $commonid = intval($_POST['commonid']);
            if ($commonid <= 0) {
                    showDialog(L('nc_common_op_fail'), 'reload');
            }
            $update2 = array();
            $update2['goods_verify'] = intval($_POST['verify_state']);

            $update1 = array();
            $update1['goods_verifyremark'] = trim($_POST['verify_reason']);
            $update1 = array_merge($update1, $update2);
            $where = array();
            $where['goods_commonid'] = $commonid;

            $model_goods = Model('goods');
            if (intval($_POST['verify_state']) == 0) {
                $model_goods->editProducesVerifyFail($where, $update1, $update2);
            } else {
                $model_goods->editProduces($where, $update1, $update2);
            }
            showDialog(L('nc_common_op_succ'), '', 'succ', '$("#flexigrid").flexReload();CUR_DIALOG.close();');
        }*/
		if(chksubmit()){
			$model_mb_backfeedback = Model('mb_backfeedback');
			$commonid=intval($_POST['commonid']);
			$param = array();
            $param['content'] = $_POST['verify_reason'];
            $param['type'] =  $_POST['client_type'];
            $param['ftime'] = TIMESTAMP;
            $param['admin_id'] =  $_POST['admin_id'];
            $param['admin_name'] =  $_POST['admin_name'];
            $param['feedback_id'] =  $_POST['commonid'];
			$param['admin_ip']= $_POST['server_ip'];
			$shifouyou=$model_mb_backfeedback->getbackFeedbackByID($commonid);
			 if($shifouyou){
			$result=  $model_mb_backfeedback->updateMbFeedback($shifouyou['id'],$param);
			showDialog(L('nc_common_op_succ'), '', 'succ', '');
             }else{
			$result = $model_mb_backfeedback->addMbFeedback($param);
            showDialog(L('nc_common_op_succ'), '', 'succ', '');
		}
           }
        $common_info = Model('mb_feedback')->getFeedbackByID($_GET['id']);
		$admin_info=$this->admin_info;
        Tpl::output('common_info', $common_info);
	    Tpl::output('admin_info',$admin_info);					
		Tpl::setDirquna('mobile');
        Tpl::showpage('goods.verify_remark', 'null_layout');
    }
	
	

}

 
