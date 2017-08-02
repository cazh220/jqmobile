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
  <h1>医生录入</h1>
  </div>

  <div data-role="content" data-theme="c">
    <form method="post" action="demoform.asp">
      <div data-role="fieldcontain">
      	<div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗机构：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.hospital}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">医疗专家：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.doctor}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者姓名：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.name}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者性别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="controlgroup">
        			<label for="male">男性</label>
        		    <input type="radio" name="gender" id="male" value="male" {if $patient.sex == 0 || !$patient.sex}checked{/if}>
        		    <label for="female">女性</label>
        		    <input type="radio" name="gender" id="female" value="female" {if $patient.sex == 1}checked{/if}>	
            </fieldset>
        </div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">患者年龄：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.birthday}"></div> 
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">选择牙位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.tooth_position}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">制作单位：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.production_unit}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">录入时间：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" placeholder="" value="{$patient.create_time}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">录入人员：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="text" name="fullname" id="fullname" value="{$patient.operator}"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">修复体类别：</div><div style="text-align: center; width: 75%; float: left;">
        	<fieldset data-role="fieldcontain">
		        <select name="type" id="type">
		         <option value="1" {if $patient.false_tooth == 1}selected{/if}>美晶瓷全牙</option>
		         <option value="2" {if $patient.false_tooth == 2}selected{/if}>氧化锆全牙</option>
		        </select>
	      </fieldset>
        </div>
        <div style="height: 100px; text-align: right; width: 25%; float: left; line-height: 100px">修复体图片：</div><div style="height: 100px; text-align: center; width: 75%; float: left; line-height: 100px"><img src="/public/upload/data/{$patient.repairosome_pic}" width="100px" height="100px"></div>
        <div style="height: 50px; text-align: right; width: 25%; float: left; line-height: 50px">案例图片：</div><div style="height: 50px; text-align: center; width: 75%; float: left; line-height: 50px"><input type="file" name="file" id="file" value=""></div>

      </div>
      <div style="text-align: right; width: 50%; float: left;"><a href="patient.php" data-role="button">提交</a></div><div style="text-align: center; width: 50%; float: left;"><a href="doctor.php?do=correction&qrcode={$qrcode}" data-role="button">纠错</a></div>
      
    </form>
  </div>
</div>

</body>
</html>