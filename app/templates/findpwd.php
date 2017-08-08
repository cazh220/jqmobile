<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="public/mobile_themes/themes/skyd.min.css" />
<link rel="stylesheet" href="public/mobile_themes/themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
</head>
<body>

<div data-role="page" data-theme="p">
  <div data-role="header" data-position="fixed"><a href="#" class="ui-btn ui-corner-all ui-icon-carat-l ui-btn-icon-notext" data-rel="back">后退</a>
  <h1>找回密码</h1>
  </div>

  <div data-role="content">

    <form method="post" action="user.php?do=updatePwd" data-ajax="false"  onsubmit="return check()">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2"><input type="text" name="mobile" id="mobile" placeholder="输入手机号"></td>
			</tr>
			<tr>
				<td width="60%"><input type="text" name="vcode" id="vcode" placeholder="输入验证码"></td>
				<td width="40%" style="text-align:center"><input type="button" data-role="none" value="获取验证码" class="vcode_button"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password1" id="password1" placeholder="输入新密码" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password2" id="password2" placeholder="确认密码" autocomplete="off"></td>
			</tr>
		</table>
        
		
		
		
		<input type="submit" value="提交">
    </form>
  </div>
</div>
<script type="text/javascript">
{literal}
function check()
{
	var mobile = $("#mobile").val();
	if (mobile == '') {
		alert('请填写手机号');
		return false;
	}

	var code = $("#vcode").val();
	if (code == '') {
		alert('请填写验证码');
		return false;
	}

	var password1 = $("#password1").val();
	if (password1 == '') {
		alert('请填写密码');
		return false;
	}

	var password2 = $("#password2").val();
	if (password2 == '') {
		alert('请填写确认密码');
		return false;
	}

	if (password2 != password1) {
		alert('密码不一致');
		return false;
	}

	return true;
}
{/literal}
</script>
</body>
</html>