<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<title>Image Manager</title>
	
	<link rel="stylesheet" type="text/css" href="/system/main/editor/imanager/css/imanager.css" />

	<script type="text/javascript" src="/lib/js/jquery/jquery.min.js"></script>
	<script type="text/javascript" src="/lib/js/jquery/jquery-colors.js"></script>
	<script type="text/javascript" src="/lib/js/jquery/jquery.form.js"></script>
	<script type="text/javascript" src="/lib/js/jquery/jquery.MultiFile.js"></script>
	
	<!--  SWFUpload -->
	<script type="text/javascript" src="/lib/js/swfupload/swfupload.js"></script>
	<!--script type="text/javascript" src="/lib/js/swfupload/swfupload.swfobject.js"></script-->
	<script type="text/javascript" src="/lib/js/swfupload/swfupload.queue.js"></script>
	<script type="text/javascript" src="/lib/js/swfupload/fileprogress.js"></script>
	<script type="text/javascript" src="/lib/js/swfupload/handlers.js"></script>
	<!-- /SWFUpload -->
	<script type="text/javascript" src="/system/main/editor/imanager/js/images.js"></script>
</head>
<body>
<?php
//chdir(dirname(__FILE__)); 
$path = '/system/main/editor/imanager/'; 
?>
<div id="upload">
 <div id="shadowBack"></div>
 <div id="uploadWindow"><div id="uploadWindow1">
 
  <div id="uploadClose"></div>
  <!--div id="uploadMenu">
   <a href="#" class="act" id="uploadAreaNormalControl">Обычная загрузка</a>
   <a href="#" id="uploadAreaMultiControl">Мультизагрузка</a>
  </div-->
  
  <!-- Нормальная загрузка -->
  <!--div id="uploadAreaNormal">
  <form action="<?php echo $path ?>connector.php" enctype="multipart/form-data" method="post" id="filesForm"><fieldset>
   <input type="hidden" name="action" value="uploadfile" />
   <input type="hidden" name="path" id="normalPathVal" value="" />
   <input type="hidden" name="pathtype" id="normalPathtypeVal" value="" />
   <div>Выберите файл:</div>
   <div id="filesHolder">
    <input type="file" id="fileOpen" class="fileOpen" />
   </div>
   <div>Укажите размер изображения:</div>
   <select name="maxwidth">
	<option value="0">Оригинал</option>
	<option value="100">100px</option>
	<option value="150">150px</option>
	<option value="200">200px</option>
	<option value="250">250px</option>
	<option value="400">400px</option>
	<option value="500">500px</option>
	<option value="800">800px</option>

   </select>
   <div id="fileNormalSubmit">
    <img src="<?php echo $path ?>img/ajax-loader-files.gif" width="43" height="11" alt="Загрузка" id="normalLoader" />
    <span id="normalResult">Файлы загружены</span>
    <input type="submit" id="normalSubmit" value="Загрузить" />
   </div>
  </fieldset></form>
  </div-->
  
  <!-- Мультизагрузка -->
  <div id="uploadAreaMulti">
  <form id="form1" action="<?php echo $path ?>connector.php" method="post" enctype="multipart/form-data">
<!--
	<fieldset>
		<input type="hidden" name="action" value="uploadimage" />
		<input type="hidden" name="path" id="multiPathVal" value="" />
		<input type="hidden" name="pathtype" id="multiPathtypeVal" value="" />
	</fieldset>
-->
		<div id="divSWFUploadUI">
   <div>Укажите размер изображения:</div>
   <script>
   	var maxwidth = 0;
	function setWidth(width){
	   $.post('<?php echo $path ?>connector/php/index.php', {action: 'changewidth' ,maxwidth: width});
	}
	//document.getElementById("maxwidth")[0].value;
	//alert(maxwidth);

   </script>
   <select id="maxwidth" name="maxwidth" id="maxsize" onChange="setWidth(this.value);">
	<option value="0">Оригинал</option>
	<option value="100">100px</option>
	<option value="150">150px</option>
	<option value="200">200px</option>
	<option value="250">250px</option>
	<option value="400">400px</option>
	<option value="500">500px</option>
	<option value="800">800px</option>
   </select><br><br>
			<div>Выберите несколько файлов:</div>
			<div id="btnsBar">
			 <div id="turboBtn1"><div id="turboBtn">
			  <div id="btnUploadOver"><span id="spanButtonPlaceholder"></span></div>
			  <input id="btnUpload" type="button" value="Обзор&#133;" />
			 </div></div>
			 <input id="btnCancel" type="button" value="Отменить все загрузки" disabled="disabled" />
			</div>
			<div id="fsUploadProgress">
			 <span class="legend">&nbsp;</span>
			 <span id="divStatus"></span>
			</div>
			<br style="clear: both;" />
		</div>
		<div id="divLoadingContent" class="SWFcontent">SWFUpload загружается. Одну секундочку...</div>
		<div id="divLongLoading" class="SWFcontent">SWFUpload слишком долго загружается или произошла ошибка загрузки, убедитесь что корректно установлен Flash Player.</div>
		<div id="divAlternateContent" class="SWFcontent">SWFUpload не может быть загружен. Необходимо установить или обновить Flash Player. Посетите <a href="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash">сайт Adobe</a> чтоб скачать Flash Player.</div>
	</form>
  </div>
  
  <div id="uploadTarget"></div>
  
 </div></div>
</div>



<div id="insertImage" title="Вставить изображение"><img src="<?php echo $path ?>img/icon.gif" width="20" height="20" alt="Вставить изображение" /></div>

<div id="addrBar"><div class="addrBar1"><div class="addrBar2" id="addr">
</div></div></div>

<div style="clear:both;"></div>


<div id="toolBar"><div class="toolBar1"><div class="toolBar2">
	<div class="toolItem" id="menuCreateFolder">
		<img src="<?php echo $path ?>img/folder_plus.png" width="16" height="16" alt="Создать папку" />
		<span>Создать папку</span>
	</div>
	<div class="toolItem" id="menuDelFolder">
		<img src="<?php echo $path ?>img/folder_minus.png" width="16" height="16" alt="Удалить папку" />
		<span>Удалить папку</span>
	</div>
	<div class="toolItem" id="menuCancelFolder">
		<img src="<?php echo $path ?>img/cross_circle_frame.png" width="16" height="16" alt="Отменить создание папки" />
		<span>Отменить создание</span>
	</div>
	<div class="toolItem" id="menuSaveFolder">
		<img src="<?php echo $path ?>img/tick_circle_frame.png" width="16" height="16" alt="Подтвердить создание папки" />
		<span>Создать</span>
	</div>
	<div class="toolItem" id="menuUploadFiles">
		<img src="<?php echo $path ?>img/images_plus.png" width="16" height="16" alt="Загрузить файлы" />
		<span>Загрузить файлы</span>
	</div>
	<div class="toolItem" id="menuDelFiles">
		<img src="<?php echo $path ?>img/images_minus.png" width="16" height="16" alt="Удалить файлы" />
		<span>Удалить файлы</span>
	</div>
	
	<div id="loader">
		<img src="<?php echo $path ?>img/ajax-loader.gif" width="16" height="16" alt="Загрузка" />
	</div>
</div></div></div>

<div style="clear:both;"></div>


<table id="mainField" cellpadding="0" cellspacing="0"><tr>
 <td valign="top" id="mainTree"><div id="mainTreeHolder">
  <div id="tree">

  </div>
 </div></td>
 <td valign="top" id="mainFiles">
  <div id="files">

   
   <div style="clear:both;"></div>
  </div>
 </td>
</tr></table>


<div id="foot">
 <table cellpadding="0" cellspacing="0" id="footTable">
  <tr>
   <td width="35%">
    <table id="footTableName">
     <tr>
      <td id="fileName">Дарк</td>
      <td><img src="<?php echo $path ?>img/pencil_small.png" width="16" height="16" alt="Редактировать" id="fileNameEdit" /><img src="<?php echo $path ?>img/tick_small.png" width="16" height="16" alt="Сохранить" id="fileNameSave" /></td>
     </tr>
    </table>
   </td>
   <td width="20%" class="footLabel" id="footDateLabel">Дата загрузки:</td>
   <td width="20%" id="footDate">28.11.2008 10:11</td>
   <td class="footLabel" id="footDimLabel">Размеры:</td>
   <td id="footDim">1600x1200</td>
  </tr>
  <tr>
   <td id="footExt">Всего файлов:</td>
   <td class="footLabel" id="footLinkLabel">Ссылка на файл:</td>
   <td id="footLink"><img src="<?php echo $path ?>img/chain.png" width="16" height="16" alt="Ссылка" style="vertical-align:sub" /> <a href="#" target="_blank">IMG_0954</a></td>
   <td class="footLabel">Размер:</td>
   <td id="footSize">0</td>
  </tr>
 </table>
</div>
</body>
</html>