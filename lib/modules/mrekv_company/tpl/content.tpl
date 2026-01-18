<a name="<?php $section->id ?>"></a><div class="content userRekv" <?php echo $section->style ?>>
<?php if(!empty($section->title)): ?>
  <<?php echo $section->title_tag ?> class="contentTitle"<?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
  <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
<div class="warning sysedit"><?php echo $warning_message ?></div>
<form style="margin:0px;" action="<?php echo $__data->getLinkPageName() ?>#<?php $section->id ?>" method="post" class="alldata">
<?php if($refer!=''): ?>
<input type="hidden" name="referer" value="<?php echo $refer ?>">
<?php endif; ?>
<div class="title">
  <?php echo $section->language->lang001 ?>
</div>
<div class="obj plant">
  <label><?php echo $section->language->lang002 ?></label>
  <div><input type="text" name="plant" value="<?php echo $_plant ?>"></div>
</div>
<div class="obj director">
  <label><?php echo $section->language->lang003 ?></label>
  <div><input type="text" name="director" value="<?php echo $_director ?>"></div>
</div>
<div class="obj uradres">
  <label><?php echo $section->language->lang004 ?></label>
  <div><input type="text" name="uradres" value="<?php echo $_uradres ?>"></div>
</div>
<div class="obj tel">
  <label><?php echo $section->language->lang005 ?></label>
  <div><input type="text" name="tel" value="<?php echo $_tel ?>"></div>
</div>
<div class="obj fax">
  <label><?php echo $section->language->lang006 ?></label>
  <div><input type="text" name="fax" value="<?php echo $_fax ?>"></div>
</div>
<div class="bankRekv">
<div class="titleBankRekv">
  <?php echo $section->language->lang007 ?>
</div>
<?php echo $SE_BANK_REKV_LIST ?>

</div>
<div class="buttonArea">
  <input type="submit" class="buttonSend saveButton" name="GoToRekv" value="<?php echo $section->language->lang008 ?>" <?php echo $dis ?>>
  <?php if($refer!=''): ?>
  <input onClick="document.location.href='<?php echo $refer ?>';" type="button" class="buttonSend backButton" value="<?php echo $section->language->lang009 ?>" <?php echo $dis ?>>
  <?php endif; ?>
</div>
</form>
</div>
