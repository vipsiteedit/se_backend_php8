<div class="content" id="cont_ank" <?php echo $section->style ?> >
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img alt="<?php echo $section->image_alt ?>" border="0" class="contentImage"<?php echo $section->style_image ?> src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText"<?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <br clear="all">
    <?php endif; ?>
    <div id="anketa">
        <?php echo $__data->linkAddRecord($section->id) ?>
        <form style="margin:0px" id="Go" action="" method="post" enctype="multipart/form-data">
            <table width="100%" border="0px" cellPadding="0px" cellSpacing="0px" class="tableTable" id="objects">
                <tbody class="tableBody">
                    <tr class="tableRow" vAlign="top" align="left">
                        <td colspan="3" class="ank_tderr sysedit">
                            <font class="ank_ertxt"><?php echo $ank_err_text ?></font> 
                        </td> 
                    </tr> 
                    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
  
                        <tr class="ObjRow" vAlign="top" align="left">
                            <?php if($record->text1=="title"): ?> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> colspan="3" class="objTitls">
                                    <span><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*string"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>                  
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="formobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                    <input type="hidden" name="formcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*email"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="email" value='<?php echo $record->note ?>' title="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="email"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="string"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input class="contentForm inputText" size="<?php echo $record->field ?>" name="formobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*list"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <select class="contentForm select" size="<?php echo $record->field ?>" Name="formobj<?php echo $record->id ?>" title="">
                                        <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ = trim(strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="formcheck<?php echo $record->id ?>" id="formcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="list"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>    
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <select class="select" class="contentForm" SIZE="<?php echo $record->field ?>" Name="formobj<?php echo $record->id ?>">
                                        <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ = trim(strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="field"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <textarea class="textArea contentForm" name="formobj<?php echo $record->id ?>" rows="<?php echo $record->field ?>" wrap="virtual"><?php echo $record->note ?></textarea> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="chbox"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                    <input type="hidden" name="ischeckbox<?php echo $record->id ?>" value="1">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <div class="checkbx">
                                        <input class="contentForm objcheck" type="checkbox" name="formobj<?php echo $record->id ?>" value="<?php echo $section->parametrs->param43 ?>" <?php echo $record->text2 ?>>
                                        <span class="objchecktext"><?php echo $record->note ?></span>
                                    </div> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="radio"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> width="1%" class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <?php $noteitem = explode("\r\n", trim(str_replace(array('<br>','<br />','<p>','</p>'),array("\r\n","\r\n",'',"\r\n"),$record->note))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<div class="radio"><input class="contentForm objRadio" type="radio" name="formobj<?php echo $record->id ?>" value="<?php echo str_replace('*', '', $num+1) ?> <?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ = trim(strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>"<?php if(strpos($noteline, '*')!==false) echo 'checked'; ?>><span class="objRadiotext"><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></span></div> 
<?php endforeach; ?>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="file"): ?>
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_alt ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="1">
                                </td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> width="1%" class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="file" class="contentForm objFile" name="formobj<?php echo $record->id ?>">
                                </td> 
                            <?php endif; ?>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td> 
                        </tr> 
                    
<?php endforeach; ?>
                </tbody> 
            </table> 
            <table width="100%" border="0" cellPadding="0" cellSpacing="0" class="tableTable" id="general">
                <tbody class="tableBody">
                    <?php if($section->parametrs->param21!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl namettl"><?php echo $section->parametrs->param22 ?><?php if($section->parametrs->param23!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input class="inputText inputName" name="name" title="&quot;<?php echo $section->parametrs->param22 ?>&quot;" value="<?php echo $name ?>">
                            </td> 
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div> 
                            </td> 
                        </tr> 
                    <?php endif; ?>
                    <?php if($section->parametrs->param24!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl addressttl"><?php echo $section->parametrs->param25 ?><?php if($section->parametrs->param26!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input class="inputText inputAddress" name="address" title="&quot;<?php echo $section->parametrs->param25 ?>&quot;" value="<?php echo $address ?>">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    <?php endif; ?>
                    <?php if($section->parametrs->param27!='No'): ?>
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl phonettl"><?php echo $section->parametrs->param28 ?><?php if($section->parametrs->param29!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input class="inputText inputPhone" name="phone" title="&quot;<?php echo $section->parametrs->param28 ?>&quot;" value="<?php echo $phone ?>">
                            </td>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if($section->parametrs->param38!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl emailttl"><?php echo $section->parametrs->param10 ?><?php if($section->parametrs->param39!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input class="inputText inputEmail" name="email" title="&quot;<?php echo $section->parametrs->param10 ?>&quot;" value="<?php echo $email ?>">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    <?php endif; ?>
                    <?php if($section->parametrs->param14!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl pinttl"><?php echo $section->parametrs->param13 ?><span class="star" style="color:red">*</font></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea" id="ank_chimg">
                                <?php echo $captcha ?><br>
                                <input maxlength="5" size="5" name="pin" type="text" class="inputPin" title="&quot;<?php echo $section->parametrs->param13 ?>&quot;">
                            </td> 
                        </tr> 
                    <?php endif; ?>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr> 
                    <tr> 
                        <td colspan="3" class="ank_tdbtn">
                            <input name="GoTo"  value="<?php echo $section->parametrs->param11 ?>" class="buttonSend" type="submit">
                        </td> 
                    </tr> 
                </tbody> 
            </table> 
        </form> 
         
    </div> 
</div>
