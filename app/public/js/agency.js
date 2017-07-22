$(function(){
	//搜索层时间选择
	$('#start_time , #end_time').datepicker({
		changeMonth: true,
		changeYear: true
	});
	
	//添加仓库信息
	$('input[type=button][name=save]').click(function(){

		var agency_name = $('input[name=agency_name]').val();
		
		var error = '';
		var ar_error = new Array();
		
		if(agency_name == '') {
			error += '请填写仓库名称';
			ar_error.push($('input[name=agency_name]'));
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
		
		var agency_admin = $('input[name=agency_admin]').val();
		var tel = $('input[name=contact_tele]').val();
		var mark = $('#agency_mark').val();
		var status = $('input[name=status]:checked').val();

		$.ajax({
			type:'POST',
			dataType:'json',
			url:'agency.php?do=InsertAgency',
			data:'agency_name='+agency_name+'&agency_admin='+agency_admin+'&tel='+tel+'&mark='+mark+'&status='+status,
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
	
	//链接到添加仓库页面
	$('#new_add').click(function(){
		window.location.href = 'agency.php?do=addAgency';
	});
});
