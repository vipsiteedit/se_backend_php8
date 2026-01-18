<footer:js>[include_js()]</footer:js>
<?php if($specdesign=='enable'): ?>

<footer:js>
[js:jquery/jquery.min.js]
[include_js({
    partid: '<?php echo $section->id ?>',
    style: '<?php echo $specstyle ?>',
    size: '<?php echo $specsize ?>',
    image: '<?php echo $specimage ?>',
    design: '<?php echo $specdesign ?>'
})]
</footer:js>
<footer:js>
    <link type="text/css" rel="Stylesheet" href="[module_url]allstyles.css" />
    <?php if($specstyle=='1'): ?>
       <link type="text/css" rel="Stylesheet" href="[module_url]style1.css" />
    <?php endif; ?>
    <?php if($specstyle=='2'): ?>
    <link type="text/css" rel="Stylesheet" href="[module_url]style2.css" />
    <?php endif; ?>
    <?php if($specstyle=='3'): ?>
    <link type="text/css" rel="Stylesheet" href="[module_url]style3.css" />
    <?php endif; ?>
</footer:js>
<div style="display: none;" id="infobardm">
<div class="container">
<div class="row">
    <span class="dme-item dme-item-font"> 
        <b><?php echo $section->language->lang006 ?></b>  
        <div class="block-item">
            <a href="?__specdesignsize__=1" class="dmchangea1">A</a>
            <a href="?__specdesignsize__=2" class="dmchangea2">A</a>
            <a href="?__specdesignsize__=3" class="dmchangea3">A</a>
        </div>
    </span>
    <span class="dme-item dme-item-img">
        <b><?php echo $section->language->lang007 ?></b>
        <div class="dme-img-icon"></div>
        <div class="block-item">
            <a href="?__specdesignimage__=off" class="dmdisableimage"><span class="big-text-img"><?php echo $section->language->lang001 ?></span><span class="small-text-img"><?php echo $section->language->lang009 ?></span></a>
            <a href="?__specdesignimage__=on" class="dmenableimage"><span class="big-text-img"><?php echo $section->language->lang002 ?></span><span class="small-text-img"><?php echo $section->language->lang010 ?></span></a>
        </div>
    </span>
    <span class="dme-item dme-item-color">   
        <b><?php echo $section->language->lang008 ?></b>
        <div class="block-item">
            <a href="?__specdesignstyle__=1" class="dmcolor1"><?php echo $section->language->lang003 ?></a>
            <a href="?__specdesignstyle__=2" class="dmcolor2"><?php echo $section->language->lang003 ?></a>
            <a href="?__specdesignstyle__=3" class="dmcolor3"><?php echo $section->language->lang003 ?></a>
        </div>
    </span>
    <span class="dme-item dme-item-reset">
        <a href="?__delspecdesign__" style="margin-left: 10px;" class="dmreset"><span class="dme-reset-text"><?php echo $section->language->lang005 ?></span><span class="dme-reset-img"></span></a>
    </span>
</div>
</div>
</div>

<?php else: ?>
<div class="content dmu part<?php echo $section->id ?>">
<a href="?__specdesign__" class="dmulink"><?php echo $section->language->lang004 ?></a>
</div>
<?php endif; ?>
