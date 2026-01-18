        <?php echo $__data->linkAddRecord($section->id) ?>
            <table width="100%" border="0px" class="tableTable miniobjects" id="miniobjects">
                <tbody class="tableBody">
                    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
  
                        <tr class="tableRow  ObjRow" vAlign="top" align="left">
                            <?php if($record->text1=="title"): ?> 
                                <td colspan="3" class="objTitls">
                                    <span class="objectTitleText titletext"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*string"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>                  
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="mformobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                    <input type="hidden" name="mformcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*email"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td>
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="email" value='<?php echo $record->note ?>' title="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="email"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="string"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="mformobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*list"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td>
                                <td width="1%" class="objHSpace">&nbsp;</td> 
                                <td class="objArea">
                                    <select class="contentForm select" size="<?php echo $record->field ?>" Name="mformobj<?php echo $record->id ?>" title="">
                                        <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="mformcheck<?php echo $record->id ?>" id="mformcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="list"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>    
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <select class="select" class="contentForm" SIZE="<?php echo $record->field ?>" Name="mformobj<?php echo $record->id ?>">
                                        <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="field"): ?> 
                            
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <textarea class="textArea contentForm" name="mformobj<?php echo $record->id ?>" rows="<?php echo $record->field ?>" wrap="virtual"><?php echo $record->note ?></textarea> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="chbox"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="">
                                    <input type="hidden" name="ischeckbox<?php echo $record->id ?>" value="1">
                                </td>
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <div class="checkbx">
                                        <input class="contentForm objcheck" type="checkbox" name="mformobj<?php echo $record->id ?>" value="<?php echo $section->parametrs->param25 ?>" <?php echo $record->text2 ?>>
                                        <span class="objchecktext"><?php echo $record->note ?></span>
                                    </div> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="radio"): ?> 
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<div class="radio"><input class="contentForm objRadio" type="radio" name="mformobj<?php echo $record->id ?>" value="<?php echo str_replace('*', '', $num+1) ?> <?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>"<?php if(strpos($noteline, '*')!==false) echo 'checked'; ?>><span class="objRadiotext"><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></span></div> 
<?php endforeach; ?>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="file"): ?>
                                <td width="30%" class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="mformtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="1">
                                </td> 
                                <td width="1%" class="objHSpace">&nbsp;</td>
                                <td class="objArea">
                                    <input type="file" class="contentForm objFile" name="mformobj<?php echo $record->id ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="mformobj<?php echo $record->id ?>">
                                </td> 
                            <?php endif; ?>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3">
                                <div style="width:0px; height:0px;"></div>
                            </td> 
                        </tr> 
                    
<?php endforeach; ?>
                </tbody> 
            </table> 
