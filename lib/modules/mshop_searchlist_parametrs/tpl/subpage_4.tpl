<header:js>[js:jquery/jquery.min.js]</header:js>
<header:js>[js:ui/jquery.ui.min.js]</header:js>
<header:js>
<style type="text/css">
#demo-frame > div.demo { padding: 10px !important; };
</style>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.22/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript">
function sliderRange(jname, vall, minval, maxval){ 
 $(function() { 
  $( "#slider-range-"+jname+"_"+vall).slider({
   range: true,
   min: minval,
   max: maxval,
   values: [ minval, maxval ],
   slide: function( event, ui ) {
    $( "#amount_from_"+jname+"_"+vall).val(ui.values[ 0 ]);
    $("#amount_to_"+jname+"_"+vall).val(ui.values[ 1 ]); 
   }
  });
 });                
}   
sliderRange('presence_count','tab1', '0', '1000');
sliderRange('price','tab1', '0', '1000');
sliderRange('price_opt','tab1', '0', '1000');
sliderRange('price_opt_corp','tab1', '0', '1000');
sliderRange('weight','tab1', '0', '1000');
sliderRange('volume','tab1', '0', '1000');
sliderRange('presence_count','tab2', '0', '1000');
sliderRange('price','tab2', '0', '1000');
sliderRange('price_opt','tab2', '0', '1000');
sliderRange('price_opt_corp','tab2', '0', '1000');
sliderRange('weight','tab2', '0', '1000');
sliderRange('volume','tab2', '0', '1000');
</script>  
</header:js>  
        <div id="first" class="firstElem">
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem1', 'new_imgb1', 'new_imgn1');">
                <img id="new_imgb1" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn1" style="cursor: pointer; display: none;" src="[module_url]filters0.gif">
                <?php echo $section->language->lang022 ?>
                </span>
            </div>
            <div class="new_elem" id="new_elem1" style="display:none"><input type="text" class="InpClass"></div>
            </div> 
                   
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem2','new_imgb2','new_imgn2');">
                <img id="new_imgb2" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn2" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang023 ?></span>
            </div>
            <div class="new_elem" id="new_elem2" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem3','new_imgb3','new_imgn3');">
                <img id="new_imgb3" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn3" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang026 ?></span>
            </div>
            <div class="new_elem" id="new_elem3" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem123','new_imgb123','new_imgn123');">
                <img id="new_imgb123" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn123" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang053 ?></span>
            </div>
            <div class="new_elem" id="new_elem123" style="display:none">
                <div class="selectBlockGroup">
                    <select class='sel_comon'>
                        <option value='-1'><?php echo $section->parametrs->param156 ?></option>
                        <option value="1"><?php echo $section->language->lang061 ?></option>
                        <option value="2"><?php echo $section->language->lang062 ?></option>
                        <option value="3"><?php echo $section->language->lang063 ?></option>
                        <option value="4"><?php echo $section->language->lang064 ?></option>
                    </select>
                    <select class='sel_comon'>
                        <option value='-1'><?php echo $section->parametrs->param156 ?></option>
                        <option value="1"><?php echo $section->language->lang065 ?></option>
                        <option value="2"><?php echo $section->language->lang066 ?></option>
                    </select>
                    <select class='sel_comon'>
                        <option value='-1'><?php echo $section->parametrs->param156 ?></option>
                        <option value="1"><?php echo $section->language->lang044 ?></option>
                        <option value="2"><?php echo $section->language->lang045 ?></option>
                        <option value="3"><?php echo $section->language->lang046 ?></option>
                        <option value="4"><?php echo $section->language->lang047 ?></option>
                    </select>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem16','new_imgb16','new_imgn16');">
                <img id="new_imgb16" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn16" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang051 ?></span>
            </div>
            <div class="new_elem" id="new_elem16" style="display:none">
                <div class="checkBlock">
                <div><input type="checkbox" name="check1" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang044 ?></span></div>
                <div><input type="checkbox" name="check2" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang045 ?></span></div>
                <div><input type="checkbox" name="check3" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang046 ?></span></div>
                <div><input type="checkbox" name="check4" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang047 ?></span></div>
                <div><input type="checkbox" name="check5" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang054 ?></span></div>
                <div><input type="checkbox" name="check6" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang055 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem17','new_imgb17','new_imgn17');">
                <img id="new_imgb17" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn17" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang052 ?></span>
            </div>
            <div class="new_elem" id="new_elem17" style="display:none">
                <div class="checkBlock">
                <div><input type="checkbox" name="check1" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang056 ?></span></div>
                <div><input type="checkbox" name="check2" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang057 ?></span></div>
                <div><input type="checkbox" name="check3" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang058 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem4','new_imgb4','new_imgn4');">
                <img id="new_imgb4" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn4" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang024 ?></span>
            </div>
            <div class="new_elem" id="new_elem4" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem5','new_imgb5','new_imgn5');">
                <img id="new_imgb5" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn5" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang025 ?></span>
            </div>
            <div class="new_elem" id="new_elem5" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem6','new_imgb6','new_imgn6');">
                <img id="new_imgb6" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn6" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang028 ?></span>
            </div>
            <div class="new_elem" id="new_elem6" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_presence_count_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_presence_count_tab1" size="5" maxlength="10" class="InpClassRange">
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-presence_count_tab1" class="sliderClass" style="width:200px;"></div>
                </div>                     
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem7','new_imgb7','new_imgn7');">
                <img id="new_imgb7" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn7" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang048 ?></span>
            </div>
            <div class="new_elem" id="new_elem7" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-price_tab1" class="sliderClass" style="width:200px;"></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem8','new_imgb8','new_imgn8');">
                <img id="new_imgb8" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn8" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang029 ?></span>
            </div>
            <div class="new_elem" id="new_elem8" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-price_opt_tab1" class="sliderClass" style="width:200px;"></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem9','new_imgb9','new_imgn9');">
                <img id="new_imgb9" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn9" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang030 ?></span>
            </div>
            <div class="new_elem" id="new_elem9" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-price_opt_corp_tab1" class="sliderClass" style="width:200px;"></div> 
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem11','new_imgb11','new_imgn11');">
                <img id="new_imgb11" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn11" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang033 ?></span>
            </div>
            <div class="new_elem" id="new_elem11" style="display:none"> 
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_weight_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_weight_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-weight_tab1" class="sliderClass" style="width:200px;"></div>
                </div>
            </div>
            </div>
            
        </div>
        
        <div><span class="click_classLink" id="showB" style="cursor: pointer; color: blue;" onClick="$('#dopParam').toggle();" onmouseover="this.style.color='orange'" onmouseout="this.style.color='blue'"><?php echo $section->parametrs->param153 ?></span></div>  
        
        <div class="dopParam" id="dopParam">
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem12','new_imgb12','new_imgn12');">
                <img id="new_imgb12" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn12" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang034 ?></span>
            </div>
            <div class="new_elem" id="new_elem12" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_volume_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_volume_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                <div style="width:200px;" class="sliderValue"><table width="100%" style="border:0px solid;">
                    <tr><td align="left"><span class="sliderMinMax"><?php echo $section->language->lang059 ?></span></td><td align="right"><span class="sliderMinMax"><?php echo $section->language->lang060 ?></span></td></tr>
                </table></div>
                <div id="slider-range-volume_tab1" class="sliderClass" style="width:200px;"></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem13','new_imgb13','new_imgn13');">
                <img id="new_imgb13" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn13" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang037 ?></span>
            </div>
            <div class="new_elem" id="new_elem13" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass" checked><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem14','new_imgb14','new_imgn14');">
                <img id="new_imgb14" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn14" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang038 ?></span>
            </div>
            <div class="new_elem" id="new_elem14" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass" checked><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem15','new_imgb15','new_imgn15');">
                <img id="new_imgb15" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn15" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang039 ?></span>
            </div>
            <div class="new_elem" id="new_elem15" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass" checked><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>    
        </div>    
<script type="text/javascript">
    function openBlock(name1, name2, name3){
        $('#'+name1).toggle();
        $('#'+name2).toggle();
        $('#'+name3).toggle();
    }
</script> 
