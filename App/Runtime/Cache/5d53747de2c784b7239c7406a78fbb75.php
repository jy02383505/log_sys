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



<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
		<h4>设置-每日考勤设置</h4>
	</div>
	
	<p class="view">
    <!--
    <b><?php echo L('VIEW_NAV');?></b>
	<img src=" __PUBLIC__/img/by_owner.png"/>  <a href="<?php echo U('event/index');?>" <?php if($_GET['by']== null): ?>class="active"<?php endif; ?>><?php echo L('ALL');?></a> |
	<a href="<?php echo U('event/index','by=me');?>" <?php if($_GET['by']== 'me'): ?>class="active"<?php endif; ?>><?php echo L('MY_RESPONSIBLE');?></a> |
	<a href="<?php echo U('event/index','by=sub');?>" <?php if($_GET['by']== 'sub'): ?>class="active"<?php endif; ?>><?php echo L('SUBORDINATE_RESPONSIBLE');?></a> | 
	<a href="<?php echo U('event/index','by=create');?>" <?php if($_GET['by']== 'create'): ?>class="active"<?php endif; ?>><?php echo L('MY_CREATE');?></a> &nbsp; &nbsp; &nbsp; &nbsp;
	<img src="__PUBLIC__/img/by_time.png"/> 
	<a href="<?php echo U('event/index','by=today');?>" <?php if($_GET['by']== 'today'): ?>class="active"<?php endif; ?>><?php echo L('TODAY_SCHEDULE');?></a> | 
	<a href="<?php echo U('event/index','by=week');?>" <?php if($_GET['by']== 'week'): ?>class="active"<?php endif; ?>><?php echo L('WEEK_SCHEDULE');?></a> | 
	<a href="<?php echo U('event/index','by=month');?>" <?php if($_GET['by']== 'month'): ?>class="active"<?php endif; ?>><?php echo L('MONTH_SCHEDULE');?></a> &nbsp; &nbsp; 
	<a href="<?php echo U('event/index','by=add');?>" <?php if($_GET['by']== 'add'): ?>class="active"<?php endif; ?>><?php echo L('RECENTLY_CREATED');?></a> | 
	<a href="<?php echo U('event/index','by=update');?>" <?php if($_GET['by']== 'update'): ?>class="active"<?php endif; ?>><?php echo L('RECENT_UPDATES');?></a>
    -->
	</p>
 <style>                  
 	.tb_left{}
 	.tb_left li{list-style-type:none;height:30px;line-height:30px;border-bottom:#CCC 1px solid;}
	.tb_left li.noborder{clear:both;height:30px;line-height:30px;border:none;}
	
 	.tb_month{float:left;width:60px;height:90px;margin:0px;padding:0px;}
	.tb_month li{float:left;list-style-type:none;overflow:hidden;}
 	.tb_month dt{clear:both;height:30px;line-height:30px;border-bottom:#CCC 1px solid;}	
	.tb_month dt.noborder{clear:both;height:30px;line-height:30px;border:none;}
	
 	.tb_year{float:left;width:60px;margin:0px;padding:0px;}
	.tb_year li{clear:both;list-style-type:none;height:30px;line-height:30px;border-bottom:#CCC 1px solid;overflow:hidden;}
	.tb_year li.noborder{clear:both;height:30px;line-height:30px;border:none;}

	.backToTop{display:none;}
 </style>
	<div class="row">
		<div class="span12">
			<ul class="nav pull-left">
				<li class="pull-left hide"><a id="delete"  class="btn" style="margin-right: 5px;"><i class="icon-remove"></i><?php echo L('DELETE');?></a></li>
				<li class="pull-left">
					<form class="form-inline" id="searchForm"  action="" method="get">
                    <input type="hidden" name="m" value="kaoqinconfig"/><!--模型：请假-->
                    <input type="hidden" name="a" value="config_year_month_day_time"/><!--模型：列表-->
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
                        <li class="pull-left " style="margin-right:10px;">
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
						<li class="pull-left " style="margin-right:10px;">
                        	<input type="checkbox" name="autoCreate" value="1" >创建默认设置
                        </li>

                        <li class="pull-left">
                        	<button type="button" class="btn" name="btn_search" id="btn_search"> <img src="__PUBLIC__/img/search.png"/>  提交</button>
                        </li>
                         <li class="pull-left hide" style="padding-right:10px;">
                        	
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
    	<div class="span12">
        	<p><h4><font color="red">导入考勤记录过程中，会依据此处设置进行计算，并将计算结果存储起来，所以，确保 此处的设置完全正确后再进行导入考勤记录！</font></h4></p>

        </div>
    </div>
    
	<div class="row">
		<div class="span12">
			<form id="form1" method="post">
				<table class="table table-hover table-striped table_thead_fixed"> 
					<?php if($datas == null): ?><tr><td><h5><font color="red"><?php echo L('EMPTY_TPL_DATA');?>，<br>如需创建默认考勤时间设置，请对复选框打勾后提交，<br>将自动创建指定月份的各日原始设置，<br>随后可修改这些设置</font></h5></td></tr>
					<?php else: ?>
					<thead> 
						<tr>
							<th class="hide"><input type="checkbox" name="check_all" id="check_all"/></th>
                            <th width='60'>ID</th>
                            <th width='110'>日期</th>
							<th width='60'>星期</th>
							<th width='100'>状态</th> 
							<th width='120'>上班时间</th>
                            <th width='120'>下班时间</th>
                            <th >备注</th>
                            <th width='120'>操作</th>
						</tr>
					</thead>

					<tbody>
						<?php if(is_array($datas)): $i = 0; $__LIST__ = $datas;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td class="hide">
                            <input name="id[]" class="check_list" type="checkbox" value="<?php echo ($vo["id"]); ?>"/>
                            </td>
                            <td><?php echo ($vo["id"]); ?></td>
                            <td><?php echo ($vo["date"]); ?></td>
                            <td><?php echo ($vo["week"]); ?></td>
                            <td ><?php echo ($vo["state"]); ?>-<?php echo $state_arr[$vo['state']];?></td>
                            <td><?php echo ($vo["start"]); ?></td>
                            <td><?php echo ($vo["end"]); ?></td>
                            <td ><?php echo ($vo["content"]); ?></td>
                            <td> 
                                <?php  if (1 == session('user_id') || 2 == session('position_id')){ ?>
                            <a href="<?php echo U('Kaoqinconfig/config_year_month_day_time_edit', 'id='.$vo['id']);?>">编缉</a>
                            	<?php
 } ?>
                            </td>
                            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
					</tbody><?php endif; ?>

					<tfoot>
					<tr><td colspan="17"><?php echo ($page); ?></td></tr>
					</tfoot>
                    <tr>
                    <td colspan="17">
                    <h5>要生成某月的设置记录，对复选框打勾后点按钮进行，记录不会被重复生成。<br>
                    修改记录，先前的导入考勤记录的过程中的计算结果不会被更新<br>
                    状态共有以下几种情况：0、不上班（如星期天和无薪假期）,1、必须打卡（包括半天班和全天班）,2、特殊情况免打卡（如全天不上班的带薪假期）<br>
                    </h5>
                    </td>
                    </tr>
				</table>
			</form>
		</div>
	</div>    
    
</div>


</body>
</html>

<script type="text/javascript" src="__PUBLIC__/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/WdatePicker.js"></script>


<script language="javascript">
$(document).ready(function() {
	
	//点击登陆，post提交
	$('#btn_search').click(function(){

		$("#searchForm").submit();
	});
	
});
</script>