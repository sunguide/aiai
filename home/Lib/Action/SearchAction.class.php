<?php
// 本类由系统自动生成，仅供测试用途
class SearchAction extends Action {
    public function index(){
		$Articles = M('Articles');
		$data = $Articles->where("article_status = 'publish'")->select();
		$this->assign('list',$data);
		$this->display();
    }
	public function sendSms(){
		$this->display();
	}
	public function holiday(){
		$this->display();
	}
	public function importContact(){
		$this->display();
	}
	public function noSend(){
		$this->display();
	}
	public function alreadySend(){
		$this->display();
	}
}