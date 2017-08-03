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
  <h1>我的订单</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <div class="blank"></div>
    <form method="post" action="demoform.asp">
		<ul data-role="listview">
			<li>
				<div class="list_order_success">
					<div class="title"></div>
					<div class="order_content">
						<table width="100%">
							{if $list}
							{foreach from=$list item=item key=key}
							<tr>
								<td><img src="{$item.gift_pic}"></td>
								<td>{$item.gift_name}</td>
								<td>{$item.amount}</td>
								<td>{$item.price}</td>
							</tr>
							{/foreach}
							<tr>
								<td>收货人：{$consignee}</td>
							</tr>
							<tr>
								<td>配送地址：{$address}</td>
							</tr>
							<tr>
								<td>联系电话：{$mobile}</td>
							</tr>
							<tr>
								<td>订单时间：{$create_time}</td>
							</tr>
							<tr>
								<td>发货状态：{$send_time}</td>
							</tr>
							{/if}
						</table>
						
					</div>
				</div>
			</li>
			
		</ul>

      
    </form>
  </div>
  
</div>

</body>

</html>
