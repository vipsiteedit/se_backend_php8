<div class="content">
  <div id="view">
    <h4 class="objectTitle"><?php echo $titlepage ?></h4>
    <?php if($imgfull!=''): ?>
    <div id="objimage">
        <img class="objectImage" alt="<?php echo $titlepage ?>" src="<?php echo $imgfull ?>" border="0">
    </div>
    <?php endif; ?>
    <div class="objectText">
    <?php echo $fulltext ?>
    </div>
    <input class="buttonSend" onclick="document.location = '<?php echo $__data->getLinkPageName() ?>'" type="button" value="<?php echo $section->language->lang015 ?>">
  </div>
</div>
