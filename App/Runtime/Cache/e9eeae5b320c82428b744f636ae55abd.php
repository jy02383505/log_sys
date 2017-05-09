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
		<h4>每日考勤设置</h4>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
	<p class="view">

	</p>
	<div class="row">
		<div class="span12" style="padding-bottom:10px;border-bottom:#CCC 1px solid;">
			<p>请注意严格按要求填写和选择。</p>
			<p ></p>
		</div>
		<div class="span12">
			<form id="form1" name="form1" method="post" action="">
            	<input type="hidden" name="id" value="<?php echo ($datas["id"]); ?>">
                <table class=" table-condensed" width="100%">
                <tr>                    
                <td width="100">日期</td>
                <td width="200"><?php echo ($datas["date"]); ?></td>
                <td></td>
                </tr>
                
                <tr>                    
                <td>星期</td>
                <td><?php echo ($datas["week"]); ?></td>
                <td></td>
                </tr>
                
                <tr>                    
                <td>状态</td>
                <td>
                    <select  style="width:auto" name="state" id="state">
                        <option value="">请选择</option>
                        <option value="0" <?php if(($datas["state"]) == "0"): ?>selected<?php endif; ?> >0-不上班</option>
                        <option value="1" <?php if(($datas["state"]) == "1"): ?>selected<?php endif; ?> >1-上班，必须打卡</option>
                        <option value="2" <?php if(($datas["state"]) == "2"): ?>selected<?php endif; ?> >2-特殊情况，免打卡</option>
                    </select>                
                </td>
                <td> </td>
                </tr>
                
                <tr>                    
                <td>签到时间</td>
                <td>
                    <input type="text" name="start"  id="start" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})" class="Wdate" value="<?php echo ($datas["start"]); ?>" style="width:120px;"/>
                </td>
                <td> </td>
                </tr>   
                
                <tr>                    
                <td>签退时间</td>
                <td>
                    <input type="text" name="end" id="end" onfocus="WdatePicker({skin:'whyGreen',dateFmt:'HH:mm:ss'})" class="Wdate" value="<?php echo ($datas["end"]); ?>" style="width:120px;"/>
                </td>
                <td> </td>
                </tr> 
                
                <tr>                    
                <td>备注</td>
                <td colspan="2">
                    <textarea class="form-control" rows="3" style="width:400px;" name="content"><?php echo ($datas["content"]); ?></textarea>
                </td>
                </tr>                 
                            
                
                
                <tr>                    
                <td></td>
                <td>
                	
                	<button type="submit" class="btn btn-info btn-sm btn_time_save" id_val="<?php echo ($vo["id"]); ?>">保存更改</button>
                	<button type="reset" class="btn btn-info btn-sm">取消</button>
                </td>
                <td></td>
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
	var tmpClassid = '';
	
	
	$('button[type="reset"]').click(function(){
		window.location.href = '<?php echo U("kaoqinconfig/config_year_month_day_time","the_year=".$datas["year"]."&the_month=".$datas["month"]);?>';
	});
});
</script>


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
			kqj_user_id:{
				required: true,//名称带方括号验证不
				number:true,
			},
			kqj_date_varchar:{
				required: true,//
			},
        },  
        messages : {
			kqj_user_id :{
				required :"用户必须选择",	
			},
			kqj_date_varchar :{
				required :"时间必填",	
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


</body>
</html>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.9.0.min.js"></script>
<script>
$(document).ready(function() {
	//mac应用按钮 zjh add


});

</script>