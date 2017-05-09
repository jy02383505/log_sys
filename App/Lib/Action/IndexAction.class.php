<?php 
// 
class IndexAction extends Action {
    
	public function _initialize(){
		$action = array(
			'permission'=>array(),
			'allow'=>array('index','widget_edit','widget_delete','widget_add')
		);
		B('Authenticate', $action);
	}
	
	public function index(){
		$user = M('User');
		$m_announcement = M('announcement');
		$dashboard = $user->where('user_id = %d', session('user_id'))->getField('dashboard');
		$widget = unserialize($dashboard);		
		$this->widget = $widget;
		if (!F('smtp')) {
			alert('info', L('NOT_CONFIGURED_SMTP_INFORMATION_CLICK_HERE_TO_SET',array(U('setting/smtp'))));
		}
		if (!F('defaultinfo')) {
			alert('info', L('SYSTEM_INFORMATION_NOT_CONFIGURED_BY_DEFAULT_CLICK_HERE_TO_SET',array(U('setting/defaultinfo'))));
		}
		$where['department'] = array('like', '%('.session('department_id').')%');
		$where['status'] = array('eq', 1);
		$this->announcement_list = $m_announcement->where($where)->select();
		$this->alert = parseAlert();
		
		
		$this->display("index");
	}
	
	public function widget_edit(){
		$user = M('User');
		$dashboard = $user->where('user_id = %d', session('user_id'))->getField('dashboard');
		$widgets = unserialize($dashboard);
		if(isset($_GET['id']) && $_GET['id']!=''){
			$this->edit_demo = $widgets[$_GET['id']];
			$this->display();
		} elseif(isset($_POST['widget_id']) && $_POST['widget_id']!='') {
			$title = $_POST['title']!='' && isset($_POST['title']) ? $_POST['title'] : '未定义组件';	
			$widgets[$_POST['widget_id']]['title'] = $title;
			$widgets[$_POST['widget_id']]['widget'] = $_POST['widget'];
			$widgets[$_POST['widget_id']]['style'] = $_POST['style'];
			$widgets[$_POST['widget_id']]['limit'] = isset($_POST['limit']) ? intval($_POST['limit']) : 10;
			$newdashboard['dashboard'] = serialize($widgets);
			if($user->where('user_id = %d', session('user_id'))->save($newdashboard)){
				alert('success', L('MODIFY_THE_COMPONENT_INFORMATION_SUCCESSFULLY',array($_POST['widget'])), U('index/index'));
			}else{
				alert('error', L('MODIFY_THE_COMPONENT_INFORMATION_NO_CHANGE',array($_POST[widget])), U('index/index'));
			}
		}
	}
	
	public function widget_delete(){
		if(isset($_GET['id']) && $_GET['id']!=''){
			$user = M('User');
			$dashboard = $user->where('user_id = %d', session('user_id'))->getField('dashboard');
			$widget = unserialize($dashboard);
			unset($widget[$_GET['id']]);
			foreach($widget as $key=>$value){
				$widget[$key]['id'] = $key;
			}
			$newdashboard['dashboard'] = serialize($widget);
			if($user->where('user_id = %d', session('user_id'))->save($newdashboard)){
				alert('success', L('THE_COMPONENT_WAS_REMOVED_SUCCESSFULLY'), U('index/index'));
			}else{
				alert('error', L('THE_COMPONENT_WAS_REMOVED_FAILURE'),$_SERVER['HTTP_REFERER']);
			}
		}
	}
	
	//serialize  unserialize
	public function widget_add(){
		if($_POST['submit']){
			if($_POST['widget']){
				$user = M('User');
				$title = $_POST['title']!='' && isset($_POST['title']) ? $_POST['title'] : L('UNNAMED_COMPONENT');
				$limit = isset($_POST['limit']) ? intval($_POST['limit']) : 10;				
				$dashboard = $user->where('user_id = %d', session('user_id'))->getField('dashboard');
				$widget = unserialize($dashboard);
				if(!is_array($widget)){
					$widget = array();
				}
				array_unshift($widget, array('widget'=>$_POST['widget'], 'style'=>$_POST['style'], 'title'=>$title, 'limit'=>$limit));
				foreach($widget as $key=>$value){
					$widget[$key]['id'] = $key;
				}
				$newdashboard['dashboard'] = serialize($widget);
				if($user->where('user_id = %d', session('user_id'))->save($newdashboard)){
					alert('success', L('ADD_COMPONENTS_TO_SUCCESS'), $_SERVER['HTTP_REFERER']);
				}
			}else{
				alert('error', L('ADD_THE_COMPONENT_FAILS_PLEASE_FILL_IN_THE_COMPONENT_NAME'), $_SERVER['HTTP_REFERER']);
			}
		}else{
			$this->alert = parseAlert();
			$this->display();
		}
	}
}