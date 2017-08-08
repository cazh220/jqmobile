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
    <form method="post" action="">
		<ul data-role="listview">
			<li>
				<div class="list_order_success">

					<div class="order_content">
						<table width="100%">
							<tr style="line-height: 40px;">
								<td width="40%"><b>商品</b></td>
								<td width="20%"><b>名称</b></td>
								<td width="20%"><b>数量</b></td>
								<td width="20%"><b>价格</b></td>
							</tr>
							{if $list}
							{foreach from=$list item=item key=key}
							<tr>
								<td style="border-top: solid 1px #000000;"><img src="http://huge.com/public/uploads/{$item.gift_pic}" width="90px" height="60px" style="padding-top: 5px;"></td>
								<td style="border-top: solid 1px #000000;">{$item.gift_name}</td>
								<td style="border-top: solid 1px #000000;">{$item.amount}</td>
								<td style="border-top: solid 1px #000000;">{$item.price}</td>
							</tr>
							{/foreach}
							<tr style="line-height: 20px;">
								<td colspan="4" style="border-top: solid 1px #000000;"><strong>收货人：</strong>{$consignee}</td>
							</tr>
							<tr style="line-height: 20px;">
								<td colspan="4"><strong>配送地址：</strong>{$address}</td>
							</tr>
							<tr style="line-height: 20px;">
								<td colspan="4"><strong>联系电话：</strong>{$mobile}</td>
							</tr>
							<tr style="line-height: 20px;">
								<td colspan="4"><strong>订单时间：</strong>{$create_time}</td>
							</tr>
							<tr style="line-height: 20px;">
								<td colspan="4"><strong>发货状态：</strong>{$send_time}</td>
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
