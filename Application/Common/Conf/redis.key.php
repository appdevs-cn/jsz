<?php

return array(
		"BOLEREDISKEY" => array(
				//用户信息（包括用户基本信息和用户的奖金信息）
				//ACTION:CommonSaveDataAction
				//METHOD:UserInfoById_Redis
				"USERINFO" => array(
						"BOLE_REDIS_USERINFO" => "REDISUSERBYID_",
						"BOLE_REDIS_USERINFO_SWITCH" => "CLOSE",
						"BOLE_REDIS_USERINFO_COMMENT" => "用户信息"
				),
				
				//用户奖金信息（包括用户基本信息和用户的奖金信息）
				//ACTION:CommonSaveDataAction
				//METHOD:UserBonusById_Redis
				"USERBONUS" => array(
						"BOLE_REDIS_USERBONUS" => "REDISUSERBONUSBYID_",
						"BOLE_REDIS_USERBONUS_SWITCH" => "CLOSE",
						"BOLE_REDIS_USERBONUS_COMMENT" => "用户信息"
				),
				
				//根据彩票ID获取彩票基本信息【名称，缩略名，状态】
				//ACTION:CommonSaveDataAction
				//METHOD:getLotteryNameById_Redis
				"LOTTERY" => array(
						"BOLE_REDIS_LOTTERY" => "REDISLOTTERYBYID_",
						"BOLE_REDIS_LOTTERY_SWITCH" => "CLOSE",
						"BOLE_REDIS_LOTTERY_COMMENT" => "获取彩票基本信息"
				),
				
				//根据玩法ID获取玩法数据
				//ACTION:CommonSaveDataAction
				//METHOD:getPlayNameById_Redis
				"PLAYWAY" => array(
						"BOLE_REDIS_PLAYWAY" => "REDISPLAYWAYBYID_",
						"BOLE_REDIS_PLAYWAY_SWITCH" => "CLOSE",
						"BOLE_REDIS_PLAYWAY_COMMENT" => "获取玩法数据"
				),
				
				//根据彩票期号 获取开奖号码
				//ACTION:CommonSaveDataAction
				//METHOD:getOpenLotteryNumber_Redis
				"OPENNUM" => array(
						"BOLE_REDIS_OPENNUM" => "REDISOPENLOTTERYNUM_",
						"BOLE_REDIS_OPENNUM_SWITCH" => "CLOSE",
						"BOLE_REDIS_OPENNUM_COMMENT" => "获取开奖号码"
				),
				
				//获取组别名称
				//ACTION:CommonSaveDataAction
				//METHOD:getGroupNameByRoleId_Redis
				"GROUP" => array(
						"BOLE_REDIS_GROUP" => "REDISGROUPNAME_",
						"BOLE_REDIS_GROUP_SWITCH" => "CLOSE",
						"BOLE_REDIS_GROUP_COMMENT" => "获取组别名称"
				),
				
				//根据彩票ID获取改彩票的所有玩法
				//ACTION:CommonSaveDataAction
				//METHOD:getPlayWayByLotteryId_Redis
				"ALLPLAYWAY" => array(
						"BOLE_REDIS_ALLPLAYWAY" => "REDISPLAYWAYBYLOTTERYID_",
						"BOLE_REDIS_ALLPLAYWAY_SWITCH" => "CLOSE",
						"BOLE_REDIS_ALLPLAYWAY_COMMENT" => "彩票的所有玩法"
				),
				
				//根据用户id获取剩余余额
				//ACTION:CommonSaveDataAction
				//METHOD:getCurrentCountByUserid_Redis
				"CURRENTCOUNT" => array(
						"BOLE_REDIS_CURRENTCOUNT" => "REDISCURRENTACOUNTS_",
						"BOLE_REDIS_CURRENTCOUNT_SWITCH" => "CLOSE",
						"BOLE_REDIS_CURRENTCOUNT_COMMENT" => "用户剩余余额"
				),
				
				//根据用户ID获取该用户的在线充值设置
				//ACTION:CommonSaveDataAction
				//METHOD:onlineInfoByUserid_Redis
				"ONLINESET" => array(
						"BOLE_REDIS_ONLINESET" => "REDISONLINESET_",
						"BOLE_REDIS_ONLINESET_SWITCH" => "CLOSE",
						"BOLE_REDIS_ONLINESET_COMMENT" => "在线充值设置"
				),
				
				//根据标签名称查询功能设置代码
				//ACTION:CommonSaveDataAction
				//METHOD:getTaglibCode_Redis
				"TAGLIBCODE" => array(
						"BOLE_REDIS_TAGLIBCODE" => "REDISTAGLIBCODE_",
						"BOLE_REDIS_TAGLIBCODE_SWITCH" => "CLOSE",
						"BOLE_REDIS_TAGLIBCODE_COMMENT" => "自定义标签"
				),
				
				//根据购买表ID 和 彩票ID 组装购买追号数据
				//ACTION:CommonSaveDataAction
				//METHOD:getAddRecordByLotteryId_Redis
				"BUYADDRECORDBYID" => array(
						"BOLE_REDIS_BUYADDRECORDBYID" => "REDISBUYADDRECORDBYID_",
						"BOLE_REDIS_BUYADDRECORDBYID_SWITCH" => "CLOSE",
						"BOLE_REDIS_BUYADDRECORDBYID_COMMENT" => "组装购买追号数据"
				),
				
				//清除所有的Redis键值
				//ACTION:CommonSaveDataAction
				//METHOD:cleanAllRedisKey
				"CLEANKEY" => array(
						"BOLE_REDIS_CLEANKEY" => "*",
						"BOLE_REDIS_CLEANKEY_SWITCH" => "CLOSE",
						"BOLE_REDIS_CLEANKEY_COMMENT" => "所有的Redis"
				),
				
		)
);