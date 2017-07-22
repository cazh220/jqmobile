// JavaScript Document
$(function(){
	
	//入库搜索
	$('#main_up input[type=button][name=search]').click(function(){
		var act = $('input[type=hidden][name=user]').val();
		
		//时间，产品编号
		var goodssn = $('#main_up input[name=goodssn]').val();
		var starttime = $('#main_up input[name=start_time]').val();
		var endtime = $('#main_up input[name=end_time]').val();
		
		var data = 'act='+act+'&start_time='+starttime+'&end_time='+endtime;
		
		if(act == 'supplier'){
			//批次,供应商，产品编号
			var batchid = $('#main_up input[name=batch]').val();
			var supplierid = $('#main_up select[name=supperliersid]').val();
			
			data += '&batchid='+batchid+'&supperliersid='+supplierid;
			
		}else if(act == 'distributor'){
			//提货单号，分销商
			var outid = $('#main_up input[name=stockout_id]').val();
			var po = $('#main_up select[name=po]').val();
			
			data += '&stockout_id='+outid+'&po='+po;
		}else if(act == 'emp'){
			//提货单号，供应商
			var outsn = $('#main_up input[name=stockout_sn]').val();
			var po = $('#main_up select[name=adminid]').val();
			
			data += '&stockout_sn='+outsn+'&po='+po;
		}

		$.ajax({
			type:"POST",
			dataType:"json",
			url: "stockin.php?do=searchStockIn",
		    data: data,
			success:function(msg){
				
				$('#list tr:not(".theader")').remove();//清除数据
				
				if(!msg.status){
					alert(msg.info);
					return;
				}
		
				var code = '';
				if(msg.act == 'supplier'){
					$.each(msg.data,function(k,v){
						code +=  '<tr>'+
								 '<td><input type="checkbox" value="'+v['batch_id']+'" name="batchid"/></td>'+
								 '<td class="batch_code">'+v['batch_code']+'</td>'+
								 '<td class="purchase_id">'+v['purchase_id']+'</td>'+
								 '<td class="supplier_name">'+v['supplier_name']+'</td>'+
								 '<td class="create_time">'+v['create_time']+'</td>'+
								 '<td class="description">'+v['description']+'</td>'+
								 '<td >'+v['sum_num']+'</td>'+
								 '<td >'+v['in_quantity']+'</td>'+
								 '<td ><a href="" onclick="editNum(this); return false;" style="color:red;">编辑</a></td></tr>';
					});
				}else {
					$.each(msg.data,function(k,v){
						code +=  '<tr>'+
								 '<td><input type="checkbox" value="'+v['stock_out_id']+'" name="stockoutid"/><input type=hidden name=stockouttype value="'+v['stock_out_type']+'" /></td>'+
								 '<td class="stockout_id" style="cursor:pointer;"><span>'+v['stock_out_sn']+
								 '</span></td>'+
								 '<td class="out_pserson">'+v['out_person']+'</td>';
						code += (v['stock_out_type'] == 8) ? '<td class="create_time">'+v['return_time']+'</td>' : '';
						code +=	'<td class="create_time">'+v['user_name']+'<br />'+v['stock_out_date']+'</td>'+
								 '<td class="description">'+v['description']+'</td>'+
								 '<td >'+v['sum_num']+'</td>'+
								 '<td >'+v['in_quantity']+'</td>'+
								 '<td ><a href="" onclick="showStockOut(this);return false;"  style="color:red;">编辑</a></td></tr>';
					});
				}

				$(code).appendTo('#list');
				shworRowBg();
			}
		});
	});

	//选择批次号
	$('input[name=select_batch] , input[name=select_stock]').click(function(){
		var x    = $(window).width();
		var y    = $(window).height();
		
		$('#db').css({'z-index':50, 'position':'absolute', 'top':0, 'left':0, width:x, height:y, 'backgroundColor':'#000', opacity:0.2}).show();
		$('#batch_box').css({'z-index':100, 'position':'absolute', 'top':'30px', 'left':'30px'}).show();
	});
	
	//搜索层时间选择
	$('#start_time,#end_time,#starttime,#endtime').datepicker({
		changeMonth: true,
		changeYear: true
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

				//$('input[name=purchaseid]').val(msg.data.purchase_id);
				$('select[name=supperliersid]').get(0).selectedIndex = msg.data.supplier_id;
				//$('select[name=adminid]').get(0).selectedIndex = msg.data.create_user_id ;
		   }
		});
		
		$('input[name=batchid]').val( sel.find('option:selected').text() );
		$('input[name=batch]').val( sel.val() );
		$('#batch_box , #db').hide();
	});
	
	//搜索供应商提货单号
	$('input[name=search_supplier_order]').click(function(){
		var su = $('select[name=supperliers]').val();
		var starttime = $('input[name=starttime]').val();
		var endtime   = $('input[name=endtime]').val();
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockin.php?do=SearchSupplierOrder",
		   data: "supperlier=" + su + "&starttime=" + starttime + "&endtime=" + endtime + "&status=0",
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
	
	//搜索分销商提货单号
	$('#batch_box input[name=search_distributor_order]').click(function(){
		var po = $('#batch_box select[name=po]').val();
		var starttime = $('input[name=starttime]').val();
		var endtime   = $('input[name=endtime]').val();
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockin.php?do=SearchDistributorOrder",
		   data: "po=" + po + "&starttime=" + starttime + "&endtime=" + endtime + "&status=0",
		   success: function(msg){
			 	if( !msg.status )
				{
					alert(msg.info);
					$('select[name=batch_box]').text('');
					return;	
				}

				var option = '<option value=0>请选择</option>' , i , v;
				
				$.each(msg.data , function(i , v){
					option += '<option value="' + v.stock_out_id + '">' + v.stock_out_sn + '</option>';						   
				});
				
				$('select[name=outsn_box]').html(option);
				
				$('input[name=poid]').val(po);
		   }
		});
	});
	
	//确认选择提货单号
	$('input[name=chose_outsn]').click(function(){
		var sel = $('select[name=outsn_box]');
		var sn_code = sel.find('option:selected').text();
		
		var poid = $('input[name=poid]').val();
		
		$('select[name=po]').get(0).selectedIndex = poid;
		$('input[name=stockout_sn]').val(sn_code);
		$('input[name=stockout_id]').val(sel.find('option:selected').val() );
		$('#batch_box , #db').hide();
	});
	
});

//编辑入库数量
var editNum = function(obj){
	$(obj).parent().parent().siblings().find('input[name=batchid]').removeAttr('checked');
	$(obj).siblings().find('input[name=batchid]').attr('checked','true');
			
	var checkbox = $(obj).parent().parent().eq(0);
	checkbox.find('input[type=checkbox]').attr("checked",true);
	var batchid = checkbox.find('input[type=checkbox]:checked').val();
	
/*	if(batchid == undefined || batchid < 1){
		alert('请选择批次！');
		return  false;
	}*/
	
	$.ajax({
		type:"POST",
		dataType:"json",
		url: "purchase.php?do=batchDetail",
		data: "batchid="+batchid,
		success:function(msg){
			//每次查询前清除数据
			 $('#list_detail tr:gt(0)').remove();
						
			//返回出错
			if(!msg.status){
				alert(msg.info);
				$('#list_detail').hide();
				return;
			}
			//alert(batchid);
			var key , val;
			var goods_code = '';
			$.each(msg.info , function(key , val){
				var row = 1;
				goods_code += '<tr><td>'+key+'</td><td>'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table>';
				$.each(val.inquantity , function(k,gs){ 
					goods_code += '<tr class="size_info"><td style="width:30px;"><span>'+k+
						'</span></td><td><span>'+gs['num']+'</span></td><td><input type="text" name="'+k+
						'" value="';
						if(gs['diff_num'] < 0){
							goods_code += 0;
						}else{
							goods_code += gs['diff_num'];	
						}
				
						goods_code += '" onblur="check_num(this)"/><input type="hidden" name="'+k+
						'" value="'+gs['diff_num']+'" /></td><td><select name=agency>';
						$.each(val.agency , function(kk,vv){
							goods_code += '<option value="'+kk+'">'+vv+'</option>';
						});
						goods_code += '</select></td></tr>'; 

				});
				goods_code += '</table></td><td>';
				goods_code += val.total+'</td><td>';
				$.each(val.inquantity , function(k,iq){ goods_code += '<div>'+iq['in_num']+'</div>'; });
				goods_code += '</td><td>';
				$.each(val.inquantity, function(k,diff){ goods_code += '<div>'+diff['diff_num']+'</div>'; });
				goods_code += '</td></tr>';
			});
	
			//有数据就显示表格
			$('#list_detail').removeAttr("style"); 
			
			//将数据追加到表格中
			$(goods_code).appendTo('#list_detail');
		}
	});
}

/**
 * 检测入库数量的合法性
 */
function check_num(obj){
	var in_num = parseInt($(obj).val());//将入库数量
	var diff_num = parseInt($(obj).siblings().val());//未入库数量(进库差额)
	var size_num = parseInt($(obj).parent().parent().children().eq(1).text());

	if(in_num < 0){
		alert('您输入的数量不正确！');
		$(obj).attr('value',diff_num);
	}

}

/**
 * 检测退货入库数量的合法性
 */
function check_tuihuo_num(obj){
	var in_num = parseInt($(obj).val());//将入库数量
	var diff_num = parseInt($(obj).siblings().val());//未入库数量(进库差额)
	var size_num = parseInt($(obj).parent().parent().children().eq(1).text());

	if(in_num < 0 || in_num > size_num - diff_num){
		alert('您输入的数量不正确！');
		$(obj).attr('value',diff_num);
	}
}

var addStockIn = function(obj){
	//操作
	var act = $('#main #operate input[name=user]').val();
	
	var id = 0;
	if(act == 'supplier'){
		id = $('#main table#list tr').not('.theader').find('td input[name=batchid]:checked').val();
	} else {
		id = $('#main table#list tr').not('.theader').find('input[name=stockoutid]:checked').val();
	}
	
	if(id == undefined || id < 1){
		alert('请选择！');
		return false;
	}

	//商品id,入库数量
	var ar = new Array();
	$('#main table#list_detail tr').not('.theader').each(function(){
		var goods_id = $(this).children().eq(0).html();

		var a = new Array();
		$(this).children().eq(4).find('table tr.size_info').each(function(){
			var size = $(this).find('td:eq(0)').text();
			var in_num = $(this).children().find('input').attr('value');
			var agency_id = $(this).children().find('select[name=agency] option:selected').attr('value');
			if(in_num > 0){
				a.push(size+'='+in_num+'='+agency_id);
			}
		});
		
		if(a.join('@') != ''){
			ar.push(goods_id+'=>'+a.join('@'));
		}
	});
	
	var data = ar.join('@@');

	if(data == ''){
		alert('请填写商品的入库数量！');
		return false;
	}
	
	//备注说明
	var description = $.trim($('#main #description textarea').val());
	
	$(obj).attr('disabled',true);

	$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockin.php?do=addStockIn",
		   data: "user=" + act + "&id=" + id + "&data=" + data+'&description='+description ,
		   success: function(msg){
			  if(msg.info == false){
			  	  alert(msg.info);
			  }else{
				  alert(msg.info); 
				  window.location.reload();
			  }
		   }
	});
}

var showStockOut = function(obj) {
	//选中时显示
	$(obj).parent().parent().siblings().find('input[name=stockoutid]').removeAttr('checked');
	$(obj).parent().parent().find('input[name=stockoutid]').attr('checked','true');
	
	var stockoutid = $(obj).parent().siblings().find('input[name=stockoutid]').val();
	var stockoutsn= $(obj).parent().parent().find('.stockout_id span').text();
	var stockouttype = $(obj).parent().parent().find('input[name=stockouttype]').val();
	
	if(stockoutid <= 0 || stockoutsn == '' || stockouttype <= 0){
		return false;
	}

	//批次id
	var batchid = $(obj).parent().parent().find('input[name=batchid]').val();
	if(batchid == ''){
		return false;
	}

	$.ajax({
		type:"POST",
		dataType:"json",
		url:"stockout.php?do=StockOutDetail",
		data:"stockoutid="+stockoutid+'&stockouttype='+stockouttype+'&stockoutsn='+stockoutsn+'&batchid='+batchid,
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
				goods_code += '<tr><td>'+val.goods_id+'</td><td>'+val.goods_sn+'</td><td>'+val.goods_name+'</td><td>'+val.color+'</td><td><table style="width:100%;color:red;">';
				$.each(val.size_quantity_agency , function(k,gs){ 
						goods_code += '<tr class="size_info"><td style="width:50px;">'+k+'</td><td style="width:50px;">'+gs[0]+'</td><td><input type="text" style="width:40px;" name="'+k+'" value="';
						if(gs[1] < 0){
							goods_code += 0;
						}else{
							goods_code += gs[1];	
						}

						goods_code += '" onblur="check_tuihuo_num(this)"/><input type="hidden" name="'+k+
						'" value="'+gs[1]+'" /></td><td><select name=agency>'; 
						$.each(val.agency , function(kk,vv){
							goods_code += '<option value="'+kk+'">'+vv+'</option>';
						});
						goods_code += '</select></td></tr>'; 
				});
				goods_code += '</table></td></tr>';
				
			});
		
			//有数据就显示表格
			$('#list_detail').removeAttr("style");
			$('#list_detail').css("width","100%");
			
			//将数据追加到表格中
			$(goods_code).appendTo('#list_detail');
		}
	});
}