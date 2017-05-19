/**
 * Created by youcaihua on 15/12/9.
 */
var App = function() {

    var getGlobalImgPath = function() {
        return '/Resourse/Home/images';
    }

    var location = function() {
        return window.location.protocol + '//' + window.location.host;
    }

    var initScroll = function(el) {
        if(!el) el = '.scroller';
        $(el).each(function () {
            if($(this).attr('data-initialized')) return;
            var color = $(this).attr('data-handle-color') ? $(this).attr('data-handle-color') : '#26b69d';
            var distance = $(this).attr('data-handle-distance') ? $(this).attr('data-handle-distance') : '0px';
            var alwaysVisible = $(this).attr('data-always-visible') ? true : false;
            var railVisible = $(this).attr('data-rail-visible') ? true : false;
            $(this).slimScroll({
                allowPageScroll: false,
                size: '7px',
                borderRadius: '0px',
                color: color,
                wrapperClass: 'slim-scroll',
                distance: distance,
                position: 'right',
                height: 'auto',
                alwaysVisible: alwaysVisible,
                railVisible: railVisible,
                disableFadeOut: true
            });
            $(this).attr('data-initialized', 1);
        });
    }
    var destroyScroll = function(el) {
        $(el).each(function() {
            // destroy existing instance before updating the height
            if ($(this).attr("data-initialized") === "1") {
                $(this).removeAttr("data-initialized");
                $(this).removeAttr("style");
                var attrList = {};
                // store the custom attribures so later we will reassign.
                if ($(this).attr("data-handle-color")) {
                    attrList["data-handle-color"] = $(this).attr("data-handle-color");
                }
                if ($(this).attr("data-handle-distance")) {
                    attrList["data-handle-distance"] = $(this).attr("data-handle-distance");
                }
                if ($(this).attr("data-always-visible")) {
                    attrList["data-always-visible"] = $(this).attr("data-always-visible");
                }
                if ($(this).attr("data-rail-visible")) {
                    attrList["data-rail-visible"] = $(this).attr("data-rail-visible");
                }
                $(this).slimScroll({
                    wrapperClass: 'slim-scroll',
                    destroy: true
                });
                var the = $(this);
                // reassign custom attributes
                $.each(attrList, function(key, value) {
                    the.attr(key, value);
                });
            }
        });
    }
    var blockUI = function(options) {
        options = $.extend(true, {}, options);
        var html = '';
        if (options.animate) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '">' + '<div class="block-spinner-bar"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>' + '</div>';
        } else if (options.iconOnly) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + getGlobalImgPath() + '/lottery/loading-spinner-grey.gif" align=""></div>';
        } else if (options.textOnly) {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><span>&nbsp;&nbsp;' + (options.message ? options.message : 'LOADING...') + '</span></div>';
        } else {
            html = '<div class="loading-message ' + (options.boxed ? 'loading-message-boxed' : '') + '"><img src="' + getGlobalImgPath() + '/lottery/loading-spinner-grey.gif" align=""><span>&nbsp;&nbsp;' + (options.message ? options.message : '正在加载中...') + '</span></div>';
        }

        if (options.target) { // element blocking
            var el = $(options.target);
            if (el.height() <= ($(window).height())) {
                options.cenrerY = true;
            }
            el.block({
                message: html,
                baseZ: options.zIndex ? options.zIndex : 1000,
                centerY: options.cenrerY !== undefined ? options.cenrerY : false,
                css: {
                    top: '10%',
                    border: '0',
                    padding: '0',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                    opacity: options.boxed ? 0.05 : 0.1,
                    cursor: 'wait'
                }
            });
        } else { // page blocking
            $.blockUI({
                message: html,
                baseZ: options.zIndex ? options.zIndex : 1000,
                css: {
                    border: '0',
                    padding: '0',
                    backgroundColor: 'none'
                },
                overlayCSS: {
                    backgroundColor: options.overlayColor ? options.overlayColor : '#555',
                    opacity: options.boxed ? 0.05 : 0.1,
                    cursor: 'wait'
                }
            });
        }
    }
    var unblockUI = function(target) {
        if (target) {
            $(target).unblock({
                onUnblock: function() {
                    $(target).css('position', '');
                    $(target).css('zoom', '');
                }
            });
        } else {
            $.unblockUI();
        }
    }
    var getUrl = function(key) {
        var search = window.location.search.substring(1), i, val, params = search.split("&");
        for (i = 0; i < params.length; i++) {
            val = params[i].split("=");
            if (val[0] == key) {
                return unescape(val[1]);
            }
        }
    }
    var confirm = function(type, title, content, autoClose, button1, button2, fn1, fn2) {
        if(title == undefined) title = '确认消息';
        if(autoClose == undefined) autoClose = 0;
        if(button1 == undefined) {
            button1 = '确定<i class="icon ok"></i>';
        }
        if(button2 == undefined) {
            button2 = '取消<i class="icon close"></i>';
        }
        if(fn1 == undefined) fn1 = function() {};
        if(fn2 == undefined) fn2 = function() {};
        content = '<div class="msg">' + content + '</div>';
        if(type == 'warning') {
            title = '<i class="icon warning"></i>' + title;
            content = '<i class="icon warning"></i>' + content;
        }
        if(type == 'info') {
            title = '<i class="icon info"></i>' + title;
            content = '<i class="icon info"></i>' + content;
        }
        if(type == 'question') {
            title = '<i class="icon question"></i>' + title;
            content = '<i class="icon question"></i>' + content;
        }
        if(type == 'success') {
            title = '<i class="icon success"></i>' + title;
            content = '<i class="icon success"></i>' + content;
        }
        var box = new jBox('Confirm', {
            title: title,
            content: content,
            confirmButton: button1,
            cancelButton: button2,
            overlay: true,
            blockScroll: false,
            closeOnClick: false,
            closeButton: 'title',
            confirm: fn1,
            cancel: fn2,
            addClass: 'common-confirm',
            zIndex: 20000,
            onInit: function() {
                this.open();
            },
            onCloseComplete: function() {
                this.destroy();
                box = undefined;
            }
        });
        if(autoClose && autoClose != 0) {
            setTimeout(function() {
                if(box) box.close();
            }, autoClose);
        }
    }
    var alert = function(type, title, content, autoClose, button, fn) {
        if(title == undefined) title = '提示消息';
        if(autoClose == undefined) autoClose = 0;
        if(button == undefined) {
            button = '关闭<i class="icon close"></i>';
        }
        if(fn == undefined) fn = function() {}
        content = '<div class="msg">' + content + '</div>';
        if(type == 'warning') {
            title = '<i class="icon warning"></i>' + title;
            content = '<i class="icon warning"></i>' + content;
        }
        if(type == 'info') {
            title = '<i class="icon info"></i>' + title;
            content = '<i class="icon info"></i>' + content;
        }
        if(type == 'question') {
            title = '<i class="icon question"></i>' + title;
            content = '<i class="icon question"></i>' + content;
        }
        if(type == 'success') {
            title = '<i class="icon success"></i>' + title;
            content = '<i class="icon success"></i>' + content;
        }
        var box = new jBox('Confirm', {
            title: title,
            content: content,
            confirmButton: button,
            overlay: true,
            closeOnClick: false,
            blockScroll: false,
            closeButton: 'title',
            confirm: fn,
            addClass: 'common-alert',
            zIndex: 20000,
            onInit: function() {
                this.open();
            },
            onCloseComplete: function() {
                this.destroy();
                box = undefined;
            }
        });
        if(autoClose && autoClose != 0) {
            setTimeout(function() {
                if(box) box.close();
            }, autoClose);
        }
        $(".common-alert").show();
    }
    var notice = function(title, content, autoClose) {
        if(autoClose == undefined) autoClose = 3000;
        var noticeBox = new jBox('Notice', {
            content: content,
            autoClose: autoClose,
            closeOnClick: false,
            addClass: 'common-notice sys-message-list',
            onCloseComplete: function() {
                this.destroy();
                noticeBox = undefined;
            }
        });
        if(autoClose && autoClose != 0) {
            var clearTime = setTimeout(function() {
                if(noticeBox) {
                    noticeBox.close();
                    window.clearTimeout(clearTime);
                }
            }, autoClose);
        }
    }
    var tips = function(title, content, autoClose) {
        $('.message-tips').remove();
        var messageTips = $('<div class="message-tips">');
        messageTips.append('<div class="title">' + title + '</div>');
        messageTips.append('<div class="content">' + content + '</div>');
        $('body').append(messageTips);
        var width = messageTips.width();
        var height = messageTips.height();
        var winWidth = $(window).width();
        messageTips.css({bottom: -height, right: ((winWidth - 1050) / 2 - width) / 2}).stop().animate({bottom: 202}, 1000, 'easeOutExpo');
        if(autoClose) {
            setTimeout(function() {
                messageTips.stop().animate({bottom: -height,}, 1000, 'easeOutExpo', function() {
                    messageTips.remove();
                });
            }, autoClose);
        }
    }
    return {
        location: location,
        initScroll: initScroll,
        destroyScroll: destroyScroll,
        blockUI: blockUI,
        unblockUI: unblockUI,
        getUrl: getUrl,
        confirm: confirm,
        alert: alert,
        notice: notice,
        tips: tips
    }
}();