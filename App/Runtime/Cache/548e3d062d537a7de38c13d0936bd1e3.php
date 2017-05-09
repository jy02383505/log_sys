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
		<h4>导入考勤机记录</h4>
        <div style="">
        <p>请选择考勤机原始DAT文件，可以用记事本打开DAT文件，将旧的记录删除，但不得对任何记录作任何修改，空回车键在导入时自动跳过。</p>
        <p>导入过程中，切记不要进行任何操作，导入完成后，会自动跳转到考勤记录列表页。</p>
        <p><h4><font color="red">导入前，确保已经正确的设置了考勤时间（某日是否上班，上、下班时间等），否则产生的错误无法撤销！</font></h4>
        <p><h4><font color="red">切忌：先导入考勤记录，后设置考勤时间，是严重错误的，因为很多数据是在导入过程中计算并存储！</font></h4>
        <p><h4><font color="red">如果不确定或没有设置，请点下面的按钮查看、设置。</font></h4></p> <a href="<?php echo U('Kaoqinconfig/config_year_month_day_time');?>"><button type="button" class="btn btn-primary" title="设置每月考勤时间">每日考勤时间设置</button></a></p>
        </div>
	</div>
	<?php if(is_array($alert)): foreach($alert as $k=>$v): if(is_array($v)): foreach($v as $kk=>$vv): ?><div class="alert alert-<?php echo ($k); ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<?php echo ($vv); ?>
		</div><?php endforeach; endif; endforeach; endif; ?>
	<p class="view">



        <form action="<?php echo U(Kaoqin/txtimport);?>" method="post" enctype="multipart/form-data">
            <table class="table table-hover">
                <tr>
                    <td class="tdleft">选择文件:</td> 
                    <td>考勤机导出的原始DAT文件</td>
                </tr>
                <tr>
                    <td class="tdleft" width="20%"><?php echo L('ERROR_HANDLING');?></td> 
                    <td>
                        <input id="ownership" type="radio" checked="checked" value="0" name="error_handing"><?php echo L('TERMINATION');?>
                        <input id="ownership1" type="radio" value="1" name="error_handing"><?php echo L('SKIP');?>
                    </td>
                </tr>
                <tr>
                    <td class="tdleft"><?php echo L('Attachment');?></td>
                    <td>
                        <input type="file" name="txt"/>
                    </td>
                </tr>		
                <tr> 
                    <td>&nbsp;</td>
                    <td>
                    <input class="btn btn-primary" type="submit" name="submit" value="<?php echo L('IMPORT');?>"/> &nbsp; 
                    <a href="<?php echo U('Kaoqin/index');?>">
                    <button class="btn" onclick="javascript:$('#dialog-import').dialog('close');" type="button" value="<?php echo L('CANCEL');?>"/>返回考勤记录</button>
                    </a>
                    </td>
                </tr>
            </table>
        </form>

	</p>
</div>