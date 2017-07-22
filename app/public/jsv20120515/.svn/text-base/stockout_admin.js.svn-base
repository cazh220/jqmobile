// JavaScript Document
$(function(){
	//时间选择	   
	$('#start_time,#end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//分页搜索
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./stockout.php?do=findRequest"+qs+"&currentPage="+page;
	});
	
	//显示提货申请详细
	$('#list td.stockout_id').click(function(){				 
		//选中时显示
		var stockoutid = $(this).siblings().find('input[name=stockoutid]').val();
		var stockoutsn= $(this).find('span').text();
		var stockouttype = $(this).find('input[name=stockouttype]').val();
		
		if(stockoutid <= 0 || stockoutsn == '' || stockouttype <= 0){
			return false;
		}
		
		$(this).siblings().find('input[name=stockoutid]').attr('checked','true');

		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stockout.php?do=StockOutDetail",
			data:"stockoutid="+stockoutid+'&stockouttype='+stockouttype+'&stockoutsn='+stockoutsn,
			success:function(msg){
				//移除表格中的数据
				 $('#list_detail tr:gt(0)').remove();

				if(!msg.status){
					alert(msg.info);
					return ;
					
				}
	
				var key , val;
				var goods_code = '';
				$.each(msg.info , function(key , val){
					goods_code += '<tr><td>'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table style="width:100%;">';
					$.each(val.size_quantity_agency , function(k,gs){ 
							goods_code += '<tr ><td style="width:40px;">'+k+'</td><td  style="width:40px;">'+gs[0]+'</td><td  style="width:120px;border:none;">'+gs[2]+'</td></tr>'; 
					});
	
					goods_code += '</table></td><td><table width=100%>';
					$.each(val.sum , function(k,gs){ 
						goods_code += '<tr><td>'+gs+'</td></tr>'; 
					});
					goods_code += '</table></td><td><table style="width:100%;color:red;" id="goods_'+key+'">';
					$.each(val.quantity, function(k,gs){ goods_code += '<tr id="'+k+'"><td>'+gs+'</td></tr>';});
					goods_code += '</table></td></tr>';
				});
			
				//有数据就显示表格
				$('#list_detail').removeAttr("style");
				$('#list_detail').css("width","100%");
				
				//将数据追加到表格中
				$(goods_code).appendTo('#list_detail');
			}
		});
		
	});
	
	//出库审核
	$('#operate input[name=submit]').click(function(){
		var act = $('input[type=hidden][name=do]').val();

		var stock_info  = [] , audit;
		var stock_input = $('#list tr td input[type=checkbox]:checked');
		
		stock_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $(this).parent().siblings('.confirm_status').text();
			  if( audit == '已审核' || audit == '无效' )
			  {
				  return false;
			  }
			  stock_info.push($(this).val());
		});

		if( audit == '已审核' || audit == '无效' )
		{
			alert('已审核或已取消，禁止审核');
			stock_input.attr('checked' , '');
			stock_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(stock_info.length == 0)
		{
			alert('请选择您要审核的出库商品');
			return;	
		}
		
		var obj = $(this);
		
		obj.attr('disabled',true);

		//执行审核操作
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockout.php?do=auditRequest",
		   data: "stockout_id="+stock_info+'&act='+act,
		   success: function(msg){
			  alert(msg.info);
			  
			  if(msg.status == true)
			  {
				  stock_input.parent().siblings().removeClass('red').find('.confirm_status').html('已审核');
				  stock_input.parent().siblings('.confirm_status').html('已审核');
			  }

			  stock_input.parent().parent().removeClass('checked');
			  stock_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  stock_input.attr('checked' , '');
			  
			  obj.attr('disabled',false);
		   }
		});	
	});
	
	//移除出库申请详细
	$('#operate input[name=remove]').unbind('click').click(function(){
		var stock_info  = [] , audit;
		var stock_input = $('#list tr td input[type=checkbox]:checked');
		
		stock_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $(this).parent().siblings('.confirm_status').text();	
			  if( audit == '已审核' || audit == '无效' )
			  {
				  return false;
			  }
			  stock_info.push($(this).val());
		});

		if( audit == '已审核' || audit == '无效' )
		{
			alert('已审核或已取消，禁止移除');
			stock_input.parent().parent().removeClass('checked');
			stock_input.attr('checked' , '');
			return;
		}
		//alert(audit);return ;
		if(stock_info.length == 0)
		{
			alert('请选择您要移除的出库商品');
			return;	
		}
		
		if(!confirm('确认删除？'))
		{
			return;
		}
		
		var obj = $(this);
		
		obj.attr('disabled',true);

		//执行删除操作
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockout.php?do=remove",
		   data: "stockout_id=" + stock_info,
		   success: function(msg){
			  if(msg.status)
			  {
				  stock_input.parent().siblings().removeClass('red').end().siblings('.confirm_user').text(msg.data).end().siblings('.confirm_status').text('无效');
			  }
			 
			  alert(msg.info);
			  
			  stock_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  stock_input.parent().parent().removeClass('checked');
			  stock_input.attr('checked' , '');
			  
			  obj.attr('disabled',false);
		   }
		});	
	});
	
	//出库导出
	$('#operate input[name=export]').click(function(){
		var stock_info  = [] ;
		var stock_input = $('#list tr td input[type=checkbox]:checked');
		
		stock_input.each(function(){
			  stock_info.push($(this).val());
		});

		if(stock_info.length == 0) {
			alert('请选择您要导出的提货出库单!');
			return;	
		}
		
		window.location.href = 'stockout.php?do=ExportStockOut&stockout_id='+stock_info;
	});
	
	
	$('#list span.rmtr').hover(function(){
		$(this).css('backgroundColor', '#f00');								  
	}, function(){
		$(this).css('backgroundColor', '#f3f9fa');
	});
	
	$('#list span.rmtr').click(function(){
		tr = $(this).parent().parent();
		tr.animate({opacity:0} , 200);
		tr.queue(function(){
			$(this).remove();
		});
	});
	
	//添加出库申请
	$('#new_add').click(function(){
		var parm = $('input[type=hidden][name=user]').val();
		window.location = 'stockout.php?do=request&act='+parm;
	});
	
	//添加出库
	$('#add_out_stock').click(function(){
		var parm = $('input[type=hidden][name=user]').val();
		window.location = 'stockout.php?user='+parm;
	});
});