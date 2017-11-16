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
class provinceControl extends mobileHomeControl{

    /**
     * 商品列表
     */
    public function indexOp() {
        $keyword = $_GET['keyword'];
        if(empty($keyword))
        {

            $data = Model()->table('store')->where(array('is_province'=>1))->field('member_id,store_id,store_company_name,sc_id')->order('is_sort')->limit(10)->select();
            //member_provinceid
                foreach ($data as $key => $value) {
                    $where1['member_id'] = $value['member_id'];
                    $member_provinceid  = Model()->table('member')->where($where1)->field('member_provinceid')->find();
                    //店铺分类
                    $sc_name = Model()->table('store_class')->where(array('sc_id'=>$value['sc_id']))->field('sc_name')->find();
                    $data[$key]['sc_name'] =$sc_name['sc_name'];

                    $data[$key]['name'] = $this->img($member_provinceid['member_provinceid']);
                    $data[$key]['colour'] = $this->colour($member_provinceid['member_provinceid']);
                    $data[$key]['servicecredit'] = $this->servicecredit($value['servicecredit']);
                    //商品信息
                    $where['store_id'] = $value['store_id'];
                    $where['goods_state'] = 1;
                    $where['goods_verify'] = 1;
                    $goodsinfo_count = Model()->table('goods')->where($where)->field('goods_storage')->select();
                    $data[$key]['goodsinfo_count'] = count($goodsinfo_count);

                    $goodsinfo = Model()->table('goods')->where($where)->limit(2)->field('goods_id,goods_name,goods_price,goods_image,goods_storage')->order('goods_addtime desc')->select();
                    foreach ($goodsinfo as $k => $v) {
                        //$goodsinfo[$k]['goods_name'] = mb_substr($v['goods_name'],0,6,'utf-8');
                        $goodsinfo[$k]['goods_name'] = $v['goods_name'];
                        $goodsinfo[$k]['goods_image'] =UPLOAD_SITE_URL.'/shop/store/goods/'.$value['store_id'].'/'.$v['goods_image'];
                    }
                    $data[$key]['goods_storage'] =$goods_storage;
                    $data[$key]['goodsinfo'] =$goodsinfo;
                }
        }
        else{
            $condition['store_company_name'] = array('like', '%' . $keyword . '%');
            $condition['is_province'] = 1;
            $data = Model()->table('store')->where($condition)->field('member_id,store_id,store_company_name,sc_id')->order('is_sort')->limit(10)->select();
            foreach ($data as $key => $value) {
                    $where1['member_id'] = $value['member_id'];
                    $member_provinceid  = Model()->table('member')->where($where1)->field('member_provinceid')->find();
                    //店铺分类
                    $sc_name = Model()->table('store_class')->where(array('sc_id'=>$value['sc_id']))->field('sc_name')->find();
                    $data[$key]['sc_name'] =$sc_name['sc_name'];
                    
                    $data[$key]['name'] = $this->img($member_provinceid['member_provinceid']);
                    $data[$key]['colour'] = $this->colour($member_provinceid['member_provinceid']);
                    $data[$key]['servicecredit'] = $this->servicecredit($value['servicecredit']);
                    //商品信息
                    $where['store_id'] = $value['store_id'];
                    $where['goods_state'] = 1;
                    $where['goods_verify'] = 1;
                    $goodsinfo_count = Model()->table('goods')->where($where)->field('goods_storage')->select();
                    $data[$key]['goodsinfo_count'] = count($goodsinfo_count);
                    $goodsinfo = Model()->table('goods')->where($where)->limit(2)->field('goods_id,goods_name,goods_price,goods_image,goods_storage')->order('goods_addtime desc')->select();
                    foreach ($goodsinfo as $k => $v) {
                        //$goodsinfo[$k]['goods_name'] = mb_substr($v['goods_name'],0,6,'utf-8');
                        $goodsinfo[$k]['goods_name'] = $v['goods_name'];
                        $goodsinfo[$k]['goods_image'] =UPLOAD_SITE_URL.'/shop/store/goods/'.$value['store_id'].'/'.$v['goods_image'];
                    }
                    $data[$key]['goods_storage'] =$goods_storage;
                    $data[$key]['goodsinfo'] =$goodsinfo;
                }
        }

        output_data($data);
    }

    //店铺评分
    private  function servicecredit($servicecredit){
            switch($colour)
        {
            case 1:
                return 'xin_1';
                break;
            case 2:
                return 'xin_2';
                break;
            case 3:
                return 'xin_3';
                break;
            case 4:
                return 'xin_4';
                break;
            case 5:
                return 'xin_5';
                break;
            default:
                return 'xin_5';
                break;
        }
    }

    private function colour($colour){
        switch($colour)
        {
            case 19:
                return 'shg_pc';
                break;
            case 23:
                return 'shg_pc shg_pc2';
                break;
            case 17:
                return 'shg_pc shg_pc3';
                break;
            case 13:
                return 'shg_pc shg_pc4';
                break;
            case 5:
                return 'shg_pc shg_pc5';
                break;
            case 16:
                return 'shg_pc';
                break;
            case 27:
                return 'shg_pc shg_pc2';
                break;
            case 7:
                return 'shg_pc shg_pc3';
                break;
            case 6:
                return 'shg_pc shg_pc4';
                break;
            case 10:
                return 'shg_pc shg_pc5';
                break;
            default:
                return 'shg_pc';
                break;
        }
    }

    //颜色
    private function img($name){
        switch($name)
        {
            case 1:
                return 'beijing';
                break;
            case 2:
                return 'tianjing';
                break;
            case 3:
                return 'hebei';
                break;
            case 4:
                return 'shanxi';
                break;
            case 5:
                return 'sheng_5';
                break;
            case 6:
                return 'sheng_9';
                break;
            case 7:
                return 'sheng_8';
                break;
            case 8:
                return 'hlj';
                break;
            case 9:
                return 'sh';
                break;
            case 10:
                return 'js';
                break;
            case 11:
                return 'zj';
                break;
            case 12:
                return 'ah';
                break;
            case 13:
                return 'sheng_4';
                break;
            case 14:
                return 'jx';
                break;
            case 15:
                return 'sd';
                break;
            case 16:
                return 'sheng_6';
                break;
            case 17:
                return 'sheng_3';
                break;
            case 18:
                return 'hn';
                break;
            case 19:
                return 'sheng_1';
                break;
            case 20:
                return 'gx';
                break;
            case 21:
                return 'hainan';
                break;
            case 22:
                return 'chongqing';
                break;
            case 23:
                return 'sheng_2';
                break;
            case 24:
                return 'guizhou';
                break;
            case 25:
                return 'yunnan';
                break;
            case 26:
                return 'xizang';
                break;
            case 27:
                return 'sheng_7';
                break;
            case 28:
                return 'ganneng';
                break;
            case 29:
                return 'qinghai';
                break;
            case 30:
                return 'nengxia';
                break;
            case 31:
                return 'xinjiang';
                break;
            case 32:
                return 'taiwan';
                break;
            case 33:
                return 'xianggang';
                break;
            case 34:
                return 'aomen';
                break;
            default:
                return 'beijing1';
                break;
        }
    }

}
