!(function(win){
    var indexHandler = {
         NoticeSlider: function()
        {
            $("#J__noticeSlider").textScroll(); // 公告滚动
        }
        ,lossLine: function()
        {
            $(".J__filter-lossLine").hover(function(){
                $(this).toggleClass("active");
                $(this).find(".selectLists").stop(true).slideToggle(300);
            });
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
            window.socket.on("message", function($data) {
                var $json = $data.split('-')
                if ($json[1] == 5 || $json[1] == 4 || $json[1] == 12 || $json[1] == 13) {
                    indexHandler.GetContract()
                }
            })
            indexHandler.NoticeSlider(), indexHandler.Logout(),
            indexHandler.xySlider(),indexHandler.Dropdown(),indexHandler.NavMenu(),indexHandler.lossLine(),
            indexHandler.GetContract()
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
                        ,area:['300px','180px']
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
        ,GetContract: function()
        {
            $.post('/dayrategetreplayContract',function(data){
                if(data.length==0)
                {
                    $("#list").html(tpl('#empty_contract_list'))
                    return
                }
                $("#list").html(tpl('#contract_list',{rows:[data]}))
                indexHandler.AgreeContract()
                indexHandler.ReleaseContract()
                indexHandler.AgreeReleaseContract()
            },'json')
        }
        ,AgreeContract: function()
        {
            $('a[data-method="agree_dayrate_contract"]').on("click", function(){
                var agreeid = $(this).attr("data-field")
                layui.use('layer', function(layer){
                var index = layer.confirm('<div style="text-align:center">您确定要跟上级进行日工资签约吗?</div>', {
                    btn: ['确定签约','取消签约']
                    ,anim:3
                    ,btnAlign: 'c'
                    }, function(){
                        $.post("/dayrateagree",{"agreeid":agreeid},function(info){
                            layer.close(index)
                            if(info)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>签约成功<i class='suc-close'></i></div>")
                                indexHandler.GetContract()
                            }
                            else
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>签约失败<i class='err-close'></i></div>")
                            }
                        },'text')
                        return
                    });
                })
            })
        }
        ,ReleaseContract: function()
        {
            $('a[data-method="relieve_dayrate_contract"]').on("click", function(){
                var agreeid = $(this).attr("data-field")
                layui.use('layer', function(layer){
                var index = layer.confirm('<div style="text-align:center">您确定要跟上级解除日工资签约吗?</div>', {
                    btn: ['确定解约','取消解约']
                    ,anim:3
                    ,btnAlign: 'c'
                    }, function(){
                        $.post("/dayrateapplyRelease",{"agreeid":agreeid},function(info){
                            layer.close(index)
                            if(info)
                            {
                                alertify.success("<div class='text'><i class='ico-success'></i>等待上级同意解除<i class='suc-close'></i></div>")
                                indexHandler.GetContract()
                            }
                            else
                            {
                                alertify.error("<div class='text'><i class='ico-error'></i>解约失败【说明:你尚有未处理的解约项目】<i class='err-close'></i></div>")
                            }
                        },'text')
                        return
                    });
                })
            })
        }
        ,AgreeReleaseContract: function()
        {
            $('a[data-method="agreeDisDayrateContract"]').on('click', function(){
                $.post("/dayratedowntotopagreeRelease",{"agreeid":$(this).attr("data-field")},function(info){
                    if(info)
                    {
                        alertify.success("<div class='text'><i class='ico-success'></i>契约解除成功<i class='suc-close'></i></div>")
                        
                        indexHandler.GetContract()
                    }
                    else
                    {
                        alertify.error("<div class='text'><i class='ico-error'></i>契约解除失败<i class='err-close'></i></div>")
                    }
                },'text')
            })
        }
    }
    win.index = indexHandler;
})(window);
$(function(){
    index.Loading()
})