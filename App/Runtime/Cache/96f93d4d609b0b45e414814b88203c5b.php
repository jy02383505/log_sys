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
	.backToTop{display:none;}
</style>


<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
		<h4>考勤设置</h4>
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
		<div class="span12" style="padding-bottom:10px;border-bottom:#CCC 1px solid;">
			<p>此处列出的是系统中被激活的用户(jl_user.status>0)，切记<b>设置正确后再执行导入</b>考勤机数据的操作，否则错误的记录无法处理。</p>
			<p >考勤时间设置：jl_kaoqin_config表中</p>
		</div>
		<div class="span12">
			<form id="form1" name="form1" method="post" action="">
                <table class=" table-condensed" width="100%">
                <tr>       
                <td width="100">user_id</td>             
                <td width="160">用户名</td>
                <td width="100">指纹考勤机id</td>
                <td></td>
                </tr>
                
                <?php if(is_array($users_list)): $i = 0; $__LIST__ = $users_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>                    
                <td><?php echo ($vo["user_id"]); ?></td>
                <td><?php echo ($vo["name"]); ?></td>
                <td><input type="text" style="width:60px;" id="kqj_user_id_<?php echo ($vo["user_id"]); ?>" name="" value="<?php echo ($vo["kqj_user_id"]); ?>"> </td>
                <td><button type="button" class="btn btn-info btn-sm btn_save" user_id="<?php echo ($vo["user_id"]); ?>">保存更改</button></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                
                <tr>
                <td colspan='10' style="border-bottom:#CCC solid 1px;"><h4>考勤时间设置</h4></td>
                </tr>
                <tr>
                <td colspan='10' style="color:#FF0000;"><h4>格式：07:00，08:30，15:30，18:00，一旦设置错误，后续的错误将无法撤销</h4></td>
                </tr>                
                
                <?php if(is_array($time_list)): $i = 0; $__LIST__ = $time_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>                    
                <td><?php echo ($vo["week_cn"]); ?></td>
                <td><input type="text" style="width:60px;" id="start_<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["week_cn"]); ?>" value="<?php echo ($vo["starttime"]); ?>"></td>
                <td><input type="text" style="width:60px;" id="end_<?php echo ($vo["id"]); ?>" name="<?php echo ($vo["week_cn"]); ?>" value="<?php echo ($vo["endtime"]); ?>"> </td>
                <td><button type="button" class="btn btn-info btn-sm btn_time_save" id_val="<?php echo ($vo["id"]); ?>">保存更改</button></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                
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
	$('.btn_save').click(function(){
		//var z_tid = $("#tid").val();
		var user_id = $(this).attr("user_id");
		var kqj_user_id_str = "#kqj_user_id_" + user_id;
		var kqj_user_id = $(kqj_user_id_str).val();
	//	alert(user_id);
		var url_do = "<?php echo U('Kaoqinconfig/ajax_save_uid_kqj');?>";
		var this_btn = $(this);
		
	
		//ajax提交
		$.ajax({
			url:url_do,
			type:'post',
			data:"user_id="+user_id+"&kqj_user_id="+kqj_user_id,
			async:true,//false为同步
			dataType:'json',
			success:function(re) {
				if (re.stat * 1 > 0){
					//alert("更新成功");
					$(this_btn).html(re.data+'...');
				}else{
					//alert(re.data);//失败提示	
					$(this_btn).html(re.data);
				}

			},
			error:function() {
				alert('由于网络异常导致操作失败，请刷新页面重试....');
			}
		});
				
	});	

	$('.btn_time_save').click(function(){
		//var z_tid = $("#tid").val();
		var id_val = $(this).attr("id_val");
		var start_id_str = "#start_" + id_val;
		var start_val = $(start_id_str).val();
		
		var end_id_str =  "#end_" + id_val;
		var end_val = $(end_id_str).val();
		
		
		var url_do = "<?php echo U('Kaoqinconfig/ajax_save_kq_time');?>";
		var this_btn = $(this);
		
		//alert(start_val);
	
		//ajax提交
		$.ajax({
			url:url_do,
			type:'post',
			data:"id="+id_val+"&starttime="+start_val+"&endtime="+end_val,
			async:true,//false为同步
			dataType:'json',
			success:function(re) {
				if (re.stat * 1 > 0){
					//alert("更新成功");
					$(this_btn).html(re.data+'...');
				}else{
					//alert(re.data);//失败提示	
					$(this_btn).html(re.data);
				}

			},
			error:function() {
				alert('由于网络异常导致操作失败，请刷新页面重试....');
			}
		});
				
	});	
});

</script>