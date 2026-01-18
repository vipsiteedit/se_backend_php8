<form id="editImageForm" style="margin:0px;" method="post" enctype="multipart/form-data" action="/<?php echo $this->pagename ?>/?jqueryform=logotype">
<table border=0>
<tr><td align="center" valign="middle">
	<img src="<?php echo '/'.SE_DIR.$sitelogotype ?>" border=0>
</td></tr>
<tr><td>
	<div>&nbsp;</div>
</td></tr>
<tr><td>
	<input type="file" name="filename" id="add_img">
</td></tr>

</td></tr>

<tr><td>
	<input type="submit" value="Сохранить" name="GoToEditContent" id="sendData">
    <input type="button" value="Отмена" name="editData" id="editData" onClick="seLoadBox('','.groupItem#group_<?php echo $section->id ?> .contentImage','showsectionimage',<?php echo $section->id ?>)">
</td></tr>
</table>
</form> 