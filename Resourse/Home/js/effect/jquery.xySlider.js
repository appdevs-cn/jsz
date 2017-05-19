/*
 *	jQuery Plug-in
 *	name: jq_xySlider
 *	desc: 左右、上下滑动切换|渐变切换
 *	version: 0.2.0
 *	author: Yan Xinyun
 */
!function ($){
    $.fn.jq_xySlider = function(options){
		var opts = $.extend({
			selector: "ul",			//幻灯片包含的容器
			item: "li",				//幻灯片容器包含的项
			prev: ".js-btn-prev",	//上一个按钮
			next: ".js-btn-next",	//下一个按钮
			event: "click",			//事件 hover|click
			effect: "left",			//效果 fade:渐显 || top:上滚动 || left:左滚动
			autoplay: false,		//是否自动播放
			delay: 3000,			//切换持续时间
			speed: 500,				//切换速度 100~1000
			onShow: $.noop,		//开始执行函数
			onEnd: $.noop,		//切换结束执行函数
			cur: 0,					//当前索引
			navigation: null		//导航(obj|false)
		}, options),
		_slider = this,
		_sbox = _slider.find(opts.selector),
		_item = _sbox.find(opts.item),
		_prev = _slider.find(opts.prev),
		_next = _slider.find(opts.next),
		
		length = _item.size(),
		_timer = null;
		
		/**初始化幻灯片*/
		
		function _init(){
			_css();
			opts.autoplay && _autoplay();
			opts.onShow && typeof opts.onShow && opts.onShow.call(this, opts.cur);
			
			//滑过容器停止-继续播放
			_slider.hover(function(){
				_stop();
			}, function(){
				opts.autoplay && _autoplay();
			});
			
			//导航控制
			if(!opts.navigation) return;
			opts.event == "hover" ? opts.navigation.hover(function(){
				_stop();
				var idx = $(this).index();
				
				_play(idx, opts.effect);
			}, function(){
				opts.autoplay && $.contains(_slider, this) && (_autoplay());
			}) : opts.navigation.click(function(){
				var idx = $(this).index();
				
				_play(idx, opts.effect);
			});
		}
		_init();
		
		function _css(){
			opts.cur < 0 && (opts.cur = length - 1) || opts.cur >= length && (opts.cur = 0);
			_item.removeClass("active").eq(opts.cur).addClass("active");
			opts.navigation && opts.navigation.removeClass("on").eq(opts.cur).addClass("on");
			
			_slider.css({"height": _item.height(), "overflow": "hidden", "position":"relative"});
			_item.css({"width": _item.width()});
			
			opts.effect == "fade" ? _item.css({
				"position": "absolute",
				"left": 0,
				"top": 0
			}).eq(opts.cur).siblings().hide() : (
				opts.effect == "left" ? _sbox.css({"width":_item.width() * length, "left":-_item.width() * opts.cur}).children().css({"float": "left"}) : _sbox.css({"height":_item.height() * length, "top":-_item.height() * opts.cur})
			);
		}
		function _autoplay(){
			_timer = setInterval(function(){
				_triggerplay(opts.cur);
			}, opts.delay);
		}
		function _triggerplay(idx){
			var index = (idx >= length - 1) ? 0 : idx + 1;
			
			_play(index, opts.effect);
		}
		
		/**切换操作-[渐变|滑动]*/
		function _play(index, effect){
			opts.effect == "fade" ? !function(){
				_item.stop(true, true).fadeOut(opts.speed).eq(index).fadeIn(opts.speed);
			}() : (opts.effect == "left" ? !function(){
				_sbox.stop(true, true).animate({left: -_item.width() * index});
			}() : !function(){
				_sbox.stop(true, true).animate({top: -_item.height() * index});
			}());
			_item.removeClass("active").eq(index).addClass("active");
			
			opts.cur = index;
			opts.onEnd && typeof opts.onEnd && opts.onEnd.call(this, opts.cur);
		}
		/**停止切换*/
		function _stop(){
			clearInterval(_timer);
		}
		
		/**左右按钮切换*/
		_prev.on("click", function(){
			opts.cur == 0 ? _triggerplay(length - 2) : _triggerplay(opts.cur - 2);
		}).hover(function(){
			_stop();
		}, function(){
			opts.autoplay && $.contains(_slider, this) && (_autoplay());
		});
		
		_next.on("click", function(){
			opts.cur == length - 1 ? _triggerplay(-1) : _triggerplay(opts.cur);
		}).hover(function(){
			_stop();
		}, function(){
			opts.autoplay && $.contains(_slider, this) && (_autoplay());
		});
		
	}
}(jQuery);


/*
 *	jQuery Plug-in
 *	name: simple Step
 *	version: 0.1.0
 *	author: Yan Xinyun
 */
!function($){
	$.fn.jq_simpleStep = function(options){
		var args = $.extend({
			selector: "ul",				//幻灯片包含的容器
			item: "li",					//幻灯片容器包含的项
			prev: ".js-btn-prev",		//上一个选择器
			next: ".js-btn-next",		//下一个选择器
			visual: 6						//可视个数
		}, options),
		
		__sliderbox = this,
		__sbox = __sliderbox.find(args.selector),
		__item = __sbox.find(args.item),
		__prev = __sliderbox.find(args.prev),
		__next = __sliderbox.find(args.next),
		
		iLeft = false, iRight = true,
		length = __item.size(),
		__iNow = 0;
		
		/**设置包含的容器宽度*/
		__sbox.css({width: __item.width() * length});
		
		/**判断元素个数是否大于可视个数*/
		if(length <= args.visual){
			//下一个按钮不可用
			__prev.addClass("remove");
			__next.addClass("disabled remove");
		}else{
			__prev.on("click", function(){
				iLeft && (_toStep(--__iNow));
			});
			__next.on("click", function(){
				iRight && (_toStep(++__iNow));
			});
		}
		
		/**移动控制*/
		function _toStep(index){
			//处理到第一个后最后一个情况
			if(index <= 0){
				__prev.addClass("disabled");
				iLeft = false;
			}else{
				__prev.removeClass("disabled");
				iLeft = true;
			}
			
			if(index >= length - args.visual){
				__next.addClass("disabled");
				iRight = false;
			}else{
				__next.removeClass("disabled");
				iRight = true;
			}
			
			$(__sbox).stop().animate({left: -__item.width() * __iNow});
		}
	}
}(jQuery);
