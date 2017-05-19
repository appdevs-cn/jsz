!(function(win){
    var indexHandler = {
        NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,CloseFloatBox: function()
        {
            $(".btn__close-floatBox").on("click", function(){
				$(this).parents(".floatBox__panel").remove();
			})
        }
        ,Loading: function()
        {
            // 页面加载
            imagesLoaded('.JszContent',function(){
                indexHandler.LoadingAfter()
            })
        }
        ,LoadingAfter: function()
        {
            // 页面加载之后执行初始化
            $(".loader").remove();
            $("body").removeClass('body-bg')
            $(".JszContent").show();
            indexHandler.NoticeSlider(), indexHandler.CloseFloatBox(), indexHandler.Logout(),indexHandler.xySlider(),
            indexHandler.Rolling(),indexHandler.MulitRolling(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.TickerHandler()
        }
        ,Logout: function()
        {
            // 退出系统
            $.dialog.close('*');
            $(document).on('click', 'a[data-method=out]', function(e) {
                $uid = $('input[name=uid]').val()
                layui.use('layer', function(layer){
                    var index = layer.confirm('确定要退出系统吗？', {
                        btn: ['退出','玩一会'] 
                        ,anim:3
                        ,btnAlign: 'c'
                        }, function(){
                            window.location.href = '/logout';
                            $('input[name=uid]').val(" ");
                            $('input[name=path]').val(" ");
                            if ($uid != "")
                                window.socket.emit("logout", $uid);
                            layer.close(index)
                        });
                })
            })
        }
        ,xySlider: function()
        {
            // 大图轮播
            var $slider = $(".js_idxSlider"), $nav = $(".slider-nav i");
            $slider.jq_xySlider({
                effect: "fade",
                autoplay:true,
                delay: 5000,
                onEnd: function(idx){
                    $nav.removeClass("on").eq(idx).addClass("on");
                },
                navigation: $nav
            });
        }
        ,Rolling: function()
        {
            // 信息滚动
            var _rollWrap = $(".mulitline"), _interval = 3000, _timer;
            _rollWrap.hover(function(){
                clearInterval(_timer);
            }, function(){
                _timer = setInterval(function(){
                    var _field = _rollWrap.find("dd:first"), _h = _field.height();
                    _field.animate({marginTop: -_h + "px"}, 200, function(){
                        _field.css("marginTop", 0).appendTo(_rollWrap);
                    });
                }, _interval);
            }).trigger("mouseleave");
        }
        ,MulitRolling: function()
        {
            // 多行滚动
            var _rollWrap1 = $("ul.mulitline_games"), _interval1 = 3000, _timer1;
            _rollWrap1.width(_rollWrap1.find("li").outerWidth() * _rollWrap1.find("li").size());
            _rollWrap1.hover(function(){
                clearInterval(_timer1);
            }, function(){
                _timer1 = setInterval(function(){
                    var _field = _rollWrap1.find("li:first"), _h = _field.width();
                    _field.animate({marginLeft: -_h + "px"}, 500, function(){
                        _field.css("marginLeft", 0).appendTo(_rollWrap1);
                    });
                }, _interval1);
            }).trigger("mouseleave");
        }
        ,Dropdown: function()
        {
            $(".J__dropdown").hover(function(){
				$(this).toggleClass("active");
				$(this).find(".dropdownMenu").stop(true).slideToggle(300);
			});
        }
        ,NavMenu: function()
        {
            $(".J__navMenu li:not('.u-info')").hover(function(){
				$(this).addClass("on").siblings().removeClass("on");
			}, function(){
				$(this).removeClass("on");
				$(".J__navMenu .current").addClass("on");
			});
        }
        ,TickerHandler: function()
        {
            Ticker.prototype.showJackpot = function() {
                var newvalue = this.getJackpot()
                if (this.type != 'count') {
                    newvalue = Math.round(newvalue * 100) / 100 + ''
                    if (newvalue.match(/^\d+\.\d$/)) {
                        newvalue = newvalue + '0'
                    }
                    if (newvalue.match(/^\d+$/)) {
                        newvalue = newvalue + '.00'
                    }
                }
                var text = '';
                if (newvalue > 0) {
                    text = (this.signpos != 0 ? newvalue + this.sign : this.sign + newvalue)
                }
                if (newvalue > 0 && this.type == 'count') {
                    text = newvalue
                }
                var imgtxt = text.toString()
                    .replace(/(\d{1,3})(?=(?:\d{3})+(?!\d))/g, '$1,')
                    .replace(/(\d{1})/g, '<strong>$1</strong>')
                    .replace(/(,)/g, '<strong>,</strong>')
                    .replace('.', '<strong>.</strong>')
                    .replace('¥', '<i class="fa fa-cny"></i>')
                if (imgtxt != '') {
                    this.textbox.innerHTML = imgtxt
                }
            }
            ticker = new Ticker({
                info: 2,
                casino: "playtech",
                currency: 'cny',
                root_url: "https://tickers.playtech.com/"
            })
            ticker.attachToTextBox("total-ticker")
            ticker.SetCurrencyPos(0)
            ticker.SetCurrencySign('CNY ')
            ticker.tick()
        }
        ,OnlineUser: function()
        {
            window.socket.on('ChildMemberCount',function(count){
                $("#online").text(count);
            })
        }
        ,CreateEbetAccount: function()
        {
            $('a[data-method=CreateEbetAccount]').on('click', function(){
                $.post('/CreateEbetAccount',function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('开通游戏成功',{time:2000,end:function(){
                                window.location.reload();
                            }})
                        })
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,EbetGameUrl: function()
        {
            $('a[data_method="ebetGame"]').on('click', function(){
                $.post("/EbetGameUrl",function(gameurl){
                        layer.msg("注意:Ebet游戏登录用户为ebet_平台用户名;初始密码为a123456!5秒后进入游戏",{time:5000,end:function(){
                            window.open(gameurl)
                        }})
                    },'text')
                })
        }
        ,CreateMgAccount: function()
        {
            $('a[data-method=CreateMgAccount]').on('click', function(){
                $.post('/CreateMgAccount',function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('开通游戏成功',{time:2000,end:function(){
                                window.location.reload();
                            }})
                        })
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,MgGameUrl: function()
        {
            $('a[data-method="gmGame"]').on('click', function(){
                var til = $(this).attr('data-title')
                $.post('/GMGame',{"gameid":$(this).attr("data-field")},function(gameUrl){
                    if(gameUrl=="")
                    {
                        return false;
                    }
                    window.open(gameUrl)
                },'text')
            })
        }
        ,CreateAgAccount: function()
        {
            $('a[data-method=CreateAgAccount]').on('click', function(){
                $.post('/CreateAgAccount',function(info){
                    if(info)
                    {
                        layui.use('layer',function(layer){
                            layer.msg('开通游戏成功',{time:2000,end:function(){
                                window.location.reload();
                            }})
                        })
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,AgGameUrl: function()
        {
            $('a[data_method="agGame"]').on('click', function(){
                var gameType = $(this).attr("data-field")
                var title = $(this).attr("data-title")
                $.post("/AgGameUrl",{"gameType":gameType},function(gameurl){
                        window.open(gameurl)
                    },'text')
                })
        }
        ,CreatePtAccount: function()
        {
            $('a[data-method=CreatePtAccount]').on('click', function(){
                $.post('/CreatePtAccount',function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>开通游戏成功<i class='suc-close'></i></div>")
                        indexHandler.PtGameList()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>开通游戏失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
        ,PtGameUrl: function()
        {
            $('a[data-method="ptGame"]').on('click', function(){
                var til = $(this).attr('data-title')
                $.post('/PtGame',{"gameid":$(this).attr("data-field")},function(gameUrl){
                    if(gameUrl=="")
                    {
                        return false;
                    }
                    window.open(gameUrl)
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading(),index.CreateMgAccount(),index.MgGameUrl(),index.EbetGameUrl(),index.CreateEbetAccount(),
    index.CreateAgAccount(),index.AgGameUrl(),index.CreatePtAccount(),index.PtGameUrl()
})