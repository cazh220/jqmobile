$(function(){
	//改变每页条数
	$('.page input[name=input_page]').keyup(function(event){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		//回车事件
		var keynum = event.keyCode || event.which;

		if(keynum != 13) return false;
		
		window.location.href = "./stocklock.php?do=searchStock"+qs+"&pageSize="+page;

	});

	//分页
	$('.page select[name=page]').change(function(){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		window.location.href = "./stocklock.php?do=searchStock"+qs+"&currentPage="+page;

	});
		   
	//根据商品货号查询商品id
	$('#main_up input[name=goods_sn]').keyup(function(event){
		var goods_sn = $(this).val();
		
		if(goods_sn == ''){
			return false;	
		}
		
		var agencyid = $('#main_up select[name=agencyid]').val();
		
		//回车事件
		var keynum = event.keyCode || event.which;

		if(keynum != 13) return false;
		
		$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'goods.php?do=GetGoodsBy',
			   data:'param='+goods_sn,
			   success:function(msg){

				   	if(!msg.status){
						$('#msg1').text(msg.info);
						$('#msg1').css('display','');
						window.setTimeout("$('#msg1').hide()",3000);
					}else{
						$('#msg1').css('display','none');
						window.location.href = './stocklock.php?do=searchStock&goodsid='+msg.info+'&agencyid='+agencyid+'&goods_sn='+goods_sn;
					}
				}
		});
	});	
	
	//根据商品名称查询商品id
	$('#main_up input[name=goods_name]').keyup(function(event){
		var goods_name = $(this).val();
		
		if(goods_name == ''){
			return false;	
		}
		
		var agencyid = $('#main_up select[name=agencyid]').val();
		
		//回车事件
		var keynum = event.keyCode || event.which;

		if(keynum != 13) return false;

		$.ajax({
			   type:'POST',
			   dataType: "json",
			   url:'goods.php?do=GetGoodsBy',
			   data:'act=name&param='+goods_name,
			   success:function(msg){
				  
				    if(!msg.status){
						//$('#main_up input[type=hidden][name=goods_id]').val('');
						$('#msg2').text(msg.info);
						$('#msg2').css('display','');
						window.setTimeout("$('#msg2').hide()",3000);
					}else{
						$('#msg2').css('display','none');
						window.location.href = './stocklock.php?do=searchStock&goodsid='+msg.info+'&agencyid='+agencyid+'&goods_name='+goods_name;
					}
				}
		});
	});
	

	
	

});

/**
 * 库存统计操作
 */
function stockcount(act) {
	//执行动作
	act = act+'Stock';

	window.location.href = './stocklock.php?do='+act;
}


/**
*商品仓库库存
*/

function exportAgency_stock(){
   window.location.href = './stocklock.php?do=ExportAgency_stock';

}
