$(function(){
	//搜索层时间选择
	$('#start_time , #end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//添加供应商信息
	$('input[type=button][name=save]').click(function(){

		var distributor_name = $('input[name=distributor_name]').val();
		
		var error = '';
		var ar_error = new Array();
		
		if(distributor_name == '') {
			error += '请填写分销商';
			ar_error.push($('input[name=distributor_name]'));
		}
		
		if(error != '') {	
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
			return;
		}
		
		var email = $('input[name=contact_email]').val();
		var name = $('input[name=contact_name]').val();
		var tel = $('input[name=contact_tele]').val();
		var address = $('input[name=contact_address]').val();
		var status = $('input[name=status]:checked').val();
	
		$.ajax({
			type:'POST',
			dataType:'json',
			url:'distributors.php?do=AddDistributorInfo',
			data:'distributor_name='+distributor_name+'&email='+email+'&name='+name+'&tel='+tel+'&address='+address+'&status='+status,
			success:function(msg){
				alert(msg.info);
				
				if(msg.status) {
					window.location.reload();
				}
				
				if(!msg.status && msg.url){
					window.location.href = url;
				} 
			}
		});
	});
	
	//添加供应商信息
	$('input[type=button][name=update]').click(function(){

		var distributor_name = $('input[name=distributor_name]').val();
		
		var error = '';
		var ar_error = new Array();
		
		if(distributor_name == '') {
			error += '请填写分销商';
			ar_error.push($('input[name=distributor_name]'));
		}
		
		if(error != '') {	
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
			return;
		}
		
		var email = $('input[name=contact_email]').val();
		var name = $('input[name=contact_name]').val();
		var tel = $('input[name=contact_tele]').val();
		var address = $('input[name=contact_address]').val();
		var status = $('input[name=status]:checked').val();
		var distributor_id = $('input[name=distributor_id]').val();
		var agency_id = $('select[name=agency_id]').val();
		
		$.ajax({
			type:'POST',
			dataType:'json',
			url:'distributors.php?do=updateDistributorInfo',
			data:'distributor_name='+distributor_name+'&email='+email+'&name='+name+'&tel='+tel+'&address='+address+'&status='+status+'&distributor_id='+distributor_id+'&agency_id='+agency_id,
			success:function(msg){
				alert(msg.info);
				
				if(msg.status) {
					window.location.reload();
				}
				
				if(!msg.status && msg.url){
					window.location.href = url;
				} 
			}
		});
	});
	
	//链接到添加供应商
	$('#new_add').click(function(){
		window.location.href = 'distributors.php';
	});
	
	//链接到供应商管理
	$('#main_right input[name=distributor_admin]').click(function(){
		window.location.href = 'distributors.php?do=distributorAdmin';
	});
});
