//创建Utils 对象
var Utils = new Object();

/**
 * 删除列表中的一个记录
 */
Utils.remove = function(id, cfm, file) {
	if (id == '' || file == '') {
		return;
	}

	if (confirm(cfm)) {
		$.ajax({
			type:'POST',
			dataType:'json',
			url:file+'.php?do=Remove',
			data:'id=' + id,
			success:function(msg){
				alert(msg.info);
				
				if(msg.status) {
					window.parent.frames["manFrame"].location.reload();
				}
			}
		});
	}
}

/**
 * Cookie 操作方法
 */
document.getCookie = function(sName) {
  // cookies are separated by semicolons
  var aCookie = document.cookie.split("; ");
  
  for (var i=0; i < aCookie.length; i++) {
    // a name/value pair (a crumb) is separated by an equal sign
    var aCrumb = aCookie[i].split("=");
    if (sName == aCrumb[0])
      return decodeURIComponent(aCrumb[1]);
  }

  // a cookie with the requested name does not exist
  return null;
}

document.setCookie = function(sName, sValue, sExpires) {
  var sCookie = sName + "=" + encodeURIComponent(sValue);
  if (sExpires != null) {
    sCookie += "; expires=" + sExpires;
  }

  document.cookie = sCookie;
}

document.removeCookie = function(sName,sValue) {
  document.cookie = sName + "=; expires=Fri, 31 Dec 1999 23:59:59 GMT;";
}
