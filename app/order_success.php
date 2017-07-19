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
				<div class="list_product">
					<div class="list_pic"><img src="images/kl.jpg" class="pic_size"></div>
					<div class="attr">产品名称：考拉<br> 产品规格：100cm<br> 兑换积分：10000<br> 数量：<span class="buy_num_css" onclick="subplus()"><img src="images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num">1</span><span class="buy_num_css" onclick="plus()"><img src="images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
				</div>
			</li>
			
		</ul>

      
    </form>
  </div>
  
  <div data-role="footer" data-position="fixed">
    <div style="line-height:40px; float:left; width:100%; text-align:center; background-color:#FF7F00">立即兑换</div>
  </div>
  
</div>

</body>

</html>
