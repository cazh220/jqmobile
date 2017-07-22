<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>找回密码</h1>
  </div>

  <div data-role="content" data-theme="b">
	<div class="blank"></div>
	<div class="blank"></div>
    <form method="post" action="find.php">
		
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

</body>
</html>