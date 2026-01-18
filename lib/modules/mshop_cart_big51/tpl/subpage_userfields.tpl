<?php if(!empty($user_fields)): ?>
    <div class="content userfields">  
        <?php foreach($section->userfields as $field): ?>
            <?php if($field->is_group): ?>
                <div class="field-group">
                    <h4><?php echo $field->name ?></h4>
                    <?php if(!empty($field->description)): ?>
                        <div class="description"><?php echo $field->description ?></div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
            <div class="user-field">
                <label><?php echo $field->name ?><?php if(!empty($field->required)): ?><span class="required">*</span><?php endif; ?></label>   
                <?php if($field->type=='string' || $field->type=='number' || $field->type=='date'): ?>
                    <input type="<?php echo $field->type ?>" name="field_<?php echo $field->code ?>" placeholder="<?php echo $field->placeholder ?>" title="<?php echo $field->placeholder ?>" <?php if(!empty($field->required)): ?>required<?php endif; ?> value="<?php if(!empty($field->val)): ?><?php echo $field->val ?><?php endif; ?>">
                <?php endif; ?>
                <?php if($field->type=='text'): ?>
                    <textarea name="field_<?php echo $field->code ?>" placeholder="<?php echo $field->placeholder ?>" title="<?php echo $field->placeholder ?>" <?php if(!empty($field->required)): ?>required<?php endif; ?>><?php if(!empty($field->val)): ?><?php echo $field->val ?><?php endif; ?></textarea>
                <?php endif; ?>
                <?php if($field->type=='select' && $field->values != ''): ?>
                    <select name="field_<?php echo $field->code ?>" placeholder="<?php echo $field->placeholder ?>" title="<?php echo $field->placeholder ?>" <?php if(!empty($field->required)): ?>required<?php endif; ?>>
                        <?php $__list = 'values'.$field->id; foreach($section->$__list as $value): ?>
                            <option value="<?php echo $value->name ?>" <?php if(!empty($value->check)): ?>selected<?php endif; ?>><?php echo $value->name ?></option>
                        
<?php endforeach; ?>    
                    </select>
                <?php endif; ?>
                <?php if($field->type=='checkbox'): ?>
                    <?php if($field->values != ''): ?>
                        <?php $__list = 'values'.$field->id; foreach($section->$__list as $value): ?>
                            <label>
                                <input type="checkbox" name="field_<?php echo $field->code ?>[]" title="<?php echo $field->placeholder ?>" value="<?php echo $value->name ?>" <?php if(!empty($value->check)): ?>checked<?php endif; ?>>
                                <span><?php echo $value->name ?></span>
                            </label>
                        <?php endforeach; ?>   
                    <?php else: ?>
                        <input type="checkbox" name="field_<?php echo $field->code ?>" title="<?php echo $field->placeholder ?>" <?php if(!empty($field->required)): ?>required<?php endif; ?> <?php if(!empty($field->val)): ?>checked<?php endif; ?>>    
                    <?php endif; ?>
                <?php endif; ?>
                <?php if($field->type=='radio'): ?>
                    <?php if($field->values): ?>
                        <?php $__list = 'values'.$field->id; foreach($section->$__list as $value): ?>
                            <label>
                                <input type="radio" name="field_<?php echo $field->code ?>" title="<?php echo $field->placeholder ?>" value="<?php echo $value->name ?>" <?php if(!empty($value->check)): ?>checked<?php endif; ?>>
                                <span><?php echo $value->name ?></span>
                            </label>
                        <?php endforeach; ?>   
                    <?php else: ?>
                        <input type="radio" name="field_<?php echo $field->code ?>" title="<?php echo $field->placeholder ?>" value="<?php echo $value->name ?>" <?php if(!empty($field->required)): ?>required<?php endif; ?>>  
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(!empty($field->description)): ?>
                    <div class="description"><?php echo $field->description ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
