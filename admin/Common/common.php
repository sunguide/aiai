<?php
function isMobile($str)
{
    return preg_match("/^1(3|4|5|8)\d{9}$/",$str);
}
/**
 * Warning提示信息
 * @param string $type 提示类型 默认支持success, error, info
 * @param string $msg 提示信息
 * @param string $url 跳转的URL地址
 * @return void
 
function alert($type='info', $msg='', $url='') {
    //多行URL地址支持
    $url        = str_replace(array("\n", "\r"), '', $url);
	$alert = unserialize(stripslashes(cookie('alert')));
    if (!empty($msg)) {
        $alert[$type][] = $msg;
		cookie('alert', serialize($alert));
	}
    if (!empty($url)) {
		if (!headers_sent()) {
			// redirect
			//header('Location: ' . $url);
			//exit();
		} else {
			$str    = "<meta http-equiv='Refresh' content='0;URL={$url}'>";
			//exit($str);
		}
	}

	return $alert;
} */
/*
 * 弹窗提示信息
 * @param string $type 提示类型 默认支持warn, remind, dialog(可传入url)
 * @param string $msg 提示信息
 * @param string $url 跳转的URL地址
 * @return void
 */
 function alert($type,$title,$msg){
	 if($type == 'warn'){
		echo "<script>$.warn('$title','$msg');</script>";
		return;
	 }
	 if($type == 'remind'){
		 echo "<script>$.remind('$title','$msg');</script>";
		 return;
	 }
	alert('warn','错误警告','方法alert使用错误！');
 }
 function dialog($title,$url=''){
	if($url == ''){
		alert('warn','错误警告','非法操作');
		return;
	}
	echo "<script>$.dialog('$title','$url');</script>";
 }
 function determinebrowser ($Agent) {
	$browseragent=""; //浏览器
	$browserversion=""; //浏览器的版本
	if (ereg('MSIE ([0-9].[0-9]{1,2})',$Agent,$version)) {
	$browserversion=$version[1];
	$browseragent="Internet Explorer";
	} else if (ereg( 'Opera/([0-9]{1,2}.[0-9]{1,2})',$Agent,$version)) {
	$browserversion=$version[1];
	$browseragent="Opera";
	} else if (ereg( 'Firefox/([0-9.]{1,5})',$Agent,$version)) {
	$browserversion=$version[1];
	$browseragent="Firefox";
	}else if (ereg( 'Chrome/([0-9.]{1,3})',$Agent,$version)) {
	$browserversion=$version[1];
	$browseragent="Chrome";
	}
	else if (ereg( 'Safari/([0-9.]{1,3})',$Agent,$version)) {
	$browseragent="Safari";
	$browserversion="";
	}
	else {
	$browserversion="";
	$browseragent="Unknown";
	}
	return $browseragent." ".$browserversion;
}

//正值表达式比对解析$_SERVER['HTTP_USER_AGENT']中的字符串 获取访问用户的浏览器的信息
// 同理获取访问用户的操作系统的信息
  function determineplatform ($Agent) {
	$browserplatform=='';
	if (eregi('win',$Agent) && strpos($Agent, '95')) {
	$browserplatform="Windows 95";
	}
	elseif (eregi('win 9x',$Agent) && strpos($Agent, '4.90')) {
	$browserplatform="Windows ME";
	}
	elseif (eregi('win',$Agent) && ereg('98',$Agent)) {
	$browserplatform="Windows 98";
	}
	elseif (eregi('win',$Agent) && eregi('nt 5.0',$Agent)) {
	$browserplatform="Windows 2000";
	}
	elseif (eregi('win',$Agent) && eregi('nt 5.1',$Agent)) {
	$browserplatform="Windows XP";
	}
	elseif (eregi('win',$Agent) && eregi('nt 6.0',$Agent)) {
	$browserplatform="Windows Vista";
	}
	elseif (eregi('win',$Agent) && eregi('nt 6.1',$Agent)) {
	$browserplatform="Windows 7";
	}
	elseif (eregi('win',$Agent) && ereg('32',$Agent)) {
	$browserplatform="Windows 32";
	}
	elseif (eregi('win',$Agent) && eregi('nt',$Agent)) {
	$browserplatform="Windows NT";
	}elseif (eregi('Mac OS',$Agent)) {
	$browserplatform="Mac OS";
	}
	elseif (eregi('linux',$Agent)) {
	$browserplatform="Linux";
	}
	elseif (eregi('unix',$Agent)) {
	$browserplatform="Unix";
	}
	elseif (eregi('sun',$Agent) && eregi('os',$Agent)) {
	$browserplatform="SunOS";
	}
	elseif (eregi('ibm',$Agent) && eregi('os',$Agent)) {
	$browserplatform="IBM OS/2";
	}
	elseif (eregi('Mac',$Agent) && eregi('PC',$Agent)) {
	$browserplatform="Macintosh";
	}
	elseif (eregi('PowerPC',$Agent)) {
	$browserplatform="PowerPC";
	}
	elseif (eregi('AIX',$Agent)) {
	$browserplatform="AIX";
	}
	elseif (eregi('HPUX',$Agent)) {
	$browserplatform="HPUX";
	}
	elseif (eregi('NetBSD',$Agent)) {
	$browserplatform="NetBSD";
	}
	elseif (eregi('BSD',$Agent)) {
	$browserplatform="BSD";
	}
	elseif (ereg('OSF1',$Agent)) {
	$browserplatform="OSF1";
	}
	elseif (ereg('IRIX',$Agent)) {
	$browserplatform="IRIX";
	}
	elseif (eregi('FreeBSD',$Agent)) {
	$browserplatform="FreeBSD";
	}
	if ($browserplatform=='') {$browserplatform = "Unknown"; }
	return $browserplatform;
}
//datestrap
function datetime(){
	return date("Y-m-d H:i:s");
}
//生成订单号
function geneOrderNumber(){
	return date('ymdhis');
}
//判断字符长度（包括汉字）
function str_len($str)
{
    if(empty($str)){
        return 0;
    }
    if(function_exists('mb_strlen')){
        return mb_strlen($str,'utf-8');
    }
    else {
        preg_match_all("/./u", $str, $ar);
        return count($ar[0]);
    }
}
?>
