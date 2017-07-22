$(function(){
	//搜索层时间选择
	$('#start_time , #end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//添加供应商信息
	$('input[type=button][name=save]').click(function(){

		var supplier_name = $('input[name=supplier_name]').val();
		
		var error = '';
		var ar_error = new Array();
		
		if(supplier_name == '') {
			error += '请填写供应商';
			ar_error.push($('input[name=supplier_name]'));
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
		var code = $('input[name=supplier_code]').val();
		var name = $('input[name=contact_name]').val();
		var tel = $('input[name=contact_tele]').val();
		var mobile = $('input[name=contact_mobile]').val();
		var address = $('input[name=contact_address]').val();
		var zip = $('input[name=contact_zipcode]').val();
		var status = $('input[name=status]:checked').val();
		
		
	
		$.ajax({
			type:'POST',
			dataType:'json',
			url:'supplier.php?do=AddSupplierInfo',
			data:'supplier_name='+supplier_name+'&email='+email+'&code='+code+'&name='+name+'&tel='+tel+'&mobile='+mobile+'&address='+address+'&zip='+zip+'&status='+status,
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
		window.location.href = 'supplier.php';
	});
	
	//链接到供应商管理
	$('#main_right input[name=supplier_admin]').click(function(){
		window.location.href = 'supplier.php?do=supplierAdmin';
	});
});
