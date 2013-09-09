<?php
    return array(
        'LANG_SWITCH_ON' => true,
        'DEFAULT_LANG' => 'zh-cn', // 默认语言
        'LANG_AUTO_DETECT' => true, // 自动侦测语言
        'LANG_LIST'=>'en-us,zh-cn,zh-tw',//必须写可允许的语言列表
		'DB_TYPE'=>'mysql',
		'DB_HOST'=>'localhost',
		'DB_PORT'=>'3306',
		'DB_NAME'=>'aiai',
		'DB_USER'=>'root',
		'DB_PWD'=>'woshinilao8',
		'DB_PREFIX'=>'aiai_',
		'URL_ROUTER_ON'   => true, //开启路由
		'URL_ROUTE_RULES' => array( //定义路由规则
			'About'					 => 'Public/about', 
		),
		'URL_MODEL '=> 3,

    );
?>