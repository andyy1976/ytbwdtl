<?php
/**
 * 商品
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');
class dmstoreControl extends mobileMemberControl{
    private $PI = 3.14159265358979324;
    private $x_pi = 52;
    
    public function __construct() {
        parent::__construct();
    }

   

    /**
     * 经纬度转换
     * @param unknown $bdLat
     * @param unknown $bdLon
     * @return multitype:number
     */
    public function bd_decrypt($bdLat, $bdLon) {
        $x = $bdLon - 0.0065; $y = $bdLat - 0.006;
        $z = sqrt($x * $x + $y * $y) - 0.00002 * sin($y * $this->x_pi);
        $theta = atan2($y, $x) - 0.000003 * cos($x * $this->x_pi);
        $gcjLon = $z * cos($theta);
        $gcjLat = $z * sin($theta);
        return array('lat' => $gcjLat, 'lon' => $gcjLon);
    }

    /**
     *  @desc 根据两点间的经纬度计算距离
     *  @param float $lat 纬度值
     *  @param float $lng 经度值
     */
    private function getDistance($lat1, $lng1, $lat2, $lng2) {
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
        $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);  $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
        $calculatedDistance = $earthRadius * $stepTwo;
    
        return round($calculatedDistance);
    }
	private function parseDistance($num = 0){
		$num = floatval($num);
		if ($num >= 1000) {
			$num = $num/1000;
			return str_replace('.0','',number_format($num,1,'.','')).'km';
		} else {
			return $num.'m';
		}
	}

    //搜索关键字
    public function search_keywordOp(){
        $keyword = $_GET['term'];
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
        //查询店铺
        $condition['store_name'] = array('like',$keyword.'%');
        $condition['baidu_city'] = $_GET['city'];
        $data = Model()->table('store_map')->where($condition)->find();
        $store = Model()->table('store')->where(array('store_id'=>$data['store_id'],'store_flag'=>1))->find();
      if(empty($store)){
        $gstore_id = Model()->table('goods')->where(array('goods_name'=>array('like','%'.$keyword.'%'),'isdmgoods'=>1))->find(); 
        if($gstore_id){
           $dmstore = Model()->table('store_map')->where(array('store_id'=>$gstore_id['store_id']))->find();
           if($dmstore){
            $store_id = $dmstore['store_id'];
           }
         }
        }else{
            $store_id = $data['store_id'];
        }
        if(!empty($store_id)){
        output_data($store_id);
      }else{
        output_data(0);
      }
    }

    //店铺详情页
    public function dmstore_detailsOp(){
        $voucher_model = Model('voucher_template');
        $store_id = $_GET['store_id'];
        $voucher_price = $voucher_model->where(array('voucher_t_state'=>1,'voucher_t_store_id'=>$store_id))->select();
        $city = $_GET['city'];
        $store = Model()->table('store')->where(array('store_id'=>$store_id))->field('store_id,store_name,store_address,store_phone,sc_id,area_info,member_id')->find();
        $member_info = Model()->table('member')->where(array('member_id'=>$store['member_id']))->field('member_mobile')->find();
        $sc_name= Model()->table('store_class')->where(array('sc_id'=>$store['sc_id']))->field('sc_name')->find();
        $store['sc_name'] = $sc_name['sc_name'];

        $goods = Model()->table('goods')->where(array('store_id'=>$store_id,'isdmgoods'=>1,'goods_verify'=>1,'goods_state'=>1))->field('goods_id,goods_name,goods_image,goods_price')->select();
        foreach ($goods as $key => $value) {
            $goods[$key]['goods_image'] = UPLOAD_SITE_URL.'/shop/store/goods/'.$store_id.'/'.$value['goods_image'];
        }

        //店铺收藏
        $collected = Model()->table('favorites')->where(array('fav_id'=>$store_id,'fav_type'=>'store','store_id'=>$store_id))->field('log_id')->select();
        $store_collected = count($collected);
        
        $goods_count = count($goods);
        $store['store_collected'] = $store_collected;
        $store['goods_count'] = $goods_count;
        $store['goods'] = $goods;
        $store['member_mobile']=$member_info['member_mobile'];
        $store['voucher']=$voucher_price;
        output_data($store);
    }

    //添加银行卡
    public function add_bankcardOp(){
        $data['acc_no']     = $_GET['bankcard'];
        $data['name']       = $_GET['bankname'];
        if(!empty($data['name'])){
          $c = Model('member_bank_card')->where(array('name'=>$data['name']))->count();
          if($c==1){
          $a='no';
          }else{
          $data['phone']      = $_GET['mobile'];
          $data['certif_id']  = $_GET['identity'];
          $data['member_id'] =$this->member_info['member_id'];
          $b = Model('member_bank_card')->insert($data);
        if($b){
          $a='ok';
          }
          }
        }
        output_data($a);
  }

    //显示银行卡列表
    public function dm_banklistOp(){
        $member_id=$this->member_info['member_id'];
        $data = Model()->table('member_bank_card')->where(array('member_id'=>$member_id))->order('id desc')->select();
        foreach ($data as $key => $value) {
            $data[$key]['bankname_li']=$this->getBankInfo($value['acc_no']);
            $data[$key]['acc_no1'] = substr($value['acc_no'],0,4)." **** **** **** ".  substr($value['acc_no'],-4,4);
        }
        output_data($data);
    }

    //商品详情提交页面
    public function dm_goods_detailsOp(){
       $voucher_model = Model('voucher_template');
       $voucher_price = $voucher_model->where(array('voucher_t_state'=>1,'voucher_t_store_id'=>$_GET['goods_id']))->select();
        $goodsdetails = Model()->table('goods')->where(array('goods_id'=>$_GET['goods_id']))->find();
        $goodsdetails['goods_image'] = cthumb($goodsdetails['goods_image'], 240);
         if($goodsdetails['usertc']==1){ //计算赠送云豆数量
          $goodsdetails['yundou'] =$goodsdetails['pointsb']*$goodsdetails['goods_price'];  //B套餐
         }else{
            $goodsdetails['yundou'] =0.5*$goodsdetails['goods_price'];  //A套餐
         }
         $member_info = Model()->table('member')-> where(array('member_id'=>$this->member_info['member_id']))->field('member_mobile')->find();
        $goodsdetails['member_mobile'] = $member_info['member_mobile'];
        $goodsdetails['member_available_rcb'] = ncPriceFormat($this->member_info['member_predeposit']);//可用余额
        $goodsdetails['djq']=$voucher_price;
        output_data($goodsdetails);
    }

    //生成订单页面
    public function orderOp(){
        $model_order_common = Model('order_common');
        $model_order_goods = Model('order_goods');
        $model_orders = Model('orders');
        $model_order_pay = Model('order_pay');
        $logic_buy = Logic('buy_1');
        $member_info = $this->member_info;
        $pay_sn=$logic_buy->makePaySn($this->member_info['member_id']);
        $order_sn=$logic_buy->makeOrderSn($this->member_info['member_id']);
		$data=array(     //产生订单
          'order_sn'=>$order_sn,
          'pay_sn'=>$pay_sn,
          'store_id'=>$_GET['store_id'],
          'store_name'=>$_GET['store_name'],
          'buyer_id'=>$member_info['member_id'],
          'buyer_name'=>$member_info['member_name'],
          'buyer_email'=>$member_info['member_email'],
          'buyer_phone'=>$member_info['member_mobile'],
          'add_time'=>time(),
          'payment_code'=>'online',
          'goods_amount'=>$_GET['goods_pay_price'],
          'order_amount'=>$_GET['goods_pay_price'],
          'order_pointsamount'=>$_GET['goods_pay_points'],
          'order_state'=>10,
          'is_dm'=>2
        );
       $model_se = $model_orders->insert($data);
       if($model_se){
              $ds = $model_orders->where(array('order_sn'=>$order_sn,'pay_sn'=>$pay_sn))->select();
               $datac=array(
               'order_id'=>$ds[0]['order_id'],
               'store_id'=>$ds[0]['store_id'],
                );
             $model_com = $model_order_common->insert($datac);
             $datag = array(
               'order_id'=>$ds[0]['order_id'],
               'goods_id'=>$_GET['goods_id'],
               'goods_name'=>$_GET['goods_name'],
               'goods_price'=>$_GET['goods_price'],
               'goods_points'=>$_GET['goods_pay_points'],
               'goods_num' =>$_GET['goods_num'],
               'goods_pay_price'=>$_GET['goods_pay_price'],
               'goods_pay_points'=>$_GET['goods_pay_points'],
               'store_id'=>$ds[0]['store_id'],
               'buyer_id'=>$member_info['member_id'],
               'goods_type'=>100,
               'gc_id'=>0
                );
             $model_good = $model_order_goods->insert($datag);
             $datap = array(
               'pay_sn'=>$pay_sn,
               'buyer_id'=>$member_info['member_id'],
               'api_pay_state'=>'0'
                );
            $model_pay = $model_order_pay->insert($datap);
            $statu=1;
        }else{
            $statu=0;
        }

        output_data($statu);
    }

    //收藏店铺
    public function store_collectedOp(){
        //先查询是否已经收藏
        $b = Model()->table('favorites')->where(array('member_id'=>$this->member_info['member_id'],'fav_id'=>$_GET['store_id'],'fav_type'=>'store','store_id'=>$_GET['store_id']))->find();
        if(empty($b))//没有收藏
        {
            $dataf = array(
            'member_id'=>$this->member_info['member_id'],
            'member_name'=>$this->member_info['member_name'],
            'fav_id'=>$_GET['store_id'],
            'fav_type'=>'store',
            'fav_time'=>time(),
            'store_id'=>$_GET['store_id'],
            'store_name'=>$_GET['store_name'],
            'sc_id'=>$_GET['sc_id']
            );
            $a = Model()->table('favorites')->insert($dataf);
            $data = 1;
        }
        else
        {
            
            $data = 0;
        }
        output_data($data);
       
    }

public function getBankInfo($card)
    {
        
        $bankList = include __DIR__ . '/return.banklist.php';

        $card_8 = substr($card, 0, 8);  
        if (isset($bankList[$card_8])) {  
            return $bankList[$card_8];  
        }

        $card_6 = substr($card, 0, 6);  
        if (isset($bankList[$card_6])) {  
            return $bankList[$card_6];  
        }

        $card_5 = substr($card, 0, 5);  
        if (isset($bankList[$card_5])) {  
            return $bankList[$card_5];   
        }

        $card_4 = substr($card, 0, 4);  
        if (isset($bankList[$card_4])) {  
            return $bankList[$card_4];  
        }

        return '银行卡';

    }

 private function level($member_level){
        switch ($member_level)
        {
            case 1:
                $level = '普通会员';
                break;
            case 2:
                $level = '端口';
                break;
            case 3:
                $level = '区级代理';
                break;
            case 4:
                $level = '市级代理';
                break;
            case 5:
                $level = '省级代理';
                break;
            default:
                $level = '见习会员';
        }
        return $level;
    }

    private function order_state($order_state){
        switch ($order_state)
        {
            case 50:
                $state = '已完成';
                break;
            case 40:
                $state = '待确认消费';
                break;
            case 30:
                $state = '已发货';
                break;
            case 20:
                $state = '已付款';
                break;
            case 0:
                $state = '已取消';
                break;
            default:
                $state = '未付款';
        }
        return $state;
    }

    //我的页面
    public  function dm_myOp(){
        $data['member_id'] =$this->member_info['member_id'];
        $data['member_name'] =$this->member_info['member_name'];
        $member_level =$this->member_info['member_level'];
        $data['member_level'] =$this->level($member_level);
        $data['member_pid'] =$this->member_info['member_pid'];
        $data['member_avatar'] =$this->member_info['member_avatar'];
        output_data($data);
    }

    //云店订单
    public function dm_orderOp(){
        $buyer_id = $this->member_info['member_id'];
       $st_m=Model('store');
        $orderid_info = Model()->table('orders')->where(array('buyer_id'=>$buyer_id,'is_dm'=>array('neq',0)))->field('order_id,order_state,add_time,order_amount,is_dm,store_id,lock_state')->order('order_id desc')->select();
       foreach ($orderid_info as $key => $value) {
         $store_online_info = $st_m->table('store')->where(array('store_id'=>$value['store_id']))->find();
         $store_info['store_avatar'] = $store_online_info['store_avatar']
            ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/'.$store_online_info['store_avatar']
            : UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
            $orderid_info[$key] = Model()->table('order_goods')->where(array('order_id'=>$value['order_id']))->find();
            $orderid_info[$key]['order_state'] = $this->order_state($value['order_state']);
            $orderid_info[$key]['add_time'] = date("Y-m-d",$value['add_time']);
            $orderid_info[$key]['order_amount'] = floor($value['order_amount']);
            $orderid_info[$key]['goods_image'] = cthumb($orderid_info[$key]['goods_image'], 240);
            $orderid_info[$key]['store_avatar'] =  $store_info['store_avatar'] ;
            $orderid_info[$key]['lock_state']=$value['lock_state'];
        }
      
        output_data($orderid_info);
    }
     //算出代金券
    public function dmstore_voucherOp(){
      $buyer_id = $this->member_info['member_id'];
      $yd_nameat = isset($_GET['yd_nameat'])?$_GET['yd_nameat']:'';
      $store_id = isset($_GET['store_id'])?$_GET['store_id']:'';
      $condition = array();
      $condition['voucher_owner_id']=$buyer_id;
      if ($yd_nameat){
        $condition['voucher_limit']=array('elt',$yd_nameat);
      }
      $condition['voucher_state']=1;
      $datenow=strtotime(date("Y-m-d H:i:s"));
      $condition['voucher_end_date']=array('gt',$datenow);
      $condition['voucher_store_id']=$store_id;
      $voucherit = Model()->table('voucher')->where($condition)->order('voucher_end_date asc')->field('voucher_price')->select();
       if (!$yd_nameat){
      if($voucherit){
        output_data( $voucherit);
        }else{
        output_data(0);
      }}else{
        if($voucherit){
        output_data(1);
        }else{
        output_data(0);
      }
      }

    }

   public function dm_orderupdateOp(){
    $order_id = isset($_GET['obj'])?$_GET['obj']:'';
   if(!empty($order_id)){
       if(dm_orderupdateOp($order_id)){
          output_data(1);
        }else{
          output_data(0);
        }
   }


}
}
