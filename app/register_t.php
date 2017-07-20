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
  <h1>��Աע��</h1>
  </div>

  <div data-role="content" data-theme="c">

    <form method="post" action="find.php">
		
		<table width="100%">
			<tr>
				<td width="100%" colspan="2" style="text-align:center">��������</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="realname" id="realname" placeholder="ע������ʵ����"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password1" id="password1" placeholder="����������" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="password" name="password2" id="password2" placeholder="ȷ������" autocomplete="off"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup">
						<label for="techer">����</label>
						<input type="radio" name="typer" id="techer" value="techer" checked="checked">
						<label for="doctor">ҽ��</label>
						<input type="radio" name="typer" id="doctor" value="doctor">	
				    </fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="email" id="email" placeholder="����"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="company_name" id="company_name" placeholder="��λȫ��"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="job" id="job" placeholder="����ְλ"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="date" name="create_time" id="create_time" value="����ʱ��"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="employee_num" id="employee_num" placeholder="��λ��/Ա����"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<fieldset data-role="controlgroup" data-type="horizontal">
						<label for="province">ѡ��ʡ��</label>
						<select name="province" id="province">
						  <option value="1">�Ϻ�</option>
						  <option value="2">����</option>
						</select>

						<label for="city">ѡ���У�</label>
						<select name="city" id="city">
						  <option value="1">�Ϻ�</option>
						  <option value="2">�Ͼ�</option>
						</select>
						<label for="district">ѡ������</label>
						<select name="district" id="district">
						  <option value="1">������</option>
						  <option value="2">������</option>
						</select>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="text" name="address" id="address" placeholder="��ϸ��ַ"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><input type="file" name="file" id="file" value="��λͼƬ"></td>
			</tr>
			<tr>
				<td width="100%" colspan="2"><textarea name="addinfo" id="info" placeholder="��λ����"></textarea></td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<label for="agree">�����Ķ�������<a href="#" target="_blank">���û�ע��Э�顷</a></label>
					<input type="checkbox" name="agree" id="agree" value="agree">
				</td>
			</tr>
			
			
		</table>

		<input type="submit" value="ע��">
    </form>
  </div>
</div>

</body>
</html>