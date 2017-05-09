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
		<h4>综合统计-出差统计</h4>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
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

	
 </style>
	<div class="row">
		<div class="span12">
			<ul class="nav pull-left">
				<li class="pull-left hide"><a id="delete"  class="btn" style="margin-right: 5px;"><i class="icon-remove"></i><?php echo L('DELETE');?></a></li>
				<li class="pull-left">
					<form class="form-inline" id="searchForm"  action="" method="get">
                    <input type="hidden" name="m" value="Count"/><!--模型：请假-->
                    <input type="hidden" name="a" value="index"/><!--模型：列表-->
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
                        <li class="pull-left " style="margin-right:10px;display:none;">
                            <select  style="width:auto" name="the_month" id="the_month">
                              <option value="">月份</option>
                              <option value="1" <?php if(($the_month) == "1"): ?>selected<?php endif; ?> >1月份</option>
                              <option value="2" <?php if(($the_month) == "2"): ?>selected<?php endif; ?> >2月份</option>
                              <option value="3" <?php if(($the_month) == "3"): ?>selected<?php endif; ?> >3月份</option>
                              <option value="4" <?php if(($the_month) == "4"): ?>selected<?php endif; ?> >4月份</option>
                              <option value="5" <?php if(($the_month) == "5"): ?>selected<?php endif; ?> >5月份</option>
                              <option value="6" <?php if(($the_month) == "6"): ?>selected<?php endif; ?> >6月份</option>
                              <option value="7" <?php if(($the_month) == "7"): ?>selected<?php endif; ?> >7月份</option>
                              <option value="8" <?php if(($the_month) == "8"): ?>selected<?php endif; ?> >8月份</option>                                  
                              <option value="9" <?php if(($the_month) == "9"): ?>selected<?php endif; ?> >9月份</option>
                              <option value="10" <?php if(($the_month) == "10"): ?>selected<?php endif; ?> >10月份</option>
                              <option value="11" <?php if(($the_month) == "11"): ?>selected<?php endif; ?> >11月份</option>
                              <option value="12" <?php if(($the_month) == "12"): ?>selected<?php endif; ?> >12月份</option>
                            </select>
                        </li>
                        <li class="pull-left " style="margin-right:10px;display:none;">
                            <select  style="width:auto" name="user_id" id="user_id">
                            <option value="">用户</option>
                            <?php if(is_array($users_list)): $i = 0; $__LIST__ = $users_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["user_id"]); ?>" <?php if(($user_id) == $vo["user_id"]): ?>selected<?php endif; ?>  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>
                        
                        <li class="pull-left " style="margin-right:10px;">
                            <select  style="width:auto" name="tj_type" id="tj_type">
                            <option value="">类型</option>
                            <?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["val"]); ?>" <?php if(($tj_type) == $vo["val"]): ?>selected<?php endif; ?>  ><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select>
                        </li>

                        <li class="pull-left hide">
                        	<button type="submit" class="btn"> <img src="__PUBLIC__/img/search.png"/>  <?php echo L('SEARCH');?></button>
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
		<div class="span12">
			<form id="form1" method="post">
				<table class="table table-hover table-striped table_thead_fixed"> 
                <?php
 $model = new Model(); foreach($users_list as $k=>$v){ ?>
                    <tr>
                    <td colspan="10" style="font-weight:bold;height:60px;line-height:60px;font-size:18px;" >
                    [<?php echo ($v['name']); ?>] 出差统计：（<?php echo ($the_year); ?>年）
                    </td>
                    </tr>

                    
                    <tr>
                    <td colspan="10" style="padding:0px;">
                    	<table class="table" style="margin:0px;min-width:1000px;">

                        <tr>
                        <td width="160">
                            <ul class="tb_left">
                            <li><b>月份</b></li>
                            <li>省内（天）</li>
                            <li>省外（天）</li>
                            <li class="noborder">一线城市（天）</li>
                            </ul>
                        </td>
                    
                        <td >
                        	<div style="clear:both;width:100%;">
                            
                            <?php
 for ($i=1;$i<13;$i++){ $map = array(); if ($i<10){ $tmp = "0".$i; }else{ $tmp = $i; } $this_year_month = $the_year."-".$tmp; $map = array(); $map['user_id'] = $v['user_id']; $map['the_month'] = $this_year_month; $map['type'] = 1; $map['checked'] = 1; $month_chucai_hours_1 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_1 = round($month_chucai_hours_1,1); $map = array(); $map['user_id'] = $v['user_id']; $map['the_month'] = $this_year_month; $map['type'] = 2; $map['checked'] = 1; $month_chucai_hours_2 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_2 = round($month_chucai_hours_2,1); $map = array(); $map['user_id'] = $v['user_id']; $map['the_month'] = $this_year_month; $map['type'] = 3; $map['checked'] = 1; $month_chucai_hours_3 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $month_chucai_hours_3 = round($month_chucai_hours_3,1); ?>
                            
                                
                                    <ul class="tb_month">
                                    <dt><?php echo $i;?>月</dt>
                                    <dt><?php echo $month_chucai_hours_1;?></dt>
                                    <dt><?php echo $month_chucai_hours_2;?></dt>
                                    <dt class="noborder"><?php echo $month_chucai_hours_3;?></dt>
                                    </ul>
                                
                            <?php
 $map = array(); $map['user_id'] = $v['user_id']; $map['the_year'] = $the_year; $map['type'] = 1; $map['checked'] = 1; $year_chucai_hours_1 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $year_chucai_hours_1 = round($year_chucai_hours_1,1); $map = array(); $map['user_id'] = $v['user_id']; $map['the_year'] = $the_year; $map['type'] = 2; $map['checked'] = 1; $year_chucai_hours_2 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $year_chucai_hours_2 = round($year_chucai_hours_2,1); $map = array(); $map['user_id'] = $v['user_id']; $map['the_year'] = $the_year; $map['type'] = 3; $map['checked'] = 1; $year_chucai_hours_3 = $model->table("jl_kaoqin_chucai")->where($map)->sum('chuchai_days'); $year_chucai_hours_3 = round($year_chucai_hours_3,1); } ?>
                            </div>
                        </td>
                        <td width="200">
                            <ul class="tb_year">
                            <li><b>全年合计</b></li>
                            <li><?php echo ($year_chucai_hours_1); ?><!--本年度--></li>
                            <li><?php echo ($year_chucai_hours_2); ?></li>
                            <li class="noborder"><?php echo ($year_chucai_hours_3); ?></li>
                            </ul>
                        </td>    
                        </tr> 
                        </table>
                    </td>
                    </tr> 
                    
                <?php
 } ?>                                    
                    

				</table>
			</form>

            

            
            
            
            
		</div>
	</div>
</div>
<div class="hide" id="dialog-import" title="<?php echo L('IMPORT_DATA');?>">loading...</div>
<div class="hide" id="dialog-role-info" title="<?php echo L('DIALOG_USER_INFO');?>">loading...</div>

<div class="hide" id="dialog-import-kaoqin" title="导入考勤机TXT">loading...</div>

<script type="text/javascript">
<?php if(C('ismobile') == 1): ?>width=$('.container').width() * 0.9;<?php else: ?>width=800;<?php endif; ?>
$("#dialog-import").dialog({
	autoOpen: false,
	modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100]
});
$("#dialog-role-info").dialog({
    autoOpen: false,
    modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100]
});

//考勤机
$("#dialog-import-kaoqin").dialog({
	autoOpen: false,
	modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100]
});

function changeContent(){
	a = $("#select1  option:selected").val();
	window.location.href="<?php echo U('event/index', 'by=');?>"+a;
}
$(function(){
<?php if($_GET['field']!= null): ?>$("#field option[value='<?php echo ($_GET['field']); ?>']").prop("selected", true);changeCondition();
	$("#condition option[value='<?php echo ($_GET['condition']); ?>']").prop("selected", true);changeSearch();
	$("#search").prop('value', '<?php echo ($_GET['search']); ?>');<?php endif; ?>
	$("#check_all").click(function(){
		$("input[class='check_list']").prop('checked', $(this).prop("checked"));
	});
	$('#delete').click(function(){
		if(confirm('确定删除考勤记录吗')){
			$("#form1").attr('action', '<?php echo U("Kaoqin/del");?>');
			$("#form1").submit();
		}
	});
	$("#import_excel").click(function(){
		$('#dialog-import').dialog('open');
		$('#dialog-import').load('<?php echo U("event/excelimport");?>');
	});
	$(".role_info").click(function(){
		$role_id = $(this).attr('rel');
		$('#dialog-role-info').dialog('open');
		$('#dialog-role-info').load('<?php echo U("user/dialoginfo","id=");?>'+$role_id);
	});
	
	//考勤机TXT导入对话框
	$("#import_kaoqin_txt").click(function(){
		$('#dialog-import-kaoqin').dialog('open');
		$('#dialog-import-kaoqin').load('<?php echo U("Kaoqin/txtimport");?>');
	});
	
})
</script>

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
		var tj_type = $("#tj_type").val();
		var url_tj = '';
		//url
		switch(tj_type)
		{
		case 'log'://日志统计
			url_tj = "<?php echo U('Count/index');?>";
			break;
		case 'kaoqin'://考勤统计
			url_tj = "<?php echo U('Count/index');?>";
			break;
		case 'chuchai'://出差统计
			url_tj = "<?php echo U('Count/index');?>";
			break;
		default:
			url_tj = "<?php echo U('Count/index');?>";

		}
		
		
		
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
//		if (user_id == ''){
//			alert("用户必选");
//			$("#user_id").focus();
//			return;
//		}
		url_tj = url_tj+"&the_year="+the_year+"&the_month="+the_month+"&user_id="+user_id+"&tj_type="+tj_type;
	//	alert(url_tj);
		window.location.href = url_tj;
	});

	//激活新增的登陆按钮的click事件，此方法已被放弃，改用on方法
	$("#btn_tj").live("click",function(){

	});
});
</script>