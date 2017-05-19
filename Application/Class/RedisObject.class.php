<?php


/**
 +--------------------------------------------------------------------------------------------------------------------
 * Redis操作类
 +--------------------------------------------------------------------------------------------------------------------
 * @access public
 +--------------------------------------------------------------------------------------------------------------------
 * @param object $redis 【redis对象】 
 * @param int $expire 【redis过期时间】
 +--------------------------------------------------------------------------------------------------------------------
 * 
 * @return NULL
 +--------------------------------------------------------------------------------------------------------------------
 * @throws ThinkExecption
 +--------------------------------------------------------------------------------------------------------------------
 */
	Class RedisObject {
		
		public $redis = NULL;
		public $expire;
		public static  $redisObject = NULL;
		
		/**
		 * 
		 * @abstract 单一模式
		 * @access public
		 * 
		 * @return mixed RedisObject
		 */
		public static  function factory () {
			if(self::$redisObject == NULL) {
				self::$redisObject = new RedisObject();
			}
			return self::$redisObject;
		}
		
		/**
		 * 
		 * @abstract 构造函数 连接Redis
		 * @access public
		 * 
		 * @return mixed
		 */
		public function __construct() {
			$this->expire = C("REDIS_EXPIRE") ? C("REDIS_EXPIRE") : 3600;
			$this->redis = new Redis();
			$this->redis->pconnect(C('REDIS_HOST'), C('REDIS_PORT'));
		}
		
		/**
		 *
		 * @abstract Redis存储数据序列化
		 * @access public
		 * 
		 * @return boolean
		 */
		public function _setOption () {
			return $this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
		
		/**
		 * 
		 * @abstract 如果key已经持有其他值，SET就覆写旧值，无视类型
		 * @access public
		 * 
		 * @param string $key
		 * @param string $value
		 * @return boolean
		 */
		public function _set ($key, $value) {
			return $this->redis->set($key, $value, $this->expire);
		}
		
		/**
		 * 
		 * @abstract 同时设置一个或多个key-value对
		 * @access public
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function _mset ($data = array()) {
			return $this->redis->mset($data);
		}
		
		/**
		 * 
		 * @abstract 返回所有(一个或多个)给定key的值
		 * @access public
		 * 
		 * @param unknown $keyArray
		 * @return mixed
		 */
		public function _mget ($keyArray = array()) {
			return $this->redis->mget($keyArray);
		}
		
		/**
		 * 
		 * @abstract 返回key所关联的字符串值,如果key不存在则返回特殊值nil
		 * @access public
		 * 
		 * @param string $key
		 * @return boolean
		 */
		public function _get ($key) {
			return $this->redis->get($key);
		}
		
		/**
		 * 
		 * @abstract 删除key所关联的字符串
		 * @access public
		 * 
		 * @param array $keyArray
		 * @return boolean
		 */
		public function _delete ($keyArray) {
			if(is_array($keyArray)) {
				foreach ($keyArray as $v) {
					$this->redis->del($v);
				}
			} else {
				$this->redis->del($keyArray);
			}
			
		}
		
		public function _keys ($match='') {
			if($match == '')
				return $this->redis->keys("*");
			else 
				return $this->redis->keys("{$match}*");
		}
		
		/**
		 * 
		 * @abstract 检查给定key是否存在
		 * @access public
		 * 
		 * @param string $key
		 * @return boolean
		 */
		public function exists ($key) {
			return $this->redis->exists($key);
		}
		
		/**
		 * 
		 * @abstract 排序之后返回的元素数量
		 * @access public
		 * 
		 * @param string $hkey 【列表key】
		 * @param int $offset  【要跳过的元素数量】
		 * @param int $count   【跳过offset个指定的元素之后】
		 * @param array $sort  【排序 ASC OR DESC】
		 * @return mixed
		 */
		public function limit ($hkey, $offset, $count, $sort="DESC") {
			return $this->redis->sort($hkey,array("LIMIT" => array($offset, $count), "SORT" => $sort));
		}
		
		/**
		 * 
		 * @abstract 列表排序
		 * @access public
		 * 
		 * @param string $hkey 【列表key】
		 * @return mixed
		 */
		public function sort ($hkey) {
			return $this->redis->sort($hkey);
		}
		
		/**
		 * 
		 * @abstract 将哈希表key中的域field的值设为value。
		 * @access public
		 * 
		 * @param string $hkey  【key】
		 * @param string $field 【指定域】
		 * @param string $value 
		 * @return boolean
		 */
		public function _hset ($hkey, $field, $value) {
			return $this->redis->hSet($hkey, $field, $value);
		}
		
		/**
		 * 
		 * @abstract 返回哈希表key中给定域field的值
		 * @access public
		 * 
		 * @param string $hkey
		 * @param string $field
		 * @return mixed
		 */
		public function _hget ($hkey, $field) {
			return $this->redis->hGet($hkey, $field);
		}
		
		/**
		 * 
		 * @abstract 将一个或多个值value插入到列表key的表头
		 * @access public
		 * 
		 * @param string $lkey
		 * @param array $data
		 * @return boolean
		 */
		public function _lpush ($lkey, $data) {
			if(is_array($data)) {
				foreach ($data as $value) {
					$this->redis->lPush($lkey, $value);
				}
			} else {
					$this->redis->lPush($lkey, $data);
			}
			return true;
		}
		
		/**
		 *
		 * @abstract 返回列表 key 中指定区间内的元素，区间以偏移量 start 和 stop 指定
		 * @access public
		 * 
		 * @param string $key
		 * @param string $start
		 * @param array $stop
		 * @return boolean
		 */
		public function _lrange ($key,$start, $stop) {
			return $this->lrange($key, $start, $stop);
		}
		
		/**
		 *
		 * @abstract 将一个或多个值value插入到列表key的表尾
		 * @access public
		 * 
		 * @param string $lkey
		 * @param array $data
		 * @return boolean
		 */
		public function _rpush ($lkey, $data) {
			if(is_array($data)) {
				foreach ($data as $value) {
					$this->redis->rPush($lkey, $value);
				}
			} else {
				$this->redis->rPush($lkey, $data);
			}
			return true;
		}
		
		/**
		 * 
		 * @abstract 移除并返回列表key的头元素
		 * @access public
		 * 
		 * @param string $lkey
		 * @return mixed
		 */
		public function _lpop ($lkey) {
			return $this->redis->lPop($lkey);
		}
		
		/**
		 *
		 * @abstract 移除并返回列表key的尾元素
		 * @access public
		 * 
		 * @param string $lkey
		 * @return mixed
		 */
		public function _rpop ($lkey) {
			return $this->redis->rPop($lkey);
		}
		
		/**
		 * 
		 * @abstract 添加一个String元素到skey对应的Set集合
		 * @access public
		 * 
		 * @param string $skey
		 * @package string $data
		 * @return boolean
		 */
		public function _sadd ($skey, $data) {
			return $this->redis->sAdd($skey, $data);
		}
		
		/**
		 *
		 * @abstract 从skey对应的set集合中移除给定元素
		 * @access public
		 * 
		 * @param string $skey
		 * @param string $value
		 * @return boolean
		 */
		public function _srem ($skey, $value) {
			return $this->redis->srem($skey, $value);
		}
		
		/**
		 *
		 * @abstract 返回set的元素个数
		 * @access public
		 * 
		 * @param string $skey
		 * @return boolean
		 */
		public function _scard ($skey) {
			return $this->redis->scard($skey);
		}
		
		/**
		 *
		 * @abstract 返回set的key对应值
		 * @access public
		 * 
		 * @param string $skey
		 * @return mixed
		 */
		public function _smembers ($skey) {
			return $this->redis->sMembers($skey);
		}
		
		/**
		 *
		 * @abstract 将 key 中储存的数字值增一
		 * @access public
		 * 
		 * @param string $skey
		 * @return mixed
		 */
		public function _incr ($skey) {
			return $this->redis->incr($skey);
		}
		
		/**
		 *
		 * @abstract 将 key 中储存的数字值减一
		 * @access public
		 * 
		 * @param string $skey
		 * @return mixed
		 */
		public function _decr ($skey) {
			return $this->redis->decr($skey);
		}
	
		public function close () {
			return $this->redis->close();
		}

		
		public function _expire($key,$date){
			$this->redis->expire($key, $date);
		}
		
	}