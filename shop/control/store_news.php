<?php
/**
 * 店铺管理界面
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class store_newsControl extends BaseSellerControl {
   
    //给推荐人进行店铺总营业额提成
    public function indexOp() {
    	$order = Model(); 
    	$pd_log	=Model('pd_log');
    	$model_member = Model('member');   	   	
    	$percent=Model('chief');
    	$store=Model('store'); 
    	$chiefs=$percent->getfby_id(7,'chief');     	
    	$i=0;
    	//获取所有在线店铺列表  	
    	$store_list=$store->where(array('store_state'=>1))->field('store_id,member_id')->select();
    	
    	$start_time=strtotime(date('Y-m-01', strtotime('-1 month')));//上个月一号的开始时间
    	$end_time=strtotime(date('Y-m-t', strtotime('-1 month'))) ; 	//上个月最后一天的结束时间     
        // $stime = ltrim(rtrim($start_time)).",".ltrim(rtrim($end_time));
    	$dang_start=strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));//当月第一天
    	$arr['order_state']=array('eq',40);
        $arr['finnshed_time']=array('between',$start_time.','.$end_time);
        $total=0;
    	foreach($store_list as $value){

    		//获取店铺用户的父级
	    	$member_pid=$model_member->getfby_member_id($value['member_id'],'member_pid');
	    	//获取上级用户的名字
	    	$member_parent_name=$model_member->getfby_member_id($member_pid,'member_name');
	    	if(!$member_pid){continue;}
	    	//判断提成是否已发放    	    	    
	    	$config['lg_add_time']=array('gt',$dang_start);
	    	$config['lg_type']=array('eq','store_chief');
            $config['lg_member_id'] = $member_pid;
	    	$conuts=$pd_log->TABLE('log_store')->where($config)->find();
            
	    	// if($conuts){continue;}	
	    	//如果没有发放记录则进行发放    	
    		$arr['store_id']=array('eq',$value['store_id']); 
	 
	    	$moneys = $order->table('orders')->where($arr)->sum('order_amount');	

	    	 if(!$moneys){continue;}    	
	    	$ticheng="$moneys" * "$chiefs"; 
	    	$total += $ticheng;
	    	// $model_member->where(array('member_id'=>$member_pid))->setInc('member_points',$ticheng);
	    	//资金进账记录	    	 	
    	    $data=array('lg_member_id'=>$member_pid,'lg_member_name'=>$member_parent_name,'lg_type'=>'store_chief','lg_av_amount'=>$ticheng,'lg_add_time'=>time(),'lg_desc'=>'下级店铺营业额提成');    	
    	    $ll = $pd_log->table('log_store')->insert($data);	
            if($ll){
                echo 1;
            }else{
                echo 0;
            }
    	    $i++;   

    	} 

    	if($i > 0){
    		echo '本次已给'.$i.'人发放了提成,总金额为'.$total ;
    	}else{
    		echo '提成已经发放，无需再次发放！！！';
    	}         
    }
    
}
