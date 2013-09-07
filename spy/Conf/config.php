<?php
return array(
	//'配置项'=>'配置值'
	'LANG_SWITCH_ON' => true,
        'DEFAULT_LANG' => 'zh-cn', // 默认语言
        'LANG_AUTO_DETECT' => true, // 自动侦测语言
        'LANG_LIST'=>'en-us,zh-cn,zh-tw',//必须写可允许的语言列表
		'DB_TYPE'=>'mysql',
		'DB_HOST'=>'localhost',
		'DB_PORT'=>'3306',
		'DB_NAME'=>'spy',
		'DB_USER'=>'root',
		'DB_PWD'=>'root',
		'DB_PREFIX'=>'sp_',

		'TOKEN_ON'=>true,  // 是否开启令牌验证
		'TOKEN_NAME'=>'__hash__',    // 令牌验证的表单隐藏字段名称
		'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
		'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
		'URL_ROUTE_RULES' => array( //定义路由规则
			'/^b\/(\d+)$/'        => 'B/read?id=:1',
			'/^b\/(\d+)\/(\d+)$/' => 'B/achive?year=:1&month=:2',
			'/^b\/(\d+)_(\d+)$/'  => 'blog.php?id=:1&page=:2',
		),
);
?>