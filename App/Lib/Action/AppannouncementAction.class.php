<?PHP 
class AppannouncementAction extends Action{
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','changestatus','views')
		);
		B('AppAuthenticate', $action);
	}
       
	/* 
	   1: 数据返回
	   2：未找到数据
	*/
	public function index(){
		$m_announcement = M('Announcement'); 		
		$where = array();		
		$p = isset($_POST['p'])?$_POST['p']:1;
		$where['status'] = array('eq',1);		
		$list['data'] = $m_announcement->where($where)->field('announcement_id,title')->order('create_time desc')->Page($p.',10')->select();		
		$list['info'] = 'success';
		$list['status'] = 1;
		if(is_array($list)){ 
		   $this->ajaxReturn($list,'JSON');
		}else{
		   $this->ajaxReturn('','error',2);	
		}
	}
	public function views(){
	  if($_REQUEST['id']){
	     $m_announcement = M('Announcement');
		 $announcement = $m_announcement->field('content,title')->where('announcement_id = %d ',intval($_REQUEST['id']))->find();	 
		 if($announcement){
		    //$announcement['content'] = str_replace('&nbsp;',' ',strip_tags($announcement['content']));		
		    $data['data'] = $announcement;
			$data['info'] = 'success';
			$data['status'] = 1;
		    $this->ajaxReturn($data,'JSON'); 
		 }else{
		   $this->ajaxReturn('','该公告不存在或已被删除！',2);
		 }		 
	  }else{
	     $this->ajaxReturn('','参数错误！',3);
	  }
	}
	public function add(){
		if($_POST['submit']){
			$title = trim($_POST['title']);
			if ($title == '' || $title == null) {
				alert('error',L('TITLE CAN NOT NULL'),$_SERVER['HTTP_REFERER']);
			}
			$d_announcement = M('Announcement');
			if($d_announcement->create()){
				$d_announcement->role_id = session('role_id');
				$d_announcement->department = '('.implode('),(', $_POST['announce_department']).')';
				$d_announcement->create_time = time();
				$d_announcement->update_time = time();
				$d_announcement->add();
				if($_POST['submit'] == L('SAVE')) {
					alert('success', L('NOTICE TO ADD SUCCESS'), U('announcement/index'));
				} else {
					alert('success', L('ADD A SUCCESS'), U('announcement/add'));
				}
			}else{
				alert('error', L('ADD FAILURE'), U('announcement/add'));
			}

		}else{
			$m_department = M('RoleDepartment');
			$department_list = $m_department->select();	
			$this->assign('department_list', getSubDepartment(0,$department_list,'', 1));
			$this->alert = parseAlert();
			$this->display();
		}
	}
	public function view(){
		if($_GET['id']){
			$m_announcement = M('Announcement');
			$m_announcement->where('announcement_id=%d',$_GET['id'])->setInc('hits');			
			$announcement = $m_announcement->where('announcement_id = %d ', $_GET['id'])->find();
			
			$announcement['username'] = D('RoleView')->where('role.role_id = %d', $announcement['role_id'])->getField('user_name');
			//$m_userRole = M('userRole');
			//$announcement['username']  = $m_userRole->where('role_id = %d',$announcement['role_id'])->getField('name');
			
			$m_department = M('RoleDepartment');
			$alldepartment_list = $m_department->select();	
			$department_list = getSubDepartment(0,$alldepartment_list,'', 1);
			$department_id_array = explode(',', $announcement['department']);
			foreach($department_list as $k=>$v){
				if(in_array('('.$v['department_id'].')', $department_id_array)){
				   $department_list[$k]['checked'] = 'checked';
				}else{
				   unset($department_list[$k]);
				}
			}
            var_dump($announcement);			
			$pre = $m_announcement->where('create_time < %d', $announcement['create_time'])->order('create_time desc')->limit(1)->find();
			if($pre) $pre_href = U('announcement/view', 'id='.$pre['announcement_id']);
			$next = $m_announcement->where('create_time > %d', $announcement['create_time'])->limit(1)->find();
			if($next) $next_href = U('announcement/view', 'id='.$next['announcement_id']);
			$data['data'] = array('announcement'=>$announcement,'department_list'=>$department_list,'pre_href'=>$pre_href,'next_href'=>$next_href);
			$data['info'] = 'success';
			$data['status'] = 1;
			//$this->ajaxReturn($data,'JSON');
			//$this->display();
		}else{
			$this->error(L('PARAMETER ERROR'));
		}
	}
	
	public function changeStatus(){
		$m_announcement = M('Announcement');
		$announcement_id = intval($_GET['id']);
		
		if ($announcement_id) {
			$announcement = $m_announcement->where('announcement_id = %d', $announcement_id)->find();
			if ($announcement['status'] == 1) {
				$m_announcement->where('announcement_id = %d', $announcement_id)->setField('status', 2);
				alert('success',L('MODIFY SUCCESS HAS BEEN DISCONTINUED'),$_SERVER['HTTP_REFERER']);
			} elseif($announcement['status'] == 2) {
				$m_announcement->where('announcement_id = %d', $announcement_id)->setField('status', 1);
				alert('success',L('MODIFY SUCCESS HAS BEEN PUBLISHED'),$_SERVER['HTTP_REFERER']);
			} else {
				alert('success',L('SYSTEM ERROR PLEASE CONTACT YOUR ADMINISTRATOR'),$_SERVER['HTTP_REFERER']);
			}
		}else{
			alert('error',L('PARAMETER ERROR'),$_SERVER['HTTP_REFERER']);
		}
	}
	
	public function edit(){
		if($this->isPost()){
			$title = trim($_POST['title']);
			if ($title == '' || $title == null) {
				alert('error',L('THE NAME CANNOT BE EMPTY'),$_SERVER['HTTP_REFERER']);
			}
			$m_announcement = M('Announcement');
			if($m_announcement->create()){
				$m_announcement->department = '('.implode('),(', $_POST['announce_department']).')';
				$m_announcement->update_time = time();
				if($m_announcement->save()){
					if($_POST['submit'] == L('SAVE')) {
						alert('success', L('ANNOUNCEMENT SAVED SUCCESSFULLY'), U('announcement/index'));
					} else {
						alert('success', L('SAVE THE SUCCESS PLEASE CONTINUE TO INPUT'), U('announcement/add'));
					}
				} else {
					alert('error', L(''),U('announcement/index'));
				}
			}else{
				alert('error', L('TO MODIFY DATA FAILED NO CHANGE'),U('announcement/index'));
			}
		}elseif($_GET['id']){
			$m_announcement = M('Announcement');
			$m_department = M('RoleDepartment');
			$department_list = getSubDepartment(0,$m_department->order('department_id')->select(),'', 1);
			$announcement = $m_announcement->where('announcement_id = %d',$_GET['id'])->find();
			$department_id_array = explode(',', $announcement['department']);

			foreach($department_list as $k=>$v){
				if(in_array('('.$v['department_id'].')', $department_id_array)) $department_list[$k]['checked'] = 'checked';
			}
			$this->assign('department_list', $department_list);
			$this ->announcement = $announcement;
			$this->display();
		}else{
			$this->error(L('PARAMETER ERROR'));
		}
	}
	public function delete(){
		$m_announcement = M('Announcement');
		$announcement_idarray = $_POST['announcement_id'];
		if (is_array($announcement_idarray)) {
			if (!session('?admin')) {
				foreach ($announcement_idarray as $v) {
					if (!$m_announcement->where('announcement_id = %d and role_id = %d', $v, session('role_id'))->find()){
						alert('error', L('YOU DONOT HAVE ALL THE PERMISSIONS ONLY THE AUTHOR OR THE ADMINISTRATOR CAN DELETE'),U('Announcement/index'));
					}
				}
			}
			if ($m_announcement->where('`announcement_id` in (%s)', join(',', $announcement_idarray))->delete()) {
				alert('success', L('DELETED SUCCESSFULLY'),U('Announcement/index'));
			} else {
				$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
			}
		} elseif($_GET['id']) {
			if (!session('?admin')) {
				if (!$m_announcement->where('announcement_id = %d and role_id = %d', $_GET['id'], session('role_id'))->find()){
					alert('error', L('YOU DONOT HAVE ALL THE PERMISSIONS ONLY THE AUTHOR OR THE ADMINISTRATOR CAN DELETE'),U('Announcement/index'));
				}
			}
			
			if($m_announcement->where('announcement_id = %d', $_GET['id'])->delete()){
				alert('success', L('DELETED SUCCESSFULLY'),U('Announcement/index'));
			}else{
				$this->error(L('DELETE FAILED CONTACT THE ADMINISTRATOR'));
			}
		} else {
			alert('error', L('PLEASE CHOOSE TO DELETE ANNOUNCEMENT'),$_SERVER['HTTP_REFERER']);
		}
	}
	
}
