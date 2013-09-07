<?php
// 本类由系统自动生成，仅供测试用途
class LogAction extends Action {
	private $_uid = '';
	public function _initialize(){
		$this->_uid = session('uid');
		if(!$this->_uid) $this->display("Public:login");//(503,"对不起，您登录超时了，请重新登录！",1);
		//B('Authenticate', $action);
	}
    public function index(){
		alert(array(
			'type' => 'remind',
			'title'=> 'hello jack',
			'meg'=>'好登录疯了似的辅导费代理费'
		));
		alert('asdfsdf');
		$this->display();
    }
	public function loginLog(){
		$Agent = $_SERVER['HTTP_USER_AGENT'];
		$LoginLog = M('LoginLog');
		$LogsLog = M('LogsLog');
		$ip = get_client_ip();
		import('ORG.Net.IpLocation');// 导入IpLocation类
		$Ip = new IpLocation('UTFWry.dat'); // 实例化类 参数表示IP地址库文件
		$area = $Ip->getlocation($ip); // 获取某个IP地址所在的位置
		
		//显示访问用户的浏览器信息
		//echo 'Browser: ' . determinebrowser($Agent) . '<BR>';
		//显示访问用户的操作系统平台
		//echo 'Platform: ' . determineplatform($Agent). '<BR>';
		$uid = $this->_uid;
		$data = array(
			'uid'	=> $uid,
			'ip'	=>$ip,
			'region'=>$area['area'],
			'country'=>$area['country'],
			'browser'=>determinebrowser($Agent),
			'os'	 =>determineplatform($Agent),
			'login_time'=>datetime(),
			'status' => session('login_status')?session('login_status'):0,    //1,正常登陆,0 失败
		);

		$v = $LoginLog->add($data);
		if($v){
			$result = array(
				'log_uid'  => $uid,
				'log_name' => $uid.'用户登陆记录成功',	
				'log_description' => '',
				'create_time' => datetime(),
				'status'	=> 1
			);
			$LogsLog->add($result);
		}else{
			$result = array(
				'log_uid'  => $uid,
				'log_name' => $uid.'用户登陆记录失败',	
				'log_description' => '',
				'create_time' => datetime(),
				'status'	=> 0
			);
			$LogsLog->add($result);
		}
	}
	public function logoutLog(){

	}
	public function loginLogRecords(){
		$get = $this->_post();
		if(!empty($get)){
			$where = ' 1 ';
			$get['startdate']=trim($get['startdate']);
			$get['enddate']=trim($get['enddate']);
			$get['company_name']=trim($get['company_name']);
			if($get['startdate']) $where .= " AND login_time >= '".$get['startdate']." 00:00:00 '";
			if($get['enddate'])   $where .= " AND login_time  <= '".$get['enddate']." 23:59:59 '";
			if($get['company_name'])  $where .= " AND b.company_name = '".$get['company_name']."'";
		}else{
			$where = 1;
		}
		$LoginLog = M('LoginLog');
		import('ORG.Util.Page');// 导入分页类
		$uid = $this->_uid;
	
		
		$count      = $LoginLog->where($where)->count();// 查询满足要求的总记录数
		$Page       = new Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$Model = new Model();
		$sql = "";//join('account ON login_log.uid = account.uid')->where($where)->order('login_time')->limit($Page->firstRow.','.$Page->listRows)->
		//$user = new Model('LoginLog');
		//$list =$user->join('sms_account ON sms_login_log.uid = sms_account.uid')->where($where)->order('login_time')->limit($Page->firstRow.','.$Page->listRows)->->select();
		
		$sql = "SELECT a.*,b.company_name,b.company_contacter
				FROM sms_login_log AS a
				LEFT JOIN sms_account AS b ON a.uid = b.uid
				WHERE ".$where."
				LIMIT ".$Page->firstRow.",".$Page->listRows;
				//alert("warn",$sql);
//echo $sql;
		$list = $Model->query($sql);
		//dump($voList);
		/*
		$icount = count($list);
		for($i = 0; $i < $icount ;$i++){
			$list[$i]['region']?1:$list[$i]['region'] = "等待确认";
			$list[$i]['user_remark']?1:$list[$i]['user_remark'] = "无";	
		}
		*/
		//dump($list);
		$this->assign('key',$get); //查询的key再次赋值到模板中。
		$this->assign('list',$list);// 赋值数据集
		$this->assign('page',$show);// 赋值分页输出
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