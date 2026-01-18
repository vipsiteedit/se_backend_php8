<?php if($filtercount!=0): ?>  
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
<?php endif; ?>
