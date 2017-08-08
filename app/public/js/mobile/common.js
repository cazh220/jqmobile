function note(note)
{
	//提示
  layer.open({
    content: note
    ,skin: 'msg'
    ,time: 2 //2秒后自动关闭
  });
  history.back();
}