<header:css>
[include_css]
</header:css>
<div class="<?php if(strval($section->parametrs->param4)=='n'): ?>container<?php else: ?>container-fluid<?php endif; ?>">
<section class="b_footer_contact">
    <div class="addr itms">
       <i class="icon"></i> <lable><?php echo $section->language->lang001 ?></label> <span class="htext"><?php echo $section->parametrs->param1 ?></span>
    </div>
    <div class="phone itms">
       <i class="icon"></i> <lable><?php echo $section->language->lang002 ?></label> <span class="htext"><?php echo $section->parametrs->param2 ?></span>
    </div>
    <div class="email itms">
       <i class="icon"></i> <lable><?php echo $section->language->lang003 ?></label> <span class="htext"><a href="mailto:<?php echo $section->parametrs->param3 ?>"><?php echo $section->parametrs->param3 ?></a></span>
    </div> 
  <div class="social itms">
    <script type="text/javascript" src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="https://yastatic.net/share2/share.js" charset="utf-8"></script>
    <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter" data-size="s"></div>
  </div>
</section>
</div>
