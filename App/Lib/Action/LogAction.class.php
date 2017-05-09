<?php
class LogAction extends Action{
	public function _initialize(){
		$action = array(
			'permission'=>array('wxadd'),
			'allow'=>array('add', 'view', 'delete', 'edit', 'index', 'mylog_view', 'mylog_edit', 'log_delete', 'mylog_add')
		);
		B('Authenticate', $action);
	}
	
	public function add(){
		if($_POST['submit']){
			if($_POST['r']){
				$r = $_POST['r'];
				$module = $_POST['module'];
				$model_id = $_POST['id'];
				$m_r = M($r);
				$m_log = M('Log');
				$m_log->create();
				$m_log->category_id = 1;
				$m_log->create_date = time();
				$m_log->update_date = time();
				if($log_id = $m_log->add()){
					$m_id = $module . '_id';
					$data['log_id'] = $log_id;
					$data[$m_id] = $model_id;
					if($m_r -> add($data)){
						alert('success',L('ADD SUCCESS', array(L('LOG'))),$_SERVER['HTTP_REFERER']);
					}else{
						alert('error', L('ADD_LOG_FAILED'),$_SERVER['HTTP_REFERER']);
					}
				}else{
					alert('error', L('ADD_LOG_FAILED'),$_SERVER['HTTP_REFERER']);
				}
			}else{
				$m_log = M('Log');
				$m_log->create();
				$m_log->category_id = 1;
				$m_log->create_date = time();
				$m_log->update_date = time();
				if($log_id = $m_log->add()){
					$data['business_id'] = intval($_POST['business_id']);
					$data['task_id'] = intval($_POST['task_id']);
					$data['product_id'] = intval($_POST['product_id']);
					$data['customer_id'] = intval($_POST['customer_id']);
					$data['log_id'] = $log_id;
					
					if ($data['business_id']) {
						M('RBusinessLog')->add($data);
					}
					if ($data['task_id']) {
						M('RLogTask')->add($data);
					}
					if ($data['product_id']) {
						M('RLogProduct')->add($data);
					}
					if ($data['customer_id']) {
						M('RCustomerLog')->add($data);
					}
					
					alert('success',L('ADD SUCCESS', array(L('LOG'))),$_SERVER['HTTP_REFERER']);
				}else{
					alert('error', L('ADD_LOG_FAILED'),$_SERVER['HTTP_REFERER']);
				}
			}
			
		} elseif ($_GET['r'] && $_GET['module'] && $_GET['id']) {
			$this -> r = $_GET['r'];
			$this -> module = $_GET['module'];
			$this -> model_id = $_GET['id'];
			$this->display();
				
		} else {
			alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		}
	}
	//WeChat page
	public function wxadd(){
		if($_POST['subject']){
			$log = M('Log');
			$log->create();
			$log->create_date = time();
			$log->update_date = time();
			
			$log->role_id = $_GET['id'];
			if($log->add()){
				$this->success(L('ADD SUCCESS', array(L('LOG'))));
			}else{
				$this->error(L('ADD_LOG_FAILED'));
			}
		}else{
			$this->display();
		}
	}
	public function view(){
		if($_GET['id']){
			$log_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$m_log = M('Log');
			$log = $m_log->where('log_id = %d', $log_id)->find();
			$file_ids = M('rFileLog')->where('log_id = %d', $log_id)->getField('file_id', true);
			$log['file'] = M('file')->where('file_id in (%s)', implode(',', $file_ids))->select();
			$file_count = 0;
			foreach ($log['file'] as $key=>$value) {
				$log['file'][$key]['owner'] = D('RoleView')->where('role.role_id = %d', $value['role_id'])->find();
				$file_count ++;
			}
			$log['file_count'] = $file_count;
			$log['creator'] = getUserByRoleId($log['role_id']);
			
			$this->log =  $log;
			$this->alert = parseAlert();
			$this->display();
		}else{
			alert('error', L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		}
	}

	public function delete(){
		$log_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (0 == $log_id){
			alert('error',L('PARAMETER_ERROR'),$_SERVER['HTTP_REFERER']);
		} else {
			if (isset($_GET['r']) && isset($_GET['id'])) {
				$m_r = M($_GET['r']);
				$m_log = M('log');
				
				if ($m_r->where('log_id = %d',$_GET['id'])->delete()) {
					if ($m_log->where('log_id = %d',$_GET['id'])->delete()) {
						alert('success',L('DELETE_LOG_SUCCESS'),$_SERVER['HTTP_REFERER']);
					} else {
						alert('success',L('DELETE FAILED CONTACT THE ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
					}
				} else {
					alert('success',L('DELETE FAILED CONTACT THE ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
				}
			} elseif (empty($_GET['r']) && isset($_GET['id'])){
				$m_log = M('Log');
				if ($m_log->where('log_id = %d',$_GET['id'])->delete()){
					alert('success',L('DELETE_LOG_SUCCESS'),U('log/index'));
				} else {
					alert('success',L('DELETE FAILED CONTACT THE ADMINISTRATOR'),U('log/index'));
				}
			}
		}
	}
	
	
	public function index(){
		$m_log = M('Log');
		$m_comment = M('Comment');
		$by = isset($_GET['by']) ? trim($_GET['by']) : '';
		$where = array();
		$params = array();
		$order = "";
		$below_ids = getSubRoleId(false);
		$all_ids = getSubRoleId();
		$module = isset($_GET['module']) ? trim($_GET['module']) : '';
		switch ($by) {
			case 'today' : $where['create_date'] =  array('gt',strtotime(date('Y-m-d', time()))); break;
			case 'week' : $where['create_date'] =  array('gt',(strtotime(date('Y-m-d', time())) - (date('N', time()) - 1) * 86400)); break;
			case 'month' : $where['create_date'] = array('gt',strtotime(date('Y-m-01', time()))); break;
			case 'last_month':
			{
				;
				break;
			}
			
			case 'add' : $order = 'create_date desc';  break;
			case 'update' : $order = 'update_date desc';  break;
			case 'sub' : $where['role_id'] = array('in',implode(',', $below_ids)); break;
			case 'me' : $where['role_id'] = session('role_id'); break;
			default :  $where['role_id'] = array('in',implode(',', $all_ids)); break;
		}
		if ($_GET['r'] && $_GET['module']){
			$m_r = M($_GET['r']);
			$log_ids = $m_r->getField('log_id', true);
			$where['log_id'] = array('in', implode(',', $log_ids));
		}
		if (!isset($where['role_id'])) {
			$where['role_id'] = array('in',implode(',', getSubRoleId())); 
		}
		if(intval($_GET['type'])){
			$where['category_id'] = intval($_GET['type']);
		}		
		if ($_REQUEST["field"]) {
			$field = trim($_REQUEST['field']);
			$search = empty($_REQUEST['search']) ? '' : trim($_REQUEST['search']);

			$condition = empty($_REQUEST['condition']) ? 'eq' : trim($_REQUEST['condition']);
			if	('create_date' == $field || 'update_date' == $field) {
				$search = strtotime($search);
			} elseif ('role_id' == $field) {
				$condtion = "is";
			}
			$params = array('field='.$_REQUEST['field'], 'condition='.$condition, 'search='.trim($_REQUEST["search"]));
			$field = trim($_REQUEST['field']) == 'all' ? 'subject|content' : $_REQUEST['field'];
			switch ($_REQUEST['condition']) {
				case "is" : $where[$field] = array('eq',$search);break;
				case "isnot" :  $where[$field] = array('neq',$search);break;
				case "contains" :  $where[$field] = array('like','%'.$search.'%');break;
				case "not_contain" :  $where[$field] = array('notlike','%'.$search.'%');break;
				case "start_with" :  $where[$field] = array('like',$search.'%');break;
				case "end_with" :  $where[$field] = array('like','%'.$search);break;
				case "is_empty" :  $where[$field] = array('eq','');break;
				case "is_not_empty" :  $where[$field] = array('neq','');break;
				case "gt" :  $where[$field] = array('gt',$search);break;
				case "egt" :  $where[$field] = array('egt',$search);break;
				case "lt" :  $where[$field] = array('lt',$search);break;
				case "elt" :  $where[$field] = array('elt',$search);break;
				case "eq" : $where[$field] = array('eq',$search);break;
				case "neq" : $where[$field] = array('neq',$search);break;
				case "between" : $where[$field] = array('between',array($search-1,$search+86400));break;
				case "nbetween" : $where[$field] = array('not between',array($search,$search+86399));break;
				case "tgt" :  $where[$field] = array('gt',$search+86400);break;
				default : $where[$field] = array('eq',$search);
			}
		}
		
		//按月查询
		$the_month = intval($_REQUEST['the_month']);
		if ($the_month){
			$the_year = date('Y');
			$tm = $this->mFristAndLast($the_year,$the_month);//本页后面定义
			$start_time = $tm['firstday'];
			$end_time = $tm['lastday'];
		//	echo $tm['firstday'].'<br>';
		//	echo $tm['lastday'].'<br>';
		//	echo $the_year;
		//	echo date("Y-m-d h:i:s",$tm['firstday']).'<br>';
		//	echo date("Y-m-d h:i:s",$tm['lastday']).'<br>';
				
			$where['create_date'] = array('between',array($start_time,$end_time));
			$this->assign('the_month',$the_month);
		}
		//
		

		

//		exit;
	
		
		if ($order) {
			$list = $m_log->where($where)->order($order)->limit(15)->select();
		} else {

			$p = isset($_GET['p']) ? intval($_GET['p']) : 1 ;
			$list = $m_log->where($where)->page($p.',10')->order('create_date desc')->select();
			$count = $m_log->where($where)->count();
			import("@.ORG.Page");
			$Page = new Page($count,10);
			if (!empty($_REQUEST['by'])){
				$params['by'] = 'by=' . trim($_REQUEST['by']);
				
			}
			if (!empty($_REQUEST['r']) && !empty($_REQUEST['module'])) {
				$params['r'] = 'r=' . trim($_REQUEST['r']);
				$params['module'] = 'module=' . trim($_REQUEST['module']);
			}
			if (!empty($_REQUEST['type'])) {
				$params['type'] = 'type=' . trim($_REQUEST['type']);
			}
			if (!empty($_REQUEST['the_month'])) {
				$params['the_month'] = 'the_month=' . trim($_REQUEST['the_month']);
			}
			$Page->parameter = implode('&', $params);
			$show = $Page->show();		
			$this->assign('page',$show);
		}
		foreach($list as $key=>$value){
			$list[$key]['creator'] = getUserByRoleId($value['role_id']);
			if($m_comment->where("module='log' and module_id=%d", $value['log_id'])->select()){
				$list[$key]['is_comment'] = 1;
			}
		}
		$this->category_list = M('LogCategory')->order('order_id')->select();
		//Get youself and Subordinate's position for search
		$d_role_view = D('RoleView');
		$this->role_list = $d_role_view->where('role.role_id in (%s)', implode(',', $below_ids))->select();
		//Communication log  (log type = 1 is communication log)
		if(intval($_GET['type']) == 1){
			$m_customer = M('Customer');
			$m_r_customer_log = M('RCustomerLog');
			$m_contacts = M('Contacts');
			$m_r_contacts_log = M('RContactsLog');
			$m_business = M('Business');
			$m_r_business_log = M('RBusinessLog');
			$m_task = M('Task');
			$m_r_task_log = M('RLogTask');
			$m_event = M('Event');
			$m_r_event_log = M('REventLog');
			$m_leads = M('Leads');
			$m_r_leads_log = M('RLeadsLog');
			foreach($list as $k=>$v){
				$r_customer_log = $m_r_customer_log->where('log_id = %d',$v)->find();
				if(!empty($r_customer_log)){
					$customer = $m_customer->where('customer_id = %d',$r_customer_log['customer_id'])->find();
					$list[$k]['customer_id'] = $customer['customer_id'];
					$list[$k]['customer_name'] = $customer['name'];
				}
				$r_contacts_log = $m_r_contacts_log->where('log_id = %d',$v)->find();
				if(!empty($r_contacts_log)){
					$contacts = $m_contacts->where('contacts_id = %d',$r_contacts_log['contacts_id'])->find();
					$list[$k]['contacts_id'] = $contacts['contacts_id'];
					$list[$k]['contacts_name'] = $contacts['name'];
				}
				$r_business_log = $m_r_business_log->where('log_id = %d',$v)->find();
				if(!empty($r_business_log)){
					$business = $m_business->where('business_id = %d',$r_business_log['business_id'])->find();
					$list[$k]['business_id'] = $business['business_id'];
					$list[$k]['business_name'] = $business['name'];
				}
				$r_task_log = $m_r_task_log->where('log_id = %d',$v)->find();
				if(!empty($r_task_log)){
					$task = $m_task->where('task_id = %d',$r_task_log['task_id'])->find();
					$list[$k]['task_id'] = $task['task_id'];
					$list[$k]['task_name'] = $task['subject'];
				}
				$r_event_log = $m_r_event_log->where('log_id = %d',$v)->find();
				if(!empty($r_event_log)){
					$event = $m_event->where('event_id = %d',$r_event_log['event_id'])->find();
					$list[$k]['event_id'] = $event['event_id'];
					$list[$k]['event_name'] = $event['subject'];
				}
				$r_leads_log = $m_r_leads_log->where('log_id = %d',$v)->find();
				if(!empty($r_leads_log)){
					$leads = $m_leads->where('leads_id = %d',$r_leads_log['leads_id'])->find();
					$list[$k]['leads_id'] = $leads['leads_id'];
					$list[$k]['leads_name'] = $leads['name'];
				}
			}
		}
		//var_dump($list);
		$this->assign('list',$list);
		$this->alert = parseAlert();
		$this->display();
	}
	
	public function mylog_view(){
		if($_GET['id']){
			if (in_array($log['role_id'], getSubRoleId())) alert('error', L('HAVE NOT PRIVILEGES'), $_SERVER['HTTP_REFERER']);
			$log_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
			$m_log = M('Log');
			$m_customer = M('Customer');
			$m_r_customer_log = M('RCustomerLog');
			$m_contacts = M('Contacts');
			$m_r_contacts_log = M('RContactsLog');
			$m_business = M('Business');
			$m_r_business_log = M('RBusinessLog');
			$m_task = M('Task');
			$m_r_task_log = M('RLogTask');
			$m_event = M('Event');
			$m_r_event_log = M('REventLog');
			$m_leads = M('Leads');
			$m_r_leads_log = M('RLeadsLog');
			$log = $m_log->where('log_id = %d', $log_id)->find();
			$file_ids = M('rFileLog')->where('log_id = %d', $log_id)->getField('file_id', true);
			$log['file'] = M('file')->where('file_id in (%s)', implode(',', $file_ids))->select();
			$log['creator'] = getUserByRoleId($log['role_id']);
			//Log related module
			$r_customer_log = $m_r_customer_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_customer_log)){
				$customer = $m_customer->where('customer_id = %d',$r_customer_log['customer_id'])->find();
				$log['customer_id'] = $customer['customer_id'];
				$log['customer_name'] = $customer['name'];
			}
			$r_contacts_log = $m_r_contacts_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_contacts_log)){
				$contacts = $m_contacts->where('contacts_id = %d',$r_contacts_log['contacts_id'])->find();
				$log['contacts_id'] = $contacts['contacts_id'];
				$log['contacts_name'] = $contacts['name'];
			}
			$r_business_log = $m_r_business_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_business_log)){
				$business = $m_business->where('business_id = %d',$r_business_log['business_id'])->find();
				$log['business_id'] = $business['business_id'];
				$log['business_name'] = $business['name'];
			}
			$r_task_log = $m_r_task_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_task_log)){
				$task = $m_task->where('task_id = %d',$r_task_log['task_id'])->find();
				$log['task_id'] = $task['task_id'];
				$log['task_name'] = $task['subject'];
			}
			$r_event_log = $m_r_event_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_event_log)){
				$event = $m_event->where('event_id = %d',$r_event_log['event_id'])->find();
				$log['event_id'] = $event['event_id'];
				$log['event_name'] = $event['subject'];
			}
			$r_leads_log = $m_r_leads_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_leads_log)){
				$leads = $m_leads->where('leads_id = %d',$r_leads_log['leads_id'])->find();
				$log['leads_id'] = $leads['leads_id'];
				$log['leads_name'] = $leads['name'];
			}

			if (in_array($log['role_id'], getSubRoleId(false))) {
				if(!($log['comment_role_id'] > 0)){
					$this->comment_role_id = session('role_id');
				}
			}
			$pre = M('Log')->where('role_id = %d and category_id <> 1 and create_date < %d', $log['role_id'], $log['create_date'])->order('create_date desc')->limit(1)->find();
			if($pre) $this->pre_href = U('log/mylog_view', 'id='.$pre['log_id']);
			$next = M('Log')->where('role_id = %d and category_id <> 1 and create_date > %d', $log['role_id'], $log['create_date'])->limit(1)->find();
			if($next) $this->next_href = U('log/mylog_view', 'id='.$next['log_id']);
			$this->log =  $log; 
			$this->comment_list = D('CommentView')->where('module = "log" and module_id = %d', $log['log_id'])->order('comment.create_time desc')->select();
			$this->alert = parseAlert();
			$this->display();
		}else{
			alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
		}
	}
	
	public function mylog_edit(){
		if($_GET['id']){
			$log_id = $_GET['id'];
			$m_log = M('Log');
			$log = $m_log->where('log_id = %d', $_GET['id'])->find();
			//Log related Module
			$m_customer = M('Customer');
			$m_r_customer_log = M('RCustomerLog');
			$m_contacts = M('Contacts');
			$m_r_contacts_log = M('RContactsLog');
			$m_business = M('Business');
			$m_r_business_log = M('RBusinessLog');
			$m_task = M('Task');
			$m_r_task_log = M('RLogTask');
			$m_event = M('Event');
			$m_r_event_log = M('REventLog');
			$m_leads = M('Leads');
			$m_r_leads_log = M('RLeadsLog');

			
			$r_customer_log = $m_r_customer_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_customer_log)){
				$customer = $m_customer->where('customer_id = %d',$r_customer_log['customer_id'])->find();
				$log['customer_id'] = $customer['customer_id'];
				$log['customer_name'] = $customer['name'];
			}
			$r_contacts_log = $m_r_contacts_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_contacts_log)){
				$contacts = $m_contacts->where('contacts_id = %d',$r_contacts_log['contacts_id'])->find();
				$log['contacts_id'] = $contacts['contacts_id'];
				$log['contacts_name'] = $contacts['name'];
			}
			$r_business_log = $m_r_business_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_business_log)){
				$business = $m_business->where('business_id = %d',$r_business_log['business_id'])->find();
				$log['business_id'] = $business['business_id'];
				$log['business_name'] = $business['name'];
			}
			$r_task_log = $m_r_task_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_task_log)){
				$task = $m_task->where('task_id = %d',$r_task_log['task_id'])->find();
				$log['task_id'] = $task['task_id'];
				$log['task_name'] = $task['subject'];
			}
			$r_event_log = $m_r_event_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_event_log)){
				$event = $m_event->where('event_id = %d',$r_event_log['event_id'])->find();
				$log['event_id'] = $event['event_id'];
				$log['event_name'] = $event['subject'];
			}
			$r_leads_log = $m_r_leads_log->where('log_id = %d',$log_id)->find();
			if(!empty($r_leads_log)){
				$leads = $m_leads->where('leads_id = %d',$r_leads_log['leads_id'])->find();
				$log['leads_id'] = $leads['leads_id'];
				$log['leads_name'] = $leads['name'];
			}

			if (in_array($log['role_id'], getSubRoleId(false))) {
				if(!($log['comment_role_id'] > 0)){
					$this->comment_role_id = session('role_id');
				}
			}
			

			
			
			
			
			$this->log =  $log;
			$this->alert = parseAlert();
			$this->display();
		} elseif ($_POST['submit']){
			$log = M('Log');
			$log -> create();
			
			$the_date = trim($_POST['the_date']);

			$log->the_date = $the_date;
			$log->the_year_month = date("Y-m",strtotime($the_date));
			$log->the_year = date("Y",strtotime($the_date));
			$log->the_month = date("m",strtotime($the_date));
			$log->the_day = date("d",strtotime($the_date));
						$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
						$week_num = date("w",strtotime($the_date));//星期几
			$log->the_week = $weekArr[$week_num];	
						
			if($log->save()){
				alert('success', L('EDIT_LOG_SUCCESS'), U('log/mylog_view','id='.$_POST['log_id']));
			}else{
				alert('error', L('EDIT_LOG_FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	public function edit(){
		if($_GET['id']){
			$log = M('Log');
			$this->log =  $log->where('log_id = %d', $_GET['id'])->find();
			$this->alert = parseAlert();
			$this->display();
		} elseif ($_POST['submit']){
			$log = M('Log');
			$log -> create();
			if($log->save()){
				alert('success', L('EDIT_LOG_SUCCESS'), $_SERVER['HTTP_REFERER']);
			}else{
				alert('error', L('EDIT_LOG_FAILED'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function mylog_add(){
		if($this->isPost()){
			if(!trim($_POST['subject'])) alert('error',L('NEED_LOG_TITLE'),U('log/index'));
			if(!trim($_POST['content'])) alert('error',L('NEED_LOG_CONTENT'),U('log/index'));
			
			$the_date = trim($_POST['the_date']);
			
			$log = M('Log');
			$log->create();
			$log->the_date = trim($_POST['the_date']);
			$log->create_date = time();
			$log->update_date = time();
			$log->the_year_month = date("Y-m",strtotime($the_date));
			$log->the_year = date("Y",strtotime($the_date));
			$log->the_month = date("m",strtotime($the_date));
			$log->the_day = date("d",strtotime($the_date));
						$weekArr=array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
						$week_num = date("w",strtotime($tm));//星期几
			$log->the_week = $weekArr[$week_num];			
			
			
			$log->role_id = session('role_id');
			if($log_id = $log->add()){	
				if (intval($_POST['business_id'])) {
					M('RBusinessLog')->add(array('business_id'=>intval($_POST['business_id']),'log_id'=>$log_id));
				}
				if (intval($_POST['task_id'])) {
					M('RLogTask')->add(array('task_id'=>intval($_POST['task_id']),'log_id'=>$log_id));
				}
				if (intval($_POST['product_id'])) {
					M('RLogProduct')->add(array('product_id'=>intval($_POST['product_id']),'log_id'=>$log_id));
				}
				if (intval($_POST['customer_id'])) {
					M('RCustomerLog')->add(array('customer_id'=>intval($_POST['customer_id']),'log_id'=>$log_id));
				}
				if($_POST['submit'] == L('SAVE')){
					alert('success',L('ADD SUCCESS', array(L('LOG'))),U('log/index'));
				}else{
					alert('success',L('ADD SUCCESS', array(L('LOG'))),U('log/mylog_add'));
				}
			}else{
				alert('error',L('ADD_LOG_FAILED'),U('log/index'));
			}
		}else{
			$this->current_time = time();
			$this->alert = parseAlert();
			
			$the_date = $datas['create_time'] = date("Y-m-d", mktime());
			$this->assign("the_date",$the_date);
			
			$this->display();
		}
	}
	
	public function log_delete () {
		$model = array("rLeadsLog","rBusinessLog","rLogProduct","rCustomerLog","rContactsLog","rLogTask","rEventLog","rFinanceLog");
		if ($_GET['id']){
			$i = 0;
			$log_id = intval($_GET['id']);
			foreach ($model as $v){
				if(M($v)->where('log_id = %d',$log_id)->delete()) $i++;
			}
			if($i == 1){
				$message = M('log')->where('log_id = %d',$log_id)->delete() ? L('DELETE_RELATED_LOG_SUCCESS') : L('DELETE_RELATED_LOG_FAILED');
				alert('success' , $message , U('log/index'));
			} elseif (M('log')->where('log_id = %d',$log_id)->delete()){
				alert('success', L('DELETED SUCCESSFULLY'),  $_SERVER['HTTP_REFERER']);
			}
		} elseif (is_array($_POST['log_id'])) {
			$i = 0;
			foreach ($_POST['log_id'] as $v) {
				foreach ($model as $vv){
					if(M($vv)->where('log_id = %d',$v)->delete()){
						$i++;
					}
				}
			}
			if($i >= 1){
				$log_ids = implode(',', $_POST['log_id']);
				if(M('log')->where('log_id in (%s)', $log_ids)->delete()){
					alert('success', L('DELETE_RELATED_LOG_SUCCESS'),  $_SERVER['HTTP_REFERER']);
				} else {
					alert('success', L('DELETE_RELATED_LOG_FAILED'),  $_SERVER['HTTP_REFERER']);
				}
			}else {
				$log_ids = implode(',', $_POST['log_id']);
				if(M('log')->where('log_id in (%s)', $log_ids)->delete()){
					alert('success', L('DELETE_RELATED_LOG_SUCCESS'),  $_SERVER['HTTP_REFERER']);
				} else {
					alert('success', L('DELETE_RELATED_LOG_FAILED'),  $_SERVER['HTTP_REFERER']);
				}
			}
		}
		
	}

	public function category(){
		$m_category = M('LogCategory');
		$this->category_list = $m_category->order('order_id')->select();
		$this->alert=parseAlert();
		$this->display();
	}
	
	public function categoryAdd(){
		if ($this->isPost()) {
			$m_category = M('LogCategory');
			if($m_category->create()){
				if ($m_category->add()) {
					alert('success', L('ADD SUCCESS', array(L('LOG'))), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('ADD_FAILED_CONTACT_ADMINISTRATOR'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('ADD_FAILED_CONTACT_ADMINISTRATOR'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$this->alert=parseAlert();
			$this->display();
		}
	}
	
	public function categoryEdit(){
		$m_category = M('LogCategory');
		if ($this->isGet()) {
			$category_id = intval(trim($_GET['id']));
			$this->log_category = $m_category->where('category_id = %d', $category_id)->find();
			$this->display();
		} else {
			if ($m_category->create()) {
				if ($m_category->save()) {
					alert('success', L('EDIT_LOG_SUCCESS'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('DATA_NO_MODIFIED'), $_SERVER['HTTP_REFERER']);
				}
			} else {
				alert('error', L('MODIFY_FAILED_CONTACT_ADMINISTRATOR'), $_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	public function categoryDelete(){
		if ($_POST['category_id']) {
			$id_array = $_POST['category_id'];
			if (M('Log')->where('category_id <> 1 and category_id in (%s)', implode(',', $id_array))->select()) {
				alert('error', L('DELETE_FAILED_PLEASE_DELETE_ONE_BY_ONE'), $_SERVER['HTTP_REFERER']);
			} else {
				if (M('LogCategory')->where('category_id in (%s)', implode(',', $id_array))->delete()) {
					alert('success', L('DELETED SUCCESSFULLY'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('DELETE_RELATED_LOG_FAILED'), $_SERVER['HTTP_REFERER']);
				}
			}
		} elseif($_POST['old_id']){
			$old_id = intval($_POST['old_id']);
			$new_id = intval($_POST['new_id']);
			if($old_id && $new_id){
				if (M('LogCategory')->where('category_id <> 1 category_id = %d', $old_id)->delete()) {
					M('Log')->where('category_id = %d', $old_id)->setField('category_id', $new_id);
					M('LogCategory')->where('category_id = %d', $old_id)->setField('category_id', $new_id);
					alert('success', L('DELETED SUCCESSFULLY'), $_SERVER['HTTP_REFERER']);
				} else {
					alert('error', L('MODULE_LOG_IS_SYSTEM_FIELDS_CAN_NOT_BE_DELETED'), $_SERVER['HTTP_REFERER']);
				}
			}else{
				alert('error', L('DELETE_FAILED_FOR_INVALIDATE_PARAMETER'), $_SERVER['HTTP_REFERER']);
			}
		} else {
			$old_id = intval(trim($_GET['id']));
			$this->old_id = $old_id;
			$this->statusList = M('LogCategory')->where('category_id <> %d', $old_id)->select();
			$this->display();
		}
	}
	
	public function categorySort(){
		if ($this->isGet()) {
			$status = M('LogCategory');
			$a = 0;
			foreach (explode(',', $_GET['postion']) as $v) {
				$a++;
				$status->where('category_id = %d', $v)->setField('order_id',$a);
			}
			$this->ajaxReturn('1', L('SAVE_SUCCESSFUL'), 1);
		} else {
			$this->ajaxReturn('0', L('SAVE_FAILED'), 1);
		}
	}
	
	/**
	* 获取指定月份的第一天开始和最后一天结束的时间戳
	*
	* @param int $y 年份 $m 月份
	* @return array(本月开始时间，本月结束时间)
	*/
	public function mFristAndLast($y="",$m=""){
		if($y=="") $y=date("Y");
		if($m=="") $m=date("m");
		
		$m=sprintf("%02d",intval($m));
		$y=str_pad(intval($y),4,"0",STR_PAD_RIGHT);
		
		$m>12||$m<1?$m=1:$m=$m;
		
		$firstday=strtotime($y.$m."01000000");
		$firstdaystr=date("Y-m-01",$firstday);
		$lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
		
		return array("firstday"=>$firstday,"lastday"=>$lastday);
	}

	
}
