<?php
// 本类由系统自动生成，仅供测试用途
class PositionAction extends Action {
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
		
		$this->display();
    }
	public function getPosition(){
		$Position = M('Position');
		$param = $this->_param();
		if($param['id']){
			$where=array('position_image' =>$param['id']);
			$data = $Position->where($where)->find();
			if($data){
				$this->ajaxReturn($data,"获得结果",1);
			}else{
				$this->ajaxReturn($data,"获得结果",0);
			}
			
		}
	
	}
	public function positionOperate(){
		$uid->_uid;
		$param = $this->_param();
		switch($param['action']){
			case 1:
				$action = 'view';
				break;
			case 2:
				$action = 'todo';
				break;
			case 3:
				$action = 'havedone';
				break;
			default:
				$error = true;
				break;
		}
		if(!$error && $param['pid']){
			$vi = $this->updatePositionStat($uid,$param['pid'],$action);
			if($vi){
				$this->ajaxReturn(1,"操作成功",1);
			}else{
				$this->ajaxReturn(2,"操作出错",0);
			}
		}else{
			$this->ajaxReturn(0,"出错".$param['pid'],0);
		}
	
	}
	public function feelVote(){
		$PositionStat = M('PositionStat');
		$Position = M('Position');
		$uid = $this->_uid;
		$voteScore = $this->_get('vote');
		$w = array('owner_uid' => $uid, 'position_id' => $this->_get('pid'));
		$update = array("feel_vote" => $voteScore,'update_time' => datetime());
		if($PositionStat->where($w)->find()){
			
			$v = $PositionStat->where($w)->save($update);//setInc($type,1);
		}else{
			$w['status'] = 1;//没有就新增
			$w['feel_vote'] = $voteScore;
			$w['create_time'] = datetime();
			$w['update_time'] = datetime();
			$v = $PositionStat->add($w);
		}
		
		//echo $PositionStat->getLastSql();
		if($v){
			$this->ajaxReturn(1,"投票成功",1);
		}else{
			$this->ajaxReturn(0,"投票失败",0);
		}
	}
	public function positionFeel(){
		$PositionStat = M('PositionStat');
		$Position = M('Position');
		$uid = $this->_uid;
		if(!$uid) $uid = 0;
		$feel = $this->_get('feel');
		$w = array('owner_uid' => $uid, 'position_id' => $this->_get('pid'));
		$update = array("feel_content" => $feel,'update_time' => datetime());
		if($PositionStat->where($w)->find()){
			
			$v = $PositionStat->where($w)->save($update);//setInc($type,1);
		}else{
			$w['status'] = 1;//没有就新增
			$w['feel_content'] = $feel;
			$w['create_time'] = datetime();
			$w['update_time'] = datetime();
			$v = $PositionStat->add($w);
		}
		
		//echo $PositionStat->getLastSql();
		if($v){
			$this->ajaxReturn(1,"投票成功",1);
		}else{
			$this->ajaxReturn(0,"投票失败",0);
		}
	}
	public function allPosition(){
		$this->display();
	}
	public function getCategory(){
		$Position = M('Position');
		$param = $this->_param();
		$where = array();
		$categoryID = $param['category_id'];
		$results = $Position->field('A.*')->alias('A')->join('aiai_position_category B ON A.id = B.position_id')->where("B.category_id = $categoryID")->select();
		$this->assign('list',$results);
		$this->display('Index/index');
	}
	
		//发布文章
	public function publish(){
		$Position = M('Position');	
		if($_POST['position_title'] == ''){
			$results = $Position->field('position_image')=>where("article_status = 'publish'")->select();
			dump($results);
			//echo $Position->getLastSql();
			$this->assign('list',$results);
			$this->display();
		}else{
			$Position = M('Position');	
			$post = $this->_post();
			$uid = $this->_uid;
			$Position->create();
			$Position->uid = $uid;
			$Position->article_status = 'draft';
			$Position->create_time = datetime();
			$v = $Position->add();
			if($v){
				$this->ajaxReturn(1,"发布成功",1);
			}else{
				$this->ajaxReturn(0,"发布失败",0);
			}

		
			
		}	
	}
	public function nodo(){
		$Position = M('Position');
		$param = $this->_param();
		$where = array();
		$uid = $this->_uid;
		$results = $Position->field('A.*')->alias('A')->join('aiai_position_stat B ON A.id = B.position_id')->where("B.todo = ''")->select();
		//echo $Position->getLastSql();
		//echo count($results);
		$this->assign('list',$results);
		$this->display('Position/myFavorites');

	}
	public function todo(){
		$PositionStat = M('PositionStat');
		$param = $this->_param();
		$where = array();
		$uid = $this->_uid;
		$results = $PositionStat->field('B.*')->alias('A')->join('aiai_position B ON A.position_id = B.id')->where("A.owner_uid = $uid AND A.todo > 0")->select();
		//echo $PositionStat->getLastSql();
		//dump($results);
		$this->assign('list',$results);
		$this->display('Position/myFavorites');

	}
	public function havedone(){
		$Position = M('Position');
		$param = $this->_param();
		$where = array();
		$uid = $this->_uid;
		$results = $Position->field('A.*')->alias('A')->join('aiai_position_stat B ON A.id = B.position_id')->where("A.owner_uid = $uid AND B.havedone > 0")->select();
		//echo $Position->getLastSql();
		//echo count($results);
		$this->assign('list',$results);
		$this->display('Position/myFavorites');

	}
	//我的收藏
	public function myFavorites(){
		$PositionStat = M('PositionStat');
		$param = $this->_param();
		$where = array();
		$uid = $this->_uid;
		$results = $PositionStat->field('B.*')->alias('A')->join('aiai_position B ON A.position_id = B.id')->where("A.owner_uid = $uid AND A.favorite > 0")->select();
		//echo $PositionStat->getLastSql();
		//dump($results);
		$this->assign('list',$results);
		$this->display();
	
	}
	public function myCreated(){
		$Position = M('Position');
		$param = $this->_param();
		$where = array();
		$uid = $this->_uid;
		$results = $Position->where("owner_uid = $uid")->select();
		//echo $Position->getLastSql();
		//dump($results);
		$this->assign('list',$results);
		$this->display('Index/index');
	}
	//加上收藏
	public function favorites(){
		$PositionStat = M('PositionStat');
		$Position = M('Position');
		$uid = $this->_uid;
		if(!$uid) $uid = 0;
		$w = array('owner_uid' => $uid, 'position_id' => $this->_get('pid'));
		$update = array("favorite" => 1,'update_time' => datetime());
		if($PositionStat->where($w)->find()){
			
			$v = $PositionStat->where($w)->save($update);//setInc($type,1);
		}else{
			$w['status'] = 1;//没有就新增
			$w['favorite'] = 1;
			$w['create_time'] = datetime();
			$w['update_time'] = datetime();
			$v = $PositionStat->add($w);
		}
		
		//echo $PositionStat->getLastSql();
		if($v){
			$this->ajaxReturn(1,"收藏成功",1);
		}else{
			$this->ajaxReturn(0,"收藏失败",0);
		}
	
	}
	
	//文章详情
	public function detail(){
		$get = $this->_get();
		$Position = M('Position');
		if($get['id']==''){
			//echo '404';
		}else{
			
			$data = array(
					'id' => trim($get['id'])
			);
			$v = $Position->where($data)->find();
			
			if($v){
				$vi = $this->updatePositionStat($uid,$get['id'],'view');
				$this->assign('list',$v);
			}else{
				$this->assign('list',0);
			}
					
		}
		$this->display();
	}
	//更新用户对姿势的操作用户uid|姿势id|(view todo havedone favorite)
	public function updatePositionStat($uid,$pid,$type){
		$PositionStat = M('PositionStat');
		$Position = M('Position');
		if(!$uid) $uid = 0;
		$w = array('owner_uid' => $uid, 'position_id' => $pid);
		$update = array("$type" => array('exp',"$type+1"),'update_time' => datetime());
		if($PositionStat->where($w)->find()){
			
			$v = $PositionStat->where($w)->save($update);//setInc($type,1);
		}else{
			$w[$type] = 1;//没有就新增
			$w['create_time'] = datetime();
			$w['update_time'] = datetime();
			$v = $PositionStat->add($w);
		}
		$Position->where("id = $pid")->save($update);
		//echo $PositionStat->getLastSql();
		if($v) return true;
		else return false;
	}
}