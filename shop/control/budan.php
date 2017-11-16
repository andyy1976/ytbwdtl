<?php

	defined('In33hao') or exit('Access Invalid!');
	class budanControl {

		public function indexOp(){
		
            //给1 2级分成
                // $member_id=$_SESSION['member_id'];
                 
               
                $pd_log = model('pd_log');
                $percent=Model('chief');
                // $orders = $model_order->getOrderInfo(array('pay_sn'=>$pay_sn));
                // $order_goods = $model_order->getOrderGoodsInfoArray(array('order_id'=>$orders['order_id']));
                $member=Model('member');
                $member_id='217041';
                $member_id=explode(",",$member_id);
                foreach ($member_id as $key => $value) {
                        $value=trim($value);
                        $seve=$member->where(array('member_id'=>$value))->find();   
                        $arras['member_points']=array('exp','member_points+500');
                        $arras['member_time']=time();
                        $arras['member_level']='1';
                        $arras['free']='0';
                        $mems = Model()->table('member')->where(array('member_id'=>$value))->find();

                        if($mems['member_level'] ==0){
                            $memberarr=$member->where(array('member_id'=>$value))->update($arras);
                            chief_card($mems['member_name']);                     
                            //赠送500云豆
                            $data_point=array('lg_member_id'=>$value,'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500云豆');
                            $update_point=$pd_log->insert($data_point);
                            $order_money='500';
                            $chiefs=$percent->where(array('id'=>11))->find();

                            // $las =  $mo->table('orders')->where(array('order_id'=>$ord_info['order_id']))->update(array('order_state'=>30));
                            $arr=get_parent_info($value);  
                            if(is_array($arr)){
                                    $mount= $order_money*$chiefs['chief'];
                                           
                                    $buyer_puid=$arr['member_id'];                    
                                    $buyer_pname=$arr['member_name']; 
                                    

                                    $member->where(array('member_id'=>$buyer_puid))->setInc('distributor_predeposit',$mount);
                                    $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_av_amount'=>$mount,'lg_add_time'=>time(),'lg_type'=>'distribution','lg_desc'=>'激活会员下级购买提成');
                                    $pd_log->insert($data);
                            }
                            $arr=get_parent_info($buyer_puid); 
                            if(is_array($arr)){             
                                    $buyer_puid=$arr['member_id'];
                                    $buyer_pname=$arr['member_name'];
                                    $chiefs=$percent->where(array('id'=>12))->find();
                                    $mount=$order_money*$chiefs['chief'];
                                                                     
                                    $member->where(array('member_id'=>$buyer_puid))->setInc('distributor_predeposit',$mount);
                                    $data=array('lg_member_id'=>$buyer_puid,'lg_member_name'=>$buyer_pname,'lg_av_amount'=>$mount,'lg_type'=>'distribution','lg_add_time'=>time(),'lg_desc'=>'激活会员下级购买提成');
                                    //资金变动记录
                                    $pd_log->insert($data);    
                            }
                }
                unset($mems);        
        }
                
		}
	}
?>