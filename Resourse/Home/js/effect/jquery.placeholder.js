;(function ($) {
	$.fn.extend({
		"placeholder" : function (options) {
			var opts = $.extend({
				defaultColor : '#bbb',
				currentColor : '#333',
				placeholder: ""
			}, options);
			opts.placeholder = typeof arguments[0] == "string" ? arguments[0] : opts.placeholder;
			
			$(this).each(function () {
				var _this = this;
				var supportPlaceholder = 'placeholder' in document.createElement('input');
				_this.val = opts.placeholder ? opts.placeholder : $(_this).attr("placeholder");
				
				if (!supportPlaceholder) {
					var self = $(_this), txt = self.attr('placeholder');
					self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));
					var pos = self.position(), h = self.outerHeight(true), paddingLeft = self.css('padding-left'), paddingTop = self.css("padding-top"), size = self.css("font-size");
					var holder = $('<span></span>').text(txt).css({
						position: "absolute",
						left: pos.left,
						top: pos.top,
						height: h,
						paddingLeft: paddingLeft,
						paddingTop: parseInt(paddingTop) - 4,
						color: opts.defaultColor,
						fontSize: size
					}).appendTo(self.parent());
					self.focusin(function(e) {
						holder.hide();
					}).focusout(function(e) {
						if(!self.val()){
							holder.show();
						}
					});
					holder.click(function(e) {
						holder.hide();
						self.focus();
					});
				}else{
					$(_this).attr("placeholder", _this.val);
				}
			});
			return this;
		}
	});
})(jQuery);