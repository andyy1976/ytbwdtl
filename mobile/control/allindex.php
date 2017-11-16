<?php
/**
 * 店铺
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */


defined('In33hao') or exit('Access Invalid!');

class allindexControl extends mobileHomeControl
{
    public function __construct()
    {
        parent::__construct();
    }
 
    public function getsingleClassProductOp(){
    	$city=isset($_POST['city'])?$_POST['city']:'';	
        $key=isset($_POST['key'])?$_POST['key']:'';
        $lng = isset($_POST['lng'])?$_POST['lng']:'';
        $lat = isset($_POST['lat'])?$_POST['lat']:'';
        $dobj= isset($_POST['dobj'])?$_POST['dobj']:'';
        $sobj = isset($_POST['sobj'])?$_POST['sobj']:'';
        $store_classsj_id=isset($_POST['store_classsj_id'])?$_POST['store_classsj_id']:'';
        $store_id = $this->getOnlineStore($city);
        $allgoods = $this->getOnlineProduct($city,$lng,$lat,$store_classsj_id,$store_id,$dobj);
        output_data($allgoods);
    	
    }


   private function getOnlineStore($city){   //获取所有有效的店铺ID
   	$allstore_id = Model()->table('store_map')->field('store_id')->where(array('baidu_city'=>$city))->select();
     //获取线下店铺的id(但不包含店铺是否有效)
     $newallstoreid=array();
   	if($allstore_id){
   		foreach ($allstore_id as $key => $value) {
   			$newallstoreid[$key] = $value['store_id'];
   		}
   		$store_id_string=@implode(',', $newallstoreid);
   	 $status_store = Model()->table('store')->field('store_id')->where(array('store_id'=>array('in',$store_id_string),'store_state'=>1))->select();     //过滤店铺状态
   	 $goods_store_id=array();
   	 foreach ($status_store as $k => $v) {
   	 	 $goods_store_id[$k]=$v['store_id'];
   	 }
    $allgoods_storeid = @implode(',', $goods_store_id);
    return $allgoods_storeid;
   }else{
   	return 0;
   }
}
   private function getOnlineProduct($city,$lng,$lat,$gc_id,$store_id,$dobj){    //根据ID sotreId获取产品
   	    $model_st = Model('store');
   	    $model_store_map = Model('store_map');
        $condition = array();
        $condition['isdmgoods']=1;
        $condition['goods_state']=1;
		$condition['goods_verify']=1;
		$condition['goods_state']=1;
	    $condition['gc_id'] = $gc_id;
        $condition['store_id']=array('in',$store_id);
        $allgoods = Model()->table('goods')->where($condition)->field('store_id,goods_image,store_name,goods_name,goods_points,goods_collect,goods_id,evaluation_count,goods_click')->order('goods_id desc')->group('store_id')->select();
        foreach ($allgoods as $key => $value) {
          	$allgoods[$key]['goods_imageit'] = cthumb($value['goods_image'], 240);
          	$address_info = $model_st->getStoreInfoByID($value['store_id']);
          	$allgoods[$key]['store_address']=$address_info['store_address'];
          	$ll = $model_store_map->getStStoreInfoByID(array('store_id'=>$value['store_id']));
          	if($ll&&!empty($lng)&&!empty($lat)){
          		$allgoods[$key]['distance']=$this->getDistance($lat, $lng, $ll['baidu_lat'], $ll['baidu_lng']); 
          	}else{
          		$allgoods[$key]['distance']=0;
          	}
          }
         if(!empty($sobj)){    //根据距离排序
            switch ($sobj) {
         	case 1:
         		return $allgoods;
         		break;
         	
         	case 2:
         		$sort = array(  
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
                'field'     => 'distance',       //排序字段  
                );  
                $arrSort = array(); 
                foreach($allgoods AS $uniqid => $row){  
                foreach($row AS $key=>$value){  
                   $arrSort[$key][$uniqid] = $value;  
                 }}  
            if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $allgoods);  
            }   
              return $allgoods;
         		break;
         	case 3:
         		$sort = array(  
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
                'field'     => 'evaluation_count',       //排序字段  
                );  
                $arrSort = array(); 
                foreach($allgoods AS $uniqid => $row){  
                foreach($row AS $key=>$value){  
                   $arrSort[$key][$uniqid] = $value;  
                 }}  
            if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $allgoods);  
            }   
              return $allgoods;
         		break;
         	case 4:
         		$sort = array(  
                'direction' => 'SORT_DESC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序  
                'field'     => 'goods_click',       //排序字段  
                );  
                $arrSort = array(); 
                foreach($allgoods AS $uniqid => $row){  
                foreach($row AS $key=>$value){  
                   $arrSort[$key][$uniqid] = $value;  
                 }}  
            if($sort['direction']){  
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $allgoods);  
            }   
              return $allgoods;
         		break;

         }

         }
         if(!empty($dobj)){   //根据距离排序
       	function filterDistance($arr)  
             {  
                return($arr['distance'] < $dobj);  
              } 
       switch($dobj){
            case 1:
            $array = array_filter($allgoods, "filterDistance"); 
            return $array;  
            break;
            case 3:
            $array = array_filter($allgoods, "filterDistance"); 
            return $array;  
            break;
            case 5:
            $array = array_filter($allgoods, "filterDistance"); 
            return $array;  
            break;
            case 10:
            $array = array_filter($allgoods, "filterDistance"); 
            return $array;  
            break;
            case 0:
            return $allgoods;
            break;
        }
      }else{
      	 return $allgoods;
      }
       

   
}
    /** 
    * @desc 根据两点间的经纬度计算距离 
    * @param float $lat 纬度值 
    * @param float $lng 经度值 
    */
private function getDistance($lat1, $lng1, $lat2, $lng2) 
    { 
        $earthRadius = 6367.729; //approximate radius of earth in meters 
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
}
?>