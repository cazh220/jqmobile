<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<script type="text/javascript">
{literal}
/*
$(".add_to_cart").click(function(){
	alert("XX");
	//var id = $(this).data('id');
	//console.log(id);
});
*/
$(function(){
	$(".add_to_cart").click(function(){
		var id = $(this).data('id');
		console.log(id);
	});
});
{/literal}
</script>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>积分商城</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <form method="post" action="demoform.asp">
      
	   <div class="ui-grid-a">
	   {if $list}
	   	{foreach from=$list item=item key=key}
		 <div {if $key%2==0}class="ui-block-a"{else}class="ui-block-b"{/if} style="border: 0px solid black;">
			<div style="text-align:center; padding:10px"><img src="images/kl.jpg" width="150px" height="120px">
				<div class="product">产品名称：{$item.gift_name}</div>
				<div class="product">产品规则：{$item.stanard}</div>
				<div class="product">兑换积分：{$item.credits}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-id="{$item.gift_id}"  class="add_to_cart">兑换</a></div>
			</div>
		 </div>
		{/foreach}
		{/if}
		 
		 
	   </div>
      
    </form>
  </div>
  
  <div data-role="footer" data-position="fixed" style="overflow:hidden;">
	<!--<div data-role="navbar">
		<ul>
			<li><a href="#">One</a></li>
			<li><a href="#">Two</a></li>
		</ul>
	</div>-->
	<div style="line-height:40px; font-size:12px; width:60%; float:left;">可用积分：9999  兑换所需积分：1000</div><div style="line-height:40px; float:left; width:40%; text-align:center; background-color:#FF7F00">立即兑换</div>
  </div>
  
    <!--<div data-role="footer" data-position="fixed">
    <h1>可用积分：9999  兑换所需积分：1000</h1>
  </div>-->
  
</div>

</body>
</html>
