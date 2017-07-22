// JavaScript Document
$(function(){
	//搜索层时间选择
	$('#starttime , #endtime , #start_time , #end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//采购管理分页搜索
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./purchase.php?do=searchPurchase"+qs+"&currentPage="+page;
	});

	//显示采购详细		
	$('.batch_code').click(function(){
			var batchid = $(this).prev().find('input[type=checkbox]').val();
			if(batchid == ''){
				return ;
			}

			$.ajax({
				   type: "POST",
				   dataType: "json",
				   url: "purchase.php?do=batchDetail",
				   data: "batchid="+batchid,
				   success: function(msg){
					    $('#list_detail tr:gt(0)').remove();
	
						//返回出错
						if(!msg.status){
							alert(msg.info);return;
						}
	
						var key , val;
						var goods_code = '';
						$.each(msg.info , function(key , val){
							goods_code += '<tr><td>'+key+'</td><td>'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table><tr>';
							$.each(val.quantity , function(k,gs){ goods_code += '<td class="size_info"><span>'+k+'</span><br /><input type="text" name="'+k+'" value="'+gs+'" readonly="readonly"/></td>'; });
								goods_code += '</tr></table></td><td>';
								goods_code += val.total+'</td><td>';
								$.each(val.inquantity , function(k,iq){ goods_code += '<div>'+iq.in_num+'</div>'; });
								goods_code += '</td><td>';
								$.each(val.inquantity, function(k,diff){ goods_code += '<div>'+diff.diff_num+'</div>'; });
								goods_code += '</td></tr>';
						});
			
						//有数据就显示表格
						$('#list_detail').removeAttr("style"); 
						
						//将数据追加到表格中
						$(goods_code).appendTo('#list_detail');
				   }   
				});
	});
	
	//审核采购单
	$('input[name=submit]').click(function(){
		var batch_info  = [] , audit;	
		var batch_input = $('#list tr td input[type=checkbox]:checked');

		batch_input.each(function(){
			//已审核 及 无效采购不能审核					  
			audit = $(this).parent().siblings('.confirm_status').text();					  
			if( audit == '已审核' || audit == '无效' )
			{
				return false;	
			}
			batch_info.push($(this).val());
		});
		
		if( audit == '已审核' || audit == '无效' )
		{
			alert('已审核或已取消采购，禁止审核');
			batch_input.attr('checked' , '');
			batch_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(batch_info.length == 0)
		{
			alert('请选择你要审核的采购单');
			batch_input.attr('checked' , '');
			return;	
		}
		
		var obj = $(this);
		
		obj.attr('disabled',true);
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "purchase.php?do=audit",
		   data: "batchid="+batch_info,
		   success: function(msg){
			  
			  if(msg.status){
				 batch_input.parent().siblings().removeClass('red').end().siblings('.confirm_status').text('已审核').end().siblings('.confirm_user').text(msg.data);  
			  }
			  
			  alert(msg.info);

			  batch_input.parent().find('input').removeClass('checked');

			  $('#list_detail tr:gt(0)').remove();
			  batch_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  batch_input.attr('checked' , '');
			  $('#list_detail').hide();
			  
			  obj.attr('disabled',false);
		   }
		});
	});
	
	//移除采购详细
	$('#operate input[name=remove]').unbind('click').click(function(){
		var batch_info  = [] , audit;
		var batch_input = $('#list tr td input[type=checkbox]:checked');
		
		batch_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $(this).parent().siblings('.confirm_status').text();	
			  if( audit == '已审核' || audit == '无效' )
			  {
				  return false;
			  }
			  batch_info.push($(this).val());
		});

		if( audit == '已审核' || audit == '无效' )
		{
			alert('已审核或已取消采购，禁止移除');
			batch_input.attr('checked' , '');
			batch_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(batch_info.length == 0)
		{
			alert('请选择您要移除的采购单');return;	
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
		   url: "purchase.php?do=DelPurchase",
		   data: "batchid=" + batch_info,
		   success: function(msg){
			  if(msg.status)
			  {
				  batch_input.parent().siblings().removeClass('red').end().siblings('.confirm_user').text(msg.data).end().siblings('.confirm_status').text('无效');
			  }

			  alert(msg.info);
			  
			  batch_input.parent().find('input').removeClass('checked');
			  
			  batch_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  batch_input.attr('checked' , '');
			  
			  obj.attr('disabled',false);
		   }
		});	
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
	
	//选择批次号
	$('input[name=select_batch]').click(function(){
		var x    = $(window).width();
		var y    = $(window).height();
		
		$('#db').css({'z-index':50, 'position':'absolute', 'top':0, 'left':0, width:x, height:y, 'backgroundColor':'#000', opacity:0.2}).show();
		$('#batch_box').css({'z-index':100, 'position':'absolute', 'top':'30px', 'left':'30px'}).show();
	});
	
	//确认选择的批次号,填充查询条件
/*	$('input[name=selectbatch]').click(function(){
		var batch = $('select[name=batch_box] option:selected').text();
		if(batch == '请选择')
		{
			alert('请选择批次号');return;	
		}
		
		//$('#db, #batch_box').hide();
		//$('input[name=batchid]').val(batch);
	});*/
	
	//搜索层搜索
	$('input[name=search_batch]').click(function(){
		var su = $('select[name=supperliers]').val();
		var starttime = $('input[name=starttime]').val();
		var endtime   = $('input[name=endtime]').val();
		var status    = $('select[name=audut]').val();
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "purchase.php?do=SearchBatch",
		   data: "supperlier=" + su + "&starttime=" + starttime + "&endtime=" + endtime + "&status=" + status,
		   success: function(msg){
			 	if( !msg.status )
				{
					alert(msg.info);
					$('select[name=batch_box]').text('');
					return;	
				}

				var option = '<option value=0>请选择</option>' , i , v;
				
				$.each(msg.data , function(i , v){
					option += '<option value="' + v.batch_id + '">' + v.batch_code + '</option>';						   
				});
				
				$('select[name=batch_box]').html(option);
				$('select[name=batch_box]').css({'width':'130px'}).show();
		   }
		});
	});
	
	//显示批次信息
	$('select[name=batch_box]').change(function(){		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "purchase.php?do=GetBatchDec",
		   data: "batch=" + $(this).val(),
		   success: function(msg){
			 	if( !msg.status )
				{
					alert(msg.info);return;	
				}
				
				var dec = msg.data ? msg.data : '没有备注信息';
				$('td.dec').text(dec);
		   }
		});
	});
	
	//确认选择批次
/*	$('input[name=chose_batch]').click(function(){
		var sel = $('select[name=batch_box]');
		var batch_code = sel.find('option:selected').text();
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "purchase.php?do=searchCondition",
		   data: "batchcode=" + batch_code,
		   success: function(msg){
			 	if( !msg.status )
				{
					alert(msg.info);return;	
				}

				$('input[name=purchaseid]').val(msg.data.purchase_id);
				$('select[name=supperliersid]').get(0).selectedIndex = msg.data.supplier_id;
				$('select[name=audut_status]').get(0).selectedIndex = msg.data.confirm_status;
				$('select[name=adminid]').get(0).selectedIndex = msg.data.create_user_id ;
		   }
		});
		
		$('input[name=batchid]').val( sel.find('option:selected').text() );
		$('input[name=batch]').val( sel.val() );
		$('#batch_box , #db').hide();
	});*/
	
	//添加新申请
	$('#new_add').click(function(){
		window.location = 'purchase.php';
	});
	
});

/**
 * 选择批次
 */
function choseBatch(val) {
	var sel = $('select[name=batch_box]');
	if(val.length > 4){
		var batch_code = val.toUpperCase();
	}else{
		
		var batch_code = sel.find('option:selected').text();
	}

	if(batch_code == '') return false;
	
	$.ajax({
	   type: "POST",
	   dataType: "json",
	   url: "purchase.php?do=searchCondition",
	   data: "batchcode=" + batch_code,
	   success: function(msg){
			if( !msg.status )
			{
				alert(msg.info);return;	
			}
			
			$('input[name=batch]').val(msg.data.batch_id);
			$('input[name=batchid]').val(msg.data.batch_code);
			$('input[name=purchaseid]').val(msg.data.purchase_id);
			$('select[name=supperliersid]').get(0).selectedIndex = msg.data.supplier_id;
			$('select[name=audut_status]').get(0).selectedIndex = msg.data.confirm_status;
			$('select[name=adminid]').get(0).selectedIndex = msg.data.create_user_id ;
	   }
	});

	$('#batch_box , #db').hide();

}
