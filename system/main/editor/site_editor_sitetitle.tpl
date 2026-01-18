<form id="editSiteTitleSmileForm" style="margin:0px; float: left; width: inherit;" method="post" action="/<?php echo $this->pagename ?>/?jqueryform=sitevars">
	<input id="namefield" type="hidden" name="name" value="prj_sitetitle">
	<input id="siteTitle" style="display: inline-block !important; border: 0; width: inherit;" onclick="focus();" type="text" autocomplete="off" 
	name="value" value="<?php echo htmlspecialchars($this->data->prj->vars->sitetitle) ?>">
</form>