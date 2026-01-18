<header:js>
    [js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript" src="[module_url]jquery.rating.js"></script>

    <script type="text/javascript" src="[module_url]jquery.MetaData.js"></script>
    <script type="text/javascript" src="[module_url]documentation.js"></script>

<link href='[module_url]jquery.rating.css' type="text/css" rel="stylesheet">
<style type="text/css">
    #gallery ul li { display: inline; }
</style> 
<script type="text/javascript">
    $(function(){ // wait for document to load
        $('input.wow').rating();
    });
</script>
<script type="text/javascript">
    function loadBox(id, name, idlink) {
        $('#' + id).load("/<?php echo $_page ?>/<?php echo $razdel ?>/sub4/?" + name + "&idlink=" + idlink, {});
    }
</script>
<script type="text/javascript" src="[module_url]jquery.lightbox-0.5.js"></script>
<link rel="stylesheet" type="text/css" href="[module_url]jquery.lightbox-0.5.css" media="screen">
<script type="text/javascript">
    $(function() {
        $('#gallery a').lightBox({
            overlayBgColor: '#fff',
            overlayOpacity: 0.6,
            imageLoading: '[module_url]lightbox-ico-loading.gif',
            imageBtnClose: '[module_url]lightbox-btn-close.gif',
            imageBtnPrev: '[module_url]left.png',
            imageBtnNext: '[module_url]right.png',
            containerResizeSpeed: 350,
            txtImage: 'Скриншот',
            txtOf: 'из'
        });
    });
</script>
<div class="content">
    <div class="contPodrFCtlg" id="view">
        <h4 class="objectTitle"><?php echo $titlepage ?></h4>
        
        <?php echo $picture ?>
        <div class="doppole">    
            <?php if($section->parametrs->param73=="on"): ?>
                <div class="obj pole1">
                    <label class="title" for="name"><?php echo $section->parametrs->param72 ?></label>
                    <div class="field"><?php echo $pole1txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param75=="on"): ?>
                <div class="obj pole2">
                    <label class="title" for="name"><?php echo $section->parametrs->param74 ?></label>
                    <div class="field"><?php echo $pole2txt ?></div> 
                </div>
            <?php endif; ?>  
            <?php if($section->parametrs->param77=="on"): ?>
                <div class="obj pole3">
                    <label class="title" for="name"><?php echo $section->parametrs->param76 ?></label>
                    <div class="field"><?php echo $pole3txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param79=="on"): ?>
                <div class="obj pole4">
                    <label class="title" for="name"><?php echo $section->parametrs->param78 ?></label>
                    <div class="field"><?php echo $pole4txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param81=="on"): ?>
                <div class="obj pole5">
                    <label class="title" for="name"><?php echo $section->parametrs->param80 ?></label>
                    <div class="field"><?php echo $pole5txt ?></div> 
                </div>
            <?php endif; ?>  
            <?php if($section->parametrs->param83=="on"): ?>
                <div class="obj pole6">
                    <label class="title" for="name"><?php echo $section->parametrs->param82 ?></label>
                    <div class="field"><?php echo $pole6txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param85=="on"): ?>
                <div class="obj pole7">
                    <label class="title" for="name"><?php echo $section->parametrs->param84 ?></label>
                    <div class="field"><?php echo $pole7txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param87=="on"): ?>
                <div class="obj pole8">
                    <label class="title" for="name"><?php echo $section->parametrs->param86 ?></label>
                    <div class="field"><?php echo $pole8txt ?></div> 
                </div>
            <?php endif; ?>  
            <?php if($section->parametrs->param89=="on"): ?>
                <div class="obj pole9">
                    <label class="title" for="name"><?php echo $section->parametrs->param88 ?></label>
                    <div class="field"><?php echo $pole9txt ?></div> 
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param91=="on"): ?>
                <div class="obj pole10">
                    <label class="title" for="name"><?php echo $section->parametrs->param90 ?></label>
                    <div class="field"><?php echo $pole10txt ?></div> 
                </div>
            <?php endif; ?>
        </div>
        <div class="objectText">
            <?php echo $fulltext ?>
        </div>
        <div class="skrinsots">
            <div id="gallery">
                <ul>
                    
                    <?php echo $skrinvivod ?>
                </ul>
            </div>
        </div>
        <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">     
            <div class="rating">
                <div class="rating_stars"> 
                    
                    <?php echo $ratingtext ?>      
                </div>
                
                <?php echo $ratinggolos ?>  
                <input class="buttonSend goRtngButton" name="GoRating" type="submit" value="<?php echo $section->parametrs->param68 ?>"> 
            </div>
        </form>  
        <div class="obj prosmotri">
            <div class="name"><?php echo $section->parametrs->param102 ?></div>
            <b class="text"><?php echo $viewing ?></b>
        </div>
        <div class="obj statistics">
            <div class="name"><?php echo $section->parametrs->param14 ?></div>
            <b class="text"><?php echo $statistics ?></b>
        </div>
        <input class="buttonSend buttonDowload statisticsbtn"  type="button" onclick="loadBox('linksspisok','showlink',<?php echo $idlink ?>);"  value="<?php echo $section->parametrs->param34 ?>">  
        <div class="linksspisok" id="linksspisok">
                 
        </div> 
        <div class="comments">
            <form style="margin:0px;" action="" method="post" enctype="multipart/form-data"> 
                <div class="comments_list"> 
                    <div class="comments_vse">
                        
                        <?php echo $comments ?>
                    </div>
                    <?php echo $navigator ?>
                     
                </div>
                <div class="comments_ins"> 
                    <div class="comments_ins_title"><?php echo $section->parametrs->param8 ?></div>  
                    <textarea class="comments_ins_text"  rows="3" cols="commentsinstext" name="commentsinstext"><?php echo $commentsinstext ?></textarea> 
                    
                    <?php echo $anti_spam ?>
                    <input class="buttonSend goButton" name="GoToFilecatalog" type="submit" value="<?php echo $section->parametrs->param20 ?>">
                </div>
            </form>
        </div>
        <input class="buttonSend buttonBack" onclick="document.location = '<?php echo seMultiDir()."/".$_page."/" ?>'" type="button" value="<?php echo $section->parametrs->param10 ?>">
    </div>
</div>
