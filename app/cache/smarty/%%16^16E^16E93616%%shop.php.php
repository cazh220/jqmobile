<?php /* Smarty version 2.6.10, created on 2017-07-28 01:06:16
         compiled from shop.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<script type="text/javascript">
<?php echo '
/*
$(".add_to_cart").click(function(){
	alert("XX");
	//var id = $(this).data(\'id\');
	//console.log(id);
});
*/
$(function(){
	$(".add_to_cart").click(function(){
		var id = $(this).data(\'id\');
		console.log(id);
	});
});
'; ?>

</script>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>积分商城</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <form method="post" action="demoform.asp">
      
	   <div class="ui-grid-a">
	   <?php if ($this->_tpl_vars['list']): ?>
	   	<?php $_from = $this->_tpl_vars['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['item']):
?>
		 <div <?php if ($this->_tpl_vars['key']%2 == 0): ?>class="ui-block-a"<?php else: ?>class="ui-block-b"<?php endif; ?> style="border: 0px solid black;">
			<div style="text-align:center; padding:10px"><img src="images/kl.jpg" width="150px" height="120px">
				<div class="product">产品名称：<?php echo $this->_tpl_vars['item']['gift_name']; ?>
</div>
				<div class="product">产品规则：<?php echo $this->_tpl_vars['item']['stanard']; ?>
</div>
				<div class="product">兑换积分：<?php echo $this->_tpl_vars['item']['credits']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" data-id="<?php echo $this->_tpl_vars['item']['gift_id']; ?>
"  class="add_to_cart">兑换</a></div>
			</div>
		 </div>
		<?php endforeach; endif; unset($_from); ?>
		<?php endif; ?>
		 
		 
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