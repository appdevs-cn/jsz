/*
 *	jQuery Plug-in
 *	name: setNum
 *	desc: 类似购物车加减数量
 */
$.setNum = {
	set: function(code, obj){
		var ipt = $(obj).siblings("input");
		var memNums = parseInt($.trim(ipt.val()));
		switch (code) {
			case 1: {
				memNums++;
			}
			break;
			case -1: {
				memNums--;
			}
			break;
		}
		ipt.val(memNums);
		$.setNum.check(ipt);
	},
	check: function(obj){
		var o = $(obj);
		//数量小于0
		var memNums = parseInt($.trim(o.val()));
		if (memNums <= 0) {
			o.val(1);
			return;
		}

		//判断数量是否是数字
		var regExp = /^[1-9]*[1-9][0-9]*$/;
		if (isNaN(memNums) || !regExp.test($.trim(o.val()))) {
			o.val(1);
			return;
		}

		//数量大于预设数字（假定预设9999）
		//var storeNums = parseInt($.trim($('#data_storeNums').text()));
		var storeNums = 9999;
		if (memNums >= storeNums) {
			o.val(storeNums);
			return;
		}
	}
};
