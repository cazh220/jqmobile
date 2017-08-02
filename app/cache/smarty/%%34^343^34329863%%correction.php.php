<?php /* Smarty version 2.6.10, created on 2017-08-02 18:38:55
         compiled from correction.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<script type="text/javascript">
<?php echo '
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: \'msg\'
    ,time: 2 //2秒后自动关闭
  });
}

function send()
{
	var errorinfo = $("#error_info").val();
	if (errorinfo == \'\')
	{
		show(\'请填写错误信息\');
		return false;
	}
	
	var correction = $("#correct_info").val();
	if (correction == \'\')
	{
		show(\'请填写正确信息\');
		return false;
	}
	console.log(errorinfo);
	$.ajax({
		url:\'doctor.php?do=writecorrction\',
		data:\'errorinfo=\'+errorinfo+\'&correction=\'+correction,
		method:\'POST\',
		success:function(msg){
			
		}
		
	});

	//$("#correction_form").submit();
}

'; ?>
	
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
	  <input type="hidden" id="qrcode" name="qrcode" value="<?php echo $this->_tpl_vars['qrcode']; ?>
"/>
      <div style="text-align: right; width: 100%; float: left;"><a href="#" data-role="button" onclick="send()" >发送</a></div>  
    </form>
  </div>
</div>

</body>
</html>