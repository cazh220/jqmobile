<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="public/mobile_themes/themes/skyd.min.css" />
<link rel="stylesheet" href="public/mobile_themes/themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<style>
{literal}
.to{
	width: 100px;
	height: 100px;
	background-image: url('templates/images/3.png');
	background-size: cover;
	display: block;
	border-radius: 50px;
	-webkit-border-radius:50px;
	-moz-border-radius:50px;
	margin:0 auto;
}
.record_header{
	width: 25%;
	float: left;
	line-height: 30px;
	text-align: center;
	font-weight: bold;
}
.record_content{
	width: 25%;
	float: left;
	line-height: 30px;
	text-align: center;
}
{/literal}
</style>
<body>

<div data-role="page" data-theme="p">
  <div data-role="header"><a href="#" class="ui-btn ui-corner-all ui-icon-carat-l ui-btn-icon-notext" data-rel="back" data-ajax="false">后退</a>
  <h1>质保卡录入</h1>
  </div>

  <div data-role="content">
    <div style="text-align:center; padding:10px 0px 20px 0px">恭喜！录入完成</div>
	<label style="font-size:28px; font-weight: bold; position:absolute; left: 48%; margin-top: 20px">{$credits}</label>
	<div class="to"></div>
	<div style="text-align:center; padding:20px 0px 20px 0px">当前总积分：{$left_credits}</div>
	<a href="patient.php?do=techrecord&user_id={$user.user_id}&qrcode=22334455" data-role="button" data-ajax="false">完善患者信息</a>
	<a href="patient.php" data-role="button">继续录入</a>
	
	
	<div style="height: 200px;"></div>
	<div>
		<div class="record_header">日期</div>
		<div class="record_header">卡号</div>
		<div class="record_header">医院</div>
		<div class="record_header">医生</div>
	</div>
	<hr>
	<div>
		<marquee direction="up" behavior="scroll" scrollamount="1" height="120px" loop="-1">
		{if $patient}
		{foreach from=$patient item=item key=key}
			<div>
			<div class="record_content">{$item.create_time}</div>
			<div class="record_content">{$item.security_code}</div>
			<div class="record_content">{$item.hospital}</div>
			<div class="record_content">{$item.doctor}</div>
			</div>
		{/foreach}
		{/if}
		</marquee>
	</div>
	
	
  </div>
</div>

</body>
</html>