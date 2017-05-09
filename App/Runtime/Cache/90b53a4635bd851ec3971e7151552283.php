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
		<h4><?php if(ACTION_NAME == 'add'): ?>添加<?php else: ?>修改<?php endif; ?>外出记录</h4>
	</div>

	<form method="post" action=<?php if(ACTION_NAME == 'add'): ?>"<?php echo U('WorkOutside/add');?>"<?php else: ?>"<?php echo U('WorkOutside/edit');?>"<?php endif; ?>>
		<table class="table table-hover table-striped "> 
            <tr>
                <td width="120px">用户：</td>
                <td>
                    <?php if(ACTION_NAME == 'add'): ?><select name="user_id">
                            <?php if($_SESSION['admin']== '1'): ?><option>---请选择---</option>
                                <?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><option value="<?php echo ($user[user_id]); ?>"><?php echo ($user[user_id]); ?>-<?php echo ($user[name]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                            <?php else: ?>
                                <option value="<?php echo (session('role_id')); ?>"><?php echo (session('role_id')); ?>-<?php echo (session('name')); ?></option><?php endif; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" value="<?php echo ($row[username]); ?>" disabled>
                        <input type="hidden" name="user_id" value="<?php echo ($row[user_id]); ?>"><?php endif; ?>
                </td>
            </tr>
            
            <tr>
                <td>外出日期：</td>
                <td>
    				<input type="text" name="out_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="Wdate" value="<?php echo ($row[out_date]); ?>">
                    <code>超过一天的请以天为单位分多次填写</code>
    			</td>
            </tr>
            
            <tr>
                <td>未打卡时间：</td>
                <td>   
                    <select name="off_time" style="width:100px;">
                        <option value="0" <?php if($row[off_time] == '0'): ?>selected<?php endif; ?> >上午</option>
                        <option value="1" <?php if($row[off_time] == '1'): ?>selected<?php endif; ?> >下午</option>
                        <option value="2" <?php if($row[off_time] == '2'): ?>selected<?php endif; ?> >全天</option>
                    </select>
    			</td>
            </tr>

            <tr>
                <td>外出事项：</td>
                <td>
                    <textarea name="events" class="form-control" rows="3" style="width:400px;"><?php echo ($row[events]); ?></textarea>
                </td>
            </tr>               
            
            <tr>
                <td> 
                    <input type="hidden" name="id" value='<?php echo ($row[id]); ?>'>  
                    <button type="submit" class="btn btn-primary btn-sm">保存</button>
                    <a href="<?php echo U('WorkOutside/index');?>"><button type="button" class="btn btn-danger btn-sm">取消</button></a> 
                </td>
            </tr>  
		</table>
	</form>
</div>

<script type="text/javascript" src="__PUBLIC__/js/WdatePicker.js"></script>


</body>
</html>