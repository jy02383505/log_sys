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
        <form class="form-inline">
            <div class="form-group">
                <select name="q_year" class="form-control">
                    <option value="">---请选择年度---</option>
                    <?php if(is_array($years)): $i = 0; $__LIST__ = $years;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$year): $mod = ($i % 2 );++$i;?><option value="<?php echo ($year); ?>"><?php echo ($year); ?>年</option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                <select name="q_month" class="form-control">
                    <option value="">---请选择月份---</option>
                    <?php for($i=1;$i<=12;$i++){ $month = $i < 10 ? '0'.$i : $i; ?>
                        <option value="<?php echo ($month); ?>"><?php echo ($month); ?>月</option>
                    <?php } ?>
                </select>
                <select name="q_user" class="form-control">
                    <option value="">---请选择用户---</option>
                    <?php if(is_array($userInfo)): $i = 0; $__LIST__ = $userInfo;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$user): $mod = ($i % 2 );++$i;?><option value="<?php echo ($user[user_id]); ?>"><?php echo ($user[name]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                </select>&nbsp;&nbsp;
                <button type="button" class="btn btn-sm btn-primary q_it">查询</button>

            </div>
        </form>
    </div>
    <ul class="nav nav-tabs">
        <li class="active">
            <a href="#attitude" data-toggle="tab">工作态度评估</a>
        </li>
        <li>
            <a href="#attendance" data-toggle="tab">出勤统计评分</a>
        </li>
        <li>
            <a href="#overtime" data-toggle="tab">加班统计评分</a>
        </li>
        <li>
            <a href="#wiki" data-toggle="tab">知识库贡献统计评分</a>
        </li>
    </ul>

    <!-- tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="attitude">
            <div class="well">
                <h4>你的出勤统计得分为：</h4>
                <code class="attendance_points" style="margin-left:15px;">...</code>
                <h4>你的加班统计得分为：</h4>
                <code class="overtime_points" style="margin-left:15px;">...</code>
                <p class="errorInfo" style="color:red;margin-left:30px;display:none;">error</p>
            </div>
            <div class="well">
                <code>工作态度评估固定由统计类和主观评估类二部分组成，包括：</code>
                <p>统计类</p>
                <p>1）出勤统计评分，15分；</p>
                <p>2）加班奖励分，0~10分；</p>
                <p>3）知识库贡献奖励分，0~3分；</p>
                <p>主观评估类</p>
                <p>1）主观评估分，14分；</p>
                <code>工作态度评估方法</code>
                <p>统计类评估，由出勤、请假、加班的统计数据计算。 主观评估，由员工本人、公司经理、部门经理、小组长、项目经理根据平时工作表现进行评估。</p>
            </div>
        </div>
        <div class="tab-pane" id="attendance">
            <div class="page-header">出勤统计评分</div>
            <table class="table table-bordered table-striped table-hover text-center">
                <tr>考勤</tr>
                <tr>
                    <th>项目分值</th>
                    <th>应出勤天数</th>
                    <th>迟到早退（次）</th>
                    <th>事假</th>
                    <th>病假</th>
                    <th>旷工</th>
                    <th>最终得分</th>
                </tr>
                <tr>
                    <td>15</td>
                    <td class="workdays">23.5</td>
                    <td class="cdzt">3</td>
                    <td class="affairleave">2</td>
                    <td class="sickleave">1</td>
                    <td class="absenteeism">1</td>
                    <td class="finalpoints">11.01</td>
                </tr>
            </table>
            <div class="well kaoqin_info"></div>
            <div class="well">
                <code>计算公式：</code>
                <p>出勤统计得分=INT(100*项目分值（15）*(本月应出勤天数-(迟到早退次数*0.25+事假天数*1+病假天数*0.5+旷工天数*3))/本月应出勤天数)/100</p>
                <code>公式说明：</code>
                <p>本月应出勤天数，按公司上班制度确定。迟到早退权重0.25，事假权重1，病假权重0.5，旷工权重3；</p>
                <p>例表：</p>
                <table class="table table-bordered table-striped table-hover text-center">
                    <tr>考勤</tr>
                    <tr>
                        <th>项目分值</th>
                        <th>应出勤天数</th>
                        <th>迟到早退（次）</th>
                        <th>事假</th>
                        <th>病假</th>
                        <th>旷工</th>
                        <th>最终得分</th>
                    </tr>
                    <tr>
                        <td>15</td>
                        <td>23.5</td>
                        <td>3</td>
                        <td>2</td>
                        <td>1</td>
                        <td>1</td>
                        <td>11.01</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="overtime">
            <div class="page-header">加班统计评分</div>
            <table class="table table-bordered table-striped table-hover text-center">
                <tr>加班</tr>
                <tr>
                    <th>项目分值</th>
                    <th>应出勤小时数</th>
                    <th>主动加班小时</th>
                    <th>有薪加班小时</th>
                    <th>实际得分</th>
                </tr>
                <tr>
                    <td>10</td>
                    <td class="workHours">167.5</td>
                    <td class="overtimeHoursNopay">40</td>
                    <td class="overtimeHoursPaid">21</td>
                    <td class="overtime_final">7.79</td>
                </tr>
            </table>
            <p class="errorInfo" style="color:red;margin-left:30px;display:none;">error</p>
            <div class="well ot_info"></div>
            <div class="well">
                <code>计算公式：</code>
                <p>加班统计得分=INT(100*项目分值（10）*（主动加班小时数*3+带薪加班小时数*0.5）/本月应出勤小时数)/100</p>
                <code>公式说明：</code>
                <p>主动加班，指非节假日因工作原因，下班时间超出正常下班时间30分钟以上者。带薪加班指节假日加班的实际工作小时数。</p>
                <p>例表：</p>
                <table class="table table-bordered table-striped table-hover text-center">
                    <tr>加班</tr>
                    <tr>
                        <th>项目分值</th>
                        <th>应出勤小时数</th>
                        <th>主动加班小时</th>
                        <th>有薪加班小时</th>
                        <th>实际得分</th>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>167.5</td>
                        <td>40</td>
                        <td>21</td>
                        <td>7.79</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="wiki">
            <div class="page-header">知识库贡献统计评分</div>
            <div class="well">
                <p>知识库的文章发布，必须是公司业务相关内容，严谨完整。</p>
                <p>统计当月的知识库经验值，进行排名。</p>
                <p>1、未创建任何词条，不得分</p>
                <p>2、月排名前20%，奖3分</p>
                <p>3、月排名前20%～80%，奖1.5分</p>
                <p>4、月排名后20%，奖0.5分</p>
                <code>知识库经验分值表</code>
                <table class="table table-bordered table-striped table-hover text-center">
                    <tr>
                        <th>参数名称</th>
                        <th>经验</th>
                        <th>参数名称</th>
                        <th>经验</th>
                    </tr>
                    <tr>
                        <td>注册用户获得</td>
                        <td>20</td>
                        <td>登录系统获得</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>创建词条获得</td>
                        <td>6</td>
                        <td>编辑词条获得</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>上传图片获得</td>
                        <td>1</td>
                        <td>发短消息获得</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>发表评论获得</td>
                        <td>1</td>
                        <td>下载附件扣除上限</td>
                        <td>0</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    // var module = '<?php echo (ACTION_NAME); ?>';
    // alert(module);
    // 查询准备
    var q_year = $('select[name=q_year]');
    var q_month = $('select[name=q_month]');
    var q_user = $('select[name=q_user]');
    $('.q_it').click(function(){
        if(!q_year.val() || !q_month.val() || !q_user.val()){
            alert('查询条件不足！');
            return false;
        }
        if(!!q_year.val() && !!q_month.val() && !!q_user.val()){
            $.post('<?php echo U("Performance/ajax_getWorkLateLeaveEarly");?>', {user_id: q_user.val(), q_year: q_year.val(), q_month: q_month.val()}, function(re){
                // console.log(re);
                // return false;
                $('.workdays').html(re.workDayNum);
                $('.cdzt').html(re.cdztNum);
                $('.affairleave').html(re.affairLeaveNum);
                $('.sickleave').html(re.sickLeaveNum);
                $('.absenteeism').html(re.absenteeismNum);
                var finalpoints = 15 * 100 * (re.workDayNum - (re.cdztNum * 0.25 + re.affairLeaveNum * 1 + re.sickLeaveNum * 0.5 + re.absenteeismNum * 3)) / re.workDayNum / 100;
                $('.finalpoints').html(finalpoints.toFixed(2));
                $('.attendance_points').html(finalpoints.toFixed(2));

                // 出勤统计简述
                var kaoqin_info = $('.kaoqin_info');
                kaoqin_info.html('<h4 class="kaoqin_title">出勤统计简述：</h4><br>');
                var kaoqin_title = $('.kaoqin_title');
                kaoqin_info.append(re.msg);

                // 加班统计
                $('.workHours').html(re.workHours);
                $('.overtimeHoursNopay').html(re.overtimeHoursNopay);
                $('.overtimeHoursPaid').html(re.overtimeHoursPaid);
                var overtime_final = 10 * 100 * (re.overtimeHoursNopay * 3 + re.overtimeHoursPaid * 0.5) / re.workHours * 1 / 100;
                var overtime_final = overtime_final < 0 ? -1 : overtime_final;
                $('.overtime_final').html(overtime_final.toFixed(2));
                $('.overtime_points').html(overtime_final.toFixed(2));
                if(!!re.otInfo){
                    $('.overtime_final').css('color', 'red');
                    $('.errorInfo').html(re.otInfo);
                    $('.errorInfo').show(1000);
                }else{
                    $('.errorInfo').hide(1000);
                }

                // 加班统计简述
                var ot_info = $('.ot_info');
                ot_info.html('<h4 class="ot_title">加班统计简述：</h4><br>');
                var kaoqin_title = $('.ot_title');
                ot_info.append(re.otInfo);


            // });
            }, 'json');
        }
    })
</script>