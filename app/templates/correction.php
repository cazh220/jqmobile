<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<script type="text/javascript">
{literal}
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: 'msg'
    ,time: 2 //2秒后自动关闭
  });
}

function send()
{
	var errorinfo = $("#error_info").val();
	if (errorinfo == '')
	{
		show('请填写错误信息');
		return false;
	}
	
	var correction = $("#correct_info").val();
	if (correction == '')
	{
		show('请填写正确信息');
		return false;
	}
	
	var qrcode = $("#qrcode").val();
	console.log(errorinfo);
	
	$.ajax({
		url:'doctor.php?do=writecorrction',
		data:'errorinfo='+errorinfo+'&correction='+correction+'&qrcode='+qrcode,
		method:'GET',
		dataType:'json',
		success:function(msg){
			if(msg.status)
			{
				//跳转到
				layer.open({
				    content: '您的纠错信息已提交，我们会尽快通知技工会员更正相关信息，感谢您的支持！'
				    ,btn: '我知道了'
				    ,end:function(){
				    	window.location.href="user.php?do=ucenter&user_id="+msg.result.user_id;
				    }
				});
			}
			else
			{
				show('failed');
				return false;
			}
		}
		
	});
	

	//$("#correction_form").submit();
}

{/literal}	
</script>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>纠错</h1>
  </div>

  <div data-role="content" data-theme="c">
    <form id="correction_form" method="post" action="doctor.php?do=writecorrction" data-ajax="false">
      <label for="fname">错误的信息：</label>
      <textarea id="error_info" name="error_info" placeholder="请输入错误的选项：如患者姓名、医疗单位"></textarea>
      <br><br>
      <label for="fname">正确的信息：</label>
      <textarea id="correct_info" name="correct_info" placeholder="请输入正确的信息：如张**、上海**医院"></textarea>
	  <input type="hidden" id="qrcode" name="qrcode" value="{$qrcode}"/>
      <div style="text-align: right; width: 100%; float: left;"><a href="#" data-role="button" onclick="send()" >发送</a></div>  
    </form>
  </div>
</div>

</body>
</html>