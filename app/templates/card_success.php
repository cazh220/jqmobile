<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
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
{/literal}
</style>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>质保卡录入</h1>
  </div>

  <div data-role="content" data-theme="b">
    <div style="text-align:center; padding:10px 0px 20px 0px">恭喜！录入完成</div>
	<label style="font-size:28px; font-weight: bold; position:absolute; left: 48%; margin-top: 20px">30</label>
	<div class="to"></div>
	<div style="text-align:center; padding:20px 0px 20px 0px">当前总积分：20000</div>
	<a href="patient.php?user_id={$user.user_id}" data-role="button">完善患者信息</a>
	<a href="patient.php" data-role="button">继续录入</a>
  </div>
</div>

</body>
</html>