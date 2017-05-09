<?php 

class YearVacationAction extends Action {
    
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
			$rows = M('yearVacation')->page($p.',15')->order('update_time desc')->select();
			$total = M('yearVacation')->count();
		}else{
			$user_id = session('role_id');
			$rows = M('yearVacation')->where("user_id = $user_id")->page($p.',10')->order('update_time desc')->select();
			$total = M('yearVacation')->where("user_id = $user_id")->count();
		}
		foreach($rows as &$row){
			$row[username] = M('user')->where("user_id=$row[user_id]")->getField('name');
		}
		$this->rows = $rows;
		import("@.ORG.Page");
		$page = new Page($total, 15);
		$this->show = $page->show();
		$this->display();
	}

	public function add(){
		if(IS_POST){
			$model = M("yearVacation");
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
		if(M("yearVacation")->delete($id)){
			$this->success('操作成功！', U('index'));
		}else{
			$this->error(M("yearVacation")->getDbError());
		}
	}

	public function edit(){
		if(IS_POST){
			$model = M("yearVacation");
			$model->create();
			$model->update_time = time();
			if($model->save()){
				$this->success('操作成功！', U('index'));
			}else{
				$this->error($model->getDbError());
			}
		}else{
			$id = $_GET[id];
			$row = M("yearVacation")->find($id);
			$row[username] = M('user')->where("user_id=$row[user_id]")->getField('name');
			$this->row = $row;
			$this->display();
		}
	}
}