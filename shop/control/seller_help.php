<?php
/**
 * 商户中心
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class seller_helpControl extends BaseHomeControl {

    /**
     * 构造方法
     *
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 商户中心首页
     *
     */
    public function seller_helpOp() {
        $condition  = array();
        $condition['ac_parent_id']  = 0;
        $condition['ac_code']  = 1;
        //左侧分类导航
        $data = Model()->table('article_class')->where($condition)->select();
        foreach ($data as $key => $value) {
            $where['ac_parent_id']=$value['ac_id'];
            $data[$key]['children']   = Model()->table('article_class')->where($where)->select();

        }
        //中间分类导航
        $condition1['ac_parent_id']  = 0;
        $condition1['ac_code']  = 2;
        $data1 = Model()->table('article_class')->where($condition1)->select();
        foreach ($data1 as $key => $value) {
            $where['ac_parent_id']=$value['ac_id'];
            $data1[$key]['children']   = Model()->table('article_class')->where($where)->select();

        } 

        //调下面的排序最高的文章
        $wenzhang= Model()->table('article')->order('article_sort desc')->limit(3)->select();
        Tpl::output('wenzhang',$wenzhang);
        Tpl::output('sub_class_list',$data);
        Tpl::output('sub_class_list1',$data1);
        Tpl::showpage('seller_help');
    }
  
    public function seller_inforOp() {
        $condition['ac_parent_id']  = 0;
        $data = Model()->table('article_class')->where($condition)->select();
        foreach ($data as $key => $value) {
            $where['ac_parent_id']=$value['ac_id'];
            $data[$key]['children']   = Model()->table('article_class')->where($where)->select();

        }
        if(!empty($_GET['article_id'])){
            $condition1['article_id'] = intval($_GET['article_id']);
        }else{
            $condition1['ac_id'] = intval($_GET['ac_id']);
        }
        
        $data1 = Model()->table('article')->where($condition1)->order('article_id desc')->find();
        if( $data1['article_time'] == 0){
            $data1['article_time']=date('Y-m-d',time());
        }else{
            $data1['article_time'] = date('Y-m-d',$data1['article_time']);
        }
       
        //面包屑
        $mianbao=Model()->table('article_class')->where($condition1)->find();
        $condition2['ac_id'] = $mianbao['ac_parent_id'];
        $mianbao1=Model()->table('article_class')->where($condition2)->find();

        Tpl::output('mianbao',$mianbao);
        Tpl::output('mianbao1',$mianbao1);
        Tpl::output('sub_class_list',$data);
        Tpl::output('data1',$data1);
        Tpl::output('title',$data1['article_title']);
        Tpl::output('description',$data1['article_blurb']);
        Tpl::output('keywords',$data1['article_keywords']);
        Tpl::showpage('seller_infor');
    }

     //商学院首页
    public function businessOp() {
        $member_level = $this->level($_SESSION['member_level']);
        //企业新闻
        $news1 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>71,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($news1 as $key => $value) {
            $news1[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        $news2 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>71,'is_school'=>1))->order('article_sort desc')->limit('4,4')->select();
        foreach ($news2 as $key => $value) {
            $news2[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }

        //特聘讲师
        $jiangshi1 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>73,'is_school'=>1))->order('article_sort desc')->limit(8)->select();
        foreach ($jiangshi1 as $key => $value) {
            $jiangshi1[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        $jiangshi2 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>73,'is_school'=>1))->order('article_sort desc')->limit(8,8)->select();
        foreach ($jiangshi2 as $key => $value) {
            $jiangshi2[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        //·注册讲师
        $reg1 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>74,'is_school'=>1))->order('article_sort desc')->limit(8)->select();
        foreach ($reg1 as $key => $value) {
            $reg1[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        $reg2 = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>74,'is_school'=>1))->order('article_sort desc')->limit('6,8')->select();
        foreach ($reg2 as $key => $value) {
            $reg2[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        //在线点播
        $video = Model()->table('article')->field('article_id,article_title,article_url,article_blurb,article_img')->where(array('ac_id'=>75,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($video as $key => $value) {
            $video[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
            $a = $value['article_url'];
            $pos1 = strpos($a, '/id_')+4;
            $pos2 = strpos($a, '.html');
            $video[$key]['article_url'] = substr($a, $pos1, $pos2-$pos1);
        }
        //最新课程6
        $where['ac_id'] = array('in','75,77');
        $where['is_school'] = 1;
        $now =  Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where($where)->order('article_time desc')->limit(6)->select();
        //企业精神
        $jingshen = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>76,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($jingshen as $key => $value) {
            $jingshen[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        //最新案例
        $anli = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>78,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($anli as $key => $value) {
            $anli[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        //业务指导
       /* $zhidao = Model()->table('article')->field('article_id,article_title,article_url,article_blurb,article_img')->where(array('ac_id'=>77,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($zhidao as $key => $value) {
            $zhidao[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
            $b = $value['article_url'];
            $pos1 = strpos($b, '/id_')+4;
            $pos2 = strpos($b, '.html');
            $video[$key]['article_url'] = substr($b, $pos1, $pos2-$pos1);
        }*/
        $zhidao = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>77,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($zhidao as $key => $value) {
            $zhidao[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        //行业解读
        $jiedu = Model()->table('article')->field('article_id,article_title,article_blurb,article_img')->where(array('ac_id'=>79,'is_school'=>1))->order('article_sort desc')->limit(4)->select();
        foreach ($jiedu as $key => $value) {
            $jiedu[$key]['article_title'] = htmlspecialchars_decode(mb_substr($value['article_title'],0,30,'utf-8'));
            $jiedu[$key]['article_img'] = 'data/upload/shop/article/'.$value['article_img'];
        }
        $title='商学院官网';
        Tpl::output('title',$title);
        Tpl::output('member_level',$member_level);
        Tpl::output('news1',$news1);
        Tpl::output('news2',$news2);
        Tpl::output('jiangshi1',$jiangshi1);
        Tpl::output('jiangshi2',$jiangshi2);
        Tpl::output('reg1',$reg1);
        Tpl::output('reg2',$reg2);
        Tpl::output('video',$video);
        Tpl::output('now',$now);
        Tpl::output('jingshen',$jingshen);
        Tpl::output('anli',$anli);
        Tpl::output('zhidao',$zhidao);
        Tpl::output('jiedu',$jiedu);
        Tpl::showpage('business');
    }

    //商学院内容页
    public function business_inforOp(){
        $member_level = $this->level($_SESSION['member_level']);
        $article_id = intval($_GET['article_id']);
        $data = Model()->table('article')->field('article_id,ac_id,article_title,article_content')->where(array('article_id'=>$article_id,'is_school'=>1))->find();
        $where['article_id'] = array('lt',$article_id);
        $where['is_school']  = 1;
        $where1['article_id'] = array('gt',$article_id);
        $where1['is_school']  = 1;
        $shang = Model()->table('article')->field('article_id')->where($where)->order('article_time desc')->find();
        $xia = Model()->table('article')->field('article_id')->where($where1)->find();
        $data['shang_id'] = $shang['article_id'];
        $data['xia_id'] = $xia['article_id'];
        $title=$data['article_title'];
        Tpl::output('member_level',$member_level);
        Tpl::output('title',$title);
        Tpl::output('data',$data);
        Tpl::showpage('business_infor');
    }

    //商学院视频页
    public function business_videoOp()
    {
        $article_id = intval($_GET['article_id']);
        $data = Model()->table('article')->field('article_id,ac_id,article_title,article_url')->where(array('article_id'=>$article_id,'is_school'=>1))->find();
        $a = $data['article_url'];
        $pos1 = strpos($a, '/id_')+4;
        $pos2 = strpos($a, '.html');
        $data['article_url'] = substr($a, $pos1, $pos2-$pos1);
        $title=$data['article_title'];
        Tpl::output('title',$title);
        Tpl::output('data',$data);
        Tpl::showpage('business_video');
    }

    private function level($level){
        switch ($level) {
            case 1:
                return '会员';
                break;
            case 2:
                return '端口代理';
                break;
            case 3:
                return '区县代理';
                break;
            case 4:
                return '市级代理';
                break;
            case 5:
                return '省级代理';
                break;
            default:
                return '见习会员';
                break;
        }
    } 

}
