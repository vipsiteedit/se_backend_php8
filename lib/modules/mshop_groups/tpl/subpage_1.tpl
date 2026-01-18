
                <div class="titleGroup">
                    <h2 class="grouptitle"><?php echo $thisgroup_name ?></h2>
                    <span class="grouptitleg"><?php echo $section->language->lang001 ?></span>
                </div>
                <div class="searchform">
                    <form style="margin:0px" method="post" action="">
                        <span class="price"><?php echo $section->language->lang002 ?></span>
                        <label class="from"><?php echo $section->language->lang003 ?></label>
                        <?php if($typeofvitrine=='N'): ?>
                            <input name="search_price_from" class="from" value="<?php echo $search_price_from ?>">
                        <?php else: ?>
                            <input name="SHOP_SEARCH[price][min]" class="from" value="<?php echo $search_price_from ?>">
                        <?php endif; ?>
                        <label class="to"><?php echo $section->language->lang004 ?></label>
                        <?php if($typeofvitrine=='N'): ?>
                            <input size="8" name="search_price_to" class="to" value="<?php echo $search_price_to ?>">
                        <?php else: ?>
                            <input size="8" name="SHOP_SEARCH[price][max]" class="to" value="<?php echo $search_price_to ?>">
                        <?php endif; ?>
                        <span class="valut"><?php echo $thisvalut ?></span>
                        <input value="<?php echo $section->language->lang005 ?>" type="submit" class="buttonSend">
                    </form>
                </div>
                <?php if($thisgroup_image!=''): ?>
                    <img class="groupImage" title="<?php echo $thisgroup_image_alt ?>" alt="<?php echo $thisgroup_image_alt ?>" src="<?php echo $thisgroup_image ?>">
                <?php endif; ?>
                <div class="grouptitleblock">
                    <h4 class="grouptitle"><?php echo $thisgroup_name ?></h4>
                </div>
                <div class="groupcomment"><?php echo $thisgroup_commentary ?></div>
                <div class="groupsublinkblock">
                    <?php if(strval($section->parametrs->param21)=='N'): ?> 
                       <?php foreach($section->subgroups as $group): ?>
                            <div class="subgrouplink">
                                <a class="link" href="<?php echo $group->link ?>"><?php echo $group->name ?></a>
                                <?php if($group->scount!='0'): ?>
                                    <span>(<?php echo $group->scount ?>)</span>
                                <?php endif; ?>
                            </div>
                        
<?php endforeach; ?>
                    <?php else: ?>   
                        <?php if(file_exists($__MDL_ROOT."/php/subpage_3.php")) include $__MDL_ROOT."/php/subpage_3.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_3.tpl")) include $__data->include_tpl($section, "subpage_3"); ?>
                    <?php endif; ?>
                </div>

