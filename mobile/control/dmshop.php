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
class dmshopControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    public function dmgoodsclassOp(){     //商品二级分类链接
    	$class1=isset($_POST['class1'])?$_POST['class1']:'';
    	$class2=isset($_POST['class2'])?$_POST['class2']:'';
    	$class3=isset($_POST['class3'])?$_POST['class3']:'';
    	$class4=isset($_POST['class4'])?$_POST['class4']:'';
    	$class5=isset($_POST['class5'])?$_POST['class5']:'';
    	$class6=isset($_POST['class6'])?$_POST['class6']:'';
    	$class7=isset($_POST['class7'])?$_POST['class7']:'';
    	$class8=isset($_POST['class8'])?$_POST['class8']:'';
    	$store_class_id=isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
    	$data = array();
    	$fl1=$this->get_store_class($class1,$store_class_id);
    	$data[0]['sc_name']=$fl1['gc_name'];
    	$data[0]['sc_id'] = $fl1['gc_id'];
    	$data[0]['gc_parent_id'] = $fl1['gc_parent_id'];
    	$data[0]['count'] = intval($fl1['count']);
    	$fl2=$this->get_store_class($class2,$store_class_id);
    	$data[1]['sc_name']=$fl2['gc_name'];
    	$data[1]['sc_id'] = $fl2['gc_id'];
    	$data[1]['gc_parent_id'] = $fl2['gc_parent_id'];
    	$data[1]['count'] = intval($fl2['count']);
    	$fl3=$this->get_store_class($class3,$store_class_id);
    	$data[2]['sc_name']=$fl3['gc_name'];
    	$data[2]['sc_id'] = $fl3['gc_id'];
    	$data[2]['gc_parent_id'] = $fl3['gc_parent_id'];
    	$data[2]['count'] = intval($fl3['count']);
    	$fl4=$this->get_store_class($class4,$store_class_id);
    	$data[3]['sc_name']=$fl4['gc_name'];
    	$data[3]['sc_id'] = $fl4['gc_id'];
    	$data[3]['gc_parent_id'] = $fl4['gc_parent_id'];
    	$data[3]['count'] = intval($fl4['count']);
    	$fl5=$this->get_store_class($class5,$store_class_id);
    	$data[4]['sc_name']=$fl5['gc_name'];
    	$data[4]['sc_id'] = $fl5['gc_id'];
    	$data[4]['gc_parent_id'] = $fl5['gc_parent_id'];
    	$data[4]['count'] = intval($fl5['count']);
    	$fl6=$this->get_store_class($class6,$store_class_id);
    	$data[5]['sc_name']=$fl6['gc_name'];
    	$data[5]['sc_id'] = $fl6['gc_id'];
    	$data[5]['gc_parent_id'] = $fl6['gc_parent_id'];
    	$data[5]['count'] = intval($fl6['count']);
    	$fl7=$this->get_store_class($class7,$store_class_id);
    	$data[6]['sc_name']=$fl7['gc_name'];
    	$data[6]['sc_id'] = $fl7['gc_id'];
    	$data[6]['gc_parent_id'] = $fl7['gc_parent_id'];
    	$data[6]['count'] = intval($fl7['count']);
    	$fl8=$this->get_store_class($class8,$store_class_id);
    	$data[7]['sc_name']=$fl8['gc_name'];
    	$data[7]['sc_id'] = $fl8['gc_id'];
    	$data[7]['gc_parent_id'] = $fl8['gc_parent_id'];
    	$data[7]['count'] = intval($fl8['count']);
    	$data['allcont'] = intval($fl1['count'])+intval($fl2['count'])+intval($fl3['count'])+intval($fl4['count'])+intval($fl5['count'])+intval($fl6['count'])+intval($fl7['count'])+intval($fl8['count']);
    	output_data($data);

    }


	public function dmstoreclassOp(){    //商品分类链接

		$class1=isset($_POST['class1'])?$_POST['class1']:'';
		$class2=isset($_POST['class2'])?$_POST['class2']:'';
		$class3=isset($_POST['class3'])?$_POST['class3']:'';
		$class4=isset($_POST['class4'])?$_POST['class4']:'';
		$class5=isset($_POST['class5'])?$_POST['class5']:'';
		$data = array();
    $bs1 =$this->get_store_class($class1,0);
		$data[0]['sc_name']=$bs1['gc_name'];
		$data[0]['sc_id'] = $bs1['gc_id'];
		$bs2 =$this->get_store_class($class2,0);
		$data[1]['sc_name']=$bs2['gc_name'];
		$data[1]['sc_id'] = $bs2['gc_id'];
		$bs3 =$this->get_store_class($class3,0);
		$data[2]['sc_name']=$bs3['gc_name'];
		$data[2]['sc_id'] = $bs3['gc_id'];
		$bs4 =$this->get_store_class($class4,0);
		$data[3]['sc_name']=$bs4['gc_name'];
		$data[3]['sc_id'] = $bs4['gc_id'];
		$bs5 =$this->get_store_class($class5,0);
		$data[4]['sc_name']=$bs5['gc_name'];
		$data[4]['sc_id'] = $bs5['gc_id'];

		output_data($data);
	}
    private function get_store_class($name,$nid){   //根据名称回调数据店铺分类
    	$store_class = Model('goods_class');
    	$model_gd = Model('goods');
    	$condition = array();
    	$condition['store_flag']=1;
    	$condition['gc_name']=$name;
    	if(!empty($nid)){
    		$condition['gc_parent_id']=$nid;
    	}else{
    		$condition['gc_parent_id']=0;
    	}
    	$condition['show_type']=1;
    	$get_store_class = $store_class->where($condition)->find();
    	if($get_store_class){
    		$get_store_class['count'] = $model_gd->where(array('gc_id_3'=>$get_store_class['gc_id']))->count();
    	}
    	return $get_store_class;
    }

    public function guessgoodsOp(){    //猜你喜欢及模板数据返回
    	$lng = isset($_POST['lngg'])?$_POST['lngg']:'';
    	$lat = isset($_POST['latt'])?$_POST['latt']:'';
    	$model_mem = Model('goods_browse');
    	$model_st = Model('store');
    	$model_gd = Model('order_goods');
    	$model_store_map = Model('store_map');
    	$dts = $model_mem->table('goods_browse')->where(array('member_id'=>$this->member_info['member_id']))->order('browsetime desc')->limit(4)->select();
    	$goods_model = Model('goods');
    	$voucher_model = Model('voucher_template');
    	$dmgoods_id = array();
          foreach ($dts as $key => $value) {   //根据猜你喜欢的参数查找。
          	$bsd = $goods_model->table('goods')->where(array('goods_id'=>$value['goods_id'],'goods_verify'=>1,'goods_state'=>1))->find();
          	if($bsd['isdmgoods']==1){
          		$voucher_price = $voucher_model->where(array('voucher_t_state'=>1,'voucher_t_store_id'=>$bsd['store_id']))->select();
          		$address_info = $model_st->getStoreInfoByID($bsd['store_id']);
          		$ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$bsd['store_id']));
                if($ll&&!empty($lng)&&!empty($lat)){   //计算距离
                	$dmgoods_id[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
                }else{
                	$dmgoods_id[$key]['distance']=0;
                }
                if(!empty($value['goods_id'])){    //计算销售数量
                	$xsl = $model_gd->table('order_goods')->where(array('goods_id'=>$value['goods_id']))->count();
                	$dmgoods_id[$key]['count']=$xsl;
                }else{
                	$dmgoods_id[$key]['count']=0;	
                }
                if($voucher_price){  //计算代金券数量及价格
                	$vci =array();
                	foreach ($voucher_price as $k => $v) {
                		$vci[$k] = $v['voucher_t_price']; 
                	} 
                	$string=implode(',',$vci);
                	$dmgoods_id[$key]['voucher_count']=count($vci);
                	$dmgoods_id[$key]['voucher_price']=$string;
                }else{
                	$dmgoods_id[$key]['voucher_count']=0;
                	$dmgoods_id[$key]['voucher_price']=0;
                }
                $dmgoods_id[$key]['store_address']=$address_info['store_address'];
                $dmgoods_id[$key]['goods_id']=$bsd['goods_id'];
                $dmgoods_id[$key]['store_id']=$bsd['store_id'];
                $dmgoods_id[$key]['store_name']=$bsd['store_name'];
                $dmgoods_id[$key]['goods_name'] = $bsd['goods_name'];
                $dmgoods_id[$key]['goods_price']=$bsd['goods_price'];
                $dmgoods_id[$key]['goods_marketprice']=$bsd['goods_marketprice'];
                $dmgoods_id[$key]['goods_image'] = cthumb($bsd['goods_image'], 240);
            }
        }
        output_data($dmgoods_id);


    }

    public function getcityOp(){   //根据一级地名找到二级地面获取地区
    	$provice = isset($_POST['provice'])?$_POST['provice']:'';
    	$city = isset($_POST['city'])?$_POST['city']:'';
    	if(!empty($provice)){
    		$model_area =  Model('area');
    		$area_details = $model_area->where(array('area_name'=>$provice,'area_parent_id'=>0))->find();
    		if($area_details){
    			$aller_area = $model_area->where(array('area_parent_id'=>$area_details['area_id'],'area_name'=>$city))->find();
    			if($aller_area){
    				$allqu = $model_area->where(array('area_parent_id'=>$aller_area['area_id']))->select();
    			}
    			output_data($allqu);
    		}
    	}
    }
   public function gethotcityOp(){  //根据一级地名找到二级地面
   	$provice = isset($_POST['provice'])?$_POST['provice']:'';
   	if(!empty($provice)){
   		$model_area = Model('area');
   		$area_details = $model_area->where(array('area_name'=>$provice,'area_parent_id'=>0))->find();
   		if($area_details){
   			$aller_area = $model_area->where(array('area_parent_id'=>$area_details['area_id']))->select();
   		}
   		output_data($aller_area);
   	}
   }
     public function getcharOp(){    //根据字母找城市名
     	$char = isset($_POST['char'])?$_POST['char']:'';
     	if(!empty($char)){
     		$model_area = Model('area');
     		$aller_area = $model_area->where(array('area_charcter'=>''.$char.'','area_deep'=>2))->select();
     		output_data($aller_area);
     	}

     }
    public function rukuOp(){     //定位城市入库以方便查找最近访问城市

    	$city = isset($_POST['city'])?$_POST['city']:'';
    	$model_area = Model('area');
    	if(!empty($city)){
    		$zcs = $model_area->where(array('area_name'=>$city))->find();
    	}
    	$memid = $this->member_info['member_id'];
      $datanow=strtotime(date("Y-m-d H:i:s",time()-7*24*3600));
    	$model_zuijincity = Model('dm_city');
      $dmcity = array();
      $dmcity['member_id']=$memid;
      $dmcity['add_time']=array('lt',$datanow);
      
      $okcity = $model_zuijincity->table('dm_city')->where($dmcity)->delete();
    	$bds = $model_zuijincity->table('dm_city')->where(array('cityname'=>$city,'member_id'=>$memid))->find();	
      if(!$bds){
    		$data = array('cityname'=>$city,'member_id'=>$memid,'area_id'=>$zcs['area_id'],'add_time'=>strtotime(date("Y-m-d H:i:s")));
    		$model_zuijincity->insert($data);
      }
      output_data(1);

    	}
    public function getrecentcityOp(){ //定位城市出库以方便查找最近访问城市
    	$model_zuijincity = Model('dm_city');
    	$memid = $this->member_info['member_id']; 
    	$bds = $model_zuijincity->where(array('member_id'=>$memid))->select();
    	output_data($bds);

    }
    public function getvrcityOp(){   //虚拟定位城市，查出所有内容
    	$city_id = isset($_POST['city_id'])?$_POST['city_id']:'';
    	if(!empty($city_id)){
    		$allcontet=array();
    		$model_area = Model('area');
    		$aller_area = $model_area->where(array('area_id'=>$city_id,'area_deep'=>2))->find();
    		$allcontet['benshi'] = $aller_area;   //获取本市名
    		if(!empty($aller_area['area_parent_id'])){ //获取上级名称
    			$aller_shen = $model_area->where(array('area_id'=>$aller_area['area_parent_id']))->find();
    			$aller_hotcity = $model_area->where(array('area_parent_id'=>$aller_shen['area_id']))->select();
    			$aller_quxian = $model_area->where(array('area_parent_id'=>$city_id))->select();
    		}
    		$allcontet['quxian']=$aller_quxian;
    		$allcontet['provice']=$aller_shen;
    		$allcontet['hotcity']=$aller_hotcity;
    		output_data($allcontet);

    	}


    }
   public function getthiscityOp(){  //根据值查拼音和文字搜索城市ID
   	$char = isset($_POST['char'])?$_POST['char']:'';
    $b = isset($_POST['b'])?$_POST['b']:'';
     $model_area = Model('area');  
      $condition = array();
   	if(!empty($char)){
   	
    
   		$condition['area_name'] = $char;
   		$condition['area_pingyin']=$char;
   		$condition['_op'] = 'or';
    }
     if($b){
      $condition['area_id'] = intval($b);
      }
   		$list =  $model_area->where($condition)->find();
           if(empty($list)){  //如果没有则失败
           	echo 1;
           }else{
           	output_data($list);
           }
       }

   

    /** 
* @desc 根据两点间的经纬度计算距离 
* @param float $lat 纬度值 
* @param float $lng 经度值 
*/
function getDistance($lat1, $lng1, $lat2, $lng2) 
{ 
           $earthRadius = 6367.729; //approximate radius of earth in meters 

/* 
Convert these degrees to radians 
to work with the formula 
*/

$lat1 = ($lat1 * pi() ) / 180; 
$lng1 = ($lng1 * pi() ) / 180; 

$lat2 = ($lat2 * pi() ) / 180; 
$lng2 = ($lng2 * pi() ) / 180; 

/* 
Using the 
Haversine formula 
 
http://en.wikipedia.org/wiki/Haversine_formula 
 
calculate the distance 
*/
$calcLongitude = $lng2 - $lng1; 
$calcLatitude = $lat2 - $lat1; 
$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2); 
$stepTwo = 2 * asin(min(1, sqrt($stepOne))); 
$calculatedDistance = $earthRadius * $stepTwo; 
return round($calculatedDistance); 
} 
  public function dmgetclassnameOp(){     //根据ID获取类名称
  	$obj = isset($_POST['obj'])?$_POST['obj']:'';
  	if(!empty($obj)){
  		$store_class = Model('goods_class');
  		$condition = array();
  		$condition['store_flag']=1;
  		$condition['gc_id']=$obj;
  		$condition['show_type']=1;
  		$get_store_class = $store_class->where($condition)->find();
  		output_data($get_store_class);

  	}
  }
   public function dmgoodsspclassthreeOp(){     //动态获取二级分类

   	$store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';  
   	   //$obj = isset($_POST['obj'])?$_POST['obj']:'';
   	if(!empty($store_class_id)){
   		$store_class = Model('goods_class');
   		$store_goods = Model('goods');
   		$array_sjid = array();
   		$condition=array();
   		$condition['gc_id']=$store_class_id;
   		$condition['store_flag']=1;
   		$condition['show_type']=1;
   		$get_store_class = $store_class->where($condition)->select();
        foreach ($get_store_class as $key => $value){    //循环出所有的二级ID
        	$array_sjid[$key] = $value['gc_id'];
        }
        $char = @implode(",", $array_sjid);
        $tiaojiao = array();
        $tiaojiao['gc_parent_id']=array('in',$char);
        $allsj_class = $store_class->where($tiaojiao)->limit(8)->select();
     foreach ($allsj_class as $k => $v) {   //计算产品数量
     	$singlegoods = $store_goods->table('goods')->where(array('gc_id'=>$v['gc_id']))->count();
     	$allsj_class[$k]['count'] =$singlegoods;
     	$se += $singlegoods;
     }
     $allsj_class[0]['total'] = $se;
     output_data($allsj_class);

 }
}
   	public function dmgoodsascOp(){  //切换出三级分类效果
   		$obj = isset($_POST['obj'])?$_POST['obj']:'';
   		$store_class = Model('goods_class');
   		$tiaojiao = array();
   		$tiaojiao['gc_parent_id']=$obj;
   		$allsj_class = $store_class->where($tiaojiao)->limit(8)->select();
   		output_data($allsj_class);

   	}
   	public function goods1Op(){
   		$store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
   		$lng= isset($_POST['lng'])?$_POST['lng']:'';
   		$lat= isset($_POST['lat'])?$_POST['lat']:'';
      $city = isset($_POST['city'])?$_POST['city']:'';  //定位重要条件
      $model_st = Model('store');
      $model_gd = Model('order_goods');
      $model_store_map = Model('store_map');
      $all_store_id = array();    //获取map_id的store_id;
      $all_store_map_id = $model_store_map->table('store_map')->where(array('baidu_city'=>$city))->select();
          foreach ($all_store_map_id as $b => $c) {   //循环获取有效的store_id;
          	$all_single_store_id = $model_st->where(array('store_state'=>1,'store_flag'=>1,'store_id'=>$c['store_id']))->find();
          	$all_store_id[$b]  = $all_single_store_id['store_id'];

          }
          $nall_store_id=@array_unique($all_store_id);
          $nnall_store_id=@array_filter($nall_store_id);
          $store_id_string = @implode(',',  $nnall_store_id);
          $goods_model = Model('goods');
          $condition = array();
          $condition['isdmgoods']=1;
          $condition['goods_state']=1;
		  $condition['goods_verify']=1;
		  $condition['goods_state']=1;
          $condition['gc_id_1']=$store_class_id;
          $condition['store_id']=array('in',$store_id_string);
          $condition['goods_commend']=1;    //推荐的
          $allgoods = $goods_model->where($condition)->order('goods_id desc')->select();
          $singsotre = array();
          foreach ($allgoods as $k => $v) {
           $singsotre[$k] = $v['goods_id'];
          }
          $singlegoodsid= @implode(',',  $singsotre);
          if(!empty($singlegoodsid)){
            $allgoodsg  = $goods_model->where(array('goods_id'=>array('in',$singlegoodsid)))->order('goods_id desc')->group('store_id')->limit(3)->select();
          }

          foreach ($allgoodsg as $key => $value) {
          	$allgoodsg[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
          	$address_info = $model_st->getStoreInfoByID($value['store_id']);
          	$allgoodsg[$key]['store_address']=$address_info['store_address'];
          	$ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
          	if($ll&&!empty($lng)&&!empty($lat)){
          		$allgoodsg[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
          	}else{
          		$allgoodsg[$key]['distance']=0;
          	}

          }
          output_data($allgoodsg);


      }
      public function goods2Op(){

      	$store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
      	$lng= isset($_POST['lng'])?$_POST['lng']:'';
      	$lat= isset($_POST['lat'])?$_POST['lat']:'';
      	$city = isset($_POST['city'])?$_POST['city']:'';
      	$model_st = Model('store');
      	$model_gd = Model('order_goods');
      	$model_store_map = Model('store_map');
          $all_store_id = array();    //获取map_id的store_id;
          $all_store_map_id = $model_store_map->table('store_map')->where(array('baidu_city'=>$city))->select();
          foreach ($all_store_map_id as $b => $c) {   //循环获取有效的store_id;
          	$all_single_store_id = $model_st->where(array('store_state'=>1,'store_flag'=>1,'store_id'=>$c['store_id']))->find();
          	$all_store_id[$b]  = $all_single_store_id['store_id'];

          }
          $nall_store_id=@array_unique($all_store_id);
          $nnall_store_id=@array_filter($nall_store_id);
          $store_id_string = @implode(',',  $nnall_store_id);
          $goods_model = Model('goods');
          $condition = array();
          $condition['isdmgoods']=1;
          $condition['goods_state']=1;
		  $condition['goods_verify']=1;
		  $condition['goods_state']=1;
          //$condition['gc_id_1']=$store_class_id;
          $condition['gc_id_1|gc_id_2|gc_id_3|gc_id'] = $store_class_id;
          $condition['goods_commend']=0;    //没推荐的
          $condition['store_id']=array('in',$store_id_string);
          $allgoods = $goods_model->where($condition)->order('goods_id desc')->select();
          $singsotre = array();
          foreach ($allgoods as $k => $v) {
           $singsotre[$k] = $v['goods_id'];
          }
          $singlegoodsid= @implode(',',  $singsotre);
          if(!empty($singlegoodsid)){
            $allgoodsg  = $goods_model->where(array('goods_id'=>array('in',$singlegoodsid)))->order('goods_id desc')->group('store_id')->select();
          }
         
          foreach ($allgoodsg as $key => $value) {
            $allgoodsg[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
            $address_info = $model_st->getStoreInfoByID($value['store_id']);
            $allgoodsg[$key]['store_address']=$address_info['store_address'];
            $ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
            if($ll&&!empty($lng)&&!empty($lat)){
              $allgoodsg[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
            }else{
              $allgoodsg[$key]['distance']=0;
            }

          }
          output_data($allgoodsg);


      }
    public function goods1disOp(){    //根据距离列出结果
         $store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
        $lng= isset($_POST['lng'])?$_POST['lng']:'';
        $lat= isset($_POST['lat'])?$_POST['lat']:'';
        $city = isset($_POST['city'])?$_POST['city']:'';
        $obj = isset($_POST['obj'])?$_POST['obj']:'';
        $model_st = Model('store');
        $model_gd = Model('order_goods');
        $model_store_map = Model('store_map');
          $all_store_id = array();    //获取map_id的store_id;
          $all_store_map_id = $model_store_map->table('store_map')->where(array('baidu_city'=>$city))->select();
          foreach ($all_store_map_id as $b => $c) {   //循环获取有效的store_id;
            $all_single_store_id = $model_st->where(array('store_state'=>1,'store_flag'=>1,'store_id'=>$c['store_id']))->find();
            $all_store_id[$b]  = $all_single_store_id['store_id'];

          }
          $nall_store_id=@array_unique($all_store_id);
          $nnall_store_id=@array_filter($nall_store_id);
          $store_id_string = @implode(',',  $nnall_store_id);
          $goods_model = Model('goods');
          $condition = array();
          $condition['isdmgoods']=1;
          $condition['goods_state']=1;
		  $condition['goods_verify']=1;
		  $condition['goods_state']=1;
          $condition['gc_id_1']=$store_class_id;
          $condition['goods_commend']=1;    //没推荐的
          $condition['store_id']=array('in',$store_id_string);
          $allgoods = $goods_model->where($condition)->order('goods_id desc')->select();
          $singsotre = array();
          foreach ($allgoods as $k => $v) {
           $singsotre[$k] = $v['goods_id'];
          }
          $singlegoodsid= @implode(',',  $singsotre);
          if(!empty($singlegoodsid)){
            $allgoodsg  = $goods_model->where(array('goods_id'=>array('in',$singlegoodsid)))->order('goods_id desc')->group('store_id')->limit(3)->select();
          }
         
          foreach ($allgoodsg as $key => $value) {
            $allgoodsg[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
            $address_info = $model_st->getStoreInfoByID($value['store_id']);
            $allgoodsg[$key]['store_address']=$address_info['store_address'];
            $ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
            if($ll&&!empty($lng)&&!empty($lat)){
              $allgoodsg[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
            }else{
              $allgoodsg[$key]['distance']=0;
            }

          }
          if($obj != '9'){
            foreach ($allgoodsg as $k=> $v) {
          if($v['distance']<$obj){
                $allgoogs[$k]=$allgoodsg[$k] ;
              }

            }
          }else{
            $allgoogs=$allgoodsg;
          }
          //var_dump($allgoogs);
          
          output_data($allgoogs);




      }


   	public function goods2disOp(){    //根据距离列出结果
   		  $store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
        $lng= isset($_POST['lng'])?$_POST['lng']:'';
        $lat= isset($_POST['lat'])?$_POST['lat']:'';
        $city = isset($_POST['city'])?$_POST['city']:'';
        $obj = isset($_POST['obj'])?$_POST['obj']:'';
        $model_st = Model('store');
        $model_gd = Model('order_goods');
        $model_store_map = Model('store_map');
          $all_store_id = array();    //获取map_id的store_id;
          $all_store_map_id = $model_store_map->table('store_map')->where(array('baidu_city'=>$city))->select();
          foreach ($all_store_map_id as $b => $c) {   //循环获取有效的store_id;
            $all_single_store_id = $model_st->where(array('store_state'=>1,'store_flag'=>1,'store_id'=>$c['store_id']))->find();
            $all_store_id[$b]  = $all_single_store_id['store_id'];

          }
          $nall_store_id=@array_unique($all_store_id);
          $nnall_store_id=@array_filter($nall_store_id);
          $store_id_string = @implode(',',  $nnall_store_id);
          $goods_model = Model('goods');
          $condition = array();
          $condition['isdmgoods']=1;
          $condition['goods_state']=1;
		  $condition['goods_verify']=1;
		  $condition['goods_state']=1;
          $condition['gc_id_1']=$store_class_id;
          $condition['goods_commend']=0;    //没推荐的
          $condition['store_id']=array('in',$store_id_string);
          $allgoods = $goods_model->where($condition)->order('goods_id desc')->select();
          $singsotre = array();
          foreach ($allgoods as $k => $v) {
           $singsotre[$k] = $v['goods_id'];
          }
          $singlegoodsid= @implode(',',  $singsotre);
          if(!empty($singlegoodsid)){
            $allgoodsg  = $goods_model->where(array('goods_id'=>array('in',$singlegoodsid)))->order('goods_id desc')->group('store_id')->select();
          }
         
          foreach ($allgoodsg as $key => $value) {
            $allgoodsg[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
            $address_info = $model_st->getStoreInfoByID($value['store_id']);
            $allgoodsg[$key]['store_address']=$address_info['store_address'];
            $ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
            if($ll&&!empty($lng)&&!empty($lat)){
              $allgoodsg[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
            }else{
              $allgoodsg[$key]['distance']=0;
            }

          }
          if($obj != '9'){
          	foreach ($allgoodsg as $k=> $v) {
          if($v['distance']<$obj){
          			$allgoogs[$k]=$allgoodsg[$k] ;
          		}

          	}
          }else{
          	$allgoogs=$allgoodsg;
          }
          //var_dump($allgoogs);
          
          output_data($allgoogs);




      }

      public function goods3sortOp(){
        $store_class_id = isset($_POST['store_class_id'])?$_POST['store_class_id']:'';
        $lng= isset($_POST['lng'])?$_POST['lng']:'';
        $lat= isset($_POST['lat'])?$_POST['lat']:'';
        $city = isset($_POST['city'])?$_POST['city']:'';
        $obj = isset($_POST['obj'])?$_POST['obj']:'';
        $model_st = Model('store');
        $model_gd = Model('order_goods');
        $model_store_map = Model('store_map');
          $all_store_id = array();    //获取map_id的store_id;
          $all_store_map_id = $model_store_map->table('store_map')->where(array('baidu_city'=>$city))->select();
          foreach ($all_store_map_id as $b => $c) {   //循环获取有效的store_id;
            $all_single_store_id = $model_st->where(array('store_state'=>1,'store_flag'=>1,'store_id'=>$c['store_id']))->find();
            $all_store_id[$b]  = $all_single_store_id['store_id'];

          }
          $nall_store_id=@array_unique($all_store_id);
          $nnall_store_id=@array_filter($nall_store_id);
          $store_id_string = @implode(',',  $nnall_store_id);
          $goods_model = Model('goods');
          $condition = array();
          $condition['isdmgoods']=1;
          $condition['goods_state']=1;
		  $condition['goods_verify']=1;
		  $condition['goods_state']=1;
          $condition['gc_id_1']=$store_class_id;
          $condition['goods_commend']=0;    //没推荐的
          $condition['store_id']=array('in',$store_id_string);
          $allgoods = $goods_model->where($condition)->order('goods_id desc')->select();
          $singsotre = array();
          foreach ($allgoods as $k => $v) {
           $singsotre[$k] = $v['goods_id'];
          }
          $singlegoodsid= @implode(',',  $singsotre);
          if(!empty($singlegoodsid)){
            $allgoodsg  = $goods_model->where(array('goods_id'=>array('in',$singlegoodsid)))->order('goods_id desc')->group('store_id')->select();
          }
         
          foreach ($allgoodsg as $key => $value) {
            $allgoodsg[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
            $address_info = $model_st->getStoreInfoByID($value['store_id']);
            $allgoodsg[$key]['store_address']=$address_info['store_address'];
            $ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
            if($ll&&!empty($lng)&&!empty($lat)){
              $allgoodsg[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
            }else{
              $allgoodsg[$key]['distance']=0;
            }

          }
          if($obj==3){
          	$sort = array(
        'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
        'field'     => 'pls',       //排序字段
        );
          	$arrSort = array();
          	foreach($allgoodsg AS $uniqid => $row){
          		foreach($row AS $key=>$value){
          			$arrSort[$key][$uniqid] = $value;
          		}
          	}
          	if($sort['direction']){
          		@array_multisort($arrSort[$sort['field']], constant($sort['direction']), $allgoodsg);
          	}
          	$allgoogs=$allgoodsg ;



          }
          elseif($obj==2){

          	$sort = array(
        'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
        'field'     => 'distance',       //排序字段
        );
          	$arrSort = array();
          	foreach($allgoodsg AS $uniqid => $row){
          		foreach($row AS $key=>$value){
          			$arrSort[$key][$uniqid] = $value;
          		}
          	}
          	if($sort['direction']){
          		array_multisort($arrSort[$sort['field']], constant($sort['direction']), $allgoodsg);
          	}
          	$allgoogs=$allgoodsg;


          }else{
          	$allgoogs=$allgoodsg ;
          }
          output_data($allgoogs);

      }

 






  }

  ?>