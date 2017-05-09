<?php 
// 
class JiabanAction extends Action {
    
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


		
		$user_id = intval($_GET['user_id']);
		$the_year = intval($_GET['the_year']);//if(!$the_year){$the_year = $defaul_year;}
		if ($the_year){
			$map['jl_kaoqin_jiaban.the_year'] = $the_year;
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
			$map['jl_kaoqin_jiaban.user_id'] = $user_id;
		}
		if ($the_year && $the_month){
			$map['jl_kaoqin_jiaban.the_month'] = $the_month;
		}
		if ($type){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin_jiaban.type'] = $type;
		}
			
		$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
		//$qstr = "select A.*,B.user_id FROM jl_kaoqin AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.kqj_user_id = B.kqj_user_id ".$where." limit ".$Page->firstRow.",".$Page->listRows;
		
		if (session("user_id") == 1 || session('position_id') == 2){
			if (intval($_REQUEST['user_id'])){
				$map['jl_kaoqin_jiaban.user_id'] = intval($_REQUEST['user_id']);
			}
		}else{
			$map['jl_kaoqin_jiaban.user_id']=session("user_id");
		}
		
		
          // 加载数据分页类
        import("@.ORG.Page");
        
        // 数据分页
        $totals = $model->table("jl_kaoqin_jiaban")->where($map)->count();
		
        $Page = new Page($totals, 10);
        $show = $Page->show();
        $this->assign('page', $show);	

		
		$order = " id DESC";

		$datas = $model->table("jl_kaoqin_jiaban")->where($map)->join(' LEFT JOIN jl_user ON jl_user.user_id = jl_kaoqin_jiaban.user_id')->limit($Page->firstRow.','.$Page->listRows)->order($order)->select();
//		echo $model->getlastsql();
//		var_dump($datas);


/*
		//统计
		if ($show_type){
			$out = array();
			//1请假,2加班,3出差
			//全年请假总天数（年假5天）
			if (!$user_id){
				//全部人员
				foreach($user_datas as $k=>$v){
					$out[$k]['user_id'] = $v['user_id'];
					//请假
					$map = array();
					$map['user_id']=$v['user_id'];
					$map['the_month'] = $the_month;
					$map['type']=1;

					$qj_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
					$out[$k]['qj_count'] = intval($qj_count);//单位：小时
					
					//加班
					$map = array();
					$map['user_id']=$v['user_id'];
					$map['the_month'] = $the_month;
					$map['type']=2;
					
					$jb_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
					$out[$k]['jb_count'] = $jb_count;
					
					
					//出差
					$map = array();
					$map['user_id']=$v['user_id'];
					$map['the_month'] = $the_month;
					$map['type']=3;
					
					$cc_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
					$out[$k]['cc_count'] = $cc_count;
					
					//数组的每一行分别为：用户编号，某月请假小时数，加班小时数，出差小时数
					
				}
//				var_dump($out);
				$this->display("record_count_all_user");
				exit;
				
			}else{

				exit;
			}
			
		}
*/

		$Page->parameter = implode('&', $params);
		$this->assign('page',$Page->show());
		$this->assign("datas",$datas);
		$this->display("index");
	}
	
	public function tj(){
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
			$data = $model->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num');
			$tj_every_month[$i]['month_int'] = $i;
			$tj_every_month[$i]['this_month_qj_hour_count'] = round($data,1);//单位：小时
			//echo $model->getlastsql();
		//	var_dump($tj_every_month);				
		}

		$this->assign("tj_every_month",$tj_every_month);		
		$this->display("tj");
	}
	
	//增加
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
	
			/*
			$starttime = trim($_POST['starttime']);
			$endtime = trim($_POST['endtime']);
			
			if ($endtime < $starttime ){
				alert('error', "结束时间小于开始时间", U('Jiaban/index'));
			}
			
			$tm = trim($_POST['starttime']);
			*/
			$the_date = trim($_POST['the_date']);
			$the_date = date("Y-m-d",strtotime($the_date));//保证格式统一为2016-01-05
			
			$shijianduan_ch = trim($_POST['shijianduan_ch']);//时间段，上午下午或一天
			switch ($shijianduan_ch){
				case "上午":
					$shijianduan_ch = "上午";
					$hour_num = 4;//小时
					break;	
				case "下午":
					$shijianduan_ch = "下午";
					$hour_num = 4;//小时
					break;
				case "一天":
					$shijianduan_ch = "一天";
					$hour_num = 8;//小时
					break;
				default:
					$shijianduan_ch = "一天";
					$hour_num = 8;//小时
			}		
			
			$datas = array();
			
			
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
			

			$jiaban_hours = (strtotime($endtime) - strtotime($starttime)) / 3600;
			$jiaban_hours = round($jiaban_hours,1);
			$f = end(explode('.',$jiaban_hours));//取小数部分
			$the_int = reset(explode('.',$jiaban_hours));
			if ($f>5){
				$jiaban_hours = $the_int + 0.5;
			} else {
				$jiaban_hours = $the_int;
			}
			
			$datas['hour_num'] = $hour_num;//$jiaban_hours;
			$datas['the_date'] = $the_date;//date("Y-m-d",strtotime($tm));//年月日
			$datas['the_year'] = date("Y",strtotime($the_date));//年
			$datas['the_month'] = date("Y-m",strtotime($the_date));//年月
			$datas['the_day'] = date("d",strtotime($the_date));//日
			$datas['the_time'] = date("H:i:s",strtotime($the_date));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			$datas['shijianduan_ch'] = trim($_POST['shijianduan_ch']);//上午、下午、一天
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($the_date));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['title'] = '加班';
			$datas['content'] = trim($_POST['content']);	
			$datas['checked'] = 0;//管理员审核后设为1
		//	var_dump($datas);exit;
			$result = $kq_model->table("jl_kaoqin_jiaban")->data($datas)->add();
			//var_dump($result);
			alert('success', '新增请假记录成功',U('Jiaban/index','content='.$_GET['content']));
			
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
	
	//增加请假记录
	public function edit(){
		if (IS_POST) {
			$kq_model = new Model();
	//		$kq_config_model = new Model();
			
			//读取考勤配置
	//		$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
	//		$config_datas = $kq_config[0];
	//		var_dump($config_datas);
	//		$starttime = $config_datas['starttime'];//上班时间
	//		$endtime = $config_datas['endtime'];//下班时间
			/*
			$starttime = trim($_POST['starttime']);
			$endtime = trim($_POST['endtime']);
			
			$tm = trim($_POST['starttime']);
			*/
			
			$the_date = trim($_POST['the_date']);
			$the_date = date("Y-m-d",strtotime($the_date));//保证格式统一为2016-01-05
			
			$shijianduan_ch = trim($_POST['shijianduan_ch']);//时间段，上午下午或一天
			switch ($shijianduan_ch){
				case "上午":
					$shijianduan_ch = "上午";
					$hour_num = 4;//小时
					break;	
				case "下午":
					$shijianduan_ch = "下午";
					$hour_num = 4;//小时
					break;
				case "一天":
					$shijianduan_ch = "一天";
					$hour_num = 8;//小时
					break;
				default:
					$shijianduan_ch = "一天";
					$hour_num = 8;//小时
			}
			
			
			
			$datas = array();
			$datas['id'] = intval($_POST['id']);
			//如果是超级管理员，或者是会计
			if (session("user_id") == 1 || session('position_id') == 2){
				$datas['user_id'] = intval($_POST['user_id']);
			} else {
				$datas['user_id'] = session("user_id");
			}
	//		$datas['starttime'] = trim($_POST['starttime']);//开始时间
	//		$datas['endtime'] = trim($_POST['endtime']);//结束时间
			$datas['hour_num'] = $hour_num;//round((strtotime($endtime) - strtotime($starttime))/3600,2);
			$datas['the_date'] = $the_date;//年月日
			$datas['the_year'] = date("Y",strtotime($the_date));//年
			$datas['the_month'] = date("Y-m",strtotime($the_date));//年月
			$datas['the_day'] = date("d",strtotime($the_date));//日
			$datas['the_time'] = date("H:i:s",strtotime($the_date));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			$datas['shijianduan_ch'] = trim($_POST['shijianduan_ch']);//上午、下午、一天
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($the_date));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['title'] = '请假';
			$datas['content'] = trim($_POST['content']);	
			$datas['checked'] = 0;//管理员审核后设为1
		//	var_dump($datas);exit;
			$result = $kq_model->table("jl_kaoqin_jiaban")->save($datas);
			//var_dump($result);
			alert('success', '新增请假记录成功',U('Jiaban/index','content='.$_GET['content']));
			
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
			$datas = $model->table("jl_kaoqin_jiaban")->where($map)->find();
			

            $this->assign("datas",$datas);
            $this->display("edit");
        }
	}	
	
	//删除
	public function delete(){
		$id = intval($_GET['id']);
		
		if (!$id){
			alert('error', '参数错误',U('Jiaban/index','content='.$_GET['content']));	
		}
		if (session("user_id") == 1 || session('position_id') == 2){
			//管理员，可删除任意记录
			$model = new Model();
			$model->table("jl_kaoqin_jiaban")->where("id=".$id)->delete();
			alert('success', '操作成功',U('Jiaban/index','content='.$_GET['content']));
		} else {
			//普通用户，只可删除自己的
			$model = new Model();
			$model->table("jl_kaoqin_jiaban")->where("id=".$id." and user_id=".session("user_id"))->delete();	
			alert('success', '操作成功',U('Jiaban/index','content='.$_GET['content']));		
		}
		exit;
	}
	
	//审核
	public function accept(){
		$id = intval($_GET['id']);
		if (session("user_id") == 1 || session('position_id') == 2){
			$model = new Model();
			$datas = $model->table("jl_kaoqin_jiaban")->where("id=".$id)->find();
			if ($datas['checked']){
				$data_accept = 0;
			}else{
				$data_accept = 1;
			}
		//	var_dump($datas);exit;
			
			$data = array();
			//$data['id'] = $id;
			$data['checked'] = $data_accept;
			$result = $model->table("jl_kaoqin_jiaban")->where("id=".$id)->save($data);
			alert('success', '操作成功',U('Jiaban/index','content='.$_GET['content']));
		}
	}
	
	
	
	
	
}