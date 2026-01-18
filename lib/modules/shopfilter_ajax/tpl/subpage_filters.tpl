    <?php foreach($section->filters as $filter): ?>
        <div class="filterItem" data-id='<?php echo $filter->id ?>' data-type='<?php echo $filter->type ?>'>
            <div class="filterTitle <?php if($filter->expanded==0): ?>closed<?php endif; ?>">
                <span class="nameFilter"><?php echo $filter->name ?><?php if(!empty($filter->measure)): ?>, <span class="measure"><?php echo $filter->measure ?></span><?php endif; ?></span>
                <a href="#" class="clearFilter" style="display:none;">x</a>
            </div>
            <div class="filterValueList" <?php if($filter->expanded==0): ?>style="display:none;"<?php endif; ?>>
                <?php if($filter->type=='range'): ?>
                <div class="filterValueItem filterSlider">
                    <input type="text" class="min" size="5" name="f[<?php echo $filter->id ?>][min]" value="<?php echo $filter->min ?>" disabled style="display:none;">
                    <input type="text" class="max" size="5" name="f[<?php echo $filter->id ?>][max]" value="<?php echo $filter->max ?>" disabled style="display:none;">
                    <input type="text" class="from" size="5" name="f[<?php echo $filter->id ?>][from]" value="<?php echo $filter->from ?>">
                    <input type="text"  class="to" size="5" name="f[<?php echo $filter->id ?>][to]" value="<?php echo $filter->to ?>">
                    <input class="inputRange" type="text" style="display:none;" disabled>
                </div>
                <?php endif; ?>
                <?php if($filter->type=='list'): ?>
                    <?php $__list = 'filter_values'.$filter->id; foreach($section->$__list as $value): ?>
                        <div class="filterValueItem listFeature">
                            <label>
                                <input class="inpCheckbox" type="checkbox" name="f[<?php echo $filter->id ?>][]" value="<?php echo $value->id ?>" <?php if(!empty($value->check)): ?>checked<?php endif; ?>>
                                <span class="filterValue"><?php echo $value->value ?></span>
                            </label>
                        </div>
                    
<?php endforeach; ?> 
                <?php endif; ?>
                <?php if($filter->type=='colorlist'): ?>
                    <?php $__list = 'filter_values'.$filter->id; foreach($section->$__list as $cvalue): ?>
                       <div class="filterValueItem colorFeature">
                        <label class="itemValue" title="<?php echo $cvalue->value ?>" data-id="<?php echo $filter->id ?>">
                        <input class="inpRadio" style="display: none;"  type="checkbox" name="f[<?php echo $filter->id ?>][]"
                             value="<?php echo $cvalue->id ?>" <?php if(!empty($cvalue->check)): ?>checked<?php endif; ?>>
                             <?php if(!empty($cvalue->color)): ?>
                                <span class="featureValue" style="background-color:#<?php echo $cvalue->color ?>;"><span></span></span>
                             <?php endif; ?>
                             <?php if(!empty($cvalue->image)): ?>
                                <img class="featureValue" src="<?php echo $cvalue->image ?>" style="width:16px;height:16px;">
                             <?php endif; ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if($filter->type=='bool'): ?>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name='f[<?php echo $filter->id ?>]' value="1"<?php if($filter->check=='1'): ?> checked<?php endif; ?>>
                            <span class="filterValue"><?php echo $section->language->lang003 ?></span>
                        </label>
                    </div>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name='f[<?php echo $filter->id ?>]' value="0"<?php if($filter->check=='0'): ?> checked<?php endif; ?>>
                            <span class="filterValue"><?php echo $section->language->lang004 ?></span>
                        </label>
                    </div>
                    <div class="filterValueItem">
                        <label>
                            <input class="inpRadio" type="radio" name="f[<?php echo $filter->id ?>]"<?php if($filter->check!='1' && $filter->check!='0'): ?> checked<?php endif; ?>>
                            <span class="filterValue"><?php echo $section->language->lang005 ?></span>
                        </label>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
