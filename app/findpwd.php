<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">����</a>
  <h1>�һ�����</h1>
  </div>

  <div data-role="content" data-theme="b">
	<div class="blank"></div>
	<div class="blank"></div>
    <form method="post" action="find.php">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2"><input type="text" name="mobile" id="mobile" placeholder="�����ֻ���"></td>
			</tr>
			<tr>
				<td width="60%"><input type="text" name="vcode" id="vcode" placeholder="������֤��"></td>
				<td width="40%" style="text-align:center"><input type="button" data-role="none" value="��ȡ��֤��" class="vcode_button"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password1" id="password1" placeholder="����������" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password2" id="password2" placeholder="ȷ������" autocomplete="off"></td>
			</tr>
		</table>
        
		
		
		
		<input type="submit" value="�ύ">
    </form>
  </div>
</div>

</body>
</html>