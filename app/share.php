<!DOCTYPE html>
<html>
<head>
<title>订单完成</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<style>
.to{
	width: 100px;
	height: 100px;
	background-image: url('images/kl.jpg');
	background-size: cover;
	display: block;
	border-radius: 50px;
	-webkit-border-radius:50px;
	-moz-border-radius:50px;
	margin:0 auto;
}
.user_account{
	text-align:center;
	padding:10px 0px;
}
.base_info{
	padding:30px 0px;
}
</style>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>订单详情</h1>
  <a href="#" data-icon="gear">选项</a>
  </div>
  

  <div data-role="content" data-theme="c">
    <ul data-role="listview">
		<li>
			<div class="to"></div>
			<div class="user_account">大**</div>
			<div class="user_account">美晶瓷氧化锆义齿</div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>医疗机构名称</h4></div>
			<div class="content">沪鸽口腔</div>
			<div><img src="images/kl.jpg" width="400px" height="300px"></div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>医疗机构名称</h4></div>
			<div class="content">沪鸽口腔</div>
			<div><img src="images/kl.jpg" width="400px" height="300px"></div>
		</li>
		
	</ul>
	
  </div>
  
</div>

</body>

</html>
