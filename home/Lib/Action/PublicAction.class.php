<?php
	class PublicAction extends Action{
		public function login(){
			$Account = M('Account');
			$param = $this->_param();
			if(session('uid')) redirect(U('Index/index'));
			$param['account'] = trim($param['account']);
			$param['password'] = trim($param['password']);
			if($param['account'] && $param['password']){
			
				$v = $Account->where("account = '{$param['account']}' AND password = '{$param['password']}'")->find();
				
				if($v){
					session('uid',$v['uid']);
					cookie('uid',$v['uid'],3600000);
					$this->ajaxReturn(2,"登录成功",2);
				}else{
					$s = $Account->where("account = '{$param['account']}'")->find();
					if($s){
						$this->ajaxReturn(0,"密码错误",0);	
					}else{
						$data = array(
							'account' => $param['account'],
							'password' => $param['password'],
							'create_time' => datetime(),
							'status' => 1
						);
						$v = $Account->add($data);
						if($v){
							session('uid',$v);
							cookie('uid',$v,3600000);
							$this->ajaxReturn(1,'<p>请牢记账号<span class="label label-success">'.$param['account'].'</span>和密码<span class="badge badge-info">'.$param['password'].'</span>，以便下次登录',1);
						}else{
							$this->ajaxReturn(0,"对不起，请重试",0);
						}
					}
								
				}
			
			}else{
				$this->display();
			}
			
		
		}
		public function logout(){
			session(null);
			cookie(null);
			$this->redirect('Index/index');
		}
		public function feedback(){
			$Feedback = M('Feedback');
			$param = $this->_param();
			if($param['title']){
				$data = array(
					'owner_uid' => session('uid'),
					'title' => trim($param['title']),
					'content' => $param['content'],
					'create_time' => datetime(),
					'status' => 0
				);
				$v = $Feedback->add($data);
				if($v){
					$this->ajaxReturn(1,"感谢您的提议，我们已经收到，我们会尽快根据您的提议，做出产品改善。",1);
				}else{
					$this->ajaxReturn(0,"很抱歉，由于系统原因，您的建议提交失败，我们已经检测到该错误，会尽快解决。",0);
				}
			}else{
				$this->display();
			
			}
		}
		public function sinaLogin(){
			//生成登录链接
			import("@.ORG.Sina");
			$sina_k='3598307079'; //新浪微博应用App Key
			$sina_s='1c163695f54d5fabe10dd45e747d4ac4'; //新浪微博应用App Secret
			$callback_url=U('Callback/sina');//授权回调网址
			$sina=new Sina($sina_k, $sina_s);
			$login_url=$sina->login_url($callback_url);
			echo '<a href="',$login_url,'">点击进入授权页面</a>';
		}
		public function qqLogin(){
			//生成登录链接
			import("@.ORG.QQ");
			$qq_k='100522698'; //QQ应用APP ID
			$qq_s='aa00efb01848d2eb17acd3bfa3f92a50'; //QQ应用APP KEY
			$callback_url=U('Callback/sina'); //授权回调网址
			$scope='get_user_info,add_share'; //权限列表，具体权限请查看官方的api文档
			$qq=new QQ($qq_k, $qq_s);
			$login_url=$qq->login_url($callback_url, $scope);
			echo '<a href="',$login_url,'">点击进入授权页面</a>';
		}
	}
?>