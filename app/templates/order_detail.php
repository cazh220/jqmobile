<!DOCTYPE html>
<html>
<head>
<title>��д����</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<script type="text/javascript">
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
</script>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">����</a>
  <h1>��д����</h1>
  </div>
  

  <div data-role="content" data-theme="c">
    <div class="blank"></div>
    <form method="post" action="demoform.asp">
		<ul data-role="listview">
			<li>
				<div class="list_product">
					<div class="list_pic"><img src="images/kl.jpg" class="pic_size"></div>
					<div class="attr">��Ʒ���ƣ�����<br> ��Ʒ���100cm<br> �һ����֣�10000<br> ������<span class="buy_num_css" onclick="subplus()"><img src="images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num">1</span><span class="buy_num_css" onclick="plus()"><img src="images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
				</div>
			</li>
			<li>
				<div class="list_product">
					<div class="list_pic"><img src="images/kl.jpg" class="pic_size"></div>
					<div class="attr">��Ʒ���ƣ�����<br> ��Ʒ���100cm<br> �һ����֣�10000<br> ������<span class="buy_num_css" onclick="subplus()"><img src="images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num">1</span><span class="buy_num_css" onclick="plus()"><img src="images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
				</div>
			</li>
			<li>
				<div class="list_product">
					<div class="list_pic"><img src="images/kl.jpg" class="pic_size"></div>
					<div class="attr">��Ʒ���ƣ�����<br> ��Ʒ���100cm<br> �һ����֣�10000<br> ������<span class="buy_num_css" onclick="subplus()"><img src="images/subplus.png" width="20px" height="20px" style="vertical-align: middle;"></span><span id="buy_num">1</span><span class="buy_num_css" onclick="plus()"><img src="images/plus.png" width="20px" height="20px" style="vertical-align: middle;"></span></div>
				</div>
			</li>
			
		</ul>
		
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<div class="blank"></div>
		<ul data-role="listview">
			<li><table width="100%"><tr><td>�ջ�����Ϣ��</td><td><input type="text" name="receiver" id="receiver" value="" data-min="true" placeholder="Ĭ�ϻ�Ա��Ϣ"></td></tr>
			<tr><td>�ջ��绰��</td><td><input type="text" name="mobile" id="mobile" value="" data-min="true" placeholder="Ĭ�ϻ�Ա�ֻ���"></td></tr>
			<tr><td>�ջ���ַ��</td><td><input type="text" name="receiver" id="receiver" value="" data-min="true" placeholder="Ĭ�ϵ�λ��ַ"></td></tr></table></li>
		</ul>
      
    </form>
  </div>
  
  <div data-role="footer" data-position="fixed">
    <div style="line-height:40px; float:left; width:100%; text-align:center; background-color:#FF7F00">�����һ�</div>
  </div>
  
</div>

</body>

</html>
