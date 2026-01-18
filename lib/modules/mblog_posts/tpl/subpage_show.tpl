<header:js>      
    [js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript">
    function loadBox(id, name, idlink) {
        $('.comments_ins').hide();
        $('.addcommmenttt').remove();
        $('#'+id).load("<?php echo $multidir ?>/<?php echo $_page ?>/?" + name + "&idlink=" + idlink+"&sid="+ "<?php echo $sid ?>"+"&tim="+ "<?php echo $tim ?>", {});
    }
</script>
<script type="text/javascript" src="[module_url]jquery.rating.js"></script>
<link href='[module_url]jquery.rating.css' type="text/css" rel="stylesheet">
<script type="text/javascript">
    $(function(){ // wait for document to load
        $('input.wow').rating();
    });
</script>
<div class="blogposts content blogposts_post">
    <div class="one_post">
        <div class="title">
            <div class="date_post">             
                <span class="date_post_day"><?php echo $date_add_day ?></span>
                <span class="date_post_nedel"><?php echo $date_add_nedel ?></span>
                <span class="date_post_god"><?php echo $date_add_god ?></span>
            </div>
            <h3 class="title_post">
                <span name="title_content" href="#comments" class="scroll"><?php echo $blog_title ?></span>
            </h3>
        </div>
        <?php if($showshort!=0): ?><div class="short_post"><?php echo $blog_short ?></div><?php endif; ?>
        <a name="full_content"></a>
        <div class="full_post"><?php echo $blog_full ?></div>
        <div class="sociallike">
        <h5><?php echo $section->language->lang078 ?></h5>
        <script type="text/javascript">(function() {
          if (window.pluso)if (typeof window.pluso.start == "function") return;
          var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
          s.type = 'text/javascript'; s.charset='UTF-8'; s.async = true;
          s.src = ('https:' == window.location.protocol ? 'https' : 'http')  + '://share.pluso.ru/pluso-like.js';
          var h=d[g]('head')[0] || d[g]('body')[0];
          h.appendChild(s);
          })();</script>
        <div class="pluso" data-options="medium,square,line,horizontal,counter,sepcounter=1,theme=14" data-services="vkontakte,facebook,odnoklassniki,twitter,google" data-background="none;"></div>
        </div>
        <div class="post_info">
            <?php if($tagcount!=0): ?>
                <div class="obj tags">
                    <span class="ttl"><?php echo $section->language->lang054 ?></span>
                    <noindex>
                        <?php foreach($section->blog_tags as $blog_tags): ?>
                            <a class="txt" href="<?php echo $multidir ?>/<?php echo $_page ?>/tag/<?php echo $blog_tags->tag1 ?>/" rel="nofollow"><?php echo $blog_tags->tag2 ?></a>
                        
<?php endforeach; ?>
                    </noindex>
                </div>
            <?php endif; ?>
            <?php if($catvivod!=0): ?>
                <div class="obj cats">                        
                    <span class="ttl"><?php echo $section->language->lang053 ?></span>
                    <?php foreach($section->catout as $catout): ?>
                        <a class="txt" href="<?php echo $multidir ?>/<?php echo $_page ?>/blog/<?php echo $catout->dt1 ?>/"><?php echo $catout->dt2 ?></a>
                    
<?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param35=="on"): ?>  
                <div class="obj prosmotri">
                    <span class="ttl"><?php echo $section->language->lang051 ?></span>
                    <span class="txt"><?php echo $viewing ?></span>
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param41=="on"): ?>
                <div class="rating">
                    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
                        <div class="rating_stars"> 
                            <input name="adv2" type="radio" class="wow" value="1" title="<?php echo $section->language->lang065 ?>"<?php if($rating0==1): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="2" title="<?php echo $section->language->lang066 ?>"<?php if($rating0==2): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="3" title="<?php echo $section->language->lang067 ?>"<?php if($rating0==3): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="4" title="<?php echo $section->language->lang068 ?>"<?php if($rating0==4): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="5" title="<?php echo $section->language->lang069 ?>"<?php if($rating0==5): ?> checked<?php endif; ?>>
                        </div>
                        <div class="obj golosa">
                            <span class="ttl"><?php echo $section->language->lang056 ?></span>
                            <span class="txt">
                                <span class="first"><?php echo $rating1 ?></span>
                                <span class="last"><?php echo $section->language->lang064 ?></span>
                            </span>
                        </div>
                        <input class="buttonSend goRtngButton" name="GoRating" type="submit" value="<?php echo $section->language->lang070 ?>"> 
                    </form>
                </div>
            <?php endif; ?> 
        </div>
    </div>
    <div class="blognav">
        <?php if($prenews!=''): ?>
            <a href="<?php echo $multidir ?>/<?php echo $_page ?>/post/<?php echo $prenews ?>/" class="pre">пред.</a>
        <?php endif; ?>
        <?php if($nxtnews!=''): ?>
            <a href="<?php echo $multidir ?>/<?php echo $_page ?>/post/<?php echo $nxtnews ?>/" class="nxt">след.</a>
        <?php endif; ?>
    </div>
    <div class="blogbacks">
        <a href="<?php echo seMultiDir()."/".$_page."/" ?>" class="backButton"><?php echo $section->language->lang080 ?></a>    
    </div>
    <?php if($commenting=="yes"): ?>
        <div class="comments">
            <?php if($err==true): ?>
                <div class="errorpin">
                    <?php echo $errorpin ?>
                </div>
            <?php endif; ?>
            <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
                <input type="hidden" value="<?php echo $usrcode ?>" name="usrcode"> 
                <div class="comments_list"> 
                    <div id="comments" class="comments_vse">
                        <?php foreach($section->comments as $com): ?>
                            <div class="commentsall comment_un<?php echo $com->level ?>">
                                <div class="comm_ttl">
                                    <div class="comments_athor"><?php echo $com->author ?></div>
                                    <div class="comments_date"><?php echo $com->date_add_rus ?></div>
                                </div>
                                <div class="comments_text"><?php echo $com->message ?></div>
                                <?php if($com->masterlink!=0): ?>
                                    <a class="comments_link" style="text-decoration:none;" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/subaction/" ?>id/<?php echo $idlink ?>/idmsg/<?php echo $com->id ?>/"><?php echo $section->language->lang030 ?></a>
                                <?php endif; ?>
                                
                                    <a class="buttonDowload" href="javascript:loadBox('linksspisok<?php echo $com->id ?>', 'showlink', <?php echo $com->id ?>);"><?php echo $section->language->lang071 ?></a>
                                
                                  
                                <div class="linksspisok" id="linksspisok<?php echo $com->id ?>">
                                    
                                </div>
                            </div>
                        
<?php endforeach; ?>
                    </div>
                </div>
                <div class="comments_ins"> 
                    <?php if($usrn=="1"): ?>  
                        <div class="obj name">
                            <label class="title" for="name"><?php echo $section->language->lang050 ?></label>
                            <div class="field">
                                <input class="inputs" id="name" name="name" value="<?php echo $name ?>" maxlength="255" placeholder="<?php echo $section->language->lang050 ?>">
                            </div> 
                        </div> 
                    <?php endif; ?>
                    <div class="comments_ins_title"><?php echo $section->language->lang079 ?></div>
                    
                    <div class="add_comment">  
                        <textarea class="comments_ins_text" rows="3" cols="commentsinstext" name="commentsinstext" placeholder="<?php echo $section->language->lang081 ?>"><?php echo $commentsinstext ?></textarea> 
                        <div class="submit_block">
                            <!--div class="antispam">
                                <img class="pin_img" src="/lib/cardimage.php?session=<?php echo $sid ?>&<?php echo $tim ?>">
                                <div class="titlepin"><?php echo $section->language->lang034 ?></div>
                                <input class="inp inppin <?php echo $errstpin ?>" <?php echo $glob_err_stryle ?> name="pin" maxlength="5" value="" autocomplete="off">
                            </div--> 
                            <div class="buttons">
                                <input class="buttonSend goButton" name="GoTonewbbs" type="submit" value="<?php echo $section->language->lang032 ?>">
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
