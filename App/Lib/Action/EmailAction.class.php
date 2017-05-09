<?php
	class EmailAction extends Action{
		public function _initialize(){
			$action = array(
				'permission'=>array(''),
				'allow'=>array('')
			);
			B('Authenticate',$action);
		}
		public function index(){
			$templateList = M('EmailTemplate')->order('order_id')->select();
			$this->templateList = $templateList;
			$this->alert=parseAlert();
			$this->display();
		}
		
		public function add(){
			if($this->isPost()){
				$m_template = M('EmailTemplate');
				if(!$_POST['subject']) alert('error', L('TEMPLATE_NAME_CANNOT_BE_EMPTY'), $_SERVER['HTTP_REFERER']);
				if(!$_POST['content']) alert('error', L('MAIL_CONTENTS_CAN_NOT_BE_EMPTY'), $_SERVER['HTTP_REFERER']);	
				if($m_template->create()){
						alert('success', L('ADD_A_SUCCESS'), $_SERVER['HTTP_REFERER']);
					if($m_template->add()){
					}else{
						alert('error', L('ADD_FAILURE'), $_SERVER['HTTP_REFERER']);
					}
				}else{
					alert('error', L('ADD_FAILURE'), $_SERVER['HTTP_REFERER']);
				}
			}else{
				$this->display();
			}
		}
		
		public function edit(){
			$m_template = M('emailTemplate');
			if($this->isPost()){
				
				if(!$_POST['subject']) alert('error', L('TEMPLATE_SUBJECT_CANNOT_BE_EMPTY'), $_SERVER['HTTP_REFERER']);
				if(!$_POST['content']) alert('error', L('TEMPLATE_CONTENT_CANNOT_BE_EMPTY'), $_SERVER['HTTP_REFERER']);
				if($m_template->create()){
					if($m_template->save()){
						alert('success', L('MODIFY_THE_SUCCESS'), $_SERVER['HTTP_REFERER']);
					}else{
						alert('error', L('MODIFY_THE_FAILURE'), $_SERVER['HTTP_REFERER']);
					}
				}else{
					alert('error', L('MODIFY_THE_FAILURE'), $_SERVER['HTTP_REFERER']);
				}
			}else{
				if($_GET['id']){
					$this->template = $m_template->where('template_id = %d', intval($_GET['id']))->find();
					$this->display();
				}else{
					alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
				}
				
			}
		}
		
		public function delete(){
			if($this->isPost()){
				if(!empty($_POST['template_id'])){
					$m_template = M('EmialTemplate');
					$template_ids = $_POST['template_id'];
					if($m_template->where('template_id in (%s)', implode(',', $template_ids))->delete()){
						alert('success', L('DELETED SUCCESSFULLY'), $_SERVER['HTTP_REFERER']);
					}else{
						alert('error', L('DELETED_FAILURE'), $_SERVER['HTTP_REFERER']);
					}
				}else{
					alert('error', L('PARAMETER_ERROR'), $_SERVER['HTTP_REFERER']);
				}
			}else{
				$this->display();
			}
		}
		
		public function orderSort(){
			if ($this->isGet()) {
				$m_template = M('EmailTemplate');
				$a = 0;
				foreach (explode(',', $_GET['postion']) as $v) {
					$a++;
					$m_template->where('template_id = %d', $v)->setField('order_id',$a);
				}
				$this->ajaxReturn('1', L('SAVE_SUCCESS'), 1);
			} else {
				$this->ajaxReturn('0', L('SAVE_FAILURE'), 1);
			}
		}
	}