<?php
//性趣闻，爱爱百事
class NewsAction extends Action {
	
	public function index(){
		$this->assign('coming','爱爱囧事，记录分享爱爱点点滴滴！');
		$this->display('Public/coming');
	}
	public function getMore(){
		$data = array(
			'imgURL' =>  "http://jyimg1.meimei22.com/pic/jingyan/2013-9-19/2/2.jpg",
			'title' => "美女"
		);
		$result=array();
		for($i=0;$i<5;$i++){
			$result[] = $data; 
		}
		$this->ajaxReturn($result,"jiegou",1);
	}
}