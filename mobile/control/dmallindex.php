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

class dmallindexControl extends mobileHomeControl
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getByIdOp(){
    	$obj = isset($_POST['obj'])?$_POST['obj']:'';
    	$tsd = $this->gethostbynamebyid($obj);
    	output_data($tsd);
    }
    public function dmgoodsclassOp(){   //所有分类的ID及名称
      $tallsc = Model()->table('goods_class');
      $class1 = isset($_POST['class1'])?$_POST['class1']:'';
      $class2 = isset($_POST['class2'])?$_POST['class2']:''; 
      $class3 = isset($_POST['class3'])?$_POST['class3']:''; 
      $class4 = isset($_POST['class4'])?$_POST['class4']:''; 
      $class5 = isset($_POST['class5'])?$_POST['class5']:''; 
      $class6 = isset($_POST['class6'])?$_POST['class6']:''; 
      $class7 = isset($_POST['class7'])?$_POST['class7']:'';
      $class8 = isset($_POST['class8'])?$_POST['class8']:'';
      $store_class_id=isset($_POST['store_class_id'])?$_POST['store_class_id']:'';  
      switch ($store_class_id) {
      	case '10505':       //美食
        $classname = array();
        $classname[0]['classid'] = $this->getclassbyname($class1)['gc_id'];
        $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
        $classname[1]['classid'] = $this->getclassbyname($class2)['gc_id'];
        $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
        $classname[2]['classid'] = $this->getclassbyname($class3)['gc_id'];
        $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
        $classname[3]['classid'] = $this->getclassbyname($class4)['gc_id'];
        $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
        $classname[4]['classid'] = $this->getclassbyname($class5)['gc_id'];
        $classname[4]['gc_parent_id']=$this->getclassbyname($class5)['gc_parent_id'];
        $classname[5]['classid'] = $this->getclassbyname($class6)['gc_id'];
        $classname[5]['gc_parent_id']=$this->getclassbyname($class6)['gc_parent_id'];
        $classname[6]['classid'] = $this->getclassbyname($class7)['gc_id'];
        $classname[6]['gc_parent_id']=$this->getclassbyname($class7)['gc_parent_id'];
        $classname[7]['classid'] = $this->getclassbyname($class8)['gc_id'];
        $classname[7]['gc_parent_id']=$this->getclassbyname($class8)['gc_parent_id'];
        output_data($classname);
      		break;
      	case '10494':      //酒店住宿
      	$classname = array();
        $classname[0]['classid'] = $this->getclassbyname($class1)['gc_id'];
        $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
        $classname[1]['classid'] = $this->getclassbyname($class2)['gc_id'];
        $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
        $classname[2]['classid'] = $this->getclassbyname($class3)['gc_id'];
        $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
        $classname[3]['classid'] = $this->getclassbyname($class4)['gc_id'];
        $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
        $classname[4]['classid'] = $this->getclassbyname($class5)['gc_id'];
        $classname[4]['gc_parent_id']=$this->getclassbyname($class5)['gc_parent_id'];
        $classname[5]['classid'] = $this->getclassbyname($class6)['gc_id'];
        $classname[5]['gc_parent_id']=$this->getclassbyname($class6)['gc_parent_id'];
        $classname[6]['classid'] = $this->getclassbyname($class7)['gc_id'];
        $classname[6]['gc_parent_id']=$this->getclassbyname($class7)['gc_parent_id'];
        $classname[7]['classid'] = $this->getclassbyname($class8)['gc_id'];
        $classname[7]['gc_parent_id']=$this->getclassbyname($class8)['gc_parent_id'];
        output_data($classname);
      		break;
      	case '10475':
      	$classname = array();
        $classname[0]['classid'] = $this->getclassbyname($class1)['gc_id'];
        $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
        $classname[1]['classid'] = $this->getclassbyname($class2)['gc_id'];
        $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
        $classname[2]['classid'] = $this->getclassbyname($class3)['gc_id'];
        $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
        $classname[3]['classid'] = $this->getclassbyname($class4)['gc_id'];
        $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
        $classname[4]['classid'] = $this->getclassbyname($class5)['gc_id'];
        $classname[4]['gc_parent_id']=$this->getclassbyname($class5)['gc_parent_id'];
        $classname[5]['classid'] = $this->getclassbyname($class6)['gc_id'];
        $classname[5]['gc_parent_id']=$this->getclassbyname($class6)['gc_parent_id'];
        $classname[6]['classid'] = $this->getclassbyname($class7)['gc_id'];
        $classname[6]['gc_parent_id']=$this->getclassbyname($class7)['gc_parent_id'];
        $classname[7]['classid'] = $this->getclassbyname($class8)['gc_id'];
        $classname[7]['gc_parent_id']=$this->getclassbyname($class8)['gc_parent_id'];
        output_data($classname);
      		break;
      	case '10429':
      	$classname = array();
        $classname[0]['classid'] = $this->getclassbyname($class1)['gc_id'];
        $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
        $classname[1]['classid'] = $this->getclassbyname($class2)['gc_id'];
        $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
        $classname[2]['classid'] = $this->getclassbyname($class3)['gc_id'];
        $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
        $classname[3]['classid'] = $this->getclassbyname($class4)['gc_id'];
        $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
        output_data($classname);
      		break;
        case '10432':
      	$classname = array();
        $classname[0]['classid'] = $this->getclassbyname($class1)['gc_id'];
        $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
        $classname[1]['classid'] = $this->getclassbyname($class2)['gc_id'];
        $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
        $classname[2]['classid'] = $this->getclassbyname($class3)['gc_id'];
        $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
        $classname[3]['classid'] = $this->getclassbyname($class4)['gc_id'];
        $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
        $classname[4]['classid'] = $this->getclassbyname($class5)['gc_id'];
        $classname[4]['gc_parent_id']=$this->getclassbyname($class5)['gc_parent_id'];
        $classname[5]['classid'] = $this->getclassbyname($class6)['gc_id'];
        $classname[5]['gc_parent_id']=$this->getclassbyname($class6)['gc_parent_id'];
        $classname[6]['classid'] = $this->getclassbyname($class7)['gc_id'];
        $classname[6]['gc_parent_id']=$this->getclassbyname($class7)['gc_parent_id'];
        $classname[7]['classid'] = $this->getclassbyname($class8)['gc_id'];
        $classname[7]['gc_parent_id']=$this->getclassbyname($class8)['gc_parent_id'];
        output_data($classname);
      		break;
      
      }

    }

   public function getAllProductOp(){     //获取首页默认的所有产品
   	   $key=isset($_POST['key'])?$_POST['key']:'';      //cookie登录值
   	   $city=isset($_POST['city'])?$_POST['city']:'';    //所在城市
   	   $lng=isset($_POST['lng'])?$_POST['lng']:'';       //经度
   	   $lat=isset($_POST['lat'])?$_POST['lat']:'';         //纬度
       $store_class_id=isset($_POST['store_class_id'])?$_POST['store_class_id']:'';    //分类ID
   	   $obj = isset($_POST['obj'])?$_POST['obj']:'';    //二级分类ID
   	   $dobj = isset($_POST['dobj'])?$_POST['dobj']:'';    //距离传值
   	   $sobj = isset($_POST['sobj'])?$_POST['sobj']:'';    //自定义传真
   	   $goodsall['goodname'] = $this->getProduct($store_class_id,$obj,$city,$lng,$lat,$dobj,$sobj);
   	    /**显示另一个数组开始*/
      $tallsc = Model()->table('goods_class');
      $class1 = isset($_POST['class1'])?$_POST['class1']:'';
      $class2 = isset($_POST['class2'])?$_POST['class2']:''; 
      $class3 = isset($_POST['class3'])?$_POST['class3']:''; 
      $class4 = isset($_POST['class4'])?$_POST['class4']:''; 
      $class5 = isset($_POST['class5'])?$_POST['class5']:''; 
      $class6 = isset($_POST['class6'])?$_POST['class6']:''; 
      $class7 = isset($_POST['class7'])?$_POST['class7']:'';
      $class8 = isset($_POST['class8'])?$_POST['class8']:'';
      $classname = array();
      $classname[0]['gc_id'] = $this->getclassbyname($class1)['gc_id'];
      $classname[0]['gc_parent_id']=$this->getclassbyname($class1)['gc_parent_id'];
      $classname[0]['gc_name']=$this->getclassbyname($class1)['gc_name'];
      $classname[1]['gc_id'] = $this->getclassbyname($class2)['gc_id'];
      $classname[1]['gc_parent_id']=$this->getclassbyname($class2)['gc_parent_id'];
      $classname[1]['gc_name']=$this->getclassbyname($class2)['gc_name'];
      $classname[2]['gc_id'] = $this->getclassbyname($class3)['gc_id'];
      $classname[2]['gc_parent_id']=$this->getclassbyname($class3)['gc_parent_id'];
      $classname[2]['gc_name']=$this->getclassbyname($class3)['gc_name'];
      $classname[3]['gc_id'] = $this->getclassbyname($class4)['gc_id'];
      $classname[3]['gc_parent_id']=$this->getclassbyname($class4)['gc_parent_id'];
      $classname[3]['gc_name']=$this->getclassbyname($class4)['gc_name'];
      if($store_class_id!=10429&&$store_class_id!=10494){
      $classname[4]['gc_id'] = $this->getclassbyname($class5)['gc_id'];
      $classname[4]['gc_parent_id']=$this->getclassbyname($class5)['gc_parent_id'];
      $classname[4]['gc_name']=$this->getclassbyname($class5)['gc_name'];
      $classname[5]['gc_id'] = $this->getclassbyname($class6)['gc_id'];
      $classname[5]['gc_parent_id']=$this->getclassbyname($class6)['gc_parent_id'];
      $classname[5]['gc_name']=$this->getclassbyname($class6)['gc_name'];
      $classname[6]['gc_id'] = $this->getclassbyname($class7)['gc_id'];
      $classname[6]['gc_parent_id']=$this->getclassbyname($class7)['gc_parent_id'];
      $classname[6]['gc_name']=$this->getclassbyname($class7)['gc_name'];
      $classname[7]['gc_id'] = $this->getclassbyname($class8)['gc_id'];
      $classname[7]['gc_parent_id']=$this->getclassbyname($class8)['gc_parent_id'];
      $classname[7]['gc_name']=$this->getclassbyname($class8)['gc_name'];
      }
      $goodsall['classname']=$classname;

      /**显示另一个数组结束*/

   	   output_data($goodsall);
       }
 private function getProduct($gc_id_1,$gc_id,$city,$lng,$lat,$dobj,$sobj){ //获取产品的方法
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
   	 if($status_store){
   	 	$allgoods_storeid = @implode(',', $goods_store_id);
   	 	$goods_model = Model('goods');
   	 	$model_store_map = Model('store_map');
   	 	$model_st = Model('store');
        $condition = array();
        $condition['isdmgoods']=1;
        $condition['goods_state']=1;
		$condition['goods_verify']=1;
		$condition['goods_state']=1;
		if(!empty($gc_id)){
		$condition['gc_id'] = $gc_id;
	    }else{
        $condition['gc_id_1']=$gc_id_1;
        }
        $condition['store_id']=array('in',$allgoods_storeid);
       $allgoods = $goods_model->where($condition)->field('store_id,goods_image,store_name,goods_name,goods_points,goods_collect,goods_id,evaluation_count,goods_click')->order('goods_id desc')->group('store_id')->select();
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
       if(!empty($sobj)){    //智能排序
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
   	}
           
   }
   private function getclassbyname($name){   //根据类名称获取分类ID
    	
    	 $tse = Model()->table('goods_class')->where(array('gc_name'=>$name,'store_flag'=>1))->field('gc_id,gc_parent_id,gc_name')->find();
    	 if($tse){
    	 return $tse;
    	}
    }

    private function gethostbynamebyid($id){ //根据类ID获取分类ID
    	 $tse = Model()->table('goods_class')->where(array('gc_id'=>$id,'store_flag'=>1))->field('gc_id,gc_parent_id,gc_name')->find();
    	 if($tse){
    	 return $tse;
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
   public function getAllClassNameOp(){
     $city=isset($_POST['city'])?$_POST['city']:'';          //城市地点
     $store_class_id=isset($_POST['store_class_id'])?$_POST['store_class_id']:'';    //大类ID
    if(!empty($store_class_id)){
      $allclassname = Model()->table('goods_class')->field('gc_id,gc_parent_id,gc_name')->where(array('gc_parent_id'=>$store_class_id))->order('gc_id desc')->select();   //获取大类下面所有的名称
      $all_store_id = $this->getOnlineStore($city);
      foreach ($allclassname as $k => $v) {
      	$allclassname[$k]['count']= Model()->table('goods')->where(array('gc_id'=>$v['gc_id'],'store_id'=>array('in',$all_store_id),'isdmgoods'=>1,'goods_state'=>1,'goods_verify'=>1))->count();
      }
     }
      $bsd =array();
       foreach ($allclassname as $k => $v) {
        $bsd[0]['gc_name']='全部';
        $bsd[0]['gc_id']=0;
        $bsd[0]['count'] +=$v['count'];
       } 
      $tbs = array_merge($bsd, $allclassname);
      output_data($tbs);
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
/*  allindex.details*/
   public function getSingleClassNameOp(){
    $city=isset($_POST['city'])?$_POST['city']:'';          //城市地点
     $store_class_id=isset($_POST['store_classsj_id'])?$_POST['store_classsj_id']:'';    //当前ID
    if(!empty($store_class_id)){
      $allclassname = Model()->table('goods_class')->field('gc_id,gc_parent_id,gc_name')->where(array('gc_id'=>$store_class_id))->order('gc_id desc')->find();   //获取大类下面所有的名称
      $all_store_id = $this->getOnlineStore($city);
      
     }
      
      output_data($allclassname);

    }


}