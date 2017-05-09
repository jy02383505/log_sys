<?php
//考勤记录类 zjh add
class KaoqinconfigAction extends Action{
	
	//设置
	public function config(){
		$model = new Model();
		
		//用户下拉列表
		if (session("user_id") == 1 || session('position_id') == 2){
	
			//全部用户，下拉列表
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
				if (!$v['kqj_user_id']){
					//$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
				}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列
			
							$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
							$tm = mktime(1,1,1,$the_month,$i,$the_year);
							$week_num = date("w",$tm);//星期几
			$time_list = $model->table("jl_kaoqin_config")->order("id ASC")->select();
			foreach($time_list as $k=>$v){
				$time_list[$k]['week_cn'] = $weekArr[$v['week']];
			}
			$this->assign("time_list",$time_list);//
			
			$this->display("Kaoqin:config");
		} else{
			$this->display("public:error");
		}
	}
	
	//保存系统用户和考勤机id匹配记录 ajax
	public function ajax_save_uid_kqj(){
		$user_id = intval($_POST['user_id']);
		$kqj_user_id = intval($_POST['kqj_user_id']);
		
		if (!$user_id){
			echo json_encode(array("stat"=>0,"data"=>"参数错误"));
		}
		if (!$kqj_user_id){
			echo json_encode(array("stat"=>0,"data"=>"参数错误"));
		}		
		
		if ($user_id && $kqj_user_id) {
			$model = M('Kaoqin_system_userid');
			$datas = $model->table("jl_kaoqin_system_userid")->where(array('user_id'=>$user_id))->find();
			if ($datas){
				
				$update_datas = array();
				$update_datas['user_id'] = $user_id;
				$update_datas['kqj_user_id'] = $kqj_user_id;
				$result = $model->table("jl_kaoqin_system_userid")->where("user_id=".$user_id)->save($update_datas);
				if (false === $result){
					echo json_encode(array("stat"=>0,"data"=>"更新失败"));
				}else{
					echo json_encode(array("stat"=>1,"data"=>"更新成功"));
				}
				
			}else{
				
				$update_datas = array();
				$update_datas['user_id'] = $user_id;
				$update_datas['kqj_user_id'] = $kqj_user_id;
				$result = $model->data($update_datas)->add();
				if (false === $result){
					echo json_encode(array("stat"=>0,"data"=>"更新失败"));
				}else{
					echo json_encode(array("stat"=>1,"data"=>"更新成功"));
				}
			}
		}	
	}
	
	
	//保存考勤时间设置 ajax
	public function ajax_save_kq_time(){
		$id = intval($_POST['id']);
		$starttime = trim($_REQUEST['starttime']);
		$endtime = trim($_REQUEST['endtime']);
		
		if (!$id){
			echo json_encode(array("stat"=>0,"data"=>"参数错误"));
		}
		if (!$starttime){
			echo json_encode(array("stat"=>0,"data"=>"参数错误"));
		}
		if (!$endtime){
			echo json_encode(array("stat"=>0,"data"=>"参数错误"));
		}
		
		$allow_start_time = array("7:30","8:00","8:30","9:00","9:30");
		$allow_end_time = array("12:00","17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30");
		
		if (!in_array($starttime,$allow_start_time)){
			//错误
			echo json_encode(array("stat"=>0,"data"=>"更新失败1"));exit;
		}
		
		if (!in_array($endtime,$allow_end_time)){
			//错误
			echo json_encode(array("stat"=>0,"data"=>"更新失败2"));exit;
		}		
		
		if ($id && $starttime && $endtime) {
			$model = new Model();
			$datas = $model->table("jl_kaoqin_config")->where(array('id'=>$id))->find();
			if ($datas){
				
				$update_datas = array();
				$update_datas['id'] = $id;
				$update_datas['starttime'] = $starttime;
				$update_datas['endtime'] = $endtime;
				$result = $model->table("jl_kaoqin_config")->where("id=".$id)->save($update_datas);
				if (false === $result){
					echo json_encode(array("stat"=>0,"data"=>"更新失败"));
				}else{
					echo json_encode(array("stat"=>1,"data"=>"更新成功"));
				}
			} else {
				echo json_encode(array("stat"=>0,"data"=>"更新失败"));
			}
			
		}	
			
	}	
	
	/**
	 * 设置每年每月每日考勤时间-创建某月每天的默认记录
	*/
	public function config_year_month_day_time(){
		/*
		$m = D("Configyearmonthdaytime");
		$d = $m->select();
		var_dump($d);
		*/
		
		$model = new Model();
		header("Content-Type:text/html;charset=utf-8");
		$the_year = trim($_GET['the_year']);
		$the_month = trim($_GET['the_month']);
		
		if (empty($the_year)){
			$the_year = date("Y",time());;
		}else{
			$the_year = trim($_GET['the_year']);
		}
		
		if (empty($the_month)){
			$the_month = date("m",time());			
		}else{
			$the_month = trim($_GET['the_month']);
		}
		
		//echo $the_month;
		
		$state_arr = array("0"=>"不上班","1"=>"必须打卡","2"=>"免打卡");
		$this->assign("state_arr",$state_arr);
		
		$autoCreate = intval($_GET['autoCreate']);//自动创建
		
		//年月
		$year_month = $the_year."-".$the_month;
		$year_month = date("Y-m",strtotime($year_month));
		
		$month_first_day = $the_year."-".$the_month."-"."1";
		$month_first_day = date("Y-m-d",strtotime($month_first_day));//格式化成2016-02-01
		
		$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");

		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config ");
		$kq_time = array();
		foreach($kq_config as $k=>$v){
			$kq_time[$k]["starttime"]=$v['starttime'];//每天的时间存对应的数组
			$kq_time[$k]["endtime"]=$v['endtime'];
		}
		
		
		
		$this->assign("the_year",$the_year);
		$this->assign("the_month",$the_month);
		$this->assign("autoCreate",$autoCreate);
		
		//提交，如果勾选了如没有请创建默认记录，则自动创建
		if (!empty($the_year) && ($the_month)){
			//
			if ($autoCreate){
				//echo "你勾选了复选框：自动创建";
				
				//获取本月有多少天
				$month_days = date('t',strtotime($month_first_day));//本月总天数
				
				//遍历全部天数
				for($i=1;$i<$month_days+1;$i++){
					//格式化本次循环的日期
					$year_month_day = $year_month."-".$i;
					$this_day = date("Y-m-d",strtotime($year_month_day));
					$this_day_week_int = date( "w",strtotime($year_month_day));
					$this_day_week_ch = $weekArr[$this_day_week_int];
					
					//echo "<br>".$this_day." ".$this_day_week_ch;
					
					//生成本日期的记录
					$ins_datas = array();
					$ins_datas['date'] = $this_day;
					$ins_datas['year'] = date("Y",strtotime($this_day));
					$ins_datas['month'] = date("m",strtotime($this_day));
					$ins_datas['day'] = date("d",strtotime($this_day));
					$ins_datas['week'] = $this_day_week_ch;
					$ins_datas['week_int'] = $this_day_week_int;
					switch($this_day_week_int){
						case 0:
							$ins_datas['state'] = 0;//星期天
						//	$ins_datas['content'] = "星期天休息";
							break;
						case 6:
							$ins_datas['state'] = 1;
							$ins_datas['start'] = $kq_time[$this_day_week_int-1]["starttime"];
							$ins_datas['end'] = $kq_time[$this_day_week_int-1]["endtime"];
						//	$ins_datas['content'] = "星期六";
							break;
						default:
							$ins_datas['state'] = 1;
							$ins_datas['start'] = $kq_time[$this_day_week_int-1]["starttime"];
							$ins_datas['end'] = $kq_time[$this_day_week_int-1]["endtime"];
						//	$ins_datas['content'] = "正常上班";
					}
					
					//var_dump($ins_datas);
					
					//查找该天的记录
					$model = new Model();
					$datas = $model->table("jl_kaoqin_config_days")->where("date='".$this_day."'")->find();
					if ($datas){
						//已有记录
					}else{
						$model->table("jl_kaoqin_config_days")->data($ins_datas)->add();
					}
				}

			}
			
			//显示已有记录
			$map = array();
			$map['year'] = $the_year;
			$map['month'] = $the_month;
			
			$datas = array();
			$datas = $model->table("jl_kaoqin_config_days")->where($map)->select();//var_dump($datas);
			$this->assign("datas",$datas);
			
		}
		
		
		
		//如果没有选择年月，忽略复选框
		
		//设置了年月，检测是否已存在，已存在的日记录不变，不存在的日记录新增
		
			
		

		$this->display("Kaoqin:config_year_month_day_time");
		
	}
	
	/**
	 * 设置每年每月每日考勤时间-创建某月每天的默认记录-修改及保存
	*/
	public function config_year_month_day_time_edit(){
		$id = $this->_request('id','intval',0);
		
		$start = $this->_request('start','trim');
		$end = $this->_request('end','trim');
		$content = $this->_request('content','trim');
		$model = new Model();
		
		if (IS_POST){
			$state = $this->_request('state','intval',0);
			
			$data = array();
			if (!$state){
				$data['state'] = 0;
				$data['start'] = NULL;
				$data['end'] = NULL;
			} elseif ($state == 1) {
				$data['state'] = 1;
				$data['start'] = $start;
				$data['end'] = $end;	
			}elseif ($state == 2){
				$data['state'] = 2;
				$data['start'] = NULL;
				$data['end'] = NULL;
			}else{
				//状态不明确
				;	
			}

			$data['content'] = $content;
			$model->table("jl_kaoqin_config_days")->where("id=".$id)->data($data)->save();
		}
		
		$datas = array();
		$datas = $model->table("jl_kaoqin_config_days")->where("id=".$id)->find();
		
	//	var_dump($datas);
		$this->assign("datas",$datas);
		$this->display("Kaoqin:config_year_month_day_time_edit");
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}