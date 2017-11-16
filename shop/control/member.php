<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class memberControl extends BaseMemberControl{

    /**
     * 我的商城
     */
    public function homeOp() {
        Tpl::showpage('member_home');
    }

    public function ajax_load_member_infoOp() {

        $member_info = $this->member_info;
        $member_info['security_level'] = Model('member')->getMemberSecurityLevel($member_info);

        //代金券数量
        $member_info['voucher_count'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
        Tpl::output('home_member_info',$member_info);

        Tpl::showpage('member_home.member_info','null_layout');
    }

    public function ajax_load_order_infoOp() {
        $model_order = Model('order');

        //交易提醒 - 显示数量
        $member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'NewCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'SendCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'EvalCount');
        Tpl::output('home_member_info',$member_info);

        //交易提醒 - 显示订单列表
        $condition = array();
        $condition['buyer_id'] = $_SESSION['member_id'];
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS));
        $order_list = $model_order->getNormalOrderList($condition,'','*','order_id desc',3,array('order_goods'));

        foreach ($order_list as $order_id => $order) {
            //显示物流跟踪
            $order_list[$order_id]['if_deliver'] = $model_order->getOrderOperateState('deliver',$order);
            //显示评价
            $order_list[$order_id]['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order);
            //显示支付
            $order_list[$order_id]['if_payment'] = $model_order->getOrderOperateState('payment',$order);
            //显示收货
            $order_list[$order_id]['if_receive'] = $model_order->getOrderOperateState('receive',$order);
        }

        Tpl::output('order_list',$order_list);

        //取出购物车信息
        $model_cart = Model('cart');
        $cart_list  = $model_cart->listCart('db',array('buyer_id'=>$_SESSION['member_id']),3);
        Tpl::output('cart_list',$cart_list);
        Tpl::showpage('member_home.order_info','null_layout');
    }

    public function ajax_load_goods_infoOp() {
        //商品收藏
        $favorites_model = Model('favorites');
        $favorites_list = $favorites_model->getGoodsFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 7);
        if (!empty($favorites_list) && is_array($favorites_list)){
            $favorites_id = array();//收藏的商品编号
            foreach ($favorites_list as $key=>$fav){
                $favorites_id[] = $fav['fav_id'];
            }
            $goods_model = Model('goods');
            $field = 'goods_id,goods_name,store_id,goods_image,goods_promotion_price';
            $goods_list = $goods_model->getGoodsList(array('goods_id' => array('in', $favorites_id)), $field);
            Tpl::output('favorites_list',$goods_list);
        }

        //店铺收藏
        $favorites_list = $favorites_model->getStoreFavoritesList(array('member_id'=>$_SESSION['member_id']), '*', 6);
        if (!empty($favorites_list) && is_array($favorites_list)){
            $favorites_id = array();//收藏的店铺编号
            foreach ($favorites_list as $key=>$fav){
                $favorites_id[] = $fav['fav_id'];
            }
            $store_model = Model('store');
            $store_list = $store_model->getStoreList(array('store_id'=>array('in', $favorites_id)));
            Tpl::output('favorites_store_list',$store_list);
        }

        $goods_count_new = array();
        if (!empty($favorites_id)) {
            foreach ($favorites_id as $v){
                $count = Model('goods')->getGoodsCommonOnlineCount(array('store_id' => $v));
                $goods_count_new[$v] = $count;
            }
        }
        Tpl::output('goods_count',$goods_count_new);
        Tpl::showpage('member_home.goods_info','null_layout');
    }

    public function ajax_load_sns_infoOp() {
        //我的足迹
        $goods_list = Model('goods_browse')->getViewedGoodsList($_SESSION['member_id'],20);
        $viewed_goods = array();
        if(is_array($goods_list) && !empty($goods_list)) {
            foreach ($goods_list as $key => $val) {
                $goods_id = $val['goods_id'];
                $val['url'] = urlShop('goods', 'index', array('goods_id' => $goods_id));
                $val['goods_image'] = thumb($val, 240);
                $viewed_goods[$goods_id] = $val;
            }
        }
        Tpl::output('viewed_goods',$viewed_goods);

        //我的圈子
        $model = Model();
        $circlemember_array = $model->table('circle_member')->where(array('member_id'=>$_SESSION['member_id']))->select();
        if(!empty($circlemember_array)) {
            $circlemember_array = array_under_reset($circlemember_array, 'circle_id');
            $circleid_array = array_keys($circlemember_array);
            $circle_list = $model->table('circle')->where(array('circle_id'=>array('in', $circleid_array)))->limit(6)->select();
            Tpl::output('circle_list', $circle_list);
        }

        //好友动态
        $model_fd = Model('sns_friend');
        $fields = 'member.member_id,member.member_name,member.member_avatar';
        $follow_list = $model_fd->listFriend(array('limit'=>15,'friend_frommid'=>"{$_SESSION['member_id']}"),$fields,'','detail');
        $member_ids = array();$follow_list_new = array();
        if (is_array($follow_list)) {
            foreach ($follow_list as $v) {
                $follow_list_new[$v['member_id']] = $v;
                $member_ids[] = $v['member_id'];
            }
        }
        $tracelog_model = Model('sns_tracelog');
        //条件
        $condition = array();
        $condition['trace_memberid'] = array('in',$member_ids);
        $condition['trace_privacy'] = 0;
        $condition['trace_state'] = 0;
        $tracelist = Model()->table('sns_tracelog')->where($condition)->field('count(*) as ccount,trace_memberid')->group('trace_memberid')->limit(5)->select();
        $tracelist_new = array();$follow_list = array();
        if (!empty($tracelist)){
            foreach ($tracelist as $k=>$v){
                $tracelist_new[$v['trace_memberid']] = $v['ccount'];
                $follow_list[] = $follow_list_new[$v['trace_memberid']];
            }
        }
        Tpl::output('tracelist',$tracelist_new);
        Tpl::output('follow_list',$follow_list);
        Tpl::showpage('member_home.sns_info','null_layout');
    }
    //以下代码为自己添加的
    //省市县下面的端口查询方法，省代查询本省下所有端口和会员，市代查询本市下所有端口和会员，区代查询本区下所有端口和会员，
    public function teamOp() {    	
    	$member = Model('member');    	
    	switch ($_SESSION['member_level'])
	    {			
			case 2:
			  $area= $member ->getfby_member_id($_SESSION['member_id'],'member_areaid');
			  $member_info=$member->where(array('member_level'=>array('lt',2),'member_areaid'=>$area))->field('member_id,member_name,member_avatar,member_mobile,member_level,editor_name')->select();
			  break;
			case 3:
			  $area= $member ->getfby_member_id($_SESSION['member_id'],'member_cityid');
			  $member_info=$member->where(array('member_level'=>array('lt',2),'member_cityid'=>$area))->field('member_id,member_name,member_avatar,member_mobile,member_level,editor_name')->select();
			  break;
			case 4:
			  $area= $member ->getfby_member_id($_SESSION['member_id'],'member_provinceid');
			  $member_info=$member->where(array('member_level'=>array('lt',2),'member_provinceid'=>$area))->field('member_id,member_name,member_avatar,member_mobile,member_level,editor_name')->select();
			  break;			
		} 		    	
    	
    	Tpl::output('member_info',$member_info);
        Tpl::showpage('member_team');
    }
    //ajax端口设置方法
    public function partOp() {         	
        $member = Model('member');
        $member_id=$_POST['member_id'];
        $chief = Model('chief');
        $chiefs=0;//存放该会员级别可以设置的最大端口数量
        //通过用户的代理级别获取可设置的端口数量
        switch ($_SESSION['member_level'])
	    {			
			case 2:
			  $chiefs= $chief ->getfby_id(11,'chief');
			  break;
			case 3:
			  $chiefs= $chief ->getfby_id(10,'chief');
			  break;
			case 4:
			  $chiefs= $chief ->getfby_id(9,'chief');
			  break;			
		}  
        //获取当前代理已设置的端口数量    
        $member_part_sum=$member->getfby_member_id($_SESSION['member_id'],'member_part_sum');  
          
        $part=$_POST['part'];
        $member_info=get_member_info($member_id);       
        //part值为1时给予会员端口，为2时取消该会员端口
    	if($part==1){
    		if($member_info['member_level']==1){echo '该会员已经是端口级别';
    		}else{
    			 if($member_part_sum < $chiefs){
    			 	$member->where(array('member_id'=>$member_id))->update(array('member_level'=>1,'editor_name'=>$_SESSION['member_name']));
    			 	$member->where(array('member_id'=>$_SESSION['member_id']))->setInc('member_part_sum',1);
    		        echo '端口设置成功';
    			 }else{
    			 	echo '您的端口设置数量已达到上限，如需设置请先取消之前设置的端口';
    			 }   		     
    	    }
    	}elseif($part==2){
    		if($member_info['member_level']==0){echo '该会员为普通会员，无需取消操作！';
    		}else{
    			//判断操作者的权限，是否是自己先前设置的端口，如果不是则不能取消
    			if($_SESSION['member_name'] != $member_info['editor_name']){
        	          echo '您不是该端口的给予者，无法取消该会员的端口级别';exit;
                 }
    		     $member->where(array('member_id'=>$member_id))->update(array('member_level'=>0,'editor_name'=>''));
    		     $member->where(array('member_id'=>$_SESSION['member_id']))->setDec('member_part_sum',1);
    		     echo '端口取消成功';
    	    }
    	}
    }
    //代理查看下面人数和个人详情
    public function myteamOp() {    	
    	$member = Model('member');   	
    	$field='member_id,member_pid,member_name,member_mobile,member_level';
    	$t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
    	$provinceid= $member ->getfby_member_id($_SESSION['member_id'],'member_provinceid'); 
    	$cityid= $member ->getfby_member_id($_SESSION['member_id'],'member_cityid');
    	$areaid= $member ->getfby_member_id($_SESSION['member_id'],'member_areaid');    	  	
    	if($_GET['level'])  {
	    	switch ($_GET['level'])
		   {
		   			
			case 1:
              if($_SESSION['member_level']==5){
                  $data['member_provinceid']=$provinceid;  
                  $data['member_level']=1;
              }elseif($_SESSION['member_level']==4){
                  $data['member_cityid']=$cityid;  
                  $data['member_level']=1;
              }elseif($_SESSION['member_level']==3){
                  $data['member_areaid']=$areaid;  
                  $data['member_level']=1;
              }elseif($_SESSION['member_level']==2){
                  $data['portid']=$partid;  
                  $data['member_level']=1;
              }
              break;
            case 2:
              if($_SESSION['member_level']==5){
                  $data['member_provinceid']=$provinceid;  
                  $data['member_level']=2;
              }elseif($_SESSION['member_level']==4){
                  $data['member_cityid']=$cityid;  
                  $data['member_level']=2;
              }elseif($_SESSION['member_level']==3){
                  $data['member_areaid']=$areaid;  
                  $data['member_level']=2;
              }
              break;
            case 3:
              if($_SESSION['member_level']==4){
                  $data['member_cityid']=$cityid;  
                  $data['member_level']=3;
              }else{
                  $data['member_provinceid']=$provinceid;  
                  $data['member_level']=3;
              }
              break;
            case 4:
              $data['member_provinceid']=$provinceid;  
              $data['member_level']=4;
              break;
            case 8://一级直推
              $data['member_pid']=$_SESSION['member_id'];
              break;
            case 9://今日新增
              if($_SESSION['member_level']==5){
                  $data['member_provinceid']=$provinceid;               
              }elseif($_SESSION['member_level']==4){
                  $data['member_cityid']=$cityid;                 
              }elseif($_SESSION['member_level']==3){
                  $data['member_areaid']=$areaid;  
              }elseif($_SESSION['member_level']==2){
                  $data['portid']=$partid;  
              }               
              $data['member_time']=array('gt',$start);
              break;
            case 10://未激活
              if($_SESSION['member_level']==5){
                  $data['member_provinceid']=$provinceid; 
                    $data['member_level'] = 0;          
              }elseif($_SESSION['member_level']==4){
                  $data['member_cityid']=$cityid; 
                    $data['member_level'] = 0;                
              }elseif($_SESSION['member_level']==3){
                  $data['member_areaid']=$areaid; 
                    $data['member_level'] = 0; 
              }elseif($_SESSION['member_level']==2){
                  $data['portid']=$partid; 
                    $data['member_level'] = 0; 
              }       
              $data['member_level']=0;
              break;                            
            	
		  }
		}else{
			switch ($_SESSION['member_level'])
		    {	
				case 3:
				  $data['member_provinceid']=$provinceid; 
				  $data['member_cityid']=$cityid;
				  $data['member_areaid']=$areaid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;
				case 4:
				  $data['member_provinceid']=$provinceid; 
				  $data['member_cityid']=$cityid; 
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;
				case 5:
				  $data['member_provinceid']=$provinceid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;			
		    }
			
		}		
    	$member_infos = $member ->where($data)->field($field)->page(50)->select(); 
    	if($_GET['level']==11){$member_infos = $member ->getMembersListtwo(array('member_pid'=>$_SESSION['member_id']));}
    	switch ($_SESSION['member_level'])
		{	
			case 3:
				  $member_part  = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_areaid'=>$areaid,'member_level'=>2))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_areaid'=>$areaid,'member_level'=>1))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_areaid'=>$areaid,'member_time'=>array('gt',$start)))->field('member_id,member_name,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;
			case 4:
				  $member_quxian= $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_level'=>3))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的区代人数
				  $member_part  = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_level'=>2))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_level'=>1))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_time'=>array('gt',$start)))->field('member_id,member_name,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;
			case 5:
				  $member_city  = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>4))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的市代人数 
    	          $member_quxian= $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>3))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的区代人数
				  $member_part  = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>2))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>1))->field('member_id,member_name,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_provinceid'=>$provinceid,'member_time'=>array('gt',$start)))->field('member_id,member_name,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;			
		}    	
    	Tpl::output('show_page',$member->showpage());
    	Tpl::output('member_infos',$member_infos);
    	Tpl::output('member_new',$member_new);
        Tpl::output('member_city',$member_city);
        Tpl::output('member_quxian',$member_quxian);
        Tpl::output('member_part',$member_part);
        Tpl::output('member_vip',$member_vip);
        Tpl::showpage('member_myteam');
    }
     //新增
        public function memberinfoOp(){
            if($_POST){
                if(!empty($_POST) || !isset($_POST) || is_numeric($_POST)){
                   $model = model('member');
                   $area = model('area');
                   $member_info =  $model->getMemberInfo(array("member_id"=>$_POST['member_id']));
                   $info['member_id'] = $member_info['member_id'];
                   if($member_info['member_level'] == 0){
                        $info['member_level'] = "见习会员";
                   }
                   if($member_info['member_level'] == 1){
                        $info['member_level'] = "会员";
                   }
                   if($member_info['member_level'] == 2){
                        $info['member_level'] = "端口";
                   }
                   if($member_info['member_level'] == 3){
                        $info['member_level'] = "区县代理";
                   }
                   if($member_info['member_level'] == 4){
                        $info['member_level'] = "市代理";
                   }
                   if($member_info['member_level'] == 5){
                        $info['member_level'] = "省代理";
                   }
                   $area_info = $area->getAreas();
                   foreach ($area_info as $key => $value) {
                        foreach ($value as $ky =>$v) {
                            //获取省级名字
                            if($ky==$member_info['member_provinceid'] && $info['member_provinceid'] =="" ){
                                if(!is_array($v)){
                                    $info['member_provinceid'] = $v;
                                    }else{
                                        $info['member_provinceid'] = "";
                                    }
                                

                            }
                            if($ky==$member_info['member_cityid'] && $info['member_cityid'] ==""){
                                if(!is_array($v)){
                                    $info['member_cityid'] = $v;
                                    }else{
                                        $info['member_cityid'] = "";
                                    }

                            }
                            if($ky==$member_info['member_areaid'] && $info['member_areaid'] ==""){
                               if(!is_array($v)){$info['member_areaid'] = $v;
                                    }else{
                                        $info['member_areaid'] = "";
                                    }
                            }
                        }
                   }

                   echo $member_info['member_id'].'+'. $info['member_level']."+".$info['member_provinceid']."+".$info['member_cityid']."+".$info['member_areaid'];
                   
                }else{
                return array('error'=>'您提交的不少数字类型的');
                }

            }else{
                return array('error'=>'错误操作');
            }
        }
}
