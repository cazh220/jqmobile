<?php /* Smarty version 2.6.10, created on 2017-07-25 23:27:44
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

    <form method="post" action="user.php?do=register" data-ajax="false" enctype="multipart/form-data">
		
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
						<?php if ($this->_tpl_vars['province']): ?>
							<?php $_from = $this->_tpl_vars['province']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
							<option value="<?php echo $this->_tpl_vars['item']['id']; ?>
"><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
						    <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</select>

						<label for="city">选择市：</label>
						<select name="city" id="city">
						  <option value="0">请选择</option>
						</select>
						<label for="district">选择区：</label>
						<select name="district" id="district">
						  <option value="0">请选择</option>
						</select>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="address" id="address" placeholder="详细地址"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="file" name="cfile" id="cfile" value="单位图片"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><textarea name="addinfo" id="info" placeholder="单位介绍"></textarea></td>
			</tr>
			<tr>
				<td width="20%" style="text-align:right">
					<input type="checkbox" name="agree" id="agree" value="agree" style="zoom:200%;">
					
				</td>
				<td width="80%" style="text-align:left">
					我已阅读并接受<a href="user.php?do=ViewXy" style="text-decoration:none">《用户注册协议》</a>
					
				</td>
			</tr>
			
			
		</table>

		<input type="submit" id="register" value="注册" >
    </form>
  </div>
</div>
<script type="text/javascript">
<?php echo '
$("#province").change(function(){
	var id = $(this).val();
	$.ajax({
		url:\'area.php?do=getcity\',
		method:\'get\',
		data:\'province_id=\'+id,
		dataType:\'json\',
		success:function(msg){
			var str = \'<option value="0">请选择</option>\';
			if (msg.status==1)
			{
				$.each(msg.list, function(i, n){
					str += "<option value=\'"+n.id+"\'>"+n.name+"</option>";
				});
			}
			$("#city").html(str);
		}
	});
});
$("#city").change(function(){
	var id = $(this).val();
	$.ajax({
		url:\'area.php?do=getdistrict\',
		method:\'get\',
		data:\'city_id=\'+id,
		dataType:\'json\',
		success:function(msg){
			var str = \'<option value="0">请选择</option>\';
			if (msg.status==1)
			{
				$.each(msg.list, function(i, n){
					str += "<option value=\'"+n.id+"\'>"+n.name+"</option>";
				});
			}
			$("#district").html(str);
		}
	});
});

$("#register").click(function(){
	
	var agree = $("#agree").attr("checked");
	if (typeof(agree)=="undefined")
	{
		alert("你还没有同意协议");
		return false;
	}
	
	
	var realname = $("#realname").val();
	if (realname == \'\')
	{
		alert("请填写真实姓名");
		return false;
	}
	
	var password1 = $("#password1").val();
	if (password1 == \'\')
	{
		alert("请填写密码");
		return false;
	}
	
	var password2 = $("#password2").val();
	if (password2 == \'\')
	{
		alert("请填写确认密码");
		return false;
	}
	
	if (password2 != password1)
	{
		alert("密码不一致");
		return false;
	}
	
	var email = $("#email").val();
	if (email == \'\')
	{
		alert("请填写邮箱");
		return false;
	}
	
	var company_name = $("#company_name").val();
	if (company_name == \'\')
	{
		alert("请填写单位名称");
		return false;
	}
	
	var job = $("#job").val();
	if (job == \'\')
	{
		alert("请填写职位");
		return false;
	}
	
	var create_time = $("#create_time").val();
	if (create_time == \'\')
	{
		alert("请填写成立时间");
		return false;
	}
	
	var employee_num = $("#employee_num").val();
	if (employee_num == \'\')
	{
		alert("请填写员工数");
		return false;
	}
	
	var district = $("#district").val();
	if (district == \'\')
	{
		alert("请选择省市区");
		return false;
	}
	
	var address = $("#address").val();
	if (address == \'\')
	{
		alert("请填写地址");
		return false;
	}
	
	var file = $("#cfile").val();
	if (file == \'\')
	{
		alert("请选择图片");
		return false;
	}
	
	var info = $("#info").val();
	if (info == \'\')
	{
		alert("请填写单位介绍");
		return false;
	}

});

'; ?>

</script>
</body>
</html>