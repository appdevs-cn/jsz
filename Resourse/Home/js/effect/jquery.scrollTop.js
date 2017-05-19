/*
 *	jQuery(Zepto) Plug-in
 *	name: jqScrollTop
 *	version: 0.0.5
 *	author: Andy
 */

!function (o) {
	o.fn.jqScrollTop = function (t) {
		var i = this,
		e = o(window),
		s = o("html, body"),
		n = o.extend({
				autohide : !0,
				offset : 420,
				speed : 500,
				position : !0,
				right : 15,
				bottom : 30
			}, t);
		i.css({
			cursor : "pointer"
		}),
		n.autohide && i.css("display", "none"),
		n.position && i.css({
			position : "fixed",
			right : n.right,
			bottom : n.bottom
		}),
		i.click(function () {
			s.animate({
				scrollTop : 0
			}, n.speed)
		}),
		e.scroll(function () {
			var o = e.scrollTop();
			n.autohide && (o > n.offset ? i.fadeIn(n.speed) : i.fadeOut(n.speed))
		})
	}
}(jQuery);
