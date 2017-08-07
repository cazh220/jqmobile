<!DOCTYPE html>
<html>
<head>
<title>填写订单</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<script type="text/javascript">
{literal}
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: 'msg'
    ,time: 2 //2秒后自动关闭
  });
}

function plus(id, price)
{
	var num = $("#buy_num_"+id).html();
	num = parseInt(num);
	num++;
	$("#buy_num_"+id).html(num);
	$("#gift_num_"+id).val(num);
	var credits = 0;
	credits = parseInt($("#need_credits").val());
	credits += parseFloat(price*num);
	
	$("#need_credits").val(credits);
}

function subplus(id, price)
{
	var num = $("#buy_num_"+id).html();
	num = parseInt(num);
	num--;
	if (num < 0)
	{
		num = 0;
	}
	$("#buy_num_"+id).html(num);
	$("#gift_num_"+id).val(num);
	var credits =parseInt( $("#need_credits").val());
	credits -= parseFloat(price*num);
	$("#need_credits").val(credits);
}

$(function(){
	$(".create_order").click(function(){
		//检查表单
		var receiver = $("#receiver").val();
		var mobile = $("#mobile").val();
		var address = $("#address").val();
		if(receiver == '')
		{
			show("请填写收货人");
			return false;
		}
		if(mobile == '')
		{
			show("请填写收货电话");
			return false;
		}
		if(address == '')
		{
			show("请填写收货地址");
			return false;
		}
		
		var left_credits = $("#$left_credits").val();
		
		
		
		$("#order_form").submit();
	});
});
{/literal}
</script>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>填写订单</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <div class="blank"></div>
    <form id="order_form" method="post" action="order.php?do=createorder" data-ajax="false">
		<ul data-role="listview">
			{if $list}
			{foreach from=$list item=item key=key}
			<li>
				<div class="list_product">
					<div class="list_pic"><img src="http://huge.com/public/uploads/{$item.gift_photo}" class="pic_size"></div>
					<div class="attr">产品名称：{$item.gift_name}<br> 产品规格：{$item.gift_intro}<br> 兑换积分：{$item.credits}<br> 数量：<span class="buy_num_css" onclick="subplus({$item.gift_id}, {$item.credits})"><img src="templates/images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num_{$item.gift_id}" class="buy_num" data-id="{$item.gift_id}">1</span><span class="buy_num_css" onclick="plus({$item.gift_id}, {$item.credits})"><img src="templates/images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
					<input type="hidden" id="gift_id_{$item.gift_id}" name="gift_id[]" class="h_gift_id" value="{$item.gift_id}"/>
					<input type="hidden" id="gift_num_{$item.gift_id}" name="gift_num[]" class="h_gift_num" value="1"/>
				</div>
			</li>
			{/foreach}
			{/if}
			
		</ul>
		
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<ul data-role="listview">
			<li><table width="100%"><tr><td>收货人信息：</td><td><input type="text" name="receiver" id="receiver" value="" data-min="true" placeholder="默认会员信息"></td></tr>
			<tr><td>收货电话：</td><td><input type="text" name="mobile" id="mobile" value="" data-min="true" placeholder="默认会员手机号"></td></tr>
			<tr><td>收货地址：</td><td><input type="text" name="address" id="address" value="" data-min="true" placeholder="默认单位地址"></td></tr></table></li>
		</ul>
       <input type="hidden" id="left_credits" name="left_credits" value="{$left_credits}"/>
       <input type="hidden" id="need_credits" name="need_credits" value="0"/>
    </form>
  </div>
  
  <div data-role="footer" data-position="fixed">
    <div class="create_order" style="line-height:40px; float:left; width:100%; text-align:center; background-color:#FF7F00">立即兑换</div>
  </div>
  
</div>

</body>

</html>
