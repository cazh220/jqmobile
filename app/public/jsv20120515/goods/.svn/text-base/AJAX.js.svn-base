//AJAXµ÷ÓÃ
function showHint(sn,goods_id,obj_tag,url)
{ 
   if (window.XMLHttpRequest)
   {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
   }
   else
  {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
      xmlhttp.onreadystatechange=function()
     {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById(obj_tag).innerHTML=xmlhttp.responseText;
        }
     }   
     xmlhttp.open("GET",url,true);
	 document.getElementById(obj_tag).style.color = "red";
     xmlhttp.send();
}