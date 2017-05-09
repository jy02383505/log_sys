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
		<h4>添加出差记录</h4>
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
	<div class="row">
		<div class="span12">
			

		</div>
		<div class="span12">
			<form id="form1" name="form1" method="post" action="" class="form-inline">
				<table class="table table-hover table-striped table_thead_fixed"> 
                <tr>
                <td width='80'>用户：</td>
                <td>
                    <select  style="width:auto" name="user_id" id="user_id">
                    <option value="">职员</option>
                    <?php if(is_array($users_list)): $i = 0; $__LIST__ = $users_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(ACTION_NAME == 'edit'): ?><option value="<?php echo ($vo["user_id"]); ?>" <?php if(($datas["user_id"]) == $vo["user_id"]): ?>selected<?php endif; ?>  ><?php echo ($vo["user_id"]); ?>-<?php echo ($vo["name"]); ?></option>
                    <?php else: ?>
                    	<option value="<?php echo ($vo["user_id"]); ?>" <?php if(($user_id) == $vo["user_id"]): ?>selected<?php endif; ?>  ><?php echo ($vo["user_id"]); ?>-<?php echo ($vo["name"]); ?></option><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    <span></span>
                </td>
                <td></td>
                </tr>
                
                 <tr>
                <td>出差时长：</td>
                <td>   
					<input type="text" name="chuchai_days" id="chuchai_days" value= "<?php echo ($datas["chuchai_days"]); ?>" style="width:40px;"> 天
                    
                    &nbsp;&nbsp;
                    <select name="days_sel" id="days_sel" style="width:100px;">
					<option value="">快捷输入</option>
                    <?php
 for ($i=0.5;$i<7;$i+=0.5){ ?>
                       <option value="<?php echo $i;?>"><?php echo $i;?>天</option>
                    <?php
 } ?>         
                    </select>
				</td>
                </tr>
                
                <tr>
                <td>开始时间：</td>
                <td>
				<input type="text" id="starttime" name="starttime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="Wdate" value="<?php echo ($datas["starttime"]); ?>" style="width:140px"/>&nbsp;
                结束时间：<input type="text" id="endtime" name="endtime" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="Wdate" value="<?php echo ($datas["endtime"]); ?>" style="width:140px"/>&nbsp;
                跨月的请分开两条填写，以开始时间的年月作为统计时间
                </td>
                <td>
                </td>
                </tr>
                
                <tr>
                <td>地区：</td>
                <td>
                	<input type="radio" name="type" value="3" <?php if(($datas["type"]) == "3"): ?>checked<?php endif; ?> >一线城市&nbsp;&nbsp;
                	<input type="radio" name="type" value="2" <?php if(($datas["type"]) == "2"): ?>checked<?php endif; ?> >省外&nbsp;&nbsp;
                    <input type="radio" name="type" value="1" <?php if((ACTION_NAME) == "add"): ?>checked<?php else: ?>  <?php if(($datas["type"]) == "1"): ?>checked<?php endif; endif; ?> >省内
                </td>
                </tr>   
                
                 <tr>
                <td>备注：</td>
                <td><textarea name="content" class="form-control" rows="3" style="width:400px;"><?php echo ($datas["content"]); ?></textarea>

                </td>
                <td>
                </td>
                </tr>               
   
                
                <tr>
                <td></td>
                <td>
                    <input type="hidden" name="id" value='<?php echo ($datas["id"]); ?>'>  
                    <button type="submit" class="btn btn-info btn-sm">保存更改</button>   &nbsp;&nbsp;
                    <a href="<?php echo U('Chuchai/index');?>"><button type="button" class="btn btn-info btn-sm">取消</button></a> 
                </td>
                <td>

                </td>
                </tr>  
                
                <tr>
                <td></td>
                <td>
				注意：以输入的天数作为统计时长，允许的值为0.5、1、1.5之类<br>
                跨月的，请分开填写，否则后面的都被统计到开始时间所在的月份<br>
                当天出差，当天回来，开始和截止时间请写为相同即可，不可为空，否则无法统计<br>
                开始日期：出差第一天的日期，结束日期为出差最后一天的日期<br>
                </td>
                <td>
                </td>
                </tr>
                
				</table>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript" src="__PUBLIC__/js/jquery-1.9.0.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/WdatePicker.js"></script>


<script type="text/javascript">
$(function($) {
	
	// 判断英文字符
	jQuery.validator.addMethod("isEnglish", function(value, element) {       
         return this.optional(element) || /^[A-Za-z]+$/.test(value);       
    }, "只能包含英文字符。");
	
	
	$('#form1').validate({
		errorElement : 'span',
		errorClass : 'error',
		focusInvalid : true,  
        rules : {  
			user_id:{
				required: true,//名称带方括号验证不
				number:true,
			},
			chuchai_days:{
				required: true,//
				number:true,
			},
			starttime:{
				required: true,//
			},
			endtime:{
				required: true,//
			},
			content:{
				required: true,//
			},
        },  
        messages : {
			user_id :{
				required :"用户必须选择",	
			},
			chuchai_days:{
				required :"出差时长必填",	
				number:"只能是小数或整数",
			},
			starttime :{
				required :"开始时间必填",	
			},
			endtime :{
				required :"结束时间必填",	
			},			
			content :{
				required :"备注必填",	
			},			
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

<script language="javascript" type="text/javascript"> 
$(document).ready(function(){ 
	$('#days_sel').change(function(){ 
		//alert($(this).children('option:selected').val()); 
		$("#chuchai_days").val($(this).children('option:selected').val());
	
	}) 
}) 
</script> 


</body>
</html>