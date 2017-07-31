<!DOCTYPE html>
<html>
<head>
<title>订单完成</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
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
					<div class="title"><img src="templates/images/fill_finish.png" width="20px" height="20px"><span class="text_title">{$info.create_time}您的订单（订单号：{$info.order_no}）</span></div>
					<div class="order_content">{$info.info}已成功提交；扣除积分{$info.total_credits}，当前可用积分{$info.left_credits}；积分信息请前往会员中心查看</div>
				</div>
			</li>
			
		</ul>

      
    </form>
  </div>
  
</div>

</body>

</html>
