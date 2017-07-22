/**
 * 关联价设置
 * 2010-1-11 
 * 鲍
 */
function checkDo(obj, act, id , price){

  var tag = obj.firstChild.tagName;

  if (typeof(tag) != "undefined" && tag.toLowerCase() == "input"){
    return;
  }

  /* 保存原始的内容 */
  var org = obj.innerHTML;
  var val = Browser.isIE ? obj.innerText : obj.textContent;

  /* 创建一个输入框 */
  var txt = document.createElement("INPUT");
  txt.value = (val == 'N/A') ? '' : val;
  
  txt.style.width = (obj.offsetWidth + 12) + "px" ;

  /* 隐藏对象中的内容，并将输入框加入到对象中 */
  obj.innerHTML = "";
  obj.appendChild(txt);
  txt.focus();

  /* 编辑区输入事件处理函数 */
  txt.onkeypress = function(e)
  {
    var evt = Utils.fixEvent(e);
    var obj = Utils.srcElement(e);

    if (evt.keyCode == 13)
    {
      obj.blur();

      return false;
    }

    if (evt.keyCode == 27)
    {
      obj.parentNode.innerHTML = org;
    }
  }

  /* 编辑区失去焦点的处理函数 */
  txt.onblur = function(e)
  {

    if (Utils.trim(txt.value).length > 0)
    {
  		//如果关联价大于实价，就不修改;否则设置为新的价格为关联价	
		if(Utils.trim(txt.value) > price){	
			alert("对不起，您的输入有误！");
			txt.parentNode.innerHTML = org;
		}else{
			 var res = Ajax.call(listTable.url, "act="+act+"&price=" + encodeURIComponent(Utils.trim(txt.value)) + "&id=" +id, null, "POST", "JSON", false);
		  	 if (res.message){
        		alert(res.message);
  			 }
  			 
  			 if(res.id && (res.act == 'goods_auto' || res.act == 'article_auto')){
          		document.getElementById('del'+res.id).innerHTML = "<a href=\""+ thisfile +"?goods_id="+ res.id +"&act=del\" onclick=\"return confirm('"+deleteck+"');\">"+deleteid+"</a>";
		     }
		     
		     //如果结果为空就设为0
	     	if(res.content == '')
	     		res.content =  txt.value;
	
	     	txt.parentNode.innerHTML =  res.content;
		}
    }else{
      txt.parentNode.innerHTML = org;
    }
    
  }
}
