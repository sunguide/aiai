<?php
/*======================================================================*\
		Description:	Common Functions
		Author:			aiai.im@qq.com
		CreateTime:		2013-9-27 21:05:32
\*======================================================================*/
class CommonAction extends Action{
	/*======================================================================*\
		Function:	loginLog
		Purpose:	初始化一些参数，主要判断是否登录授权
		Input:		无	            空
		Output:		无      		空
	\*======================================================================*/
	public function getUserLocation(){
		$Agent = $_SERVER['HTTP_USER_AGENT'];
		$ip = get_client_ip();//内置方法
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
		);

		return $data;		
	}
}
?>