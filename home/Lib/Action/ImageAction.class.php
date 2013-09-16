<?php
/*
param $image   图象资源
param size     字体大小
param angle    字体输出角度
param showX    输出位置x坐标
param showY    输出位置y坐标
param font    字体文件位置
param content 要在图片里显示的内容
*/
class ImageAction extends Action {
	var $text = 'php网站程序开发';
	var $font = './huayankaijianW5-A.TTF'; //如果没有要自己加载到相应的目录下（本地www）
	var $angle = 0;
	var $size = 15;
	var $showX = 100;
	var $showY = 160;
	
	var $text0 = '2011 年 12 月 12 日';
	var $angle0 = 0;
	var $showX0 = 230;
	var $showY0 = 200;
	
	var $text1 = '新郎';
	var $angle1 = 20;
	var $showX1 = 135;
	var $showY1 = 285;
	
	var $text2 = '新娘';
	var $angle2 = 20;
	var $showX2 = 300;
	var $showY2 = 285;
	
	var $text3 = '北京市海淀区香格里拉酒店';
	var $angle3 = 0;
	var $showX3 = 120;
	var $showY3 = 445;
	
	var $text4 = '上午十一点整';
	var $angle4 = 0;
	var $showX4 = 305;
	var $showY4 = 480;
	
	public function showImage($showText = '') {
		$this->text = ! isset ( $showText ) ? $showText : $this->text;
		$this->show ();
	}
	public function createText($instring) {
		$outstring = "";
		$max = strlen ( $instring );
		for($i = 0; $i < $max; $i ++) {
			$h = ord ( $instring [$i] );
			if ($h >= 160 && $i < $max - 1) {
				$outstring .= substr ( $instring, $i, 2 );
				$i ++;
			} else {
				$outstring .= $instring [$i];
			}
		}
		return $outstring;
	}
	public function show() {

		//输出头内容
		Header ( "Content-type: image/png" );
		//建立图象
		$image = imagecreate(600,600);
		//$image = imagecreatefromjpeg ( "../01.jpg" ); //这里的图片，换成你的图片路径
		//定义颜色
		$red = ImageColorAllocate ( $image, 255, 0, 0 );
		$white = ImageColorAllocate ( $image, 255, 255, 255 );
		$black = ImageColorAllocate ( $image, 0, 0, 0 );
		//填充颜色
		ImageFilledRectangle($image,0,0,600,200,$red);
		//显示文字
		$text = 'Testing...';
		// Replace path by your own font path
		$font = 'arial.ttf';
		// Add some shadow to the text
		imagettftext($im, 20, 0, 11, 21, $grey, $font, $text);

		// Add the text
		imagettftext($im, 20, 0, 10, 20, $black, $font, $text);
		$txt = $this->createText ( $this->text );
		$txt0 = $this->createText ( $this->text0 );
		$txt1 = $this->createText ( $this->text1 );
		$txt2 = $this->createText ( $this->text2 );
		$txt3 = $this->createText ( $this->text3 );
		$txt4 = $this->createText ( $this->text4 );
		//写入文字
		imagettftext ( $image, $this->size, 20,2, 2, 2, $white, $this->font, "gfdg" );
		imagettftext ( $image, $this->size, $this->angle0, $this->showX0, $this->showY0, $white, $this->font, $txt0 );
		imagettftext ( $image, $this->size, $this->angle1, $this->showX1, $this->showY1, $white, $this->font, $txt1 );
		imagettftext ( $image, $this->size, $this->angle2, $this->showX2, $this->showY2, $white, $this->font, $txt2 );
		imagettftext ( $image, $this->size, $this->angle3, $this->showX3, $this->showY3, $white, $this->font, $txt3 );
		imagettftext ( $image, $this->size, $this->angle4, $this->showX4, $this->showY4, $white, $this->font, $txt4 );
		//ImageString($image,5,50,10,"中国",$white);
		//显示图形
		imagejpeg ( $image );
		imagegif ( $image, "a2.jpg" );
		ImageDestroy ( $image );
	}
}