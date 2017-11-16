<?php
/**
 * 店铺分类管理
 *
 *
 *
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */



defined('In33hao') or exit('Access Invalid!');

class store_industryControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('store_class');
    }

    public function indexOp() {
	
        $this->store_classOp();
    }

    /**
     * 店铺分类
     */
    public function store_classOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_hangye');
        $store_class_list = $model_class->order('hy_id desc')->select();
        Tpl::output('class_list',$store_class_list);
		Tpl::setDirquna('shop');
        Tpl::showpage('store_industry.index');
    }

    /**
     * 商品分类添加
     */
    public function store_hangye_addOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_hangye');
        if (chksubmit()){//验证
           $sc_name=isset($_POST['sc_name'])?$_POST['sc_name']:'';
           $sc_bail=isset($_POST['sc_bail'])?$_POST['sc_bail']:'';
           $hy_littlename = isset($_POST['hy_littlename'])?$_POST['hy_littlename']:'';
           if(!empty($sc_name)){
            $con=array();
            $con['hymc_name']=$sc_name;
            $checkit=$model_class->where(array('hymc_name'=>$sc_name))->find();
            if($checkit){
               showMessage('行业名称已经存在！'); 
           }else{
            $data = array('hymc_name'=>$sc_name,'hy_dianshu'=>$sc_bail,'hy_littlename'=>$hy_littlename);
            $checkitsuccess=$model_class->insert($data);
            if($checkitsuccess){
             showMessage($lang['nc_common_save_succ'],'index.php?act=store_industry&op=store_class');
            }else{
              showMessage($lang['nc_common_save_fail']);
            }
           }
           }
        }
		Tpl::setDirquna('shop');
        Tpl::showpage('store_hangye.add');
    }

    /**
     * 编辑
     */
    public function store_class_editOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_hangye');

        if (chksubmit()){
            //验证
              $sc_id=$_POST['sc_id'];
              $sc_name=$_POST['sc_name'];
              $sc_bail=$_POST['sc_bail'];
              $hy_littlename=$_POST['hy_littlename'];
              if(empty($sc_id)){
                 showMessage($lang['illegal_parameter']);
              }
              if(!empty($sc_name)){
                 $con=array();
            $con['hymc_name']=$sc_name;
            $checkit=$model_class->where(array('hymc_name'=>$sc_name))->count();
            if($checkit>1){
               showMessage('行业名称已经存在！'); 
              }else{
                 $data = array('hymc_name'=>$sc_name,'hy_dianshu'=>$sc_bail,'hy_littlename'=>$hy_littlename);
                 $result = $model_class->where(array('hy_id'=>$sc_id))->update($data);
                 if ($result){
                    showMessage($lang['nc_common_save_succ'],'index.php?act=store_industry&op=store_class');
                }else {
                    showMessage($lang['nc_common_save_fail']);
                }  

              }
              }
               
               
            }
        

        $class_array = $model_class->where(array('hy_id'=>intval($_GET['hy_id'])))->find();
        if (empty($class_array)){
            showMessage($lang['illegal_parameter']);
        }

        Tpl::output('class_array',$class_array);
		Tpl::setDirquna('shop');
        Tpl::showpage('store_hangye.edit');
    }

    /**
     * 删除分类
     */
    public function store_class_delOp(){
        $lang   = Language::getLangContent();
        $model_class = Model('store_hangye');

        if (intval($_GET['hy_id']) > 0){
            $con = array();
            $con['hy_id'] = intval($_GET['hy_id']);
            $result=$model_class->where($con)->delete();
            if ($result) {
              showMessage($lang['nc_common_del_succ'],getReferer());
            }
        }
        showMessage($lang['nc_common_del_fail'],'index.php?act=store_industry&op=store_class');
    }

    
    
    
}
