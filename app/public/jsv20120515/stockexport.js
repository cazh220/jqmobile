$(function(){
	//时间选择	   
	$('#timebegin,#timeend').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//鼠标悬浮更换背景颜色
	$('.list_main').hover(function(){
		$(this).css({background:"#F3F9CA"});
	},function(){
		$(this).css({background:"#FFF"});
	});
	
	//请输入货号
	$('.stock_export_goodssn').click(function(){
		$('input[name=export_goods_sn]').val('');
		$('input[name=export_goods_sn]').blur(function(){
			if($('input[name=export_goods_sn]').val() == "")$(this).val("请输入货号");
		});
	});

	//导出查询提交
	$('.export_submit_search').click(function(){
		var begintime = $('#timebegin').val();
		if(begintime.match(/\d{4}-\d{2}-\d{2}/)){
			var beginhour = $('select[name=export_hourbegin]').val();
			var beginmin = $('select[name=export_minbegin]').val();
			begintime += ' ' + beginhour + ":" + beginmin + ":00"
		}else{
			begintime = '';
		}
		var endtime = $('#timeend').val();
		if(endtime.match(/\d{4}-\d{2}-\d{2}/)){
			var endhour = $('select[name=export_hourend]').val();
			var endmin = $('select[name=export_minend]').val();			
			endtime += ' ' + endhour + ":" + endmin + ":00"
		}else{
			endtime = '';
		}
		var goods_sn = $('input[name=export_goods_sn]').val();
		if(!goods_sn.match(/[A-Za-z0-9]{5,8}/)){
			goods_sn = "";
		}
		var export_full_flag = $('input[name=export_full_info]').attr('checked') ? 1 : 0;
		var agency_id = $('select[name=export_channel]').val();
		params = "&start_time="+begintime+"&end_time="+endtime+"&goods_sn="+goods_sn+"&agency_id="+agency_id+"&export_full="+export_full_flag;
		location.href = "stock_transfer.php?do=Showtranserlogs" + params;
	});
	
	//查询导出
	$('.export_submit').click(function(){
		var begintime = $('#timebegin').val();
		if(begintime.match(/\d{4}-\d{2}-\d{2}/)){
			var beginhour = $('select[name=export_hourbegin]').val();
			var beginmin = $('select[name=export_minbegin]').val();
			begintime += ' ' + beginhour + ":" + beginmin + ":00"
		}else{
			begintime = '';
		}
		var endtime = $('#timeend').val();
		if(endtime.match(/\d{4}-\d{2}-\d{2}/)){
			var endhour = $('select[name=export_hourend]').val();
			var endmin = $('select[name=export_minend]').val();			
			endtime += ' ' + endhour + ":" + endmin + ":00"
		}else{
			endtime = '';
		}
		var goods_sn = $('input[name=export_goods_sn]').val();
		if(!goods_sn.match(/[A-Za-z0-9]{5,8}/)){
			goods_sn = "";
		}
		var export_full_flag = $('input[name=export_full_info]').attr('checked') ? 1 : 0;
		var agency_id = $('select[name=export_channel]').val();
		params = "&start_time="+begintime+"&end_time="+endtime+"&goods_sn="+goods_sn+"&agency_id="+agency_id+"&export_full="+export_full_flag;
		location.href = "stock_transfer.php?do=showtranserlogs&action=export" + params;
	});
});