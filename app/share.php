<!DOCTYPE html>
<html>
<head>
<title>�������</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<style>
.to{
	width: 100px;
	height: 100px;
	background-image: url('images/kl.jpg');
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
</style>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">����</a>
  <h1>��������</h1>
  <a href="#popupMenu" data-rel="popup" data-transition="slideup" data-icon="gear">ѡ��</a>
  <div data-role="popup" id="popupMenu" data-theme="b">
	<ul data-role="listview" data-inset="true" style="min-width:20px">
		<li data-role="list-divider">��ѡ��</li>
		<li><a href="#" onclick="share()">΢��</a></li>
		<li><a href="#">΢��</a></li>
	</ul>
  </div>
  </div>
  

  <div data-role="content" data-theme="c">
    <ul data-role="listview">
		<li>
			<div class="to"></div>
			<div class="user_account">��**</div>
			<div class="user_account">��������������</div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>ҽ�ƻ�������</h4></div>
			<div class="content">�����ǻ</div>
			<div><img src="images/kl.jpg" width="400px" height="300px"></div>
		</li>
		
	</ul>
	<ul data-role="listview">
		<li>
			<div><h4>ҽ�ƻ�������</h4></div>
			<div class="content">�����ǻ</div>
			<div><img src="images/kl.jpg" width="400px" height="300px"></div>
		</li>
		
	</ul>
	
  </div>
  
</div>
<script type="text/javascipt">
<script>
var dataForWeixin = {
appId: "",
MsgImg: "http://chivashi.gotoip1.com/cover.jpg",
TLImg: "http://chivashi.gotoip1.com/cover.jpg",
url: "http://mp.weixin.qq.com/s?__biz=MzA3OTQ2NjkwMA==&mid=200600448&idx=1&sn=459125c55439aef94e6eb1df8ab179f3#rd",
title: '�μ�NaviCam��֪����Ӯ������飡СС������θ����������������θ�죡',
desc: '�μ�NaviCam��֪����Ӯ������飡',
fakeid: "",
callback: function () {
}
};
(function () {
var onBridgeReady = function () {
// ���͸�����; 
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
// ��������Ȧ;
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
// ����΢��;
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
</script>

</script>
</body>

</html>
