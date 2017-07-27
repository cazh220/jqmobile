<?php /* Smarty version 2.6.10, created on 2017-07-27 18:07:13
         compiled from user.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<style>
<?php echo '
.to{
	width: 100px;
	height: 100px;
	/*background-image: url("/public/upload/");*/
	background-size: cover;
	display: block;
	border-radius: 50px;
	-webkit-border-radius:50px;
	-moz-border-radius:50px;
	margin:0 auto;
}
.user_account{
	text-align:center;
	padding:10px 0px;
}
.base_info{
	padding:30px 0px;
}
'; ?>

</style>
<body>
<div data-role="page" id="pageone">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>会员中心</h1>
  <a href="#pagetwo" data-role="button" data-icon="alert">12</a>
  </div>

  <div data-role="content" data-theme="d">
    <div class="to" style="background-image: url('/public/upload/<?php echo $this->_tpl_vars['user']['head_img']; ?>
');"></div>
	<div class="user_account"><?php echo $this->_tpl_vars['user']['realname']; ?>
</div>
	<div class="base_info"><label style="margin-left:0px">已录入：<?php echo $this->_tpl_vars['user']['exchanged_credits']; ?>
</label><label style="float:right">积分余额：<?php echo $this->_tpl_vars['user']['left_credits']; ?>
</label></div>
	
	<div style="height:40px"></div>
	<ul data-role="listview">
      <li><a href="member.php">录入查询</a></li>
      <?php if ($this->_tpl_vars['user']['user_type'] == 1): ?>
      <li><a href="user.php?do=patientin&user_id=<?php echo $this->_tpl_vars['user']['user_id']; ?>
">质保卡积分录入</a></li>
      <?php else: ?>
      <li><a href="user.php?do=doctorin&user_id=<?php echo $this->_tpl_vars['user']['user_id']; ?>
">质保卡积分录入</a></li>
      <?php endif; ?>
      
    </ul>
	<div style="height:80px"></div>
	<ul data-role="listview">
      <li><a href="order.php?do=myorder">我的订单</a></li>
      <li><a href="score.php?do=exchange">积分兑换</a></li>
    </ul>
	
	<div style="height:80px"></div>
	<ul data-role="listview">
      <li><a href="user.php?do=userdetail">个人资料</a></li>
      <li><a href="user.php?do=logout">退出</a></li>
    </ul>
  </div>
</div>

<div data-role="page" id="pagetwo">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>消息中心</h1>
  </div>

  <div data-role="content" data-theme="d">	
	<ul data-role="listview" data-inset="true">
      <li data-role="list-divider">星期三, 1 月 2 日, 2013 <span class="ui-li-count">2</span></li>   
      <li><a href="#pagethree">   
        <h2>医生</h2>
        <p><b>To Peter Griffin</b></p>
        <p>Well, Mr. Griffin, I've looked into physical results.</p>
        <p>Ah, Mr. Griffin, I'm not quite sure how to say this. Kim Bassinger? Bass singer? Bassinger?</p>
        <p>But now, onto the cancer</p>
        <p>You are a Cancer, right? You were born in July? Now onto these test results.</p>
        <p class="ui-li-aside">Re: Appointment</p></a>
      </li>
      <li><a href="#">
        <h2>Glen Quagmire</h2>
        <p>Remember me this weekend!</p>
        <br>
        <p>- giggity giggity goo</p>
        <p class="ui-li-aside">Re: Camping</p></a>
      </li>
      <li data-role="list-divider">周二, 1 月 1 日, 2013 <span class="ui-li-count">1</span></li>   
      <li><a href="#">   
        <h2>Louis</h2>
        <p><b>Happy Girl!</b></p>
        <p>Thank you so much!!</p>
        <p class="ui-li-aside">Re: Christmas Gifts</p></a>
      </li>
    </ul>
  </div>
</div>

<div data-role="page" id="pagethree">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>消息详情</h1>
  </div>

  <div data-role="content" data-theme="d">	
	我的消息就是
  </div>
</div>

</body>
</html>