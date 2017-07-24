<?php /* Smarty version 2.6.10, created on 2017-07-25 01:19:30
         compiled from register_t.php */ ?>
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
  <h1>会员注册</h1>
  </div>

  <div data-role="content" data-theme="c">

    <form method="post" action="find.php">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2" style="text-align:center">完善资料</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="realname" id="realname" placeholder="注册人真实姓名"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password1" id="password1" placeholder="请输入密码" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password2" id="password2" placeholder="确认密码" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup">
						<label for="techer">技工</label>
						<input type="radio" name="typer" id="techer" value="techer" checked="checked">
						<label for="doctor">医生</label>
						<input type="radio" name="typer" id="doctor" value="doctor">	
				    </fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="email" id="email" placeholder="邮箱"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="company_name" id="company_name" placeholder="单位全称"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="job" id="job" placeholder="部门职位"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="date" name="create_time" id="create_time" value="成立时间"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="employee_num" id="employee_num" placeholder="椅位数/员工数"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup" data-type="horizontal">
						<label for="province">选择省：</label>
						<select name="province" id="province">
						  <option value="1">上海</option>
						  <option value="2">江苏</option>
						</select>

						<label for="city">选择市：</label>
						<select name="city" id="city">
						  <option value="1">上海</option>
						  <option value="2">南京</option>
						</select>
						<label for="district">选择区：</label>
						<select name="district" id="district">
						  <option value="1">黄浦区</option>
						  <option value="2">杨浦区</option>
						</select>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="address" id="address" placeholder="详细地址"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="file" name="file" id="file" value="单位图片"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><textarea name="addinfo" id="info" placeholder="单位介绍"></textarea></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<label for="agree">我已阅读并接受<a href="#" target="_blank">《用户注册协议》</a></label>
					<input type="checkbox" name="agree" id="agree" value="agree">
				</td>
			</tr>
			
			
		</table>

		<input type="submit" value="注册">
    </form>
  </div>
</div>

</body>
</html>