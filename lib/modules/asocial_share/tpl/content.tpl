<style type='text/css'>
    .social_share #share42 {
        display: inline-block;
        padding: 6px 0 0 6px;
    <?php if(strval($section->parametrs->param6)=='white'): ?>
        background: #FFF;
        border: 1px solid #E9E9E9;
        border-radius: 4px;
    <?php endif; ?>          
    }
    .social_share #share42:hover {
    <?php if(strval($section->parametrs->param6)=='white'): ?>
        background: #F6F6F6;
        border: 1px solid #D4D4D4;
        box-shadow: 0 0 5px #DDD;
    <?php endif; ?>          
    }
    .social_share #share42 a {opacity: 0.7;}
    .social_share #share42:hover a {opacity: 0.7}
    .social_share #share42 a:hover {opacity: 1}    
    <?php if(strval($section->parametrs->param1)=='2'): ?>
    .social_share {
        margin-top:-10%; position:fixed; top:50%;
        <?php if(strval($section->parametrs->param5)=='left'): ?>
            left:0px; right: none;
        <?php else: ?>
            right:0px; left: none;
        <?php endif; ?>
    }
    <?php endif; ?>
</style>
<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param1)=='1'): ?>
[module_js:social1.js]
<?php else: ?>
<?php if($oth_code!=0): ?>
[module_js:social2.js]
</else>
[module_js:social3.js]
<?php endif; ?><?php endif; ?>
<script type="text/javascript">share42('[this_url_modul]'<?php if(strval($section->parametrs->param1)==2): ?>, 150, 20<?php endif; ?>)</script>
</footer:js>
<div class="social_share" <?php echo $section->style ?>>
<?php if(!empty($section->title)): ?>
    <h3 class="contentTitle" <?php echo $section->style_title ?>>
        <span class="contentTitleTxt"><?php echo $section->title ?></span>
    </h3>
<?php endif; ?>
<?php if(!empty($section->image)): ?>
    <img class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
<?php endif; ?>
<?php if(!empty($section->text)): ?>
    <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
<?php endif; ?>
    <div class="contentBody">
        <div class="share42init" data-url="<?php echo $url ?>" data-title="<?php echo $section->title ?>"></div>
    </div>
</div>
