<?php
// 本类由系统自动生成，仅供测试用途
class AccountAction extends Action {
	//private $_uid = '';
	//public function _initialize(){
	//	$this->_uid = session('uid');
	//	$data = array(
	//		''	
	//	);
	//	if(!$this->_uid) $this->display("Public:login");//(503,"对不起，您登录超时了，请重新登录！",1);
		//B('Authenticate', $action);
	//}
    public function index(){
		
		$this->display();
    }
	//账号信息查看与修改
	public function accountInfo(){
		if($_GET['company_name'] == ''){
			$this->display();
		}else{
			$Account = M('Account');
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
	public function accountManage(){
		$Balance = M('Balance');
		$Account = M('Account');
		import('ORG.Util.Page');// 导入分页类
		$count      = $Account->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$data = $Account->order('update_time')->limit($Page->firstRow.','.$Page->listRows)->field('uid,account,company_name,company_contacter,create_time,status,contact_mobilephone')->select();
		$result = array();
		$icount = count($data);
		for($i = 0;$i < $icount;$i++){
			$data[$i]['assets'] = $Balance->where("uid =".$data[$i]['uid'])->order('id')->getField('assets');
		}
		dump($data);
		$this->assign('list',$data);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
	}
	public function checkAccount(){
		$GET = $this->_get();
		if($GET['account']){
			$Account = M('Account');
			
			$data['account'] = trim($GET['account']);
			$is = $Account->where($data)->find();
			if($is){
				$this->ajaxReturn(1,"该账号已经注册",1);
			}else{
				$this->ajaxReturn(0,"该账号可以注册",0);
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
	//公司签名修改
	public function signatureEdit(){
		$get = $this->_get();
		$Account = M('Account');
		if($get['signature']==''){
			$this->ajaxReturn(0,"请输入密码",0);
		}else{
			if(strlen($get['signature'] > 20)) $this->ajaxReturn(0,"签名不能超过20个字符",0);
			$data = array(
					'uid'      => $uid,
					'company_signature' => $get['signature']
			);
			$v = $Account->save($data);
			if($v){
				$this->ajaxReturn(0,"更新签名失败",0);
			}else{
				$this->ajaxReturn(1,"更新签名成功",1);
			}
					
		}
	
	}
	//账户充值记录
	//待修改
	public function chargerRecords(){
		$Order = M('Balance');
		$Account = M('Account');
		import('ORG.Util.Page');// 导入分页类
		$count      = $Order->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$data = $Order->order('update_time')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result = array();
		$icount = count($data);
		for($i = 0;$i < $icount;$i++){
			$ac = $Account->where("uid = ".$data[$i]['uid'])->find();
			$data[$i]['account'] = $ac['account'];
			$data[$i]['company_name'] = $ac['company_name'];
		}
		//dump($data);
		$this->assign('list',$data);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	//企业签名
	public function signature(){
		
		$this->display();



	}
	//订单记录
	public function orderRecords(){
		$Order = M('Order');
		$Account = M('Account');
		import('ORG.Util.Page');// 导入分页类
		$count      = $Order->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$data = $Order->order('update_time')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result = array();
		$icount = count($data);
		for($i = 0;$i < $icount;$i++){
			$ac = $Account->where("uid = ".$data[$i]['uid'])->find();
			$data[$i]['account'] = $ac['account'];
			$data[$i]['company_name'] = $ac['company_name'];
		}
		//dump($data);
		$this->assign('list',$data);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	//订单审核
	public function orderCheck(){
		$Order = M('Order');
		$Account = M('Account');
		import('ORG.Util.Page');// 导入分页类
		$count      = $Order->where('status = 0')->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$data = $Order->where('status = 0')->order('create_time')->limit($Page->firstRow.','.$Page->listRows)->select();
		$result = array();
		foreach($data as $value){
			$ac = $Account->where("uid = ".$value['uid'])->find();
			
			$list[] = array(
				'order_id' => $value['order_id'],
				'uid' => $value['uid'],
				'account' =>$ac['account'],
				'company_name' =>$ac['company_name'],
				'order_amount' =>$value['order_amount'],
				'create_time'  => $value['create_time'],
				'user_remark'  => $value['user_remark']
			);
		}dump($list);
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
	}
	public function orderConfirm(){
		$Order = M('Order');
		$Account = M('Account');
		$post = $this->_post();
		$data = array(
			'pay_amount' => $post['pay_amount'],
			'handler_id' => $this->_uid,
			'handle_time'=> date("Y-m-d H:i:s"),
			'handler_remark' => $post['handler_remark']
		);
		$v = $Order->save($data);
		if($v){
			$this->ajaxReturn(1,"确认订单成功",1);
		}else{
			$this->ajaxReturn(0,"确认订单失败",0);
		}
	}
}