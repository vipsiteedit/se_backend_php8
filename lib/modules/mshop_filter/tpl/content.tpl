<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param1)=='Y'): ?>
[lnk:ionrangeslider/ionrangeslider.min.css]  
[js:ionrangeslider/ionrangeslider.min.js]
<?php endif; ?>
[include_js({
    ajax_url: '?ajax<?php echo $section->id ?>',
    param4:'<?php echo $section->parametrs->param4 ?>',
    partNum: '<?php echo $section->id ?>',
    param2:'<?php echo $section->parametrs->param2 ?>',
    param3:'<?php echo $section->parametrs->param3 ?>',
    param1:'<?php echo $section->parametrs->param1 ?>'
     
})]
</footer:js> 
<?php if($filtercount!=0): ?> 
<div class="content shopFilter" data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" <?php echo $section->style ?>> 
<?php if(!empty($section->title)): ?> 
  <<?php echo $section->title_tag ?> class="contentTitle" <?php echo $section->style_title ?>><span class="contentTitleTxt"><?php echo $section->title ?></span></<?php echo $section->title_tag ?>> 
<?php endif; ?> 
<?php if(!empty($section->image)): ?> 
  <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_alt ?>">
<?php endif; ?> 
<?php if(!empty($section->text)): ?> 
  <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div> 
<?php endif; ?>
<div class="contentBody">
    <div class="filterNotify" style="display:none;">
        <div class="productsFound">
            <?php echo $section->language->lang001 ?> <span class="countFound"><?php echo $count_price_found ?></span>
        </div>
        <div class="showProducts">
            <a href="javascript:void(0);" title="<?php echo $section->language->lang002 ?>"><?php echo $section->language->lang006 ?></a>
        </div>
        <div class="notifyOverlay"></div>
    </div>
    <form style="margin:0px" method="get" id="filterForm" action="<?php if(!empty($page_vitrine)): ?><?php echo $page_vitrine ?><?php endif; ?>">
        <div class="filtersList" id="filterList<?php echo $section->id ?>">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_filters.php")) include $__MDL_ROOT."/php/subpage_filters.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_filters.tpl")) include $__data->include_tpl($section, "subpage_filters"); ?>
        </div>
            <div class="blockButton">     
                <button class="buttonSend btnSearch" title="<?php echo $section->language->lang002 ?>">
                    <span><?php echo $section->language->lang006 ?></span>
                </button>
                <button class="buttonSend btnClear" title="<?php echo $section->language->lang008 ?>">
                    <span><?php echo $section->language->lang007 ?></span>
                </button>
            </div>
    </form>
</div>
</div><?php endif; ?>
