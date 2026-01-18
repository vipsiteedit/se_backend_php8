<header:js>
[js:jquery/jquery.min.js]
<script type='text/javascript' src='[module_url]jquery.lightbox.js'></script>
</header:js>
<script type="text/javascript">
$(document).ready(function(){ 
$("#photo a").lightBox({ 
txtImage:'&nbsp;<?php echo $section->parametrs->param248 ?>',
txtOf:'<?php echo $section->parametrs->param250 ?>',
overlayOpacity: 0.6, 
fixedNavigation:true, 
imageLoading: '[module_url]lightbox-ico-loading.gif', 
imageBtnPrev: '[module_url]foto_arrow_left.png', 
imageBtnNext: '[module_url]foto_arrow_right.png', 
imageBtnClose: '[module_url]foto_close.gif', 
imageBlank: '[module_url]lightbox-blank.gif' 
});
});
</script>
<header:js><style type="text/css">
#jquery-overlay {
 position: absolute;
 top: 0;
 left: 0;
 z-index: 10000;
 width: 100%;
 height: 500px;
}
#jquery-lightbox {
 position: absolute;
 top: 0;
 left: 0;
 width: 100%;
 z-index: 10010;
 text-align: center;
 line-height: 0;
}
#jquery-lightbox a img { border: none; }
#lightbox-container-image-box {
 position: relative;
 background-color: #fff;
 width: 250px;
 height: 250px;
 margin: 0 auto;
}
#lightbox-container-image { padding: 10px; }
#lightbox-loading {
 position: absolute;
 top: 40%;
 left: 0%;
 height: 25%;
 width: 100%;
 text-align: center;
 line-height: 0;
}
#lightbox-nav {
 position: absolute;
 top: 0;
 left: 0;
 height: 100%;
 width: 100%;
 z-index: 10005;
}
#lightbox-container-image-box > #lightbox-nav { left: 0; }
#lightbox-nav a { outline: none;}
#lightbox-nav-btnPrev, #lightbox-nav-btnNext {
 width: 49%;
 height: 100%;
 zoom: 1;
 display: block;
}
#lightbox-nav-btnPrev { 
 left: 0; 
 float: left;
}
#lightbox-nav-btnNext { 
 right: 0; 
 float: right;
}
#lightbox-container-image-data-box {
 font: 10px Verdana, Helvetica, sans-serif;
 background-color: #fff;
 margin: 0 auto;
 line-height: 1.4em;
 overflow: auto;
 width: 100%;
 padding: 0 10px;
}
#lightbox-container-image-data { 
 color: #666; 
}
#lightbox-container-image-data #lightbox-image-details { 
 width: 70%; 
 float: left; 
 text-align: left; 
} 
#lightbox-image-details-caption { font-weight: bold; }
#lightbox-image-details-currentNumber {
 display: block; 
 clear: left; 
 padding-bottom: 1.0em; 
} 
#lightbox-secNav-btnClose {
 width: 70px; 
 float: right;
 padding-bottom: 0.7em; 
}
</style></header:js> 
<div class="morephotos">
    <h3 class="goodsMorephotoHat"><?php echo $section->parametrs->param26 ?></h3>
    <?php if($section->parametrs->param282=='L'): ?><!-- Использовать экранную лупу -->
        <div>        
            <?php foreach($section->photos as $foto): ?>
                <a class="cloud-zoom-gallery" href="<?php echo $foto->image ?>" title="" rel="useZoom: 'zoom1', smallImage: '<?php echo $foto->image_mid ?>'">
                    <img src="<?php echo $foto->image_prev ?>" alt="" width="<?php echo $section->parametrs->param285 ?>">
                </a>
            
<?php endforeach; ?>
        </div>
    <?php else: ?>    <!-- Не использовать экранную лупу, используем jquery lightbox plugin -->
        <div id="photo">
            <?php foreach($section->photos as $foto): ?>
                <a rel="lightbox-foto" href="<?php echo $foto->image ?>" title="<?php echo $foto->title ?>">
                    <img src="<?php echo $foto->image_prev ?>" class="imgAll" alt="<?php echo $foto->title ?>" border="0">
                </a>
            
<?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
