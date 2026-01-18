<?php if(!empty($options)): ?>
<div class="product-options">
    <?php foreach($section->option_group as $ogroup): ?>
        <div class="option-group-item" data-id="<?php echo $ogroup->id ?>">
            <?php if(!empty($ogroup->id)): ?>
                <h3><?php echo $ogroup->name ?></h3>
                <?php if(!empty($ogroup->description)): ?>
                    <div class=""><?php echo $ogroup->description ?></div>
                <?php endif; ?>
            <?php endif; ?>
        <?php $__list = 'option'.$ogroup->id; foreach($section->$__list as $option): ?>
            <div class="option-item" data-id="<?php echo $option->id ?>" data-type="<?php echo $option->type_price ?>">
                <?php if(!empty($option->image)): ?>
                    <img class="option-item-image" src="<?php echo $option->image ?>">
                <?php endif; ?>
                <h4><?php echo $option->name ?></h4>
                <?php if(!empty($option->description)): ?> 
                    <div class="option-item-description"><?php echo $option->description ?></div> 
                <?php endif; ?>
                <?php if($option->type=='select'): ?>
                    <select name="option[<?php echo $option->id ?>]">
                        <?php $__list = 'option_value'.$option->id; foreach($section->$__list as $ovalue): ?>
                            <option value="<?php echo $ovalue->id ?>" data-price="<?php echo $ovalue->price_value ?>" <?php if(!empty($ovalue->selected)): ?>selected<?php endif; ?>><?php echo $ovalue->name ?></option>    
                        
<?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <div class="option-value-list">
                        <?php $__list = 'option_value'.$option->id; foreach($section->$__list as $ovalue): ?>
                            <div class="option-value-item">
                                <label class="">
                                    <?php if($option->type=='radio'): ?>
                                        <input type="radio" name="option[<?php echo $option->id ?>]" value="<?php echo $ovalue->id ?>" data-price="<?php echo $ovalue->price_value ?>" <?php if(!empty($ovalue->selected)): ?>checked<?php endif; ?>>
                                    <?php else: ?>
                                        <input type="checkbox" name="option[<?php echo $option->id ?>][]" value="<?php echo $ovalue->id ?>" data-price="<?php echo $ovalue->price_value ?>" <?php if(!empty($ovalue->selected)): ?>checked<?php endif; ?>>    
                                    <?php endif; ?> 
                                    <?php if(!empty($ovalue->image)): ?> 
                                        <img class="value-item-image" src="<?php echo $ovalue->image ?>"> 
                                    <?php endif; ?>  
                                    <span class="value-item-name"><?php echo $ovalue->name ?></span> 
                                    <?php if(!empty($ovalue->price)): ?> 
                                        <span class="value-item-price">Цена: <?php echo $ovalue->price ?></span>
                                    <?php endif; ?>  
                                    <?php if(!empty($ovalue->description)): ?> 
                                        <div class="value-item-description"><?php echo $ovalue->description ?></div> 
                                    <?php endif; ?> 
                                    <?php if(!empty($ovalue->is_counted)): ?> 
                                        <input type="number" name="ocount[<?php echo $ovalue->id ?>]" value="1" min="1" step="1">
                                    <?php endif; ?>  
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>  
        </div>
    <?php endforeach; ?>    
</div>
<?php endif; ?>
