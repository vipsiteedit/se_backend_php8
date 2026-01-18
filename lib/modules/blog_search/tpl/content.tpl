<header:css>
    [lnk:bootstrap/css/bootstrap.min.css]
</header:css>
<header:js>
    [js:jquery/jquery.min.js]
    [js:bootstrap/bootstrap.min.js]
</header:js>
<div class="content blog_search" <?php echo $section->style ?>>
    <?php if(!empty($section->title)): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <div class="serch_form">
        <form role="form" action="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>" method="post">
        <?php if($section->parametrs->param2=='long'): ?>    
            <div class="form-group title phase">
                <?php echo $section->language->lang001 ?>
            </div>
        <?php endif; ?>
            <div class="form-group block phase block_s">
                <input type="text" class="phase_inp" name="search_phase" placeholder="<?php echo $section->language->lang002 ?>">            
            </div>
            
        <?php if($section->parametrs->param2=='short'): ?>
            <div class="form-group block_s b_country">
                <select class="form-control" name='search_country[]'>
                    <option value=""><?php echo $section->language->lang003 ?></option>
                    <?php foreach($section->countries as $country): ?>
                        <option value="<?php echo $country->title ?>"><?php echo $country->title ?></option>
                    
<?php endforeach; ?>
                </select>
            </div>
            <div class="form-group block_s b_topic">
                <select class="form-control" name='search_topic[]'>
                    <option value=""><?php echo $section->language->lang004 ?></option>
                    <?php foreach($section->topics as $topic): ?>
                        <option value="<?php echo $topic->url ?>"><?php echo $topic->title ?></option>
                    
<?php endforeach; ?>
                </select>
            </div>
            <div class="form-group block_s b_tag">
                <select class="form-control" name="search_tag[]">
                    <option value=""><?php echo $section->language->lang005 ?></option>
                    <?php foreach($section->tags as $tag): ?>
                        <option value="<?php echo $tag->title ?>"><?php echo $tag->title ?></option>
                    
<?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
            <div class="form-group title m_country">
                <?php echo $section->language->lang006 ?>
            </div>
            <div class="form-group block m_country">
                <?php foreach($section->countries as $country): ?>
                    <div class="checkbox">
                        <label>
                            <span class="ttitle"><?php echo $country->title ?></span> 
                            <input type="checkbox" name='search_country[]' value="<?php echo $country->title ?>"> 
                        </label>
                    </div>
                
<?php endforeach; ?>       
            </div>
            <div class="form-group title m_topic">
                <?php echo $section->language->lang007 ?>
            </div>
            <div class="form-group block m_topic">
                <?php foreach($section->topics as $topic): ?>
                    <div class="checkbox">
                        <label>
                            <span class="ttitle"><?php echo $topic->title ?></span> 
                            <input type="checkbox" name='search_topic[]' value="<?php echo $topic->url ?>"> 
                        </label>
                    </div>
                
<?php endforeach; ?>       
            </div>
            <div class="form-group title m_tag">
                <?php echo $section->language->lang008 ?>
            </div>
            <div class="form-group block m_tag">
                <?php foreach($section->tags as $tag): ?>
                    <div class="checkbox">
                        <label>
                            <span class="ttitle"><?php echo $tag->title ?></span> 
                            <input type="checkbox" name="search_tag[]"  value="<?php echo $tag->title ?>"> 
                        </label>
                    </div>
                
<?php endforeach; ?>        
            </div>
        <?php endif; ?>    
            <div class="form-group but">
                <button type="submit" class="buttonSend btn btn-default"><?php echo $section->language->lang009 ?></button>
            </div>
        </form>
    </div>
</div>
