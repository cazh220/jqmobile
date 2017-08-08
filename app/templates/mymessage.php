<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="public/mobile_themes/themes/skyd.min.css" />
<link rel="stylesheet" href="public/mobile_themes/themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.3/jquery.mobile.structure-1.4.3.min.css" />
<link rel="stylesheet" href="templates/css/style.css">
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.3/jquery.mobile-1.4.3.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<body>

<div data-role="page" data-theme="p">
  <div data-role="header" data-position="fixed"><a href="#" class="ui-btn ui-corner-all ui-icon-carat-l ui-btn-icon-notext" data-rel="back">后退</a>
  <h1>我的消息</h1>
  </div>

    <div data-role="content">	
			<ul data-role="listview" data-inset="true"> 
				{if $list}
					{foreach from=$list item=item key=key}
		      <li><a href="#pagethree">   
		        <div class="blank"></div>
		        <div class="blank"></div>
		        {if $item.type == 1}
		        <p>{$item.realname}纠正了您于{$item.record_time}录入的{$item.name}的信息，<span id="link" class="link_style" onclick="link_u({$item.qrcode})">点击这里</span>更正此信息，即就可获得30积分。</p>
		        {/if}
		        <p class="ui-li-aside" style="width:80%"><b>From:</b>{$item.realname} &nbsp;&nbsp;<b>Time：</b>{$item.send_time}  &nbsp;&nbsp;已读</p>
		       </a>
		      </li>
		      {/foreach}
		    {/if}
		    </ul>
  	</div>

</body>
<script type="text/javascript">
{literal}
function link_u(qrcode)
{
	window.location.href="user.php?do=patientin&user_id=25&qrcode="+qrcode;
}
{/literal}
</script>
</html>