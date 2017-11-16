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
class dimian_storeControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }
	public function shangjia_listOp() {
		$lng = $_POST['lng'];
		$lat = $_POST['lat'];
		$city = $_POST['city'];
		$district=$_POST['district'];
		$addr = $_POST['addr'];
		$province = $_POST['province'];
		$bs = $_POST['bs'];
	    $condition=array();    //查询条件
	    if(!empty($province)){    //省

	    	$condition['store_map.baidu_province']=$province;
	    }
	    if(!empty($city)){      //市
            $condition['store_map.baidu_city']=$city;
        }
        if(!empty($district)){    //区
          // $condition['store_map.baidu_distict']=$district;
       }
       if(!empty($bs)){
           $condition['store_map.name_info'] = array('like','%'.$bs.'%');

       }
       // echo '2';
      $model = Model();
      $field = 'store_map.store_id,store_map.name_info, store_map.baidu_province,store_map.baidu_city,store_map.baidu_district,store_map.baidu_street,store_map.shop_bz,store_map.baidu_lng,store_map.baidu_lat,store.store_avatar,store.store_label,store.store_description,store_map.map_id,store_map.custom_money,store_map.custom_point,store_map.points';

      $on = 'store_map.store_id = store.store_id ';
      $model->table('store_map,store')->field($field);
   
      $addresslist=$model->join('left')->on($on)->where($condition)->limit(50)->select(); 
      foreach ($addresslist as $key => $value) {
      	$addresslist[$key]['distance'] = $this->getDistance($lat,$lng,$value['baidu_lat'],$value['baidu_lng'])/1000;
		$addresslist[$key]['shop_bzd'] = mb_substr( $addresslist[$key]['shop_bz'], 0, 15,'utf-8');
		$addresslist[$key]['store_descriptiond']=mb_substr( $addresslist[$key]['store_description'], 0, 20,'utf-8');
      }
      //$addresslist=$model->join('left')->on($on)->select();
      output_data(array('addresslist'=>$addresslist));
}

	/** 
* @desc 根据两点间的经纬度计算距离 
* @param float $lat 纬度值 
* @param float $lng 经度值 
*/
function getDistance($lat1, $lng1, $lat2, $lng2) 
{ 
$earthRadius = 6367000; //approximate radius of earth in meters 
 
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
/**
选取单条信息
*/
public function dimiandetailsOp(){
      
     $map_store = Model('store_map');
     $store = Model('store');
     $map_id = isset($_POST['map_id'])?$_POST['map_id']:'';
     $lat = isset($_POST['lat'])?$_POST['lat']:'';
     $lng = isset($_POST['lng'])?$_POST['lng']:'';
     if(!empty($map_id)){
      $arr = array();
      $arr['map_id']=$map_id;
      $ishasstore = $map_store->getStStoreInfoByID($arr);
     

      $ishasstore['distance'] = $this->getDistance($lat,$lng,$ishasstore['baidu_lat'],$ishasstore['baidu_lng'])/1000;
      output_data(array('ishasstore'=>$ishasstore ));
     }else{
      echo 7;
      exit();
     }
    
}
}



?>
    