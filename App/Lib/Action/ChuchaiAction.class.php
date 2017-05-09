<?php 
// 
class ChuchaiAction extends Action {
    
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','tj','add','edit','accept','delete')
		);
		B('Authenticate', $action);
		$this->assign("user_id",session("user_id"));
	}
	
	public function view(){
		//session('role_id')//会员组
		$this->index();
	}
	
	public function index(){
		$model = new Model();
		
		header("Content-type: text/html; charset=utf-8");
		
		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
	
		//设置下拉列表的默认选中
		$defaul_year = date("Y",mktime());$this->assign("defaul_year",$defaul_year);
		$defaul_month = date("m",mktime());
//		if (!$_GET['the_month']){$_GET['the_month']=$defaul_month;}
		
		//用户下拉列表
		if (session("user_id") == 1 || session('position_id') == 2){
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
		} else {
			//普通用户，只显示自己
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id= ".session('user_id');
		}
		$user_datas = $model->query($u_str);
		foreach($user_datas as $k=>$v){
			if (!$v['user_id']){
				$user_datas[$k]['user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
			}	
		}
		$this->assign("users_list",$user_datas);//用户下拉列表

		/*
		$type_list = array(array("type"=>1,"name"=>"请假"),array("type"=>2,"name"=>"加班"),array("type"=>3,"name"=>"出差"));
		$this->assign("type_list",$type_list);//记录类型
		*/

		$show_type_list = array(array("type"=>0,"name"=>"列表"),array("type"=>1,"name"=>"统计"));
		$this->assign("show_type_list",$show_type_list);//记录类型
		
		$show_type = intval($_GET['show_type']);
		$this->assign("show_type",$show_type);
		
		if (session("user_id") == 1 || session('position_id') == 2){		
			$user_id = intval($_GET['user_id']);
		}else{
			$user_id = session("user_id");
		}
		
		$the_year = intval($_GET['the_year']);//if(!$the_year){$the_year = $defaul_year;}
		if ($the_year){
			$map['jl_kaoqin_chucai.the_year'] = $the_year;
		}else{
			if(!$the_year){
			//	$the_year = $defaul_year;
			}	
		}
		
		
		$the_month = intval($_GET['the_month']);
		if ($the_month){
			if(!$the_year){
				$the_year = $defaul_year;
			}
			if ( $the_month < 10 ){
				$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
			}
			//if($the_month == '00'){$the_month = $defaul_month;}
			$the_month = $the_year.'-'.$the_month;
		}
		
		
		
//			if ($the_month < 10){
//				$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
//			}
//			if($the_month == '00'){$the_month = $defaul_month;}
		$type = intval($_GET['type']);
		
//		$the_month = $the_year.'-'.$the_month;
		if ($user_id){
			$this->assign("user_id",$user_id);
		}
		$this->assign("the_year",$the_year);
		$this->assign("the_month",intval($_GET['the_month']));
		$this->assign("type",intval($_GET['type']));
		
		$params = array("the_year=".$the_year,"the_month=".intval($_GET['the_month']),"user_id=".$user_id);//分页传参

		
		if ($user_id){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin_chucai.user_id'] = $user_id;
		}
		if ($the_year && $the_month){
			$map['jl_kaoqin_chucai.the_month'] = $the_month;
		}
		if ($type){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin_chucai.type'] = $type;
		}
			
		$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
		//$qstr = "select A.*,B.user_id FROM jl_kaoqin AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.kqj_user_id = B.kqj_user_id ".$where." limit ".$Page->firstRow.",".$Page->listRows;
		
		
          // 加载数据分页类
        import("@.ORG.Page");
        
        // 数据分页
        $totals = $model->table("jl_kaoqin_chucai")->where($map)->count();
		
        $Page = new Page($totals, 10);
        $show = $Page->show();
        $this->assign('page', $show);	

		
		$order = " id DESC";
		$datas = $model->table("jl_kaoqin_chucai")->where($map)->join(' LEFT JOIN jl_user ON jl_user.user_id = jl_kaoqin_chucai.user_id')->limit($Page->firstRow.','.$Page->listRows)->order($order)->select();
//		echo $model->getlastsql();
	//	var_dump($datas);

		//重新整理
		foreach($datas as $k=>$v){
			$datas[$k]['hour_num'] = round((strtotime($v['endtime'])- strtotime($v['starttime']))/3600,1);
			
		}

		$Page->parameter = implode('&', $params);
		$this->assign('page',$Page->show());
		$this->assign("datas",$datas);
		$this->display("index");

	}
	
	public function add(){
		if (IS_POST) {
			$kq_model = new Model();
	//		$kq_config_model = new Model();
			
			//读取考勤配置
	//		$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
	//		$config_datas = $kq_config[0];
	//		var_dump($config_datas);
	//		$starttime = $config_datas['starttime'];//上班时间
	//		$endtime = $config_datas['endtime'];//下班时间
			
			$starttime = trim($_POST['starttime']);
			$endtime = trim($_POST['endtime']);
			
			$tm = trim($_POST['starttime']);
			$datas = array();
			
			$type = intval($_POST['type']);
			
			$chuchai_days = (float)$_POST['chuchai_days'];
			
			
			//var_dump(session("position_id"));//岗位数据表=jl_position,会计=2,软件工程师=5,美工=6,网络工程师=4,销售经理=3,CEO=1
			//session('role_id')，???
			
			//如果是超级管理员，或者是会计
			if (session("user_id") == 1 || session('position_id') == 2){
				$datas['user_id'] = intval($_POST['user_id']);
			} else {
				$datas['user_id'] = session("user_id");
			}
			
			
			$datas['starttime'] = trim($_POST['starttime']);//开始时间
			$datas['endtime'] = trim($_POST['endtime']);//结束时间
			$datas['hour_num'] = round((strtotime($endtime) - strtotime($starttime))/3600,2);
			$datas['chuchai_days'] = $chuchai_days;
			$datas['the_date'] = date("Y-m-d",strtotime($tm));//年月日
			$datas['the_year'] = date("Y",strtotime($tm));//年
			$datas['the_month'] = date("Y-m",strtotime($tm));//年月
			$datas['the_day'] = date("d",strtotime($tm));//日
			$datas['the_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($tm));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['type'] = $type;
			$datas['title'] = '出差';
			$datas['content'] = trim($_POST['content']);
			if (session("user_id") == 1 || session('position_id') == 2){	
				$datas['checked'] = 1;//管理员添加的直接设为1
			}else{
				$datas['checked'] = 0;//管理员审核后设为1
			}
		//	var_dump($datas);exit;
			$result = $kq_model->table("jl_kaoqin_chucai")->data($datas)->add();
			//var_dump($result);
			alert('success', '新增请假记录成功',U('Chuchai/index','content='.$_GET['content']));
			
        } else {
			
            $model = new Model();
			//用户下拉列表
			if (session("user_id") == 1 || session('position_id') == 2){
				$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			} else {
				//普通用户，只显示自己
				$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id= ".session('user_id');
			}
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
				if (!$v['kqj_user_id']){
					$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
				}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列表
			
			
			$datas = array();
			$datas['type']=0;
            $this->assign("datas",$datas);
            $this->display("edit");
        }
	}	
	
	
	//修改记录
	public function edit(){
		if (IS_POST) {
			$kq_model = new Model();
			
			$starttime = trim($_POST['starttime']);
			$endtime = trim($_POST['endtime']);
			
			$chuchai_days = (float)$_POST['chuchai_days'];
			$type = intval($_POST['type']);
			
			$tm = trim($_POST['starttime']);
			$datas = array();
			$datas['id'] = intval($_POST['id']);
			//如果是超级管理员，或者是会计
			if (session("user_id") == 1 || session('position_id') == 2){
				$datas['user_id'] = intval($_POST['user_id']);
			} else {
				$datas['user_id'] = session("user_id");
			}
			$datas['starttime'] = trim($_POST['starttime']);//开始时间
			$datas['endtime'] = trim($_POST['endtime']);//结束时间
			$datas['chuchai_days'] = $chuchai_days;
			$datas['hour_num'] = round((strtotime($endtime) - strtotime($starttime))/3600,2);
			$datas['the_date'] = date("Y-m-d",strtotime($tm));//年月日
			$datas['the_year'] = date("Y",strtotime($tm));//年
			$datas['the_month'] = date("Y-m",strtotime($tm));//年月
			$datas['the_day'] = date("d",strtotime($tm));//日
			$datas['the_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($tm));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['title'] = '出差';
			$datas['type'] = $type;
			$datas['content'] = trim($_POST['content']);	
			$datas['checked'] = 0;//管理员审核后设为1
		//	var_dump($datas);exit;
			$result = $kq_model->table("jl_kaoqin_chucai")->save($datas);
			//var_dump($result);
			alert('success', '修改记录成功',U('Chuchai/index','content='.$_GET['content']));
			
        } else {
            $model = new Model();
			//用户下拉列表
			if (session("user_id") == 1 || session('position_id') == 2){
				$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			} else {
				//普通用户，只显示自己
				$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id= ".session('user_id');
			}
			
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
				if (!$v['kqj_user_id']){
					$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
				}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列表
			
			$map = array();
			$map['id'] = intval($_GET['id']);
//			$map['user_id'] = session('user_id');
			$datas = $model->table("jl_kaoqin_chucai")->where($map)->find();
			
			if ($datas['checked']){
				echo "已审核，不允许编缉";				
			}


            $this->assign("datas",$datas);
            $this->display("edit");
        }
	}	
	
	//删除
	public function delete(){
		$id = intval($_GET['id']);
		
		if (!$id){
			alert('error', '参数错误',U('Chuchai/index','content='.$_GET['content']));	
		}
		if (session("user_id") == 1 || session('position_id') == 2){
			//管理员，可删除任意记录
			$model = new Model();
			$model->table("jl_kaoqin_chucai")->where("id=".$id)->delete();
			alert('success', '操作成功',U('Chuchai/index','content='.$_GET['content']));
		} else {
			//普通用户，只可删除自己的
			$model = new Model();
			$model->table("jl_kaoqin_chucai")->where("id=".$id." and user_id=".session("user_id"))->delete();	
			alert('success', '操作成功',U('Chuchai/index','content='.$_GET['content']));		
		}
		exit;
	}
	
	//审核
	public function accept(){
		$id = intval($_GET['id']);
		if (session("user_id") == 1 || session('position_id') == 2){
			$model = new Model();
			$datas = $model->table("jl_kaoqin_chucai")->where("id=".$id)->find();
			if ($datas){
				$data_accept = !$datas['checked'];
			}
			
			$data = array();
			//$data['id'] = $id;
			$data['checked'] = $data_accept;
			$result = $model->table("jl_kaoqin_chucai")->where("id=".$id)->save($data);
			alert('success', '操作成功',U('Chuchai/index','content='.$_GET['content']));
		}
	}
		
	
	
	
	
}