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
	<div class="page-header">
		<h4><?php echo L('LOG_VIEW');?></h4>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
	<div class="row">
		<div class="tabbable span12">
			<div class="tab-content">
				<div class="tab-pane active" id="tab1">
					<table class="table" border="0" cellspacing="1" cellpadding="0">
						<thead>
							<tr>
								<td colspan="2">
									<span style="font-size: 14px;">
										<?php if($comment_role_id): ?><a id="comment_btn" href="javascript:void(0);"><?php echo L('COMMENT');?></a> &nbsp; | &nbsp;<?php endif; ?>
										<a href="<?php echo U('log/mylog_edit','id='.$log['log_id'].'&type='.$_GET['type']);?>"><?php echo L('EDIT');?></a> &nbsp; | &nbsp; 
										<a href="<?php echo U('log/log_delete','redirect=index&id='.$log['log_id']);?>"><?php echo L('DELETE');?></a> &nbsp; | &nbsp; 
										<a href="javascript:history.go(-1);"><?php echo L('BACK_TO_PRE_PAGE');?></a> &nbsp; | &nbsp; 
										<a href="<?php echo U('log/index');?>"><?php echo L('BACK_TO_INDEX');?></a>
									</span>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="2"><span style="font-size: 18px;color: #000;font-weight: 600;"><?php echo ($log["subject"]); ?> &nbsp; <span style="font-size:14px;"><span style="font-weight:500;color: #000;">(<?php if(!empty($log['creator']['user_name'])): ?><a class="role_info" rel="<?php echo ($log["creator"]["role_id"]); ?>" href="javascript:void(0)"><span style="font-size:14px;"><?php echo ($log["creator"]["user_name"]); ?></span></a><?php endif; ?>:<?php echo (date("Y-m-d H:i",$log["create_date"])); ?>)</span></span>&nbsp;  &nbsp;  &nbsp; </span> <?php if($pre_href): ?><a href="<?php echo ($pre_href); ?>"><?php echo L('PRE_LOG');?></a><?php endif; ?> &nbsp;  &nbsp;  &nbsp;<?php if($next_href): ?><a href="<?php echo ($next_href); ?>"><?php echo L('NEXT_LOG');?></a><?php endif; ?></td>
								
							</tr>
							<tr>
								<th colspan="2"><?php echo L('BASIC_INFO');?></th>
							</tr>						
							<?php if(!empty($log['customer_id'])): ?><tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_CUSTOMER');?>:</td>
								<td><div><a href="<?php echo U('customer/view','id='.$log['customer_id']);?>" target="blank"><?php echo ($log["customer_name"]); ?></a></div></td>
							</tr>
							<?php elseif(!empty($log['contacts_id'])): ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_CONTACTS');?>:</td>
								<td><div><a href="<?php echo U('contacts/view','id='.$log['contacts_id']);?>" target="blank"><?php echo ($log["contacts_name"]); ?></a></div></td>
							</tr>
							<?php elseif(!empty($log['business_id'])): ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_BUSINESS');?>:</td>
								<td><div><a href="<?php echo U('business/view','id='.$log['business_id']);?>" target="blank"><?php echo ($log["business_name"]); ?></a></div></td>
							</tr>
							<?php elseif(!empty($log['task_id'])): ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_TASK');?>:</td>
								<td><div><a href="<?php echo U('task/view','id='.$log['task_id']);?>" target="blank"><?php echo ($log["task_name"]); ?></a></div></td>
							</tr>
							<?php elseif(!empty($log['event_id'])): ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_EVENT');?>:</td>
								<td><div><a href="<?php echo U('event/view','id='.$log['event_id']);?>" target="blank"><?php echo ($log["event_name"]); ?></a></div></td>
							</tr>
							<?php elseif(!empty($log['leads_id'])): ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('RELATED_LEADS');?>:</td>
								<td><div><a href="<?php echo U('leads/view','id='.$log['leads_id']);?>" target="blank"><?php echo ($log["leads_name"]); ?></a></div></td>
							</tr><?php endif; ?>
							<tr>
								<td class="tdleft" width="17%"><?php echo L('DETAILS_DESCRIPTION_ABOUT_JOB');?>:</td>
								<td><div style="min-height:200px"><?php if($log["content"] != null): echo ($log["content"]); endif; ?></div></td>
							</tr>
							<tr>
								<td class="tdleft"><?php echo L('COMMENT');?>:</td>
								<td>
									<table width="100%">
										<?php if(empty($comment_list)): ?><tr>
												<td colspan="2"><?php echo L('NO_COMMENTS');?></td>
											</tr>
										<?php else: ?>
											<?php if(is_array($comment_list)): $i = 0; $__LIST__ = $comment_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
													<td colspan="2"><?php echo L('COMMENTATOR');?>:<a class="role_info" rel="<?php echo ($vo["role_id"]); ?>" href="javascript:void(0)"><?php echo ($vo["user_name"]); ?></a>（<?php echo (date("Y-m-d H:i",$vo["create_time"])); ?>）<?php if($vo['role_id'] == session('role_id')): ?><a rel="<?php echo ($vo['comment_id']); ?>" class="edit_comment_btn" href="javascript:void(0);"><?php echo L('EDIT');?></a><?php endif; ?></td>
												</tr>
												<tr>
													<td colspan="2"><?php if($vo["content"] != null): ?><pre><?php echo ($vo["content"]); ?></pre><?php endif; ?></td>
												</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
									</table>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="tab-pane" id="tab2">
					<table class="table table-hover">
						<?php if($log["file"] == null): ?><tr>
								<td><?php echo L('EMPTY_DATA');?> </td>
							</tr>
						<?php else: ?> 
							<tr>
								<th>&nbsp;</th>
								<th><?php echo L('FILE_NAME');?></th>
								<th><?php echo L('SIZE');?></th>
								<th><?php echo L('ADDED_BY');?></th>
								<th><?php echo L('ADD_TIME');?></th>
							</tr>
							<?php if(is_array($log["file"])): $i = 0; $__LIST__ = $log["file"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
									<td class="tdleft"><a href="<?php echo U('file/delete','r=RFileLeads&id='.$vo['file_id']);?>"><?php echo L('DELETE');?></a></td>
									<td>
										<a target="_blank" href="<?php echo ($vo["file_path"]); ?>"><?php echo ($vo["name"]); ?></a>
									</td>
									<td>
										<?php echo ($vo["size"]); echo L('BYTE');?>
									</td>
									<td>
										<?php if(!empty($vo["owner"]["user_name"])): ?><a class="role_info" rel="<?php echo ($vo["owner"]["role_id"]); ?>" href="javascript:void(0)"><?php echo ($vo["owner"]["user_name"]); ?></a><?php endif; ?>
									</td>
									<td>
										<?php if(!empty($vo["create_date"])): echo (date("Y-m-d g:i:s a",$vo["create_date"])); endif; ?>
									</td>
								</tr><?php endforeach; endif; else: echo "" ;endif; endif; ?>
						<tr>
							<td colspan="5">
								<a href="javascript:void(0);" class="add_file"><?php echo L('ADD');?></a>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="hide" id="dialog-file" title="<?php echo L('DIALOG_ADD_ATTACHMENTS');?>">loading...</div>
<div class="hide" id="dialog-role-info" title="<?php echo L('DIALOG_USER_INFO');?>">loading...</div>
<?php if($comment_role_id): ?><div class="hide" id="dialog-comment" title="<?php echo L('COMMENT');?>">loading...</div>
<div class="hide" id="dialog-editcomment" title="<?php echo L('EDIT_COMMENT');?>">loading...</div><?php endif; ?>
<script type="text/javascript">
<?php if(C('ismobile') == 1): ?>width=$('.container').width() * 0.9;<?php else: ?>width=600;<?php endif; ?>
$("#dialog-file").dialog({
    autoOpen: false,
    modal: true,
	width: width,
	maxHeigth: 400,
	position: ["center",100]
});
$("#dialog-role-info").dialog({
    autoOpen: false,
    modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100]
});
$("#dialog-comment").dialog({
    autoOpen: false,
    modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100],
	buttons: { 
		"<?php echo L('OK');?>": function () {
			$('#comment').submit();
			$(this).dialog("close"); 
		},
		"<?php echo L('CANCEL');?>": function () {
			$(this).dialog("close");
		}
	}
});
$("#dialog-editcomment").dialog({
    autoOpen: false,
    modal: true,
	width: width,
	maxHeight: 400,
	position: ["center",100],
	buttons: { 
		"<?php echo L('OK');?>": function () {
			$('#editcomment').submit();
			$(this).dialog("close"); 
		},
		"<?php echo L('CANCEL');?>": function () {
			$(this).dialog("close");
		}
	}
});
$(function(){
	$(".role_info").click(function(){
		$role_id = $(this).attr('rel');
		$('#dialog-role-info').dialog('open');
		$('#dialog-role-info').load('<?php echo U("user/dialoginfo","id=");?>'+$role_id);
	});
	<?php if($comment_role_id): ?>$("#comment_btn").click(function(){
		$('#dialog-comment').dialog('open');
		$('#dialog-comment').load('<?php echo U("comment/add","to_role_id=".$log["role_id"]."&module=log&module_id=".$log["log_id"]);?>');
	});
	$(".edit_comment_btn").click(function(){
		comment_id = $(this).attr('rel');
		$('#dialog-comment').dialog('open');
		$('#dialog-comment').load('<?php echo U("comment/edit","to_role_id=".$log["role_id"]."&id=");?>'+comment_id);
	});<?php endif; ?>
});
</script>

</body>
</html>