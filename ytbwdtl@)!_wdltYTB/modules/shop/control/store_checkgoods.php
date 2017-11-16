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

class store_checkgoodsControl extends SystemControl{
    const EXPORT_SIZE = 600;

    private $_links = array(
        array('url'=>'act=store_checkgoods&op=checksuccess','text'=>'审核成功商品统计'),
        //20171025潘丙福添加开始--审核失败商品--待完成
        // array('url'=>'act=store_checkgoods&op=checkfail','text'=>'审核失败'),
        // 20171025潘丙福添加结束
        array('url'=>'act=store_checkgoods&op=checkgoodsdetail','text'=>'店铺审核商品详情查询'),
    );

    public function __construct(){
        parent::__construct();
        Language::read('store,store_grade');
    }

    public function indexOp() {
        $this->checksuccessOp();
    }

    /**
     * 店铺
     */
    public function checksuccessOp(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'checksuccess'));
		Tpl::setDirquna('shop');
        Tpl::showpage('store.checkgoods');
    }
    /**
     * 具体店铺审核商品信息
     */
    public function checkgoodsdetailOp(){
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->_links,'checkgoodsdetail'));
        Tpl::setDirquna('shop');
        Tpl::showpage('store.checkgoodsdetail');
    }
    /**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_goods = Model('goods');

        $condition = array();
        
        if ($_GET['is_own_shop'] != '') {
            $condition['is_own_shop'] = $_GET['is_own_shop'];
        }

        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        //20171019潘丙福添加开始-增加时间搜索
        $if_start_time    = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_start_date']);
        $if_end_time      = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_end_date']);
        $start_unixtime   = $if_start_time ? strtotime($_REQUEST['query_start_date']) : null;
        $end_unixtime     = $if_end_time ? strtotime($_REQUEST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['goods_addtime'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //20171019潘丙福添加结束

        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }

        $condition['goods_verify'] = 1; 

        $order = 'totalnum desc';

        $page = $_POST['rp'];

        $tmp = $model_goods->table('goods_common')->field('store_id,store_name,is_own_shop,count(*) as totalnum')->where($condition)->group('store_id')->select();
        $tmpCount = count($tmp);
        $goods_list = $model_goods->table('goods_common')->field('store_id,store_name,is_own_shop,count(*) as totalnum')->where($condition)->group('store_id')->order($order)->page($page,$tmpCount)->select();
        $data              = array();
        $data['now_page']  = $model_goods->shownowpage();
        $data['total_num'] = $tmpCount;
        foreach ($goods_list as $value) {
            $param['store_id']    = $value['store_id'];
            $store_name           = "<a class='" . $store_state . "' href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>";
            $store_name           .= $value['store_name'] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['store_name']  = $store_name;
            $param['is_own_shop'] = $value['is_own_shop'] == 1 ?'自营店铺':'入驻商家';
            $param['totalnum']    = $value['totalnum'];
            $data['list'][$value['store_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
    

    /**
     * csv导出
     */
    public function export_csvOp() {
        $model_goods = Model('goods');
        $condition = array();

        if ($_GET['is_own_shop'] != '') {
            $condition['is_own_shop'] = $_GET['is_own_shop'];
        }
        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        }
        //20171019潘丙福添加开始-增加时间搜索
        $if_start_time    = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_start_date']);
        $if_end_time      = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_end_date']);
        $start_unixtime   = $if_start_time ? strtotime($_REQUEST['query_start_date']) : null;
        $end_unixtime     = $if_end_time ? strtotime($_REQUEST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['goods_addtime'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //20171019潘丙福添加结束

        if ($_REQUEST['query'] != '') {
            $condition[$_REQUEST['qtype']] = array('like', '%' . $_REQUEST['query'] . '%');
        }

        $condition['goods_verify'] = 1; 

        $order = 'totalnum desc';

        // if (!is_numeric($_GET['curpage'])){
        //     $goods_list = $model_goods->table('goods_common')->field('store_id,store_name,is_own_shop,count(*) as totalnum')->where($condition)->group('store_id')->select();
        //     $count = count($goods_list);
        //     var_dump($count);exit;
        //     if ($count > self::EXPORT_SIZE ){   //显示下载链接
        //         $array = array();
        //         $page = ceil($count/self::EXPORT_SIZE);
        //         for ($i=1;$i<=$page;$i++){
        //             $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
        //             $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
        //             $array[$i] = $limit1.' ~ '.$limit2 ;
        //         }
        //         Tpl::output('list',$array);
        //         // Tpl::output('murl','index.php?act=goods&op=index');
        //         Tpl::setDirquna('shop');
        //         Tpl::showpage('export.excel');
        //         exit();
        //     }
        // } else {
        //     $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
        //     $limit2 = self::EXPORT_SIZE;
        //     $limit = $limit1 .','. $limit2;
        // }
        $goods_list = $model_goods->table('goods_common')->field('store_id,store_name,is_own_shop,count(*) as totalnum')->where($condition)->group('store_id')->order($order)->select();
        $this->createCsv($goods_list);
    }
    /**
     * 生成csv文件
     */
    private function createCsv($store_list) {

        $data = array();
        foreach ($store_list as $value) {
            $param                    = array();
            $param['store_id']        = $value['store_id'];
            $param['store_name']      = $value['store_name'];
            $param['is_own_shop']     = $value['is_own_shop'] == '1'?'自营店铺':'入住店铺';
            $param['totalnum']        = $value['totalnum'];
            $data[$value['store_id']] = $param;
        }

        $header = array(
            'store_id'    => '店铺ID',
            'store_name'  => '店铺名称',
            'is_own_shop' => '店铺类型',
            'totalnum'    => '审核商品数量'
        );
        array_unshift($data, $header);
		$csv = new Csv();
	    $export_data = $csv->charset($data,CHARSET,'gbk');
	    $csv->filename = $csv->charset('店铺审核商品统计列表',CHARSET).'-'.date('Y-m-d');
	    $csv->export($data);	
    }
    
    public function get_goodsdetail_xmlOp()
    {
        $model_goods = Model('goods');

        $condition = array();

        if ($_GET['store_name'] != '') {
            $condition['store_name'] = array('like', '%' . $_GET['store_name'] . '%');
        } else {
            $condition['store_name'] = array('like', '%中华粮仓%');
        }
        //20171019潘丙福添加开始-增加时间搜索
        $if_start_time    = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_start_date']);
        $if_end_time      = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_end_date']);
        $start_unixtime   = $if_start_time ? strtotime($_REQUEST['query_start_date']) : null;
        $end_unixtime     = $if_end_time ? strtotime($_REQUEST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['goods_addtime'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //20171019潘丙福添加结束

        $condition['goods_verify'] = 1; 

        $order = 'store_id desc,goods_commonid desc';

        $page = $_POST['rp'];

        $goods_list = $model_goods->table('goods_common')->field('store_id,store_name,goods_commonid,goods_name,goods_addtime')->where($condition)->order($order)->page($page)->select();
        $data              = array();
        $data['now_page']  = $model_goods->shownowpage();
        $data['total_num'] = $model_goods->gettotalnum();
        foreach ($goods_list as $value) {
            $param['store_id']                = $value['store_id'];
            $store_name                       = "<a class='" . $store_state . "' href='". urlShop('show_store', 'index', array('store_id' => $value['store_id'])) ."' target='blank'>";
            $store_name                       .= $value['store_name'] . "<i class='fa fa-external-link ' title='新窗口打开'></i></a>";
            $param['store_name']              = $store_name;
            $param['goods_commonid']          = $value['goods_commonid'];
            $param['goods_name']              = $value['goods_name'];
            $param['goods_addtime']           = date('Y-m-d H:i:s',$value['goods_addtime']);
            $data['list'][$value['goods_commonid']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
