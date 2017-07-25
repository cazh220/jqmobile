<?php /* Smarty version 2.6.10, created on 2017-07-25 23:27:25
         compiled from login.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>
<script type="text/javascript">
<?php echo '
	function login(){
	
		//帐号
		var username = $(\'#username\').val();
		
		if(username == \'\'){
			alert(\'请输入您的帐号！\');
			$(\'#username\').focus();
			return false;
		}
		
		//密码
		var pwd =  $(\'#password\').val();
		
		if(pwd == \'\'){
			alert(\'请输入密码！\');
			$(\'#password\').focus();
			return false;
		}
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "user.php?do=login",
		   data: "username="+username+\'&password=\'+pwd,
		   success: function(msg){
			   if(msg.status){
			      window.location.href = \'/index.php\';	  
			   }else{
			   	  alert(msg.info);
				  $(\'#password\').val(\'\');
			   }
		   }
		});
	}
'; ?>

</script>
<div data-role="page">
  <div data-role="header">
  <h1>会员登录</h1>
  </div>

  <div data-role="content" data-theme="b">
    <div style="text-align:center"><img src="templates/images/logo.png" width="200px" height="100px"></div>
    <form data-ajax="false">

        <input type="text" name="username" id="username" placeholder=" 手机号/用户名/邮箱">       
        <input type="password" name="password" id="password" placeholder="密码" autocomplete="off">
      
		<input type="button" value="登录" onclick="login()">
		<label style="margin-left:0px"><a href="user.php?do=findPwd" style="text-decoration:none;">忘记密码?</a></label><label style="float:right"><a href="user.php?do=bind_mobile" style="text-decoration:none;">马上注册</a></label>
    </form>
  </div>
</div>

</body>
</html>