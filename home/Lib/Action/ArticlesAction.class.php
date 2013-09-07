<?php

class ArticlesAction extends Action {
	private $_uid = '';
	/*
	public function _initialize(){
		$this->_uid = session('uid');
		$data = array(
			''	
		);
		if(!$this->_uid) $this->ajaxReturn(404,"",1);
		//B('Authenticate', $action);
	}
	*/
    public function index(){
		$Articles = M('Articles');
		$data = $Articles->where("article_status = 'publish'")->select();
		//dump($data);
		$this->display();
    }
		//发布文章
	public function publish(){
		if($_POST['position_title'] == ''){
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
	//收藏
	public function favorites(){
		$get = $this->_get();
		$this->ajaxReturn(1,"已收藏",1);
	
	}
	//账号信息查看与修改
	public function accountInfo(){
		if($_GET['title'] == ''){
			$this->display();
		}else{
			$Account = M('Articles');
			$data = $Account->where('uid == $this->_uid')->find();
			$result = array(
				'company_name' => $data['company_name'],
				'company_contacter' => $data['company_contacter'],
				'contact_telphone' => $data['contact_telphone'],
				'contact_mobilephone' => $data['contact_mobilephone'],
				'industry' => $data['industry'],
				'customer_manager' => $data['customer_manager'],
				'company_fax' => $data['company_fax'],
				'zip_code' => $data['zip_code'],
				'email' => $data['email'],
				'company_address' => $data['company_address'],
			);
			if(empty($data)){
				$this->ajaxReturn(0,"未查到账号信息，请输入。",0);
			}else{
				$this->ajaxReturn($result,"查询到用户的账号信息如下",1);
			}
		}	
	}
	//修改密码
	public function resetPassword(){
		$get = $this->_get();
		$uid = session('uid');
		if(!$uid) $this->redirect('Public:login');
		$Account = M('Account');
		if($get['password']==''){
			$this->error("请输入密码");
		}else if($get['password'] != $get['repassword']){
			$this->error("两次输入的密码不对!");			
		}else{
			
			$data = array(
					'uid'      => $uid,
					'password' => $get['password']
			);
			$Account->save($data);
		}
	}
	//文章详情
	public function detail(){
		$get = $this->_get();
		$Position = M('Position');
		if($get['id']==''){
			echo '404';
		}else{
			
			$data = array(
					'id' => trim($get['id'])
			);
			$v = $Position->where($data)->find();
			
			if($v){
				$this->assign('list',$v);
			}else{
				$this->assign('list',0);
			}
					
		}
		$this->display();
	
	}


}