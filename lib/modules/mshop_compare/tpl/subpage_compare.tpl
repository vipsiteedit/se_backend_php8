<h1 class="titleCompare"><span><?php echo $section->language->lang016 ?></span></h1>
<?php if($allcount!=0): ?>
<div class="blockCompare">
    <table class="tableTable tableCompare" border="0" cellpadding="0" cellspacing="0">
        <thead class="tableHead"> 
            <tr class="tableHeader headCompare">
                <th class="thMainSelect">
                    <?php if(!empty($categories_compare)): ?>
                        <div class="selectCategory">
                            <form style="margin:0px;" method="get" action="" onchange="submit();">
                                <label class="selectLabel"><?php echo $section->language->lang017 ?></label>
                                <select class="" name="category">
                                    <?php foreach($section->categories as $cat): ?>
                                        <option value="<?php echo $cat->id ?>" <?php if(!empty($cat->selected)): ?>selected<?php endif; ?>><?php echo $cat->name ?> (<?php echo $cat->count ?>)</option>
                                    
<?php endforeach; ?>
                                </select>  
                            </form>
                        </div>
                    <?php endif; ?>
                    <div class="showCompare">
                        <span class="showAll selected"><?php echo $section->language->lang020 ?></span>
                        <span class="showDiff"><?php echo $section->language->lang021 ?></span>   
                    </div>
                    <div class="clearCompare">
                        <a class="lnkClear" href="?clear_compare"><?php echo $section->language->lang022 ?></a>
                    </div>
                </th>
                <?php foreach($section->product as $pr): ?>
                    <th class="thProduct">
                        <div class="blockGoods">
                            <a class="lnkRemove" href="?del_compare=<?php echo $pr->id ?>" title="<?php echo $section->language->lang023 ?>"><?php echo $section->language->lang024 ?></a>
                            <div class="blockImage">
                                <a class="lnkProduct" href="<?php echo $pr->link ?>" title="<?php echo $pr->name ?>"><img src="<?php echo $pr->image ?>" border="0"></a>
                            </div>
                            <div class="blockTitle">
                                <a class="lnkProduct" href="<?php echo $pr->link ?>" title="<?php echo $pr->name ?>"><?php echo $pr->name ?></a>
                            </div>
                            <div class="blockButton">
                                <form class="form_addCart" style="margin:0px;" method="post" action="">
                                    <input type="hidden" name="addcart" value="<?php echo $pr->id ?>">
                                    <button class="buttonSend addcart" title="<?php echo $section->language->lang025 ?>"><span><?php echo $section->language->lang026 ?></span></button>
                                    <a class="lnkDetail" href="<?php echo $pr->link ?>" title="<?php echo $section->language->lang028 ?>"><?php echo $section->language->lang027 ?></a>
                                </form>
                            </div>
                        </div>
                    </th>
                
<?php endforeach; ?>
            </tr>
        </thead>
        <tbody class="tableBody">
            
            <?php foreach($section->compare_test as $cm): ?>
                <tr class="tableRow featureCompare <?php echo $cm->diff ?>" data-id="<?php echo $cm->id ?>">
                    <?php if($cm->group!=""): ?>
                        <?php if(!empty($cm->name)): ?>
                            <td class="tdNameGroup" colspan="<?php echo $cm->count ?>"><?php echo $cm->name ?></td>
                        <?php endif; ?>
                    <?php else: ?>
                        <td class="tdNameFeature"><?php echo $cm->name ?></td>
                        <?php $__list = 'features'.$cm->id; foreach($section->$__list as $ft): ?>
                            <td class="tdValueFeature"><?php echo $ft->val ?></td>    
                        
<?php endforeach; ?>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php else: ?>
    <div class="emptyCompare">
        <div class="emptyMessage"><?php echo $section->language->lang018 ?></div>
        <a class="lnkCatalog" href="<?php echo seMultiDir()."/".$section->parametrs->param3."/" ?>"><?php echo $section->language->lang019 ?></a>
    </div>
<?php endif; ?>
