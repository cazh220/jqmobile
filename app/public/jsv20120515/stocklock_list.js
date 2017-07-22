// JavaScript Document
$(function(){

	//显示提货申请详细
	$('#list td.goods_id').click(function(){	
														 
		//选中时显示
		var goodsid = $(this).html();
		
		if(goodsid <= 0){
			return false;
		}
		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stocklock.php?do=getstocklockinfo",
			data:"goods_id="+goodsid,
			success:function(msg){
				//移除表格中的数据
				 $('#list_detail_distribution tr:gt(0)').remove();
				 $('#list_detail_stock tr:gt(0)').remove();
				
				if(!msg.status){
					alert(msg.info);
					return ;
					
				}
	
				var key , val;
				var goods_code = '';
				var flag_nolock=1;
				if(msg.data.sstock[0]){
					$.each(msg.data.sstock , function(key , val){
						goods_code +='<tr><td>'+val.stock_out_type+'</td><td>'+val.stock_out_sn+'</td><td>'
							+val.create_user+'</td><td>'+val.confirm_user+'</td><td>'+val.quantity+'</td><td>'
							+val.create_time+'</td><td>'+val.confirm_time+'</td><td class="" onclick="releaseStock('
							+val.stock_out_sn+','+val.stock_out_details_id+');">释放</td><td class="" onclick="releaseAllStock('
							+val.stock_out_sn+');">释放此单所有占用</td></tr>';
					});
				
					//有数据就显示表格
					$('#list_detail_stock').removeAttr("style");
					$('#list_detail_stock').css("width","100%");
					
					//将数据追加到表格中
					$(goods_code).appendTo('#list_detail_stock');
					flag_nolock=0;
						
				}else{
					$('#list_detail_stock').css("display","none");
				};
				goods_code='';
				if(msg.data.dstock[0]){
					$.each(msg.data.dstock , function(key , val){
						goods_code +='<tr><td>'+val.order_sn+'</td><td>'+val.consignee+'</td><td>'
							+val.goods_number+'</td><td>'+val.order_status+'</td><td>'+val.pay_status+'</td><td>'
							+val.add_time+'</td><td class="partner_id" onclick="cancelOrder('
							+val.partner_id+','+val.order_sn+');">取消</td></tr>';
					});
				
					//有数据就显示表格
					$('#list_detail_distribution').removeAttr("style");
					$('#list_detail_distribution').css("width","100%");
					
					//将数据追加到表格中
					$(goods_code).appendTo('#list_detail_distribution');
					flag_nolock=0;
				}else{
					$('#list_detail_distribution').css("display","none");
				};
				if(flag_nolock)alert('此产品没有占用库存');
			}
		});

		
	});
});
/**
 * 订单状态更新
 */
function cancelOrder(partner_id,order_sn) {
	alert('此功能开发中...');
	return;
	$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'stocklock.php?do=cancelOrder',
			   data:'partner_id=' + partner_id + '&order_sn=' + order_sn,
			   success:function(msg){
						alert(msg.message);
				}
	});
	
}

/**
 * 释放库存
 */
function releaseStock(stock_out_sn,stock_out_details_id) {
	var conf = confirm('谨慎操作，继续操作请按确认');
	if(!conf)return;
	$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'stocklock.php?do=ReleaseStock',
			   data:'stock_out_sn=' + stock_out_sn + '&stock_out_details_id=' + stock_out_details_id,
			   success:function(msg){
						alert(msg.message);
				}
	});

}

/**
 * 释放库存
 */
function releaseAllStock(stock_out_sn) {
	var conf = confirm('谨慎操作，继续操作请按确认');
	if(!conf)return;

	$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'stocklock.php?do=ReleaseAllStock&action=getinfo',
			   data:'stock_out_sn=' + stock_out_sn ,
			   success:function(msg){
						if(!msg.status){
							alert(msg.message);
							return;
						}
						var getinfo = function(info){
							var st = "";
							for(i in info){
								st += info[i].goods_sn + ":" + info[i].size + ":" + info[i].quantity + "\n";
							}
							return st
						};	
						var conf = confirm("按确认释放" + "\n" + getinfo(msg.info));
						if(!conf)return;
						$.ajax({
								   type:'POST',
								   dataType: "json",
								   url:'stocklock.php?do=ReleaseAllStock&action=release',
								   data:'stock_out_sn=' + stock_out_sn ,
								   success:function(msg){
								   			alert(msg.message);
								   }
						});
			   }
	});

}
// $(function(){
// 	
	// $('#list_detail_distribution td.partner_id').click(function(){	
// 														 
		// //选中时显示
		// var partnerid = $(this).html();
		// alert('partnerid');
	// });
// 
// });