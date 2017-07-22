$(function(){
	//时间选择	   
	$('#start_time,#end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//改变每页条数
	$('.page input[name=input_page]').keyup(function(event){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		var user = $('#main_up input[name=user]').val();
		
		//回车事件
		var keynum = event.keyCode || event.which;

		if(keynum != 13) return false;
		
		window.location.href = "./stockout.php?do=findRequest&user="+user+qs+"&pageSize="+page;

	});
		
	//分页搜索
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//
		var user = $('#main_up input[name=user]').val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./stockout.php?do=findRequest&user="+user+qs+"&currentPage="+page;
	});
	
	//提交出库
	$('#operate input[type=button][name=submit]').click(function(){
		var user = $('#operate input[type=hidden][name=user]').val();

		if(user == ''){
			window.location.href = "javascript:history.go(-1)";
		}
		
		var stock_id = new Array();
		
		$("#list input:checked").each(function(){
			stock_id.push($(this).val());
		});
		
		//商品id,入库数量
		var ar = new Array();
		$('#main table#list_detail tr').not('.theader').each(function(){
			var goods_id = $(this).children().find('input[type=hidden][name=goods_id]').val();

			$(this).children().eq(3).find('table tr').each(function(){
				var size = $(this).find('td:eq(0)').text();
				var in_num = $(this).children().find('input').attr('value');
				//var agency_id = $(this).children().find('select[name=agency] option:selected').attr('value');
				var agency_id = $(this).children().find('input[name=agency_id]').attr('value');
				if(in_num > 0){
					ar.push(goods_id+'='+size+'='+in_num+'='+agency_id);
				}
			});
		});
		
		var data = ar.join('@');//alert(data);return;
		
		var description = $('#description textarea').val();
		
		$(this).attr('disabled',true);
		
		window.location.href = "./stockout.php?do=addStockOut&user="+user+"&stockoutid="+stock_id+"&description="+description+"&data="+data;

	});
	//打印
	$('#operate input[type=button][name=print]').click(function(){
		var user = $('#operate input[type=hidden][name=user]').val();

		if(user == ''){
			window.location.href = "javascript:history.go(-1)";
		}
		
		var stock_id = new Array();
		
		$("#list input:checked").each(function(){
			stock_id.push($(this).val());
		});
		
		window.open("./stockout.php?do=StockOutPrint&user="+user+"&stockoutid[]="+stock_id);

	});
	
	//重值已操作
	$('#operate input[type=reset][name=reset]').click(function(){
		window.location.reload();
	});
	
	//编辑出库详细
	$('#list td.edit_stockout').click(function(){
		$(this).parent().siblings().find('input[name=stockoutid]').removeAttr('checked');
		$(this).siblings().find('input[name=stockoutid]').attr('checked','true');
		//选中时显示
			
		var stockoutid = $(this).siblings().find('input[name=stockoutid]').val();
		var stockoutsn= $(this).siblings().find('span').text();
		var stockouttype = $(this).siblings().find('input[name=stockouttype]').val();
		
		if(stockoutid <= 0 || stockoutsn == '' || stockouttype <= 0){
			return false;
		}

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
					goods_code += '<tr><td><input type=hidden name=goods_id value='+key+' />'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table width=100%>';
					
					$.each(val.size_quantity_agency , function(k,gs){ 
						goods_code += '<tr><td class="size_info" style="width:40px;"><span>'+k+'</span></td><td class="size_info" style="width:40px;">'+
									  '<span class=request_num>'+gs[0]+'</span></td><td><input type="text" name="'+k+'" value="'+gs[1]+'" style="width:30px;"'+
									  'onblur=checkNum(this,"goods_'+key+'","'+k+'") />'+
									  '</td><td style="width:100px;border:none;">'+
									  '<input type="text" name="agency_name" value="'+gs[2]+'" style="width:100px;" disabled="disabled"/>'+
									  '<input type="hidden" name="agency_id" value="'+val.agency_id+'" /></td></tr>'; 
					});
					
					goods_code += '</table></td><td><table width=100%>';
					$.each(val.sum , function(k,gs){ 
						goods_code += '<tr><td>'+gs+'</td></tr>'; 
					});
					goods_code += '</table></td><td><table style="width:100%;" id="goods_'+key+'">';
					$.each(val.quantity, function(k,gs){ 
						goods_code += '<tr id="goods_'+key+'_'+k+'">';
						if(gs > 0){
							goods_code += '<td style="color:red;font-weight:bold;">'+gs+'</td>';
						} else {
							goods_code += '<td>'+gs+'</td>';
						}
						goods_code += '</tr>';
					});
					goods_code += '</table></td></tr>';
				});

				//有数据就显示表格
				$('#list_detail').removeAttr("style");
				$('#list_detail').css("width","100%");
				
				//将数据追加到表格中
				$(goods_code).appendTo('#list_detail');
				$('#description').css('display','block');
			}
		});
		
	});
});

function checkNum (obj,id,size) {
	//申请数量
	//var request_num = $(obj).parent().parent().find('td.size_info .request_num').text();
	
	//未出库数
	var no_out_stock = $('#'+id+'_'+size).text();
	
	//输入出库数量
	var in_num = $(obj).val();
	
	if(!IsNum(in_num) || no_out_stock < in_num) {
		alert('对不起，您的输入有误！');
		$(obj).val(no_out_stock);
		return;
	}
}