<?php
/**
 * 专题管理管理
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */
defined('In33hao') or exit('Access Invalid!');

class zhuantiControl extends BaseHomeControl {

    public function __construct() {
        parent::__construct();
    }
    /**
     * 教师节活动专题
     **/
    public function teachersdayOp() {
        // Tpl::showpage('zhuanti_teachersday.list');
    }

    /**
     * 国庆节活动专题
     **/
    public function nationaldayOp() {
        Tpl::showpage('zhuanti_nationalday.list');
    }
}
