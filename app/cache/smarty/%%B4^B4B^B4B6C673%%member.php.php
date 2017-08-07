<?php /* Smarty version 2.6.10, created on 2017-08-07 16:02:36
         compiled from member.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>个人资料</h1>
  </div>

  <div data-role="content" data-theme="c">

    <form method="post" action="user.php?do=updateuser" data-ajax="false" enctype="multipart/form-data">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2"><input type="text" name="realname" id="realname" placeholder="真实姓名" value="<?php echo $this->_tpl_vars['mine']['realname']; ?>
"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup">
						<label for="techer">技工</label>
						<input type="radio" name="typer" id="techer" value="1" <?php if ($this->_tpl_vars['mine']['user_type'] == 1 || ! $this->_tpl_vars['mine']['user_type']): ?>checked="checked"<?php endif; ?>>
						<label for="doctor">医生</label>
						<input type="radio" name="typer" id="doctor" value="2" <?php if ($this->_tpl_vars['mine']['user_type'] == 2): ?>checked="checked"<?php endif; ?>>	
				    </fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="email" id="email" placeholder="邮箱" value="<?php echo $this->_tpl_vars['mine']['email']; ?>
"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="company_name" id="company_name" placeholder="单位全称" value="<?php echo $this->_tpl_vars['mine']['company_name']; ?>
"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="job" id="job" placeholder="部门职位" value="<?php echo $this->_tpl_vars['mine']['position']; ?>
"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="date" name="create_time" id="create_time" value="<?php echo $this->_tpl_vars['mine']['birthday']; ?>
"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="employee_num" id="employee_num" placeholder="椅位数/员工数" value="<?php echo $this->_tpl_vars['mine']['persons_num']; ?>
"></td>
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
" <?php if ($this->_tpl_vars['item']['id'] == $this->_tpl_vars['mine']['province']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
						    <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</select>

						<label for="city">选择市：</label>
						<select name="city" id="city">
						<?php if ($this->_tpl_vars['city']): ?>
							<?php $_from = $this->_tpl_vars['city']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
							<option value="<?php echo $this->_tpl_vars['item']['id']; ?>
" <?php if ($this->_tpl_vars['item']['id'] == $this->_tpl_vars['mine']['city']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
						  <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</select>
						<label for="district">选择区：</label>
						<select name="district" id="district">
						<?php if ($this->_tpl_vars['district']): ?>
							<?php $_from = $this->_tpl_vars['district']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
							<option value="<?php echo $this->_tpl_vars['item']['id']; ?>
" <?php if ($this->_tpl_vars['item']['id'] == $this->_tpl_vars['mine']['district']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']['name']; ?>
</option>
						  <?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
						</select>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="address" id="address" placeholder="详细地址" value="<?php echo $this->_tpl_vars['mine']['company_addr']; ?>
"></td>
			</tr>
			<tr>
				<td width="20%">单位图片：</td>
				<td width="80%"><img src="/public/upload/data/<?php echo $this->_tpl_vars['mine']['head_img']; ?>
" width="150px" height="150px"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="file" name="cfile" id="cfile" value="单位图片"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><textarea name="addinfo" id="info" placeholder="单位介绍"><?php echo $this->_tpl_vars['mine']['company_info']; ?>
</textarea></td>
			</tr>
			
			
		</table>

		<input type="button" id="perfect" value="提交" >
		<input type="hidden" id="mobile" name="mobile" value="<?php echo $this->_tpl_vars['mine']['mobile']; ?>
" >
		<input type="hidden" id="province" name="province" value="<?php echo $this->_tpl_vars['mine']['province']; ?>
" >
		<input type="hidden" id="city" name="city" value="<?php echo $this->_tpl_vars['mine']['city']; ?>
" >
		<input type="hidden" id="district" name="district" value="<?php echo $this->_tpl_vars['mine']['district']; ?>
" >
		<input type="hidden" id="username" name="username" value="<?php echo $this->_tpl_vars['mine']['username']; ?>
" >
		<input type="hidden" id="company_pic" name="company_pic" value="<?php echo $this->_tpl_vars['mine']['head_img']; ?>
" >
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

function show(note)
{
	//提示
  layer.open({
    content: note
    ,btn: \'我知道了\'
    ,yes:function(){
    	window.location.href="user.php?do=ucenter"
    }
  });
}

$("#perfect").click(function(){
	
	
		
	var realname = $("#realname").val();
	if (realname == \'\')
	{
		show(\'请填写真实姓名\');
		return false;
	}
	
		
	var email = $("#email").val();
	if (email == \'\')
	{
		show("请填写邮箱");
		return false;
	}
	
	var company_name = $("#company_name").val();
	if (company_name == \'\')
	{
		show("请填写单位名称");
		return false;
	}
	
	var job = $("#job").val();
	if (job == \'\')
	{
		show("请填写职位");
		return false;
	}
	
	var create_time = $("#create_time").val();
	if (create_time == \'\')
	{
		show("请填写成立时间");
		return false;
	}
	
	var employee_num = $("#employee_num").val();
	if (employee_num == \'\')
	{
		show("请填写员工数");
		return false;
	}
	
	var district = $("#district").val();
	if (district == \'\')
	{
		show("请选择省市区");
		return false;
	}
	
	var address = $("#address").val();
	if (address == \'\')
	{
		show("请填写地址");
		return false;
	}
	
	var file = $("#company_pic").val();
	if (file == \'\')
	{
		show("请选择图片");
		return false;
	}
	
	var info = $("#info").val();
	if (info == \'\')
	{
		show("请填写单位介绍");
		return false;
	}
	
	var user_type;
	if($("#techer").attr("checked") == \'checked\')
	{
		user_type = 1;
	}
	else
	{
		user_type = 2;
	}
	
	var province;
	province = $("#province").val();
	var city;
	city = $("#city").val();

	
	
	var data = "realname="+realname+"&user_type="+user_type+"&email="+email+"&company_name="+company_name+"&job="+job+"&create_time="+create_time+"&employee_num="+employee_num+"&address="+address+"&company_pic="+file+"&info="+info+"&province="+province+"&city="+city+"&district="+district;
	$.ajax({
		type:"GET",
		url:"user.php?do=updateuser",
		data:data,
		dataType:"json",
		success:function(msg){
			show(msg.message);
			if(msg.status)
			{
				show(msg.message);
				
			}
		}
	});
	
});

'; ?>

</script>
</body>
</html>