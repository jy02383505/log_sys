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



<link rel="stylesheet" href="__PUBLIC__/jui_datetimepicker/css/jquery-ui.css">
<script src="__PUBLIC__/jui_datetimepicker/js/jquery-ui.js"></script>
<script src="__PUBLIC__/jui_datetimepicker/js/jquery-ui-timepicker-addon.js"></script>
<style>
    .ui-datepicker-calendar{
        display: none;
    }
    .ui-datepicker-year{
        width: 70px;
    }
</style>

<div class="container">
    <div class="page-header">
        <h3>外出登记记录</h3>
        <form action="<?php echo U('WorkOutside/index');?>">
            <select name="q_user">
                <option value="">---请选择用户---</option>
                <?php if(is_array($users)): $i = 0; $__LIST__ = $users;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><option value="<?php echo ($user[user_id]); ?>"><?php echo ($user[name]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
            <input type="text" name="q_month" class="q_month" placeholder="选择日期">
            <input type="hidden" name="m" value="<?php echo (MODULE_NAME); ?>">
            <input type="hidden" name="a" value="<?php echo (ACTION_NAME); ?>">
            <button class="btn btn-sm btn-primary">查询</button>
            <button type="button" class="btn btn-sm btn-info clear_q_month">清空日期</button>
        </form>

        <a href="<?php echo U('WorkOutside/add');?>"><button type="button" class="btn btn-sm btn-success pull-right add_out">新增外出记录</button></a>
    </div>
    <table class="table table-hover table-striped table-bordered" style="margin-top:15px;">
        <tr>
            <th>id</th>
            <th>登记日期</th>
            <th>未打卡事项</th>
            <th>未打卡时间</th>
            <th>相关人员</th>
            <th>修改时间</th>
            <th>操作</th>
        </tr>
        <?php if(is_array($rows)): $i = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$row): $mod = ($i % 2 );++$i;?><tr>
                <td><?php echo ($row[id]); ?></td>
                <td><?php echo ($row[out_date]); ?></td>
                <td><?php echo ($row[events]); ?></td>
                <td>
                    <?php if($row[off_time] == '0'): ?>上午<?php elseif($row[off_time] == '1'): ?>下午<?php else: ?>全天<?php endif; ?>
                </td>
                <td><?php echo ($row[username]); ?></td>
                <td><?php echo (date("Y-m-d H:i:s", $row[update_time])); ?></td>
                <td>
                    <a href=<?php echo U('WorkOutside/edit', "id=$row[id]");?> class="btn btn-sm btn-warning">修改</a>
                    <button type="button" data-href=<?php echo U('WorkOutside/delete', "id=$row[id]");?> class="btn btn-sm btn-danger del_it">删除</button>
                </td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
    <div class="page-box">
        <?php echo ($show); ?>
    </div>
</div>

<script type="text/javascript">
    // var module = '<?php echo (ACTION_NAME); ?>';
    // alert(module);
    $('.del_it').click(function(){
        var self = $(this);
        if(confirm('确定删除外出记录吗')){
            location.href = self.attr('data-href');
        }
    })

    // datepicker initialization
    $('.q_month').datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'yy-mm',
        onClose: function(dateText, inst){
            var month = $('#ui-datepicker-div .ui-datepicker-month option:selected').val();
            var year = $('#ui-datepicker-div .ui-datepicker-year option:selected').val();
            var month_str = (month * 1 + 1) < 10 ? '0' + (month * 1 + 1) : '' + (month * 1 + 1);
            $('.q_month').val(year + '-' + month_str);
        }
    });

    // 搜索之后的页面处理
    var q_user = '<?php echo ($_GET['q_user']); ?>';
    var q_month = '<?php echo ($_GET['q_month']); ?>';
    if(!!q_user){
        $.each($('select[name=q_user] option'), function(i, o){
            if(o.value == q_user){
                o.selected = true;
            }else{
                o.selected = false;
            }
        })
    }
    if(!!q_month){
        $('.q_month').val(q_month);
    }

    // clear_q_month
    $('.clear_q_month').click(function(){
        $('.q_month').val('');
    })
</script>