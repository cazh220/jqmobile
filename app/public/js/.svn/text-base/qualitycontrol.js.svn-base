// JavaScript Document
$(function(){
	//查记录搜索层时间选择
	$('#start_time,#end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	
	//跳转至良品转次品处理界面
	$('#convertgood').click(function(){
		//var parm = $('input[type=hidden][name=user]').val();
		window.location = 'qualitycontrol.php?do=request&act=1';
	});
	
	//跳转至包装损坏退货处理界面
	$('#hiddengood').click(function(){

		window.location = 'qualitycontrol.php?do=request&act=2';
	});
	
	//跳转至次品转良品处理界面
	$('#convertbad').click(function(){

		window.location = 'qualitycontrol.php?do=request&act=3';
	});

	//跳转至额外次品处理界面
	$('#extrabad').click(function(){

		window.location = 'qualitycontrol.php?do=request&act=4';
	});
	
	//审核转换申请
	$('#operate input[name=submit]').click(function(){
//		var act = $('input[type=hidden][name=act]').val();
//		var user = $('input[type=hidden][name=user]').val();

		var quality_info  = [] , audit;
		var quality_input = $('#list tr td input[type=checkbox]:checked');
		
		quality_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $(this).parent().siblings('.confirm_status').text();
			  if( audit == '已审核' || audit == '取消' )
			  {
				  return false;
			  }
			  quality_info.push($(this).val());
		});
		if( audit == '已审核' || audit == '取消' )
		{
			alert('已审核或已取消，禁止审核');
			quality_input.attr('checked' , '');
			quality_input.parent().parent().removeClass('checked');
			return;
		}
		
		if(quality_info.length == 0)
		{
			alert('请选择您要审核的转换');
			return;	
		}
		
		var obj = $(this);
		
		obj.attr('disabled',true);

		//执行审核操作
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "qualitycontrol.php?do=vertifyRequest",
		   data: "quality_control_id="+quality_info,
		   success: function(msg){
			  alert(msg.info);	  
			  if(msg.status)
			  {
				  quality_input.parent().siblings().removeClass('red').end().siblings('.convert').text(msg.data);
				  quality_input.parent().siblings('.confirm_status').text('已审核');
			  }

			  quality_input.parent().parent().removeClass('checked');
			  quality_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  quality_input.attr('checked' , '');
			  
			  obj.attr('disabled',false);
		   }
		});	
	});
	
	//移除转换申请详细，即设置审核状态为 2
	$('#operate input[name=remove]').unbind('click').click(function(){
		var quality_info  = [] , audit;
		var quality_input = $('#list tr td input[type=checkbox]:checked');	
		quality_input.each(function(){
			  //已审核 及 无效采购不能审核					  
			  audit = $(this).parent().siblings('.confirm_status').text();	
			  if( audit == '已审核' || audit == '取消' )
			  {
				  return false;
			  }
			  quality_info.push($(this).val());
		});

		if( audit == '已审核' || audit == '取消' )
		{
			alert('已审核或已取消，禁止移除');
			quality_input.parent().parent().removeClass('checked');
			quality_input.attr('checked' , '');
			return;
		}
		
		if(quality_info.length == 0)
		{
			alert('请选择您要移除转换申请');return;	
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
		   url: "qualitycontrol.php?do=remove",
		   data: "quality_control_id=" + quality_info,
		   success: function(msg){
			  if(msg.status)
			  {
				  quality_input.parent().siblings().removeClass('red').end().siblings('.convert').text(msg.data).end().siblings('.confirm_status').text('取消');
			  }
			
			 alert(msg.info);
			
			  quality_input.parent().siblings().andSelf()
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600)
					.animate({backgroundColor: '#ff0'}, 600)
					.animate({backgroundColor: '#f3f9fa'}, 600);
			  quality_input.parent().parent().removeClass('checked');
			  quality_input.attr('checked' , '');
			  
			  obj.attr('disabled',false);
		   }
		});	
	});
	
})
