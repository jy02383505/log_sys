<?php 
// 
class PerformanceAction extends Action {
    
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','widget_edit','widget_delete','widget_add')
		);
		B('Authenticate', $action);
	}
	
	public function index(){
		$kaoqin = M('kaoqin')->select();
		foreach($kaoqin as $kq){
			if($kq[sign_year]){
				$years[$kq[sign_year]] = $kq[sign_year];
			}
			if($kq[user_id]){
				$users[$kq[user_id]] = $kq[user_id];
			}
		}
		foreach($users as &$user){
			$userInfo[] = M('user')->where("user_id=$user and status=1")->find($user);
		}
		sort($years);
		$this->years = $years;
		$this->userInfo = $userInfo;
		$this->display();
	}

	public function ajax_getWorkLateLeaveEarly(){
		$user_id = $_POST[user_id] ? : die('用户参数不正确！');
		$q_year = $_POST[q_year] ? : die('日期参数不正确！');
		$q_month = $_POST[q_month] ? : die('日期参数不正确！');
		$q_ym = $q_year . '-' . $q_month;
		$totalDayNum = M('kaoqinConfigDays')->where("year = '$q_year' and month = '$q_month'")->count();
		$worklessDayNum = M('kaoqinConfigDays')->where("year = '$q_year' and month = '$q_month' and start is null and end is null and state = 0")->count();
		$halfWorkDayNum = M('kaoqinConfigDays')->where("year = '$q_year' and month = '$q_month' and end = '12:00'")->count();
		$totalDays = M('kaoqinConfigDays')->where("year = '$q_year' and month = '$q_month'")->select();
		// 应出勤天数 = 本月的总天数 - (!start&&!end) - 0.5*(end == '12:00')
		$workDayNum = $totalDayNum - $worklessDayNum - 0.5 * $halfWorkDayNum;
		$result[workDayNum] = $workDayNum;

		// 外出登记信息
		$workOutside = M('kaoqinWorkOutside')->where("out_date like '$q_ym%' and user_id = $user_id")->getField('out_date', true);

		// 迟到早退次数
		$kaoqin = M('kaoqin')->where("sign_month = '$q_ym' and user_id = $user_id")->select();
		foreach($kaoqin as $kq){
			foreach($totalDays as $base){
				if($kq[cdzt_minutes] != '0' && $kq[sign_date] == $base[date] && $base[start] && $base[end] && $base[state] == '1'){
					++$cdztNum;
					$cdztInfo[] = $kq[sign_date].','.$kq[week].','.$kq[cdzt_minutes];
				}
			}
		}
		// 请假记录
		$leaves = M('kaoqinQinjia')->where("the_month = '$q_ym' and user_id = $user_id")->getField('the_date', true);

		foreach($cdztInfo as &$cdzt){
			$cdztArr = explode(',', $cdzt);
			// 迟到早退去除外出的情况
			foreach($workOutside as $o_date){
				if($cdztArr[0] == $o_date){
					--$cdztNum;
					$cdzt .= ',[外出了]';
				}
			}
			// 迟到早退去除请假的情况
			foreach($leaves as $leave){
				if($cdztArr[0] == $leave){
					--$cdztNum;
					$cdzt .= ',[请假了]';
				}
			}
		}
		$result[cdztNum] = $cdztNum;
		$result[cdztInfo] = $cdztInfo;

		// 事假天数
		// $affairLeaveNum = M('kaoqinQinjia')->where("the_month = '$q_ym' and user_id = $user_id and type=1")->count();
		$affairLeaveNum = M('kaoqinQinjia')->where("the_month = '$q_ym' and user_id = $user_id and type=1")->sum('hour_num') / 8;
		$result[affairLeaveNum] = $affairLeaveNum;

		// 病假天数
		$sickLeaveNum = M('kaoqinQinjia')->where("the_month = '$q_ym' and user_id = $user_id and type=2")->sum('hour_num') / 8;
		$result[sickLeaveNum] = $sickLeaveNum;

		// 旷工
		// 应出勤日期数组
		foreach($totalDays as $base1){
			// if($base1[state] != '0'){
			// 	$workDays[] = $base1[date];
			// }
			if($base1[start] && $base1[end]){
				$workDays[] = $base1[date];
			}else if(!$base1[start] && !$base1[end] && $base1[state] == '2'){
				$workDays[] = $base1[date];
			}
		}
		// 实际出勤日期数组
		// 当月工作安排的架子，应出勤日
		foreach($totalDays as $oneDay){
			if($oneDay[start] && $oneDay[end] == '17:30'){
				$wholeMonth[$oneDay[date].'|'.$oneDay[start].'|'.$oneDay[end].'|workday'] = array();
			}
			if($oneDay[start] && $oneDay[end] == '12:00'){
				$wholeMonth[$oneDay[date].'|'.$oneDay[start].'|'.$oneDay[end].'|weekend'] = array();
			}
			if(!$oneDay[start] && !$oneDay[end] && $oneDay[state] == '2'){
				// $wholeMonth[$oneDay[date].'|'.'08:30'.'|'.'17:30'.'|workday'] = array();
				$wholeMonth[$oneDay[date].'|'.$oneDay[start].'|'.$oneDay[end].'|workday'] = array();
			}
			if(!$oneDay[start] && !$oneDay[end] && $oneDay[state] != '2'){
				$wholeMonth[$oneDay[date].'|'.$oneDay[start].'|'.$oneDay[end].'|weekend'] = array();
			}
		}
		// 在架子当中填入某位员工的考勤数据
		foreach($totalDays as $workDay){
			if($workDay[start] && $workDay[end] == '17:30'){
				foreach($kaoqin as $kq1){
					if($workDay[date] == $kq1[sign_date]){
						// 按单日汇总(普通工作日)
						$wholeMonth[$workDay[date].'|'.$workDay[start].'|'.$workDay[end].'|workday'][] = $kq1;
					}
				}
			}else if(!$workDay[start] && !$workDay[end] && $workDay[state] == '2'){
				foreach($kaoqin as $kq1){
					if($workDay[date] == $kq1[sign_date]){
						// 按单日汇总(特殊情况工作日)
						$wholeMonth[$workDay[date].'|||workday'][] = $kq1;
					}
				}
			}else{
				foreach($kaoqin as $kq1){
					if($workDay[date] == $kq1[sign_date]){
						// 按单日汇总(周六也归入周末)
						$wholeMonth[$workDay[date].'|'.$workDay[start].'|'.$workDay[end].'|weekend'][] = $kq1;
					}
				}
			}
		}
		$result[wholeMonth] = $wholeMonth;

		$s = M('user')->where("user_id = $user_id")->getField('name').':<br>';
		$evection = M('kaoqinChucai')->where("the_month = '$q_ym' and user_id = $user_id")->select();
		// 出差时间段[时间戳]
		foreach($evection as $ev){
			$tmp_ev[start] = strtotime($ev[starttime]);
			$tmp_ev[end] = strtotime($ev[endtime]);
			$allEvection[] = $tmp_ev;
		}
		foreach($wholeMonth as $key => $sign){
			$keyArr = explode('|', $key);
			if($keyArr[3] == 'workday'){
				$keyArr[1] = $keyArr[1] ? : '08:30';
				$keyArr[2] = $keyArr[2] ? : '17:30';
			}
			$start = $keyArr[0].' '.$keyArr[1];
			$end = $keyArr[0].' '.$keyArr[2];
			if(in_array($keyArr[0], $workDays)){
				if(count($sign) == 0){
					$s .= $keyArr[0].' 全天没有签到签退记录，可能有旷工嫌疑！未结合出差、请假、外出情况综合评定！<br>';
					// 全天没有打卡，疑似旷工记录
					$absenteeism[] = $keyArr[0];
				}else if(count($sign) < 2){
					$absenteeism[] = $keyArr[0];
					$s .= $keyArr[0].' '.$sign[0][sign_time].' 未签到or未签退！未结合出差、请假、外出情况综合评定！<br>';
				}else{
					foreach($sign as $sign1){
						if($sign1[type] == '0' && $sign1[kqj_date_int] * 1 >= strtotime($start)){
							$s .= $keyArr[0].' '.$sign1[sign_time].' 迟到嫌疑！<br>';
						}
						if($sign1[type] == '1' && $sign1[kqj_date_int] * 1 <= strtotime($end)){
							$s .= $keyArr[0].' '.$sign1[sign_time].' 早退嫌疑！<br>';
						}
					}
				}
			}
		}
		$result[msg] = $s;

		$result[leaves] = $leaves;
		// 刨去出差、请假、外出之后的旷工记录
		if($absenteeism){
			foreach($absenteeism as $k_absence => &$absence){
				foreach($allEvection as $ev1){
					if(strtotime($absence) >= $ev1[start] && strtotime($absence) <= $ev1[end]){
						unset($absenteeism[$k_absence]);
					}
				}
				// 还得刨去外出
				if(in_array(trim($absence), $workOutside)){
					unset($absenteeism[$k_absence]);
				}
				// 刨去请假
				if(in_array(trim($absence), $leaves)){
					unset($absenteeism[$k_absence]);
				}
			}
		}
		$result[absenteeism] = $absenteeism;

		// 旷工数
		// 12点下班的情况
		$halfNum = 0;
		foreach($absenteeism as $absence1){
			foreach($wholeMonth as $key2 => $oneDay1){
				$keyArr2 = explode('|', $key2);
				if($absence1 == $keyArr2[0] and $keyArr2[2] == '12:00'){
					++$halfNum;
				}
			}
		}
		$absenteeismNum = count($absenteeism) - ($halfNum * 0.5);
		$result[absenteeismNum] = $absenteeismNum;
		$result[absenteeism] = $absenteeism;

		// 加班统计评分
		// 本月应出勤小时数
		$workHours = $workDayNum * 7.5;
		$result[workHours] = $workHours;
		// 主动加班时长
		$overtimeHoursNopay = 0;
		// 带薪加班时长
		$overtimeHoursPaid = 0;
		foreach($wholeMonth as $key1 => $value1){
			$keyArr1 = explode('|', $key1);
			if($keyArr1[3] == 'workday'){
				// 考虑特殊工作日，没有下班时间，这里手动给出17:30为下班时间
				$keyArr1[2] = $keyArr1[2] ? : '17:30';
				$off_time = $keyArr1[0].' '.$keyArr1[2];
				foreach($value1 as $value2){
					if($value2[type] == '1' and $value2[kqj_date_int] > strtotime($off_time) + 30 * 60){
						$overtimeHoursNopay += intval(($value2[kqj_date_int] - strtotime($off_time)) / 30 / 60) * 0.5;
					}
				}
			}
			if($keyArr1[3] == 'weekend' and $value1){
				// 12:00下班的情况
				if($keyArr1[2] == '12:00'){
					$off_time = $keyArr1[0].' '.$keyArr1[2];
					// 全天只有一次打卡，且为签到的情况
					if(count($value1) < 2){
						$otInfo .= $keyArr1[0] . '考勤记录残缺，请先在考勤页面当中补全当日考勤信息！<br>';
						continue;
					}
					foreach($value1 as $value3){
						// 签退时间大于当天下班时间的统计带薪加班小时数
						if($value3[type] == '1' and $value3[kqj_date_int] > strtotime($off_time) + 30 * 60){
							$overtimeHoursPaid += (intval(($value3[kqj_date_int] - strtotime($off_time)) / 30 / 60) * 0.5);
							$otInfo .= $off_time . ' 下班时间：' . date('Y-m-d H:i:s', $value3[kqj_date_int]) . ' = ' . $overtimeHoursPaid . '小时<br>';
						}
					}
				}
				// 全天休息的情况
				if(!$keyArr1[2]){
					if(count($value1) < 2){
						$otInfo .= $keyArr1[0] . '考勤记录残缺，请先在考勤页面当中补全当日考勤信息！<br>';
						continue;
					}
					foreach($value1 as $value4){
						if($value4[type] == '0' and count($value4) > 1){
							$startWeekend = $value4[kqj_date_int];
						}
						if($value4[type] == '1' and count($value4) > 1){
							$endWeekend = $value4[kqj_date_int];
						}
					}
					$overtimeHoursPaid += intval(($endWeekend - $startWeekend) / 30 / 60) * 0.5;
					$otInfo .= date('Y-m-d H:i:s', $startWeekend) . ' 至 ' . date('Y-m-d H:i:s', $endWeekend) . ' = ' . $overtimeHoursPaid . '小时<br>';
				}
			}

		}
		$result[otInfo] = $otInfo;
		$result[overtimeHoursNopay] = $overtimeHoursNopay;
		$result[overtimeHoursPaid] = $overtimeHoursPaid;
		die(json_encode($result));
	}
}
