<div class="content cont_ank"  >
    <?php if(!empty($section->title)): ?>
        <<?php echo $section->title_tag ?> class="contentTitle">
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </<?php echo $section->title_tag ?>>
    <?php endif; ?>
    <?php if(!empty($section->image)): ?>
        <img alt="<?php echo $section->image_alt ?>" title="<?php echo $section->image_title ?>" border="0" class="contentImage" src="<?php echo $section->image ?>">
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <div class="contentText"><?php echo $section->text ?></div>
    <?php endif; ?>
    <?php if(!empty($section->text)): ?>
        <br clear="all">
    <?php endif; ?>
    <div id="anketa">
        <?php echo $__data->linkAddRecord($section->id) ?>
        <form style="margin:0px" id="Go" action="" method="post" enctype="multipart/form-data">
            <?php $__data->recordsWrapperStart($section->id) ?>
            <table width="100%" border="0px" cellPadding="0px" cellSpacing="0px" class="tableTable" id="objects">
                <tbody class="tableBody">
                    <tr class="tableRow" vAlign="top" align="left">
                        <td colspan="3" class="ank_tderr sysedit">
                            <font class="ank_ertxt"><?php echo $ank_err_text ?></font> 
                        </td> 
                    </tr> 
                    <?php foreach($__data->limitObjects($section, $section->objectcount) as $record): ?>
  
                        <tr class="ObjRow record-item" <?php echo $__data->editItemRecord($section->id, $record->id) ?> vAlign="top" align="left">
                            <?php if($record->text1=="title" || $record->text1==""): ?> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> colspan="3" class="objTitls">
                                    <span><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*string"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>                  
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="text" class="contentForm inputText" 
                                        size="<?php echo $record->field ?>" name="formobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                    <input type="hidden" name="formcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*email"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="text" class="inputText" size="<?php echo $record->field ?>" 
                                        name="email" value='<?php echo $record->note ?>' title="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="email"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->title ?>">
                                </td> 
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="text" class="inputText" size="<?php echo $record->field ?>" 
                                        name="email" value='<?php echo $record->note ?>' title="<?php echo $record->image_alt ?>">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="string"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="text" class="inputText" size="<?php echo $record->field ?>" 
                                        name="formobj<?php echo $record->id ?>" value="<?php echo $record->note ?>" title="">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="*list"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?><span class="objectRedStar"><font color="red">*</font></span></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <select class="contentForm select" size="<?php echo $record->field ?>" Name="formobj<?php echo $record->id ?>" title="">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="formcheck<?php echo $record->id ?>" id="formcheck<?php echo $record->id ?>" value="1">
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="list"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>    
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <select class="contentForm select" size="<?php echo $record->field ?>" Name="formobj<?php echo $record->id ?>">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>
<option value="<?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>" <?php if(strpos($noteline, '*')!==false) echo 'selected'; ?>><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></option> 
<?php endforeach; ?>
                                    </select>
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="field"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                </td> 
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <textarea class="textArea" name="formobj<?php echo $record->id ?>" rows="<?php echo $record->field ?>" wrap="virtual"><?php echo $record->note ?></textarea> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="chbox"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="">
                                    <input type="hidden" name="ischeckbox<?php echo $record->id ?>" value="1">
                                </td>
                                <td width="1%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <div class="checkbx">
                                        <input class="objcheck" type="checkbox" name="formobj<?php echo $record->id ?>" value="<?php echo $section->parametrs->param43 ?>" <?php echo $record->text2 ?>>
                                        <span class="objchecktext"><?php echo $record->note ?></span>
                                    </div> 
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="radio"): ?> 
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                </td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> width="1%" class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <div class="radioblock">
                                        <?php $noteitem = explode("\n", str_replace("\n\n","\n", trim(str_replace(array("<br>","<br />","<p>","</p>"),array("\n","\n","","\n"),str_replace("\r", "", $record->note))))); ?>
<?php foreach($noteitem as $num=>$noteline): ?>

                                            <div class="radio">
                                                <input class="contentForm objRadio" type="radio" name="formobj<?php echo $record->id ?>" value="<?php echo str_replace('*', '', $num+1) ?> <?php list(,$noteline_) = explode('%%', trim($noteline)); if (empty($noteline_)) $noteline_ =  (strip_tags($noteline)); echo str_replace('*', '', htmlspecialchars($noteline_)) ?>"<?php if(strpos($noteline, '*')!==false) echo 'checked'; ?>>
                                                <span class="objRadiotext"><?php list($noteline_) = explode('%%', $noteline); echo str_replace('*', '', $noteline_) ?></span>
                                            </div> 
                                        
<?php endforeach; ?>
                                    </div>                            
                                </td> 
                            <?php endif; ?>
                            <?php if($record->text1=="file"): ?>
                                <td width="30%" <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objTitl"><?php echo $__data->linkEditRecord($section->id, $record->id,'') ?>
                                    <?php if(!empty($record->image)): ?>
                                        <img class="objectTitleImage" src="<?php echo $record->image_prev ?>" alt="<?php echo $record->image_alt ?>" title="<?php echo $record->image_title ?>">
                                    <?php endif; ?>
                                    <span class="objectTitleText"><?php echo $record->title ?></span>
                                    <input type="hidden" name="formtitle<?php echo $record->id ?>" value="<?php echo $record->image_alt ?>">
                                    <input type="hidden" name="isfile<?php echo $record->id ?>" value="1">
                                </td> 
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> width="1%" class="objHSpace">&nbsp;</td>
                                <td <?php echo $__data->editItemRecord($section->id, $record->id) ?> class="objArea">
                                    <input type="file" class="objFile" name="formobj<?php echo $record->id ?>">
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
            <?php $__data->recordsWrapperEnd() ?> 
            <table width="100%" border="0" cellPadding="0" cellSpacing="0" class="tableTable" id="general">
                <tbody class="tableBody">
                    <?php if(strval($section->parametrs->param21)!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl namettl"><?php echo $section->language->lang012 ?><?php if(strval($section->parametrs->param23)!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputName" name="name" title="&quot;<?php echo $section->language->lang012 ?>&quot;" value="<?php echo $name ?>">
                            </td> 
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div> 
                            </td> 
                        </tr> 
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param24)!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl addressttl"><?php echo $section->language->lang013 ?><?php if(strval($section->parametrs->param26)!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputAddress" name="address" title="&quot;<?php echo $section->language->lang013 ?>&quot;" value="<?php echo $address ?>">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param27)!='No'): ?>
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl phonettl"><?php echo $section->language->lang014 ?><?php if(strval($section->parametrs->param29)!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputPhone" name="phone" title="&quot;<?php echo $section->language->lang014 ?>&quot;" value="<?php echo $phone ?>">
                            </td>
                        </tr> 
                        <tr> 
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param38)!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl emailttl"><?php echo $section->language->lang015 ?><?php if(strval($section->parametrs->param39)!='No'): ?><span class="star" style="color:red">*</span><?php endif; ?></span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea">
                                <input type="text" class="inputText inputEmail" name="email" title="&quot;<?php echo $section->language->lang015 ?>&quot;" value="<?php echo $email ?>">
                            </td> 
                        </tr> 
                        <tr>
                            <td class="ank_spctd" colspan="3" height="4px">
                                <div style="width:0px; height:0px;"></div>
                            </td>
                        </tr> 
                    <?php endif; ?>
                    <?php if(strval($section->parametrs->param14)!='No'): ?> 
                        <tr class="tableRow" id="tableRowOdd" vAlign="top" align="left">
                            <td width="30%" class="objTitl">
                                <span class="basettl pinttl">
                                    <?php echo $section->language->lang011 ?>
                                    <span class="star" style="color:red">*</span>
                                </span>
                            </td> 
                            <td width="1%" class="objHSpace">&nbsp;</td> 
                            <td class="objArea" id="ank_chimg">
                                <?php echo $anti_spam ?>
                            </td> 
                        </tr> 
                    <?php endif; ?>   
                    <?php if(strval($section->parametrs->param55)=='Y' || strval($section->parametrs->param57)=='Y'): ?>
                        <tr>
                            <td colspan="3"><?php if(file_exists($__MDL_ROOT."/php/subpage_license.php")) include $__MDL_ROOT."/php/subpage_license.php"; if(file_exists($__MDL_ROOT."/tpl/subpage_license.tpl")) include $__data->include_tpl($section, "subpage_license"); ?></td>
                        </tr>
                    <?php endif; ?> 
                    <tr>
                        <td colspan="3">&nbsp;</td>
                    </tr> 
                    <tr> 
                        <td colspan="3" class="ank_tdbtn">
                            <input name="GoTo<?php echo $section->id ?>"  value="<?php echo $section->language->lang017 ?>" class="buttonSend" type="submit">
                        </td> 
                    </tr> 
                </tbody> 
            </table> 
        </form> 
         
    </div> 
</div>
