<?php 

class WorkOutsideAction extends Action {
    
	public function _initialize(){
		$action = array(
			'permission'=>array('add', 'delete', 'edit'),
			'allow'=>array('index','widget_edit','widget_delete','widget_add')
		);
		B('Authenticate', $action);
	}
	
	public function index(){
		$p = isset($_GET[p]) ? intval($_GET[p]) : 1 ;
		if(session('admin')){
			$rows = M('kaoqinWorkOutside')->page($p.',15')->order('update_time desc')->select();
			$total = M('kaoqinWorkOutside')->count();
			$users = M('user')->where('status=1')->select();
		}else{
			$user_id = session('user_id');
			$rows = M('kaoqinWorkOutside')->where("user_id = $user_id")->page($p.',10')->order('update_time desc')->select();
			$total = M('kaoqinWorkOutside')->where("user_id = $user_id")->count();
			$users[0][user_id] = $user_id;
			$users[0][name] = session('name');
		}
		$q_user = $_GET[q_user];
		$q_month = $_GET[q_month];
		if($q_user or $q_month){
			$rows = M('kaoqinWorkOutside')->where("user_id = $q_user and out_date like '$q_month%'")->page($p.',10')->order('update_time desc')->select();
			$total = M('kaoqinWorkOutside')->where("user_id = $q_user and out_date like '$q_month%'")->count();
		}
		foreach($rows as &$row){
			$row[username] = M('user')->where("user_id=$row[user_id]")->getField('name');
		}
		$this->rows = $rows;
		$this->users = $users;
		import("@.ORG.Page");
		$page = new Page($total, 15);
		$this->show = $page->show();
		$this->display();
	}

	public function add(){
		if(IS_POST){
			$model = M("kaoqinWorkOutside");
			$model->create();
			$model->create_time = time();
			$model->update_time = time();
			if($model->add()){
				$this->success('操作成功！', U('index'));
			}else{
				$this->error($model->getDbError());
			}
		}else{
			$this->users = M('user')->where("status=1")->select();
			$this->display('edit');
		}
	}

	public function delete(){
		$id = $_GET[id];
		if(M("kaoqinWorkOutside")->delete($id)){
			$this->success('操作成功！', U('index'));
		}else{
			$this->error(M("kaoqinWorkOutside")->getDbError());
		}
	}

	public function edit(){
		if(IS_POST){
			$model = M("kaoqinWorkOutside");
			$model->create();
			$model->update_time = time();
			if($model->save()){
				$this->success('操作成功！', U('index'));
			}else{
				$this->error($model->getDbError());
			}
		}else{
			$id = $_GET[id];
			$row = M("kaoqinWorkOutside")->find($id);
			$row[username] = M('user')->where("user_id=$row[user_id]")->getField('name');
			$this->row = $row;
			$this->display();
		}
	}
}