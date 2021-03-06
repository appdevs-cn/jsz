<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// | lanfengye <zibin_5257@163.com>
// +----------------------------------------------------------------------

class Page {
	
	// 分页栏每页显示的页数
	public $rollPage = 5;
	// 页数跳转时要带的参数
	public $parameter ;
	// 分页URL地址
	public $url = '';
	// 默认列表每页显示行数
	public $listRows = 20;
	// 起始行数
	public $firstRow ;
	// 分页总页面数
	protected $totalPages ;
	// 总行数
	protected $totalRows ;
	// 当前页数
	protected $nowPage ;
	// 分页的栏的总页数
	protected $coolPages ;
	// 分页显示定制
	protected $config = array('header'=>'条记录','prev'=>'上一页','next'=>'下一页','first'=>'第一页','last'=>'最后一页','theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first% %prePage% %linkPage% %nextPage% %end% %ajax%');
	// 默认分页变量名
	protected $varPage;
	//分页外层div的id
	protected $pagesId;
	//分页内容替换目标ID
	protected $target;
	//是否滚动自动加载 默认false,

	/**
	 +----------------------------------------------------------
	 * 架构函数
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 * @param array $totalRows  总的记录数
	 * @param array $listRows  每页显示记录数
	 * @param array $parameter  分页跳转的参数
	 +----------------------------------------------------------
	 */
	public function __construct($totalRows,$listRows='',$parameter='',$url='',$target,$pagesId) {
		$this->totalRows = $totalRows;
		$this->parameter = $parameter;
		$this->url = $url;
		$this->target = $target;
		$this->pagesId = $pagesId;
		$this->varPage = C('VAR_PAGE') ? C('VAR_PAGE') : 'p' ;
		if(!empty($listRows)) {
			$this->listRows = intval($listRows);
		}
		$this->totalPages = ceil($this->totalRows/$this->listRows);     //总页数
		$this->coolPages  = ceil($this->totalPages/$this->rollPage);
		$this->nowPage  = !empty($_REQUEST[$this->varPage])?intval($_REQUEST[$this->varPage]):1;
		if(!empty($this->totalPages) && $this->nowPage>$this->totalPages) {
			$this->nowPage = $this->totalPages;
		}
		$this->firstRow = $this->listRows*($this->nowPage-1);
	}
	
	public function setConfig($name,$value) {
		if(isset($this->config[$name])) {
			$this->config[$name]    =   $value;
		}
	}
	
	/**
	 +----------------------------------------------------------
	 * 分页显示输出
	 +----------------------------------------------------------
	 * @access public
	 +----------------------------------------------------------
	 */
	public function show() {
		if(0 == $this->totalRows) return '';
		$p = $this->varPage;
		$nowCoolPage      = ceil($this->nowPage/$this->rollPage);
		$url  =  $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'],'?')?'':"?").$this->parameter;
		$parse = parse_url($url);
		if(isset($parse['query'])) {
			parse_str($parse['query'],$params);
			unset($params[$p]);
			$url   =  $parse['path'].'?'.http_build_query($params);
		}
		$url = "http://".$_SERVER[HTTP_HOST].$url."&";
		//上下翻页字符串
		$upRow   = $this->nowPage-1;
		$downRow = $this->nowPage+1;
		if ($upRow>0){
			$upPage = '<a id=".$this->pagesId." p="'.$upRow.'" class="pageCls first" href="javascript:void(0);">&laquo;</a>';
		}else{
			$upPage = '<a class="first disabled" href="javascript:void(0);" title="上一页">&laquo;</a>';
		}
	
		if ($downRow <= $this->totalPages){
			$downPage = '<a id="'.$this->pagesId.'" p="'.$downRow.'" class="pageCls last" href="javascript:void(0);">&raquo;</a>';
		}else{
			$downPage = '<a class="last disabled" href="javascript:void(0);" title="下一页">&raquo;</a>';
		}
		// << < > >>
//		if($nowCoolPage == 1){
//			$theFirst = "";
//			$prePage = "";
//		}else{
//			$preRow =  $this->nowPage-$this->rollPage;
//			$prePage = "<a id='{$this->pagesId}' href='".$url.$p."=$preRow' >上".$this->rollPage."页</a>";
//			$theFirst = "<a id='{$this->pagesId}' href='".$url.$p."=1' >".$this->config['first']."</a>";
//		}
//		if($nowCoolPage == $this->coolPages){
//			$nextPage = "";
//			$theEnd="";
//		}else{
//			$nextRow = $this->nowPage+$this->rollPage;
//			$theEndRow = $this->totalPages;
//			$nextPage = "<a id='{$this->pagesId}' href='".$url.$p."=$nextRow' >下".$this->rollPage."页</a>";
//			$theEnd = "<a id='{$this->pagesId}' href='".$url.$p."=$theEndRow' >".$this->config['last']."</a>";
//		}
		// 1 2 3 4 5
		$linkPage = "";
		for($i=1;$i<=$this->rollPage;$i++){
			$page=($nowCoolPage-1)*$this->rollPage+$i;
			if($page!=$this->nowPage){
				if($page<=$this->totalPages){
					$linkPage .= '<a href="javascript:void(0);" class="pageCls" id="'.$this->pagesId.'" p="'.$page.'">'.$page.'</a>';
				}else{
					break;
				}
			}else{
				if($this->totalPages != 1){
					$linkPage .= '<a class="pageCls selected" href="javascript:void(0);">'.$page.'</a>';

				}
			}
		}
		$pageStr = str_replace(
			array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%','%ajax%'),
			array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd,$ajax),$this->config['theme']);
		return $pageStr;
	}
}	