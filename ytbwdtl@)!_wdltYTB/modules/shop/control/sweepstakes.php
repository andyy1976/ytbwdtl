<?php
/**
 * 兑换礼品管理
 *
 * @好商城提供技术支持 授权请购买shopnc授权
 * @license    http://www.33hao.com
 * @link       交流群号：138182377
 */

defined('In33hao') or exit('Access Invalid!');
class sweepstakesControl extends SystemControl{
    public function __construct(){
        parent::__construct();
        Language::read('pointprod,pointorder');
    }

    public function indexOp() {
        $this->sweepstakesOp();
    }

    /**
     * 抽奖活动列表
     */
    public function sweepstakesOp()
    {
		Tpl::setDirquna('shop');
        Tpl::showpage('sweepstakes.list');
    }

    /**
     * 抽奖活动列表
     */
    public function sweepstakes_xmlOp()
    {
        $condition = array();

        if (strlen($q = trim($_REQUEST['query']))) {
            switch ($_REQUEST['qtype']) {
                case 'sweepstakes_name':
                    $condition['sweepstakes_name'] = array('like', '%'.$q.'%');
                    break;
            }
        }

        switch ($_REQUEST['sortname']) {
            case 'sweepstakes_cons':
            case 'praise_count':
            case 'start_time':
            case 'end_time':
            case 'sweepstakes_state':
                $sort = $_REQUEST['sortname'];
                break;
            default:
                $sort = 'id ';
                break;
        }
        if ($_REQUEST['sortorder'] != 'asc') {
            $sort .= ' desc';
        }

        $sweepstakes_model = Model('sweepstakes');
        $prod_list = (array) $sweepstakes_model->getPointProdList(
            $condition,
            '*',
            $sort,
            0,
            $_REQUEST['rp']
        );
        $data = array();
        $data['now_page']  = $sweepstakes_model->shownowpage();
        $data['total_num'] = $sweepstakes_model->gettotalnum();

        foreach ($prod_list as $val) {
            $o = '<a class="btn red confirm-del-on-click" href="javascript:;" data-href="' . urlAdminShop('sweepstakes', 'prod_drop', array(
                'pg_id' => $val['id'],
            )) . '"><i class="fa fa-trash-o"></i>删除</a>';

            $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

            $o .= '<li><a href="' . urlAdminShop('sweepstakes', 'prod_edit', array(
                'pg_id' => $val['id'],
            )) . '">编辑抽奖</a></li>';
            //20170930潘丙福添加-判断是否已经设置奖项
            $awards = $sweepstakes_model->table('sweepstakes_award')->where(array('sweepstakes_id' => $val['id']))->select();
            if (count($awards) > 0) {
                $o .= '<li><a href="' . urlAdminShop('sweepstakes', 'award_list', array('pg_id' => $val['id'])) . '">查看奖项</a></li>';
            } else {
                $o .= '<li><a href="' . urlAdminShop('sweepstakes', 'add_award', array('pg_id' => $val['id'],'praise_count'=> $val['praise_count'])) . '">设置奖项</a></li>';
            }

            if ($val['sweepstakes_state'] == '1') {
                $o .= '<li><a href="javascript:;" data-ie-column="sweepstakes_state" data-ie-value="0">停止抽奖</a></li>';
            } else {
                $o .= '<li><a href="javascript:;" data-ie-column="sweepstakes_state" data-ie-value="1">启动抽奖</a></li>';
            }

            $o .= '</ul></span>';

            $i = array();
            $i['operation'] = $o;
            $i['sweepstakes_name'] = $val['sweepstakes_name'];
            $i['sweepstakes_cons'] = $val['sweepstakes_cons'];
            $i['praise_count'] = $val['praise_count'];
            $i['sweepstakes_bgimg'] = <<<EOB
<a href="javascript:;" class="pic-thumb-tip"
onmouseout="toolTip()" onmouseover="toolTip('<img src=\'{$val['sweepstakes_bgimg_small']}\'>')">
<i class='fa fa-picture-o'></i></a>
EOB;
            $i['start_time'] = date('Y-m-d H:i:s', $val['start_time']);
            $i['end_time']   = date('Y-m-d H:i:s', $val['end_time']);
            $countsTmp = Model()->table('sweepstakes_orders')->where(array('sweepstakes_id' => $val['id']))->count(); 
            $i['points_total'] = $countsTmp*$i['sweepstakes_cons'];
            $i['sweepstakes_state'] = $val['sweepstakes_state'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>开启</span>'
                : '<span class="no"><i class="fa fa-ban"></i>停止</span>';

            $data['list'][$val['id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 抽奖活动添加
     */
    public function sweepstakes_addOp(){
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        $upload_model = Model('upload');
        if (chksubmit()){
            //验证表单
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["sweepstakes_name"],"require"=>"true","message"=>'请添加抽奖活动名称');
            $validate_arr[] = array("input"=>$_POST["sweepstakes_cons"],"require"=>"true","validator"=>"DoublePositive","message"=>'参加抽奖活动每次消耗云豆数不正确');
            $validate_arr[] = array('input'=>$_POST['praise_count'],'require'=>'true','validator'=>'IntegerPositive','message'=>'抽奖活动奖项数量填写不正确');
            $validate_arr[] = array('input'=>$_POST['textfield'],'require'=>'true','message'=>'请上传抽奖活动的转盘图片');
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'','error');
            }

            $model_sweepstakes = Model('sweepstakes');
            $sweepstakes_array = array();
            $sweepstakes_array['sweepstakes_name']     = trim($_POST['sweepstakes_name']);
            $sweepstakes_array['sweepstakes_cons']     = trim($_POST['sweepstakes_cons']);
            $sweepstakes_array['praise_count']         = trim($_POST['praise_count']);
            $sweepstakes_array['sweepstakes_add_time'] = time();
            $sweepstakes_array['sweepstakes_state']    = intval($_POST['sweepstakes_state']);
            //抽奖活动时间处理
            $starttime = trim($_POST['start_time']);
            $sdatearr = explode('-',$starttime);
            $starttime = mktime(intval($_POST['starthour']),0,0,$sdatearr[1],$sdatearr[2],$sdatearr[0]);
            unset($sdatearr);

            $endtime = trim($_POST['end_time']);
            $edatearr = explode('-',$endtime);
            $endtime = mktime(intval($_POST['endhour']),0,0,$edatearr[1],$edatearr[2],$edatearr[0]);

            $sweepstakes_array['start_time']           = $starttime;
            $sweepstakes_array['end_time']             = $endtime;

            //添加礼品代表图片
            $indeximg_succ = false;
            if (!empty($_FILES['sweepstakes_bgimg']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_POINTPROD);
                $upload->set('thumb_width', '60,420');
                $upload->set('thumb_height','60,420');
                $upload->set('thumb_ext','_small,_mid');
                $result = $upload->upfile('sweepstakes_bgimg');
                if ($result){
                    $indeximg_succ = true;
                    $sweepstakes_array['sweepstakes_bgimg'] = $upload->file_name;
                }else {
                    showDialog($upload->error,'','error');
                }
            }
            $state = $model_sweepstakes->addPointGoods($sweepstakes_array);
            if($state){
                $this->log('添加抽奖活动成功'.'['.$_POST['sweepstakes_name'].']');
                showDialog('添加抽奖活动成功','index.php?act=sweepstakes&op=index','succ');
            }
        }
        Tpl::output('PHPSESSID',session_id());
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        Tpl::output('hourarr',$hourarr);
		Tpl::setDirquna('shop');
        Tpl::showpage('sweepstakes.add');
    }

    /**
     * 抽奖活动编辑
     */
    public function prod_editOp(){
        $hourarr = array('00','01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23');
        $upload_model = Model('upload');
        $pg_id = intval($_GET['pg_id']);
        if (!$pg_id){
            showDialog('此抽奖活动不存在','index.php?act=sweepstakes&op=index','error');
        }
        $model_sweepstakes = Model('sweepstakes');
        //查询抽奖活动是否存在
        $prod_info = $model_sweepstakes->getPointProdInfo(array('id'=>$pg_id));
        if (!$prod_info){
            showDialog('此抽奖活动不存在','index.php?act=sweepstakes&op=index','error');
        }
        if (chksubmit()){
            //验证表单
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["sweepstakes_name"],"require"=>"true","message"=>'请添加抽奖活动名称');
            $validate_arr[] = array("input"=>$_POST["sweepstakes_cons"],"require"=>"true","validator"=>"DoublePositive","message"=>'参加抽奖活动每次消耗云豆数不正确');
            $validate_arr[] = array('input'=>$_POST['praise_count'],'require'=>'true','validator'=>'IntegerPositive','message'=>'抽奖活动奖项数量填写不正确');
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'','error');
            }

            //实例化抽奖活动模型
            $model_sweepstakes = Model('sweepstakes');
            $sweepstakes_array = array();
            $sweepstakes_array['sweepstakes_name']     = trim($_POST['sweepstakes_name']);
            $sweepstakes_array['sweepstakes_cons']     = trim($_POST['sweepstakes_cons']);
            $sweepstakes_array['praise_count']         = trim($_POST['praise_count']);
            $sweepstakes_array['sweepstakes_add_time'] = time();
            $sweepstakes_array['sweepstakes_state']    = intval($_POST['sweepstakes_state']);
            //抽奖活动时间处理
            $starttime = trim($_POST['start_time']);
            $sdatearr = explode('-',$starttime);
            $starttime = mktime(intval($_POST['starthour']),0,0,$sdatearr[1],$sdatearr[2],$sdatearr[0]);
            unset($sdatearr);

            $endtime = trim($_POST['end_time']);
            $edatearr = explode('-',$endtime);
            $endtime = mktime(intval($_POST['endhour']),0,0,$edatearr[1],$edatearr[2],$edatearr[0]);

            $sweepstakes_array['start_time']           = $starttime;
            $sweepstakes_array['end_time']             = $endtime;

            //添加礼品代表图片
            $indeximg_succ = false;
            if (!empty($_FILES['sweepstakes_bgimg']['name'])){
                $upload = new UploadFile();
                $upload->set('default_dir',ATTACH_POINTPROD);
                $upload->set('thumb_width', '60,420');
                $upload->set('thumb_height','60,420');
                $upload->set('thumb_ext','_small,_mid');
                $result = $upload->upfile('sweepstakes_bgimg');
                if ($result){
                    $indeximg_succ = true;
                    $sweepstakes_array['sweepstakes_bgimg'] = $upload->file_name;
                }else {
                    showDialog($upload->error,'','error');
                }
            }
            $state = $model_sweepstakes->editPointProd($sweepstakes_array,array('id'=>$prod_info['id']));
            if($state){
                $this->log('编辑修改抽奖活动成功'.'['.$_POST['sweepstakes_name'].']');
                showDialog('修改抽奖活动成功','index.php?act=sweepstakes&op=index','succ');
            }
        }else {
            Tpl::output('PHPSESSID',session_id());
            Tpl::output('hourarr',$hourarr);
            Tpl::output('prod_info',$prod_info);
			Tpl::setDirquna('shop');
            Tpl::showpage('sweepstakes.edit');
        }
    }

    /**
     * 删除抽奖活动
     */
    public function prod_dropOp(){
        $pg_id = intval($_GET['pg_id']);
        if (!$pg_id){
            showDialog('删除抽奖活动失败','index.php?act=sweepstakes&op=sweepstakes','error');
        }
        $model_sweepstakes = Model('sweepstakes');
        //查询抽奖活动是否存在
        $sweepstakes_info = $model_sweepstakes->getPointProdInfo(array('id'=>$pg_id));
        if (!is_array($sweepstakes_info) || count($sweepstakes_info)<=0){
            showDialog('删除抽奖活动失败','index.php?act=sweepstakes&op=sweepstakes','error');
        }
        //删除操作
        $result = $model_sweepstakes->delPointProdById($pg_id);
        if($result) {
            $this->log(L('nc_del,admin_pointprodp').'[ID:'.id.']-抽奖活动');
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 批量删除抽奖活动
     */
    public function prod_dropallOp()
    {
        $pg_id = array();
        foreach (explode(',', (string) $_REQUEST['pg_id']) as $i) {
            $pg_id[(int) $i] = null;
        }
        unset($pg_id[0]);
        $pg_id = array_keys($pg_id);
        if (!$pg_id){
            showDialog('删除抽奖活动失败','index.php?act=sweepstakes&op=sweepstakes','error');
        }
        $result = Model('sweepstakes')->delPointProdById($pg_id);
        if($result) {
            $this->log(L('nc_del,admin_pointprodp').'[ID:'.implode(',',$pg_id).']-抽奖活动');
            $this->jsonOutput();
        } else {
            $this->jsonOutput('操作失败');
        }
    }

    /**
     * 抽奖活动异步状态修改
     */
    public function ajaxOp()
    {
        $id = intval($_GET['id']);
        if ($id <= 0){
            echo 'false'; exit;
        }
        $model_sweepstakes = Model('sweepstakes');
        $update_array = array();
        $update_array[$_GET['column']] = trim($_GET['value']);
        $model_sweepstakes->editPointProd($update_array,array('id'=>$id));
        echo 'true';exit;
    }
    /**
     * 云豆礼品上传
     */
    public function pointprod_pic_uploadOp(){
        /**
         * 上传图片
         */
        $upload = new UploadFile();
        $upload->set('default_dir',ATTACH_POINTPROD);

        $result = $upload->upfile('fileupload');
        if ($result){
            $_POST['pic'] = $upload->file_name;
        }else {
            echo 'error';exit;
        }
        /**
         * 模型实例化
         */
        $model_upload = Model('upload');
        /**
         * 图片数据入库
        */
        $insert_array = array();
        $insert_array['file_name'] = $_POST['pic'];
        $insert_array['upload_type'] = '6';
        $insert_array['file_size'] = $_FILES['fileupload']['size'];
        $insert_array['upload_time'] = time();
        $insert_array['item_id'] = intval($_POST['item_id']);
        $result = $model_upload->add($insert_array);
        if ($result){
            $data = array();
            $data['file_id'] = $result;
            $data['file_name'] = $_POST['pic'];
            $data['file_path'] = $_POST['pic'];
            /**
             * 整理为json格式
             */
            $output = json_encode($data);
            echo $output;
        }
    }
    /**
     * ajax操作删除已上传图片
     */
    public function ajaxdeluploadOp(){
        //删除文章图片
        if (intval($_GET['file_id']) > 0){
            $model_upload = Model('upload');
            /**
             * 删除图片
             */
            $file_array = $model_upload->getOneUpload(intval($_GET['file_id']));
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_POINTPROD.DS.$file_array['file_name']);
            /**
             * 删除信息
             */
            $model_upload->del(intval($_GET['file_id']));
            echo 'true';exit;
        }else {
            echo 'false';exit;
        }
    }

    /**
     * 中奖信息列表
     */
    public function pointorder_listOp()
    {
        $states = Model('sweepstakesorder')->getPointOrderStateBySign();
        Tpl::output('states', $states);
        Tpl::setDirquna('shop');
        Tpl::showpage('sweepstakesorder.list');
    }

    /**
     * 中奖信息列表XML
     */
    public function sweepstakesorder_list_xmlOp()
    {
        $condition = array();

        if ($_REQUEST['advanced']) {
            if (strlen($q = trim((string) $_REQUEST['sweepstakes_name']))) {
                $condition['sweepstakes_name'] = array('like', '%' . $q . '%');
            }
            if (strlen($q = trim((string) $_REQUEST['id']))) {
                $condition['id'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['member_id']))) {
                $condition['member_id'] = $q;
            }
            if (strlen($q = trim((string) $_REQUEST['order_state']))) {
                $condition['order_state'] = (int) $q;
            }
            //20171019潘丙福添加开始-增加时间搜索
            $if_start_time    = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_start_date']);
            $if_end_time      = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_end_date']);
            $start_unixtime   = $if_start_time ? strtotime($_REQUEST['query_start_date']) : null;
            $end_unixtime     = $if_end_time ? strtotime($_REQUEST['query_end_date']): null;
            if ($start_unixtime || $end_unixtime) {
                $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
            }
            //20171019潘丙福添加结束
        } else {
            if (strlen($q = trim($_REQUEST['query']))) {
                switch ($_REQUEST['qtype']) {
                    case 'id':
                        $condition['id'] = $q;
                        break;
                    case 'member_id':
                        $condition['member_id'] = $q;
                        break;
                }
            }
        }
        //20171018潘丙福添加开始-状态大于2
        if (!$condition['order_state']) {
            $condition['order_state'] = array('gt', 2);
        }
        //20171018潘炳福添加结束
        $model_pointorder = Model('sweepstakesorder');
        $list = (array) $model_pointorder->getPointOrderList($condition, '*', $_REQUEST['rp'], 0, 'id desc');
        $data = array();
        $data['now_page'] = $model_pointorder->shownowpage();
        $data['total_num'] = $model_pointorder->gettotalnum();

        foreach ($list as $val) {
            // $o = '<a class="btn green" href="' . urlAdminShop('pointprod', 'order_info', array(
            //     'order_id' => $val['point_orderid'],
            // )) . '"><i class="fa fa-list-alt"></i>查看</a>';
            $o = null;
            if (
                $val['point_orderallowship']
                || $val['point_orderalloweditship']
                || $val['point_orderallowcancel']
                || $val['point_orderallowdelete']
            ) {
                $o .= '<span class="btn"><em><i class="fa fa-cog"></i>设置<i class="arrow"></i></em><ul>';

                if ($val['point_orderallowship']) {
                    // 发货（已确认付款，待发货）
                    $o .= '<li><a href="' . urlAdminShop('sweepstakes', 'order_ship', array(
                        'id' => $val['id'],
                    )) . '">设置发货</a></li>';
                }

                if ($val['point_orderalloweditship']) {
                    // 修改物流（已发货，待收货）
                    $o .= '<li><a href="' . urlAdminShop('sweepstakes', 'order_ship', array(
                        'id' => $val['id'],
                    )) . '">修改物流</a></li>';
                }

                if ($val['point_orderallowcancel']) {
                    // 取消订单（未发货）
                    $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('sweepstakes', 'order_cancel', array(
                        'id' => $val['id'],
                    )) . '">取消订单</a></li>';
                }

                if ($val['point_orderallowdelete']) {
                    // 删除订单
                    $o .= '<li><a class="confirm-on-click" href="' . urlAdminShop('sweepstakes', 'order_drop', array(
                        'id' => $val['id'],
                    )) . '">删除订单</a></li>';
                }

                $o .= '</ul></span>';
            }
            //查询收货信息
            $addressInfo = Model()->table('sweepstakes_orderaddress')->where(array('point_orderid' => $val['id']))->find();

            $i = array();
            $i['operation'] = $o;
            $i['id'] = $val['id'];
            $i['member_id'] = $val['member_id'];
            $i['add_time']  = date('Y-m-d H:i', $val['add_time']);
            $i['award_content'] = $val['award_content'];
            $i['sweepstakes_name'] = $val['sweepstakes_name'];
            $i['order_type'] = $val['order_type'] == 1 ? '实物订单' : '虚拟订单' ;
            $i['message'] = $val['message'];
            $i['phone_require'] = $val['phone_require'] == 0? '-' : $val['phone_require'];
            $i['order_state'] = $val['point_orderstatetext'];
            if ($addressInfo) {
                $i['order_address'] = $addressInfo['point_truename'].','.$addressInfo['point_mobphone'].','.$addressInfo['point_areainfo'].','.$addressInfo['point_address'];
            } else {
                $i['order_address'] = '-';
            }

            $data['list'][$val['id']] = $i;
        }

        echo Tpl::flexigridXML($data);
        exit;
    }

    /**
     * 删除兑换订单信息
     */
    public function order_dropOp(){
        $data = Model('sweepstakesorder')->delPointOrderByOrderID($_GET['id']);
        if ($data['state']){
            showDialog(L('admin_pointorder_del_success'),'index.php?act=sweepstakes&op=pointorder_list','succ');
        } else {
            showDialog($data['msg'],'index.php?act=sweepstakes&op=pointorder_list','error');
        }
    }

    /**
     * 取消兑换
     */
    public function order_cancelOp(){
        $model_pointorder = Model('sweepstakesorder');
        //取消订单
        $data = $model_pointorder->cancelPointOrder($_GET['id']);
        if ($data['state']){
            showDialog(L('admin_pointorder_cancel_success'),'index.php?act=sweepstakes&op=pointorder_list','succ');
        }else {
            showDialog($data['msg'],'index.php?act=sweepstakes&op=pointorder_list','error');
        }
    }

    /**
     * 发货
     */
    public function order_shipOp(){
        $order_id = intval($_GET['id']);
        if ($order_id <= 0){
            showDialog('中奖信息id错误','index.php?act=sweepstakes&op=pointorder_list','error');
        }
        $model_pointorder = Model('sweepstakesorder');
        //获取订单状态
        $pointorderstate_arr = $model_pointorder->getPointOrderStateBySign();

        //查询订单信息
        $where = array();
        $where['id'] = $order_id;
        $where['order_state'] = array('in',array($pointorderstate_arr['waitship'][0],$pointorderstate_arr['waitreceiving'][0]));//待发货和已经发货状态
        $order_info = $model_pointorder->getPointOrderInfo($where);
        if (!$order_info){
            showDialog('无此中奖信息','index.php?act=sweepstakes&op=pointorder_list','error');
        }
        if (chksubmit()){
            if ($_POST['e_code']) {
                $obj_validate = new Validate();
            
                $validate_arr[] = array("input"=>$_POST["shippingcode"],"require"=>"true","message"=>L('admin_pointorder_ship_code_nullerror'));
                $obj_validate->validateparam = $validate_arr;
                $error = $obj_validate->validate();
                if ($error != ''){
                    showDialog(L('error').$error,'index.php?act=sweepstakes&op=pointorder_list','error');
                }
            }
            //发货
            $data = $model_pointorder->shippingPointOrder($order_id, $_POST, $order_info);
            if ($data['state']){
                //
                $data1['order_state'] = 40;
                $data1['send_time'] = time();
                if($order_info['order_type']==1 && $order_info['award_id']==26){
                    Model()->table('sweepstakes_orders')->where(array('id'=>$order_info['id']))->update($data1);
                }
                showDialog('发货修改成功','index.php?act=sweepstakes&op=pointorder_list','succ');
            }else {
                showDialog($data['msg'],'index.php?act=sweepstakes&op=pointorder_list','error');
            }
        } else {
            $express_list = Model('express')->getExpressList();
            Tpl::output('express_list',$express_list);
            Tpl::output('order_info',$order_info);
			Tpl::setDirquna('shop');
            Tpl::showpage('sweepstakesorder.ship');
        }
    }
    /**
     * 兑换信息详细
     */
    public function order_infoOp(){
        $order_id = intval($_GET['order_id']);
        if ($order_id <= 0){
            showDialog(L('admin_pointorder_parameter_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        //查询订单信息
        $model_pointorder = Model('pointorder');
        $order_info = $model_pointorder->getPointOrderInfo(array('point_orderid'=>$order_id));
        if (!$order_info){
            showDialog(L('admin_pointorderd_record_error'),'index.php?act=pointprod&op=pointorder_list','error');
        }
        $orderstate_arr = $model_pointorder->getPointOrderState($order_info['point_orderstate']);
        $order_info['point_orderstatetext'] = $orderstate_arr[1];

        //查询兑换订单收货人地址
        $orderaddress_info = $model_pointorder->getPointOrderAddressInfo(array('point_orderid'=>$order_id));
        Tpl::output('orderaddress_info',$orderaddress_info);

        //兑换商品信息
        $prod_list = $model_pointorder->getPointOrderGoodsList(array('point_orderid'=>$order_id));
        Tpl::output('prod_list',$prod_list);

        //物流公司信息
        if ($order_info['point_shipping_ecode'] != ''){
            $data = Model('express')->getExpressInfoByECode($order_info['point_shipping_ecode']);
            if ($data['state']){
                $express_info = $data['data']['express_info'];
            }
            Tpl::output('express_info',$express_info);
        }

        Tpl::output('order_info',$order_info);
		Tpl::setDirquna('shop');
        Tpl::showpage('pointorder.info');
    }

    public function add_awardOp()
    {
        $award_zh      = array('一等奖','二等奖','三等奖','四等奖','五等奖','六等奖','七等奖','八等奖');
        $add_awardinfo = array();
        $add_awardinfo['id']           = $_GET['pg_id'];
        $add_awardinfo['praise_count'] = $_GET['praise_count'];

        if (chksubmit()) {
            //整理传输的数据
            $praise_count   = $_POST['praise_count'];
            $sweepstakes_id = intval($_POST['sweepstakes_id']);
            //判断中间概率相加是否为100、同时整理要批量插入的数据
            $chance_amount = null;
            $insertArray   = array();
            for ($i=0; $i < $praise_count; $i++) {
                $tmpPraiseName     = 'praise_name'.$i;
                $tmpMinAngle       = 'min_angle'.$i;
                $tmpMaxAngle       = 'max_angle'.$i;
                $tmpPraiseNumber   = 'praise_number'.$i;
                $tmpChance         = 'chance'.$i;
                $tmpPraiseContent  = 'praise_content'.$i;
                $tmpIsVr           = 'is_vr'.$i;
                //中奖概率相加
                $chance_amount     += $_POST[$tmpChance];
                //整理要存储的数据
                $tmpArray = array();
                $tmpArray['praise_name']    = $_POST[$tmpPraiseName];
                $tmpArray['min_angle']      = $_POST[$tmpMinAngle];
                $tmpArray['max_angle']      = $_POST[$tmpMaxAngle];
                $tmpArray['praise_number']  = $_POST[$tmpPraiseNumber];
                $tmpArray['chance']         = $_POST[$tmpChance];
                $tmpArray['praise_content'] = $_POST[$tmpPraiseContent];
                $tmpArray['is_vr']          = $_POST[$tmpIsVr];
                $tmpArray['sweepstakes_id'] = $sweepstakes_id;
                $tmpArray['add_time']       = time();
                $insertArray[] = $tmpArray;
            }
            if ($chance_amount != 100) {
                showDialog('中奖概率相加必须为100','index.php?act=sweepstakes&op=index','error');
            }
            //执行插入数据库操作
            $result = Model()->table('sweepstakes_award')->insertAll($insertArray);
            if ($result) {
                showDialog('添加奖项成功','index.php?act=sweepstakes&op=index','succ');
            } else {
                showDialog('添加奖项失败','index.php?act=sweepstakes&op=index','error');
            }
        }

        Tpl::output('add_awardinfo',$add_awardinfo);
        Tpl::output('award_zh',$award_zh);
        Tpl::setDirquna('shop');
        Tpl::showpage('sweepstakes.add_award');
    }

    /**
     * 抽奖活动列表
     */
    public function award_listOp()
    {
        Tpl::setDirquna('shop');
        Tpl::showpage('sweepstakes_award.list');
    }

    /**
     * 查看奖项
     */
    public function award_listxmlOp()
    {
        //获取奖项信息
        $sweepstakes_model = Model('sweepstakes');
        $award_list = $sweepstakes_model->table('sweepstakes_award')->where(array('sweepstakes_id' => $_GET['pg_id']))->select();
        $data = array();
        $data['now_page']  = $sweepstakes_model->shownowpage();
        $data['total_num'] = $sweepstakes_model->gettotalnum();
        foreach ($award_list as $val) {
            $o = '<a class="btn red" href="' . urlAdminShop('sweepstakes', 'edit_award', array(
                'award_id' => $val['id'],
            )) . '"><i class="fa fa-cog"></i>编辑</a>';

            $i = array();
            $i['operation'] = $o;
            $i['praise_name'] = $val['praise_name'];
            $i['praise_content'] = $val['praise_content'];
            $i['praise_number'] = $val['praise_number'];
            $i['min_angle'] = $val['min_angle'];
            $i['max_angle'] = $val['max_angle'];
            $i['chance'] = $val['chance'];
            $i['is_vr'] = $val['is_vr'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>实物</span>'
                : '<span class="no"><i class="fa fa-ban"></i>虚拟</span>';
            $i['is_phone_require'] = $val['is_phone_require'] == '1'
                ? '<span class="yes"><i class="fa fa-check-circle"></i>是</span>'
                : '<span class="no"><i class="fa fa-ban"></i>否</span>';

            $data['list'][$val['id']] = $i;
        }
        echo Tpl::flexigridXML($data);
        exit;    
    }

    /**
     * 编辑奖项信息
     */
    public function edit_awardOp()
    {
        $award_id = intval($_GET['award_id']);
        if (!$award_id){
            showDialog('此奖项不存在','index.php?act=sweepstakes&op=index','error');
        }
        $model_sweepstakes = Model('sweepstakes');
        //查询抽奖活动是否存在
        $award_info = $model_sweepstakes->table('sweepstakes_award')->find($award_id);
        if (!$award_info){
            showDialog('此奖项不存在','index.php?act=sweepstakes&op=index','error');
        }
        if (chksubmit()){
            //验证表单
            $obj_validate = new Validate();
            $validate_arr[] = array("input"=>$_POST["min_angle"],"require"=>"true","message"=>'请填写中奖的最小角度');
            $validate_arr[] = array("input"=>$_POST["max_angle"],"require"=>"true","message"=>'请填写中奖的最大角度');
            $validate_arr[] = array("input"=>$_POST["praise_number"],"require"=>"true","validator"=>"integerpositive","message"=>'奖品数量不正确');
            $validate_arr[] = array('input'=>$_POST['chance'],'require'=>'true','validator'=>'doublepositive','message'=>'中奖概率填写不正确');
            $obj_validate->validateparam = $validate_arr;
            $error = $obj_validate->validate();
            if ($error != ''){
                showDialog(L('error').$error,'','error');
            }

            //实例化抽奖活动模型
            $model_sweepstakes = Model('sweepstakes');
            $updateData = array();
            $updateData['id']             = $_POST['award_id'];
            $updateData['praise_content'] = $_POST['praise_content'];
            $updateData['praise_number']  = $_POST['praise_number'];
            $updateData['min_angle']      = $_POST['min_angle'];
            $updateData['max_angle']      = $_POST['max_angle'];
            $updateData['chance']         = $_POST['chance'];
            $updateData['is_vr']          = $_POST['is_vr'];
            $updateData['is_phone_require'] = $_POST['is_phone_require'];
            $updateData['add_time']       = time();
            $state = $model_sweepstakes->table('sweepstakes_award')->update($updateData);
            if($state){
                $this->log('编辑奖项内容成功,奖项id-'.'['.$_POST['id'].']');
                showDialog('编辑奖项内容成功','index.php?act=sweepstakes&op=award_list&pg_id='.$_POST['sweepstakes_id'],'succ');
            }
        }else {
            Tpl::output('PHPSESSID',session_id());
            Tpl::output('award_info',$award_info);
            Tpl::setDirquna('shop');
            Tpl::showpage('sweepstakes.edit_award');
        }
    }

    /**
     * csv导出
     */
    public function export_csvOp() 
    {
        // var_dump($_GET);exit;
        $condition = array();

        if (strlen($q = trim((string) $_REQUEST['sweepstakes_name']))) {
            $condition['sweepstakes_name'] = array('like', '%' . $q . '%');
        }
        if (strlen($q = trim((string) $_REQUEST['id']))) {
            $condition['id'] = $q;
        }
        if (strlen($q = trim((string) $_REQUEST['member_id']))) {
            $condition['member_id'] = $q;
        }
        if (strlen($q = trim((string) $_REQUEST['order_state']))) {
            $condition['order_state'] = (int) $q;
        }
        //20171019潘丙福添加开始-增加时间搜索
        $if_start_time    = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_start_date']);
        $if_end_time      = preg_match('/^20\d{2}-\d{2}-\d{2}$/', $_REQUEST['query_end_date']);
        $start_unixtime   = $if_start_time ? strtotime($_REQUEST['query_start_date']) : null;
        $end_unixtime     = $if_end_time ? strtotime($_REQUEST['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //20171019潘丙福添加结束
        if (!$condition['order_state']) {
            $condition['order_state'] = array('gt', 2);
        }

        $model_pointorder = Model('sweepstakesorder');
        $list = (array) $model_pointorder->getPointOrderList($condition, '*', $_REQUEST['rp'], 0, 'id desc');
        $data = array();
        // $data['now_page'] = $model_pointorder->shownowpage();
        // $data['total_num'] = $model_pointorder->gettotalnum();

        foreach ($list as $key => $val) {
            //查询收货信息
            $addressInfo = Model()->table('sweepstakes_orderaddress')->where(array('point_orderid' => $val['id']))->find();
            $i = array();
            $i['id'] = $val['id'];
            $i['member_id'] = $val['member_id'];
            $i['award_content'] = $val['award_content'];
            $i['sweepstakes_name'] = $val['sweepstakes_name'];
            $i['order_type'] = $val['order_type'] == 1 ? '实物订单' : '虚拟订单' ;
            $i['message'] = $val['message'];
            $i['phone_require'] = $val['phone_require'] == 0? '-' : $val['phone_require'];
            $i['order_state'] = $val['point_orderstatetext'];
            $i['order_address'] = $addressInfo['point_truename'].','.$addressInfo['point_mobphone'].','.$addressInfo['point_areainfo'].','.$addressInfo['point_address'];
            $data[$key] = $i;
        }
        $this->createCsv($data);
    }

    /**
     * 生成csv文件
     */
    private function createCsv($award_order_list) 
    {
        $data = array();
        foreach ($award_order_list as $value) {
            $param = array();
            $param['id']               = $value['id'];
            $param['member_id']        = $value['member_id'];
            $param['award_content']    = $value['award_content'];
            $param['sweepstakes_name'] = $value['sweepstakes_name'];
            $param['order_type']       = $value['order_type'];
            $param['message']          = $value['message'];
            $param['phone_require']    = $value['phone_require'];
            $param['order_state']      = $value['order_state'];
            $param['order_address']    = $value['order_address'];
            $data[$value['id']]        = $param;
        }

        $header = array(
                'id'               => '中奖ID',
                'member_id'        => '会员ID',
                'award_content'    => '奖品信息',
                'sweepstakes_name' => '活动名称',
                'order_type'       => '订单类型',
                'message'          => '留言',
                'phone_require'    => '充值手机号',
                'order_state'      => '订单状态',
                'order_address'    => '收货人信息'
        );
        array_unshift($data, $header);
        $csv = new Csv();
        $export_data = $csv->charset($data,CHARSET,'GBK');
        $csv->filename = $csv->charset('中奖记录表', CHARSET).'-'.date('Y-m-d');
        $csv->export($data);
    }

}
