<script type="text/javascript" src="/lib/js/jquery/jquery.min.js"></script>
<div id="entersite" class="entersite">
<center>
<div id="enterdiv" style="-webkit-border-radius:15px 15px 15px 15px; -moz-border-radius:15px 15px 15px 15px; border-radius:15px 15px 15px 15px; width:430px;">
<FORM action="" method=post>
<table border="0" cellpadding="2" cellspacing="0" width="" align="center" style="color:#ffffff;font-size:18px;">
<tr align="left">
<td colspan="2" height="35px"><A id="link" href="registration.html">Регистрация</A></td>
<td><span style="color:#FFFFFF;font-size:11px;cursor:pointer;position:relative;left:50px;top:-20px;" onClick="closeWindow()">Закрыть [x]<span></td>
<td>&nbsp;</td>
</tr>
<tr height="35px" align="left">
<td><INPUT type="hidden" value="true" name="authorize"><input class="srchpole"  style="width=245px; height: 33px;" value="" title="Ваш логин" maxLength="60" name="authorlogin"></td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr align="left">
<td><input class="srchpole" style="width=245px; height: 33px;" value="" title="Ваш пароль" type="password" maxLength="60" name="authorpassword"></td>
<td>&nbsp;&nbsp;</td>
<td><input style="cursor:pointer;color:#000000; height: 40px;" id="authorsend" type="submit" value="Вход" name="GoToAuthor"></td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="4" height="30px" valign="top" align="left" id="authorSave"><input style="border: 0;" id="authorSaveCheck" type="checkbox" name="authorSaveCheck" value="1">&nbsp;Запомнить
<!-- / <A id=forget href="remember.html">Напомнить</A--></td>
</tr>
</table>
</FORM>
</div>
</center>
</div>
<div id="enterbg" class="enterbg"></div>
<style>
#entersite {position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;z-index:999;}
//.entersite {VISIBILITY:hidden;}
.entersite2 {VISIBILITY:visible;}

#enterbg {width:100%;}
.enterbg {display:none;}
.enterbg2 {display:block; height:3000px; background-color:#000000; opacity: 0.5; filter: alpha(Opacity=50); z-index:998; position:absolute; top:0px; left:0px;}

#entersite a {color:#ffffff;}
#entersite input {font-size:30px;}

#enterdiv {width:430px; background-color:#0860a8; position:relative; z-index:1000; text-align: left; margin-top: 150px; padding: 30px; border: 3px solid #fff;}
.srchpole {width:100%;}
</style>

<script type="text/javascript">

  $(document).ready(function(){
  
 var wsize = windowWorkSize(),                                               // размеры "рабочей области"
     testElem = document.getElementById("entersite"),                        // entersite, ложим наш блок в переменную
     testElemWid =  testElem.offsetWidth,                                    // ширина блока
     testElemHei =  testElem.offsetHeight;                                   // высота блока
	testElem.style.left = wsize[0]/2 - testElemWid/2 + "px";        // центрируем блок по горизонтали
	testElem.style.top = wsize[1]/2 - testElemHei/2 + (document.body.scrollTop || document.documentElement.scrollTop) + "px";    // центрируем блок по вертикали + скролл

    window.document.onscroll = function(){                                           // цетрируем по событию onclick
	testElem.style.left = wsize[0]/2 - testElemWid/2 + "px";        // центрируем блок по горизонтали
	testElem.style.top = wsize[1]/2 - testElemHei/2 + (document.body.scrollTop || document.documentElement.scrollTop) + "px";    // центрируем блок по вертикали + скролл
    };

   // фунция определения "рабочего пространства"
   function windowWorkSize(){
       var wwSize = new Array();
       if (window.innerHeight !== undefined) wwSize= [window.innerWidth,window.innerHeight]    // для основных браузеров
       else    
       {   // для "особо одарённых" (ИЕ6-8)
           wwSizeIE = (document.body.clientWidth) ? document.body : document.documentElement; 
           wwSize= [wwSizeIE.clientWidth, wwSizeIE.clientHeight];
       };
       return wwSize;
   };

  }); 
  function closeWindow(){
	$('#se_editor_box').html('');
  }
</script>
