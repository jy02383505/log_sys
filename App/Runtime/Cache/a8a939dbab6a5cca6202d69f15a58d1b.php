<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="utf-8">
	<title><?php echo C('defaultinfo.name');?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<meta name="description" content=""/>
	<meta name="author" content="<?php echo L('AUTHOR');?>"/>
	<link type="text/css" href="__PUBLIC__/css/bootstrap.min.css" rel="stylesheet" />
	<link type="text/css" href="__PUBLIC__/css/bootstrap-responsive.min.css" rel="stylesheet">
	<link type="text/css" href="__PUBLIC__/css/jquery-ui-1.10.0.custom.css" rel="stylesheet" />
	<link type="text/css" href="__PUBLIC__/css/font-awesome.min.css" rel="stylesheet" />
	<link href="__PUBLIC__/css/docs.css" rel="stylesheet"/>
	<link rel="shortcut icon" href="__PUBLIC__/ico/favicon.png"/>
    <script type="text/javascript">
        var browserInfo = {browser:"", version: ""};
        var ua = navigator.userAgent.toLowerCase();
        if (window.ActiveXObject) {
            browserInfo.browser = "IE";
            browserInfo.version = ua.match(/msie ([\d.]+)/)[1];
            if(browserInfo.version <= 7){
                if(confirm("您的ie浏览器版本过低，建议使用chorme浏览器，\n点击【确定】转到下载页面")){}
                location.href = 'http://www.google.cn/intl/zh-CN/chrome/browser/';
            }
        }
    </script>
	<script src="__PUBLIC__/js/jquery-1.9.0.min.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/jquery-ui-1.10.0.custom.min.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/WdatePicker.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/gototop.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/5kcrm_zh-cn.js" type="text/javascript"></script>
	<script src="__PUBLIC__/js/5kcrm.js" type="text/javascript"></script>
	<!--[if lte IE 6]>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/bootstrap-ie6.css">
	<![endif]-->
	<!--[if lte IE 7]>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/ie.css">
	<![endif]-->
	<!--[if IE 7]>
	<link rel="stylesheet" type="text/css" href="__PUBLIC__/css/font-awesome-ie7.min.css" />
	<![endif]-->
	<!--[if lt IE 9]>
	<link type="text/css" href="__PUBLIC__/css/jquery.ui.1.10.0.ie.css" rel="stylesheet"/>
	<![endif]-->	
	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<script src="__PUBLIC__/js/respond.min.js"></script>
	<![endif]-->
</head>

<body data-spy="scroll" data-target=".bs-docs-sidebar" data-twttr-rendered="true">
<div class="navbar navbar-inverse navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<div style="line-height: 40px;padding-right: 5px;padding-top: 6px;" class="pull-left"><img src="__PUBLIC__/img/logomini.png"/></div>
			<a class="brand" href="<?php echo (__APP__); ?>" alt="<?php echo C('defaultinfo.description');?>"><?php echo C('defaultinfo.name');?></a>
			<?php echo W("Navigation");?>
		</div> 
	</div>
</div>




<style>
	.error{border:#FF0000 solid 1px;}
</style>


<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
		<h4>考勤统计详情</h4>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>


	<div class="row">
		<div class="span12">
			<ul class="nav pull-left">
				<li class="pull-left hide"><a id="delete"  class="btn" style="margin-right: 5px;"><i class="icon-remove"></i><?php echo L('DELETE');?></a></li>
				<li class="pull-left">
					<form class="form-inline" id="searchForm"  action="" method="get">
                    <input type="hidden" name="m" value="count"/><!--模型：请假-->
                    <input type="hidden" name="a" value="count_one_people_days"/><!--模型：列表-->
					<ul class="nav pull-left">
                        <li class="pull-left" style="margin-right:10px;">
                            <select  style="width:auto" name="the_year" id="the_year">
                              <option value="">年份</option>
                              <option value="2014" <?php if(($the_year) == "2014"): ?>selected<?php endif; ?> >2014</option>
                              <option value="2015" <?php if(($the_year) == "2015"): ?>selected<?php endif; ?> >2015</option>
                              <option value="2016" <?php if(($the_year) == "2016"): ?>selected<?php endif; ?> >2016</option>
                              <option value="2017" <?php if(($the_year) == "2017"): ?>selected<?php endif; ?> >2017</option>
                              <option value="2018" <?php if(($the_year) == "2018"): ?>selected<?php endif; ?> >2018</option>
                              <option value="2019" <?php if(($the_year) == "2019"): ?>selected<?php endif; ?> >2019</option>
                              <option value="2020" <?php if(($the_year) == "2020"): ?>selected<?php endif; ?> >2020</option>
                            </select>
                            <span></span>
                        </li>
                        <li class="pull-left " style="margin-right:10px;display:block;">
                            <select  style="width:auto" name="the_month" id="the_month">
                              <option value="">月份</option>
                              <option value="01" <?php if(($the_month) == "01"): ?>selected<?php endif; ?> >1月份</option>
                              <option value="02" <?php if(($the_month) == "02"): ?>selected<?php endif; ?> >2月份</option>
                              <option value="03" <?php if(($the_month) == "03"): ?>selected<?php endif; ?> >3月份</option>
                              <option value="04" <?php if(($the_month) == "04"): ?>selected<?php endif; ?> >4月份</option>
                              <option value="05" <?php if(($the_month) == "05"): ?>selected<?php endif; ?> >5月份</option>
                              <option value="06" <?php if(($the_month) == "06"): ?>selected<?php endif; ?> >6月份</option>
                              <option value="07" <?php if(($the_month) == "07"): ?>selected<?php endif; ?> >7月份</option>
                              <option value="08" <?php if(($the_month) == "08"): ?>selected<?php endif; ?> >8月份</option>                                  
                              <option value="09" <?php if(($the_month) == "09"): ?>selected<?php endif; ?> >9月份</option>
                              <option value="10" <?php if(($the_month) == "10"): ?>selected<?php endif; ?> >10月份</option>
                              <option value="11" <?php if(($the_month) == "11"): ?>selected<?php endif; ?> >11月份</option>
                              <option value="12" <?php if(($the_month) == "12"): ?>selected<?php endif; ?> >12月份</option>
                            </select>
                        </li>
                        <li class="pull-left " style="margin-right:10px;display:block;">
                            <select  style="width:auto" name="user_id" id="user_id">
                            <option value="">用户</option>
                            <?php if(is_array($users_list)): $i = 0; $__LIST__ = $users_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["user_id"]); ?>" <?php if(($user_id) == $vo["user_id"]): ?>selected<?php endif; ?>  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>

                         <li class="pull-left" style="padding-right:10px;">
                        	<button type="button" class="btn" name="btn_tj" id="btn_tj"> <img src="__PUBLIC__/img/search.png"/>  统计</button>
                        </li>                       
                    
					</ul>
					</form>
				</li>
			</ul>
			<div class="pull-right">
				<div class="btn-group hide">
					<button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i class="icon-wrench"></i>  &nbsp;<?php echo L('SCHEDULING_TOOLS');?><span class="caret"></span></button>
					<ul class="dropdown-menu">
						<!--<li><a href="javascript:return(0);" id="import_excel"  class="link">导入日程</a></li>-->
						<li><a href="<?php echo U('Kaoqin/excelexport');?>"  onclick="return window.confirm(&quot;<?php echo L('ARE_YOU_SURE_YOU_WANT_TO_EXPORT_THE_SCHEDULE');?> &quot;);" class="link"><i class="icon-download"></i>&nbsp;<?php echo L('EXPORT_THE_SCHEDULE');?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>



	<div class="row">
		<div class="span12" style="padding-bottom:10px;border-bottom:#CCC 1px solid;">
			<h4>姓名：<?php echo $user['name'];?> &nbsp;&nbsp;&nbsp;&nbsp;考勤时间：<?php echo $_GET['the_year'];?> - <?php echo $_GET['the_month'];?></h4>
			<p></p>
		</div>
		<div class="span12">
                <table class="table table-striped" width="100%">
                <tr>       
                <td width="80">日期</td>
                <td width="60">星期</td>       
                <td width="120">规定打卡时间</td>
                <td width="70">签到时间</td>
                <td width="70">签退时间</td>
                <td width="40">迟到</td>
                <td width="40">早退</td>
                <td width="40">未打卡</td>
                <td width="40" title="主动加班">主动</td>
                <td width="40">日志</td>
                <td width="40">请假</td>
                <td width="40">加班</td>
                <td width="40">出差</td>
                <td>备注</td>
                </tr>
			<?php
 $kaoqinModel = D("Kaoqin"); if (!$datas){ ; ?>

                <tr>                    
                <td colspan="10">
                	本月未找到考勤时间设置，请先前往设置
                	<a href="<?php echo U('Kaoqinconfig/config_year_month_day_time');?>"><button type="button" class="btn btn-primary" title="设置每月考勤时间">时间设置</button></a>
                </td>
                </tr>

            <?php
 }else{ $count_weidaka = 0; $count_chidao = 0; $count_zhaotui = 0; $count_zhudongjiaban = 0; $count_sunday = 0; $count_buxudaka = 0; $count_log_this_day = 0; $count_jiaban_this_day = 0; $is_chucai = ''; $model_tp = new Model(); foreach($datas as $k=>$v){ $year_month_day = $v['year']."-".$v['month'].'-'.$v['day']; $map = array(); $map['role_id'] = intval($_GET['userid']); $map['the_date'] = $year_month_day; $count_log_this_day = $model_tp->table("jl_log")->where($map)->count(); $map = array(); $map['user_id'] =intval($_GET['userid']); $map['the_date'] = $year_month_day; $map['type'] = 1; $map['checked'] = 1; $qinjia_days_1 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $qinjia_days_1 = round($qinjia_days_1/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_date'] = $year_month_day; $map['type'] = 2; $map['checked'] = 1; $qinjia_days_2 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $qinjia_days_2 = round($qinjia_days_2/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_date'] = $year_month_day; $map['type'] = 3; $map['checked'] = 1; $qinjia_days_3 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $qinjia_days_3 = round($qinjia_days_3/8,1); $qinjia_days = $qinjia_days_1 + $qinjia_days_2 +$qinjia_days_3; $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_date'] = $year_month_day; $map['checked'] = 1; $count_jiaban_this_day = $model_tp->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num'); $count_jiaban_this_day = round($count_jiaban_this_day/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_date'] = $year_month_day; $map['checked'] = 1; $str = "user_id = ".intval($_GET['userid']) ." and checked=1 and ( (starttime = '$year_month_day' or endtime = '$year_month_day') or (starttime < '$year_month_day' and endtime > '$year_month_day') )"; $today_chucai = $model_tp->table("jl_kaoqin_chucai")->where($str)->find(); if ($today_chucai){ $is_chucai = '出差'; }else{ $is_chucai = ''; } switch ($v['state']){ case 0: ?>
                <tr>                    
                <td><?=$v['date'];?></td>
                <td><?=$v['week'];?></td>
                <td>不上班</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><a title="单位：天"><?php if ($count_jiaban_this_day){echo $count_jiaban_this_day;}?></a></td>
                <td><?php echo $is_chucai;?></td>
                <td><?=$v['content'];?></td>
                </tr>
                            <?php
 break; case 1: $map_kq = array(); $map_kq['user_id'] = $userid; $map_kq['sign_date'] = $v['date']; $count_kq = $kaoqinModel->where($map_kq)->order("kqj_date_int DESC")->count(); switch($count_kq){ case 0: $count_weidaka++; $zd_jiaban_hours = ""; ?>
                <tr>   
                <td><?=$v['date'];?></td>
                <td><?=$v['week'];?></td>
                <td><?=$v['start'];?> - <?=$v['end'];?></td> 
                <td colspan="4">无打卡记录</td>
                <td>未打卡</td>
                <td><?php if ($zd_jiaban_hours){echo $zd_jiaban_hours;}?></td>
                <td><?=$count_log_this_day;?></td>
                <td>
                <?php  if ($qinjia_days){ ?>
                <a title="事假：<?php echo $qinjia_days_1;?>;病假：<?php echo $qinjia_days_2;?>;调休：<?php echo $qinjia_days_3;?>天"><?php echo $qinjia_days;?></a>
                <?php
 } ?>
                </td>
                <td><a title="单位：天"><?php if ($count_jiaban_this_day){echo $count_jiaban_this_day;}?></a></td>
                <td><?php echo $is_chucai;?></td>
                <td></td>
                </tr>
                                    <?php
 break; case 1: $datas_kq = $kaoqinModel->where($map_kq)->order("kqj_date_int DESC")->find(); $count_dakayici++; $datas_kq_qiandao = $kaoqinModel->where($map_kq)->order("kqj_date_int ASC")->find(); $zd_jiaban_hours = ""; ?>
                <tr>                    
                <td><?=$v['date'];?></td>
                <td><?=$v['week'];?></td>
                <td><?=$v['start'];?> - <?=$v['end'];?></td>
                <td><?=$datas_kq_qiandao['sign_time'];?></td>
                <td><?php echo '<?'; ?>
 //=$datas_kq_qiantui['sign_time'];//签退时间?></td>
                <td><?php if ($datas_kq_qiandao['sign_time'] > $v['start']){ echo "迟到";$count_chidao++;}?></td>
                <td><?php if ($datas_kq_qiantui['sign_time'] < $v['end']){ echo "早退";}?></td>
                <td></td>
                <td><?php if ($zd_jiaban_hours){echo $zd_jiaban_hours;}?></td>
                <td><?=$count_log_this_day;?></td>
                <td >
                <?php  if ($qinjia_days){ ?>
                <a title="事假：<?php echo $qinjia_days_1;?>;病假：<?php echo $qinjia_days_2;?>;调休：<?php echo $qinjia_days_3;?>天"><?php echo $qinjia_days;?></a>
                <?php
 } ?>
                </td>
                <td><a title="单位：天"><?php if ($count_jiaban_this_day){echo $count_jiaban_this_day;}?></a></td>
                <td><?php echo $is_chucai;?></td>
                <td><?=$v['content'];?></td>
                </tr>
                                    <?php
 break; default: $datas_kq_qiandao = $kaoqinModel->where($map_kq)->order("kqj_date_int ASC")->find(); $datas_kq_qiantui = $kaoqinModel->where($map_kq)->order("kqj_date_int DESC")->find(); if ( (strtotime($datas_kq_qiantui['sign_time'])) - strtotime($v['end']) > 0){ $zd_jiaban_hours = (strtotime($datas_kq_qiantui['sign_time']) - strtotime($v['end'])) / 3600; $zd_jiaban_hours = round($zd_jiaban_hours,1); $f = end(explode('.',$zd_jiaban_hours)); $the_int = reset(explode('.',$zd_jiaban_hours)); if ($f>5){ $zd_jiaban_hours = $the_int + 0.5; } else { $zd_jiaban_hours = $the_int; } $count_zhudongjiaban = $count_zhudongjiaban +$zd_jiaban_hours; }else{ $zd_jiaban_hours = ""; } ?>
                <tr>                    
                <td><?=$v['date'];?></td>
                <td><?=$v['week'];?></td>
                <td><?=$v['start'];?> - <?=$v['end'];?></td>
                <td><?=$datas_kq_qiandao['sign_time'];?></td>
                <td><?=$datas_kq_qiantui['sign_time'];?></td>
                <td>
                <?php  if ($datas_kq_qiandao['sign_time'] > $v['start']){ echo "迟到";$count_chidao++; } ?></td>
                <td><?php if ($datas_kq_qiantui['sign_time'] < $v['end']){ echo "早退";}?></td>
                <td></td>
                <td><?php if ($zd_jiaban_hours){echo $zd_jiaban_hours;}?></td>
                <td><?=$count_log_this_day;?></td>
                <td>
                    <?php  if ($qinjia_days){ ?>
                	<a title="事假：<?php echo $qinjia_days_1;?>;病假：<?php echo $qinjia_days_2;?>;调休：<?php echo $qinjia_days_3;?>天"><?php echo $qinjia_days;?></a>
                    <?php
 } ?>
                </td>
                <td><a title="单位：天"><?php if ($count_jiaban_this_day){echo $count_jiaban_this_day;}?></a></td>
                <td><?php echo $is_chucai;?></td>
                <td><?=$v['content'];?></td>
                </tr>
                                <?php
 } break; case 2: ?>
                <tr>                    
                <td><?=$v['date'];?></td>
                <td><?=$v['week'];?></td>
                <td>不打卡</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?php echo $is_chucai;?></td>
                <td><?=$v['content'];?></td>
                </tr>
                            
                            <?php
 break; default: ; } } } ?>
                 </table>       
             <?php
 if ($datas){ ?>    
                <table class="table table-striped" width="100%">
                <tr>       
                <td colspan="3"><h4>统计 - 考勤</h4></td>
                <tr>
             
                <tr>       
                <td width="80">要求打卡天数</td>
                <td width="100"><?php echo $count_yinqiandao_num;?> 天</td>
                <td >签到与签退次数永远应该相等，均为每工作日签到一次，签退一次。</td>
                <tr>             
                
                <tr>       
                <td>未打卡</td>
                <td><?php echo $count_weidaka;?> 天</td>
                <td >全天都未打卡，不包括星期天和节假日</td>
                <tr>
                
                <tr>       
                <td>漏打卡</td>
                <td><?php echo $count_dakayici;?> 次</td>
                <td >一天仅打了一次卡的情况（全天只有一次打卡的，均被视为签到记录处理）</td>
                <tr>
                
                
                <tr>       
                <td>迟到</td>
                <td><?php echo $count_chidao;?> 次</td>
                <td ></td>
                <tr>               
                
                <tr>       
                <td>早退</td>
                <td><?php echo $count_zhaotui;?> 次</td>
                <td ></td>
                <tr> 
                
                <tr>       
                <td>主动加班</td>
                <td><?php echo $count_zhudongjiaban;?> 小时</td>
                <td ></td>
                <tr>
                
                
                <tr>
                <td colspan="3"><h4>统计 - 日志</h4></td>
                <tr>
                
                <tr>
                <td >应写日志天数</td>
                <td ><?php echo $count_yinqiandao_num;?> 天</td>
                <td ></td>  
                <tr>                
                
                <tr>
                <td >实写日志天数</td>
                <td ><?php echo $log_month_count_days;?> 天</td>
                <td >某日只要有日志，天数加1</td>  
                <tr>                
                               
                <tr>
                <td >日志总数</td>
                <td ><?php echo $log_month_count_total;?> 个</td>
                <td >全部日志总数，一天有多少计算多少</td>  
                <tr>                

                <tr>
                <td ></td>
                <td ></td>
                <td ></td>  
                <tr>                 
   
<?php
 $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['checked'] = 1; $month_jiaban_hours_1 = $model_tp->table("jl_kaoqin_jiaban")->where($map)->sum('hour_num'); $month_jiaban_hours_1 = round($month_jiaban_hours_1/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['sign_month'] = $year_month; $month_jiaban_hours_2 = $model_tp->table("jl_kaoqin")->where($map)->sum('jiaban_hours'); $month_jiaban_hours_2 = round($month_jiaban_hours_2,1); ?>
   
                
                 
                <tr>
                <td colspan="3"><h4>统计 - 加班</h4></td>
                <tr>               
                
                <tr>
                <td >正常加班</td>
                <td ><?php echo $month_jiaban_hours_1;?> 天</td>
                <td ></td>  
                <tr>                
                
                
                <tr>
                <td >主动加班</td>
                <td ><?php echo $month_jiaban_hours_2;?> 小时</td>
                <td ></td>  
                <tr> 
                 
                 
                            <?php
 $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 1; $map['checked'] = 1; $year_qinjia_days_1 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $year_qinjia_days_1 = round($year_qinjia_days_1/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 2; $map['checked'] = 1; $year_qinjia_days_2 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $year_qinjia_days_2 = round($year_qinjia_days_2/8,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 3; $map['checked'] = 1; $year_qinjia_days_3 = $model_tp->table("jl_kaoqin_qinjia")->where($map)->sum('hour_num'); $year_qinjia_days_3 = round($year_qinjia_days_3/8,1); ?>         
                 
                 
                <tr>
                <td colspan="3"><h4>统计 - 请假</h4></td>
                <tr>               
                
                <tr>
                <td >事假</td>
                <td ><?php echo $year_qinjia_days_1;?> 天</td>
                <td ></td>  
                <tr>                
 
                <tr>
                <td >病假</td>
                <td ><?php echo $year_qinjia_days_2;?> 天</td>
                <td ></td>  
                <tr>                 
                
                <tr>
                <td >调休</td>
                <td ><?php echo $year_qinjia_days_3;?> 天</td>
                <td ></td>  
                <tr>                 

<?php
 $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 1; $map['checked'] = 1; $month_chucai_hours_1 = $model_tp->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_1 = round($month_chucai_hours_1,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 2; $map['checked'] = 1; $month_chucai_hours_2 = $model_tp->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_2 = round($month_chucai_hours_2,1); $map = array(); $map['user_id'] = intval($_GET['userid']); $map['the_month'] = $year_month; $map['type'] = 3; $map['checked'] = 1; $month_chucai_hours_3 = $model_tp->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_3 = round($month_chucai_hours_3,1); ?>                
                 
                <tr>
                <td colspan="3"><h4>统计 - 出差</h4></td>
                <tr>               
                
                <tr>
                <td >省内</td>
                <td ><?php echo $month_chucai_hours_1;?>天</td>
                <td ></td>  
                <tr>                
                
                <tr>
                <td >省外</td>
                <td ><?php echo $month_chucai_hours_2;?>天</td>
                <td ></td>  
                <tr> 
                               
                <tr>
                <td >一线城市</td>
                <td ><?php echo $month_chucai_hours_3;?>天</td>
                <td ></td>  
                <tr>                 
                                
                        
                </table>
                <?php
 } ?>
		</div>
	</div>
</div>

</body>
</html> 
        
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/WdatePicker.js"></script>


<script type="text/javascript">
$(function($) {
	
	// 判断英文字符
	jQuery.validator.addMethod("isEnglish", function(value, element) {       
         return this.optional(element) || /^[A-Za-z]+$/.test(value);       
    }, "只能包含英文字符。");
	
	
	$('#searchForm').validate({
		errorElement : 'span',
		errorClass : 'error',
		focusInvalid : true,  
        rules : {  
			the_year:{
				required: true,//名称带方括号验证不
			},
			the_month:{
				required: true,//
			},
			user_id:{
				required: true,//
			},
        },  
        messages : {
			the_year :{
				required :"年份必选",	
			},
			the_month :{
				required :"月份必选",	
			},
			user_id :{
				required :"用户必选",	
			}		
        },  
		

        highlight : function(element) {  
            $(element).closest('.form-group').addClass('has-error');  
        },  

        success : function(label) {  
            label.closest('.form-group').removeClass('has-error');  
            label.remove();  
        },  

        errorPlacement : function(error, element) {  
            element.parent('div').append(error);  
        },  
		submitHandler: function(form) {
			form.submit();
		}
	});
});
</script>

<script language="javascript">
$(document).ready(function() {
	
	//点击登陆，post提交
	$('#btn_tj').click(function(){
		url_tj = "<?php echo U('Count/count_one_people_days');?>";
		
		
		//select value
		var the_year = $("#the_year").val();
		var the_month = $("#the_month").val();
		var user_id = $("#user_id").val();
		if (the_year == ''){
			alert("年份必选");
			$("#the_year").focus();
			return;	
		}
		if (the_month==''){
			alert("月份必选");
			$("#the_month").focus();
			return;
		}
		if (user_id == ''){
			alert("用户必选");
			$("#user_id").focus();
			return;
		}
		url_tj = url_tj+"&the_year="+the_year+"&the_month="+the_month+"&userid="+user_id;
	//	alert(url_tj);
		window.location.href = url_tj;
	});

	//激活新增的登陆按钮的click事件，此方法已被放弃，改用on方法
	$("#btn_tj").live("click",function(){

	});
});
</script>