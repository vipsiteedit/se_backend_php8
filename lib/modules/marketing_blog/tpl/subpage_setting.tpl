<div class="modal fade" id="blogSetting" tabindex="-1" role="dialog" aria-labelledby="blogLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="blogLabel"><?php echo $section->language->lang070 ?></h4>
            </div>
            <div class="modal-body"> 
                <div class="row">
                <div class="panel panel-default">
                <div class="panel-heading"><?php echo $section->language->lang071 ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 text-right">
                            <label class="control-label " for="postRate"><?php echo $section->language->lang072 ?></label> 
                        </div>
                        <div class="col-lg-6 col-sm-6 text-left">
                            <div class="input-group">
                                <input type="text" class="form-control" value="<?php echo $posts->rating ?>" id="blogposts_post_rating_change_<?php echo $postId ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="blogposts_post_rating_change" data-original="<?php echo $posts->rating ?>" data-source="<?php echo $postId ?>"><?php echo $section->language->lang073 ?></button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 text-right">
                            <label class="control-label " for="postRate"><?php echo $section->language->lang074 ?></label>
                        </div>
                        <div class="col-lg-6 col-sm-6 text-left">
                            <div class="input-group ">
                                <input type="text" class="form-control" value="<?php echo $posts->views ?>" id="blogposts_post_views_change_<?php echo $postId ?>">
                                <span class="input-group-btn">
                                    <button class="btn btn-primary" type="button" id="blogposts_post_views_change" data-original="<?php echo $posts->views ?>" data-source="<?php echo $postId ?>"><?php echo $section->language->lang073 ?></button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                </div>
                    <?php if($commentlist_count>0): ?>
                    <div class="row">
                    <div class="panel panel-default">
                    <div class="panel-heading"><?php echo $section->language->lang075 ?></div>
                    <div class="panel-body">
                <div class="row">
                        <?php foreach($section->comments as $com): ?>
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-lg-6 col-sm-6 text-left"><?php echo $com->fio ?></div>
                                        <div class="col-lg-6 col-sm-6 text-right"><?php echo $com->date ?></div>
                                    </div>
                                </div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-3 col-sm-3 text-left"><img src="<?php echo $com->avatar ?>" border="0" style="width:100px;"></div>
                                        <div class="col-lg-9 col-sm-9 text-left"><?php echo $com->message ?></div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div class="row">
                                            <div class="control-group">
                                                <div class="col-lg-6 col-sm-6 text-right">
                                                    <label class="control-label " for="postRate"><?php echo $section->language->lang072 ?></label>
                                                </div>
                                                <div class="col-lg-6 col-sm-6 text-left">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" id="blogposts_post_comment_change_<?php echo $com->id ?>" value="<?php echo $com->com_rating ?>">
                                                        <span class="input-group-btn">
                                                            <button class="btn btn-primary blogposts_post_comment_change" id="comment_change_<?php echo $com->id ?>" data-source="<?php echo $com->id ?>" data-original="<?php echo $com->com_rating ?>" type="button"><?php echo $section->language->lang073 ?></button>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        
<?php endforeach; ?>
                </div>
                    </div>
                    </div>
                    </div>
                    <?php endif; ?>                
            </div>    
        </div>
    </div>
</div>
