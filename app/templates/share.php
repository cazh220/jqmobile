<!DOCTYPE html>
<html>
<head>
<title>订单完成</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<style>
{literal}
.to{
	width: 100px;
	height: 100px;
	/*background-image: url('images/kl.jpg');*/
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

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>防伪详情</h1>
  <a href="#popupMenu" data-rel="popup" data-transition="slideup" data-icon="gear">选项</a>
  <div data-role="popup" id="popupMenu" data-theme="b">
	<ul data-role="listview" data-inset="true" style="min-width:20px">
		<li data-role="list-divider">请选择</li>
		<li><a href="#" onclick="share()">微信</a></li>
		<li><a href="#">微博</a></li>
	</ul>
  </div>
  </div>
  

  <div data-role="content" data-theme="c">
    <ul data-role="listview">
		<li>
			<div class="to" style="background-image: url('/public/upload/data/{$user.head_img}');"></div>
			<div class="user_account">{$patient.wxname}</div>
			<div class="user_account">{$patient.false_tooth_name}</div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>{$patient.hospital}</h4></div>
			<div class="content">{$patient.doc.company_info}</div>
			<div style="text-align: center;"><img src="/public/upload/data/{$patient.doc.head_img}" width="400px" height="300px"></div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>{$patient.production_unit}</h4></div>
			<div class="content">{$patient.tech.company_info}</div>
			<div style="text-align: center;"><img src="/public/upload/data/{$patient.tech.head_img}" width="400px" height="300px"></div>
		</li>
		
	</ul>
	
  </div>
  
</div>
<script type="text/javascipt">
{literal}
var dataForWeixin = {
appId: "",
MsgImg: "http://chivashi.gotoip1.com/cover.jpg",
TLImg: "http://chivashi.gotoip1.com/cover.jpg",
url: "http://mp.weixin.qq.com/s?__biz=MzA3OTQ2NjkwMA==&mid=200600448&idx=1&sn=459125c55439aef94e6eb1df8ab179f3#rd",
title: '参加NaviCam我知道，赢免费体验！小小胶囊做胃镜，轻轻松松送享胃检！',
desc: '参加NaviCam我知道，赢免费体验！',
fakeid: "",
callback: function () {
}
};
(function () {
var onBridgeReady = function () {
// 发送给好友; 
WeixinJSBridge.on('menu:share:appmessage', function (argv) {
WeixinJSBridge.invoke('sendAppMessage', {
"appid": dataForWeixin.appId,
"img_url": dataForWeixin.MsgImg,
"img_width": "120",
"img_height": "120",
"link": dataForWeixin.url,
"desc": dataForWeixin.title,
"title": dataForWeixin.desc
}, function (res) {
});
});
// 分享到朋友圈;
WeixinJSBridge.on('menu:share:timeline', function (argv) {
(dataForWeixin.callback)();
WeixinJSBridge.invoke('shareTimeline', {
"img_url": dataForWeixin.TLImg,
"img_width": "120",
"img_height": "120",
"link": dataForWeixin.url,
"desc": dataForWeixin.desc,
"title": dataForWeixin.title
}, function (res) {
});
});
// 分享到微博;
WeixinJSBridge.on('menu:share:weibo', function (argv) {
WeixinJSBridge.invoke('shareWeibo', {
"content": dataForWeixin.title,
"url": dataForWeixin.url
}, function (res) {
});
});
};
if (document.addEventListener) {
document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
} else if (document.attachEvent) {
document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
}
})();
{/literal}
</script>
</body>

</html>
