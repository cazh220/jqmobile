<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>登录</title>
<link rel="stylesheet" href="public/mobile_themes/themes/skyd.min.css" />
<link rel="stylesheet" href="public/mobile_themes/themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />

<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>
<script type="text/javascript">
{literal}
	function login(){
		alert("1111");return false;
		//帐号
		var username = $('#username').val();
		
		if(username == ''){
			alert('请输入您的帐号！');
			$('#username').focus();
			return false;
		}
		
		//密码
		var pwd =  $('#password').val();
		
		if(pwd == ''){
			alert('请输入密码！');
			$('#password').focus();
			return false;
		}
		
		$.ajax({
		   type: "POST",
		   dataType: "json",
		   url: "user.php?do=login",
		   data: "username="+username+'&password='+pwd,
		   success: function(msg){
			   if(msg.status){
			      window.location.href = '/user.php?do=ucenter';	  
			   }else{
			   	  alert(msg.info);
				  $('#password').val('');
			   }
		   }
		});
	}
{/literal}
</script>
<div data-role="page" data-theme="a">
  <div data-role="header">
  <h1>会员登录</h1>
  </div>

  <div data-role="content" data-theme="a">
    <div style="text-align:center"><img src="templates/images/logo.png" width="200px" height="100px"></div>
    <form data-ajax="false">

        <input type="text" name="username" id="username" placeholder=" 手机号/用户名/邮箱">       
        <input type="password" name="password" id="password" placeholder="密码" autocomplete="off">
      
		<a href="javascript:login()" data-role="button">按钮</a>
		<label style="margin-left:0px"><a data-ajax="false" href="user.php?do=findPwd" style="text-decoration:none;">忘记密码?</a></label><label style="float:right"><a data-ajax="false" href="user.php?do=bind_mobile" style="text-decoration:none;">马上注册</a></label>
    </form>
  </div>
</div>

</body>
</html>