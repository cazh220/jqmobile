<?php /* Smarty version 2.6.10, created on 2017-07-27 23:37:49
         compiled from search.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>录入查询</h1>
  </div>

  <div data-role="content" data-theme="b">
    <form method="post" action="user.php?do=ValidateMobile" data-ajax="false">
		<input type="search" name="search" id="search" placeholder="诊所姓名/模糊查询">
    </form>
  </div>
  
  <table width="100%">
	<tr class="member_table">
		<th width="25%" style="text-align:center">日期</th>
		<th width="25%">卡号</th>
		<th width="25%">医院</th>
		<th width="25%">医生</th>
	</tr>
  </table>
</div>

</body>
</html>