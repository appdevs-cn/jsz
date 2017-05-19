<?php

/*
 * Created on 2014-10-23
 *
 * 表单令牌验证功能
 */

return array(
     	// 添加下面一行定义即可
        'view_filter' => array('Behavior\TokenBuildBehavior'),
     	'TOKEN_ON'      =>    true,  // 是否开启令牌验证 默认关闭
		'TOKEN_NAME'    =>    '__hash__',    // 令牌验证的表单隐藏字段名称，默认为__hash__
		'TOKEN_TYPE'    =>    'md5',  //令牌哈希验证规则 默认为MD5
		'TOKEN_RESET'   =>    true,  //令牌验证出错后是否重置令牌 默认为true

);





?>