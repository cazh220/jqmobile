// JavaScript Document
$(function(){
	$('#start_time , #end_time').datepicker({
		changeMonth:true,
		changeYear:true
	});
	
	//盘点管理分页搜索
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./checkstock.php?do=search"+qs+"&currentPage="+page;
	});
	
	
	//移除
	$('#operate input[name=remove]').unbind('click').click(function(){
		var stock_info  = [] , audit;
		var stock_input = $('#list tr td input[type=checkbox]:checked');
		
		stock_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $.trim($(this).parent().siblings('.confirm_status').text());
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
		   url: "checkstock.php?do=remove",
		   data: "checkid=" + stock_info,
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
	
	//审核
	$('input[type=button][name=check_audit]').click(function(){
		var check_info  = [] , audit;	
		var check_input = $('#list tr td input[type=checkbox]:checked');
		
		check_input.each(function(){
			//已审核 及 无效采购不能审核					  
			audit = $.trim($(this).parent().siblings('.confirm_status').text());					  
			if( audit == '已审核' || audit == '无效' )
			{
				return false;	
			}
			
			check_info.push($(this).val());
		});
		
		if( audit == '已审核' || audit == '无效' )
		{
			alert('已审核或已取消，禁止审核');
			check_input.attr('checked' , '');
			check_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(check_info.length == 0)
		{
			alert('请选择你要审核的盘点数据');
			check_input.attr('checked' , '');
			return;	
		}
		
		var obj = $(this);
		
		obj.attr('disabled',true);
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "checkstock.php?do=audit",
		   data: "checkid="+check_info,
		   success: function(msg){
		
			  if(msg.status)
			  {
			    check_input.parent().siblings().removeClass('red').end().siblings('.confirm_user').text(msg.data).end().siblings('.confirm_status').text('已审核');
			  }
			  
			   alert(msg.info);

			  check_input.parent().parent().removeClass('checked');

			  $('#list_detail tr:gt(0)').remove();
			  check_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  check_input.attr('checked' , '');
			  $('#list_detail').hide();
			  
			  obj.attr('disabled',true);
		   }
		});
	});
	
	
/*	//更新判点数量
	$('input[name=update_check_data]').click(function(){
		var check_info  = [] , audit;	
		var check_input = $('#list tr td input[type=checkbox]:checked');
		
		check_input.each(function(){
			//已审核 及 无效采购不能审核					  
			audit = $(this).parent().siblings('.confirm_status').text();					  
			if( audit == '未审核' || audit == '无效' )
			{
				return false;	
			}
			
			check_info.push($(this).val());
		});
		
		if( audit == '未审核' || audit == '无效' )
		{
			alert('未审核或已取消');
			check_input.attr('checked' , '');
			check_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(check_info.length == 0)
		{
			alert('请选择你要更新的盘点数据');
			check_input.attr('checked' , '');
			return;	
		}
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "checkstock.php?do=updateStock",
		   data: "checkid="+check_info,
		   success: function(msg){
		   		alert(msg.info);
				
				if(msg.status){
					window.location.reload();
				}
		   }
		});
	});*/
	
});
