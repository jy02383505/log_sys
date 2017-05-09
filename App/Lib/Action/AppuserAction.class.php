<?php 
/**
 *
 * 手机相关模块
 *
 **/
class AppUserAction extends Action {

	public function _initialize(){
		$action = array(
			'permission'=>array('login'),
			'allow'=>array('mylog_add','mylog','mylog_view')
		);
		B('AppAuthenticate', $action);
	}
	/*
		1.登录成功
		2.用户名或密码错误！
		3.您的账号未通过审核，请联系管理员！
		4.您的帐号正在审核中···请耐心等待！
		5.系统没有给您分配任何岗位，请联系管理员！
		6.用户名或密码为空
	*/
	public function login(){
		if ($this->isPost()){
			$m_user = M('user');
			$user = $m_user->where(array('name' => trim($_POST['name'])))->find();
			if ($user['password'] == md5(trim($_POST['password']) . $user['salt'])) {
				if (-1 == $user['status']) {
					$this->ajaxReturn('','error',3);
				} elseif (0 == $user['status']) {
					$this->ajaxReturn('','error',4);
				}else {
					$d_role = D('RoleView');
					$role = $d_role->where('user.user_id = %d', $user['user_id'])->find();
					if (!is_array($role) || empty($role)) {
						$this->ajaxReturn('','error',5);
					} else {
						if($user['category_id'] == 1){
							session('admin', 1);
						}
						session('role_id', $role['role_id']);
						session('position_id', $role['position_id']);
						session('role_name', $role['role_name']);
						session('department_id', $role['department_id']);
						session('name', $user['name']);
						session('user_id', $user['user_id']);
						//userLog($user['user_id']);
						$data['info'] = 'success';
						$data['status'] = 1;
						$data['session_id'] = session_id();
						$data['password'] = md5('123456');
						$this->ajaxReturn($data,'JSON');
					}
				}
			} else {
				$this->ajaxReturn('','error',2);				
			}
		}else{
			$this->ajaxReturn('','error',6);
		}
	}
	/*  
		1.日志添加成功
		2.日志添加失败
		3.没有接受到参数
		4.标题空
		5.内容空
	*/
	public function mylog_add(){
		if($this->isPost()){
			if(!trim($_POST['rztitle'])) $this->ajaxReturn('',"error",4);;
			if(!trim($_POST['rzcontent'])) $this->ajaxReturn('',"error",5);;
			$log = M('Log');
			$d_role = D('RoleView');
			$role_id = session('role_id');
			$data['subject'] = $_POST['rztitle'];
			$data['content'] = $_POST['rzcontent'];
			$data['create_date'] = time();
			$data['update_date'] = time();
			$data['role_id'] = $role_id;
			$data['category_id'] = 4;
			
			if($log->add($data)){
				$this->ajaxReturn(session_id(),"success",1);
			}else{
				$this->ajaxReturn('',"error",2);
			}
		}else{
			$this->ajaxReturn('',"error",3);
		}
	}
	public function mylog(){
		$m_log = M('Log');
		$m_comment = M('Comment');
		$by = isset($_POST['by']) ? trim($_POST['by']) : 'me';
		$d_role = D('RoleView');
		$user_id = session('user_id');
		$role_id = session('role_id');
		$where = array();
		$params = array();
		$order = "";
		$below_ids = getSubRoleByRole($role_id,false);
		$all_ids = getSubRoleByRole($role_id);
		$module = isset($_GET['module']) ? trim($_GET['module']) : '';
		switch ($by) {
			case 'today' : $where['create_date'] =  array('gt',strtotime(date('Y-m-d', time()))); break;
			case 'week' : $where['create_date'] =  array('gt',(strtotime(date('Y-m-d', time())) - (date('N', time()) - 1) * 86400)); break;
			case 'month' : $where['create_date'] = array('gt',strtotime(date('Y-m-01', time()))); break;
			case 'add' : $order = 'create_date desc';  break;
			case 'update' : $order = 'update_date desc';  break;
			case 'sub' : $where['role_id'] = array('in',implode(',', $below_ids)); break;
			case 'me' : $where['role_id'] = $role_id; break;
			default :  $where['role_id'] = $role_id; break;
		}
		$where['category_id'] = 4;
		if (!isset($where['role_id'])) {
			$where['role_id'] = array('in',implode(',', getSubRoleByRole($role_id))); 
		}
		
		if ($order) {
			$list = $m_log->where($where)->field('log_id,subject')->order($order)->limit(15)->select();
		} else {
			$p = isset($_POST['p']) ? intval($_POST['p']) : 1 ;
			$list = $m_log->where($where)->field('log_id,role_id,subject')->page($p.',10')->order('log_id desc')->select();
			foreach($list as $key=>$value){
				$creator = getUserByRoleId($value['role_id']);
				$list[$key]['username'] = $creator['user_name'];
			}
			$count = $m_log->where($where)->count();
			$pages = ceil($count/10);
		}
		if(empty($list)){
			$list = array();
		}
		$data['list'] = $list;
		$data['pages'] = $pages;
		$data['status'] = 1;
		$data['info'] = 'success';
		$this->ajaxReturn($data,'JSON');
	}
	public function mylog_view(){
		if($this->isPost()){
			$m_log = M('Log');
			$m_comment = M('Comment');
			$where = array();
			if(intval($_POST['log_id'])){
				$log_id = $_POST['log_id'];
				$where['log_id'] = $log_id;
				$where['category_id'] = 4;
			}else{
				$this->ajaxReturn('',"error",5);
			}
			$role_id = session('role_id');
			$list = $m_log->where($where)->find();
			$list['create_date'] = date('Y-m-d H:i:s', $list['create_date']);
			$creator = getUserByRoleId($list['role_id']);
			$list['username'] = $creator['user_name'];
			if($m_comment->where("module='log' and module_id=%d", $list['log_id'])->select()){
				$list['comment_list'] = D('CommentView')->where('module = "log" and module_id = %d', $log_id)->order('comment.create_time desc')->select();
			}
			if(!empty($list)){
				$data['list'] = $list;
				$data['status'] = 1;
				$data['info'] = 'success';
				$this->ajaxReturn($data,"JSON");
			}else{
				$this->ajaxReturn('',"error",0);
			}
		}else{
			$this->ajaxReturn('',"error",6);
		}
		
	}
	
}