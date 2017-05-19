var TrendData = function() {
	var isLoading = false;
	var load = function(data, thisContent, callback) {
		if(!isLoading) {
			isLoading = true;
			App.blockUI({
				target: thisContent,
				boxed: true
			});
			$.ajax({
				type: 'post',
				url: '/LotteryTrend',
				data: data,
				timeout: 10000,
				dataType: 'json',
				success: function(response) {
					isLoading = false;
					App.unblockUI(thisContent);
					if($.isFunction(callback)) {
						callback(response.list);
					}
				},
				error: function() {
					isLoading = false;
					App.unblockUI(thisContent);
				}
			});
		}
	}
	
	return {
		load: load
	}
	
}();

var TrendSSC = function() {
	
	var trendMethod = $('.trend-method');
	var trendControl = $('.trend-control');
	var trendWrapper = $('.trend-wrapper');
	
	var initWuxing = function() {
		
		var initControl = function() {
			var innerHtml = 
			'<div class="tools">'+
				'<label><input name="guides" type="checkbox" checked="checked">辅助线</label>'+
				'<label><input name="lostNum" type="checkbox" checked="checked">遗漏</label>'+
				'<label><input name="lostLine" type="checkbox">遗漏条</label>'+
				'<label><input name="trend" type="checkbox" checked="checked">走势</label>'+
			'</div>'+
			'<div class="time">'+
				'<a data-command="latest-30">最近30期</a>'+
				'<a data-command="latest-50">最近50期</a>'+
				'<a data-command="date-today">今日数据</a>'+
				'<a data-command="date-yesterday">昨日数据</a>'+
				'<a data-command="date-before-yesterday">前日数据</a>'+
			'</div>';
			trendControl.html(innerHtml);
		}
		
		initControl();
		
		var inputGuides = trendControl.find('input[name="guides"]');
		var inputLostNum = trendControl.find('input[name="lostNum"]');
		var inputLostLine = trendControl.find('input[name="lostLine"]');
		var inputTrend = trendControl.find('input[name="trend"]');
		
		inputGuides.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-guides');
			} else {
				thisTable.addClass('hide-guides');
			}
		});
		
		inputLostNum.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-lost');
			} else {
				thisTable.addClass('hide-lost');
			}
		});
		
		inputLostLine.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				initLostLine();
			} else {
				thisTable.find('tbody > tr > td').removeClass('lost');
			}
		});
		
		inputTrend.change(function() {
			var thisCanvas = trendWrapper.find('canvas');
			if($(this).is(':checked')) {
				initTrendLine();
			} else {
				thisCanvas.hide();
			}
		});
		
		var buildThead = function(thisThead) {
			var template = 
			'<tr>'+
				'<td rowspan="2" class="expect border-right">期号</td>'+
				'<td rowspan="2" class="opencode border-right">开奖号码</td>'+
				'<td colspan="10" class="border-right">万位</td>'+
				'<td colspan="10" class="border-right">千位</td>'+
				'<td colspan="10" class="border-right">百位</td>'+
				'<td colspan="10" class="border-right">十位</td>'+
				'<td colspan="10" class="border-right">个位</td>'+
			'</tr>'+
			'<tr class="border-bottom">'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code">9</td>'+
			'</tr>';
			thisThead.html(template);
		}
		
		var ThisData = []; // 号码数据
		var DataTotalCount = []; // 出现总次数
		var DataTmpLost = []; // 当前累计遗漏
		var DataSumLost = []; // 总的遗漏
		var DataMaxLost = []; // 最大遗漏
		
		// 格式化号码
		var formatCode = function(thisRow, code) {
			var tmpCode = code.split(',');
			$.each(tmpCode, function(i, val) {
				if(DataTotalCount[i] == undefined) {
					DataTotalCount[i] = [];
				}
				if(DataTmpLost[i] == undefined) {
					DataTmpLost[i] = [];
				}
				if(DataSumLost[i] == undefined) {
					DataSumLost[i] = [];
				}
				if(DataMaxLost[i] == undefined) {
					DataMaxLost[i] = [];
				}
				for (var j = 0; j < 10; j++) {
					if(DataTotalCount[i][j] == undefined) {
						DataTotalCount[i][j] = 0;
					}
					if(DataTmpLost[i][j] == undefined) {
						DataTmpLost[i][j] = 0;
					}
					if(DataSumLost[i][j] == undefined) {
						DataSumLost[i][j] = 0;
					}
					if(DataMaxLost[i][j] == undefined) {
						DataMaxLost[i][j] = 0;
					}
					var thisCell = $('<td class="code">');
					thisCell.attr('data-idx', i);
					if(val == j) {
						DataTotalCount[i][j] += 1;
						DataTmpLost[i][j] = 0;
						thisCell.addClass('open').append('<i>' + j + '</i>');
					} else {
						DataTmpLost[i][j] += 1;
						DataSumLost[i][j] += DataTmpLost[i][j];
						if(DataTmpLost[i][j] > DataMaxLost[i][j]) {
							DataMaxLost[i][j] = DataTmpLost[i][j];
						}
						thisCell.append(DataTmpLost[i][j]);
					}
					if(j == 9 && i != tmpCode.length - 1) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			});
		}
		
		// 构建中间
		var buildTbody = function(thisTbody) {
			$.each(ThisData, function(i, val) {
				var thisRow = $('<tr>');
				if((i + 1) % 5 == 0) {
					thisRow.addClass('guides');
				}
				thisRow.append('<td class="border-right">' + val.expect + '</td>');
				thisRow.append('<td class="border-right">' + val.code + '</td>');
				thisRow.append(formatCode(thisRow, val.code));
				thisTbody.append(thisRow);
			});
		}
		
		// 构建总的出现次数
		var buildTotalCount = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">出现总次数</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataTotalCount.length; i++) {
				for (var j = 0; j < DataTotalCount[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataTotalCount[i][j]);
					if(j == 9 && i != DataTotalCount.length - 1) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisTfoot.append(thisRow);
		}
		
		// 构建平均遗漏值
		var buildAvgLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">平均遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataSumLost.length; i++) {
				for (var j = 0; j < DataSumLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(Math.round(DataSumLost[i][j] / ThisData.length));
					if(j == 9 && i != DataSumLost.length - 1) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisTfoot.append(thisRow);
		}
		
		// 构建最大遗漏值
		var buildMaxLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">最大遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataMaxLost.length; i++) {
				for (var j = 0; j < DataMaxLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataMaxLost[i][j]);
					if(j == 9 && i != DataMaxLost.length - 1) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisTfoot.append(thisRow);
		}
		
		var buildFootDoc = function(thisTfoot) {
			var template = 
			'<tr>'+
				'<td rowspan="2" class="expect border-right">期号</td>'+
				'<td rowspan="2" class="opencode border-right">开奖号码</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code">9</td>'+
			'</tr>'+
			'<tr>'+
				'<td colspan="10" class="border-right">万位</td>'+
				'<td colspan="10" class="border-right">千位</td>'+
				'<td colspan="10" class="border-right">百位</td>'+
				'<td colspan="10" class="border-right">十位</td>'+
				'<td colspan="10" class="border-right">个位</td>'+
			'</tr>';
			thisTfoot.append(template);
		}
		
		// 构建底部
		var buildTfoot = function(thisTfoot) {
			buildTotalCount(thisTfoot);
			buildAvgLost(thisTfoot);
			buildMaxLost(thisTfoot);
			buildFootDoc(thisTfoot);
		}
		
		// 初始化走势
		var initTrendLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisCanvas = trendWrapper.find('canvas');
			if(thisCanvas.length > 0) {
				thisCanvas.remove();
			}
			thisCanvas = $('<canvas>');
			thisCanvas.attr('width', thisTable.width());
			thisCanvas.attr('height', thisTable.height());
			trendWrapper.append(thisCanvas);
			// 构建走势图
			var context = thisCanvas[0].getContext('2d');
			context.lineWidth = 1.5;
			context.strokeStyle = '#26b69d';
			for (var i = 0; i < 5; i++) {
				var paths = thisTable.find('[data-idx="' + i + '"].open');
				$.each(paths, function(j) {
					var x = $(this).position().left;
					var y = $(this).position().top;
					if(j == 0) {
						context.moveTo(x + 9, y + 15);
					} else {
						context.lineTo(x + 9, y + 15);
					}
				});
				context.stroke();
			}
		}
		
		// 初始化遗漏
		var initLostLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisTbody = thisTable.find('tbody');
			var thisRows = thisTbody.find('tr');
			for (var i = 0; i < 50; i++) {
				var cells = [];
				$.each(thisRows, function(j, val) {
					cells.push($(this).find('td.code').eq(i));
				});
				for (var j = cells.length; j > 0; j--) {
					if($(cells[j - 1]).hasClass('open')) {
						break;
					} else {
						$(cells[j - 1]).addClass('lost');
					}
				}
			}
		}
		
		var buildTable = function(data) {
			ThisData = data;
			DataTotalCount = [];
			DataTmpLost = [];
			DataSumLost = [];
			DataMaxLost = [];
			
			var thisTable = $('<table>');
			var thisThead = $('<thead>');
			var thisTbody = $('<tbody>');
			var thisTfoot = $('<tfoot>');
			// 构建头部
			buildThead(thisThead);
			thisTable.append(thisThead);
			// 构建中间
			buildTbody(thisTbody);
			thisTable.append(thisTbody);
			// 构建底部
			buildTfoot(thisTfoot);
			thisTable.append(thisTfoot);
			trendWrapper.html(thisTable);
			// 工具栏
			inputGuides.trigger('change');
			inputLostNum.trigger('change');
			inputLostLine.trigger('change');
			inputTrend.trigger('change');
		}
		
		trendControl.find('.time > a').click(function() {
			var command = $(this).attr('data-command');
			var params = {lotteryId: Lottery.id, command: command};
			TrendData.load(params, trendWrapper, function(data) {
				if(data && data.length > 0) {
					buildTable(data);
				}
			});
		}).eq(0).trigger('click');
	}
	
	var initSanXing = function(type) {
		
		var initControl = function() {
			var innerHtml = 
			'<div class="tools">'+
				'<label><input name="guides" type="checkbox" checked="checked">辅助线</label>'+
				'<label><input name="lostNum" type="checkbox" checked="checked">遗漏</label>'+
				'<label><input name="lostLine" type="checkbox">遗漏条</label>'+
				'<label><input name="trend" type="checkbox" checked="checked">走势</label>'+
			'</div>'+
			'<div class="time">'+
				'<a data-command="latest-30">最近30期</a>'+
				'<a data-command="latest-50">最近50期</a>'+
				'<a data-command="date-today">今日数据</a>'+
				'<a data-command="date-yesterday">昨日数据</a>'+
				'<a data-command="date-before-yesterday">前日数据</a>'+
			'</div>';
			trendControl.html(innerHtml);
		}
		
		initControl();
		
		var inputGuides = trendControl.find('input[name="guides"]');
		var inputLostNum = trendControl.find('input[name="lostNum"]');
		var inputLostLine = trendControl.find('input[name="lostLine"]');
		var inputTrend = trendControl.find('input[name="trend"]');
		
		inputGuides.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-guides');
			} else {
				thisTable.addClass('hide-guides');
			}
		});
		
		inputLostNum.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-lost');
			} else {
				thisTable.addClass('hide-lost');
			}
		});
		
		inputLostLine.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				initLostLine();
			} else {
				thisTable.find('tbody > tr > td').removeClass('lost');
			}
		});
		
		inputTrend.change(function() {
			var thisCanvas = trendWrapper.find('canvas');
			if($(this).is(':checked')) {
				initTrendLine();
			} else {
				thisCanvas.hide();
			}
		});
		
		var buildThead = function(thisThead) {
			var balls = ["万位", "千位", "百位", "十位", "个位"];
			var row1 = $('<tr>');
			row1.append('<td rowspan="2" class="expect border-right">期号</td>');
			row1.append('<td rowspan="2" class="opencode border-right">开奖号码</td>');
			if(type == 'before') {
				for (var i = 0; i < 3; i++) {
					row1.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'middle') {
				for (var i = 1; i < 4; i++) {
					row1.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'after') {
				for (var i = 2; i < 5; i++) {
					row1.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			row1.append('<td rowspan="2" class="border-right">大小形态</td>');
			row1.append('<td rowspan="2" class="border-right">单双形态</td>');
			row1.append('<td rowspan="2" class="border-right">组三</td>');
			row1.append('<td rowspan="2" class="border-right">组六</td>');
			row1.append('<td rowspan="2" class="border-right">豹子</td>');
			row1.append('<td rowspan="2" class="border-right">直选和值</td>');
			thisThead.append(row1);
			var row2 = 
			'<tr class="border-bottom">'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
			'</tr>';
			thisThead.append(row2);
		}
		
		var ThisData = []; // 号码数据
		var DataTotalCount = []; // 出现总次数
		var DataTmpLost = []; // 当前累计遗漏
		var DataSumLost = []; // 总的遗漏
		var DataMaxLost = []; // 最大遗漏
		
		// 格式化类型
		var formatType = function(thisCode, thisRow) {
			// 大小
			var cell_dx = $('<td class="bg-blue border-right">');
			$.each(thisCode, function(i, val) {
				cell_dx.append(val < 5 ? '小' : '大');
			});
			thisRow.append(cell_dx);
			// 单双
			var cell_ds = $('<td class="bg-green border-right">');
			$.each(thisCode, function(i, val) {
				if($.inArray(val, ['0','2','4','6','8']) != -1) {
					cell_ds.append('双');
				} else {
					cell_ds.append('单');
				}
			});
			thisRow.append(cell_ds);
			// 组三
			var cell_zs = $('<td class="border-right">');
			if(thisCode[0] == thisCode[1] || thisCode[1] == thisCode[2] || thisCode[0] == thisCode[2]) {
				cell_zs.append('<i class="checked">');
			}
			thisRow.append(cell_zs);
			// 组六
			var cell_zl = $('<td class="border-right">');
			if(thisCode[0] != thisCode[1] && thisCode[1] != thisCode[2] && thisCode[0] != thisCode[2]) {
				cell_zl.append('<i class="checked">');
			}
			thisRow.append(cell_zl);
			// 豹子
			var cell_bz = $('<td class="border-right">');
			if(thisCode[0] == thisCode[1] && thisCode[1] == thisCode[2]) {
				cell_bz.append('<i class="checked">');
			}
			thisRow.append(cell_bz);
			// 直选和值
			var cell_hz = $('<td class="bg-red border-right">');
			var hzVal = 0;
			$.each(thisCode, function(i, val) {
				hzVal += parseInt(val);
			});
			cell_hz.append(hzVal);
			thisRow.append(cell_hz);
		}
		
		// 格式化号码
		var formatCode = function(thisRow, code) {
			var tmpCode = code.split(',');
			if(type == 'before') {
				tmpCode.splice(3, 2);
			}
			if(type == 'middle') {
				tmpCode.splice(0, 1);
				tmpCode.splice(3, 1);
			}
			if(type == 'after') {
				tmpCode.splice(0, 2);
			}
			$.each(tmpCode, function(i, val) {
				if(DataTotalCount[i] == undefined) {
					DataTotalCount[i] = [];
				}
				if(DataTmpLost[i] == undefined) {
					DataTmpLost[i] = [];
				}
				if(DataSumLost[i] == undefined) {
					DataSumLost[i] = [];
				}
				if(DataMaxLost[i] == undefined) {
					DataMaxLost[i] = [];
				}
				for (var j = 0; j < 10; j++) {
					if(DataTotalCount[i][j] == undefined) {
						DataTotalCount[i][j] = 0;
					}
					if(DataTmpLost[i][j] == undefined) {
						DataTmpLost[i][j] = 0;
					}
					if(DataSumLost[i][j] == undefined) {
						DataSumLost[i][j] = 0;
					}
					if(DataMaxLost[i][j] == undefined) {
						DataMaxLost[i][j] = 0;
					}
					var thisCell = $('<td class="code">');
					thisCell.attr('data-idx', i);
					if(val == j) {
						DataTotalCount[i][j] += 1;
						DataTmpLost[i][j] = 0;
						thisCell.addClass('open').append('<i>' + j + '</i>');
					} else {
						DataTmpLost[i][j] += 1;
						DataSumLost[i][j] += DataTmpLost[i][j];
						if(DataTmpLost[i][j] > DataMaxLost[i][j]) {
							DataMaxLost[i][j] = DataTmpLost[i][j];
						}
						thisCell.append(DataTmpLost[i][j]);
					}
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			});
			formatType(tmpCode, thisRow);
		}
		
		// 构建中间
		var buildTbody = function(thisTbody) {
			$.each(ThisData, function(i, val) {
				var thisRow = $('<tr>');
				if((i + 1) % 5 == 0) {
					thisRow.addClass('guides');
				}
				thisRow.append('<td class="border-right">' + val.expect + '</td>');
				thisRow.append('<td class="border-right">' + val.code + '</td>');
				thisRow.append(formatCode(thisRow, val.code));
				thisTbody.append(thisRow);
			});
		}
		
		// 构建总的出现次数
		var buildTotalCount = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">出现总次数</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataTotalCount.length; i++) {
				for (var j = 0; j < DataTotalCount[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataTotalCount[i][j]);
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="6"></td>');
			thisTfoot.append(thisRow);
		}
		
		// 构建平均遗漏值
		var buildAvgLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">平均遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataSumLost.length; i++) {
				for (var j = 0; j < DataSumLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(Math.round(DataSumLost[i][j] / ThisData.length));
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="6"></td>');
			thisTfoot.append(thisRow);
		}
		
		// 构建最大遗漏值
		var buildMaxLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">最大遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataMaxLost.length; i++) {
				for (var j = 0; j < DataMaxLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataMaxLost[i][j]);
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="6"></td>');
			thisTfoot.append(thisRow);
		}
		
		var buildFootDoc = function(thisTfoot) {
			var row1 = 
			'<tr class="border-bottom">'+
				'<td rowspan="2" class="expect border-right">期号</td>'+
				'<td rowspan="2" class="opencode border-right">开奖号码</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td rowspan="2" class="border-right">大小形态</td>'+
				'<td rowspan="2" class="border-right">单双形态</td>'+
				'<td rowspan="2" class="border-right">组三</td>'+
				'<td rowspan="2" class="border-right">组六</td>'+
				'<td rowspan="2" class="border-right">豹子</td>'+
				'<td rowspan="2" class="border-right">直选和值</td>'+
			'</tr>';
			thisTfoot.append(row1);
			var balls = ["万位", "千位", "百位", "十位", "个位"];
			var row2 = $('<tr>');
			if(type == 'before') {
				for (var i = 0; i < 3; i++) {
					row2.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'middle') {
				for (var i = 1; i < 4; i++) {
					row2.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'after') {
				for (var i = 2; i < 5; i++) {
					row2.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			thisTfoot.append(row2);
		}
		
		// 构建底部
		var buildTfoot = function(thisTfoot) {
			buildTotalCount(thisTfoot);
			buildAvgLost(thisTfoot);
			buildMaxLost(thisTfoot);
			buildFootDoc(thisTfoot);
		}
		
		// 初始化走势
		var initTrendLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisCanvas = trendWrapper.find('canvas');
			if(thisCanvas.length > 0) {
				thisCanvas.remove();
			}
			thisCanvas = $('<canvas>');
			thisCanvas.attr('width', thisTable.width());
			thisCanvas.attr('height', thisTable.height());
			trendWrapper.append(thisCanvas);
			// 构建走势图
			var context = thisCanvas[0].getContext('2d');
			context.lineWidth = 1.5;
			context.strokeStyle = '#26b69d';
			for (var i = 0; i < 5; i++) {
				var paths = thisTable.find('[data-idx="' + i + '"].open');
				$.each(paths, function(j) {
					var x = $(this).position().left;
					var y = $(this).position().top;
					if(j == 0) {
						context.moveTo(x + 9, y + 15);
					} else {
						context.lineTo(x + 9, y + 15);
					}
				});
				context.stroke();
			}
		}
		
		// 初始化遗漏
		var initLostLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisTbody = thisTable.find('tbody');
			var thisRows = thisTbody.find('tr');
			for (var i = 0; i < 50; i++) {
				var cells = [];
				$.each(thisRows, function(j, val) {
					cells.push($(this).find('td.code').eq(i));
				});
				for (var j = cells.length; j > 0; j--) {
					if($(cells[j - 1]).hasClass('open')) {
						break;
					} else {
						$(cells[j - 1]).addClass('lost');
					}
				}
			}
		}
		
		var buildTable = function(data) {
			ThisData = data;
			DataTotalCount = [];
			DataTmpLost = [];
			DataSumLost = [];
			DataMaxLost = [];
			
			var thisTable = $('<table>');
			var thisThead = $('<thead>');
			var thisTbody = $('<tbody>');
			var thisTfoot = $('<tfoot>');
			// 构建头部
			buildThead(thisThead);
			thisTable.append(thisThead);
			// 构建中间
			buildTbody(thisTbody);
			thisTable.append(thisTbody);
			// 构建底部
			buildTfoot(thisTfoot);
			thisTable.append(thisTfoot);
			trendWrapper.html(thisTable);
			// 工具栏
			inputGuides.trigger('change');
			inputLostNum.trigger('change');
			inputLostLine.trigger('change');
			inputTrend.trigger('change');
		}
		
		trendControl.find('.time > a').click(function() {
			var command = $(this).attr('data-command');
			var params = {lotteryId: Lottery.id, command: command};
			TrendData.load(params, trendWrapper, function(data) {
				if(data && data.length > 0) {
					buildTable(data);
				}
			});
		}).eq(0).trigger('click');
	}
	
	var initErXing = function(type) {
		
		var initControl = function() {
			var innerHtml = 
			'<div class="tools">'+
				'<label><input name="guides" type="checkbox" checked="checked">辅助线</label>'+
				'<label><input name="lostNum" type="checkbox" checked="checked">遗漏</label>'+
				'<label><input name="lostLine" type="checkbox">遗漏条</label>'+
				'<label><input name="trend" type="checkbox" checked="checked">走势</label>'+
			'</div>'+
			'<div class="time">'+
				'<a data-command="latest-30">最近30期</a>'+
				'<a data-command="latest-50">最近50期</a>'+
				'<a data-command="date-today">今日数据</a>'+
				'<a data-command="date-yesterday">昨日数据</a>'+
				'<a data-command="date-before-yesterday">前日数据</a>'+
			'</div>';
			trendControl.html(innerHtml);
		}
		
		initControl();
		
		var inputGuides = trendControl.find('input[name="guides"]');
		var inputLostNum = trendControl.find('input[name="lostNum"]');
		var inputLostLine = trendControl.find('input[name="lostLine"]');
		var inputTrend = trendControl.find('input[name="trend"]');
		
		inputGuides.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-guides');
			} else {
				thisTable.addClass('hide-guides');
			}
		});
		
		inputLostNum.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				thisTable.removeClass('hide-lost');
			} else {
				thisTable.addClass('hide-lost');
			}
		});
		
		inputLostLine.change(function() {
			var thisTable = trendWrapper.find('table');
			if($(this).is(':checked')) {
				initLostLine();
			} else {
				thisTable.find('tbody > tr > td').removeClass('lost');
			}
		});
		
		inputTrend.change(function() {
			var thisCanvas = trendWrapper.find('canvas');
			if($(this).is(':checked')) {
				initTrendLine();
			} else {
				thisCanvas.hide();
			}
		});
		
		var buildThead = function(thisThead) {
			var balls = ["万位", "千位", "百位", "十位", "个位"];
			var row1 = $('<tr>');
			row1.append('<td rowspan="2" class="expect border-right">期号</td>');
			row1.append('<td rowspan="2" class="opencode border-right">开奖号码</td>');
			if(type == 'before') {
				for (var i = 0; i < 2; i++) {
					row1.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'after') {
				for (var i = 3; i < 5; i++) {
					row1.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			row1.append('<td rowspan="2" class="border-right">对子</td>');
			row1.append('<td rowspan="2" class="border-right">大小形态</td>');
			row1.append('<td rowspan="2" class="border-right">单双形态</td>');
			row1.append('<td rowspan="2" class="border-right">直选和值</td>');
			thisThead.append(row1);
			var row2 = 
			'<tr class="border-bottom">'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
			'</tr>';
			thisThead.append(row2);
		}
		
		var ThisData = []; // 号码数据
		var DataTotalCount = []; // 出现总次数
		var DataTmpLost = []; // 当前累计遗漏
		var DataSumLost = []; // 总的遗漏
		var DataMaxLost = []; // 最大遗漏
		
		// 格式化类型
		var formatType = function(thisCode, thisRow) {
			// 对子
			var cell_dz = $('<td class="border-right">');
			if(thisCode[0] == thisCode[1]) {
				cell_dz.append('<i class="checked">');
			}
			thisRow.append(cell_dz);
			// 大小
			var cell_dx = $('<td class="bg-blue border-right">');
			$.each(thisCode, function(i, val) {
				cell_dx.append(val < 5 ? '小' : '大');
			});
			thisRow.append(cell_dx);
			// 单双
			var cell_ds = $('<td class="bg-green border-right">');
			$.each(thisCode, function(i, val) {
				if($.inArray(val, ['0','2','4','6','8']) != -1) {
					cell_ds.append('双');
				} else {
					cell_ds.append('单');
				}
			});
			thisRow.append(cell_ds);
			// 直选和值
			var cell_hz = $('<td class="bg-red border-right">');
			var hzVal = 0;
			$.each(thisCode, function(i, val) {
				hzVal += parseInt(val);
			});
			cell_hz.append(hzVal);
			thisRow.append(cell_hz);
		}
		
		// 格式化号码
		var formatCode = function(thisRow, code) {
			var tmpCode = code.split(',');
			if(type == 'before') {
				tmpCode.splice(2, 3);
			}
			if(type == 'after') {
				tmpCode.splice(0, 3);
			}
			$.each(tmpCode, function(i, val) {
				if(DataTotalCount[i] == undefined) {
					DataTotalCount[i] = [];
				}
				if(DataTmpLost[i] == undefined) {
					DataTmpLost[i] = [];
				}
				if(DataSumLost[i] == undefined) {
					DataSumLost[i] = [];
				}
				if(DataMaxLost[i] == undefined) {
					DataMaxLost[i] = [];
				}
				for (var j = 0; j < 10; j++) {
					if(DataTotalCount[i][j] == undefined) {
						DataTotalCount[i][j] = 0;
					}
					if(DataTmpLost[i][j] == undefined) {
						DataTmpLost[i][j] = 0;
					}
					if(DataSumLost[i][j] == undefined) {
						DataSumLost[i][j] = 0;
					}
					if(DataMaxLost[i][j] == undefined) {
						DataMaxLost[i][j] = 0;
					}
					var thisCell = $('<td class="code">');
					thisCell.attr('data-idx', i);
					if(val == j) {
						DataTotalCount[i][j] += 1;
						DataTmpLost[i][j] = 0;
						thisCell.addClass('open').append('<i>' + j + '</i>');
					} else {
						DataTmpLost[i][j] += 1;
						DataSumLost[i][j] += DataTmpLost[i][j];
						if(DataTmpLost[i][j] > DataMaxLost[i][j]) {
							DataMaxLost[i][j] = DataTmpLost[i][j];
						}
						thisCell.append(DataTmpLost[i][j]);
					}
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			});
			formatType(tmpCode, thisRow);
		}
		
		// 构建中间
		var buildTbody = function(thisTbody) {
			$.each(ThisData, function(i, val) {
				var thisRow = $('<tr>');
				if((i + 1) % 5 == 0) {
					thisRow.addClass('guides');
				}
				thisRow.append('<td class="border-right">' + val.expect + '</td>');
				thisRow.append('<td class="border-right">' + val.code + '</td>');
				thisRow.append(formatCode(thisRow, val.code));
				thisTbody.append(thisRow);
			});
		}
		
		// 构建总的出现次数
		var buildTotalCount = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">出现总次数</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataTotalCount.length; i++) {
				for (var j = 0; j < DataTotalCount[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataTotalCount[i][j]);
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="4"></td>');
			thisTfoot.append(thisRow);
		}
		
		// 构建平均遗漏值
		var buildAvgLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">平均遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataSumLost.length; i++) {
				for (var j = 0; j < DataSumLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(Math.round(DataSumLost[i][j] / ThisData.length));
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="4"></td>');
			thisTfoot.append(thisRow);
		}
		
		// 构建最大遗漏值
		var buildMaxLost = function(thisTfoot) {
			var thisRow = $('<tr>');
			thisRow.append('<td class="border-right">最大遗漏值</td>');
			thisRow.append('<td class="border-right"></td>');
			for (var i = 0; i < DataMaxLost.length; i++) {
				for (var j = 0; j < DataMaxLost[i].length; j++) {
					var thisCell = $('<td>');
					thisCell.html(DataMaxLost[i][j]);
					if(j == 9) {
						thisCell.addClass('border-right');
					}
					thisRow.append(thisCell);
				}
			}
			thisRow.append('<td colspan="4"></td>');
			thisTfoot.append(thisRow);
		}
		
		var buildFootDoc = function(thisTfoot) {
			var row1 = 
			'<tr class="border-bottom">'+
				'<td rowspan="2" class="expect border-right">期号</td>'+
				'<td rowspan="2" class="opencode border-right">开奖号码</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td class="code">0</td>'+
				'<td class="code">1</td>'+
				'<td class="code">2</td>'+
				'<td class="code">3</td>'+
				'<td class="code">4</td>'+
				'<td class="code">5</td>'+
				'<td class="code">6</td>'+
				'<td class="code">7</td>'+
				'<td class="code">8</td>'+
				'<td class="code border-right">9</td>'+
				'<td rowspan="2" class="border-right">对子</td>'+
				'<td rowspan="2" class="border-right">大小形态</td>'+
				'<td rowspan="2" class="border-right">单双形态</td>'+
				'<td rowspan="2" class="border-right">直选和值</td>'+
			'</tr>';
			thisTfoot.append(row1);
			var balls = ["万位", "千位", "百位", "十位", "个位"];
			var row2 = $('<tr>');
			if(type == 'before') {
				for (var i = 0; i < 2; i++) {
					row2.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			if(type == 'after') {
				for (var i = 3; i < 5; i++) {
					row2.append('<td colspan="10" class="border-right">' + balls[i] + '</td>');
				}
			}
			thisTfoot.append(row2);
		}
		
		// 构建底部
		var buildTfoot = function(thisTfoot) {
			buildTotalCount(thisTfoot);
			buildAvgLost(thisTfoot);
			buildMaxLost(thisTfoot);
			buildFootDoc(thisTfoot);
		}
		
		// 初始化走势
		var initTrendLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisCanvas = trendWrapper.find('canvas');
			if(thisCanvas.length > 0) {
				thisCanvas.remove();
			}
			thisCanvas = $('<canvas>');
			thisCanvas.attr('width', thisTable.width());
			thisCanvas.attr('height', thisTable.height());
			trendWrapper.append(thisCanvas);
			// 构建走势图
			var context = thisCanvas[0].getContext('2d');
			context.lineWidth = 1.5;
			context.strokeStyle = '#26b69d';
			for (var i = 0; i < 5; i++) {
				var paths = thisTable.find('[data-idx="' + i + '"].open');
				$.each(paths, function(j) {
					var x = $(this).position().left;
					var y = $(this).position().top;
					if(j == 0) {
						context.moveTo(x + 9, y + 15);
					} else {
						context.lineTo(x + 9, y + 15);
					}
				});
				context.stroke();
			}
		}
		
		// 初始化遗漏
		var initLostLine = function() {
			var thisTable = trendWrapper.find('table');
			var thisTbody = thisTable.find('tbody');
			var thisRows = thisTbody.find('tr');
			for (var i = 0; i < 50; i++) {
				var cells = [];
				$.each(thisRows, function(j, val) {
					cells.push($(this).find('td.code').eq(i));
				});
				for (var j = cells.length; j > 0; j--) {
					if($(cells[j - 1]).hasClass('open')) {
						break;
					} else {
						$(cells[j - 1]).addClass('lost');
					}
				}
			}
		}
		
		var buildTable = function(data) {
			ThisData = data;
			DataTotalCount = [];
			DataTmpLost = [];
			DataSumLost = [];
			DataMaxLost = [];
			
			var thisTable = $('<table>');
			var thisThead = $('<thead>');
			var thisTbody = $('<tbody>');
			var thisTfoot = $('<tfoot>');
			// 构建头部
			buildThead(thisThead);
			thisTable.append(thisThead);
			// 构建中间
			buildTbody(thisTbody);
			thisTable.append(thisTbody);
			// 构建底部
			buildTfoot(thisTfoot);
			thisTable.append(thisTfoot);
			trendWrapper.html(thisTable);
			// 工具栏
			inputGuides.trigger('change');
			inputLostNum.trigger('change');
			inputLostLine.trigger('change');
			inputTrend.trigger('change');
		}
		
		trendControl.find('.time > a').click(function() {
			var command = $(this).attr('data-command');
			var params = {lotteryId: Lottery.id, command: command};
			TrendData.load(params, trendWrapper, function(data) {
				if(data && data.length > 0) {
					buildTable(data);
				}
			});
		}).eq(0).trigger('click');
	}
	
	var initMethod = function() {
		var innerHtml = 
		'<a data-type="WuXing">五星</a>'+
		'<a data-type="HouSan">后三</a>'+
		'<a data-type="ZhongSan">中三</a>'+
		'<a data-type="QianSan">前三</a>'+
		'<a data-type="HouEr">后二</a>'+
		'<a data-type="QianEr">前二</a>';
		trendMethod.html(innerHtml);
		trendMethod.find('a').click(function() {
			if(!$(this).hasClass('active')) {
				trendMethod.find('a').removeClass('active');
				$(this).addClass('active');
				var thisVal = $(this).attr('data-type');
				if(thisVal == 'WuXing') {
					initWuxing();
				}
				if(thisVal == 'HouSan') {
					initSanXing('after');
				}
				if(thisVal == 'ZhongSan') {
					initSanXing('middle');
				}
				if(thisVal == 'QianSan') {
					initSanXing('before');
				}
				if(thisVal == 'HouEr') {
					initErXing('after');
				}
				if(thisVal == 'QianEr') {
					initErXing('before');
				}
			}
		}).eq(1).trigger('click');
	}
	
	var init = function() {
		initMethod();
	}
	
	return {
		init: init
	}
	
}();