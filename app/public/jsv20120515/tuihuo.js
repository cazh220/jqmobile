// JavaScript Document

$(function(){
	//搜索层时间选择
	$('#start_time , #end_time').datepicker({
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
	
	//入库搜索分页查询
	$('.page select[name=page]').change(function(){
		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();

		window.location.href = "./tuihuo.php?do=searchList"+qs+"&currentPage="+page;
	});
		

	//添加新申请
	$('input.new_add').click(function(){
		var parm = $('input[type=hidden][name=act]').val();
		window.location = 'tuihuo.php?act=' + parm;
	});

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
															 
		//备注
		var description = $('[name=description]').val();

		//提取商品信息
		var ar_goodsinfo = new Array(), a = new Array() , b = new Array();
		var s_goodsinfo  = '';
		
		//获取提货商品详细
		$('#list tr.list_tr').each(function(){
			if(!$(this).hasClass('hidden') && $(this).find('input[type=checkbox]').attr('checked') && $(this).find('.goods_sn').text()){
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
		
		var isbad=0;
		if($('input[name=is_bad]').attr('checked')){
			isbad=1;
			alert('以不良品退货');
		}else{
			alert('良品退货');
		}

		//判断数组中是否为空($(ar_goodsinfo).size())
		if($(ar_goodsinfo).length < 1) {
			alert('请选择退货商品！');
			return false;
		}
		
		$(this).attr('disabled',true);

		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "tuihuo.php?do=AddTuihuo",
		   data: "goods="+ar_goodsinfo.join('@@')+'&description='+description+'&isbad='+isbad,
		   success: function(msg){
			   alert(msg.info);
			   
			   if(msg.status){
			      window.parent.frames["manFrame"].location.reload();	  
			   }
		   }
		});
	});
	
	//移除退货
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
		   url: "tuihuo.php?do=remove",
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
					agency += "<option value="+i+">"+v+"</option>";							 
				});
				agency += "</select>";
				
				//需要申请提货的数量
				i = '' ; v = '';
				size = '<table><tr>';
				$.each(msg.info.size , function(i , v){
					size += '<td class="size_info"><span>'+v+'</span><br /><input type="text" name="'+v+'" value="0"  onblur="check(this)"></td>';
				});
				size += '</tr></table>';
				
				//各尺寸对应的商品数量
				i = '' ; v = '';
				stock = '<table><tr>';
				$.each(msg.info.stock , function(i , v){
				//stock += '<td class="size_info"><span>'+v['size']+'</span><br /><input type="text" name="'+v['size']+'" value="'+v['quantity']+'" readonly="readonly" class="bold"></td>';
					stock += '<td class="size_info"><span>'+i+'</span><br /><input type="text" name="'+v['size']+'" value="'+v[act]+'" readonly="readonly" class="bold"></td>';
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
	
	var name  = $obj.attr('name');
	var stock = $obj.parent().parent().parent().parent().parent().siblings('td.instock_num').find('input[name='+name+']');
	var num   = $obj.val();
	
	if(num != parseInt(num) || num < 0)
	{
		alert('对不起 , 此处请输入非负整数');
		
		$obj.val(0);
		$obj.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#fdfdbb'}, 500);	
		return false;
	}
	
	//获取同级尺寸商品的在库数量
/*	var sum = stock.val();

	if(sum == undefined)
	{
		sum = 0;
	}
	
	if(parseInt(num) > parseInt(sum)){
		alert('对不起,您要申请'+name+'尺码的提货数量:'+num+' 大于库存数量:'+sum);
		
		$obj.val(0);
		$.each([$obj , stock] , function(){
			$(this)
				.animate({backgroundColor:'#ff0'}, 500)
				.animate({backgroundColor:'#f00'}, 500)
				.animate({backgroundColor:'#ff0'}, 500)
				.animate({backgroundColor:'#f00'}, 500)
				.animate({backgroundColor:'#fdfdbb'}, 500);								 
		});
	}*/
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
				size = '<table><tr>';
				$.each(msg.info.size , function(i , v){
					size += '<td class="size_info"><span>'+v+'</span><br /><input type="text" name="'+v+'" value="0"  onblur="check(this)"></td>';
				});
				size += '</tr></table>';
				
				//各尺寸对应的商品数量
				i = '' ; v = '';
				stock = '<table><tr>';
				$.each(msg.info.stock , function(i , v){
					stock += '<td class="size_info"><span>'+v['size']+'</span><br /><input type="text" name="'+v['size']+'" value="'+v[act]+'" readonly="readonly"></td>';
				});
				stock += '</tr></table>';//alert(stock);
				
				tr.find('.goods_name').text(msg.info.goods_name);
				tr.find('.goods_color').text(msg.info.color);
				tr.find('.goods_size').html(size);
				tr.find('.instock_num').html(stock);
				tr.find('.goods_act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除" />');
			 	td.removeClass('focus');
		   }
		});
};

//显示入库详细
function showDetail(stock_id){
	if(stock_id == ''){
		return false;	
	}
	
	var act = $('#main_up input[type=hidden][name=act]').val();
	
	$.ajax({
		type:"POST",
		dataType:"json",
		url: "tuihuo.php?do=Detail",
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
				goods_code += '<td>'+val.quantity+'</td></tr>';
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