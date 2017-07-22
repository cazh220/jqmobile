//AJAX调用
function remove_cat(cat_id)
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
            document.getElementById('te').innerHTML=xmlhttp.responseText;
        }
     }   
	 url = "category.php?do=DeleteCategory&catid="+cat_id;
     xmlhttp.open("GET",url,true);
     xmlhttp.send();
}