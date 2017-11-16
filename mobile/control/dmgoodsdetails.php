<?php
/**
 * 我的订单
 *
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class dmgoodsdetailsControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}
	public function goods_detailOp(){  //获取商品信息
       
	    $goods_id = isset($_POST['goods_id'])?$_POST['goods_id']:'';
		if($goods_id){
			  $mg = Model('goods');
			  $model_gd = Model('order_goods');
			  $model_store = Model('store');
			  $goodsdetails = $mg->where(array('goods_id'=>$goods_id))->find();
              $md_class = Model()->table('goods_class')->where(array('gc_id'=>$goodsdetails['gc_id_1']))->find();
             if($md_class){
                switch ($md_class['gc_name']) {
                    case '丽人':
                    $goodsdetails['class_name']=1;
                        break;
                    case '生活服务':
                    $goodsdetails['class_name']=2;
                        break;
                    case '休闲娱乐':
                     $goodsdetails['class_name']=3;
                     break;
                     case '酒店/客栈':
                     $goodsdetails['class_name']=4;
                     break;
                    case '美食':
                     $goodsdetails['class_name']=5;
                     break;
                    default:
                     $goodsdetails['class_name']=0;
                      break;
                    
                    
                }
              }

			  if($goodsdetails){



                 if(!empty($goodsdetails['dmbuy_rule'])){     //是否有购买规则这一项，没有则不处理。
        $editr = $goodsdetails['dmbuy_rule'];
        $editrall =explode('&', $editr);
        $ttname = explode('|', $editrall[0]);
        $txtName =unserialize($ttname[1]);
        $ttdj = explode('|', $editrall[1]);
        $txtDanj = unserialize($ttdj[1]);
        $txtsl = explode('|', $editrall[2]);
        $txtSulian = unserialize($txtsl[1]);
        $txttl = explode('|', $editrall[3]);
        $txtTel  = unserialize($txttl[1]);
        $txsindex = explode(':', $editrall[4]);
        $txtTRLastIndex=$txsindex[1];
        $goodsdetails['txtName']=$txtName;
        $goodsdetails['txtDanj']=$txtDanj;
        $goodsdetails['txtSulian']=$txtSulian;
         $goodsdetails['txtTel']=$txtTel;
       
}
			 $goods_evaluate_info = Model('evaluate_goods')->getEvaluateGoodsInfoByGoodsID($goods_id); //商品评分
			 $goods_allinfo = Model('evaluate_goods')->table('evaluate_goods')->where(array('geval_goodsid'=>$goods_id))->order('geval_addtime desc')->limit(10)->select();
			 $goodsdetails['ncomment'] = $goods_allinfo;
			 $goodsdetails['comment']=$goods_evaluate_info;
			 $ll = $model_store->where(array('store_id'=>$goodsdetails['store_id']))->find();  //获取店铺详细信息
			   if($ll){//获取实体店铺名
              $goodsdetails['address']=$ll['store_address'];

			   }   
			  $edit =unserialize($goodsdetails['dmbuy_know']);
              $editall = array_filter(explode('&', $edit));
             $goodsdetails['goods_image_url']= cthumb($goodsdetails['goods_image'], 240);//图片地址
             $xsl = $model_gd->table('order_goods')->where(array('goods_id'=>$goods_id))->count();
             $goodsdetails['count']=$xsl;  //计算销售量
             if($goodsdetails['usertc']==1){ //计算赠送云豆数量
              $goodsdetails['yundou'] =$goodsdetails['pointsb']*$goodsdetails['goods_price'];  //B套餐
             }else{
             	$goodsdetails['yundou'] =0.5*$goodsdetails['goods_price'];  //A套餐
             }                            
             if($editall){
            foreach ($editall as $key => $value) {
               $editallnw[] = explode(':',$value);
            }
              
            foreach($editallnw as $k=>$v){
            	if(!empty($v[1])){
             switch ($v[0]) {
             	case 'validate':
             		$goodsdetails['wzvalidate']='有效期:';
             		break;
                 case 'usertime':
             		$goodsdetails['wzusertime']='使用时间:';
             		break;
             	case 'attationpeople':
             		$goodsdetails['wzattationpeople']='预约提醒:';
             		break;
             	case 'ruzhutime':
             		$goodsdetails['wzruzhutime']='入住时间:';
             		break;
             	case 'shopknow':
             		$goodsdetails['wzshopknow']='入住须知:';
             		break;
             	case 'suitpepole':
             		$goodsdetails['wzsuitpepole']='适用人数:';
             		break;
             	case 'otherfree':
             		$goodsdetails['wzotherfree']='其他费用:';
             		break;
             	case 'othercoupon':
             		$goodsdetails['wzothercoupon']='其他优惠:';
             		break;
             	case 'otherglue':
             		$goodsdetails['wzotherglue']='使用规则:';
             		break;
             	case 'otherpeole':
             		$goodsdetails['wzotherpeole']='适用人群:';
             		break;
             	case 'suitcontent':
             	   $goodsdetails['wzsuitcontent']='套餐内容:';
             	    break;
             	
             }
         }
             $goodsdetails[$v[0]]=$v[1];
            }
           
           }
		}
	output_data($goodsdetails);
			}
		
		}
	 public function getMemberOp(){   //获取会员信息
	     $member = Model('member');
	 	 $member_info=$member->where(array('member_id'=>$this->member_info['member_id']))->find();
	 	  output_data($member_info);

	 }
	 public function tuijian_detailOp(){    //推荐页面
       $goods_id = isset($_POST['goods_id'])?$_POST['goods_id']:'';
	  if($goods_id){
	   $mg = Model('goods');
	   $goodsdetails = $mg->where(array('goods_id'=>$goods_id))->find();
	   if($goodsdetails){
	   $goodsdetailsall = $mg->where(array('store_id'=>$goodsdetails['store_id'],'goods_commend'=>1))->order('goods_id desc')->select();
	  foreach ($goodsdetailsall as $key => $value) {
	   $goodsdetailsall[$key]['goods_image_url']= cthumb($value['goods_image'], 120);
	  }
	      output_data($goodsdetailsall);
	     }
      }
 }
 public function getGoodsArrayOp(){
       
      $goods_id = isset($_POST['goods_id'])?$_POST['goods_id']:'';
        if($goods_id){
            $mg = Model('goods');
            $str2 = ',';//strpos 大小写敏感  stripos大小写不敏感    两个函数都是返回str2 在str1 第一次出现的位置
       
           if(strpos($goods_id,$str2) === false){     //使用绝对等于
             $singlegoods = Model()->table('goods')->where(array('goods_id'=>$goods_id))->find();
              if($singlegoods['usertc']==1){ //计算赠送云豆数量
              $singlegoods['yundou'] =$singlegoods['pointsb']*$singlegoods['goods_price'];  //B套餐
             }else{
                $singlegoods['yundou'] =0.5*$singlegoods['goods_price'];  //A套餐
             }      
            }else{
            $bcd = @explode(',', $goods_id);
            foreach ($bcd as $key => $value) {
                 $singlegoods = Model()->table('goods')->where(array('goods_id'=>$value))->find();
              if($singlegoods['usertc']==1){ //计算赠送云豆数量
              $ss[$key] =$singlegoods['pointsb']*$singlegoods['goods_price'];  //B套餐
             }else{
                $ss[$key] =0.5*$singlegoods['goods_price'];  //A套餐
             }      
                }  
            $singlegoods['yundou']=round(array_sum($ss));
                
    
}
         output_data($singlegoods);
          }
 }
   
}
?>