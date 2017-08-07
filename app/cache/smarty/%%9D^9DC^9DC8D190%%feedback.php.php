<?php /* Smarty version 2.6.10, created on 2017-08-07 16:02:21
         compiled from feedback.php */ ?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
<script src="public/layer_mobile/layer.js"></script>
</head>
<script type="text/javascript">
<?php echo '
function show(note)
{
	//提示
  layer.open({
    content: note
    ,skin: \'msg\'
    ,time: 2 //2秒后自动关闭
  });
}

function commit()
{
	var feedback = $("#feedback").text();
	if (feedback == \'\')
	{
		show(\'请填写\');
		return false;
	}
	
	var correction = $("#correct_info").val();
	if (correction == \'\')
	{
		show(\'请填写正确信息\');
		return false;
	}

	$("#feedback").submit();
}

'; ?>
	
</script>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>意见反馈</h1>
  </div>

  <div data-role="content" data-theme="c">
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