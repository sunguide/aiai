<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	private $_uid = '';
	public function _initialize(){
		$this->_uid = session('uid');
		if(!$this->_uid) {
			$this->redirect("Public/login");//(503,"对不起，您登录超时了，请重新登录！",1);
		}
		//B('Authenticate', $action);
	}
    public function index(){
		$this->display();
    }
	public function waitCheckMsg(){
		$this->display();
	}
	public function holiday(){
		$this->display();
	}
	public function importContact(){
		$this->display();
	}
	public function alreadyCheckMsg(){

		$this->display();
	}
	public function alreadySendMsg(){
		$this->display();
	}
	public function exportContact(){
		$this->display();
	}
	public function accountManage(){
		$this->display();
	}
	public function chargeSignature(){
		$this->display();
	}
	public function consumeSignature(){
		$this->display();
	}
	public function loginLog(){
		$this->display();
	}
	public function roadSetting(){
		$this->display();
	}
}