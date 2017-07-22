// JavaScript Document
$(function(){
	//时间选择
/*	$('#start_time , #end_time').datepicker(
		changeMonth: true,
		changeYear: true
	});*/
	
	//提示框alert
	$.fx.speeds._default = 500;
/*	$('#dialog').dialog({
		autoOpen: false,
		show: 'explode',
		hide: 'explode'
	});*/
	
	//选中表格中行
	$('#list input[name=checkbox]').click(function(){									   
		var tr = $(this).parent().parent();

		if(!tr.hasClass('checked'))
		{	
			if(!tr.hasClass('hidden'))
			{
				tr.addClass('checked').children().css('backgroundColor' , '#FC9');
			}
		}
		else
		{	
			tr.children().not('.fill').css('backgroundColor' , '#F3F9FA');
			tr.children('.fill').css('backgroundColor' , '#FDFDBB');
			tr.removeClass('checked');
		}
	});
	
	/*$('td').has('input[type=checkbox]').siblings().click(function(){
		$(this).parent().find('input[type=checkbox]').click();													  
	});*/
	
	//全选反选
	$('#selectAll').hover(function(){
		$(this).css('color' , '#f00');							   
	} , function(){
		$(this).css('color' , '#666');	
	});
	$('#selectAll').toggle(function(){
		$('#list').find('input[name=checkbox]').each(function(){
			if(!$(this).attr('checked'))
			{
				$(this).click();
			}
		});
		$('#selectAll').text('反选');
	} , function(){
		$('#list').find('input[name=checkbox]').each(function(){
			if($(this).attr('checked'))
			{
				$(this).click();
			}
		});
		$('#selectAll').text('全选');
	});
	

	//必填项背景提示
	$('.fill').css('backgroundColor', '#FFC');
	
	//当前行背景提示
	$('tr:not(".theader")').hover(function(){
		if(!$(this).is('.checked'))
		{
			$(this).children().css('backgroundColor' , '#C5FEE1');
		}
	}, function(){
		if(!$(this).is('.checked'))
		{
			$(this).children().not('.fill').css('backgroundColor' , '#F3F9FA');
			$(this).children('.fill').css('backgroundColor' , '#FDFDBB');
		}
	});
	
	//必填项click功能
	$('td.fill').click(function(){
		if($(this).children('input').length == 0)
		{	
			var text = $(this).text();
			var val  = '' , stock = 0;
			$(this).text('');
			$('<input type="text" class="must_fill" />').val(text).prependTo(this).focus().blur(function(){
					val = $.trim($(this).val());
					
					if($(this).parent().hasClass('allocate_num')) {
						val = parseInt(val);

						if (isNaN(val)) {
							alert('对不起，您输入了非法数字！');
							val = 0;
						}
						
						stock = $.trim($(this).parent().parent().find('.instock_num').text());
						
						if(stock < val) {
							alert('库存不足!');
							val = stock;
						}
					}

					$(this).parent().empty().text(val);
					
					//ajax方法需要在页面js中指定
					if(!!ajax)
					{
						ajax($(this));
					}
			});
		}
	});
	
	//绑定回车键事件
	$('td.fill').keydown(function(event){
		if(event.keyCode == 13)
		{
			$(this).find('input:eq(0)').blur();
		}
	});
	
	//添加一行产品录入
	$('div.add_row').hover(function(){
		$(this).css('color', '#f00');						 
	}, function(){
		$(this).css('color', '#000');
	});
	
	$('div.add_row').click(function(){
		var err = 0;
		var tr = $('#list tr.list_tr:last');
		
		tr.each(function(){
			if(!$(this).find('td:eq(1)').text())
			{
				err = 1;
				return false;
			}
		});
		if(err == 1)
		{
			return false;	
		}
		
		
		var id = parseInt(tr.find('.id span').text());
		id = !id ? 1 : parseInt(id+1);
		$('tr.hidden').clone(true).removeClass('hidden').addClass('list_tr').appendTo('table#list').find('td:eq(0)').append('<span>' + id + '</span>')
			.end()
			.animate({borderBottomColor: '#f00'}, 100)
			.animate({borderBottomColor: '#ff0'}, 100)
			.animate({borderBottomColor: '#f00'}, 100)
			.animate({borderBottomColor: '#fff'}, 100);
	});
		
	//删除表格中
	$('input[name=remove]').click(function(){
		$('#list tr input[type=checkbox]:checked').each(function(){
			tr = $(this).parent().parent();	
			tr.remove();
			//tr.children().eq(1).text() ? tr.addClass('hide') : tr.remove();
		});
		
	});
	
	//只能输入数字
	$('input.numeric').keyup(function(){
		  var num = $(this).val();
		  if(num != parseInt(num) || num.indexOf(".") != -1 || num < 0)
		  {
			  $(this).val('');
		  }
	});
	
	
});

var succImg = 'public/images/succ.gif';
var erroImg = 'public/images/erro.gif';
var warnImg = 'public/images/warning.gif';
var infoImg = 'public/images/information.gif';

//截取字符串两端空格
String.prototype.trim = function()
{
   return this.replace(/(^\s+)|\s+$/g,"");
}

//当前行背景提示
function  shworRowBg(){
	$('tr:not(".theader")').hover(function(){
		if(!$(this).is('.checked'))
		{
			$(this).children().css('backgroundColor' , '#C5FEE1');
		}
	}, function(){
		if(!$(this).is('.checked'))
		{
			$(this).children().not('.fill').css('backgroundColor' , '#F3F9FA');
			$(this).children('.fill').css('backgroundColor' , '#FDFDBB');
		}
	});
}

//添加一行
function addRow() {
	var err = 0;
	var tr = $('#list tr.list_tr:last');
		
	tr.each(function(){
		if(!$(this).find('td:eq(1)').text()) {
			err = 1;
			return false;
		}
	});
	if(err == 1){
		return false;	
	}
		
		
	var id = parseInt(tr.find('.id span').text());
	id = !id ? 1 : parseInt(id+1);
	$('tr.hidden').clone(true).removeClass('hidden').addClass('list_tr').appendTo('table#list').find('td:eq(0)').append('<span>' + id + '</span>')
		.end()
		.animate({borderBottomColor: '#f00'}, 100)
		.animate({borderBottomColor: '#ff0'}, 100)
		.animate({borderBottomColor: '#f00'}, 100)
		.animate({borderBottomColor: '#fff'}, 100);
}

//移除一行
function romoveRow(obj) {
	tr = $(obj).parent().parent();	
	tr.remove();
}

//检测是否是数字
function IsNum(s) {
    if(s!=null){
        var r,re;
        re = /\d*/i; //\d表示数字,*表示匹配多个数字
        r = s.match(re);
        return (r==s)?true:false;
    }
    return false;
}

//库存预警分类
$(function(){
 $("select[name=level]").change(function(){
    var id = $(this).val();
    if(id == "11"){
        $('.red').parent('tr').show();
        $('.green').parent('tr').show();
        return;
    }
    if(id == "12"){
        $('.red').parent('tr').show();
        $('.green').parent('tr').hide(); 
        return;
    }
    if(id == "13"){
        $('.green').parent('tr').show();
        $('.red').parent('tr').hide();
        return;
    }
    if(id == "14"){
       $('.red').parent('tr').show();
       $('.green').parent('tr').show();
       return; 
    }
 });
});
