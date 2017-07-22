// JavaScript Document

$(function(){
	//时间选择	   
	$('#deliver').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//货号列下面的input输入框添加class ， 为ajax做准备
	$('td.fill').click(function(){
		if($(this).hasClass('goods_sn'))
		{
			$(this).addClass('focus').find('input').addClass('goods_sn');
		}
	});
	
	//在页面底部添加一个‘添加一行’
	//$('div.add_row').clone(true).appendTo('#operate');
	
	//回到页顶
	$('<a id="top" name="top"></a>').prependTo('body');
	$('<a href="#top">回到页首</a>').css({marginRight:10 , marginLeft:10}).insertAfter($('div.add_row:gt(0)'));
	
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
	
	//提交申请
	$('#operate_stockout input[name=submit]').click(function(){
		//提货申请的对象
		var act =  $('#main_up input[type=hidden][name=act]').val();
		
		var stockout_sn    = $('#main_up input[type=text][name=stockout_sn]').val();
		var create_time = $('#main_up input[type=text][name=create_time]').val();
		var out_person       = $('#main_up select[name=out_person]').val();
		var description = $('textarea.description').val();

		var error         = '';
		var ar_error      = new Array;
		
		if(act == ''){
			alert('网络运行异常，不知出库申请对象。');
			return;
		}
		
		if(out_person == 0){
			error += '请选择提货申请人' + '\n';	
			ar_error.push($('#main_up select[name=admin]'));
		}
		
		var data = "act="+act+"&stockout_sn="+stockout_sn+"&create_time="+create_time+"&out_person="+out_person+"&description="+description;
		
		if(act == 'distributor' || act == 'supplier'){
			var out_time = $('#main_up input[type=text][name=out_time]').val();
			var po       = $('#main_up select[name=po]').val();
			
			if(out_time == ''){
				error += '请选择出货时间' + '\n';	
				ar_error.push($('#main_up input[type=text][name=out_time]'));
			}
			
			if(po == 0){
				error += '请选择提货人' + '\n';	
				ar_error.push($('#main_up select[name=po]'));
			}
			
			data += "&out_time="+out_time+"&po="+po;
		}else if(act == 'emp'){
			var need_return = $("#main_up input[@type=checkbox]:checked").val();
			if(need_return != 0){need_return = 1;}
			var return_time = $('#main_up input[type=text][name=return_time]').val();
			data += "&need_return="+need_return+"&return_time="+return_time;
		}else if(act == 'lose' || act == 'bad'){
			var agency = $('#main_up select[name=agencyid]').val();
			
			if(agency == 0){
				error += '请选择仓库' + '\n';	
				ar_error.push($('#main_up select[name=agencyid]'));
			}
			data += "&agency_id="+agency;
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
		
		//获取提货商品详细
		$('#list tr.list_tr').each(function(){
			if(!$(this).hasClass('hide')){
				$(this).children('td:gt(0)').each(function(){	   
					if($(this).hasClass('goods_size')) {
						$(this).find('td.size_info').each(function(){
							b.push($(this).find('span').text() + '=' + $(this).find('input').val());
						});	
						a.push(b.join('@'));
						b.length = 0; //清空数组	
					}
					else if($(this).hasClass('agency')){
						a.push($(this).find('option:selected').val());
					}
					//各尺寸下对应的数量
					else{
						a.push($(this).text());
					}
				});
			
				ar_goodsinfo.push(a);
				a = new Array();
			}
		});
		
		$(this).attr('disabled',true);

		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "stockout.php?do=addRequest",
		   data: data+"&goods_info="+ar_goodsinfo.join('@@'),
		   success: function(msg){
			   alert(msg.info);
			   
			   if(msg.status){
			      window.parent.frames["manFrame"].location.reload();	  
			   }
		   }
		});
	});
});


var ajax = function($obj){
	//出库类型
	var act = $('input[name=act]').val();
	//var supplies = $('select[name=po]').val();
	
	var sn = $obj.val();
	if(sn == '') {	
		$('td.focus').siblings('td:gt(0)').andSelf().text('');
		$('td.focus').removeClass('focus');
		return false;
	}
	
	//如果不是goods_sn ， return
	td = $('td.focus');
	if(!td.hasClass('goods_sn')) return ;
	
	//如果goods_sn已经存在，return
	var i = 0, err_sn = '';
	$('#list td.goods_sn').each(function(){
		if( $(this).text() == sn ) {	
			i += 1;
			
			if(i>1) {
				err_sn = '输入的货号：'+sn+'已存在！';
				$('td.focus').text('');
				return false;
			}
		}	
	});

	if(err_sn) {
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
		   data: "goodssn=" + sn + "&type=out",
		   success: function(msg){
			 	if(!msg.status)
				{
					alert(msg.info);
					$('td.focus').siblings('td:gt(0)').andSelf().text('');
					$('td.focus').removeClass('focus');
					return;
				}
				
				act = (act == 'bad')    ? 'bad_quantity' : 'efficacious_quantity';
				
				//仓库
				var i , v , agency = "<select onchange=AgencyChange(this,'"+act+"');>";
				$.each(msg.info.agency , function(i , v){
					agency += "<option value="+i;
					if(msg.info.agencyed == i) {agency += " selected=selected";}
					agency += ">"+v+"</option>";
				});
				agency += "</select>";
				
				//需要申请提货的数量
				i = '' ; v = '';
				size = '<table style="width:100%;"><tr>';
				$.each(msg.info.size , function(i , v){
					size += '<td class="size_info"><span>'+v+'</span><br /><input type="text" name="'+v+'" value="0"  onblur="check(this)"></td>';
				});
				size += '</tr></table>';
				
				//各尺寸对应的商品数量
				i = '' ; v = '';
				stock = '<table style="width:100%;"><tr>';
				$.each(msg.info.stock , function(i , v){
					stock += '<td class="size_info"><span>'+i+'</span><br />'+
					         '<input type="text" name="'+v['size']+'" value="'+v[act]+'" readonly="readonly" class="bold"><br />'+
							 '<input type="text" name="freeze_'+v['size']+'" value="'+v['freeze']+'" readonly="readonly" style="margin-top:5px;color:red;"></td>';
				});
				stock += '</tr></table>';

				td.siblings('.agency').html(agency);
				td.siblings('.goods_name').text(msg.info.goods_name);
				td.siblings('.goods_color').text(msg.info.color);
				td.siblings('.goods_size').html(size);
				td.siblings('.instock_num').html(stock);
				td.siblings('.act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除" />');
			 	td.removeClass('focus');
			
				//添加新行
				addRow();
		   }
		});
};

//检测提货数量在合法性
var check = function(obj){
	$obj = $(obj);
	
	var size   = $obj.attr('name');   //当前提货尺寸
	var num    = $obj.val();          //当前提货数量

	if(num != parseInt(num) || num < 0) {
		alert('对不起 , 此处请输入非负整数');
		
		$obj.val(0);
		$obj.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#fdfdbb'}, 500);	
		return false;
	}
	
	//库存对象
	var stock  = $obj.parent().parent().parent().parent().parent().siblings('td.instock_num');

	//获取同级尺寸商品的在库数量
	var stock_sum = (stock.find('input[name='+size+']').val() != undefined) ? stock.find('input[name='+size+']').val() : 0;
	
	//获取同级尺寸商品的冻结库存
	var stock_freeze = (stock.find('input[name=freeze_'+size+']').val() != undefined) ? stock.find('input[name=freeze_'+size+']').val() : 0;

	//if(parseInt(num) > parseInt(stock_sum-stock_freeze)){
	if(parseInt(num) > parseInt(stock_sum)){
		alert('对不起,您要申请的“'+size+'”商品库存不足!');
		
		$obj.val(0);
		$.each([$obj , stock.find('input[name='+size+']')] , function(){
			$(this)
				.animate({backgroundColor:'#ff0'}, 500)
				.animate({backgroundColor:'#f00'}, 500)
				.animate({backgroundColor:'#ff0'}, 500)
				.animate({backgroundColor:'#f00'}, 500)
				.animate({backgroundColor:'#fdfdbb'}, 500);								 
		});
	}
};


/**
 * 更改仓库
 */
var AgencyChange = function(obj,act){
	$obj = $(obj);
	tr   = $obj.parent().parent();
	var goods_sn   = tr.find('td.goods_sn').text();
	var agencyid = $obj.find('option:selected').val();

	$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "goods.php?do=GetGoodsInfo",
		   data: "&goodssn="+goods_sn+"&agency="+agencyid,
		   success: function(msg){
			 	if(!msg.status)
				{
					alert(msg.info);
					$('td.focus').text('');
					return;
				}
				
				tr.find('.instock_num').empty();
				
				//仓库
				if(msg.info.agency) {
					var i , v , agency = "<select onchange=AgencyChange(this,'"+act+"');>";
					$.each(msg.info.agency , function(i , v){
						agency += "<option value="+i;
						if(agencyid == i) {agency += " selected=selected";}
						agency += ">"+v+"</option>";							 
					});
					agency += "</select>";
					
					td.find('.agency').html(agency);
				}
				
				//需要申请提货的数量
				i = '' ; v = '';
				size = '<table style="width:100%;"><tr>';
				$.each(msg.info.size , function(i , v){
					size += '<td class="size_info"><span>'+v+'</span><br /><input type="text" name="'+v+'" value="0"  onblur="check(this)"></td>';
				});
				size += '</tr></table>';
				
				//各尺寸对应的商品数量
				i = '' ; v = '';
				stock = '<table style="width:100%;"><tr>';
				$.each(msg.info.stock , function(i , v){
					stock += '<td class="size_info"><span>'+i+'</span><br />'+
					         '<input type="text" name="'+v['size']+'" value="'+v[act]+'" readonly="readonly" class="bold"><br />'+
							 '<input type="text" name="freeze_'+v['size']+'" value="'+v['freeze']+'" readonly="readonly" style="margin-top:5px;color:red;"></td>';
				});
				stock += '</tr></table>';
				
				tr.find('.goods_name').text(msg.info.goods_name);
				tr.find('.goods_color').text(msg.info.color);
				tr.find('.goods_size').html(size);
				tr.find('.instock_num').html(stock);
				tr.find('.goods_act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除" />');
			 	td.removeClass('focus');
		   }
		});
	
};