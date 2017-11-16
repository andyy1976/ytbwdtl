<?php
defined('In33hao') or exit('Access Invalid!');

class card_chiefControl extends SystemControl{

    const EXPORT_SIZE = 1000;
	private $links = array(
	    array('url'=>'act=card_chief&op=index','lang'=>'member_index_manage')		 
    );

    public function __construct(){
       parent::__construct();      
    }

    public function indexOp() {
    	$card_chief=Model('card_chief_log');
    	$begin=mktime(0,0,0,date('m'),1,date('Y'));
    	$daili=array();
        $daili['addtime'] = array('gt',$begin);
    	$arr=$card_chief->where($daili)->order('member_level desc')->select();
    	if($arr){
    		foreach($arr as $key=>$value){
    			$arr[$key]['member_level']=member_level_name($value['member_level']);
    		}
    		$content=$arr;
    	}else{
    		$arr=card_chief_tables();
    		$card_chief->insertAll($arr);
    		foreach($arr as $key=>$value){
    			$arr[$key]['member_level']=member_level_name($value['member_level']);
    		}
    		$content=$arr;
    	}	    
    	   	
    	//var_dump($content)   ;     
	    Tpl::output('top_link',$this->sublink($this->links,'index'));
		Tpl::setDirquna('system');
		Tpl::output('content',$content);	
		Tpl::showpage('card_chief');		        
    }
}
?>