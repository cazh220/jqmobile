// JavaScript Document


$(function(){

		   
	//������Ʒ���Ų�ѯ��Ʒid
	$('#transfer').click(function(event){

		//var goods_sn = $(this).val();
		var agency       = $('#main_up select[name=agency]').val();
		var rate         = $('#main_up select[name=rate]').val();
		
		
		$.ajax({
			   type:'GET',
			   dataType: "json",
			   url:'transfer.php?do=TransferStock',
			   data:'agency='+agency+'&rate='+rate,
			   success:function(msg){

				   	if(msg.status){
						alert(msg.message);

					}else{
						alert(msg.message);
					}
				}
		});
	});	
	


});

