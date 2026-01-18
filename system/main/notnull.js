function Go.onsubmit()
{
  var str="";
  var n=0;
  
  for (i=0; i<document.frm.length; i++)
  {
  
    if ((document.frm[i].notnull == "1") && (document.frm[i].value == ""))
    {
      str = str + document.frm[i].title + "\n";
      n++;
    }  
  }
  
  if (n!=0)
  {
    window.event.returnValue = false;
    if (n==1)
    {
      alert("Не заполнено поле " + str);
    }
    else
    {
      alert("Следующие поля обязательны для заполнения:" + "\n" + str);
    }
  }
}
