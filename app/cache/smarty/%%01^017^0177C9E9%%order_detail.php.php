<?php /* Smarty version 2.6.10, created on 2017-07-28 15:57:08
         compiled from order_detail.php */ ?>
<!DOCTYPE html>
<html>
<head>
<title>填写订单</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<script type="text/javascript">
<?php echo '
function plus()
{
	var num = $("#buy_num").html();
	num = parseInt(num);
	num++
	$("#buy_num").html(num);
}

function subplus()
{
	var num = $("#buy_num").html();
	num = parseInt(num);
	num--;
	if (num < 0)
	{
		num = 0;
	}
	$("#buy_num").html(num);
}
'; ?>

</script>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>填写订单</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <div class="blank"></div>
    <form method="post" action="demoform.asp">
		<ul data-role="listview">
			<?php if ($this->_tpl_vars['list']): ?>
			<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
			<li>
				<div class="list_product">
					<div class="list_pic"><img src="E:\jqmobi\jqmobile\app\public\upload\<?php echo $this->_tpl_vars['item']['gift_photo']; ?>
" class="pic_size"></div>
					<div class="attr">产品名称：<?php echo $this->_tpl_vars['item']['gift_name']; ?>
<br> 产品规格：<?php echo $this->_tpl_vars['item']['gift_intro']; ?>
<br> 兑换积分：<?php echo $this->_tpl_vars['item']['credits']; ?>
<br> 数量：<span class="buy_num_css" onclick="subplus()"><img src="images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num">1</span><span class="buy_num_css" onclick="plus()"><img src="images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
				</div>
			</li>
			<?php endforeach; endif; unset($_from); ?>
			<?php endif; ?>
			
		</ul>
		
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<ul data-role="listview">
			<li><table width="100%"><tr><td>收货人信息：</td><td><input type="text" name="receiver" id="receiver" value="" data-min="true" placeholder="默认会员信息"></td></tr>
			<tr><td>收货电话：</td><td><input type="text" name="mobile" id="mobile" value="" data-min="true" placeholder="默认会员手机号"></td></tr>
			<tr><td>收货地址：</td><td><input type="text" name="receiver" id="receiver" value="" data-min="true" placeholder="默认单位地址"></td></tr></table></li>
		</ul>
      
    </form>
  </div>
  
  <div data-role="footer" data-position="fixed">
    <div style="line-height:40px; float:left; width:100%; text-align:center; background-color:#FF7F00">立即兑换</div>
  </div>
  
</div>

</body>

</html>