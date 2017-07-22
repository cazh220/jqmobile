// JavaScript Document
$(function(){
	//搜索层时间选择
	$('#starttime , #endtime , #start_time , #end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//改变每页条数
	$('.page input[name=input_page]').blur(function(){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		window.location.href = "./stockin.php?do=searchList"+qs+"&pageSize="+page;
	});
	
	//选择分页查询
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./stockin.php?do=searchList"+qs+"&currentPage="+page;
	});
		

	//添加新申请
	$('input.new_add').click(function(){
		var parm = $('input[type=hidden][name=user]').val();
		window.location = 'stockin.php?act=add&user=' + parm;
	});
	
	
	/********************* 供应商产品入库 start *********************/
	
	//选择批次号
	$('input[name=select_batch]').click(function(){
		var x    = $(window).width();
		var y    = $(window).height();
		
		$('#db').css({'z-index':50, 'position':'absolute', 'top':0, 'left':0, width:x, height:y, 'backgroundColor':'#000', opacity:0.2}).show();
		$('#batch_box').css({'z-index':100, 'position':'absolute', 'top':'30px', 'left':'30px'}).show();
	});
	
	//层 搜索批次
	$('input[name=search_batch]').click(function(){
		var su = $('select[name=supperliers]').val();
		var starttime = $('input[name=starttime]').val();
		var endtime   = $('input[name=endtime]').val();
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "purchase.php?do=SearchBatch",
		   data: "supperlier=" + su + "&starttime=" + starttime + "&endtime=" + endtime,
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
	$('input[name=chose_batch]').click(function(){
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

				$('select[name=supperliersid]').get(0).selectedIndex = msg.data.supplier_id;
				//$('select[name=audut_status]').get(0).selectedIndex = msg.data.confirm_status;
		   }
		});
		
		$('input[name=batch]').val( sel.find('option:selected').text() );
		$('input[name=batchid]').val( sel.val() );
		$('#batch_box , #db').hide();
	});
	
	/********************* 供应商产品入库 end *********************/
	
	
	
	/********************* 员工提货出库入库 start *********************/
	//弹出搜索员工提货单号层
	$('input[name=select_stock]').click(function(){
		var x    = $(window).width();
		var y    = $(window).height();
		$('#db').css({'z-index':50, 'position':'absolute', 'top':0, 'left':0, width:x, height:y, 'backgroundColor':'#000', opacity:0.2}).show();
		$('#stock_box').css({'z-index':100, 'position':'absolute', 'top':'30px', 'left':'30px'}).show();											 
	});
	
	//搜索员工提货单号
	$('input[name=search_stock]').click(function(){
		var su        = $('select[name=supperliers]').val();
		var outperson = $('select[name=outperson ]').val();
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
					alert(msg.info);return;	
				}

				var option = '<option value=0>请选择</option>' , i , v;
				
				$.each(msg.data , function(i , v){
					option += '<option value="' + v.batch_id + '">' + v.batch_code + '</option>';					   
				});
				
				$('select[name=batch_box]').html(option);
		   }
		});
	});
	
	/********************* 员工提货出库入库 end *********************/
	
	
	//入库单打印
	$('#operate input[name=print]').click(function(){
		var ar_stockid = [];
		
		$('#list tr td input[type=checkbox]:checked').each(function(){
			  ar_stockid.push($(this).val());
		});	
		
		if(ar_stockid.length == 0) {
			alert('请选择您要打印的入库单');
			return;	
		}
		
		window.open("./stockin.php?do=printStockin&stockin_id=" + ar_stockid);
		
	});
	
	//入库单打印
	$('#operate input[name=export]').click(function(){
		var ar_stockid = [];
		
		$('#list tr td input[type=checkbox]:checked').each(function(){
			  ar_stockid.push($(this).val());
		});	
		
		if(ar_stockid.length == 0) {
			alert('请选择您要导出的入库单');
			return;	
		}
		
		window.location.href = "./stockin.php?do=exportStockin&stockin_id=" + ar_stockid;
		
	});
	
	//移除入库
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
			alert('请选择您要移除的出库商品');return;	
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
		   url: "stockin.php?do=remove",
		   data: "stockin_id=" + stock_info,
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
});

//显示入库详细
function showDetail(stock_id){
	if(stock_id == ''){
		return false;	
	}
	
	var act = $('#main_up input[type=hidden][name=user]').val();
	
	$.ajax({
		type:"POST",
		dataType:"json",
		url: "stockin.php?do=stockInDetail",
		data: "stock_in_id="+stock_id+'&act='+act,
		success:function(msg){
			//每次查询前清除数据
			 $('#list_detail tr:gt(0)').remove();
			
			if(!msg.status){
				alert(msg.info);
				return ;
			}
			
			var key , val;
			var goods_code = '';
			$.each(msg.info , function(key , val){
				goods_code += '<tr><td>'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table><tr>';
				$.each(val.size_quantity , function(k,gs){ goods_code += '<td class="size_info"><span>'+k+'</span><br /><input type="text" name="'+k+'" value="'+gs+'" readonly="readonly" class="bg_brown" /></td>'; });
				goods_code += '</tr></table></td>';
				goods_code += '<td>'+val.sum+'</td><td>'+val.quantity+'</td><td>'+val.diff_num+'</td></tr>';
			});
		
			//有数据就显示表格
			//$('#list_detail').removeAttr("style");
			$('#list_detail').show();
			
			//将数据追加到表格中
			$(goods_code).appendTo('#list_detail');
			
			shworRowBg();
		}
	});
}