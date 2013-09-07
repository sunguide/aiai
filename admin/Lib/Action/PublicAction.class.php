<?php
	class PublicAction extends Action{
		public function addContact(){
			$this->display();
		}
		public function login(){
			$GET = $this->_get();
			if($GET['account'] && $GET['password']){
				$Account = M('Account');
				$account = $GET['account'];
				$password = MD5($GET['password']);
				$v = $Account->where("account = '$account' AND password = '$password' AND status = 1")->find();
				$Log = new LogAction();
				if($v){
					session('uid',$v['uid']);
					session('account',$v['account']);
					session('login_status',1);
					$Log->loginLog();
					$this->ajaxReturn(1,"登录成功",1);
				}else{
					session('login_status',0);
					//A('Log/loginLog');
					$Log->loginLog();
					$this->ajaxReturn(0,"登录失败",0);
				}			
			}else{
				$this->display();
			
			}
		}
		public function reg(){
			$GET = $this->_get();
			if($GET['account'] && $GET['password']){
				
				$Account = M('Account');
				
				$data['account'] = trim($GET['account']);
				$is = $Account->where($data)->find();
				if($is){
					$this->ajaxReturn(0,"该账号已经注册",2);
				}
				$password = trim($GET['password']);
				if(strlen($password) < 6){
					$this->ajaxReturn(0,"注册失败提示,密码必须为六位以上！",3);
				}else{
				
					$data['password'] = MD5($password);
					$data['status'] = 1;
					$data['create_time'] = "'now()'";
					$v = $Account->add($data);
					if($v){
						session('uid',$v);
						$this->ajaxReturn(1,"注册成功",1);
					}else{
					 $this->ajaxReturn(0,"注册失败",0);
					}			
				}	
			}else{
				$this->display();
			}
		
		}
		public function logout(){
			session(null);
			$this->redirect("Public/login");
		}
	}
?>