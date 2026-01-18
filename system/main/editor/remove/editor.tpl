     <script type='text/javascript' charset=utf-8">
         <!--
         tinyMCE.init({	
            language : "ru",
            mode : "exact",
            elements : "edittar",
            convert_urls : false,
            theme : "advanced",
            force_br_newlines : true,
            forced_root_block : false,
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            //theme_advanced_statusbar_location : "bottom"
         });
      -->
    </script>
<?php 
	$tab = $_GET['edittab_'.$section->id];
	if (empty($tab)) $tab = 'cont';
?>
<style>
#cont_txt_edit {position:absolute; WIDTH:100%; HEIGHT: 100%; TOP: 0px; LEFT: 0px; z-index:999;}
#enterbg {position:absolute; WIDTH:100%; HEIGHT: 100%; TOP: 0px; LEFT: 0px; opacity: 0.5; filter: alpha(Opacity=50); background-color:#000000; z-index:998;}
</style>
<div id="cont_txt_edit">
<table border="0" cellSpacing="0" cellPadding="0" width="100%" align="center" height="100%">
  <tbody>
  <tr>
    <td vAlign="middle" align="center">
	<form style="margin:0px;" method="post">
	<table border="0" cellSpacing="0" cellPadding="0" width="800" height="600" style="color:#444444; position:relative; background-color:#cccccc; z-index:10000; opacity: 1;">
	<tr>
		<td align="center" height="30"><a href="?<?php echo 'edittab_'.$section->id ?>=cont">Раздел</a></td>
		<td align="center"><a href="?<?php echo 'edittab_'.$section->id ?>=records">Записи</a></td>
		<td align="center"><a href="?<?php echo 'edittab_'.$section->id ?>=type">Тип раздела</a></td>
		<td align="center"><a href="?<?php echo 'edittab_'.$section->id ?>=param">Парметры</a></td>
		<td align="center">Языки</td>
		<td align="center">Доступ</td>
	</tr>
	<tr>
		<td colspan=6 vAlign="top">
		<table width=100% height=100% style="color:#000000;" cellSpacing="5" cellPadding="0">
		<tbody>
		<?php if($tab == 'cont'): ?>
		<tr>
			<td width="120" height="25">Заголовок раздела</td>
			<td><input style="width:98%;" name="parttitle" value="<?php echo $section->title ?>"></td>
		</tr>
		<tr>
			<td height="450">Текст</td>
			<td><textarea id="edittar" name="parttext" style="width:100%; height:450px;" class="inparea" cols="10" rows="20"><?php echo $section->text ?></textarea></td>
		</tr>
		<tr>
			<td height="25">Рисунок</td>
			<td><input type="file" style="width:100%;" name="title"><input type="hidden" name="image" value="<?php echo $section->image ?>"></td>
		</tr>
		<?php endif; ?>

		<?php if($tab == 'records'): ?>
		<tr>
			<td width="120" height="25">Заголовок</td>
			<td><input style="width:100%;" name="title" value="<?php echo $section->title ?>"></td>
		</tr>
		<tr>
			<td height="450">Текст</td>
			<td><textarea name="text" style="WIDTH:100%; height:100%;" class="inparea" cols="10" rows="10"><?php echo $section->text ?></textarea></td>
		</tr>
		<tr>
			<td height="25">Рисунок</td>
			<td><input type="file" style="width:100%;" name="title"><input type="hidden" name="image" value="<?php echo $section->image ?>"></td>
		</tr>
		<?php endif; ?>

		</tbody>
		</table>
		</td>
	</tr>
		<tr>
			<td colspan=3></td>
			<td height="25" colspan=3><input class="buttonSend" name="GoToEditContent" value="Применить" type="submit"></td>
		</tr>
	</tbody></table></form>
	</td>
	</tr>
	</tbody></table>
	<div id="enterbg"></div>
</div>