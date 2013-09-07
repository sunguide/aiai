<?php
class KangluAction extends Action {
	
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
	   for($i = 1; $i<22; $i++) {
		   $this->fetchList($i);
	   }
   }
   
   //获取采集的列表，并写入数据库中
   public function fetchList($id){
		$listURL = "http://jb.kanglu.com/e/xgc/index_".$id.".html";
		$FetchList = M('FetchLists');
		import("ORG.Net.Http");//导入ThinkPHP中的http类
		$HTTP = new Http();
		$str = $HTTP->fsockopenDownload($listURL);    //访问远程文件，功能类似file_get_content()
		$str = _convertEncoding($str);                //转换编码
		
		$content_start = 'div class="main_left_btpx">';//开始位置
		$content_end = '<div class="tPages mt20 pd15">';   //结束位置
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
				$value['tag'] = '性高潮';
				$value['match_html'] = $matchContent;
				$value['start_flag'] = $content_start;
				$value['end_flag'] = $content_end;
				$value['remark'] = '性生活中关于性高潮技巧的文章';
				$value['create_time'] = date("Y-m-d H:i:s");
				$value['status'] = 0;
				$value['source'] = 'kanglu';
				if($FetchList->add($value)) $success++;
				else $fail++;
			}
		}
		//end
		echo $success.'/fail:'.$fail.'/all:'.$number;
   }
	public function runCo(){
		 for($i = 1; $i<2; $i++) {
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
		$content_start = '<div class="pb10 pt10">';//文章开始位置
		$content_end = '<div class="readAll pb10 bbor">';   //文章结束位置
		$list = $FetchList->field('item_id,item_type,title,url')->where('status = 0')->limit(50)->select();
		//dump($list);//exit;
		$fail = 0;
		$i = 0;
		foreach($list as $key => $v){
			$listURL = $v['url'];
			$listURL = str_replace('.html','_all.html',$listURL);
			echo $listUrl;
			$str = $HTTP->fsockopenDownload($listURL);    //访问远程文件，功能类似file_get_content()
			$str = _convertEncoding($str);                //转换编码

			

			//首先使用这则表达式获取匹配的内容，失败的话采用字符串分割的方法
			$matchContent = pregMatch($content_start,$content_end,$str);//正则的方法获取匹配内容
			if(!$matchContent) $matchContent = getMatch($content_start,$content_end,$str); //字符串分割的方法获取匹配内容
echo $matchContent;
			//echo $matchContent;
			//采集标题 
			//$pregTitle = '/<ul class=\"detaila\">(.*?)<\/ul>/is'; //采集文章内容中的标题
			//preg_match($pregTitle,$matchContent,$title);//匹配此连接页面的标题 $title[1]
			// echo getTitle($str);//获取浏览器栏中的标题

			// echo $matchContent;
			// 获取文章中的图片
			$imageUrl = _stripImageUrl($matchContent);
			dump($imageUrl);
			
			foreach($imageUrl as $imgUrl){
				$imgArray = explode('/',$imgUrl);
				$img = $imgUrl[count($imgArray) - 1];
				echo $img;
				$HTTP->curlDownload($imgUrl,$img);
			}

			
			$success = 0;
			//记入数据库中
			if($matchContent){
				$value['url'] = $listURL;
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
				$value['source'] = 'kanglu';
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
//清洗数据
	public function clear(){
		$FetchList = M('FetchLists');
		$FetchContent = M('FetchContents');
		$FetchStat = M('FetchStat');
		$c = $FetchContent->select();
		//echo '<xmp>'.$c['match_html'].'</xmp>';原型输出
		foreach($c as $v){
		//$str = str_replace('康路网','',$v['match_html']);
			$data=array(
				'content_id' => $v['content_id'],
				'match_html' => $str,
				'status' => 12
			);
			if($str){ 
				$is = $FetchContent->save($data);
			echo "成功";	
			}else{
			echo "失败";
			}
			
		}
	    
		
		
	} 
//转换,保留<p>
	//$str = str_replace('<p>','[[[',$v['match_html']);
	//$str = str_replace('</p>',']]]',$str);
	//$str = str_replace('[[[','<p>',$v['match_html']);
		//	$str = str_replace(']]]','</p>',$str);
	
/*
	$FetchContent = M('FetchContents');
		$FetchStat = M('FetchStat');
		$c = $FetchContent->where("source != 'kanglu'")->select();
		//echo '<xmp>'.$c['match_html'].'</xmp>';原型输出
		foreach($c as $v){
			$str = str_replace('<div class="detail03"></div>','',$v['match_html']);
			$data=array(
				'content_id' => $v['content_id'],
				'match_html' => $str,
				'status' => 10
			);
			if($str){ 
				$is = $FetchContent->save($data);
			echo "成功";	
			}else{
			echo "失败";
			}
			
		}


*/
	/*去掉特定的内容
	foreach( $c as $key => $value){
			$str = trim($value['title']);
			$pos = mb_strpos($str,'全文');
			if($pos){
				$len = mb_strlen($str,'utf-8') - 3;
				$STR = mb_substr($str,2,$len,'utf-8');
				$data['title']=$STR;
				$v = $FetchContent->where('content_id = '.$value['content_id'])->save($data);
				if($v) echo $value['title'].'=>'.$STR.'<br>';
				else echo "查找到但是更新数据失败".$value['title'].'=>'.$STR.'<br>';
			}else{
			echo "未找到";
			
			}
	
	*/

}