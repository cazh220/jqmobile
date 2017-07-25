<?php /* Smarty version 2.6.10, created on 2017-07-25 23:27:39
         compiled from register.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script type="text/javascript">
<?php echo '
function send_sms()
{
	var mobile = $("#mobile").val();
	if (mobile == \'\')
	{
		alert("请输入手机号");
		return false;
	}
	$.ajax({
		url:"user.php?do=SendSms",
		data:\'mobile=\'+mobile,
		method:\'get\',
		dataType:\'json\',
		success:function(msg){
			
		}
	});
}
'; ?>

</script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>会员注册</h1>
  </div>

  <div data-role="content" data-theme="b">
	<div class="blank"></div>
	<div class="blank"></div>
    <form method="post" action="user.php?do=ValidateMobile" data-ajax="false">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2"><input type="text" name="mobile" id="mobile" placeholder="输入手机号"></td>
			</tr>
			<tr>
				<td width="60%"><input type="text" name="vcode" id="vcode" placeholder="输入验证码"></td>
				<td width="40%" style="text-align:center"><input type="button" data-role="none" value="获取验证码" class="vcode_button" onclick="send_sms()"></td>
			</tr>
		</table>

		<input type="submit" value="下一步">
    </form>
  </div>
</div>

</body>
</html>