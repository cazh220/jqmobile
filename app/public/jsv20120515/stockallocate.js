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
});

//审核和移除
var edit = function(obj) {
	if((act = obj.name) == 'undefined') {
		alert('操作异常,请刷新');return;	
	}
	
	var allot_info  = [] , audit;	
	var allot_input = $('#main #list tr td input[type=checkbox]:checked');

	allot_input.each(function(){
		//已审核 及 无效采购不能审核					  
		audit = $(this).parent().siblings('.confirm_status').text();					  
		if( audit == '已审核' || audit == '无效' )
		{
			return false;	
		}
		allot_info.push($(this).val());
	});
	
	if( audit == '已审核' || audit == '无效' )
	{
		alert('已审核或已取消，禁止审核');
		allot_input.attr('checked' , '');
		allot_input.parent().parent().removeClass('checked');
		return;
	}
	
	if(allot_input.length == 0)
	{
		alert('请选择你要操作的调拨单');
		allot_input.attr('checked' , '');
		return;	
	}

	var obj = $(obj);
	
	obj.attr('disabled',true);
	
	$.ajax({
	   type: "POST",
	   dataType: "json",
	   url: "stock.php?do="+act,
	   data: "allotid="+allot_info,
	   success: function(msg){
		   
		  if(msg.status){
			 act = (act == 'removeAllocate') ? '无效' : '已审核';
			 allot_input.parent().siblings().removeClass('red').end().siblings('.confirm_status').text(act);  
		  }
		  
		  alert(msg.info);

		  allot_input.parent().find('input').removeClass('checked');

		  $('#list_detail tr:gt(0)').remove();
		  allot_input.parent().siblings().andSelf()
				.animate({backgroundColor: '#ff0'}, 600)
				.animate({backgroundColor: '#f3f9fa'}, 600)
				.animate({backgroundColor: '#ff0'}, 600)
				.animate({backgroundColor: '#f3f9fa'}, 600);
		  allot_input.attr('checked' , '');
		  
		  obj.attr('disabled',false);
	   }
	});
}

var ajax = function($obj){
	//货号
	var sn = $obj.val();
	
	if(sn == '') {	
		$('td.focus').siblings('td:gt(0)').andSelf().text('');
		$('td.focus').removeClass('focus');
		return false;
	}
	
	//如果不是goods_sn ， return
	td = $('td.focus');
	
	if(!td.hasClass('goods_sn')) return;
 
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
				
				var i , v;
				
				//仓库
				var agency = '<select onchange="AgencyChange(this);">';
				$.each(msg.info.agency , function(i , v){
					agency += '<option value='+i+'>'+v+'</option>';							 
				});
				agency += '</select>';
				
				//仓库列表
				var to_agency = '<select name="to_agency"><option value="">请选择</option>';
				$.each(msg.info.agency_list , function(i , v){
					to_agency += '<option value='+i+'>'+v+'</option>';							 
				});
				to_agency += '</select>';

				
				//各尺寸对应的商品数量
				var size_num = '<select name="size_num" onchange="changeStockNum(this)"><option value="">请选择</option>';
				$.each(msg.info.stock , function(i , v){
					size_num += '<option value="'+v['efficacious_quantity']+'" >'+i+'</option>';
				});
				size_num += '</select>';
				
				td.siblings().find('input[name=checkbox]').val(msg.info.goods_id);
				td.siblings('.goods_name').text(msg.info.goods_name);
				td.siblings('.goods_color').text(msg.info.color);
				td.siblings('.from_agency').html(agency);
				td.siblings('.size_num').html(size_num);
				td.siblings('.allocate_num').html(0);
				td.siblings('.to_agency').html(to_agency);
				
				td.siblings('.act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除" />');
			 	td.removeClass('focus');
			
				//添加新行
				addRow();
		   }
		});
}

//选择尺寸更改显示库存数量
var changeStockNum = function(obj) {

	$(obj).parent().parent().find('.instock_num').text($(obj).val());
	$(obj).parent().parent().find('.allocate_num').html(0);
}

/**
 * 更改仓库
 */
var AgencyChange = function(obj){
	$obj = $(obj);
	tr   = $obj.parent().parent();
	var goods_sn   = tr.find('td.goods_sn').text();
	var agencyid = $obj.find('option:selected').val();

	$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "goods.php?do=GetGoodsInfo",
		   data: "&goodssn="+goods_sn+"&agency="+agencyid+"&type=out",
		   success: function(msg){
			 	if(!msg.status)
				{
					alert(msg.info);
					$('td.focus').text('');
					return;
				}
				
				tr.find('.instock_num').empty();
				
				//各尺寸对应的商品数量
				var size_num = '<select name="size_num" onchange="changeStockNum(this)"><option value="">请选择</option>';
				$.each(msg.info.stock , function(i , v){
					size_num += '<option value="'+v['efficacious_quantity']+'" >'+i+'</option>';
				});
				size_num += '</select>';

				tr.find('.size_num').html(size_num);
				tr.find('.allocate_num').html(0);
				
				tr.find('.goods_act').html('<img src="public/images/icon_drop.gif"  onclick="romoveRow(this);" title="移除" />');
			 	td.removeClass('focus');
		   }
		});
};