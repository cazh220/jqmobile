<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>商品详情</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" type="text/css" href="plugins/css/style.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="plugins/js/imagesloaded.pkgd.min.js"></script>
<script src="plugins/js/jquery.hslider.js"></script>
<script type="text/javascript">       
	$( document ).ready(function() { 
		$( ".hsldr-container" ).hslider({
		  navBar: true,
		  auto: true,
		  delay: 2000
		});
	});	
</script> 
</head>
<body>

<div data-role="page">
  <div data-role="header" data-position="fixed"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>积分商城</h1>
  </div>
  
	<div class="hsldr-container">
		<figure>
			<img src="plugins/images/wider.jpg" />
			<figcaption>Car in the snow</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/f5bd8360.jpg" />
			<figcaption>People surfing</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1415769663272-8504c6cc02b3.jpg" />
			<figcaption>Girl with the balloon</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1418662589339-364ad47f98a2.jpg" />
			<figcaption>Ice surfing</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1423483786645-576de98dcbed.jpg" />
			<figcaption>Golden hair</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1424470535838-79a00dc41aa5.jpg" />
			<figcaption>Antartica</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1428069940893-209d71f133cf.jpg" />
			<figcaption>Mofler</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1430834447668-d44a17fc36fe.jpg" />
			<figcaption>The hard worker</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1446902236611-65b30daefc2a.jpg" />
			<figcaption>Winter lamps</figcaption>
		</figure>
		<figure>
			<img src="plugins/images/photo-1428471226620-c2698eadf413.jpg" />
			<figcaption>Winter lamps</figcaption>
		</figure>				
		<figure>
			<img src="plugins/images/wider2.jpg" />
			<figcaption>Winter lamps</figcaption>
		</figure>

	</div>
	
	<div data-role="content" data-theme="c">
		<form method="post" action="demoform.asp">
			<hr>
			<div class="attribute">产品名称：考拉</div>
			<hr>
			<div class="attribute">规格：100cm</div>
			<hr>
			<div class="attribute">积分：10000</div>
			
			<div data-role="navbar">
			  <ul>
				<li><a href="#" class="ui-btn-active ui-state-persist">商品介绍</a></li>
				<li><a href="#pagetwo">规格参数</a></li>
				<li><a href="#pagetwo">包装售后</a></li>
			  </ul>
			</div>
		</form>
	</div>
	
	<div data-role="footer" data-position="fixed" style="overflow:hidden;">
		<div style="line-height:40px; font-size:12px; width:60%; float:left;">可用积分：9999  兑换所需积分：1000</div><div style="line-height:40px; float:left; width:40%; text-align:center; background-color:#FF7F00">立即兑换</div>
	</div>

</div>

</body>
</html>