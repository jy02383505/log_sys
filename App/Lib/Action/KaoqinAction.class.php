<?php
//考勤记录类 zjh add
class KaoqinAction extends Action{
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','tj','add','edit','txtimport')
		);
		B('Authenticate', $action);
		$this->assign("user_id",session("user_id"));
	}

	public function view(){
		$this->index();
	}

	public function index(){
		$model = new Model();
		
			//var_dump(session("position_id"));//岗位数据表=jl_position,会计=2,软件工程师=5,美工=6,网络工程师=4,销售经理=3,CEO=1
			//session('role_id')，???
		
		header("Content-type: text/html; charset=utf-8");
		
		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
		
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
		
//		$user_id = intval($_GET['user_id']);
		$the_year = intval($_GET['the_year']);
		
		
		if (session("user_id") == 1 || session('position_id') == 2){		
			$user_id = intval($_GET['user_id']);
		}else{
			$user_id = session("user_id");
		}
		////////////////////////////////////////////////////////////////////////////////
		
		//设置下拉列表的默认选中
		$defaul_year = date("Y",mktime());
		if (!$the_year){
			$the_year = $defaul_year;
		}
		
		$defaul_month = date("m",mktime());
		
		$the_month = intval($_GET['the_month']);
		if ($the_month){

		}else{
			$the_month = $defaul_month;
		}
		if (strlen($the_month) < 2){
			$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
		}
		
		$sign_month = $the_year.'-'.$the_month;
		$this->assign("kqj_user_id",$kqj_user_id);
		$this->assign("user_id",$user_id);
		$this->assign("the_year",$the_year);
		$this->assign("the_month",$the_month);
		if ($user_id){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin.user_id'] = $user_id;
		}
		if ($the_year && $the_month){
			$map['jl_kaoqin.sign_month'] = $sign_month;
		}

		$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
		//$qstr = "select A.*,B.user_id FROM jl_kaoqin AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.kqj_user_id = B.kqj_user_id ".$where." limit ".$Page->firstRow.",".$Page->listRows;
		
		
          // 加载数据分页类
        import("@.ORG.Page");
        
        // 数据分页
        $totals = $model->table("jl_kaoqin")->where($map)->count();
		
        $Page = new Page($totals, 10);
        $show = $Page->show();
        $this->assign('page', $show);	
		
		$order = " id DESC";
		$datas = $model->table("jl_kaoqin")->where($map)->join('jl_kaoqin_system_userid ON jl_kaoqin.user_id = jl_kaoqin_system_userid.user_id')->field("jl_kaoqin.*")->limit($Page->firstRow.','.$Page->listRows)->order($order)->select();
//		echo $model->getlastsql();
//		var_dump($datas);

		//重新整理
		foreach($datas as $k=>$v){
			$user = $model->query("select name from jl_user where user_id=".$v['user_id']);
			$datas[$k]['name']=$user[0]['name'];
			
			if ($v['sign_time'] < '12:00:00'){
				$datas[$k]['status']="<font color='#CCC'>签到</font>";
			} else {
				$datas[$k]['status']="签退";
			}
			
			//这儿获取每条记录对应日期的签到签退时间，从jl_kaoqin_config_days中读取
			
			
			
		}

		$Page->parameter = implode('&', $params);
		$this->assign('page',$Page->show());
		$this->assign("datas",$datas);
		$this->display("index");
	}
	
	//TXT文件上传
	public function txtimport(){
		$kq_model = new Model();
		$kq_file_model = new Model();
		$kq_config_model = new Model();
		header("Content-Type:text/html;charset=utf-8");
		//读取考勤配置
		/*
		$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where week=1 limit 1");//星期1-5的时间一样
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
		
		$kq_config_6 = $kq_config_model->query("select * from jl_kaoqin_config where week=6 limit 1");//星期6
		$config_datas_6 = $kq_config_6[0];
		$starttime_6 = $config_datas_6['starttime'];//星期六，上班时间
		$endtime_6 = $config_datas_6['endtime'];//星期六，下班时间		
		*/

		
		if($_POST['submit']){
			//var_dump($_FILES);exit;
			if (isset($_FILES['txt']['size']) && $_FILES['txt']['size'] != null) {
				import('@.ORG.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = 20000000;
				$upload->allowExts  = array('dat');
				$dirname = './Uploads/' . date('Ym', time()).'/'.date('d', time()).'/';
				if (!is_dir($dirname) && !mkdir($dirname, 0777, true)) {
					alert('error', L('ATTACHMENTS_TO_UPLOAD_DIRECTORY_CANNOT_WRITE'), U('customer/index'));
				}
				$upload->savePath = $dirname;
				if(!$upload->upload()) {
					alert('error', $upload->getErrorMsg(), U('customer/index'));
				}else{
					$info =  $upload->getUploadFileInfo();
				//	var_dump($info);//array(1) { [0]=> array(8) { ["name"]=> string(13) "11_attlog.dat" ["type"]=> string(24) "application/octet-stream" ["size"]=> int(227800) ["key"]=> string(3) "txt" ["extension"]=> string(3) "dat" ["savepath"]=> string(20) "./Uploads/201605/17/" ["savename"]=> string(17) "573a86ec824c9.dat" ["hash"]=> string(32) "0d3abe511757a0d36cb68ccbd3c8bda6" } }
				}
			}
			
			//每日考勤设置
			$cfgKqModel = D("Configyearmonthdaytime");
			
			if(is_array($info[0]) && !empty($info[0])){
				$savePath = $dirname . $info[0]['savename'];
				
				//每次上传时在表中加一条记录，记下dat存储路径
				$datas = array();
				$datas['filepath'] = $savePath;
				$datas['createtime'] = date("Y/m/d h:i:s", mktime());
				$result = $kq_file_model->table("jl_kaoqin_file")->data($datas)->add();
				/*
				if ($result){
					echo "ok";
				}else{
					echo "err";
				}*/
				
				//读取txt文件
				$fp=fopen($savePath,'r');
				$str=array();
				$i=0;
				
				
				
				while(!feof($fp))
				{
					$row=fgets($fp);//fgets() 函数从文件指针中读取一行。fgets(file,length)file	必需。规定要读取的文件。length	可选。规定要读取的字节数。默认是 1024 字节。
					
					//把原始的行写入数据库
					
					//
					
					$row = trim($row);//trim() 函数移除字符串两侧的空白字符或其他预定义字符。
					$row = str_ireplace("	",",",$row);
					$str[$i]=$row;//每一行作为一个数组元素
					$i++;
				}
				fclose($fp);
				//var_dump($str);
				
				//读取系统用户和考勤机的用户匹配数据
				$model_user = new Model();
				$user_user = $model_user->table("jl_kaoqin_system_userid")->select();
				
				
				//遍历行数组
				foreach ($str as $k=>$v){
					//echo "<br>".$str[$k];
					//把整理过的写入数据库（逗号分隔的字符串）
					//判断重复
					$kq_result = $kq_model->table("jl_kaoqin")->where("txt_row='".$str[$k]."'")->find();
					if ($kq_result){
						//有重复
						echo 'had';
					}else{
						
						$datas = array();
						
						$row_arr = array();
						$row_array = explode(",",$v);
						
						if ($v != '' && !empty($v) && $v !=null ){	//防止有空行被导入，即只有回车换行
							$datas['txt_row'] = $v;
							$datas['kqj_user_id'] = $row_array[0];//考勤机id
							$this_user_id = 0;
							$name = "";
							foreach($user_user as $kk=>$vv){
								if ($vv['kqj_user_id']==$row_array[0]){
									$this_user_id = $vv['user_id'];
									$name = $vv['name'];//
								}
							}
							
							$ymd = date("Y-m-d",strtotime($row_array[1]));
							
							$datas['name'] = $name;//为了直观检测正确与否
							$datas['user_id'] = $this_user_id;
							$datas['kqj_date_varchar'] = $row_array[1];//考勤机时间,varchar格式
							$datas['kqj_date_int'] = strtotime($row_array[1]);//考勤机时间,int格式
							$datas['sign_date'] = date("Y-m-d",strtotime($row_array[1]));//年月日
							$datas['sign_year'] = date("Y",strtotime($row_array[1]));//年
							$datas['sign_month'] = date("Y-m",strtotime($row_array[1]));//年月
							$datas['sign_day'] = date("d",strtotime($row_array[1]));//日
							$datas['sign_time'] = date("H:i:s",strtotime($row_array[1]));//几点几分几秒
							$datas['create_time'] = date("Y-m-d h:i:s", mktime());
									$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
									$week_num = date("w",strtotime($row_array[1]));//星期几
							$datas['week'] = $weekArr[$week_num];	
							//如果刷卡时间大于下班时间
							$tm = date("H:i:s",strtotime($row_array[1]));
							
							//读取今日考勤时间设置（jl_kaoqin_config_days）							
							$datas_today_cfg = $cfgKqModel->where("date='".$ymd."'")->find();
							if (!$datas_today_cfg){
								//alert('error',"未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作", U('Kaoqin/index'));
								echo "未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作";
								echo '<a href='.U('Kaoqinconfig/config_year_month_day_time').'><button type="button" class="btn btn-primary" title="设置每月考勤时间">设置每月考勤时间</button></a>';
								
								exit;
							}else{
								$endtime = $datas_today_cfg['end'];	
							}
							
							if ($tm >= $endtime){
								$datas['type'] = 1;//签到
								
								//
								$jiaban_hours = (strtotime($tm) - strtotime($endtime)) / 3600;
								$jiaban_hours = round($jiaban_hours,1);
								$f = end(explode('.',$jiaban_hours));//取小数部分
								$the_int = reset(explode('.',$jiaban_hours));
								if ($f>5){
									$jiaban_hours = $the_int + 0.5;
								} else {
									$jiaban_hours = $the_int;
								}
								$datas['jiaban_hours'] = $jiaban_hours;
							} else {
								$datas['type'] = 0;//签退
							}
							
							/*
							if ($week_num==6){
								$datas['starttime'] = $config_datas_6['starttime'];//星期六，上班时间
								$datas['endtime'] = $config_datas_6['endtime'];//星期六，下班时间
							}else{
								$datas['starttime'] = $config_datas['starttime'];//上班时间
								$datas['endtime'] = $config_datas['endtime'];//下班时间
							}
							*/
							
							
							
							//直接入库，然后整理
							$map_check = array();
							$map_check['kqj_user_id'] = $row_array[0];
							$map_check['sign_date'] = date("Y-m-d",strtotime($row_array[1]));//年月日
							$result_check = $kq_model->table("jl_kaoqin")->where($map_check)->count();
							
							if ($result_check == 2){								
								//与已有记录最小的和最大的比较，如果是中间，则忽略不入库
								
								//如果比最小的还小，则入库，并删除原先最小
								$result_check_small =  $kq_model->table("jl_kaoqin")->where($map_check)->order("kqj_date_varchar ASC")->find();
								if ($row_array[1] < $result_check_small['kqj_date_varchar']){
									//入库
									$kq_model->table("jl_kaoqin")->data($datas)->add();
									//删除旧的最小
									$kq_model->table("jl_kaoqin")->where("id=".$result_check_small['id'])->delete();
								}								
								
								//如果比最大的还大，则入库，并删除原先最大的	
								$result_check_big =  $kq_model->table("jl_kaoqin")->where($map_check)->order("kqj_date_varchar DESC")->find();
								if ($row_array[1] > $result_check_big['kqj_date_varchar']){
									//入库
									$kq_model->table("jl_kaoqin")->data($datas)->add();
									//删除旧的最大
									$kq_model->table("jl_kaoqin")->where("id=".$result_check_big['id'])->delete();
								}
								
							} else{
								$kq_model->table("jl_kaoqin")->data($datas)->add();
							}
							
							//处理今天的数据
							$map_set = array();
							$map_set['kqj_user_id'] = $row_array[0];
							$map_set['sign_date'] = date("Y-m-d",strtotime($row_array[1]));//年月日
							$result_set = $kq_model->table("jl_kaoqin")->where($map_set)->order("kqj_date_varchar ASC")->select();
							foreach($result_set as $k=>$v){
								//读取此日考勤时间设置（jl_kaoqin_config_days）	
								$datas_today_cfg = array();						
								$datas_today_cfg = $cfgKqModel->where("date='".$v['sign_date']."'")->find();
								if (!$datas_today_cfg){
									//找到此日考勤时间设置
								}else{
									//未找到此日考勤时间设置	
								}
								if ($k==0){
									$up_dates = array();
									$up_dates['id'] = $v['id'];
									$up_dates['type']=0;
									$up_dates['content']='签到';
									

									if(strtotime($v['sign_time']) > strtotime($datas_today_cfg['start'])){
										$up_dates['cdzt_minutes'] = (strtotime($v['sign_time']) - strtotime($datas_today_cfg['start']))/60;
									}

									$kq_model->table("jl_kaoqin")->save($up_dates);
								}else{
									$up_dates = array();
									$up_dates['id'] = $v['id'];
									$up_dates['type'] = 1;
									$up_dates['content']='签退';
										if(strtotime($v['sign_time']) < strtotime($datas_today_cfg['end'])){
											$up_dates['cdzt_minutes'] = (strtotime($datas_today_cfg['end']) - strtotime($v['sign_time']))/60;
										}
									
									$kq_model->table("jl_kaoqin")->save($up_dates);
								}
							}
						}
						
						
					}
					
				}
				
				//
				
			//	alert('success', '导入成功',U('Kaoqin/index','content='.$_GET['content']));
			}else{
				alert('error', L('UPLOAD_FAILED'), U('customer/index'));
			}
			
			
		}else{
			//echo "1";exit;
			$this->display("txtimport");
		}
	}


	public function add(){
		if (IS_POST) {
			if (1 == session('user_id') || 2 == session('position_id')){
				$kq_model = new Model();
				$kq_config_model = new Model();
				
				$tm = trim($_POST['the_time']);
				$type = intval($_POST['type']);
				
				//取星期几
				$week_num = date("w",strtotime($tm));//星期几
				
				//读取考勤配置
				/*
				$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where week=".$week_num." limit 1");//无星期日时间
				$config_datas = $kq_config[0];
				
				$starttime = $config_datas['starttime'];//上班时间
				$endtime = $config_datas['endtime'];//下班时间
				*/
				
				//每日考勤设置
				$cfgKqModel = D("Configyearmonthdaytime");
				
				//取到时间，然后查询配置
				$ymd = date("Y-m-d",strtotime($tm));
				//读取今日考勤时间设置（jl_kaoqin_config_days）							
				$datas_today_cfg = $cfgKqModel->where("date='".$ymd."'")->find();
				if (!$datas_today_cfg){
					//alert('error',"未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作", U('Kaoqin/index'));
					echo "未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作";
					echo '<a href='.U('Kaoqinconfig/config_year_month_day_time').'><button type="button" class="btn btn-primary" title="设置每月考勤时间">设置每月考勤时间</button></a>';
					exit;
				}else{
					//var_dump($datas_today_cfg);exit;
					$starttime = $datas_today_cfg['start'];	
					$endtime = $datas_today_cfg['end'];	
				}
				
				
				
				
				
				$user_id = intval($_POST['user_id']);
				$user = $kq_model->table("jl_kaoqin_system_userid")->where("user_id=".$user_id)->find();
				
				
	
				
				$datas = array();
				$datas['kqj_user_id'] = $user['kqj_user_id'];
				$datas['user_id'] = intval($_POST['user_id']);
				$datas['name'] = $user['name'];
				$datas['starttime'] = $config_datas['starttime'];//上班时间
				$datas['endtime'] = $config_datas['endtime'];//下班时间
				$datas['kqj_date_varchar'] = $tm;//考勤机时间,varchar格式
				$datas['kqj_date_int'] = strtotime($tm);//考勤机时间,int格式
				$datas['sign_date'] = date("Y-m-d",strtotime($tm));//年月日
				$datas['sign_month'] = date("Y-m",strtotime($tm));//年月日
				$datas['sign_day'] = date("d",strtotime($tm));//日
				$datas['sign_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
				$datas['create_time'] = date("Y/m/d h:i:s", mktime());
				
						$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
						$week_num = date("w",strtotime($tm));//星期几
				$datas['week'] = $weekArr[$week_num];		
				$datas['type'] = intval($_POST['type']);
				$datas['content'] = trim($_POST['content']);
				
								//如果刷卡时间大于下班时间
				if (0 == $type){
					//签到
					if(strtotime(date("H:i:s",strtotime($tm))) > strtotime($starttime)){
						$cdzt_minutes = (strtotime(date("H:i:s",strtotime($tm))) - strtotime($starttime))/60;//迟到分钟数
					}else{
						$cdzt_minutes = 0;
					}
				} else {
					//签退
					$jiaban_hours = (strtotime($tm) - strtotime($endtime)) / 3600;
					$jiaban_hours = round($jiaban_hours,1);
					$f = end(explode('.',$jiaban_hours));//取小数部分
					$the_int = reset(explode('.',$jiaban_hours));
					if ($f>5){
						$jiaban_hours = $the_int + 0.5;
					} else {
						$jiaban_hours = $the_int;
					}
					$datas['jiaban_hours'] = $jiaban_hours;
					
					if(strtotime(date("H:i:s",strtotime($tm))) < strtotime($endtime)){
						$cdzt_minutes = (strtotime($endtime) - strtotime(date("H:i:s",strtotime($tm))))/60;//早退时间
					}else{
						$cdzt_minutes = 0;
					}
					
				}			
				$datas['cdzt_minutes'] = $cdzt_minutes;//迟到早退分钟数
				
				$result = $kq_model->table("jl_kaoqin")->data($datas)->add();
				//var_dump($result);
				alert('success', '新增考勤记录成功',U('Kaoqin/index','content='.$_GET['content']));
			}
        } else {
            $model = new Model();
			//全部用户，下拉列表
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
				if (!$v['kqj_user_id']){
					$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
				}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列表
			
            $this->display("edit");
        }
	}
	
	
	
	public function edit(){
		if (IS_POST) {
			if (1 == session('user_id') || 2 == session('position_id')){
				$kq_model = new Model();
				$kq_config_model = new Model();
				
				$id = intval($_POST['id']);
				$tm = trim($_POST['the_time']);
				if(empty($tm) || empty($id)){
					//日期时间为空，直接退出	
				}
				
				//取星期几
				$week_num = date("w",strtotime($tm));//星期几
				
				//读取考勤配置
				/*
				$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where week=".$week_num." limit 1");//无星期日时间
				$config_datas = $kq_config[0];
	
				$starttime = $config_datas['starttime'];//上班时间
				$endtime = $config_datas['endtime'];//下班时间
				*/
				
				//每日考勤设置
				$cfgKqModel = D("Configyearmonthdaytime");
				
				//取到时间，然后查询配置
				$ymd = date("Y-m-d",strtotime($tm));
				//读取今日考勤时间设置（jl_kaoqin_config_days）							
				$datas_today_cfg = $cfgKqModel->where("date='".$ymd."'")->find();
				if (!$datas_today_cfg){
					//alert('error',"未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作", U('Kaoqin/index'));
					echo "未找到考勤时间设置(".$ymd.")，导入中止，请先去设置每日考勤时间后再执行导入操作";
					echo '<a href='.U('Kaoqinconfig/config_year_month_day_time').'><button type="button" class="btn btn-primary" title="设置每月考勤时间">设置每月考勤时间</button></a>';
					exit;
				}else{
					//var_dump($datas_today_cfg);exit;
					$starttime = $datas_today_cfg['start'];	
					$endtime = $datas_today_cfg['end'];	
				}
				
				$user_id = intval($_POST['user_id']);
				$user = $kq_model->table("jl_kaoqin_system_userid")->where("user_id=".$user_id)->find();
				
				$type = intval($_POST['type']);	
				
				//echo $tm;exit;
				$datas = array();
		//		$datas['id'] = intval($_POST['id']);
				$datas['kqj_user_id'] = $user['kqj_user_id'];
				$datas['user_id'] = intval($_POST['user_id']);
				$datas['name'] = $user['name'];
				
				$datas['starttime'] = $starttime;//上班时间
				$datas['endtime'] = $endtime;//下班时间
				$datas['kqj_date_varchar'] = $tm;//考勤机时间,varchar格式
				$datas['kqj_date_int'] = strtotime($tm);//考勤机时间,int格式
				$datas['sign_date'] = date("Y-m-d",strtotime($tm));//年月日
				$datas['sign_month'] = date("Y-m",strtotime($tm));//年月日
				$datas['sign_day'] = date("d",strtotime($tm));//日
				$datas['sign_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
						$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
						$week_num = date("w",strtotime($tm));//星期几
				$datas['week'] = $weekArr[$week_num];	
				$datas['update_date'] = date("Y-m-d h:i:s", mktime());
				$datas['type'] = intval($_POST['type']);
				$datas['content'] = trim($_POST['content']);	
				
				if (0 == $type){//签到
					if(strtotime(date("H:i:s",strtotime($tm))) > strtotime($starttime)){
						$cdzt_minutes = (strtotime(date("H:i:s",strtotime($tm))) - strtotime($starttime))/60;//迟到分钟数
					}else{
						$cdzt_minutes = 0;
					}
				} else {//签退
					//
					$jiaban_hours = (strtotime($tm) - strtotime($endtime)) / 3600;
					$jiaban_hours = round($jiaban_hours,1);
					$f = end(explode('.',$jiaban_hours));//取小数部分
					$the_int = reset(explode('.',$jiaban_hours));
					if ($f>5){
						$jiaban_hours = $the_int + 0.5;
					} else {
						$jiaban_hours = $the_int;
					}
					$datas['jiaban_hours'] = $jiaban_hours;
					
					if(strtotime(date("H:i:s",strtotime($tm))) < strtotime($endtime)){
						$cdzt_minutes = (strtotime($endtime) - strtotime(date("H:i:s",strtotime($tm))))/60;//早退时间
					}else{
						$cdzt_minutes = 0;
					}
				}	
				$datas['cdzt_minutes'] = $cdzt_minutes;//迟到早退分钟数
				
				
				$result = $kq_model->table("jl_kaoqin")->where("id=".$id)->save($datas);
				//var_dump($datas);
				alert('success', '修改考勤记录成功',U('Kaoqin/index','content='.$_GET['content']));
			}			
        } else {
			$id = intval($_GET['id']);
			
            $model = new Model();
			$datas = $model->table("jl_kaoqin")->where("id=".$id)->find();
			
			if ($datas){
					
			} else {
				
			}
			
			//全部用户，下拉列表
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
				if (!$v['kqj_user_id']){
					$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
				}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列表
			
			//var_dump($datas);
			
            $this->assign("datas",$datas);
            $this->display("edit");
        }
	}
	
	
	
	//统计
	public function tj(){
		header("Content-type: text/html; charset=utf-8");
		$model = new Model();
		//全部用户，下拉列表
		$u_str = "select A.name,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
		$user_datas = $model->query($u_str);
		foreach($user_datas as $k=>$v){
			if (!$v['kqj_user_id']){
				$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
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
		$kqj_user_id = intval($_REQUEST['kqj_user_id']);
		if ($the_year && $the_month && kjq_user_id){// if start
			$this->assign("the_year",$the_year);
			$this->assign("the_month",$the_month);
			$this->assign("kqj_user_id",$kqj_user_id);
			
			//按月
			/*			
			echo "<br>the_year=".$the_year;
			echo "<br>the_month=".$the_month;
			echo "<br>kqj_user_id=".$kqj_user_id;
			echo '<br>本月天数：'.date("t",strtotime("$the_year-$the_month"));
			*/
			//本月总天数
			$days = date("t",strtotime("$the_year-$the_month"));
			
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
			
			
			if ($the_month < 10){
				$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
			}
			
			$out_datas = array();//输出结果
			for ($i=1; $i< $days+1; $i++) {
				//echo "数字是：$x <br>";
				$out_datas[$i]['id'] = $i;//第几号
				//查找本日的签到记录
				$map_1024 = array();
				$map_1024['kqj_user_id'] = $kqj_user_id;
				$map_1024['sign_month'] = $the_year.'-'.$the_month;//存的是年-月
				$map_1024['sign_day'] = $i;
				$data_1024 = $model->table("jl_kaoqin")->where($map_1024)->order("kqj_date_varchar ASC")->find();//日期正序排列，第一条作为签到记录
				if ($data_1024){
					$out_datas[$i]['kqj_date_varchar'] = $data_1024['kqj_date_varchar'];//签到时间
					if ($data_1024['type'] == 1){
						$out_datas[$i]['status'] = "请假";
					} else {
						if ($data_1024['kqj_date_varchar']<="8:30"){
							$out_datas[$i]['status'] = "正常签到";
						}else{
							$out_datas[$i]['status'] = "迟到";
						}
					}
					$out_datas[$i]['starttime'] = $data_1024['starttime'];
					$out_datas[$i]['endtime'] = $data_1024['endtime'];
					$out_datas[$i]['week'] = $data_1024['week'];
					$out_datas[$i]['content'] = $data_1024['content'];
					
				} else {
					$out_datas[$i]['status'] = "未签到";
					
							$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
							$tm = mktime(1,1,1,$the_month,$i,$the_year);
							$week_num = date("w",$tm);//星期几
					$out_datas[$i]['week'] = $weekArr[$week_num];	
					$out_datas[$i]['kqj_date_varchar'] = date("Y-m-d",$tm);//签到时间
					$out_datas[$i]['starttime'] = $starttime;
					$out_datas[$i]['endtime'] = $endtime;
					$out_datas[$i]['content'] = '无记录';
				}
				
			}
//			var_dump($out_datas);
			$this->assign("out_datas",$out_datas);
			
			
			$map = array();
			
			$map['sign_month'] = $the_year.'-'.$the_month;//存的是年-月
			$map['kqj_user_id'] = $kqj_user_id;
	//	echo '<br>'.$map['sign_month'];
			
			$datas = $model->table("jl_kaoqin")->where($map)->select();
			if ($datas){
//				echo '<br>'.$model->getlastsql();
				//var_dump($datas);
				foreach ($datas as $k=>$v){
//					echo '<br>'.$v['kqj_user_id'];
					
				}
				

				
			}else{
				//echo "err";	
			}
			
			//遍历用户，查看指定年月的考勤记录
			foreach($user_datas as $k=>$v){
				//
				//echo '<br>name='.$v['name'].'----user_id='.$v['user_id'].'----kqj_user_id='.$v['kqj_user_id'];
				//按天循环，将本用户的考勤记录列出统计，遇到星期天空行
	
				
			}
			
			
			
		}// if end
		
		$this->display("tj");
	}
	
	

	
	//其它考勤记录，包括出差，请假、加班等
	public function record(){
		$model = new Model();
		
		header("Content-type: text/html; charset=utf-8");
		
		//读取考勤配置
		$kq_config = $model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
		$config_datas = $kq_config[0];
		$starttime = $config_datas['starttime'];//上班时间
		$endtime = $config_datas['endtime'];//下班时间
		
		
		//全部用户，下拉列表
		$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
		$user_datas = $model->query($u_str);
		foreach($user_datas as $k=>$v){
			if (!$v['user_id']){
				$user_datas[$k]['user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
			}	
		}
		$this->assign("users_list",$user_datas);//用户下拉列表

		$type_list = array(array("type"=>1,"name"=>"请假"),array("type"=>2,"name"=>"加班"),array("type"=>3,"name"=>"出差"));
		$this->assign("type_list",$type_list);//记录类型

		$show_type_list = array(array("type"=>0,"name"=>"列表"),array("type"=>1,"name"=>"统计"));
		$this->assign("show_type_list",$show_type_list);//记录类型
		
		$show_type = intval($_GET['show_type']);
		$this->assign("show_type",$show_type);
		
		$user_id = intval($_GET['user_id']);
		$the_year = intval($_GET['the_year']);
		$the_month = intval($_GET['the_month']);
			if ($the_month < 10){
				$the_month = '0'.$the_month;//确保是01,02,03,04,05,06,07,08,09,10,11,12格式的月份
			}
		$type = intval($_GET['type']);
		
		$the_month = $the_year.'-'.$the_month;
		$this->assign("user_id",$user_id);
		$this->assign("the_year",$the_year);
		$this->assign("the_month",intval($_GET['the_month']));
		$this->assign("type",intval($_GET['type']));
		
		if ($user_id){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin_record.user_id'] = $user_id;
		}
		if ($the_year && $the_month){
			$map['jl_kaoqin_record.the_month'] = $the_month;
		}
		if ($type){
			//$where = "where A.id>0  and A.kqj_user_id=".$kqj_user_id;
			$map['jl_kaoqin_record.type'] = $type;
		}
			
		$p = !$_REQUEST['p']||$_REQUEST['p']<=0 ? 1 : intval($_REQUEST['p']);
		//$qstr = "select A.*,B.user_id FROM jl_kaoqin AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.kqj_user_id = B.kqj_user_id ".$where." limit ".$Page->firstRow.",".$Page->listRows;
		
		
          // 加载数据分页类
        import("@.ORG.Page");
        
        // 数据分页
        $totals = $model->table("jl_kaoqin_record")->where($map)->count();
		
        $Page = new Page($totals, 10);
        $show = $Page->show();
        $this->assign('page', $show);	

		
		$order = " id DESC";
		$datas = $model->table("jl_kaoqin_record")->where($map)->join(' LEFT JOIN jl_user ON jl_user.user_id = jl_kaoqin_record.user_id')->limit($Page->firstRow.','.$Page->listRows)->order($order)->select();
	//	echo $model->getlastsql();
	//	var_dump($datas);

		//重新整理
		foreach($datas as $k=>$v){
			$datas[$k]['hour_num'] = intval((strtotime($v['endtime'])- strtotime($v['starttime']))/3600);
			
		}


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
				var_dump($out);
				$this->display("record_count_all_user");
				exit;
				
			}else{
				//具体到一个人
				$out['user_id'] = $user_id;
				$result = $model->table("jl_user")->where("user_id=".$user_id)->field("name")->find();
				$out['name'] = $result['name'];
				//请假
				$map = array();
				$map['user_id']=$user_id;
				$map['type']=1;
				$map['the_month'] = $the_month;
				
				$qj_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
				$out['qj_count'] = intval($qj_count);//单位：小时
				
				//加班
				$map = array();
				$map['user_id']=$user_id;
				$map['type']=2;
				$jb_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
				$out['jb_count'] = intval($jb_count);//单位：小时
				
				
				//出差
				$map = array();
				$map['user_id']=$user_id;
				$map['type']=3;
				$cc_count = $model->table("jl_kaoqin_record")->where($map)->sum('hour_num');
				$out['cc_count'] = intval($cc_count);//单位：小时
				
				
				//年假剩余，每年5天年假
				
				
				//var_dump($out);
				$this->assign("datas",$out);
				$this->display("record_count_one_user");
				exit;
			}
			
		}


		$Page->parameter = implode('&', $params);
		$this->assign('page',$Page->show());
		$this->assign("datas",$datas);
		$this->display("record");
	}
	
	public function record_add(){
		if (IS_POST) {
			$kq_model = new Model();
	//		$kq_config_model = new Model();
			
			//读取考勤配置
	//		$kq_config = $kq_config_model->query("select * from jl_kaoqin_config where is_used=1 limit 1");
	//		$config_datas = $kq_config[0];
	//		var_dump($config_datas);
	//		$starttime = $config_datas['starttime'];//上班时间
	//		$endtime = $config_datas['endtime'];//下班时间
			
			
			$tm = trim($_POST['starttime']);
			$datas = array();
			$datas['user_id'] = intval($_POST['user_id']);
			$datas['starttime'] = trim($_POST['starttime']);//开始时间
			$datas['endtime'] = trim($_POST['endtime']);//结束时间
			$datas['the_date'] = date("Y-m-d",strtotime($tm));//年月日
			$datas['the_month'] = date("Y-m",strtotime($tm));//年月日
			$datas['the_day'] = date("d",strtotime($tm));//日
			$datas['the_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($tm));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['type'] = intval($_POST['type']);
			$datas['title'] = '标题';	
			$datas['content'] = trim($_POST['content']);	
		//	var_dump($datas);exit;
			$result = $kq_model->table("jl_kaoqin_record")->data($datas)->add();
			//var_dump($result);
			alert('success', '新增考勤记录成功',U('Kaoqin/record','content='.$_GET['content']));
			
        } else {
            $model = new Model();
			//全部用户，下拉列表
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
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
            $this->display("record_edit");
        }
		
	}
	
	
	public function record_edit(){	
		if (IS_POST) {
			$kq_model = new Model();
			$tm = trim($_POST['starttime']);
			$id = intval($_POST['id']);
			
			$datas = array();
			$datas['user_id'] = intval($_POST['user_id']);
			$datas['starttime'] = trim($_POST['starttime']);//开始时间
			$datas['endtime'] = trim($_POST['endtime']);//结束时间
			$datas['the_date'] = date("Y-m-d",strtotime($tm));//年月日
			$datas['the_month'] = date("Y-m",strtotime($tm));//年月日
			$datas['the_day'] = date("d",strtotime($tm));//日
			$datas['the_time'] = date("H:i:s",strtotime($tm));//几点几分几秒
			$datas['create_time'] = date("Y/m/d h:i:s", mktime());
			
					$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
					$week_num = date("w",strtotime($tm));//星期几
			$datas['week'] = $weekArr[$week_num];
			$datas['type'] = intval($_POST['type']);
			$datas['title'] = '标题';	
			$datas['content'] = trim($_POST['content']);	
			$datas['hour_num'] = intval((strtotime(trim($_POST['endtime']))- strtotime(trim($_POST['starttime'])))/3600);
			
			$result = $kq_model->table("jl_kaoqin_record")->where("id=".$id)->save($datas);
			//var_dump($result);
			alert('success', '更新记录成功',U('Kaoqin/record','content='.$_GET['content']));
			
        } else {
			$id = intval($_GET['id']);
			
            $model = new Model();
			$datas = $model->table("jl_kaoqin_record")->where("id=".$id)->find();
			
			if ($datas){
					
			} else {
				
			}
			
			//全部用户，下拉列表
			$u_str = "select A.name,A.user_id,B.kqj_user_id FROM jl_user AS A LEFT JOIN jl_kaoqin_system_userid AS B ON A.user_id = B.user_id where A.status>0 and A.user_id>1 ";
			$user_datas = $model->query($u_str);
			foreach($user_datas as $k=>$v){
			//	if (!$v['kqj_user_id']){
			//		$user_datas[$k]['kqj_user_id']=9000 + $k;//有些用户没有考勤id，会造成默认选中	
			//	}	
			}
			$this->assign("users_list",$user_datas);//用户下拉列表
			
			//var_dump($datas);
			
            $this->assign("datas",$datas);
            $this->display("record_edit");
        }
	
	
	}
	
	
	
	
	
	
	
	
	
	
}