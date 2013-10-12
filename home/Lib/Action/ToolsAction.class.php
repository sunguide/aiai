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
	
	/**
	 * Short description.
	 * @param   type    $varname    description
	 * @return  type    description
	 * @access  public or private
	 * @static  makes the class property accessible without needing an instantiation of the class
	 */
	public function txtToImage()
	{
	    //header ("Content-type: image/png");

		$param = $this->_param();
		mb_internal_encoding("UTF-8"); // 设置编码
//rgb(236, 241, 247)
		
		$text = "前段时间练习使用 PHP 的 GD 库时，为了文本的自动换行纠结了很久。虽然可以通过插入 \n\n    实现换行，但考虑到文本中既有中文又有英文，强制限定每多少个文字就换行的效果很差。后来终于找到了一个英文下的自动换行的方法，其大概原理是将空格作为分隔符，将字符串分割为一个个单词，然后再一个接一个地拼接在一起，判断其长度是否超过画布，若超过则换行再拼接，否则继续拼接。考虑到中文需要将每个文字都拆开，所以我进行了一点修改，完整代码如下。";
		if($param['title']){
			$text = "@大胆做出你的爱\n#".$param['title']."#\n";
		}
		if($param['content']){
			$text .= $param['content'];
		}
		
		//echo __PUBLIC__.'/font/simsun.ttf';
		$result = $this->autowrap(18, 0, "./Public/font/kaiti.ttf", $text, 400); // 自动换行处理
		//dump($result);
		$result['height'] += 400;
		$bg = imagecreatetruecolor(440, $result['height']); // 创建画布
		$bgcolor = imagecolorallocate($bg, 250, 248, 248);
		imagefill($bg,0,0,$bgcolor);
		$white = imagecolorallocate($bg, 51, 51, 51); // 创建字体颜色
		//echo $text;
		// 若文件编码为 GB2312 请将下行的注释去掉
		// $text = iconv("GB2312", "UTF-8", $text);
		imagettftext($bg, 18, 0, 30, 150, $white, "./Public/font/kaiti.ttf", $result['content']);
		$imgfile = './Public/bimg/'.time().'bg.png';
		imagepng($bg,$imgfile);
		imagedestroy($bg);
		import('ORG.Util.Image');
		$Image = new Image();
		// 给avator.jpg 图片添加logo水印
		$Image->water($imgfile,'./Public/water/head.png','',100,0);//顶部
		$Image->water($imgfile,'./Public/water/tail.png','',100,1);//底部
		$this->ajaxReturn("<img src='http://127.0.0.1/ii/$imgfile' style='width:300px'/>","http://127.0.0.1/ii/".$imgfile,1);
	} // end func
	public function autowrap($fontsize, $angle, $fontface, $string, $width) {
		// 这几个变量分别是 字体大小, 角度, 字体名称, 字符串, 预设宽度
		 $content = "";
		 // 将字符串拆分成一个个单字 保存到数组 letter 中
		 for ($i=0;$i<mb_strlen($string);$i++) {
			$letter[] = mb_substr($string, $i, 1);
		 }
		 $line = 0;
		 foreach ($letter as $l) {
			  $teststr = $content." ".$l;
			  $testbox = imagettfbbox($fontsize, $angle, $fontface, $teststr);
			  // 判断拼接后的字符串是否超过预设的宽度
			  if (($testbox[2] > $width) && ($content !== "")) {
			      $content .= "\n";
				  $line++;
			  }
			  $content .= ''.$l;
		 }
	
		 $width = $line * 50;
		 $result = array(
			'height' => $width,
			'content' => $content
		 );
		 //dump($result);
		 return $result;
	}
	public function publishSubmit(){
		$param = $this->_param();
		import('@.ORG.Sina');
	
		$sina_k='3598307079'; //新浪微博应用App Key
		$sina_s='1c163695f54d5fabe10dd45e747d4ac4'; //新浪微博应用App Secret
	
		$sina=new Sina($sina_k, $sina_s);
		if($param['weibo']){
			$v = $sina->update($param['weibo'],$param['imgurl']);
			if($v){
				$this->ajaxReturn(1,"发布成功",1);
			}else{
				$this->ajaxReturn(0,"发布失败",0);
			}
		}
		
	}
}