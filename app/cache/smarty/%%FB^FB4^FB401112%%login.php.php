<?php /* Smarty version 2.6.10, created on 2017-07-22 09:04:06
         compiled from login.php */ ?>
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
  <h1>会员登录</h1>
  </div>

  <div data-role="content" data-theme="b">
    <div style="text-align:center"><img src="templates/images/logo.png" width="200px" height="100px"></div>
    <form method="post" action="user.php?do=Login">

        <input type="text" name="user" id="user" placeholder=" 手机号/用户名/邮箱">       
        <input type="password" name="password" id="password" placeholder="密码" autocomplete="off">
      
		<input type="submit" value="登录">
		<label style="margin-left:0px"><a href="#" style="text-decoration:none;">忘记密码?</a></label><label style="float:right"><a href="#" style="text-decoration:none;">马上注册</a></label>
    </form>
  </div>
</div>

</body>
</html>