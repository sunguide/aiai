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
 */
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
}
function datetime(){

	return date("Y-m-d H:i:s");
}
?>
