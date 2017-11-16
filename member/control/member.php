<?php
/**
 * 会员中心——账户概览
 *
 *
 *
 * * @好商城 (c) 2015-2018 33HAO Inc. (http://www.33hao.com)
 * @license    http://www.33 hao.c om
 * @link       交流群号：138182377
 * @since      好商城提供技术支持 授权请购买shopnc授权
 */



defined('In33hao') or exit('Access Invalid!');

class memberControl extends BaseMemberControl{

    /**
     * 我的商城
     */
    public function homeOp() {
        $model_consume = Model('consume');
        $consume_list = $model_consume->getConsumeList(array('member_id' => $_SESSION['member_id']), '*', 0, 10);
        Tpl::output('consume_list', $consume_list);
        Tpl::showpage('member_home');
    }
    public  function homesOp(){
    	 Tpl::showpage('member_home1');
    }
    	public function kasOp(){
		$models = $_POST['member_id'];
		$arr = "";
		$model = model('member');
		$model->goup($models,$arr);
	}
	public function kassOp(){
		$models = $_POST['member_id'];
		$arr = "";
		$model = model('member');
		$model->goups($models,$arr);
	}

    //省代查看下面人数和个人详情
      public function myteamOp() {
      if($_SESSION['member_level']=='1'){
      		showMessage('','index.php?act=member&op=home');
      	}       	
    	$member = Model('member');   	
    	$field='member_id,member_pid,member_truename,member_mobile,member_level';
    	$t = time();
        $start = mktime(0,0,0,date("m",$t),date("d",$t),date("Y",$t));
        $member_info = $member->getMemberInfo(array('member_id'=>$_SESSION['member_id'])); 
    	$provinceid= $member ->getfby_member_id($member_info['member_id'],'member_provinceid'); 
    	$cityid = $member ->getfby_member_id($member_info['member_id'],'member_cityid');      
    	$areaid = $member ->getfby_member_id($member_info['member_id'],'member_areaid'); 
    	$partid = $member ->getfby_member_id($member_info['member_id'],'portid');  
    	 	
if($_GET['level'])  {
	    	switch ($_GET['level'])
		   {	   			
			case 1:
			  if($_SESSION['member_level']==5){
				  $data['member_provinceid']=$provinceid;  
				  $data['member_level']=1;
			  }elseif($_SESSION['member_level']==4){
				  $data['member_cityid']=$cityid;  
				  $data['member_level']=1;
			  }elseif($_SESSION['member_level']==3){
				  $data['member_areaid']=$areaid;  
				  $data['member_level']=1;
			  }elseif($_SESSION['member_level']==2){
				  $data['portid']=$partid;  
				  $data['member_level']=1;
			  }
			  break;
			case 2:
			  if($_SESSION['member_level']==5){
				  $data['member_provinceid']=$provinceid;  
				  $data['member_level']=2;
			  }elseif($_SESSION['member_level']==4){
				  $data['member_cityid']=$cityid;  
				  $data['member_level']=2;
			  }elseif($_SESSION['member_level']==3){
				  $data['member_areaid']=$areaid;  
				  $data['member_level']=2;
			  }
			  break;
			case 3:
			  if($_SESSION['member_level']==4){
				  $data['member_cityid']=$cityid;  
				  $data['member_level']=3;
			  }else{
			  	  $data['member_provinceid']=$provinceid;  
				  $data['member_level']=3;
			  }
			  break;
			case 4:
			  $data['member_provinceid']=$provinceid;  
			  $data['member_level']=4;
			  break;
			case 8://一级直推
			  $data['member_pid']=$_SESSION['member_id'];
			  break;
			case 9://今日新增
			  if($_SESSION['member_level']==5){
				  $data['member_provinceid']=$provinceid;  				
			  }elseif($_SESSION['member_level']==4){
				  $data['member_cityid']=$cityid;  				  
			  }elseif($_SESSION['member_level']==3){
				  $data['member_areaid']=$areaid;  
			  }elseif($_SESSION['member_level']==2){
				  $data['portid']=$partid;  
			  }				  
			  $data['member_time']=array('gt',$start);
			  break;
			case 10://未激活
			  if($_SESSION['member_level']==5){
				  $data['member_provinceid']=$provinceid; 
				   	$data['member_level'] = 0;			
			  }elseif($_SESSION['member_level']==4){
				  $data['member_cityid']=$cityid; 
				  	$data['member_level'] = 0; 				  
			  }elseif($_SESSION['member_level']==3){
				  $data['member_areaid']=$areaid; 
				  	$data['member_level'] = 0; 
			  }elseif($_SESSION['member_level']==2){
				  $data['portid']=$partid; 
				  	$data['member_level'] = 0; 
			  }		  
			  $data['member_level']=0;
			  break;							
			}

		}else{
			switch ($_SESSION['member_level'])
		    {	
				case 2:
				  $data['portid']=$partid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;

				case 3:
				  $data['member_areaid']=$areaid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;
				case 4:
				  $data['member_cityid']=$cityid; 
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;
				case 5:
				  $data['member_provinceid']=$provinceid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;
				case 6:
				  $data['split_id']=$splitid;  
				  $data['member_level']=array('lt',$_SESSION['member_level']);
				  break;				
		    }
			
		}		

    	$member_infos = $member ->where($data)->field($field)->page(30)->select();
    	switch ($_SESSION['member_level'])
		{	
			case 1: 
				$member_new   = $member ->where(array('member_provinceid'=>$provinceid,'member_cityid'=>$cityid,'member_areaid'=>$areaid,'member_time'=>array('gt',$start)))->field('member_id,member_truename,member_mobile,member_level')->count();//获取今日新增会员人数
				$member_x = $member->where(array("member_pid"=>$_SESSION['member_id']))->field($field)->select();//查询所有下级
				break;
			case 2:
			
    	          $member_vip   = $member ->where(array('portid'=>$partid,'member_level'=>1))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('portid'=>$partid,'member_time'=>array('gt',$start)))->field('member_id,member_truename,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;
			case 3:
				  $member_part  = $member ->where(array('member_areaid'=>$areaid,'member_level'=>2))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_areaid'=>$areaid,'member_level'=>1))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_areaid'=>$areaid,'member_time'=>array('gt',$start)))->field('member_id,member_truename,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;
			case 4:
				  $member_quxian= $member ->where(array('member_cityid'=>$cityid,'member_level'=>3))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的区代人数
				  $member_part  = $member ->where(array('member_cityid'=>$cityid,'member_level'=>2))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_cityid'=>$cityid,'member_level'=>1))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_cityid'=>$cityid,'member_time'=>array('gt',$start)))->field('member_id,member_truename,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;
			case 5:
				  $member_city  = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>4))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的市代人数 
    	          $member_quxian= $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>3))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的区代人数
				  $member_part  = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>2))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的端口人数
    	          $member_vip   = $member ->where(array('member_provinceid'=>$provinceid,'member_level'=>1))->field('member_id,member_truename,member_mobile,member_level')->count();//获取本省下的会员人数
    	          $member_new   = $member ->where(array('member_provinceid'=>$provinceid,'member_time'=>array('gt',$start)))->field('member_id,member_truename,member_mobile,member_level')->count();//获取今日新增会员人数
				  break;			
		}  

    	Tpl::output('show_page',$member->showpage());
    	Tpl::output('member_infos',$member_infos);
    	Tpl::output('member_new',$member_new);
        Tpl::output('member_city',$member_city);
        Tpl::output('member_quxian',$member_quxian);
        Tpl::output('member_part',$member_part);
        Tpl::output('member_vip',$member_vip);
        Tpl::showpage('member_myteam');
    }
    public  function teamOp(){
		$member_id['member_id'] = $_SESSION['member_id'];//会员ID
		$field = "member_truename,member_id,member_pid,member_level,parent2";
		$model = model('member');
		$member_info = $model->getMemberInfo($member_id);//会员信息 getMemberList
		$member_pid = $model->where("member_pid={$member_info['member_id']}  or parent2={$member_info['member_id']}")->field($field)->page(30)->select();//下级信息
		$pid = $model->where(array('member_pid'=>$member_info['member_id']))->field($field)->page(30)->select();
			foreach ($pid as $key => $value) {
				if($value){
				 	$p[] = $model->where(array('member_pid'=>$value['member_id']))->field($field)->select();
				}else{
					continue;
				}

			}
		Tpl::output('pid',$pid);
		Tpl::output('parent2',$p);

		Tpl::output('show_page',$model->showpage());
		Tpl::showpage('member_team');
	}
//注册会员
	public function registerOp(){
		$member=Model('member');
		$pd_log=Model('pd_log');
		$member_info=$member->where(array('member_id'=>$_SESSION['member_id']))->find();
		if($member_info['member_points']<5000 && empty($member_info['split_id'])){
			showMessage('您充值云豆不足5000，暂时无法注册新会员！');
		}else{
			if($_POST){
				//检测账号是否存在
				$where['member_name']=$_POST['member_name'];
				$where['member_mobile']=$_POST['member_name'];
				$where['_op']='or';
				$member_check = $member->where($where)->find();
				if($_POST['member_name']==''){
					showMessage('手机号码不能为空，请重新输入!!');
					exit;
				}
				if($member_check){
					showMessage('该账户已存在，请重新输入!!');
					exit;
				}

				if($_POST['member_passwd']=='' || $_POST['member_paypwd']==''){
					showMessage('密码不能为空，请重新输入!!');
					exit;
				}
				$register_info = array();
		        $register_info['username'] = $_POST['member_name'];
		        $register_info['password'] = $_POST['member_passwd'];
		        $register_info['password_confirm'] = $_POST['member_passwd'];
		        $register_info['member_pid'] = $_SESSION['member_id'];
		        $register_info['member_mobile'] = $_POST['member_name'];
				$register_info['member_paypwd']=$_POST['member_paypwd'];
				if(empty($member_info['split_id'])){
					$register_info['register']='1';
					$data['member_points']=array('exp','member_points-5000');
					$update=$member->where(array('member_id'=>$_SESSION['member_id']))->update($data);
				}else{
					$register_info['register']='2';
				}	
				$member_param = $member->register($register_info);
				// $data['member_points']=array('exp','member_points-5000');
				// $update=$member->where(array('member_id'=>$_SESSION['member_id']))->update($data);
				if(empty($member_info['split_id'])){
					$pd['lg_member_id']=$_SESSION['member_id'];
					$pd['lg_member_name']=$_SESSION['member_name'];
					$pd['lg_type']='activation';
					$pd['lg_av_amount']='5000';
					$pd['lg_admin_name']=$_POST['member_mobile'];
					$pd['lg_add_time']=time();
					$pd['lg_desc']='激活会员使用5000云豆';
				}else{
					$pd['lg_member_id']=$_SESSION['member_id'];
					$pd['lg_member_name']=$_SESSION['member_name'];
					$pd['lg_admin_name']=$_POST['member_mobile'];
					$pd['lg_type']='split';
					$pd['lg_av_amount']='0';
					$pd['lg_add_time']=time();
					$pd['lg_desc']='注册激活会员';
				}
				$insert=$pd_log->insert($pd);
				if($update && $member_param){
					showMessage('该账号注册成功!用户名：'.$_SESSION['member_name'].'','','','',5000);
				}

			}else{
			Tpl::showpage('member_register');	
			}
		}
		
	}
	//检测会员名称和手机号码
	public function checkOp(){
		$member=Model('member');
		$where['member_name']=$_POST['member'];
		$where['member_mobile']=$_POST['member'];
		$where['_op']='or';
		$member_info=$member->where($where)->find();
		if(!empty($member_info)){
			echo '1';
		}
		// print_r($where);
	}
	//云豆转账
	public function accountsOp(){
		Tpl::showpage('member_account');
	}
	public function transferOp(){
		$member=Model('member');
		$points_log=Model('points_log');
		$member_name=$_POST['memberid'];
		$points=$_POST['money'];
		$pwd=$_POST['pwd'];
		//查询该会员id是否是该伞下会员
		$under_info=$member->where(array('member_name'=>$member_name))->find();
		if(!$under_info){
			 echo '4';exit();
		}
		if($under_info['split_id']!=$_SESSION['member_id']){
			echo '7';
			exit;
		}
		if($_POST['type']=='1'){
			$where['member_id']=$_SESSION['member_id'];
		}else{
			$where['member_name']=$member_name;
		}
		$member_info=$member->where($where)->find();
		if($member_info['member_points']<$points){
			echo '2';
			exit;
		}
		
		if($member_info['member_paypwd'] != md5($pwd)){
            echo '3';exit;
        }
        $points_info=$points_log->where(array('pl_membername'=>$member_name))->sum('pl_points');
        // var_dump($points_info);
        if($points_info+$points>100000 && $_POST['type']=='1'){
        	echo '6';exit;
        }
        if($_POST['type']=='1'){
	        $member->where(array('member_id'=>$_SESSION['member_id']))->setDec('member_points',$points);
	        $member->where(array('member_id'=>$member_name))->setInc('member_points',$points);
	        $a=$points_log->insert(array('pl_memberid'=>$under_info['member_id'],'pl_membername'=>$under_info['member_name'],
	                                 'pl_points'=>$points,'pl_addtime'=>time(),'pl_desc'=>'id号为'.$_SESSION['member_id'].'会员给您转云豆','pl_stage'=>'transfer'));
	        $b=$points_log->insert(array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],
	                                 'pl_points'=>"-$points",'pl_addtime'=>time(),'pl_desc'=>'给手机号为'.$member_name.'的会员转云豆','pl_stage'=>'transfer'));
	        if($a && $b){           
	            echo '1';
	        }else{echo '0';}
        }else{
        	$member->where(array('member_id'=>$_SESSION['member_id']))->setInc('member_points',$points);
	        $member->where(array('member_id'=>$member_name))->setDec('member_points',$points);
	        $a=$points->insert(array('pl_memberid'=>$under_info['member_id'],'pl_membername'=>$under_info['member_name'],
	                                 'type'=>'fromfrend','pl_points'=>"-$points",'pl_addtime'=>time(),'pl_desc'=>'id号为'.$_SESSION['member_id'].'会员扣除您的云豆','pl_stage'=>'transfer'));
	        $b=$points->insert(array('pl_memberid'=>$_SESSION['member_id'],'pl_membername'=>$_SESSION['member_name'],
	                                 'type'=>'tofrend','pl_points'=>$points,'pl_addtime'=>time(),'pl_desc'=>'扣除id号为'.$member_id.'的会员云豆','pl_stage'=>'transfer'));	        if($a && $b){           
	            echo '1';
	        }else{echo '0';}
        }
        
	}
	//查看下级代理收益
	public function profitOp(){
		$member_id=$_GET['member_id'];
		// $member=Model('member');
		$statistics=Model('statistics');
		//获取代理人数和收益
		$where['lg_member_id']=$member_id;
		$where['lg_type']='profit';
		$pd_info=$statistics->where($where)->select();
		Tpl::output('show_page',$statistics->showpage());
		Tpl::output('pd_info',$pd_info);
		Tpl::showpage('profit');
	}
		/*
	ID查询
	 */
	public function useridOp(){
		//实例化
		$member=Model('member');
		//判断是否POST过来的值
		if($_POST){
			if(!empty($_POST) || !isset($_POST) || is_numeric($_POST)){
                   $model = model('member');
                   $area = model('area');
                   $member_info =  $model->getMemberInfo(array("member_id"=>$_POST['member_id']));
                   $info['member_id'] = $member_info['member_id'];
                   if($member_info['member_level'] == 0){
                        $info['member_level'] = "未激活";
                   }
                   if($member_info['member_level'] == 1){
                        $info['member_level'] = "会员";
                   }
                   if($member_info['member_level'] == 2){
                        $info['member_level'] = "端口";
                   }
                   if($member_info['member_level'] == 3){
                        $info['member_level'] = "区县代理";
                   }
                   if($member_info['member_level'] == 4){
                        $info['member_level'] = "市代理";
                   }
                   if($member_info['member_level'] == 5){
                        $info['member_level'] = "省代理";
                   }
                   $area_info = $area->getAreas();
                   foreach ($area_info as $key => $value) {
                        foreach ($value as $ky =>$v) {
                            //获取省级名字
                            if($ky==$member_info['member_provinceid'] && $info['member_provinceid'] =="" && is_array($v)){
                                if(!is_array($v)){$info['member_provinceid'] = $v;
                                    }else{
                                        $info['member_provinceid'] = "";
                                    }
                                

                            }
                            if($ky==$member_info['member_cityid'] && $info['member_cityid'] ==""){
                                if(!is_array($v)){$info['member_cityid'] = $v;
                                    }else{
                                        $info['member_cityid'] = "";
                                    }

                            }
                            if($ky==$member_info['member_areaid'] && $info['member_areaid'] ==""){
                               if(!is_array($v)){$info['member_areaid'] = $v;
                                    }else{
                                        $info['member_areaid'] = "";
                                    }
                            }
                        }
                   }
                   
                   echo  $member_info['member_id'].'+'. $info['member_level']."+".$info['member_provinceid']."+".$info['member_cityid']."+".$info['member_areaid'];
                }else{
                return array('error'=>'您提交的不少数字类型的');
                }
            }else{
            	Tpl::showpage('member_userid');
            }

		}
		 public function registerrOp(){
		
		$member=Model('member');
		$pd_log=Model('pd_log');
		$member_info=$member->where(array('member_id'=>$_SESSION['member_id']))->find();
		if($member_info['distributor_predeposit']<500){
			showMessage('您分销余额不足500，暂时无法注册新会员！');
			exit();
		}else{
			if($_POST){
				//检测账号是否存在
				$where['member_name']=$_POST['member_name'];
				$where['member_mobile']=$_POST['member_name'];
				$where['_op']='or';
				$member_check = $member->where($where)->find();
				if($_POST['member_name']==''){
					showMessage('手机号码不能为空，请重新输入!!');
					exit;
				}
				if($member_check){
					showMessage('该账户已存在，请重新输入!!');
					exit;
				}

				if($_POST['member_passwd']=='' || $_POST['member_paypwd']==''){
					showMessage('密码不能为空，请重新输入!!');
					exit;
				}
				$register_info = array();
		        $register_info['username'] = $_POST['member_name'];
		        $register_info['password'] = $_POST['member_passwd'];
		        $register_info['password_confirm'] = $_POST['member_passwd'];
		        $register_info['member_pid'] = $_SESSION['member_id'];
		        $register_info['member_mobile'] = $_POST['member_name'];
				$register_info['member_paypwd']=$_POST['member_paypwd'];
				$register_info['register']='1';
				$member_param = $member->register($register_info,1);
				
				$data['distributor_predeposit']=array('exp','distributor_predeposit-500');
				$update=$member->where(array('member_id'=>$member_info['member_id']))->update($data);
				$pd['lg_member_id']=$_SESSION['member_id'];
				$pd['lg_member_name']=$_SESSION['member_name'];
				$pd['lg_type']='distribution';
				$pd['lg_av_amount']='-500';
				$pd['lg_admin_name']=$_POST['member_name'];
				$pd['lg_add_time']=time();
				$pd['lg_desc']='激活会员使用500分销奖金';
				$insert=$pd_log->insert($pd);
				if(!empty($member_info['member_id'])){  //给自己添加分销奖金
                  $data1 = array();
                  $data1['distributor_predeposit'] = array('exp','distributor_predeposit+100');
                  $updatee1 = $member->where(array('member_id'=>$member_info['member_id']))->update($data1);
           if($updatee1){
           	$pd2['lg_member_id']= $member_info['member_id'];
			$pd2['lg_member_name']= $member_info['member_name'];
			$pd2['lg_type']='distribution';
			$pd2['lg_av_amount']='100';
			$pd2['lg_admin_name']=$_POST['member_name'];
			$pd2['lg_add_time']=time();
			$pd2['lg_desc']='注册会员'.$_POST['member_name'].'获得分销奖金';
			$insert2=$pd_log->insert($pd2);
           }
		}
	
		if(!empty($member_info['member_pid'])){ //给上级添加分销奖金
		$member_pid = $member->where(array('member_id'=>$member_info['member_pid']))->find(); 
	        $data=array();              //更新上级会员500元金额30%
			$data['distributor_predeposit']=array('exp','distributor_predeposit+150');
			$updatee=$member->where(array('member_id'=> $member_pid['member_id']))->update($data);   //更新上级ID
			if($updatee){
            $pd3['lg_member_id']= $member_pid['member_id'];
			$pd3['lg_member_name']= $member_pid['member_name'];
			$pd3['lg_type']='distribution';
			$pd3['lg_av_amount']='150';
			$pd3['lg_admin_name']=$_POST['member_name'];
			$pd3['lg_add_time']=time();
			$pd3['lg_desc']='注册会员'.$_POST['member_name'].'获得分销奖金';
			$insert3=$pd_log->insert($pd3);
           }
		
		}
		if($member_param){
			     //赠送500积分
			             $condition = array();
			             $condition['member_name']=$register_info['username'];
			             $member_nid = $member->getMemberInfo($condition);
                        $seve=$member->where(array('member_id'=>$member_nid['member_id']))->find();
                        $data_point=array('lg_member_id'=>$seve['member_id'],'lg_member_name'=>$seve['member_name'],'lg_type'=>'complimentary','lg_av_amount'=>'500','lg_add_time'=>time(),'lg_desc'=>'会员激活赠送500积分');
                        $update_point=Model()->table('pd_log')->insert($data_point);
			     chief_card($register_info['username']);  //代理分润
		        }  
				if($update && $member_param){
					showMessage('该账号注册成功!');
				}
				
		
			}else{
			Tpl::showpage('member_registerr');	
			}
		}
		
	}
	

}
