<?php

return array(
    //'TMPL_ACTION_SUCCESS'       =>  APP_PATH.'Home/View/Prompt/success.html', // 默认成功跳转对应的模板文件
    //'TMPL_ACTION_ERROR'       =>  APP_PATH.'Home/View/Prompt/error.html', // 默认成功跳转对应的模板文件
    //'TMPL_EXCEPTION_FILE'       =>  APP_PATH.'Home/View/Prompt/exception.html', // 默认成功跳转对应的模板文件

    //加载自定义标签
	'TAGLIB_PRE_LOAD'=>'Common\\LibTag\\Appdevs,Common\\LibTag\\Other',//预加载的tag
	'TAGLIB_BUILD_IN' => 'cx', //内置标签
    
    //cookie配置
    'COOKIE_EXPIRE'            => 'iqbn_',
    'COOKIE_PATH'            => '/',

    //系统安全配置
    'AUTHKEY' => 'sjjabesoLFniY3C',
    'QUERYSAFE_DFUNCTION' => [
        '0' => 'load_file',
        '1' => 'hex',
        '2' => 'substring',
        '3' => 'if',
        '4' => 'ord',
        '5' => 'char',
    ],
    'QUERYSAFE_DACTION' => [
        '0' => '@',
        '1' => 'intooutfile',
        '2' => 'intodumpfile',
        '3' => 'unionselect',
        '4' => '(select',
        '5' => 'unionall',
        '6' => 'uniondistinct',
    ],
    'QUERYSAFE_DNOTE' => [
        '0' => '/*',
        '1' => '*/',
        '2' => '#',
        '3' => '--',
        '4' => '"',
    ],
    'QUERYSAFE_DLIKEHEX' => 1,
    'QUERYSAFE_AFULLNOTE' => '0',
    'URLXSSDEFEND' => 1,//xss开关
    'SECRITY_ATTACKEVASIVE' => '1|2|4|8',//ddos级别控制默认0为关闭
    'QUERYSAFE_STATUS' => 1,//sql注入开关
    'SAFE_LOG_POWER'    =>  1, // 记录CC攻击日志





    // MG私钥匙
    'MG_RSA' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKqzaW0B0Vhbr4jHrszG9Mwy3bNc4njJCjVmknIf5ZRQyg90p6OeUj0Bor3yB9AGsnff2K2xkK3H5OxNAEqq8gkHTprVTVEhsvTnQPx2znEpjtxgH3giL18+HR+J+/7IE6+105ISWH6+kMfSJCBUg8JWi+QFgbz582sKk6oTpDbZAgMBAAECgYANHfQ/+B/KFmGKtS1tduxgtJfsrHqKpYgvSk5+jozptLuSLHilTgkrvvBugCaxiZI1O/I+hqKDeBTTQ6d+FVy7zFC9bIbIvc27/7sfBjUL1uk6RJqGbMErfJaj3fSbQV0dy0HKYYTyY40fa6OgRG/AfU0hPjSrOF2md7oRMWQiwQJBAPZCD0RiI/Sn3Pns+LYiC9vZZA6GzQ6R1PTcEpGf8mkC5OV555WHOai+dvxD7k521Sq1onBhSj9hsXebRYQbmrUCQQCxdCh6Imtd6+Q0il5feak4Tel72ZTzGixBa1JHBsn70Zj6QjL2CjVnLkahQp1hwQj5h9etohqht87auH+hxC4VAkBCDcgglJ6GYnxwgXLZD9rUdsRS7S+Vp+JSZ7GHZDrWlhMlxoQq5KG/tI8f7Wy/mTpmgV/3+vC6fY7nwTttSyY9AkEAn6f20wwstXAK/gqQJi57xaCztS5qCN8/egxpFmZRTDOkA2WUHDHLIm5MNcKNfuoCfR9vOVVjyKRrg/YDPzQWkQJADhxDy8NKnBvwaJryF4tnnUHl87QEc0br+RDhHIuNfYg/kJPxW6kgMB7ipDiBSobFqB5WGF3kTVhXu3+LlmVhVA==",

    // EbetRSA私钥匙
    'EBET_RSA' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAI1pwIJzjD7roUhXPkTNX3myDymnjPk9Rqkn3SsCo4v857zHPGsbnFg+fD2ZCkvaobQAg38NgCcmA2HqIfbLk0PKJBPT7Rul0dboy0e+Y3ttTeJcWoG9ykqH0ET4Het8oUvremSjnzfrNHUQ36vau9ccu6q4UcuyQWGX5Tz+KtntAgMBAAECgYEAgEW36NzVD1H/dzYj9pbwDtx0Io2oM4DgspnFZbk8DsQ2lKeI54MhG256lglXLJ1B5pw8qyfc4iX6FXwimNHqXs0eMfhhBtgliHxaUWCCWD8YtJXHDxx40OA3SupFFVViG96+jOXe15FCRRnmq+Ip536zXpmx1YsNLxx6a7M3C2UCQQDUXv2pNBZkvRhrpg/4uR0kSVnEQkVVGontqNzjccPwZM5djZQQdV/cKweU7VaXJsJpgUy6+SMGjxPQjZSI8S/3AkEAqnbxbC3rgF/ONAdR6rFYfKn/gxgXDIsxtnniuklO3fx75acAaqrnUwfVMGoB9U5+FkQkKvu0g39x7peydXaUOwJATO90uZ2TFz+h3y2zz4lQU1r3WDAh6ejJWv23t/X449fBIwctQqEi7yHvhzZMDkoOWCZtY7bpx3CB3yXLT6kAlQJAUAq7v/Ur8MKLu4h4YYBWsWrNjIviPsSZWjqPSLOlcbzdnG83Vd7b8fPqmeoc80ehEul6Jii8kUZlhAf9BwTgAwJAOO67C0WD9G1o586I17/56S6pZMJrSecaiuHSmgnSgp033fTK9hDeqOJcOGcCAbAyZ6fA697lFM//bbVlOZBuqQ==",


    // AG私钥匙
    'AG_RSA' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKqzaW0B0Vhbr4jHrszG9Mwy3bNc4njJCjVmknIf5ZRQyg90p6OeUj0Bor3yB9AGsnff2K2xkK3H5OxNAEqq8gkHTprVTVEhsvTnQPx2znEpjtxgH3giL18+HR+J+/7IE6+105ISWH6+kMfSJCBUg8JWi+QFgbz582sKk6oTpDbZAgMBAAECgYANHfQ/+B/KFmGKtS1tduxgtJfsrHqKpYgvSk5+jozptLuSLHilTgkrvvBugCaxiZI1O/I+hqKDeBTTQ6d+FVy7zFC9bIbIvc27/7sfBjUL1uk6RJqGbMErfJaj3fSbQV0dy0HKYYTyY40fa6OgRG/AfU0hPjSrOF2md7oRMWQiwQJBAPZCD0RiI/Sn3Pns+LYiC9vZZA6GzQ6R1PTcEpGf8mkC5OV555WHOai+dvxD7k521Sq1onBhSj9hsXebRYQbmrUCQQCxdCh6Imtd6+Q0il5feak4Tel72ZTzGixBa1JHBsn70Zj6QjL2CjVnLkahQp1hwQj5h9etohqht87auH+hxC4VAkBCDcgglJ6GYnxwgXLZD9rUdsRS7S+Vp+JSZ7GHZDrWlhMlxoQq5KG/tI8f7Wy/mTpmgV/3+vC6fY7nwTttSyY9AkEAn6f20wwstXAK/gqQJi57xaCztS5qCN8/egxpFmZRTDOkA2WUHDHLIm5MNcKNfuoCfR9vOVVjyKRrg/YDPzQWkQJADhxDy8NKnBvwaJryF4tnnUHl87QEc0br+RDhHIuNfYg/kJPxW6kgMB7ipDiBSobFqB5WGF3kTVhXu3+LlmVhVA==",

    // PT私钥匙
    'PT_RSA' => "MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAKqzaW0B0Vhbr4jHrszG9Mwy3bNc4njJCjVmknIf5ZRQyg90p6OeUj0Bor3yB9AGsnff2K2xkK3H5OxNAEqq8gkHTprVTVEhsvTnQPx2znEpjtxgH3giL18+HR+J+/7IE6+105ISWH6+kMfSJCBUg8JWi+QFgbz582sKk6oTpDbZAgMBAAECgYANHfQ/+B/KFmGKtS1tduxgtJfsrHqKpYgvSk5+jozptLuSLHilTgkrvvBugCaxiZI1O/I+hqKDeBTTQ6d+FVy7zFC9bIbIvc27/7sfBjUL1uk6RJqGbMErfJaj3fSbQV0dy0HKYYTyY40fa6OgRG/AfU0hPjSrOF2md7oRMWQiwQJBAPZCD0RiI/Sn3Pns+LYiC9vZZA6GzQ6R1PTcEpGf8mkC5OV555WHOai+dvxD7k521Sq1onBhSj9hsXebRYQbmrUCQQCxdCh6Imtd6+Q0il5feak4Tel72ZTzGixBa1JHBsn70Zj6QjL2CjVnLkahQp1hwQj5h9etohqht87auH+hxC4VAkBCDcgglJ6GYnxwgXLZD9rUdsRS7S+Vp+JSZ7GHZDrWlhMlxoQq5KG/tI8f7Wy/mTpmgV/3+vC6fY7nwTttSyY9AkEAn6f20wwstXAK/gqQJi57xaCztS5qCN8/egxpFmZRTDOkA2WUHDHLIm5MNcKNfuoCfR9vOVVjyKRrg/YDPzQWkQJADhxDy8NKnBvwaJryF4tnnUHl87QEc0br+RDhHIuNfYg/kJPxW6kgMB7ipDiBSobFqB5WGF3kTVhXu3+LlmVhVA==",
    


    // 默认头像路径
    'HEADIMG' => '/Uploads/original/20170417/trumb100_58f4bf9aebfd5.png',
    'TRUMBHEADIMG' => '/Uploads/original/20170417/trumb_58f4bf9aebfd5.png',

    // 限制第三方显示 充值金额设定
    "THIRDMONEY" => 2000,

    // 提款次数限制
    'WITHDRAWCOUNT' => array(0=>5,1=>5,2=>6,3=>6,4=>8,5=>8,6=>10,7=>20,8=>20),


);

