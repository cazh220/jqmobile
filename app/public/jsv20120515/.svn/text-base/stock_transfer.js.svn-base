$(function(){
	//时间选择	   
	$('#time,#timebegin,#timeend').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//获取产品小类
	$('select[name=time_category]').change(function(){
		var categoryid = $(this).val();
		if(categoryid == ''){
			$('select[name=time_type] option').remove();
			return false;
		}
		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stock_transfer.php?do=GetType",
			data:"categoryid="+categoryid,
			success:function(msg){
				if(!msg.status){
					return ;
				}
				
				$('select[name=time_type] option').remove();
				
				var key,val;
				var code = '<option value=\'0\'>全部</option>';
				if(msg.data){
					$.each(msg.data, function(key , val){code +='<option value='+val.cat_id+'>'+val.cat_name+'</option>';});
				}
				$(code).appendTo('select[name=time_type]');
			} 				
		});
	});
	
	$('select[name=channel_category]').change(function(){
		var categoryid = $(this).val();
		if(categoryid == ''){
			$('select[name=channel_type] option').remove();
			return false;
		}
		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stock_transfer.php?do=GetType",
			data:"categoryid="+categoryid,
			success:function(msg){
				if(!msg.status){
					return ;
				}
				
				$('select[name=channel_type] option').remove();
				
				var key,val;
				var code = '<option value=\'0\'>全部</option>';
				if(msg.data){
					$.each(msg.data, function(key , val){code +='<option value='+val.cat_id+'>'+val.cat_name+'</option>';});
				}
				$(code).appendTo('select[name=channel_type]');
			} 				
		});
	});
	
	//获取产品名称
	$('select[name=time_type]').change(function(){
		var typeid = $(this).val();
		if(typeid == ''){
			$('select[name=time_pname] option').remove();
			return false;
		}
		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stock_transfer.php?do=GetPname",
			data:"typeid="+typeid,
			success:function(msg){
				if(!msg.status){
					return ;
				}
				
				$('select[name=time_pname] option').remove();
				
				var key,val;
				var code = '<option value=\'0\'>全部</option>';
				if(msg.data){
					$.each(msg.data, function(key , val){code +='<option value='+val.goods_id+'>'+val.goods_name+'</option>';});
				}
				$(code).appendTo('select[name=time_pname]');
			} 				
		});
	});
	
	$('select[name=channel_type]').change(function(){
		var typeid = $(this).val();
		if(typeid == ''){
			$('select[name=channel_pname] option').remove();
			return false;
		}
		$.ajax({
			type:"POST",
			dataType:"json",
			url:"stock_transfer.php?do=GetPname",
			data:"typeid="+typeid,
			success:function(msg){
				if(!msg.status){
					return ;
				}
				
				$('select[name=channel_pname] option').remove();
				
				var key,val;
				var code = '<option value=\'0\'>全部</option>';
				if(msg.data){
					$.each(msg.data, function(key , val){code +='<option value='+val.goods_id+'>'+val.goods_id+' - '+val.goods_name+'</option>';});
				}
				$(code).appendTo('select[name=channel_pname]');
			}
		});
	});
	
	//鼠标悬浮更换背景颜色
	$('.list_main').hover(function(){
		$(this).css({background:"#F3F9CA"});
	},function(){
		$(this).css({background:"#FFF"});
	});
	
	//库存更改
	$('.stock_change li').dblclick(function(){
		var i = $(this).text();
		var pid = $(this).parents('.list_main').attr('id'); //商品编号
		$(this).html("<input type=\"text\" class=\"stockInsert\" value="+ i +"></input>");
		$(this).children(".stockInsert").focus();	
		$(this).children(".stockInsert").blur(function(){
			var p = $(this);			
			var cval = $(this).val(); //输入的值
			var cid = $(this).parents('li').attr('id'); //渠道编号
			var color = $(this).parents('.list_title_channel').siblings('.list_title_color').text();
			var size = $(this).parents('.list_title_channel').siblings('.list_title_size').text(); 

			$.ajax({
				type:"POST",
				dataType:"json",
				url:"stock_transfer.php?do=TransferStock",
				data:"goods_id="+pid+"&agency_id="+cid+"&quantity="+cval+"&color="+color+"&size="+size,
				success:function(msg){
					if(!msg.status){
						alert('更新库存失败');
						p.parents('li').html(i);
						return;
					}else{
						alert('更新库存成功');
						p.parents('li').html(cval);	
						location.reload()
						return;		
					}
					
				}
			});			
		});
	});
	//提交时间　查询　
	$('input[name=time_submit]').click(function(){
		var time_category = $('select[name=time_category]').val();
		var time_type = $('select[name=time_type]').val();
		var time_pname = $('select[name=time_pname]').val();
		var url = '/stock_transfer.php?do=Search&stype=category&category='+time_category+'&type='+time_type+'&goods_id='+time_pname;
		if(time_category == ""){
			alert('非法的查询');
			return ;
		}
		location.href=url;
	});
	
	//an huo hao cha xun
	$('.stock_search_goods_sn_submit').click(function(){
		var goods_sn = $('input[name=goods_sn]').val();
		var url = '/stock_transfer.php?do=Search&stype=goods_sn&goods_sn='+goods_sn;
		if(!goods_sn.match(/[A-Za-z0-9]{5,8}/)){
			alert('非法的货号');
			return ;
		}
		location.href=url;
		
	});
	$('.stock_search_goods_sn').click(function(){
		$('input[name=goods_sn]').val('');
		$('input[name=goods_sn]').blur(function(){
			if($('input[name=goods_sn]').val() == "")$(this).val("请输入货号");
		});
	});
});