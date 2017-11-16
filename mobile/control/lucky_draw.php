<?php
/**
 * 抽奖
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */

defined('In33hao') or exit('Access Invalid!');

class lucky_drawControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }
    
    public function indexOp() 
    {
        $id = intval(trim($_GET['id']));
        if ($id > 0) {
            $sweepstakesInfo = Model()->table('sweepstakes')->find($id);
            //判断是否有此抽奖活动
            if (!$sweepstakesInfo) {
                output_error('此抽奖活动不存在');
            }
            //判断此抽奖活动是否开启
            if ($sweepstakesInfo['sweepstakes_state'] == 0) {
                output_error('此抽奖活动暂未开放');
            }
            //判断此抽奖活动是否处于当前活动时间
            if (time() < $sweepstakesInfo['start_time'] && time() > $sweepstakesInfo['end_time']) {
                output_error('此抽奖活动已经过期');
            }
            //获取奖项信息
            $awardInfo = Model()->table('sweepstakes_award')->field('id,praise_name,praise_content')->where(array('sweepstakes_id' => $id))->select();
        }
        output_data(array('sweepstakesInfo' => $sweepstakesInfo, 'awardInfo' => $awardInfo));
    }

    public function runOp()
    {
        $id = intval(trim($_POST['id']));
        if ($id > 0) {
            $sweepstakesInfo = Model()->table('sweepstakes')->find($id);
            //判断是否有此抽奖活动
            if (!$sweepstakesInfo) {
                output_error('此抽奖活动不存在');
            }
            //判断此抽奖活动是否开启
            if ($sweepstakesInfo['sweepstakes_state'] == 0) {
                output_error('此抽奖活动暂未开放');
            }
            //判断此抽奖活动是否处于当前活动时间
            if (time() < $sweepstakesInfo['start_time'] || time() > $sweepstakesInfo['end_time']) {
                output_error('此抽奖活动已经过期');
            }
        } else {
            output_error('非法id');
        }
        //获取用户信息--主要查看云豆是否满足抽奖需求
        $memberInfo = Model()->table('member')->find($this->member_info['member_id']);
        if ($memberInfo['member_points'] < $sweepstakesInfo['sweepstakes_cons']) {
            output_error('您的云豆余额不足');
        }
        //获取奖项信息
        $prizeArr = $this->prizeArr($id);
        $this->getResult($prizeArr, $memberInfo, $sweepstakesInfo);
    }

    /**
     * 获取奖项信息
     */
    private function prizeArr($id)
    {
        $prize_arr=array();
        $arr=Model()->table('sweepstakes_award')->where(array('sweepstakes_id' => $id))->select();
        foreach($arr as $key=>$val){
            $min_angle = explode(",",$val['min_angle']);
            $max_angle = explode(",",$val['max_angle']);
            if(count($min_angle)>1){
                $val['min_angle']=$min_angle;
            }
            if(count($max_angle)>1){
                $val['max_angle']=$max_angle;
            }
            $prize_arr[$val['id']]=$val;
        }
        return $prize_arr;
    }
    /**
     * 主程序获取相关数据返回到前台页面
     * @param  [type] $priearr [description]
     * @return [type]          [description]
     */
    private function getResult($priearr, $memberInfo, $sweepstakesInfo){
        $arr=array();
        $count=array();
        foreach ($priearr as $key => $val) {
            $arr[$val['id']] = $val['chance'];
            $count[$val['id']] = $val['praise_number'];
        }
        
        $rid = $this->getRand($arr,$count); //根据概率获取奖项id
        $res = $priearr[$rid]; //中奖项
        //根据中奖项生成合理的随机角度
        $min = $res['min_angle'];
        $max = $res['max_angle'];
        if(is_array($min)){ //多等奖的时候
            $i = mt_rand(0,count($min)-1);
            $result['angle'] = mt_rand($min[$i],$max[$i]);
        }else{
            $result['angle'] = mt_rand($min,$max); //随机生成一个角度
        }
        //获取奖项名称以及奖项内容
        $result['praise_name']    = $res['praise_name'];
        $result['praise_content'] = $res['praise_content'];
        $result['order_type']     = $res['is_vr'];
        //奖项数量减1
        $result1 = Model()->table('sweepstakes_award')->where(array('id'=>$rid))->setDec('praise_number',1);
        //消耗云豆log存储
        $pointLogArray = array();
        $pointLogArray['pl_memberid']   = $memberInfo['member_id'];
        $pointLogArray['pl_membername'] = $memberInfo['member_name'];
        $pointLogArray['pl_points']     = '-'.$sweepstakesInfo['sweepstakes_cons'];
        $pointLogArray['pl_addtime']    = time();
        $pointLogArray['pl_desc']       = '参加抽奖消耗云豆，抽奖活动ID:'.$sweepstakesInfo['id'];
        $pointLogArray['pl_stage']      = 'sweepstakes';

        $result2 = false;
        $result2 = Model('points')->addPointsLog($pointLogArray);
        //用户云豆减少
        if ($result2) {
            $result3 = Model()->table('member')->where(array('member_id'=>$memberInfo['member_id']))->setDec('member_points',$sweepstakesInfo['sweepstakes_cons']);
        }
        //中奖信息存储
        $sweepstakesOrderArray = array();
        $sweepstakesOrderArray['member_id']         = $memberInfo['member_id'];
        $sweepstakesOrderArray['award_id']          = $rid;
        $sweepstakesOrderArray['award_content']     = $res['praise_content'];
        $sweepstakesOrderArray['sweepstakes_id']    = $sweepstakesInfo['id'];
        $sweepstakesOrderArray['sweepstakes_name']  = $sweepstakesInfo['sweepstakes_name'];
        $sweepstakesOrderArray['order_type']        = $res['is_vr'];
        $sweepstakesOrderArray['add_time']          = time();
        //20171018潘丙福添加--需要灵活变动  未抽中奖项状态修改为2已取消
        if ($rid == 28) {
            $sweepstakesOrderArray['order_state'] = 2;
        }

        $result4 = Model()->table('sweepstakes_orders')->insert($sweepstakesOrderArray);
        $result['sweeporder_id'] = $result4;
        //返回前台数据
        if ($result1 && $result2 && $result3 && $result4) {
            output_data(array('panaward' => $result));
        }
    }

    /**
     * 随机获取奖项信息
     */

    private function getRand($proArr,$proCount){
        $result = '';
        $proSum=0;
        //概率数组的总概率精度  获取库存不为0的
        foreach($proCount as $key=>$val){
            if($val <= 0){
                continue;
            }else{
                $proSum=$proSum+$proArr[$key];
            }
        }
        //概率数组循环
        foreach ($proArr as $key => $proCur) {
            if($proCount[$key] <= 0){
                continue;
            }else{
                //随机算法
                $randNum = mt_rand(1, $proSum);
                if ($randNum <= $proCur) {
                    $result = $key;
                    break;
                }else{
                    $proSum -= $proCur;
                }
            }
 
        }
        unset ($proArr);
        return $result;
    }
}
