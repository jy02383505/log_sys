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



<script type="text/javascript" src="__PUBLIC__/js/kindeditor-all-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/zh_CN.js"></script>
<link rel="stylesheet" href="__PUBLIC__/css/kindeditor.css" type="text/css" />
<script type="text/javascript">
	<?php if(C('ismobile') != 1): ?>var editor;
		KindEditor.ready(function(K) {
			editor = K.create('textarea[name="content"]', {
				uploadJson:'<?php echo U("file/editor");?>',
				allowFileManager : true,
				loadStyleMode : false
			});
		});<?php endif; ?>
</script>
<div class="container">
	<!-- Docs nav ================================================== -->
	<div class="page-header">
		<h4><?php echo L('EDIT_LOG');?></h4>
	</div>	
	<div class="row">
		<div class="span12">
			<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
			<form action="<?php echo U('log/mylog_edit');?>" method="post">
				<input type="hidden" name="log_id" value="<?php echo ($log["log_id"]); ?>"/>
				<table class="table" width="95%" border="0" cellspacing="1" cellpadding="0">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td><input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp; <input class="btn" onclick="javascript:history.go(-1)" type="reset" value="<?php echo L('RETURN');?>"/></td>
						</tr>
					</thead>
					<tfoot>
						<tr> 
							<td>&nbsp;</td>
							<td><input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp; <input class="btn" type="reset" onclick="javascript:history.go(-1)" value="<?php echo L('RETURN');?>"/></td>					
						</tr>
					</tfoot>
					<tbody width="100%">
						<tr>
							<th colspan="2"><?php echo L('BASIC_INFO');?></th>
						</tr>
						<?php if($_GET['type'] == 1): ?><tr>
								<?php if(!empty($log['customer_id'])): ?><td class="tdleft"><?php echo L('BELONG_TO_CUSTOMER');?></td>
									<td><a href="<?php echo U('customer/view','id='.$log['customer_id']);?>" target="blank"><?php echo ($log["customer_name"]); ?></a></td>					
								<?php elseif(!empty($log['contacts_id'])): ?>
									<td class="tdleft"><?php echo L('BELONG_TO_CONTACTS');?></td>
									<td><a href="<?php echo U('contacts/view','id='.$log['contacts_id']);?>" target="blank"><?php echo ($log["contacts_name"]); ?></a></td>
								<?php elseif(!empty($log['business_id'])): ?>
									<td class="tdleft"><?php echo L('BELONG_TO_BUSINESS');?></td>
									<td><a href="<?php echo U('business/view','id='.$log['business_id']);?>" target="blank"><?php echo ($log["business_name"]); ?></a></td>
								<?php elseif(!empty($log['task_id'])): ?>
									<td class="tdleft"><?php echo L('BELONG_TO_TASK');?></td>
									<td><a href="<?php echo U('task/view','id='.$log['task_id']);?>" target="blank"><?php echo ($log["task_name"]); ?></a></td>
								<?php elseif(!empty($log['event_id'])): ?>
									<td class="tdleft"><?php echo L('BELONG_TO_EVENT');?></td>
									<td><a href="<?php echo U('event/view','id='.$log['event_id']);?>" target="blank"><?php echo ($log["event_name"]); ?></a></td>
								<?php elseif(!empty($log['leads_id'])): ?>
									<td class="tdleft"><?php echo L('BELONG_TO_LEADS');?></td>
									<td><a href="<?php echo U('leads/view','id='.$log['leads_id']);?>" target="blank"><?php echo ($log["leads_name"]); ?></a></td><?php endif; ?>
							</tr><?php endif; ?>
						<tr>
							<td class="tdleft"><?php echo L('SUBJECT');?></td>
							<td><input type="text" class="span5" name="subject" maxlength="20" value="<?php echo ($log["subject"]); ?>"/></td>						
						</tr>
						<tr>
							<td class="tdleft" width="15%">日期</td>
							<td><input type="text" id="the_date" name="the_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="Wdate" value="<?php echo ($log["the_date"]); ?>" style="width:140px"/></td>						
						</tr>  
						<tr>
							<td class="tdleft"><?php echo L('CONTENT');?></td>
							<td width="85%" colspan="3" >
								<textarea rows="15" class="span6" name="content" style="width: 800px; height: 350px;"><?php echo ($log["content"]); ?></textarea> 
							</td>							
						</tr>
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>

</body>
</html>