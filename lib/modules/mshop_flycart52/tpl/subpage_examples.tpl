<header:js>
[js:jquery/jquery.min.js]
</header:js>
<script style="text/javascript">
$(document).ready(function(){
    $(".testShowAjaxIcon").toggle(function(){
        $('.loaderAjax').show();
        $(this).attr('value','<?php echo $section->language->lang013 ?>');
    }, function(){
        $('.loaderAjax').hide();
        $(this).attr('value','<?php echo $section->language->lang006 ?>');
    });
    
    $("#emptyAllGoods").toggle(function(){
        $('.noGoods').show();
        $('.issetGoods, .goodInfo').hide();
        $(this).attr('value','<?php echo $section->language->lang014 ?>');
    }, function(){
        $(".issetGoods, .goodInfo:not(.defHidden)").show();
        $('.noGoods').hide();
        $(this).attr('value','<?php echo $section->language->lang007 ?>');
    });
    
});                             
</script>
<div class="content contFlyCart">
<h3><?php echo $section->language->lang001 ?></h3>
<p><?php echo $section->language->lang002 ?> -> <?php echo $section->language->lang003 ?> -> <?php echo $section->language->lang004 ?> -> <?php echo $section->language->lang005 ?></p>
<input class="testShowAjaxIcon" type="button" value="<?php echo $section->language->lang006 ?>">
<br/><br/>
<input id="emptyAllGoods" type="button" value="<?php echo $section->language->lang007 ?>">
<br/><br/>
<input class="sysedit testShortExtCart" type="button" title="<?php echo $section->language->lang015 ?>" value="<?php echo $section->language->lang016 ?>">
<table border="0" align>
<tr align="center">
    <td>
        &nbsp;
    </td>
    <td>
        <?php echo $section->language->lang008 ?>
    </td>
    <td>
        <?php echo $section->language->lang004 ?>
    </td>
    <td>
        <?php echo $section->language->lang005 ?>
    </td>
    <td>
        <?php echo $section->language->lang009 ?>
    </td>
</tr>
<tr>
    <td>
        <?php echo $section->language->lang010 ?>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>    
        </div>    
    </td>
    <td>
        <div id="fixedCart" class="fixedCart activeCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart hoverCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart activeCart hoverCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
</tr>
<tr>
    <td>
        <?php echo $section->language->lang003 ?>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>    
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart activeCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart hoverCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart activeCart hoverCart">
            <?php if(file_exists($__MDL_ROOT."/php/subpage_cart.php")) include $__MDL_ROOT."/php/subpage_cart.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_cart.tpl")) include $__data->include_tpl($section, "subpage_cart"); ?>
        </div>
    </td>
</tr>
</table>
</div>
