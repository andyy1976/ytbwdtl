<?php
/**
 * 统计管理（销量分析）
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');
class shop_verifyControl extends SystemControl{
    private $links = array(
        array('url'=>'act=shop_verify&op=income1','lang'=>'stat_sale_income'),
        // array('url'=>'act=stat_trade&op=predeposit','lang'=>'stat_predeposit'),
        array('url'=>'act=shop_verify&op=sale','lang'=>'stat_yundou')
    );

    private $search_arr;//处理后的参数

    public function __construct(){
        parent::__construct();
        Language::read('stat');
        import('function.statistics');
        import('function.datehelper');
        $model = Model('stat');
        //存储参数
        $this->search_arr = $_REQUEST;
        //处理搜索时间
        if (in_array($_REQUEST['op'],array('sale_trend','get_sale_xml','get_plat_sale'))){
            $this->search_arr = $model->dealwithSearchTime($this->search_arr);
            //获得系统年份
            $year_arr = getSystemYearArr();
            //获得系统月份
            $month_arr = getSystemMonthArr();
            //获得本月的周时间段
            $week_arr = getMonthWeekArr($this->search_arr['week']['current_year'], $this->search_arr['week']['current_month']);
            Tpl::output('year_arr', $year_arr);
            Tpl::output('month_arr', $month_arr);
            Tpl::output('week_arr', $week_arr);
        }
        Tpl::output('search_arr', $this->search_arr);
    }

    public function indexOp() {
        $this->saleOp();
    }

    /**
     * 输出店铺审核商品数量数据
     */
    public function get_plat_saleOp()
    {
        $model = Model('stat');
        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $if_curr_stime = $_GET['query_start_date'];
        $if_etime = $_GET['query_end_date'];
        $curr_stime   = $if_curr_stime ? strtotime($_REQUEST['query_start_date']) : null;
        $etime     = $if_etime ? strtotime($_REQUEST['query_end_date']): null;
        $where = array();
        $where['goods_common.goods_addtime'] = array('between',array($curr_stime,$etime));
        $where['goods_common.goods_verify']   = 1;
        switch (trim($_GET['shop_type'])) {
                    case '0':
                        $where['store.is_own_shop'] =0;
                        break;
                    case '1':
                        $where['store.is_own_shop'] =1;
                        break;
                    default:
                        break;
                }
        $field = 'goods_common.store_id,goods_common.goods_name,goods_common.store_name,goods_common.goods_addtime,store.is_own_shop';
        $on = 'goods_common.store_id=store.store_id';
        $list = Model()->table('goods_common,store')->field($field)->join('inner,left')->on($on)->where($where)->select();
        $count = count($list);

        echo '<div class="title"><h3>审核商品情况一览</h3></div>';
        echo '<dl class="row"><dd class="opt"><ul class="nc-row">';
        echo '<li title="审核商品数量：'. number_format(($count),0).'个"><h4>审核商品数量</h4><h2 id="count-number" class="timer" data-speed="1500" data-to="'.$count.'"></h2><h6>个</h6></li>';
        echo '</ul></dd><dl>';
        exit();
    }

    /**
     * 输出订单统计XML数据
     */
    public function get_sale_xmlOp()
    {
        $model_goods = Model('goods');
        $data = array();
        //默认统计当前数据
        if(!$this->search_arr['search_type']){
            $this->search_arr['search_type'] = 'day';
        }
        $if_curr_stime = $_GET['query_start_date'];
        $if_etime      = $_GET['query_end_date'];
        $curr_stime    = $if_curr_stime ? strtotime($_REQUEST['query_start_date']) : null;
        $etime         = $if_etime ? strtotime($_REQUEST['query_end_date']): null;

        $where = array();
        $where['goods_common.goods_addtime'] = array('between',array($curr_stime,$etime));
        $where['goods_common.goods_verify']   = 1;
        switch (trim($_GET['shop_type'])) {
            case '0':
                $where['store.is_own_shop'] =0;
                break;
            case '1':
                $where['store.is_own_shop'] =1;
                break;
            default:
                break;
        }
        $field = 'goods_common.store_id,goods_common.store_name,store.is_own_shop,count(*) as totalnum';
        $on = 'goods_common.store_id=store.store_id';
        $order="totalnum desc";
        $list = $model_goods->table('goods_common,store')->field($field)->join('inner,left')->on($on)->where($where)->group('goods_common.store_id')->order($order)->page($_REQUEST['rp'])->select();
        // var_dump($list);exit;
        $data['now_page'] = $model_goods->shownowpage();
        // $tmp = Model()->table('goods_common,store')->field($field)->join('inner,left')->on($on)->where($where)->group('goods_common.store_id')->select();
        // $data['total_num'] = $model_goods->gettotalnum();


        if (!empty($list) && is_array($list)){
            foreach ($list as $k => $v) {
                if($v['is_own_shop'] ==0){
                   $list[$k]['store_type'] = '入驻商家';
                   unset($list[$k]['is_own_shop']); 
                }else{
                    $list[$k]['store_type'] = '自营店铺';
                    unset($list[$k]['is_own_shop']);
               }
            }

        }
        $data['list'] = $list;
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 导出数据excel
     */
    public function saleOp()
    {
        $model = Model('stat');
        
        $if_curr_stime = $_GET['query_start_date'];
        $if_etime      = $_GET['query_end_date'];
        $curr_stime    = $if_curr_stime ? strtotime($_REQUEST['query_start_date']) : null;
        $etime         = $if_etime ? strtotime($_REQUEST['query_end_date']): null;
        
        $where = array();
        $where['goods_common.goods_addtime'] = array('between',array($curr_stime,$etime));
        $where['goods_common.goods_verify']   = 1;
        switch (trim($_GET['shop_type'])) {
                    case '0':
                        $where['store.is_own_shop'] =0;
                        break;
                    case '1':
                        $where['store.is_own_shop'] =1;
                        break;
                    default:
                        break;
                }
        $page = intval($_POST['rp']);
        if ($page < 1) {
            $page = 15;
        }
        if ($_GET['exporttype'] == 'excel'){
            $field = 'goods_common.store_id,goods_common.store_name,store.is_own_shop,count(*) as totalnum';
            $on = 'goods_common.store_id=store.store_id';
            $order="totalnum desc";
            $list = Model()->table('goods_common,store')->field($field)->join('inner,left')->on($on)->where($where)->group('goods_common.store_id')->order($order)->select();
            //统计数据标题
            $statlist = array();
            $statlist['headertitle'] = array('店铺ID','店铺名称','审核商品数量','店铺类型');

            if (!empty($list) && is_array($list)){
                foreach ($list as $k => $v) {
                    if($v['is_own_shop'] ==0){
                        $list[$k]['store_type'] = '入驻商家';
                        unset($list[$k]['is_own_shop']); 
                    }else{
                        $list[$k]['store_type'] = '自营店铺';
                        unset($list[$k]['is_own_shop']);
                    }
                }
                $statlist['data']=$list;
            }
                import('libraries.excel');
                $excel_obj = new Excel();
                $excel_data = array();
                //设置样式
                $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
                //header
                foreach ($statlist['headertitle'] as $v){
                    $excel_data[0][] = array('styleid'=>'s_title','data'=>$v);
                }
                //data
                foreach ((array)$statlist['data'] as $k => $v){
                    $excel_data[$k+1][] = array('data'=>$v['store_id']);
                    $excel_data[$k+1][] = array('data'=>$v['store_name']);
                    $excel_data[$k+1][] = array('data'=>$v['totalnum']);
                    $excel_data[$k+1][] = array('data'=>$v['store_type']);
                }
                $excel_data = $excel_obj->charset($excel_data,CHARSET);
                $excel_obj->addArray($excel_data);
                $excel_obj->addWorksheet($excel_obj->charset('商家审核商品统计',CHARSET));
                $excel_obj->generateXML($excel_obj->charset('商家审核商品统计',CHARSET).date('Y-m-d-H',time()));
                exit();
        } else {
                Tpl::output('top_link',$this->sublink($this->links, 'sale'));
                Tpl::setDirquna('shop');
                Tpl::showpage('shop_verify');
        }
    }

}
