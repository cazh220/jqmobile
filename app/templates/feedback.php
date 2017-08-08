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
<script type="text/javascript">
{literal}
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: 'msg'
    ,time: 2 //2秒后自动关闭
  });
}

function commit()
{
	var feedback = $("#feedback").text();
	if (feedback == '')
	{
		show('请填写');
		return false;
	}
	
	var correction = $("#correct_info").val();
	if (correction == '')
	{
		show('请填写正确信息');
		return false;
	}

	$("#feedback").submit();
}

{/literal}	
</script>
<body>

<div data-role="page" data-theme="p">
  <div data-role="header"><a href="#" class="ui-btn ui-corner-all ui-icon-carat-l ui-btn-icon-notext" data-rel="back">后退</a>
  <h1>意见反馈</h1>
  </div>

  <div data-role="content">
    <form id="feedback" method="post" action="index.php?do=feedback" data-ajax="false">
      <label for="fname">您的意见反馈：</label>
      <textarea id="feedback" name="feedback" placeholder="请输入反馈内容"></textarea>
      <br><br>
	  <input type="hidden" id="action" name="action" value="1"/>
      <div style="text-align: right; width: 100%; float: left;"><a href="#" data-role="button" onclick="commit()" data-ajax="false">提交</a></div>  
    </form>
  </div>
</div>

</body>
</html>