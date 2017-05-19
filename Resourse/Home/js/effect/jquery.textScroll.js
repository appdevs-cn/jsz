/*
 *	jQuery Plug-in
 *	name: textScroll
 *	desc: 文字跑马灯滚动
 */
$.fn.textScroll=function(){
	var speed=60,flag=null,tt,that=$(this),child=that.children();
	var p_w=that.width(), w=child.width();
	child.css({left:p_w});
	var t=(w+p_w)/speed * 1000;
	function play(m){
		var tm= m==undefined ? t : m;
		child.animate({left:-w},tm,"linear",function(){
			$(this).css("left",p_w);
			play();
		});
	}
	child.on({
		mouseenter:function(){
			var l=$(this).position().left;
			$(this).stop();
			tt=(-(-w-l)/speed)*1000;
		},
		mouseleave:function(){
			play(tt);
			tt=undefined;
		}
	});
	play();
}


//设置导航固定
$(function(){
	var $nav_container = $(".sec_header"),
	$body = $("body"),
	$headTop = $nav_container.offset().top;
	
	//导航固定
	$(window).scroll(function () {
		if ($(window).scrollTop() - 200 > $headTop) {
			$body.addClass("header-fixed");
		}else {
			$body.removeClass("header-fixed");
		}
	});
});