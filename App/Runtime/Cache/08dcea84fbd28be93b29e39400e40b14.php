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
		<h4><?php echo L('USER_INFO');?></h4>
	</div>
	<div class="row">
		<div class="span12">
			<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
		</div>
		<div class="span12">
			<form class="form-horizontal" action="<?php echo U('user/edit');?>" method="post" >
				<input type="hidden" name="user_id" value="<?php echo ($user["user_id"]); ?>"/>
				<table class="table span7">
					<thead>
						<tr>
							<td>&nbsp;</td>
							<td><input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp;<input class="btn" type="button" onclick="javascript:history.go(-1)" value="<?php echo L('CANCEL');?>"/></td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<td>&nbsp;</td>
							<td><input name="submit" class="btn btn-primary" type="submit" value="<?php echo L('SAVE');?>"/> &nbsp;<input class="btn" type="button" onclick="javascript:history.go(-1)" value="<?php echo L('CANCEL');?>"/></td>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<th colspan="2"><?php echo L('BASIC_INFO');?></th>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('USER_NAME');?></td>
							<td><?php if(session('?admin')): ?><input type="text" name="name" value="<?php echo ($user['user_name']); ?>"><?php else: echo ($user["user_name"]); endif; ?></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('MODIFY_PASSWORD');?></td>
							<td><input class="text-input small-input" type="password" name="password" id="password" value=""/></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('USER_CATEGORY');?></td>
							<td><?php if(session('?admin')): ?><select name="category_id" id="category_id"><?php if(is_array($categoryList)): $i = 0; $__LIST__ = $categoryList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$temp): $mod = ($i % 2 );++$i;?><option value="<?php echo ($temp["category_id"]); ?>" <?php if($temp["category_id"] == $user['category_id']): ?>selected = "selected"<?php endif; ?>><?php echo ($temp["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?></select><?php else: echo ($user["category"]); endif; ?></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('ACCOUNT_STATUS');?></td>
							<td><?php if(session('?admin')): ?><select id="status" name="status">
									<?php if(is_array($statuslist)): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$temp): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" <?php if($key == $user['status']): ?>selected = "selected"<?php endif; ?>><?php echo ($temp); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
								</select><?php else: echo ($statuslist[$user['status']]); endif; ?>
							</td>
						</tr>
						<?php if(session('?admin')): ?><tr>
								<td class="tdleft"><?php echo L('DEPARTMENT');?>&nbsp;<span style="color:red;">*</span></td>  
								<td>
									<select id="department" name="department_id" onchange="changeRoleContent()">
										<option value=""></option>
										<?php if(is_array($department_list)): $i = 0; $__LIST__ = $department_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$temp): $mod = ($i % 2 );++$i;?><option value="<?php echo ($temp["department_id"]); ?>"><?php echo ($temp["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
								</td>
							</tr>
							<tr>
								<td class="tdleft"><?php echo L('POSITION');?>&nbsp;<span style="color:red;">*</span></td>  
								<td>
									<select id="role" name="position_id">
										<option value=""></option>
										<?php if(is_array($position_list)): $i = 0; $__LIST__ = $position_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$temp): $mod = ($i % 2 );++$i;?><option value="<?php echo ($temp["position_id"]); ?>"><?php echo ($temp["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
									</select>
									
								</td>
							</tr>
						<?php else: ?>
							<input type="hidden" name="position_id" value="<?php echo ($_SESSION['position_id']); ?>"/><?php endif; ?>
						<tr>
							<td class="tdleft"><?php echo L('SEX');?></td>
							<td><input type="radio"  name="sex" value="1" <?php if($user['sex'] == 1): ?>checked="checked"<?php endif; ?>/><?php echo L('MALE');?> 
							<input type="radio"  name="sex" value="2" <?php if($user['sex'] == 2): ?>checked="checked"<?php endif; ?>/><?php echo L('FEMALE');?></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('EMAIL');?>&nbsp;<span style="color:red;">*</span></td>
							<td><input class="text-input small-input" name="email" type="text" value="<?php echo ($user["email"]); ?>"></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('TELPHONE');?>&nbsp;<span style="color:red;">*</span></td>
							<td><input class="text-input small-input" name="telephone" type="text" value="<?php echo ($user["telephone"]); ?>"></td>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('ADDRESS');?></td>
							<td><textarea name="address" ><?php echo ($user["address"]); ?></textarea></td>
						</tr>
						<tr>
							<th colspan="2"><?php echo L('ACCOUNT_BIND_INFO');?></th>
						</tr>
						<tr>
							<td class="tdleft"><?php echo L('WECHAT_BIND');?></td>
							<td><?php if($user[weixinid]): echo L('BOUND_WECHAT_ID'); echo ($user["weixinid"]); else: echo L('not_bind');?><a href="javascript:void(0);" id="weixin"><?php echo L('CLICK_SCAN_QR_COD');?></a><?php endif; ?></td>
						</tr>
					</tbody>
				</table>
			</form>			
		</div> <!-- End #tab1 -->		
	</div> <!-- End #main-content -->	
</div>
<div class="hide" id="dialog-weixin" title="<?php echo L('SCAN_QR_COD');?>">loading...</div>
<script type="text/javascript">
$("#dialog-weixin").dialog({
    autoOpen: false,
    modal: true,
	width: 600,
	Height: 600,
	position: ["center",100]
});
function changeRoleContent(){
	department_id = $('#department').val();
	if(department_id == ''){
		$("#role").html('');
	}else{
		$.ajax({
			type:'get',
			url:'index.php?m=user&a=getpositionlist&id='+department_id,
			async:false,
			success:function(data){
				options = '';
				if(data.data){
					$.each(data.data, function(k, v){
						options += '<option value="'+v.position_id+'">'+v.name+'</option>';
					});
				}
				$("#role").html(options);
			},
			dataType:'json'
		});		
	}
}
$('#role').click(
	function(){
		department_id = $('#department').val();
		if(department_id == ''){
			alert("<?php echo L('SELECT_DEPARTMENT_FIRST');?>");
		}
	}
);
$(function(){
	$("#weixin").click(function(){
		$('#dialog-weixin').dialog('open');
		$('#dialog-weixin').load('<?php echo U("user/weixin");?>');
	});
	
	$("#department option[value='<?php echo ($user['department_id']); ?>']").prop("selected", true);
	$("#role option[value='<?php echo ($user['position_id']); ?>']").prop("selected", true);
});
</script>

</body>
</html>