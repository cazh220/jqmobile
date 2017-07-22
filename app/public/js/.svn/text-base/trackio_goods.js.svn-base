// JavaScript Document
$(function(){
	//时间选择	   
	$('#start_time,#end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
/*	
    $('input[id=start_time]').blur(function(){
		var start_time = $('input[id=start_time]').val();alert(start_time);
	
		$.ajax({
                                      type: "POST",
                                      url: "trackio_goods.php?do=CheckTime",
                                      data: "time="+start_time,
									  dataType:"json",
                                      success: function(msg){
										  alert(msg.info);
										  return false;
                                     }
                                    });
									
	});
*/
	$('#view_union').click(function(){
									var goods_sn = $('#goods_sn').val();
									var start_time = $('#start_time').val();
									var end_time = $('#end_time').val();
									if(goods_sn=='' || start_time=='' || end_time=='')
									{
										alert('查询条件不完整！');
										return false;
									}
									
									$.ajax({
										type: "POST",
										url: "trackio_goods.php?do=CheckTime",
										data: "start_time="+start_time+"&end_time="+end_time,
										dataType: "json",
										success:function(msg){
											if(msg.status==0)
											{
												alert(msg.info);
												return false;
											}
											else if(msg.status==1)
											{
											    alert(msg.info);
												return false;
											}
											else
											{
												window.location.href = 'trackio_goods.php?do=Viewlog&goods_sn='+goods_sn+'&start_time='+start_time+'&end_time='+end_time;
											}
										}							
									});									
									});
	//导出日志
	$('#export_log').click(function(){
		                            window.location.href = 'trackio_goods.php?do=Viewlog&act=export_log';
	
	                               });

	$('#in_out').click(function(){
                                    var goods_sn = $('#goods_sn').val();
									var start_time = $('#start_time').val();
									var end_time = $('#end_time').val();
									if(start_time && end_time)
		                            {
                                         window.location.href = 'trackio_goods.php?do=Inout&goods_sn='+goods_sn+'&start_time='+start_time+'&end_time='+end_time;
									}
									else{
									     alert('请选择时间段');
									}
									
	});

	//导出出入库统计
	$('#export_inout').click(function(){
		                            window.location.href = 'trackio_goods.php?do=Inout&act=export_inout';
	});

});

//导出入库
function export_ii(){
    window.location.href = 'trackio_goods.php?do=searchInfo&act=export_ii';
}

//导出出库
function export_io(){
    window.location.href = 'trackio_goods.php?do=searchInfo&act=export_io';
}







