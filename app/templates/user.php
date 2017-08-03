<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<style>
{literal}
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
{/literal}
</style>
<body>
<div data-role="page" id="pageone">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>会员中心</h1>
  <a href="#pagetwo" data-role="button" data-icon="alert">12</a>
  </div>

  <div data-role="content" data-theme="d">
    <div class="to" style="background-image: url('/public/upload/data/{$user.head_img}');"></div>
	<div class="user_account">{$user.mobile}</div>
	<div class="base_info"><label style="margin-left:0px">已录入：{$user.exchanged_credits}</label><label style="float:right">积分余额：{$user.left_credits}</label></div>
	
	<div style="height:40px"></div>
	<ul data-role="listview">
      <li><a href="member.php">录入查询</a></li>
      {if $user.user_type==1}
      <li><a href="user.php?do=patientin&user_id={$user.user_id}">质保卡积分录入</a></li>
      {else}
      <li><a href="user.php?do=doctorin&user_id={$user.user_id}">质保卡积分录入</a></li>
      {/if}
      
    </ul>
	<div style="height:80px"></div>
	<ul data-role="listview">
	  <li><a href="message.php?user={$user.user_id}" data-ajax="false">我的消息<span class="ui-li-count">{$message_count}</span></a></li>
      <li><a href="order.php?do=myorder" data-ajax="false">我的订单</a></li>
      <li><a href="shop.php">积分兑换</a></li>
    </ul>
	
	<div style="height:80px"></div>
	<ul data-role="listview">
      <li><a href="user.php?do=member">个人资料</a></li>
      <li><a href="user.php?do=setting">设置</a></li>
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