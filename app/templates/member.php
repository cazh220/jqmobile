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

    <form method="post" action="user.php?do=register" data-ajax="false" enctype="multipart/form-data">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2"><input type="text" name="realname" id="realname" placeholder="真实姓名" value="{$mine.realname}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup">
						<label for="techer">技工</label>
						<input type="radio" name="typer" id="techer" value="1" {if $mine.user_type == 1 || !$mine.user_type}checked="checked"{/if}>
						<label for="doctor">医生</label>
						<input type="radio" name="typer" id="doctor" value="2" {if $mine.user_type == 1}checked="checked"{/if}>	
				    </fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="email" id="email" placeholder="邮箱" value="{$mine.email}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="company_name" id="company_name" placeholder="单位全称" value="{$mine.company_name}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="job" id="job" placeholder="部门职位" value="{$mine.position}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="date" name="create_time" id="create_time" value="成立时间" value="{$mine.birthday}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="employee_num" id="employee_num" placeholder="椅位数/员工数" value="{$mine.persons_num}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup" data-type="horizontal">
						<label for="province">选择省：</label>
						<select name="province" id="province">
						{if $province}
							{foreach from=$province item=item key=key}
							<option value="{$item.id}">{$item.name}</option>
						    {/foreach}
						{/if}
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
				<td width="100%" colspan="2"><input type="text" name="address" id="address" placeholder="详细地址" value="{$mine.company_addr}"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="file" name="cfile" id="cfile" value="单位图片"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><textarea name="addinfo" id="info" placeholder="单位介绍" value=""></textarea></td>
			</tr>
			
			
		</table>

		<input type="submit" id="register" value="提交" >
		<input type="hidden" id="mobile" name="mobile" value="{$mobile}" >
		<input type="hidden" id="username" name="username" value="{$username}" >
    </form>
  </div>
</div>
<script type="text/javascript">
{literal}
$("#province").change(function(){
	var id = $(this).val();
	$.ajax({
		url:'area.php?do=getcity',
		method:'get',
		data:'province_id='+id,
		dataType:'json',
		success:function(msg){
			var str = '<option value="0">请选择</option>';
			if (msg.status==1)
			{
				$.each(msg.list, function(i, n){
					str += "<option value='"+n.id+"'>"+n.name+"</option>";
				});
			}
			$("#city").html(str);
		}
	});
});
$("#city").change(function(){
	var id = $(this).val();
	$.ajax({
		url:'area.php?do=getdistrict',
		method:'get',
		data:'city_id='+id,
		dataType:'json',
		success:function(msg){
			var str = '<option value="0">请选择</option>';
			if (msg.status==1)
			{
				$.each(msg.list, function(i, n){
					str += "<option value='"+n.id+"'>"+n.name+"</option>";
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
    ,skin: 'msg'
    ,time: 2 //2秒后自动关闭
  });
}

$("#register").click(function(){
	
	var agree = $("#agree").attr("checked");
	if (typeof(agree)=="undefined")
	{
	  show('请接受用户注册协议');
		return false;
	}
	
	
	var realname = $("#realname").val();
	if (realname == '')
	{
		show('请填写真实姓名');
		return false;
	}
	
	var password1 = $("#password1").val();
	if (password1 == '')
	{
		show("请填写密码");
		return false;
	}
	
	var password2 = $("#password2").val();
	if (password2 == '')
	{
		show("请填写确认密码");
		return false;
	}
	
	if (password2 != password1)
	{
		show("密码不一致");
		return false;
	}
	
	var email = $("#email").val();
	if (email == '')
	{
		show("请填写邮箱");
		return false;
	}
	
	var company_name = $("#company_name").val();
	if (company_name == '')
	{
		show("请填写单位名称");
		return false;
	}
	
	var job = $("#job").val();
	if (job == '')
	{
		show("请填写职位");
		return false;
	}
	
	var create_time = $("#create_time").val();
	if (create_time == '')
	{
		show("请填写成立时间");
		return false;
	}
	
	var employee_num = $("#employee_num").val();
	if (employee_num == '')
	{
		show("请填写员工数");
		return false;
	}
	
	var district = $("#district").val();
	if (district == '')
	{
		show("请选择省市区");
		return false;
	}
	
	var address = $("#address").val();
	if (address == '')
	{
		show("请填写地址");
		return false;
	}
	
	var file = $("#cfile").val();
	if (file == '')
	{
		show("请选择图片");
		return false;
	}
	
	var info = $("#info").val();
	if (info == '')
	{
		show("请填写单位介绍");
		return false;
	}
	
});

{/literal}
</script>
</body>
</html>