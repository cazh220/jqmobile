<?php /* Smarty version 2.6.10, created on 2017-07-28 00:09:25
         compiled from patient.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>技工录入</h1>
  </div>

  <div data-role="content" data-theme="c">
    <form id="patient_form" method="post" action="patient.php?do=addpatient" data-ajax="false" enctype="multipart/form-data">
      <div data-role="fieldcontain">
      	<div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗机构：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="hospital" id="hospital" placeholder="医疗机构"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗专家：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="doctor" id="doctor" placeholder="医疗专家"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者姓名：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="patient_name" id="patient_name" placeholder="患者姓名"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者性别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="controlgroup">
        			<label for="male">男性</label>
        		    <input type="radio" name="gender" id="male" value="0" checked>
        		    <label for="female">女性</label>
        		    <input type="radio" name="gender" id="female" value="1">	
            </fieldset>
        </div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者年龄：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="patient_age" id="patient_age" placeholder="患者年龄"></div> 
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">选择牙位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="tooth_position" id="tooth_position" placeholder="左上|右上|左下|右下"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">制作单位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="production_unit" id="production_unit" placeholder=""></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">录入时间：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="create_time" id="create_time" placeholder=""></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">录入人员：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="recorder" id="recorder"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">修复体类别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="fieldcontain">
		        <select name="repaire_type" id="repaire_type">
		         <option value="1">美晶瓷全牙</option>
		         <option value="2">氧化锆全牙</option>
		        </select>
	      </fieldset>
        </div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">修复体图片：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="file" name="repaire_pic" id="repaire_pic" value=""></div>

      </div>
      <div style="text-align: right; width: 50%; float: left;"><a href="#" data-role="button" onclick="doreset()">更改</a></div><div style="text-align: center; width: 50%; float: left;"><a href="#" data-role="button" onclick="dodubmit()">提交</a></div>
      
    </form>
  </div>
</div>
<script type="text/javascript">
<?php echo '
function dodubmit()
{
  $("#patient_form").submit();
}

function doreset()
{
  $("#patient_form").reset();
}
'; ?>

</script>
</body>
</html>