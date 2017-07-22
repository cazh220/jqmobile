
$(function(){
	        //关于提交
	         $('#oksub').click(function(){ 
	         	   var timebegin=$('#timebegin').val(); 
	         	   var timeend=$('#timeend').val();  
	         	   if(timebegin!=null&&timebegin!=''&&timeend!=null&&timeend!=''){ 
							var arrbegin=timebegin.split('-');  
							var arrend=timeend.split('-'); 
							var databegin=new Date(arrbegin[0],arrbegin[1],arrbegin[2]); 
							var dataend=new Date(arrend[0],arrend[1],arrend[2]);  
							if(databegin>dataend){
								alert('开始时间必须小于等于结束时间'); 	
								  return false;
							} 
							$('#queryok').submit();  
         	       }
	         	     $('#queryok').submit();
	         	
	         	});
				$('#cleardata').click(function(){ 
				   $('#goods_typeid').val(0); 
				   $('#goods_name').val('');
				
				});
				//时间选择
					$('#timebegin,#timeend').datepicker({
									showMonthAfterYear: true,	
									changeMonth: true,
									changeYear: true,
									maxDate:0}).datepicker($.datepicker.regional['zh-CN']);
						
					//全选/不选
					$('#allck').live('click',function(){
						    $(':checkbox[name=ck]').each(function(){
						  	     $(this).attr('checked',$('#allck').attr('checked'));
						  	});
					});
			   
		//导出所有订单商品
		$('#expordAll').click(function(){ 
			 $('#queryok').attr( 'action',"./commodityanalysis.php?do=ExportExcle");
			 $('#isall').val(1);
			  $('#queryok').submit();
			   $('#isall').val(0);
			  $('#queryok').attr( 'action',"./commodityanalysis.php?do=QueryGoods");
			});	
					//导出选订商品
		$('#expordOrderGoods').click(function(){			 
			 $('#queryok').attr( 'action',"./commodityanalysis.php?do=ExportExcle");
			 $('#isall').val(1);
			  $('#queryok').submit();
			  $('#isall').val(0);
			  $('#queryok').attr( 'action',"./commodityanalysis.php?do=QueryGoods");
	    });	
		
		//改变每页条数
	$('.page input[name=input_page]').keyup(function(event){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		//回车事件
		var keynum = event.keyCode || event.which;

		if(keynum != 13) return false;
		
		window.location.href = "./commodityanalysis.php?do=QueryGoods"+qs+"&pageSize="+page;

	});

	//分页
	$('.page select[name=page]').change(function(){

		//请求的页数
		var page = $(this).val();
		
		//查询字符串
		var qs = $(this).siblings('input[type=hidden][name=query_string]').val();
		
		window.location.href = "./commodityanalysis.php?do=QueryGoods"+qs+"&currentPage="+page;

	});	
				 
});


