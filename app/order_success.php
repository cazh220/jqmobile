<!DOCTYPE html>
<html>
<head>
<title>订单完成</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>订单详情</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <div class="blank"></div>
    <form method="post" action="demoform.asp">
		<ul data-role="listview">
			<li>
				<div class="list_order_success">
					<div class="title"><img src="images/fill_finish.png" width="20px" height="20px"><span class="text_title">2017-07-20 15:30:11 您的订单（订单号：123456789）</span></div>
					<div class="order_content">沪鸽双肩背包，数量1，已成功提交；扣除积分99，当前可用积分9900；积分信息请前往会员中心查看</div>
				</div>
			</li>
			<li>
				<div class="list_order_success">
					<div class="title"><img src="images/fill_finish.png" width="20px" height="20px"><span class="text_title">2017-07-20 15:30:11 您的订单（订单号：123456789）</span></div>
					<div class="order_content">沪鸽双肩背包，数量1，已成功提交；扣除积分99，当前可用积分9900；积分信息请前往会员中心查看</div>
				</div>
			</li>
			<li>
				<div class="list_order_success">
					<div class="title"><img src="images/fill_finish.png" width="20px" height="20px"><span class="text_title">2017-07-20 15:30:11 您的订单（订单号：123456789）</span></div>
					<div class="order_content">沪鸽双肩背包，数量1，已成功提交；扣除积分99，当前可用积分9900；积分信息请前往会员中心查看</div>
				</div>
			</li>
			
		</ul>

      
    </form>
  </div>
  
</div>

</body>

</html>
