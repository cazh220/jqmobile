// JavaScript Document
$(function(){
	//时间选择
	$('#purchase_time , #delivery_time').datepicker({
		changeMonth: true,
		changeYear: true
	});	   
		   
	//全选反选
	$('#selectAll').toggle(function(){
		$('#list input[name=checkbox]:gt(0)').click();								
	} , function(){
		$('#list input[name=checkbox]:gt(0)').click().parent().parent().mouseout();	
	});	   
		   
	$('input[type=button][name=submit]').click(function(){
		var batch_code    = $('input[type=text][name=batch_code]').val();
		var purchase_id   = $('input[type=text][name=purchase_id]').val();
		var supplies      = $('select[name=supplies]').val();
		var purchase_time = $('input[type=text][name=purchase_time]').val();
		var delivery_time = $('input[type=text][name=delivery_time]').val();
		var add_user      = $('select[name=add_user]').val();
		var mark          = $('#description textarea').val();
		var error         = '';
		var ar_error      = new Array;

		if(batch_code == '')
		{
			error += '请填写批次号' + '\n';	
			ar_error.push($('input[type=text][name=batch_code]'));
		}

		if(supplies == 0)
		{
			error += '请选择供应商' + '\n';
			ar_error.push($('select[name=supplies]'));
		}
		
		if(purchase_time == '')
		{
			error += '请选择采购时间' + '\n';	
			ar_error.push($('input[type=text][name=purchase_time]'));
		}
		
		if(delivery_time == '')
		{
			error += '请选择交货时间' + '\n';
			ar_error.push($('input[type=text][name=delivery_time]'));
		}
		
		if(!add_user || add_user == 0)
		{
			error += '请请选择采购担当' + '\n';
			ar_error.push($('select[name=add_user]'));
		}
		
		if(error != '')
		{	
			$.each(ar_error , function(){
				$(this)
					.animate({backgroundColor: '#f00'}, 100)
					.animate({backgroundColor: '#ff0'}, 100)
					.animate({backgroundColor: '#f00'}, 100)
					.animate({backgroundColor: '#ff0'}, 100)
					.animate({backgroundColor: '#f00'}, 100)
					.animate({backgroundColor: '#fff'}, 100)
					.animate({backgroundColor: '#ff0'}, 100);
			});
			ar_error[0].focus();

			alert(error);
			return false;
		}
	
		//提取商品信息
		var ar_goodsinfo = new Array(), a = new Array() , b = new Array();
		var s_goodsinfo  = '';

		$('#list tr.list_tr').each(function(){
			if(!$(this).hasClass('hide'))
			{
				$(this).children('td:gt(0)').each(function(){

					if(!$(this).hasClass('goods_size'))
					{
						a.push($(this).text());
					}
					//各尺寸下对应的数量
					else
					{
						$(this).find('td.size_info').each(function(){
							b.push($(this).find('span').text() + '=' + $(this).find('input').val());
						});	
						a.push(b.join('@'));
						b.length = 0; //清空数组
					}
				});
			
				ar_goodsinfo.push(a);
				
				a = new Array();
			}
		});
		
		$(this).attr('disabled',true);

		$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'purchase.php?do=AddPurchase',
			   data:'batchcode='+batch_code+'&description='+mark+'&purchaseid='+purchase_id+'&suppliersid='+supplies+'&purchasetime='+purchase_time+'&delivertime='+delivery_time+'&userid='+add_user+'&goods_info='+ar_goodsinfo.join('@@'),
			   success:function(msg){
				    alert(msg.info);
				    
				   	if(msg.status){
						window.parent.frames["manFrame"].location.reload();
						
					}
				  
				   	if(msg.url && !msg.status) {
				   		window.location.href = msg.url;
				   	}
			   }
		});
	});
	
	//货号列下面的input输入框添加class ， 为ajax做准备
	$('td.fill').click(function(){
		if($(this).hasClass('goods_sn'))
		{
			$(this).addClass('focus').find('input').addClass('goods_sn');
		}
	});
	
	//在页面底部添加一个‘添加一行’
	$('div.add_row').clone(true).appendTo('#operate');
	
	//回到页顶
	$('<a id="top" name="top"></a>').prependTo('body');
	$('<a href="#top">回到页首</a>').insertAfter($('div.add_row:gt(0)'));
	
	//移除商品到trash
	$('input[name=remove]').click(function(){
		$('#list tr.hide').find('td.goods_sn').each(function(){
			var td = $(this);
			var sn = td.text();
			
			if(sn && !td.hasClass('add_trash'))
			{	
				var trash_span = $('<span class="trash_sn">'+sn+'</span><br />');
				trash_span.appendTo('#right')
					.animate({color:'#ff0' , fontSize:13}, 500)
					.animate({color:'#f00' , fontSize:12}, 500)
					.animate({color:'#ff0' , fontSize:13}, 500)
					.animate({color:'#f00' , fontSize:12}, 500)
					.click(function(){
						$('#list td.goods_sn').each(function(){
							if( $(this).text() == sn )
							{
								$(this).parent().removeClass('hide');
								td.removeClass('add_trash');
								trash_span.remove();
							}
						});				
					});
					
				$(this).addClass('add_trash');
			}
			
		});
	});

});

var ajax = function($obj){
	sn = $obj.val();
	if(sn == '')
	{	
		$('td.focus').siblings('td:gt(1)').andSelf().text('');
		$('td.focus').removeClass('focus');
		return false;
	}
	
	//如果不是goods_sn ， return
	td = $('td.focus');
	if(!td.hasClass('goods_sn'))
	{
		return ;	
	}
	
	//如果goods_sn已经存在，return
	var i = 0;
	var err_sn = '';
	$('#list td.goods_sn').each(function(){
		if( $(this).text() == sn )
		{	
			i += 1;
			
			if(i>1)
			{
				err_sn = '输入的货号：'+sn+'已存在！';
				$('td.focus').text('');
				return false;
			}
		}	
	});
	
	if(err_sn)
	{
		alert(err_sn);
		
		$('#list td.goods_sn').each(function(){
			if( $(this).text() == sn )
			{
				if( $(this).parent().hasClass('hide') )
				{
					$('#right span.trash_sn').each(function(){
						if( $(this).text() == sn )
						{
							$(this)
								.animate({backgroundColor:'#ff0' , fontSize:14}, 500)
								.animate({backgroundColor:'#f00' , fontSize:12 , color:'#ff0'}, 500)
								.animate({backgroundColor:'#ff0' , fontSize:14 , color:'#f00'}, 500)
								.animate({backgroundColor:'#eee' , fontSize:12}, 500);
						}
					});	
				}
				else
				{
					$(this)
					.animate({backgroundColor:'#ff0'    , fontSize:14 , color:'#f00'}, 500)
					.animate({backgroundColor:'#fdfdbb' , fontSize:12 , color:'#ff0'}, 500)
					.animate({backgroundColor:'#ff0'    , fontSize:14 , color:'#f00'}, 500)
					.animate({backgroundColor:'#fdfdbb' , fontSize:12 , color:'#666'}, 500);
				}
			}									 
		});
		
		return false;
	}
	

	$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "goods.php?do=GetGoodsInfo",
		   data: "goodssn="+sn,
		   success: function(msg){
			 	if(!msg.status)
				{
					alert(msg.info);
					$('td.focus').siblings('td:gt(1)').andSelf().text('');
					$('td.focus').removeClass('focus');
					return;
				}
				
				var i , n;
				size = '<table><tr>';
				$.each(msg.info.size , function(i , n){
					size += '<td class="size_info"><span>'+n+'</span><br /><input type="text" name="'+n+'" value="0" onblur=checkNum(this,"'+msg.info.goods_id+'_'+n+'") ></td>';			
					/*if( msg.info.stock[n] )
					{
						size += '<td class="size_info"><span>'+n+'</span><br /><input type="text" name="'+n+'" value="'+ parseInt(msg.info.stock[n]['quantity'] - msg.info.stock[n]['bad_quantity']) +'"></td>';			
					}
					else
					{
						size += '<td class="size_info"><span>'+n+'</span><br /><input type="text" name="'+n+'" value="0"></td>';				
					}*/
				});
				size += '</tr></table>';

				td.siblings('.goods_name').text(msg.info.goods_name);
				td.siblings('.goods_color').text(msg.info.color);
				td.siblings('.goods_size').html(size);
				td.siblings('.act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除"/>');
			 	td.removeClass('focus');
				
				//添加新行
				addRow();
		   }
		});
}

function checkNum (obj,id) {
	
	//输入出库数量
	var in_num = $(obj).val();
	
	if(!IsNum(in_num) || in_num < 0) {
		alert('对不起，您的输入有误！');
		$(obj)
			.animate({backgroundColor:'#ff0' , fontSize:14}, 500)
			.animate({backgroundColor:'#f00' , fontSize:12 , color:'#ff0'}, 500)
			.animate({backgroundColor:'#ff0' , fontSize:14 , color:'#f00'}, 500)
			.animate({backgroundColor:'#eee' , fontSize:12}, 500);
		$(obj).val(0);
		return;
	}
}


