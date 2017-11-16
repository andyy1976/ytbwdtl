<!-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> -->
<?php 

defined('In33hao') or exit('Access Invalid!');

class golbabuyControl extends BaseHomeControl {
	  // public function indexOp() { 
	  // echo "<script> alert('系统管理员提示：系统正在全力开发中...')</script>";
	  // echo "<script> window.location.href='index.php'</script>";
	  // }
	      const PAGESIZE = 12;
      public function indexOp() { 
        $model_goods = Model('goods');
        $condition = array();

        $condition['store_id']   = 224;
        if ($_GET['gc_id']) {
            $condition['gc_id_1'] = intval($_GET['gc_id']);
        }
        $golbabug = $model_goods->getGoodsList($condition,'*','','','',self::PAGESIZE);
        $total_page = pagecmd('gettotalpage');
        if (intval($_GET['curpage'] > $total_page)) {
            exit();
        }
        $xs_goods_list = array();
        foreach ($golbabug as $k => $goods_info) {
            $xs_goods_list[$goods_info['goods_id']] = $goods_info;
            $xs_goods_list[$goods_info['goods_id']]['image_url_240'] = cthumb($goods_info['goods_image'], 240, $goods_info['store_id']);
            $xs_goods_list[$goods_info['goods_id']]['down_price'] = $goods_info['goods_price'];
        }
        
        unset($condition);
        $condition = array('goods_id' => array('in',array_keys($xs_goods_list)));
        $goods_list = $model_goods->getGoodsOnlineList($condition, 'goods_id,gc_id_1,evaluation_good_star,store_id,store_name', 0, '', self::PAGESIZE, null, false);
        foreach ($goods_list as $k => $goods_info) {
            $xs_goods_list[$goods_info['goods_id']]['evaluation_good_star'] = $goods_info['evaluation_good_star'];
            $xs_goods_list[$goods_info['goods_id']]['store_name'] = $goods_info['store_name'];
            // if ($xs_goods_list[$goods_info['goods_id']]['gc_id_1'] != $goods_info['gc_id_1']) {
            //     //兼容以前版本，如果限时商品表没有保存一级分类ID，则马上保存
            //     $model_xianshi_goods->editXianshiGoods(array('gc_id_1'=>$goods_info['gc_id_1']),array('xianshi_goods_id'=>$xs_goods_list[$goods_info['goods_id']]['xianshi_goods_id']));
            // }
        }
    
        Tpl::output('goods_list', $xs_goods_list);
        if (!empty($_GET['curpage'])) {
            Tpl::showpage('golbabuy.item','null_layout');
        } else {

            //导航
            $nav_link = array(
                    0=>array(
                            'title'=>Language::get('homepage'),
                            'link'=>SHOP_SITE_URL,
                    ),
                    1=>array(
                            'title'=>'全球购'
                    )
            );
      
            Tpl::output('nav_link_list',$nav_link);

            //查询商品分类
            $goods_class = Model('goods_class')->getGoodsClassListByParentId(0);
            foreach($goods_class as $goods_info){
                if($goods_info['gc_id'] == "10417"){
                     $goods_info['gc_id'] = ""; 
                }else{
                    $goods_l[] = $goods_info;
                }
            }
            Tpl::output('goods_class',$goods_l);

            Tpl::output('total_page',pagecmd('gettotalpage'));
            Tpl::showpage('golbabuy');
            }
        }
	}
?>