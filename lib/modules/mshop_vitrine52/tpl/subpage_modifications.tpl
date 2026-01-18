<?php if($modifications): ?>
    <div class="modifications" data-goods="<?php echo $viewgoods ?>"> 
        <div class="overlay" style="display:none;"></div>
        <?php foreach($section->modifications_group as $mgroup): ?>
            <div class="groupFeature" data-id="<?php echo $mgroup->id ?>"> 
                <?php $__list = 'modifications_feature_'.$mgroup->id; foreach($section->$__list as $mfeature): ?>
                    <div class="itemFeature <?php if($mfeature->type=='colorlist'): ?>colorFeature<?php endif; ?>" data-id="<?php echo $mfeature->id ?>" data-type="<?php echo $mfeature->type ?>">
                        <div class="nameFeature">
                            <span class="featureName"><?php echo $mfeature->name ?></span>
                            <?php if(!empty($mfeature->description)): ?>
                                <span class="markDescription">?</span>
                                <div class="contentDescription" style="display:none;">
                                    <?php echo $mfeature->description ?>
                                </div>
                            <?php endif; ?>                                                                             
                        </div>
                        <?php if(strval($section->parametrs->param310)=='radio'): ?>
                            <?php $__list = 'modifications_value_'.$mfeature->line; foreach($section->$__list as $mvalue): ?>
                                <label class="itemValue" title="<?php echo $mvalue->value ?>" data-id="<?php echo $mvalue->id ?>">
                                    <input type="radio" name="feature[<?php if(!empty($show_page)): ?>s_<?php endif; ?><?php echo $viewgoods ?>_<?php echo $mgroup->id ?>_<?php echo $mfeature->id ?>]" value="<?php echo $mvalue->id ?>" <?php echo $mvalue->checked ?>>
                                    <?php if($mfeature->type=='colorlist' && $mvalue->color!=''): ?>
                                        <?php if($mvalue->image != ''): ?>
                                            <img class="featureValue" src="<?php echo $mvalue->image ?>">
                                        <?php else: ?>
                                            <span class="featureValue" style="background-color:#<?php echo $mvalue->color ?>;">
                                                <span></span>
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="featureValue"><?php echo $mvalue->value ?></span>    
                                    <?php endif; ?>
                                </label>
                            
<?php endforeach; ?>
                        <?php else: ?>
                            <select name="feature[<?php echo $viewgoods ?>_<?php echo $mgroup->id ?>_<?php echo $mfeature->id ?>]">
                                <?php $__list = 'modifications_value_'.$mfeature->line; foreach($section->$__list as $mvalue): ?>
                                    <option title="<?php echo $mvalue->value ?>" data-id="<?php echo $mvalue->id ?>" value="<?php echo $mvalue->id ?>" <?php echo $mvalue->checked ?>><?php echo $mvalue->value ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>   
<?php endif; ?>
