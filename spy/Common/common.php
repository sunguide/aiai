<?php
    // 采集的公共函数库
    // author sunguide
	
	/**
	*  获取文章页面的标题
	*/
    function getTitle($str){
		preg_match("/(<title>)(.*?)(<\/title>)/is",$str,$title);//匹配此连接页面的标题 
		return $title[1];
	}
	/**
	*  采用explode分割，根据起止两个位置获取匹配中间的内容
	*/
	function getMatch($start,$end,$str){
		$a = explode($start,$str);
		//echo $start;
		$str = explode($end,$a[1]);
		return $str[0];
	}
	/**
	*  采用正则表达式，根据起止两个位置获取匹配中间的内容
	*/
	function pregMatch($content_start,$content_end,$str){
		$content_start = preg_quote($content_start);//对正则元字符进行转义
	    $content_end = preg_quote($content_end);    //对正则元字符进行转义
		$preg = "/".$content_start."([\s\S]*)".$content_end."/is"; //单行匹配，可以匹配包括/n在内的所有字符。
		preg_match($preg,$str,$content);//匹配此连接页面的内容 
		return $content[0];
	}
		
	/*======================================================================*\
		Function:	_striplinks
		Purpose:	strip the hyperlinks from an html document 分割出来所有的url
		Input:		$document	document to strip.
		Output:		$match		an array of the links
	\*======================================================================*/

	function _striplinks($document)
	{	
		preg_match_all("'<\s*a\s.*?href\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx",$document,$links);
						

		// catenate the non-empty matches from the conditional subpattern

		while(list($key,$val) = each($links[2]))
		{
			if(!empty($val))
				$match[] = $val;
		}				
		
		while(list($key,$val) = each($links[3]))
		{
			if(!empty($val))
				$match[] = $val;
		}		
		
		// return the links
		return $match;
	}
	/*======================================================================*\
		Function:	_stripImageUrl
		Purpose:	get the Imagelinks from an html document 分割出来所有的url
		Input:		$document	document to strip.
		Output:		$match		an array of the links
		注意：有的图片不以.png/.gif/.gpg等结尾
	\*======================================================================*/

	function _stripImageUrl($document)
	{	
		preg_match_all("'<\s*img\s.*src\s*=\s*			# find <a href=
						([\"\'])?					# find single or double quote
						(?(1) (.*?)\\1 | ([^\s\>]+))		# if quote found, match up to next matching
													# quote, otherwise match up to next space
						'isx",$document,$links);
						

		// catenate the non-empty matches from the conditional subpattern

		while(list($key,$val) = each($links[2]))
		{
			if(!empty($val))
				$match[] = $val;
		}				
		
		while(list($key,$val) = each($links[3]))
		{
			if(!empty($val))
				$match[] = $val;
		}		
		
			// return the links
		return $match;
	
	}
	/*======================================================================*\
	Function:	_getTitleAndUrl
	Purpose:	get the title and url  from an html document 
	Input:		$document	document to get Their title and  url.
	Output:		$match		an array of the links
	\*======================================================================*/

	function _getTitleAndUrl($document)
	{	//<a href="http://www.f1.com.cn/sh/xajq/385891.html" target="_blank" title="让百分百到来的流程">让百分百到来流程</a>
		//可以从上面提取url
		preg_match_all("/<a\s+href=\"(\S+?)\".*?\">(.+?)<\/a>/i",$document,$links);
						
		// catenate the non-empty matches from the conditional subpattern

		foreach($links[1] as $key => $value){
			$match[] = array(
							'title' => $links[2][$key],
							'url'	=> $value
						);		
		
		}
		// return the results
		//dump($links);
		return $match;
	}
	/*======================================================================*\
		Function:	_convertEncoding
		Purpose:	转换字符串编码,默认转换为utf-8格式
		Input:		$string	            字符串
		Output:		$outEncoding		编码
	\*======================================================================*/
	function _convertEncoding($string,$outEncoding ='UTF-8')    
	{    
		$encoding = "UTF-8";    
		for($i=0;$i<strlen($string);$i++)    
		{    
			if(ord($string{$i})<128)    
				continue;    
			
			if((ord($string{$i})&224)==224)    
			{    
				//第一个字节判断通过    
				$char = $string{++$i};    
				if((ord($char)&128)==128)    
				{    
					//第二个字节判断通过    
					$char = $string{++$i};    
					if((ord($char)&128)==128)    
					{    
						$encoding = "UTF-8";    
						break;    
					}    
				}    
			}    
		
			if((ord($string{$i})&192)==192)    
			{    
				//第一个字节判断通过    
				$char = $string{++$i};    
				if((ord($char)&128)==128)    
				{    
					// 第二个字节判断通过    
					$encoding = "GB2312";    
					break;    
				}    
			}    
		}    
				 
		if(strtoupper($encoding) == strtoupper($outEncoding)) {
			return $string;    
		}else{
			//return iconv($encoding,$outEncoding,$string);
		    $result = iconv($encoding,$outEncoding,$string);
			//当iconv不成功时，再用mb_convert_endcoding
		    if($result) return $result;
		    else return mb_convert_encoding($string,$outEncoding,$encoding);
		}   
	  		
	}


	/*
	　　相关函数: 
	　　　　preg_filter — 执行一个正则表达式搜索和替换 
	　　　　preg_grep — 返回匹配模式的数组条目 
	　　　　preg_last_error — 返回最后一个PCRE正则执行产生的错误代码 
	　　　　preg_match_all — 执行一个全局正则表达式匹配 
	　　　　preg_match — 执行一个正则表达式匹配 
	　　　　preg_quote — 转义正则表达式字符 
	　　　　preg_replace_callback — 执行一个正则表达式搜索并且使用一个回调进行替换 
	　　　　preg_replace — 执行一个正则表达式的搜索和替换 
	　　　　preg_split — 通过一个正则表达式分隔字符串
			strip_tags 去除html标签


	发现iconv在转换字符”—”到gb2312时会出错，如果没有ignore参数，所有该字符后面的字符串都无法被保存。不管怎么样，这个”—”都无法转换成功，无法输出。 另外mb_convert_encoding没有这个bug.
	一般情况下用 iconv，只有当遇到无法确定原编码是何种编码，或者iconv转化后无法正常显示时才用mb_convert_encoding 函数.
	例子：
	$content = iconv(”GBK”, “UTF-8″, $content);
	$content = mb_convert_encoding($content, “UTF-8″, “GBK”);

	*/
	function datetime(){
		return date("Y-m-d:H:i:s");
	}
   
?>