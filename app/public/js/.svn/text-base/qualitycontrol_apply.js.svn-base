// JavaScript Document
$(function() {
	//货号列下面的input输入框添加class ， 为ajax做准备
	$('td.fill').click(function() {
		if($(this).hasClass('goods_sn')) {
			$(this).addClass('focus').find('input').addClass('goods_sn');
		}
	});
	
	$('input.convert_num').keyup(function(event){
		evt = (event) ? event : ((window.event) ? window.event : "")
		keyCode = evt.keyCode ? evt.keyCode : (evt.which ? evt.which : evt.charCode);
		
		var num = $(this).val();
		var reg = /^\d+$/;
		if(!reg.test(num))
		{
			$(this).val('');
			$(this).focus();
			return false;		
		}
	});	

	$('#operate_convert input[name=submit]').click(function() {
		//获取人物，时间
		var act = $('#main_up input[type=hidden][name=act]').val();
		var create_user = $('#main_up select[name=convert_person]').val();
		var create_time = $('#main_up input[type=text][name=create_time]').val();
		var description = $('textarea.description').val();
		
		var error         = '';
		var ar_error      = new Array;
		
		if(act == ''){
			alert('网络运行异常，转换类型。');
			return;
		}
		
		if(create_user == 0){
			error += '请选择转换操作者' + '\n';	
			ar_error.push($('#main_up select[name=convert_person]'));
		}
		
		var data = "act="+act+"&create_user="+create_user+"&create_time="
					+create_time+"&description="+description;
		//错误提示
		if(error != '')
		{	
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
			return false;
		}
		
		//提取商品信息
		var ar_goodsinfo = new Array(), a = new Array() , b = new Array();
		var s_goodsinfo  = '';
		
		//获取提货商品详细
		$('#list tr.checked').each(function(){
				$(this).children('td:gt(0)').each(function(){	   
					if($(this).hasClass('convert_num')) {
						$(this).find('td.size_info').each(function(){
							b.push($(this).find('span').text() + '=' + $(this).find('input').val());
						});
						a.push(b.join('@'));
						b.length = 0; //清空数组
					}
					//各尺寸下对应的数量
					else{
						a.push($(this).text());
					}
				});
			
				ar_goodsinfo.push(a);
				a = new Array();
		});

//		$(this).attr('disabled',true);

		$.ajax({
			type : "POST",
			dataType : "json",
			url : "qualitycontrol.php?do=convertProcessing",
			data : data+"&goods_info="+ar_goodsinfo.join('@@'),
			success : function(msg) {
				alert(msg.info);
				
				if(msg.status){
//			      window.parent.frames["manFrame"].location.reload();
				  window.location.reload();  
			   }
			}
		});
	})
});

var ajax = function($obj) {
	var sn = $obj.val();
	if(sn == '') {
		$('td.focus').text('');
		$('td.focus').removeClass('focus');
		return false;
	}
	
	//如果不是goods_sn ， return
	td = $('td.focus');
	if(!td.hasClass('goods_sn')) return ;
	
	//如果goods_sn已经存在，return
	var i = 0, err_sn = '';
	$('#list td.goods_sn').each(function(){
		if( $(this).text() == sn ) {	
			i += 1;
			
			if(i>1) {
				err_sn = '输入的货号：'+sn+'已存在！';
				$('td.focus').text('');
				return false;
			}
		}	
	});
	if(err_sn) {
		alert(err_sn);
		
		$('#list td.goods_sn').each(function(){
			if( $(this).text() == sn )
			{
				if( $(this).parent().hasClass('hide') )
				{
					$('#right span.trash_sn').each(function(){
						if( $(this).text() == sn )
						{
							$(this)
								.animate({backgroundColor:'#ff0' , fontSize:14}, 500)
								.animate({backgroundColor:'#f00' , fontSize:12 , color:'#ff0'}, 500)
								.animate({backgroundColor:'#ff0' , fontSize:14 , color:'#f00'}, 500)
								.animate({backgroundColor:'#eee' , fontSize:12}, 500);
						}
					});	
				}
				else
				{
					$(this)
					.animate({backgroundColor:'#ff0'    , fontSize:14 , color:'#f00'}, 500)
					.animate({backgroundColor:'#fdfdbb' , fontSize:12 , color:'#ff0'}, 500)
					.animate({backgroundColor:'#ff0'    , fontSize:14 , color:'#f00'}, 500)
					.animate({backgroundColor:'#fdfdbb' , fontSize:12 , color:'#666'}, 500);
				}
			}									 
		});
		
		return false;
	}
	
	$.ajax({
		type : "POST",
		dataType : "json",
		url : "goods.php?do=GetGoodsInfo",
		data : "goodssn=" + sn + "&agency=1",
		success : function(msg) {
			if(!msg.status)
			{
				alert(msg.info);
				$('td.focus').siblings('td:gt(0)').andSelf().text('');
				$('td.focus').removeClass('focus');
				return;
			}
			
				//对应大小需要转换的数量
				i = '' ; v = '';
				size = '<table style="width:100%;"><tr>';
				$.each(msg.info.size , function(i , v){
					size += '<td class="size_info"><span>'+v+'</span><br /><input type="text" name="'+v+'" value="0" onblur="check(this)"></td>';
				});
				size += '</tr></table>';
			
				td.siblings('.agency').text("蓝橙仓库");
				td.siblings('.goods_name').text(msg.info.goods_name);
				td.siblings('.goods_color').text(msg.info.color);
				td.siblings('.goods_size').text(msg.info.size);
				td.siblings('.convert_num').html(size);
				td.removeClass('focus');
			//添加新行
			addRow();
		}
	});
};

//检测转换数量的合法性
var check = function(obj){
	$obj = $(obj);
	
	var size   = $obj.attr('name');   //当前转换尺寸
	var num    = $obj.val();          //当前转换数量

	if(num != parseInt(num) || num < 0) {
		alert('对不起 , 此处请输入非负整数');
		
		$obj.val(0);
		$obj.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#ff0'}, 500)
			.animate({backgroundColor:'#f00'}, 500)
			.animate({backgroundColor:'#fdfdbb'}, 500);	
		return false;
	}
};

