<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>信息更新</h1>
  </div>

  <div data-role="content" data-theme="c">
    <form id="patient_form" method="post" action="patient.php?do=updatepatient" data-ajax="false" enctype="multipart/form-data">
      <div data-role="fieldcontain">
      	<div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗机构：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="hospital" id="hospital" placeholder="医疗机构" value="{$patient.hospital}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗专家：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="doctor" id="doctor" placeholder="医疗专家" value="{$patient.doctor}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者姓名：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="patient_name" id="patient_name" placeholder="患者姓名" value="{$patient.name}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者性别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="controlgroup">
        			<label for="male">男性</label>
        		    <input type="radio" name="gender" id="male" value="0" {if $patient.sex == 0}checked{/if}>
        		    <label for="female">女性</label>
        		    <input type="radio" name="gender" id="female" value="1" {if $patient.sex == 1}checked{/if}>	
            </fieldset>
        </div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者年龄：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="patient_age" id="patient_age" placeholder="患者年龄" value="{$patient.birthday}"></div> 
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">选择牙位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="tooth_position" id="tooth_position" placeholder="左上|右上|左下|右下" value="{$patient.tooth_position}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">制作单位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="production_unit" id="production_unit" placeholder="" value="{$user.company_name}" disabled="disabled"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">录入人员：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="recorder" id="recorder" value="{$user.realname}" disabled="disabled"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">修复体类别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="fieldcontain">
		        <select name="repaire_type" id="repaire_type">
		         <option value="1" {if $patient.false_tooth == 1}selected{/if}>美晶瓷全牙</option>
		         <option value="2" {if $patient.false_tooth == 2}selected{/if}>氧化锆全牙</option>
		        </select>
	      </fieldset>
        </div>
        
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">修复体图片：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="file" name="repaire_pic" id="repaire_pic" value=""></div>
        <div style="height: 150px; text-align: right; width: 25%; float: left; line-height: 150px">修复体图片预览：</div><div style="height: 150px; text-align: center; width: 75%; float: left; line-height: 150px"><img src="/public/upload/data/{$patient.repairosome_pic}" width="150px" height="150px">
      </div>
      <div style="text-align: right; width: 50%; float: left;"><a href="#" data-role="button" onclick="doreset()">更改</a></div><div style="text-align: center; width: 50%; float: left;"><a href="#" data-role="button" onclick="dodubmit()">提交</a></div>
      <input type="hidden" id="user_id" name="user_id" value="{$user_id}" />
      <input type="hidden" id="repairosome_pic_h" name="repairosome_pic_h" value="{$patient.repairosome_pic}" />
      <input type="hidden" id="qrcode" name="qrcode" value="{$patient.security_code}" />
    </form>
  </div>
</div>
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

function dodubmit()
{
	var hospital = $("#hospital").val();
	if (hospital=="")
	{
	  show('请填写医疗机构');
		return false;
	}
	
	
	var doctor = $("#doctor").val();
	if (doctor == '')
	{
		show('请填写医疗专家');
		return false;
	}
	
	var patient_name = $("#patient_name").val();
	if (patient_name == '')
	{
		show('请填写患者姓名');
		return false;
	}
	
	var patient_age = $("#patient_age").val();
	if (patient_age == '')
	{
		show('请填写患者年龄');
		return false;
	}
	
	var tooth_position = $("#tooth_position").val();
	if (tooth_position == '')
	{
		show('请填写牙位');
		return false;
	}
	
	var file = $("#repairosome_pic_h").val();
	if (file == '')
	{
		show("请选择图片");
		return false;
	}
	
	var qrcode;
	qrcode = $("#qrcode").val();
	var sex;
	if($("#female").attr("checked")=='checked')
	{
		sex =1;
	}
	else
	{
		sex =0;
	}
	
	var false_tooth;
	false_tooth = $("#repaire_type").val();
	
	var data = "hospital="+hospital+"&doctor="+doctor+"&patient_name="+patient_name+"&sex="+sex+"&patient_age="+patient_age+"&tooth_position="+tooth_position+"&file="+file+"&false_tooth="+false_tooth+'&qrcode='+qrcode;
	$.ajax({
		type:"GET",
		url:"patient.php?do=updatepatient",
		data:data,
		dataType:"json",
		success:function(msg){
			//console.log(msg);return false;
			show(msg.message);
			if(msg.status)
			{
				//show(msg.message);
				window.location.href=msg.url;
			}
		}
	});
	//document.getElementById("patient_form").submit();
}

function doreset()
{
	document.getElementById("patient_form").reset();
}
{/literal}
</script>
</body>
</html>