<?php
/*
 * Created on 2014-10-23
 *
 * 全局配置
 */

require_once('domain.php');
require_once('rules.php');
return $config = array(
	'MODULE_DENY_LIST'      =>  array('Common','Runtime','Cli'),
	'MODULE_ALLOW_LIST'     =>  array('Home','Admin'),
	'DEFAULT_MODULE'        =>  'Home',
	'DEFAULT_CONTROLLER'    =>  'Index', 
	'DEFAULT_ACTION'        =>  'index', 
	'DEFAULT_TIMEZONE'      =>  'Asia/Shanghai',
	'DEFAULT_FILTER'        =>  'remove_xss,strip_tags,htmlspecialchars,addslashes',
	'LOAD_EXT_CONFIG'       => 'bank,mail,lim_ssc,lim_3d,lottery,upper_level,decReward,changeBonusConfig_klsf,changeBonusConfig_p3d,changeBonusConfig_k3,changeBonusConfig_pk10,changeBonusConfig_k8,rewards_level,interval_change_type,interval,changeBonusConfig,changeBonusConfig_11x5,lottery.play.way,menu,account.type,bonus_config,lowerLevel,bonus_common_config,redis.key,lottery.to.playway,webconfig,eBET',
	'APP_SUB_DOMAIN_DEPLOY' =>    1, // 开启子域名配置
	'APP_SUB_DOMAIN_RULES'  =>    array(   
	    'admin.st189.com'   => 'Admin'
	),
	'READ_DATA_MAP'         => false,  //模型映射字段
    "AUTOREGEDIT"           => $registUrl,
	"URL_ROUTER_ON"			=>	true,
	"URL_ROUTE_RULES"		=>	$UrlRules,
	'SESSION_PREFIX'		=>	'stsess_',
	"SESSION_TYPE"			=>	"",
	"SESSION_EXPIRE"		=>  6000,
	"SESSION_TABLE"			=>	"session",
	'COOKIE_PREFIX'         =>  'stcookie',
	'DB_DEPLOY_TYPE'		=> 0,
	'DB_TYPE'               => 'mysqli',    
    'DB_HOST'               => '127.0.0.1',
    'DB_NAME'               => 'cpsystemdb',  
    'DB_USER'               => 'app',     
    'DB_PWD'                => '123456',         
    'DB_PREFIX'				=>	'',
    'DB_PORT'               => '3306',
	'DB_DEBUG'				=> TRUE,
	'DB_RW_SEPARATE'		=> TRUE,

	// 额外的数据库连接
	'DB_CONFIG1' => 'mysqli://app:123456@127.0.0.1:3306/cpsystemdb#utf8',
	// 打印trace
	// 'SHOW_PAGE_TRACE'		=>	1,

    'URL_MODEL'             => 2,
	'URL_PATHINFO_DEPR'     => '/', 
	'DATA_CACHE_TYPE'       => 'File',  
	'REDIS_HOST'            => '127.0.0.1',
	'REDIS_PORT'            => 6379,
	'REDIS_AUTH'            => '',
	'REDIS_EXPIRE'			=> 3600,
	'DATA_CACHE_TIME'       => 3600,
	"TMPL_TEMPLATE_SUFFIX"	=> ".html",
    'TMPL_CACHE_ON' => false,
    'HTML_CACHE_ON' => false,
    'URL_HTML_SUFFIX'=>'.shtml',
	"AUTOLOAD_NAMESPACE"    => array('My' => THINK_PATH.'My'),
    'PAGE'=>array(
        'theme'=>'%upPage% %first% %prePage% %linkPage% %nextPage% %end% %downPage%  %ajax%'
    ),

	"DB_SQL_BUILD_CACHE" => true,
	"DB_SQL_BUILD_LENGTH" => 50,

	//自定义变量配置
	"PASSWORD_HALT"			=>	"bolehalt",      //用户登录密码的加盐值
	"CURACCOUNT_HALT"		=>	"boleaccount",   //用户财务密码的加盐值
	"AUTHKEYLENGTH"			=>	3, //动态密匙长度
	"LISTROWS"              => 15,
 	"ROOLPAGE"              => 20,
 	'DOMAIN'            	=> $domainRoot,
	'DOMAIN_NEW'            => $domainRoot,
	'CLOSE_DOMAIN_NEW'      => $closeDomain,	
	'ROOT_DOMAIN'			=> 'http://www.'.$domainRoot,
	'IM_DOMAIN'			    => $static,
	'UPLOADIMG_DOMAIN'      => 'http://www.'.$domainRoot,
	'CLI_DOMAIN'        	=> 'http://cli.'.$domainRoot,
	'NODE_DOMAIN'        	=> $nodeUrl,
	'UPLOAD_FILE_RULE'		=> 'randFileName',
	'DOMAIN_ARRAY'      	=> $domainA,
	'WEB_TITLE' 			=> "金手指-点燃璀璨人生​",
    'HIGHMAXBONUS'          => 200000,
    'LOWMAXBONUS'           => 100000,
    "ISSSCFENGSUO"             => true,
    "IS3DFENGSUO"             => true,
    "LOADMIN"               => 50,              //充值最低金额
    "LOADMAX"               =>100000,            //充值最高金额

    "ONLINELOADMIN"               => 50,              //在线充值最低金额
    "ONLINELOADMAX"               =>100000,            //在线充值最高金额

	"WITHDRAWTIME"			=> 6, //银行卡绑定6小时后才能提款

    "OPENXIANZHU"			=> true,
    "JIFENACTIVE"           => true
);
