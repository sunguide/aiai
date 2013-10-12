<?php
//第三方登录回调地址接口
class CallbackAction extends Action {
	//sina weibo 
	public function sina(){
		//授权回调页面，即配置文件中的$callback_url
		import('@.ORG.Sina');
		$sina_k='3598307079'; //新浪微博应用App Key
		$sina_s='1c163695f54d5fabe10dd45e747d4ac4'; //新浪微博应用App Secret
		$callback_url=U('Callback/sina@aiai.im'); //授权回调网址
		if(isset($_GET['code']) && $_GET['code']!=''){
			$o=new Sina($sina_k, $sina_s);
			$result=$o->access_token($callback_url, $_GET['code']);
		}
		if(isset($result['access_token']) && $result['access_token']!=''){
			echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'">';

			//保存登录信息，此示例中使用session保存
			$_SESSION['sina_t']=$result['access_token']; //access token
		}else{
			echo '授权失败';
		}
		echo '<br/><a href="demo.php">返回</a>';
	}
	public function qq(){
		//授权回调页面，即配置文件中的$callback_url
	    import('@.ORG.QQ');
		$qq_k='100522698'; //QQ应用APP ID
		$qq_s='aa00efb01848d2eb17acd3bfa3f92a50'; //QQ应用APP KEY
		echo $callback_url=U('Callback/qq@aiai.im'); //授权回调网址
		if(isset($_GET['code']) && trim($_GET['code'])!=''){
			$qq=new QQ($qq_k, $qq_s);
			$result=$qq->access_token($callback_url, $_GET['code']);
		}
		if(isset($result['access_token']) && $result['access_token']!=''){
			echo '授权完成，请记录<br/>access token：<input size="50" value="',$result['access_token'],'">';

			//保存登录信息，此示例中使用session保存
			$_SESSION['qq_t']=$result['access_token']; //access token
		}else{
			echo '授权失败';
		}
		echo '<br/><a href="demo.php">返回</a>';
	
	}
}