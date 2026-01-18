<header:css>
  [include_css]
</header:css>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param7)=='Y'): ?>
[js:ui/jquery.ui.min.js]
<?php endif; ?>
[include_js({
'id':'<?php echo $section->id ?>',
'shopcart':'<?php echo $section->parametrs->param6 ?>',
'delitem':'<?php echo $section->language->lang006 ?>',
'curr':'<?php echo $pricemoney ?>',
'param7':'<?php echo $section->parametrs->param7 ?>',
'is_local':''
})]
</footer:js>
<footer:css>
[lnk:rouble/rouble.css]
</footer:css>
<header id="section-header" class="<?php if(strval($section->parametrs->param5)=='Y'): ?>section-header-fixed<?php endif; ?>">
<div class="<?php if(strval($section->parametrs->param2)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>" id="top_b_header">
    <div class="header-row row">
        <div class="logotype-area <?php if(!empty($section->text)): ?>col-lg-5 col-md-5 col-sm-6<?php endif; ?><?php if(empty($section->text)): ?>col-lg-6 col-md-6 col-sm-6<?php endif; ?> col-xs-12 clearfix">
            <a href="home.html">
                <div id="LogotypeBlock" data-seedit="logotype" data-se-maxh="80" data-se-maxw="280" title="<?php echo $section->image_title ?>">
                    <?php if(strval($__data->prj->vars->sitelogotype) != ''): ?>
                        <img src="[%sitelogotype%]" alt="<?php echo $section->image_alt ?>">
                    <?php else: ?>                    
                        <img src="[module_url]logo.png" alt="<?php echo $section->image_alt ?>">
                    <?php endif; ?>
                </div>
            </a>
            <div class="sitetitle-area">
                 <div class="sitetitle-title" data-seedit="sitetitle">[%sitetitle%]</div>
                 <div class="sitetitle-subtitle" data-seedit="sitesubtitle">[%sitesubtitle%]</div>
            </div>
        </div>
        <?php if(!empty($section->text)): ?>
          <div class="header-call_back col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <?php if(strval($section->parametrs->param9)=='Y'): ?>
                <div class="row">
                       <a href="#" data-toggle="modal" data-target="#callBackModal" class="b_call_back-call_button"><?php echo $section->language->lang003 ?></a>
                </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <div class="head-contacts <?php if(!empty($section->text)): ?>col-lg-4 col-md-4 col-sm-3<?php endif; ?><?php if(empty($section->text)): ?>col-lg-6 col-md-6 col-sm-6<?php endif; ?> col-xs-12">
            <div class="row">
                <div class="col-xs-12 left-contact">
                    <div class="contact-phone" data-seedit="contacts">
                        <a href="tel:[%sitephone%]" class="contact-header-tel">[%sitephone%]</a>
                        <div class="contact-header-mail"><?php echo $section->language->lang031 ?> <a href="mailto:[%sitemail%]">[%sitemail%]</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</header>
<?php if(strval($section->parametrs->param14)=='s'): ?>
<div class="modal fade header_modal" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?php echo $section->language->lang029 ?></h4>
      </div>
      <div class="modal-body">       
        <?php if(file_exists($__MDL_ROOT."/php/subpage_form.php")) include $__MDL_ROOT."/php/subpage_form.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_form.tpl")) include $__data->include_tpl($section, "subpage_form"); ?>
      </div>
    </div>
  </div>
</div>
<?php else: ?>
<footer:html>
<?php if(!empty($section->text)): ?>
<div class="modal fade" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <?php echo $section->text ?>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>
</footer:html>
<?php endif; ?>
<?php if(strval($section->parametrs->param5)=='Y'): ?>
<div class="section-header-space"></div>
<?php endif; ?>
