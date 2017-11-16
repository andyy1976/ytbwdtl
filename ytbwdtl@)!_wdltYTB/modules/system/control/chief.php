<?php
defined('In33hao') or exit('Access Invalid!');

class chiefControl extends SystemControl{

    const EXPORT_SIZE = 1000;
	private $links = array(
	    array('url'=>'act=chief&op=index','lang'=>'member_index_manage')		 
    );

    public function __construct(){
       parent::__construct();      
    }

    public function indexOp() {
        //提成设置
    	$percent=Model('chief'); 
    	if($_POST){  	
	    	foreach($_POST as $key => $value){
	    		if(is_numeric($value) && $value>0){
	    			$percent->where(array('content'=>$key))->update(array('chief'=>$value));  	    			
	    		}else{
	    			continue;
	    		}
	    	}
    	}
	    $content=$percent->select();
	    Tpl::output('top_link',$this->sublink($this->links,'index'));
		Tpl::setDirquna('system');
		Tpl::output('content',$content);	
		Tpl::showpage('chief');		        
    }
}
?>