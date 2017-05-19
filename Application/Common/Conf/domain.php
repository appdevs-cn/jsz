<?php

/*
 * Created on 2014-10-23
 *
 * 全局配置网站域名的分配
 */

 //资源文件二级域名,如果访问的网站的域名在此域名中，那么静态资源文件的二级域名为
 //resourse.xxx.com 如果不在此域名配置中，那么静态资源文件的二级域名为im.xxx.com
 $staticDomain = array("yashang1.com","yswelcome.com","yswelcome1.com","yswelcome2.com","yswelcome3.com","yswelcome4.com","yswelcome5.com");
 //对多域名进行配置，可以配置多组域名组
 $domainA = array("yashang1.com","yswelcome.com","yswelcome1.com","yswelcome2.com","yswelcome3.com","yswelcome4.com","yswelcome5.com");
 //配置暂停使用的域名
 $closeDomain = array();
 $webInfo = array();
 $domainArr = explode(".", $_SERVER["HTTP_HOST"]);
 $a = $domainArr[0];
 $b = $domainArr[1];
 $c = $domainArr[2];
 $domainLen = count($domainArr);
 if($domainLen>=3 || $domainLen==2){
	$domainRoot = $b . "." . $c;
 } else{
	$domainRoot = $a . "." . $c;
 }
 if(in_array($domainRoot, $staticDomain)){
 	$static = "http://resourse." . $domainRoot;
	//配置自动注册链接地址
	$registUrl = "http://reg." . $domainRoot;
	//配置远程调用接口地址
	$rpcUrl = "http://rpc." . $domainRoot;
	//配置远程调用接口地址
	$nodeUrl = "http://chart." . $domainRoot.":9020";
 } else {
 	$static = "http://resourse." . $domainRoot;
 	//配置自动注册链接地址
	$registUrl = "http://reg." . $domainRoot;
	//配置远程调用接口地址
	$rpcUrl = "http://rpc." . $domainRoot;
	//配置远程调用接口地址
	$nodeUrl = "http://chart." . $domainRoot.":9020";
 }
 if(in_array($domainRoot, $closeDomain)) {
 	echo '<script>window.location.href="http://www.google.com"</script>';
 }


?>
