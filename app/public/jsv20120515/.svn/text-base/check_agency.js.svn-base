// JavaScript Document
var tab_goods = function(e){
	if(window.event) // IE
	{
		keynum = e.keyCode
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}
	var data = $('input[name=goods_barcode]').val();

	if(keynum==13 && data.length>0){
		//$('input[name=batch_barcode]').focus();
		$('input[name=num]').focus();
	}
}
var tab_batch = function(e){
	if(window.event) // IE
	{
		keynum = e.keyCode
	}
	else if(e.which) // Netscape/Firefox/Opera
	{
		keynum = e.which
	}
	var data = $('input[name=batch_barcode]').val();
	
	if(data == '' || data.length == 0){
		return false;
	}
	
	if(keynum==13 && data.length>0){
		//查询批次是否存在
		$.ajax({
			   type: "GET",
			   dataType: "json",
			   url: "purchase.php?do=IsExistBatch",
			   data: "batch=" + data,
			   success: function(msg){
				   //alert(msg.status);
				   if(!msg.status){
						alert(msg.info);
						$('input[name=batch_barcode]').attr('value','');
						$('input[name=batch_barcode]').focus();
					}else{
						$('input[name=num]').focus();
					}
			   }
		});
	}
}

var isEmpty = function(obj){
	switch($(obj).attr('name')){
		case 'agency_name':if($(obj).val() == ''){alert('请选择仓库')};break;
		case 'goods_barcode':
			if($(obj).val() == ''){
				alert('请输入商品条码');
				$('input[name=goods_barcode]').focus()
			};break;
		//case 'batch_barcode':if($(obj).val() == ''){alert('请输入批次条码');$('input[name=batch_barcode]').focus()};break;
	}
}

$(function(){

	//选择仓库
	$('input[name=agency_confirm]').click(function(){
		var agency = $.trim($('#agency_id option:selected').text());
		
		if(agency == '请选择' || !agency)
		{
			alert('请选择仓库');return;
		}
		
		$('input[name=agency_name]').val(agency);
		 $('input[name=goods_barcode]').focus();
	});
	
	
	//判断输入商品数据
	$('input.numeric').keyup(function(event){
		evt = (event) ? event : ((window.event) ? window.event : "")
        keyCode = evt.keyCode ? evt.keyCode : (evt.which ? evt.which : evt.charCode);       
		if (keyCode != 13) {
			return false;
		}

		//仓库
		var agency = $('input[name=agency_name]').val();
		
		//盘点仓库id
		var agency_id = $('select[name=agency_id]').val();
		
		if(agency == '' || agency_id < 1) {
			alert('请选择仓库');return;
		}
		
		//商品条码
		var goods_barcode = $('input[name=goods_barcode]').val();
		
		if(goods_barcode == '') {
			alert('请输入条码');return;
		}
		
		//批次条码
		//var batch_barcode = $('input[name=batch_barcode]').val();
		//batch_barcode = batch_barcode.toUpperCase();
							  
		//商品数量
		var num = $(this).val();
		if(num < 0 || num == '')
		{
			alert('请输入盘点商品数量');
			return false;		
		}

		 var now     = new Date();
		 var year    = now.getFullYear();
		 var month   = now.getMonth()+1;
		 var day     = now.getDate();
		 var hours   = now.getHours();
		 var minutes = now.getMinutes();
		 var seconds = now.getSeconds();
		
		var time = year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds;

		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "checkstock.php?do=getGoodsInfo",
		   data: "goods_barcode=" + goods_barcode+"&agencyid="+agency_id,
		   success: function(msg){
			  if(!msg.status)
			  {
				 alert(msg.info);return false;
			  }
			  
			  $("table#list_detail tr:not('.theader')").remove();

			  var tr = '<tr>' , i , v;
			  
			  $.each(msg.data , function(i , v){
					tr += '<td><input type="checkbox" /></td>'+
						  '<td>'+agency+'</td>'+
						  '<td>'+v.goods_sn+'</td>'+
						  '<td>'+v.size+'</td>'+
						  '<td style="color:red;">'+v.curr_quantity+'</td>'+
						  '<td class="must_fill">'+num+'</td>'+
						  '<td>'+time+'</td>'+
						  '<td class="hidden">'+v.color+'</td>'+
						  '<td class="hidden">'+v.goods_name.replace(/&/g, " ")+'</td>'+
						  '<td class="hidden">'+v.goods_id+'</td>'+
						  '<td class="hidden">'+agency_id+'</td>';
			  });
			  
			  tr += '</tr>';			  
			  $('#list').append(tr);
			  
			  $('input[name=goods_barcode]').focus();
		   }
		});
		
		$(this).val('');
		$('input[name=goods_barcode]').val('');
		
		shworRowBg();
	});
	
	//移除
	$('input[name=remove]').click(function(){
		$('#list').find('input[type=checkbox]').each(function(){
			if($(this).attr('checked'))
			{
				$(this).parent().parent().remove();	
			}
		});									   
	});
	
	//盘点数据整理
	$('input[name=edit_data]').click(function(){
		var goods_detail = new Array(), goods = new Array();

		$('#list tr:gt(0)').each(function(){
			$(this).find('td:gt(0)').each(function(){
				goods.push($(this).text());
			});
			goods_detail.push(goods.join('@'));
			
			goods = new Array();
		});
		
		var gd = goods_detail.join('$');
		
		var obj = $(this);
		
		obj.attr('disabled',true);
		
		$.ajax({
			type: "POST",
		   	dataType: "json",
		   	url: "checkstock.php?do=DspoGoodsIndo",
			data: "gd=" + gd,
			success: function(msg){
				$('#list_detail tr:gt(0)').remove();
				if(!msg.status)
				{
					alert(msg.info);return;
				}
				
				
				var tr = '' , key , val , k , v;
				$.each(msg.data , function(key , val){
					tr += '<tr>'+
							'<td>'+ val[1] +'</td>'+
							'<td>'+ val[7] +'</td>'+
							'<td>'+ val[6] +'</td><td><table><tr>';
					$.each(val[2],function(k,v){
						tr += '<td class="size_info"><span>'+k+'</span><br /><input type="text" name="'+k+'" value="'+v+'" readonly="readonly"/></td>';				   
					});
					tr += '</tr></table></td><td>'+ val[0] +'</td>'+
					      '<td>'+ val[3] +'</td>'+
				          '</tr>'				
				});
		
				$('#list_detail').append(tr).show();
				$('input[name=submit]').show();
				
				obj.attr('disabled',false);
				
				shworRowBg();
			}
		});
	});
	
	//数据迁入
	$('input[name=submit]').click(function(){
		var obj = $(this);
		
		obj.attr('disabled',true);
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "checkstock.php?do=AddStockCheckDetail",
		   data: "",
		   success: function(msg){
			   	alert(msg.info);
			   
				if(msg.status){
					window.parent.frames["manFrame"].location.reload();
				}
		   }
		});									   
	});
	
});