<?php
//工具类
class ToolsAction extends Action {
	//查手机归属地
	public function checkMobile(){
	   if(isset($_GET['mobile'])) { 
		  $number = $_GET['mobile']; 
		  $url = 'http://cz.115.com/?ct=index&ac=get_mobile_local&mobile='.$number; 
		  $ch = curl_init($url); 
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		  curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ; // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回  
		  $result = curl_exec($ch); 
		  curl_close($ch); 
		  $leng = strlen(trim($result));
		  $data = json_decode(substr($result,1,$leng-2));
		  if($data->queryresult == True){
			  $this->ajaxReturn($data,"查询到的手机归属地信息",1);
		  }else{
		 	  $this->ajaxReturn($result,"获取手机归属地出错",0);
		  }
	   }else{
			$this->display();
	   }
	}
}