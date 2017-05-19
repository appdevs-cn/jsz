/*
 * jQuery(Zepto) Plug-in
 *    name: Simple Switch Tab
 * version: 0.0.4
 *  author: Zheng Chaoping 
 */

(function ($) {
    $.extend($.fn, {
        SimpleSwitchTab: function (evt, fn, options) {
            var nop = function () {};
            if ("function" === typeof evt) {
                options = fn;
                fn = evt;
                evt = "click";
            }
            evt = (evt || "click").toString();
            fn = "function" === typeof fn ? fn : nop;

            /* compatible v0.0.3- */
            if ("[object Boolean]" == Object.prototype.toString.call(options)) {
                options = {
                    live: options
                };
            }
            options = $.extend({
                live: false,
                container: document
            }, options);

            var ACT = "st-active", PANEL = "st-panel-cls";

            var $tabs = $(this),
                panelCLS = $tabs.data(PANEL),
                $panel = $(options.container).find("." + panelCLS);
            $tabs.on(evt, function __(event) {
                if (options.live) {
                    $tabs.off(evt, __);
                    $tabs = $('[data-' + PANEL + '="' + panelCLS + '"]');
                    $panel = $(options.container).find("." + panelCLS);
                    $tabs.on(evt, __);
                }
                var idx = $tabs.index(this);
                $tabs.data(ACT, false), $(this).data(ACT, true);
                $($panel.hide().get(idx)).show();
                return fn.call(this, event, $tabs, $panel);
            }).filter('[data-' + ACT + '="true"]').trigger(evt);

            return this;
        }
    });
}($));
