<header:js>      
    [js:jquery/jquery.min.js]
</header:js>
<script type="text/javascript">
    function loadBox(id, name, idlink) {
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
        <div class="short_post"><?php echo $blog_short ?></div>
        <a name="full_content"></a>
        <div class="full_post"><?php echo $blog_full ?></div>
        <div class="post_info">
            <?php if($tagcount!=0): ?>
                <div class="obj tags">
                    <span class="ttl"><?php echo $section->parametrs->param58 ?></span>
                    <?php foreach($section->blog_tags as $blog_tags): ?>
                        <a class="txt" href="<?php echo $multidir ?>/<?php echo $_page ?>/tag/<?php echo $blog_tags->tag1 ?>/"><?php echo $blog_tags->tag2 ?></a>
                    
<?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if($catvivod!=0): ?>
                <div class="obj cats">                        
                    <span class="ttl"><?php echo $section->parametrs->param59 ?></span>
                    <?php foreach($section->catout as $catout): ?>
                        <a class="txt" href="<?php echo $multidir ?>/<?php echo $_page ?>/blog/<?php echo $catout->dt1 ?>/"><?php echo $catout->dt2 ?></a>
                    
<?php endforeach; ?>
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param35=="on"): ?>  
                <div class="obj prosmotri">
                    <span class="ttl"><?php echo $section->parametrs->param36 ?></span>
                    <span class="txt"><?php echo $viewing ?></span>
                </div>
            <?php endif; ?>
            <?php if($section->parametrs->param41=="on"): ?>
                <div class="rating">
                    <form style="margin:0px;" action="" method="post" enctype="multipart/form-data">
                        <div class="rating_stars"> 
                            <input name="adv2" type="radio" class="wow" value="1" title="<?php echo $section->parametrs->param44 ?>"<?php if($rating0==1): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="2" title="<?php echo $section->parametrs->param45 ?>"<?php if($rating0==2): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="3" title="<?php echo $section->parametrs->param46 ?>"<?php if($rating0==3): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="4" title="<?php echo $section->parametrs->param47 ?>"<?php if($rating0==4): ?> checked<?php endif; ?>>
                            <input name="adv2" type="radio" class="wow" value="5" title="<?php echo $section->parametrs->param48 ?>"<?php if($rating0==5): ?> checked<?php endif; ?>>
                        </div>
                        <div class="obj golosa">
                            <span class="ttl"><?php echo $section->parametrs->param42 ?></span>
                            <span class="txt">
                                <span class="first"><?php echo $rating1 ?></span>
                                <span class="last"><?php echo $section->parametrs->param43 ?></span>
                            </span>
                        </div>
                        <input class="buttonSend goRtngButton" name="GoRating" type="submit" value="<?php echo $section->parametrs->param49 ?>"> 
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
    <?php if($commenting=="yes"): ?>
        <div class="comments">
            <?php if($err==true): ?>
                <div class="errorpin">
                    <?php echo $errorpin ?>
                </div>
            <?php endif; ?>
            <form style="margin:0px;" action="" method="post" enctype="multipart/form-data"> 
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
                                    <a class="comments_link" style="text-decoration:none;" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub4/" ?>?id=<?php echo $idlink ?>&idmsg=<?php echo $com->id ?>"><?php echo $section->parametrs->param7 ?></a>
                                <?php endif; ?>
                                
                                    <input class="buttonSend buttonDowload" type="button" onclick="loadBox('linksspisok<?php echo $com->id ?>', 'showlink', <?php echo $com->id ?>);" value="<?php echo $section->parametrs->param50 ?>">
                                
                                  
                                <div class="linksspisok" id="linksspisok<?php echo $com->id ?>">
                                    
                                </div>
                            </div>
                        
<?php endforeach; ?>
                    </div>
                </div>
                <div class="comments_ins"> 
                    <?php if($usrn=="1"): ?>  
                        <div class="obj name">
                            <label class="title" for="name"><?php echo $section->parametrs->param33 ?></label>
                            <div class="field">
                                <input class="inputs" id="name" name="name" value="<?php echo $name ?>" maxlength="255">
                            </div> 
                        </div> 
                    <?php endif; ?>
                    <div class="comments_ins_title"><?php echo $section->parametrs->param8 ?></div>
                    
                    <div class="add_comment">  
                        <textarea class="comments_ins_text" rows="3" cols="commentsinstext" name="commentsinstext"><?php echo $commentsinstext ?></textarea> 
                        <div class="submit_block">
                            <div class="antispam">
                                <img class="pin_img" src="/lib/cardimage.php?session=<?php echo $sid ?>&<?php echo $tim ?>">
                                <div class="titlepin"><?php echo $section->parametrs->param11 ?></div>
                                <input class="inp inppin <?php echo $errstpin ?>" <?php echo $glob_err_stryle ?> name="pin" maxlength="5" value="" autocomplete="off">
                            </div> 
                            <div class="buttons">
                                <input class="buttonSend goButton" name="GoTonewbbs" type="submit" value="<?php echo $section->parametrs->param8 ?>">
                            </div">
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    <?php endif; ?>
</div>
