<?php /* Smarty version 2.6.10, created on 2017-08-07 18:07:22
         compiled from shop.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<script type="text/javascript">
<?php echo '
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: \'msg\'
    ,time: 2 //2秒后自动关闭
  });
}

$(function(){
	/*
	$(".add_to_cart").click(function(){
		var id = $(this).data(\'id\');
		console.log(id);
	});*/
	
	$("#exchange").click(function(){
		var total, need;
		total = $("#able_jf").text();
		need = $("#need_jf").text();
		if(total < need)
		{
			show("积分不够");
			return false;
		}

		var arr = new Array();
		var id;
		$(".goods_check").each(function(i,n){
			var check = $(this).attr("checked");
			if (check == \'checked\')
			{
				arr.push($(this).val());
			}
			id = arr.join(\',\');
		});

		window.location.href="/order.php?do=orderconfirm&id="+id;
	});
	
	$(".goods_check").click(function(){
		//var credits = $(this).data(\'id\');
		var need_credits = 0;
		$(".goods_check").each(function(i,n){
			var check = $(this).attr("checked");
			if (check == \'checked\')
			{
				need_credits += $(this).data(\'id\');
			}
		});
		$("#need_jf").text(need_credits);
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
		 	<table align="center" class="product">
		 		<tr>
		 			<td colspan="3" style="text-align: center;"><img src="http://huge.com/public/uploads/<?php echo $this->_tpl_vars['item']['gift_photo']; ?>
" width="180px" height="130px"></td>
		 		</tr>
		 		<tr>
		 			<td style="text-align: right; width: 30%;">产品名称:</td>
		 			<td colspan="2" style="text-align: left;"><?php echo $this->_tpl_vars['item']['gift_name']; ?>
</td>
		 		</tr>
		 		<tr>
		 			<td style="text-align: right;">产品规则:</td>
		 			<td colspan="2" style="text-align: left;"><?php echo $this->_tpl_vars['item']['stanard']; ?>
</td>
		 		</tr>
		 		<tr>
		 			<td style="text-align: right;">兑换积分:</td>
		 			<td style="text-align: left;"><?php echo $this->_tpl_vars['item']['credits']; ?>
</td>
		 			<td><input type="checkbox" class="goods_check" value="<?php echo $this->_tpl_vars['item']['gift_id']; ?>
" data-id="<?php echo $this->_tpl_vars['item']['credits']; ?>
" style="position: static; margin-top: 0px; margin-left: 10px; float: left;">选择</td>
		 			
		 		</tr>
		 	</table>
			<!--<div style="text-align:center; padding:10px"><img src="http://huge.com/public/uploads/<?php echo $this->_tpl_vars['item']['gift_photo']; ?>
" width="150px" height="120px">
				<div class="product">产品名称：<?php echo $this->_tpl_vars['item']['gift_name']; ?>
</div>
				<div class="product">产品规则：<?php echo $this->_tpl_vars['item']['stanard']; ?>
</div>
				<div class="product">兑换积分：<?php echo $this->_tpl_vars['item']['credits']; ?>
&nbsp;&nbsp;&nbsp;<input type="checkbox" class="goods_check" value="<?php echo $this->_tpl_vars['item']['gift_id']; ?>
"><a href="#" data-id="<?php echo $this->_tpl_vars['item']['gift_id']; ?>
"  class="add_to_cart">选择</a></div>
			</div>-->
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
	<div style="line-height:40px; font-size:12px; width:60%; float:left;">可用积分：<span id="able_jf"><?php echo $this->_tpl_vars['user']['total_credits']; ?>
</span>  兑换所需积分：<span id="need_jf">0</span></div><div id="exchange" style="line-height:40px; float:left; width:40%; text-align:center; background-color:#FF7F00">立即兑换</div>
  </div>
  
    <!--<div data-role="footer" data-position="fixed">
    <h1>可用积分：9999  兑换所需积分：1000</h1>
  </div>-->
  
</div>

</body>
</html>