<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace Think\Log\Driver;

class SeasLog {

    // 实例化并传入参数
    public function __construct($config=array()){
        \SeasLog::setBasePath(C("LOG_PATH"));
        \SeasLog::setLogger(C("LOG_LOGGER"));
    }

    /**
     * 日志写入接口
     * @access public
     * @param string $log 日志信息
     * @param string $destination  写入目标
     * @return void
     */
    public function write($log,$level='DEBUG') {
        \SeasLog::debug($log);
    }

    public function l($message,$level='INFO')
    {
        \SeasLog::log($level, $message);
    }
}
