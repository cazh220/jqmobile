<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="public/mobile_themes/themes/skyd.min.css" />
<link rel="stylesheet" href="public/mobile_themes/themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
</head>
<body>

<div data-role="page" data-theme="p">
  <div data-role="header" data-position="fixed"><a href="#" class="ui-btn ui-corner-all ui-icon-carat-l ui-btn-icon-notext" data-rel="back">后退</a>
  <h1>录入查询</h1>
  </div>

  <div data-role="content">
    <form method="post" action="member.php" data-ajax="false">
		<input type="search" name="search" id="search" placeholder="诊所姓名/模糊查询">
		<input type="hidden" id="act" name="act" value="1" />
    </form>
  </div>
  
  <table width="100%">
	<tr class="member_table">
		<th width="25%" style="text-align:center">日期</th>
		<th width="25%">卡号</th>
		<th width="25%">医院</th>
		<th width="25%">医生</th>
	</tr>
	{if $list}
	{foreach from=$list item=item key=key}
	<tr class="member_content_table">
		<td>{$item.create_time}</td>
		<td>{$item.security_code}</td>
		<td>{$item.hospital}</td>
		<td>{$item.doctor}</td>
	</tr>
	{/foreach}
	{/if}
  </table>
</div>

</body>
</html>