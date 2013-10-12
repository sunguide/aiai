<?php
//有色图
class TuAction extends Action {
	
	public function index(){
		
		$this->assign('coming','有色图，提供海量惊艳美女图片，敬请期待！');
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
	public function weibo(){
		$source='';//微博app账号(必填)
		$access_token='';//微博token（必填）
		$count=10;//调用微博数
		$url='https://api.weibo.com/2/statuses/friends_timeline.json?&source='.$source.'&access_token='.$access_token.'&count='.$count;//读取关注用户微博接口
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$json = curl_exec($ch);//返回结果
		curl_close($ch);
		/*******返回结果处理********/
		$result=json_decode($json);
		$data=array();
		for($i=0;$i<$count;$i++){
		$obj=$result->statuses[$i];
		$data['content']=$obj->text;//内容
		$data['author']=$obj->user->name;//用户名
		$data['header']=$obj->user->profile_image_url;//头像地址
		$time=$obj->created_at;
		$dateline=strtotime($time);
		$data['time']=date('H:i:s',$dateline);//发表时间
		/*******处理结束********/
		$list[$i]=$data;//二维数组
		 }
		$this->assign('nav',2);
		$this->assign('list',$list);
		$this->display();
	}
}