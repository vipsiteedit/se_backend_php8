<?php $__data->recordsWrapperStart($section->id) ?> <?php echo $__data->linkAddRecord($section->id) ?>
                    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
  
                        <tr class="tableRow  ObjRow" vAlign="top" align="left">
                            <?php if($record->text1=="title"): ?> 
                                <td colspan="2" class="objTitls">
                                    <?php if(!empty($record->image)): ?>                  
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText titletext"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*string"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>                  
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="mformobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                    <input type="hidden" name="mformcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*email"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="email" value='<?php echo $record->note ?>' title="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="email"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="string"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="mformobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*list"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td>
                                <td class="objArea">
                                    <select class="contentForm select" size="<?php echo $record->field ?>" Name="mformobj<?php echo $record->id ?>" title="">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="mformcheck<?php echo $record->id ?>" id="mformcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="list"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>    
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td class="objArea">
                                    <select class="select" class="contentForm" SIZE="<?php echo $record->field ?>" Name="mformobj<?php echo $record->id ?>">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="field"): ?> 
                            
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td class="objArea">
                                    <textarea class="textArea contentForm" name="mformobj<?php echo $record->id ?>" rows="<?php echo $record->field ?>" wrap="virtual"><?php echo $record->note ?></textarea> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="chbox"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                    <input type="hidden" name="ischeckbox<?php echo $record->id ?>" value="1">
                                </td>
                                <td class="objArea">
                                    <div class="checkbx">
                                        <input class="contentForm objcheck" type="checkbox" name="mformobj<?php echo $record->id ?>" value="<?php echo $section->parametrs->param25 ?>" <?php echo $record->text2 ?>>
                                        <span class="objchecktext"><?php echo $record->note ?></span>
                                    </div> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="radio"): ?> 
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                                <td class="objArea">
                                    <div class="commonradio">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<div class="radio"><input class="contentForm objRadio" type="radio" name="mformobj<?php echo $record->id ?>" value="<?php echo str_replace('*', '', $num+1) ?> <?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>"<?php if(strpos($noteline, '*')!==false) echo 'checked'; ?>><span class="objRadiotext"><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></span></div> 
<?php endforeach; ?>
                                    </div>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="file"): ?>
                                <td class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="1">
                                </td> 
                                <td class="objArea">
                                    <input type="file" class="contentForm objFile" name="mformobj<?php echo $record->id ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="mformobj<?php echo $record->id ?>">
                                </td> 
                            <?php endif; ?>
                        </tr> 
                    
<?php endforeach; ?>
<?php $__data->recordsWrapperEnd() ?>
