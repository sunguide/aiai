<?php
class IndexAction extends Action {
	
    public function index(){
		$to = "sunguide@qq.com";
		$subject = "Test mail";
		$message = "Hello! This is a simple email message.";
		$from = "someonelse@example.com";
		$headers = "From: $from";
		mail($to,$subject,$message,$headers);
		echo "Mail Sent.";
    }
/*	$r = file_get_contents($url); //用file_get_contents将网址打开并读取所打开的页面的内容 

preg_match("/<meta name=\"description\" content=\"(.*?)\">/is",$r,$booktitle);//匹配此页面的标题 
print_r($booktitle);
$bookname = $booktitle[1];//取第二层数组 
$preg = '/<li><a href=(.*).shtml target=_blank class=a03>/isU'; 
preg_match_all($preg, $r, $zj); //将此页面的章节连接匹配出来 
$bookzj = count($zj[1]);// 计算章节标题数量 
if ($ver=="new"){ 
$content_start = "<!--正文内容开始-->"; 
$content_end = "<!--正文内容结束-->"; 
} 
if ($ver=="old"){ 
$content_start = "<\/table><!--NEWSZW_HZH_END-->"; 
$content_end = "<br>"; 
} 
//特殊字符由于在正则表达式中“ \ ”、“ ? ”、“ * ”、“ ^ ”、“ $ ”、“ + ”、“（”、“）”、“ | ”、“ { ”、“ [ ”等字符已经具有一定特殊意义，如果需要用它们的原始意义，则应该对它进行转义，例如希望在字符串中至少有一个“ \ ”，那么正则表达式应该这么写： \\+ 。
//header("Content-Type:text/html;charset=gb2312"); 
*/

//用file_get_contents将章节连接打开并读取所打开的页面的内容 
   
   public function run(){
	   //if($_GET['a'] != 1) exit;
	   for($i = 1; $i<28; $i++) {
		   $this->fetchList($i);
	   }
   }
   
   //获取采集的列表，并写入数据库中
   public function fetchList($id){
		$listURL = "http://sex.fh21.com.cn/sh/xajq/list_5508_".$id.".html";
		$FetchList = M('FetchLists');
		import("ORG.Net.Http");//导入ThinkPHP中的http类
		$HTTP = new Http();
		$str = $HTTP->fsockopenDownload($listURL);    //访问远程文件，功能类似file_get_content()
		$str = _convertEncoding($str);                //转换编码
		
		$content_start = '性爱技巧</ul>';//开始位置
		$content_end = '<div class="sex_hot">';   //结束位置
	    $matchContent = getMatch($content_start,$content_end,$str);//获取匹配内容
       // echo getTitle($str);
		//echo $matchContent;
		$data = _getTitleAndUrl($matchContent);
		//dump($data);
	    //记入数据库中
		if($data){
			
			$number = count($data); //总的列表数
			$success = 0; 
			$fail = 0;
			
			foreach($data as $value){
			//$value['url'] $value[title]都是getTitleAndUrl赋值过的。
				$value['item_type'] = 1;//性技巧
				$value['category'] = '性生活';
				$value['tag'] = '性技巧';
				$value['match_html'] = $matchContent;
				$value['start_flag'] = $content_start;
				$value['end_flag'] = $content_end;
				$value['remark'] = '性生活中关于性爱技巧的文章';
				$value['create_time'] = date("Y-m-d H:i:s");
				$value['status'] = 0;
				if($FetchList->add($value)) $success++;
				else $fail++;
			}
		}
		//end
		echo $success.'/'.$fail.'/'.$number;
   }
	public function runCo(){
		 for($i = 1; $i<28; $i++) {
		     $this->fetchContent();
		 }
		
	}
	public function fetchContent(){
		$FetchList = M('FetchLists');
		$FetchContent = M('FetchContents');
		$FetchStat = M('FetchStat');
		import("ORG.Net.Http");//导入ThinkPHP中的http类
		$HTTP = new Http();
		

		$id = 728;
		$content_start = '<div class="detailc">';//文章开始位置
		$content_end = '<div class="detail03">';   //文章结束位置
		$list = $FetchList->field('item_id,item_type,title,url')->where('status = 0')->limit(50)->select();
		//dump($list);exit;
		$fail = 0;
		$i = 0;
		foreach($list as $key => $v){
			$listURL = $v['url'];
		
			$str = $HTTP->fsockopenDownload($listURL);    //访问远程文件，功能类似file_get_content()
			$str = _convertEncoding($str);                //转换编码

			

			//首先使用这则表达式获取匹配的内容，失败的话采用字符串分割的方法
			$matchContent = pregMatch($content_start,$content_end,$str);//正则的方法获取匹配内容
			if(!$matchContent) $matchContent = getMatch($content_start,$content_end,$str); //字符串分割的方法获取匹配内容

			//echo $matchContent;
			//采集标题 
			//$pregTitle = '/<ul class=\"detaila\">(.*?)<\/ul>/is'; //采集文章内容中的标题
			//preg_match($pregTitle,$matchContent,$title);//匹配此连接页面的标题 $title[1]
			// echo getTitle($str);//获取浏览器栏中的标题

			// echo $matchContent;
			/* 获取文章中的图片
			$imageUrl = _stripImageUrl($matchContent);
			dump($imageUrl);
			
			foreach($imageUrl as $imgUrl){
				$img = microtime();
				$HTTP->curlDownload($imgUrl,$img.'.png');
			}

			*/
			$success = 0;
			//记入数据库中
			if($matchContent){
				
				$value['title'] = $v['title'];
				$value['item_id'] = $v['item_id'];
				$value['item_type'] = 1;//性技巧
				$value['category'] = '性生活';
				$value['tag'] = '性技巧';
				$value['match_html'] = $matchContent;
				$value['start_flag'] = $content_start;
				$value['end_flag'] = $content_end;
				$value['remark'] = '性生活中关于性爱技巧的文章';
				$value['create_time'] = datetime();
				$value['status'] = 0; //是否清洗过内容
				if($FetchContent->add($value)) {
					$success = 1;
					$save['status'] = 1;
					$FetchList->where('item_id = '.$v['item_id'])->save($save);
				} else{
					$msg = "采集成功，但插入数据库失败";
				}
					
			}else{
				$msg = "采集失败";
			}
			$data = array(
					'url' => $listURL,
					'type' => 'content',
					'status' => 0,
					'remark' => '出现失败数据'
			);//采集失败，将相应的数据计入统计
			if(!$success) { 
				$FetchStat->add($data);
				$fail++;
			}else{
				$i++;
				echo 'success:'.$v['item_id']."<br>";
			}
		
		
		}
		
		echo 'fail:'.$fail;
		echo 'success:'.$i;


		//end
   }
   //开始采集
   public function collect(){

	 $str = file_get_contents($listURL);
     $str = mb_convert_encoding($str,"UTF-8","gb2312");
     // $str = $this->_convertEncoding($str);
	 $content_start = '<div class="area areabg1">';//'<div class="ep-content-bg clearfix">';
	 $content_end = '<div class="channel-end">';//'<div class="ep-source cDGray">';
	 //$str = '<div class="ep-content-bg clearfix">dfdfdfdfd<div class="ep-source cDGray">';
	 //转义函数
	 
      echo getTitle($str);
	  $matchContent = getMatch($content_start,$content_end,$str);
	  
	  //dump($this->_striplinks($matchContent));//strip_tags($this->pregMatch($content_start,$content_end,$str));
	  //echo $matchContent;
     
   }
	public function check(){
		$FecthArticles = M('FetchArticles');
		$id = $this->_param('id');
		$id = intval(trim($id));
		$result = $FecthArticles->where("article_id = $id")->find();
		$pre = $FecthArticles->field('article_id')->where("article_id < $id")->order('article_id desc')->find();
		$next = $FecthArticles->field('article_id')->where("article_id > $id")->order('article_id asc')->find();
		$this->assign('pre',U('Index/check?id='.$pre['article_id']));
		$this->assign('next',U('Index/check?id='.$next['article_id']));
		$this->assign('result',$result);
		$this->display();

	}
	public function checkUpdate(){
		$FecthArticles = M('FetchArticles');
		$param = $this->_post();
		dump($param);
		$data = array(
			'article_id' => $param['article_id'],
			'article_content' => $param['article_content'],
			'update_time' => datetime()
		);
		if($param['article_id'] && $param['article_content'])  $v = $FecthArticles->save($data);
		
		if($v){
			$id = $param['article_id']+1;
		
			$this->redirect('Index/check', array('id' => $id), 1, '更新成功页面跳转中...');
		}else{
			$id = $param['article_id'];
			
			$this->redirect('Index/check', array('id' => $id), 1, '更新失败页面跳转中...');
		}
	}
	//检查采集内容出错的，状态置为-3
	public function checkVaild(){
		$FecthArticles = M('FetchArticles');
		$result = $FecthArticles->select();
		foreach($result as $v){
			if(mb_strlen($v['match_html']) < 20){
				$vaild[] = $v;
				$data = array(
					'article_id' => $v['article_id'],
					'status' => -3,
					'update_time' => datetime()
				);
				$FecthArticles->save($data);
			}
		}
		dump($vaild);
		$result = $FecthArticles->where('status = -3')->select();
		echo count($result);
	}


}
