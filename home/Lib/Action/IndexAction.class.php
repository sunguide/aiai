<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends Action {
	private $_uid = '';
	public function _initialize(){
		$this->_uid = session('uid');
		$data = array(
			''	
		);
		if(!$this->_uid) $this->redirect('Public/login');
		//B('Authenticate', $action);
	}
    public function index(){
		$Position = M('Position');
		$data = $Position->where("article_status = 'publish'")->order('create_time DESC')->select();
		$this->assign('list',$data);
		$this->display();
    }
	
}