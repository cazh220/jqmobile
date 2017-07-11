<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css">
<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js"></script>
</head>
<body>

<div data-role="page">
  <div data-role="header">
  <div data-role="header"><a href="#" data-role="button" data-icon="arrow-l" data-rel="back">后退</a>
  <h1>技工录入</h1>
  </div>

  <div data-role="content" data-theme="b">
    <form method="post" action="demoform.asp">
      <div data-role="fieldcontain">
        <label for="fullname">全名：</label>
        <input type="text" name="fullname" id="fullname">       
        <label for="bday">生日：</label>
        <input type="date" name="bday" id="bday">
        <label for="email">电邮：</label>
        <input type="email" name="email" id="email" placeholder="您的邮箱地址..">
      </div>
      <input type="submit" data-inline="true" value="提交">
    </form>
  </div>
</div>

</body>
</html>