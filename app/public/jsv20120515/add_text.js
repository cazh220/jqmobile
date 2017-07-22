// JavaScript Document
$(function(){  
	$('.addblank').css('backgroundColor', '#FFC');
	$(".addblank ").dblclick(function(){
		var val = $(this).text();
		$(this).html("<input type='text' value='"+val+"' size='5' class='on_transport'>");
		$(this).children(".on_transport").focus();
		return;
	});	
	
	
	$(".on_transport").live("blur",function(){
		var val = $(this).val();
		var tr = $(this).parents("tr").children();
		var goodsid = $(tr['0']).text();
		var size = $(tr['4']).text();
        var obj = $(this).parents(".addblank").attr("type");
		
		if(obj == "ship"){
			//$(tr['13']).text(val);
			var  on_road = val;
			var ahead_day = $(tr['11']).text();  
		}
		
		if(obj == "ahead_day")
		{
			var ahead_day = val;
			var on_road = $(tr['7']).text(); 
		}
		
		if(obj == "minimum" || obj == "warning_stock")
		{   
			var ahead_day = $(tr['11']).text(); 
			var on_road = $(tr['7']).text();  
		}
		//alert(ahead_day);	return;	
		//提前期后库存
		var ave   = $(tr['10']).text();  //日均销量
		var stock = $(tr['6']).text();  //当前库存
		
		var  purchase_num=  Number(ave*ahead_day); //采购数量
		
		//$(tr['14']).text(purchase_num);
		var  ahead_stock = Number(stock) + Number(on_road) - purchase_num;  //提前期后库存
		
		$(tr['13']).text(ahead_stock);
		if(Number(ave) != 0)
		{
		  var day_stock = Math.ceil((Number(ahead_stock) + Number(purchase_num))/Number(ave));  //库存天数
		  $(tr['14']).text(day_stock);
		}
		
		
		
		
		
		
		if(val < 0)
		{
		  alert("输入数量不能小于0！");
		  $(this).val("");
		  $(this).focus();
		  return;
		}
		
		$.ajax({ 
		   type: "POST",
		   dataType: "json",
		   url: "commodityanalysis.php?do=On_ship",
		   data: "on_ship="+val+"&goods_id="+goodsid+"&size="+size+"&type="+obj,
		   success: function(msg){			   
			   if(msg.status){
			      	//true
					alert('编辑成功');
					return;
			   }else{
			   		//false					
					alert('编辑失败！');
					return;
			   }
		   }
		});
		
		$(this).parents(".addblank").text(val);
		$(this).remove();
		
		
		return;
	});
	
	//绑定回车键事件
	$('.addblank').keydown(function(event){
		if(event.keyCode == 13)
		{
			$(this).children(".on_transport").blur();
			return;
		}
		return;
	});
});