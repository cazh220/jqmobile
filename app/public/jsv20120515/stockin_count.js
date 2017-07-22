// JavaScript Document
$(function(){
	
	$('#main_up input[type=button]').click(function(){
		var action = $(this).attr('name');
		var act    = $(this).siblings('input[name=act]').val();
		
		window.location.href = './stockincount.php?do='+action+'&act='+act;
	});
});

//导出入库商品
function export_instock(id,act){

	window.location.href = './stockincount.php?do=exportInStockGoods&id='+id+'&act='+act;
}