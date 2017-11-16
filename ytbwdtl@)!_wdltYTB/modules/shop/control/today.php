<?php
defined('In33hao') or exit('Access Invalid!');
class todayControl extends SystemControl{
	 public function __construct(){
        parent::__construct();
       //   strtotime(date('Y-m-d'));
    }
	public function indexOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('today.index');
	}

	public function every_oneOp(){
		$member = Model('member');	
		$pd_log = Model('predeposit');
		$time = 1483545600;
		$data = array();
		$data['member_time']	= array("gt",$time);
		$data0['member_level']	= array("eq",0);
		$data0['member_time']	= array("gt",$time);
		$data1['member_level']	= array("eq",1);
		$data1['member_time']	= array("gt",$time);
		$data2['member_level']	= array("eq",2);
		$data2['member_time']	= array("gt",$time);
		$data3['member_level']	= array("eq",3);
		$data3['member_time']	= array("gt",$time);
		$data4['member_level']	= array("eq",4);
		$data4['member_time']	= array("gt",$time);
		$member_infos = $member->where($data)->select();//计算当天注册会员所有数据
		$member_info = $member->where($data)->count();//计算当天注册人数总和
		//0级别所有人总和
		$member_ks = $member->where($data0)->select();//计算当天注册0级别所有数据
		$member_k = $member->where($data0)->count();//计算当天注册人数总和
		//1级别所有人总和
		$member_ones = $member->where($data1)->select();//计算当天注册1级别所有数据
		$member_one = $member->where($data1)->count();//计算当天注册人数总和
		//2级别所有人总和
		$member_twos = $member->where($data2)->select();//计算当天注册2级别所有数据
		$member_two = $member->where($data2)->count();//计算当天注册人数总和
		//3级别所有人总和
		$member_threes = $member->where($data3)->select();//计算当天注册3级别所有数据
		$member_three = $member->where($data3)->count();//计算当天注册人数总和
		//4级别所有人总和
		$member_fouss = $member->where($data4)->select();//计算当天注册4级别所有数据
		$member_fous = $member->where($data4)->count();//计算当天注册人数总和

		//当天所有充值总额
		$pd_time['lg_add_time'] = array('gt',$time);
		$pd_time['lg_type']	='recharge';
		$order = "lg_av_amount";
		$pd_l = $pd_log->table('pd_log')->where($pd_time)->field($order)->select();//当天充值总金额
		if(is_array($pd_l)){
			foreach ($pd_l as $key => $value) {
				$total += $value['lg_av_amount'];
			}
		}

		$pd_times['pdc_add_time'] = array('gt',$time);
		$pd_times['predeposit_type']	='3';
		$orde = "pdc_amount";
		$pd_f = $pd_log->table('pd_cash')->where($pd_times)->field($orde)->select();//当天打款总金额
		if(is_array($pd_f)){
			foreach ($pd_f as $key => $value) {
				$totals += $value['pdc_amount'];
			}
		}

		// Tpl::output('today_shuju',$member_info);//计算当天注册会员
		// Tpl::output('today_count',$member_infos);//计算当天注册所有数据
		// Tpl::output('level_one',$member_k);			//计算当天注册0级别人数总和
		// Tpl::output('count_one',$member_ks);
		// Tpl::output('level_one',$member_one);			//计算当天注册1级别人数总和
		// Tpl::output('count_one',$member_ones);//计算当天注册1级别所有数据
		// Tpl::output('level_two',$member_two);			//计算当天注册2级别人数总和
		// Tpl::output('count_two',$member_twos);//计算当天注册2级别所有数据
		// Tpl::output('level_three',$member_three);		//计算当天注册3级别人数总和
		// Tpl::output('count_three',$member_threes);//计算当天注册3级别所有数据
		// Tpl::output('level_fous',$member_fous);			//计算当天注册4级别人数总和
		// Tpl::output('count_fous',$member_fouss);//计算当天注册4级别所有数据
		// Tpl::output('today_mon',$total);//当天充值总金额
		// Tpl::output('today_money',$totals);//当天打款总金额

            $param['today_shuju'] = $member_info;		//计算当天注册会员
            $param['count_k'] = $member_k;			//计算当天注册1级别人数总和
            $param['count_one'] = $member_one;			//计算当天注册1级别人数总和
            $param['count_two'] = $member_two;			//计算当天注册2级别人数总和
            $param['count_trhee'] = $member_three;		//计算当天注册3级别人数总和
            $param['count_fous'] = $member_fous;		//计算当天注册4级别人数总和
            $param['today_mon'] = $total;				//当天充值总金额
            $param['today_money'] = $totals;			//当天打款总金额
        $data['list']['list']	= $param;
        // print_r($data);
		 echo Tpl::flexigridXML($data);exit();

	}



}
?>