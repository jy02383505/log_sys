<?php 
// 
class CountAction extends Action {
    
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','widget_edit','widget_delete','widget_add')
		);
		B('Authenticate', $action);
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
		if (!$_GET['the_month']){$_GET['the_month']=$defaul_month;}
		
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

		if (session("user_id") == 1 || session('position_id') == 2){		
			$user_id = intval($_GET['user_id']);
		}else{
			$user_id = session("user_id");
		}
		
		$the_year = intval($_GET['the_year']);if(!$the_year){$the_year = $defaul_year;}
		$the_month = intval($_GET['the_month']);
			if ($the_month < 10){
				$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
			}
			if($the_month == '00'){$the_month = $defaul_month;}
		$type = intval($_GET['type']);
		
		$the_month = $the_year.'-'.$the_month;
		if ($user_id){
			$this->assign("user_id",$user_id);
		}
		$this->assign("the_year",$the_year);
		$this->assign("the_month",intval($_GET['the_month']));

		$tj_type = trim($_REQUEST['tj_type']);
		if (empty($tj_type) || $tj_type==''){
			$tj_type = 'log';	
		}
		$this->assign("tj_type",$tj_type);
	
		$type_list = array(array("name"=>"日志","val"=>"log"),array("name"=>"考勤","val"=>"kaoqin"),array("name"=>"出差","val"=>"chuchai"),array("name"=>"加班","val"=>"jiaban"),array("name"=>"请假","val"=>"qinjia"));
		$this->assign("type_list",$type_list);
		
		
		switch ($tj_type) {
			case 'log':	//日志
				$this->tj_log();
				break;
			case 'kaoqin'://考勤
				$this->tj_kaoqin();
				break;
			case 'jiaban'://加班
				$this->tj_jiaban();
				break;
			case 'qinjia'://请假
				$this->tj_qinjia();
				break;
			case 'chuchai'://出差
				$this->tj_chuchai();
				break;
			default:
				$this->display("index");
		}
		
		
		
		
	}
	
	

	public function tj_log(){

		$this->display("count:count_log");
	}

	
	
	public function tj_kaoqin(){
	
		$this->display("count:count_kaoqin");
	}

	
	//加班
	public function tj_jiaban(){
		header("Content-type: text/html; charset=utf-8");
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
			if (!$v['user_id']){
				$user_datas[$k]['user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
			}	
		}
		$this->assign("users_list",$user_datas);//用户下拉列表
		
		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
		
//		var_dump(date("YY-m",'2016-05'));
		
		$the_year = intval($_REQUEST['the_year']);
		$the_month = intval($_REQUEST['the_month']);
		$user_id = intval($_REQUEST['user_id']);
		
		//构造：2016-05
		$the_year_month = $the_month;
		if ($the_year_month < 10){
			$the_year_month = '0'.$the_year_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
		}
		$the_year_month = $the_year.'-'.$the_year_month;
		
		//年和月必须指定
		if (!$the_year){
			//出错
		}
		if (!$the_month){
			//出错
		}
		if (!$kqj_user_id){
			//出错
		}

		$this->assign("the_year",$the_year);
		$this->assign("the_month",$the_month);
		$this->assign("user_id",$user_id);
			
		//按月
		/*			
		echo "<br>the_year=".$the_year;
		echo "<br>the_month=".$the_month;
		echo "<br>kqj_user_id=".$kqj_user_id;
		echo '<br>本月天数：'.date("t",strtotime("$the_year-$the_month"));
		*/
		//本月总天数
		//$days = date("t",strtotime("$the_year-$the_month"));
		
		if ($the_month < 10){
			$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
		}
		
		$out_datas = array();//输出结果
		$map_1024 = array();
		$map_1024['user_id'] = $user_id;
		$map_1024['the_month'] = $the_year_month;//存的是年-月
		$map_1024['checked'] = 1;
		$out_datas = $model->table("jl_kaoqin_jiaban")->where($map_1024)->order("the_day ASC")->select();
//		echo $model->getlastsql();
		//var_dump($out_datas);
		$this->assign("out_datas",$out_datas);
		

		//具体到一个人
		$out['user_id'] = $user_id;
		$result = $model->table("jl_user")->where("user_id=".$user_id)->field("name")->find();
		$tj['name'] = $result['name'];
		//请假
		$map = array();
		$map['user_id']=$user_id;
		$map['the_month'] = $the_year_month;//2016-05
		$map['checked'] = 1;

		$data = $model->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num');
		$tj['qj_this_month_hour_count'] = round($data,1);//单位：小时
		
		//加班
		$map = array();
		$map['user_id']=$user_id;
		$map['checked'] = 1;
		$data = $model->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num');
		$tj['jb_count'] = round($data,1);//单位：小时
		
		
		//出差
		$map = array();
		$map['user_id']=$user_id;
		$map['checked'] = 1;
		$data = $model->table("jl_kaoqin_chucai")->where($map)->sum('hour_num');
		$tj['cc_count'] = round($data,1);//单位：小时
		
		
		//年假剩余，每年5天年假
		//请假
		$map = array();
		$map['user_id']=$user_id;
		$map['the_year'] = $the_year;	
		$map['checked'] = 1;			
		$qj_the_year_hour_count = $model->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num');
		$tj['qj_the_year_hour_count'] = $qj_the_year_hour_count;//本年度请假时长，小时数
		$tj['nianjia_shenyu_hour'] = 40 - $qj_the_year_hour_count;//年假剩余，每年5天年假，减去本年度请假时长
	//	echo $model->getlastsql();
		//var_dump($tj);
		$this->assign("tj",$tj);
		//$this->display("record_count_one_user");

		//全年各月份统计
		$tj_every_month = array();
		for ($i=1;$i<13;$i++){
			
			$user_datas = $model->table("jl_kaoqin_system_userid")->where("userid=".$user_id)->find();
			$kqj_user_id = $user_datas['user_id'];
			
			//请假
			$map = array();
			$map['user_id']=$user_id;
			if ($i<10){
				$tmp = "0".$i;
			}else{
				$tmp = $i;	
			}
			$this_year_month = $the_year."-".$tmp;
			$map['the_month'] = $this_year_month;//2016-05
			$map['checked'] = 1;
			$data = $model->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num');//正常加班
			$tj_every_month[$i]['month_int'] = $i;
			$tj_every_month[$i]['this_month_jiaban_hour_count'] = round($data,1);//单位：小时
			
			$map1 = array();
			$map1['user_id']=$user_id;
			$map1['sign_month'] = $this_year_month;//2016-05
			$data_2 = $model->table("jl_kaoqin")->where($map1)->sum('jiaban_hours');//每天主动加班
			$tj_every_month[$i]['this_month_zhudong_jiaban_hour_count'] = round($data_2,1);//单位：小时
			//echo $model->getlastsql();
			//
			
		}
		
		//本年度本人主动加班总时长
		$map1 = array();
		$map1['user_id']=$user_id;
		$map1['sign_year'] = $the_year;//2016-05
		$data_3 = $model->table("jl_kaoqin")->where($map1)->sum('jiaban_hours');//主动加班
		$tj_the_year_zhudong_jaban = round($data_3,1);//单位：小时
		
		$this->assign("tj_the_year_zhudong_jaban",$tj_the_year_zhudong_jaban);

		$this->assign("tj_every_month",$tj_every_month);		
		$this->display("count_jiaban");
	}
	
	//出差统计
	public function tj_chuchai(){
		header("Content-type: text/html; charset=utf-8");
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
			if (!$v['user_id']){
				$user_datas[$k]['user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
			}	
		}
		$this->assign("users_list",$user_datas);//用户下拉列表
		
		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
		
//		var_dump(date("YY-m",'2016-05'));
		
		$the_year = intval($_REQUEST['the_year']);
		$the_month = intval($_REQUEST['the_month']);
		$user_id = intval($_REQUEST['user_id']);
		
		//构造：2016-05
		$the_year_month = $the_month;
		if ($the_year_month < 10){
			$the_year_month = '0'.$the_year_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
		}
		$the_year_month = $the_year.'-'.$the_year_month;
		

		$this->assign("the_year",$the_year);
		$this->assign("the_month",$the_month);
		$this->assign("user_id",$user_id);
			
		//按月
		/*			
		echo "<br>the_year=".$the_year;
		echo "<br>the_month=".$the_month;
		echo "<br>kqj_user_id=".$kqj_user_id;
		echo '<br>本月天数：'.date("t",strtotime("$the_year-$the_month"));
		*/
		//本月总天数
		//$days = date("t",strtotime("$the_year-$the_month"));
		
		if ($the_month < 10){
			$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
		}
		
		$out_datas = array();//输出结果
		$map_1024 = array();
		$map_1024['user_id'] = $user_id;
		$map_1024['the_month'] = $the_year_month;//存的是年-月
		$map_1024['checked'] = 1;
		$out_datas = $model->table("jl_kaoqin_jiaban")->where($map_1024)->order("the_day ASC")->select();
//		echo $model->getlastsql();
		//
		$this->assign("out_datas",$out_datas);
		

		//所有用户的全年各月
		
		$all_user_count = array();
		foreach($user_datas as $k=>$v){



			
			
		}
//var_dump($all_user_count);

		//具体到一个人
		$out['user_id'] = $user_id;
		$result = $model->table("jl_user")->where("user_id=".$user_id)->field("name")->find();
		$tj['name'] = $result['name'];
		
		//出差
		$map = array();
		$map['user_id']=$user_id;
		$map['checked'] = 1;
		$data = $model->table("jl_kaoqin_chucai")->where($map)->sum('hour_num');
		$tj['cc_count'] = round($data,1);//单位：小时
		


		//全年各月份统计
		$tj_every_month = array();
		for ($i=1;$i<13;$i++){
			
			$user_datas = $model->table("jl_kaoqin_system_userid")->where("userid=".$user_id)->find();
			$kqj_user_id = $user_datas['user_id'];
			
			//请假
			$map = array();
			$map['user_id']=$user_id;
			if ($i<10){
				$tmp = "0".$i;
			}else{
				$tmp = $i;	
			}
			$this_year_month = $the_year."-".$tmp;
			$map['the_month'] = $this_year_month;//2016-05
			$map['checked'] = 1;
			$data = $model->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num');//正常加班
			$tj_every_month[$i]['month_int'] = $i;
			$tj_every_month[$i]['this_month_jiaban_hour_count'] = round($data,1);//单位：小时
			
			$map1 = array();
			$map1['user_id']=$user_id;
			$map1['sign_month'] = $this_year_month;//2016-05
			$data_2 = $model->table("jl_kaoqin")->where($map1)->sum('jiaban_hours');//每天主动加班
			$tj_every_month[$i]['this_month_zhudong_jiaban_hour_count'] = round($data_2,1);//单位：小时
			//echo $model->getlastsql();
			//
			
		}
		
		//本年度本人主动加班总时长
		$map1 = array();
		$map1['user_id']=$user_id;
		$map1['sign_year'] = $the_year;//2016-05
		$data_3 = $model->table("jl_kaoqin")->where($map1)->sum('jiaban_hours');//主动加班
		$tj_the_year_zhudong_jaban = round($data_3,1);//单位：小时
		
		$this->assign("tj_the_year_zhudong_jaban",$tj_the_year_zhudong_jaban);

		$this->assign("tj_every_month",$tj_every_month);		
		$this->display("count_chuchai");
	}	
	
	
	
}