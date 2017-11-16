<?php
/**
 * 我的商城
 *
 * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class member_avatarControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp() {

        if (isset($_GET['img'])) {
            // $mobile = isset($_GET['mobile']) ? true : false;
            $img = $_GET['img'];
            $model_member = Model('member');
            $update = $model_member->editMember(
                array('member_id' => $this->member_info['member_id']),
                array('member_avatar' => $img)
            );
            echo $update ? 1 : 0;
        }
    }
}
