<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header">
  <h1>��Ա��¼</h1>
  </div>

  <div data-role="content" data-theme="b">
    <div style="text-align:center"><img src="images/logo.png" width="200px" height="100px"></div>
    <form method="post" action="user.php">

        <input type="text" name="user" id="user" placeholder=" �ֻ���/�û���/����">       
        <input type="password" name="password" id="password" placeholder="����" autocomplete="off">
      
		<input type="submit" value="��¼">
		<label style="margin-left:0px"><a href="#" style="text-decoration:none;">��������?</a></label><label style="float:right"><a href="#" style="text-decoration:none;">����ע��</a></label>
    </form>
  </div>
</div>

</body>
</html>