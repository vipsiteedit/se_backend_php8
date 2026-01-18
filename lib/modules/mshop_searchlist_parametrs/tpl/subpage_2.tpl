<div class="content shopSearchList_editPage">
<header:js>[js:jquery/jquery.min.js]</header:js>
<header:js>[js:ui/jquery.ui.min.js]</header:js>
<header:js>
<style type="text/css">
#demo-frame > div.demo { padding: 10px !important; };
</style>
<script type="text/javascript">
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>
<script type="text/javascript">
    $(document).ready( function() {
       $("#allCheckTab1").click( function() {
            if($('#allCheckTab1').attr('checked')){
                $('.mc_checkInp_tab1').attr('checked', true);
            } else {
                $('.mc_checkInp_tab1').attr('checked', false);
            }
       });
    });
</script>
<script type="text/javascript">
    $(document).ready( function() {
       $("#allCheckTab2").click( function() {
            if($('#allCheckTab2').attr('checked')){
                $('.mc_checkInp_tab2').attr('checked', true);
            } else {
                $('.mc_checkInp_tab2').attr('checked', false);
            }
       });
    });
</script>  
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.22/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript">
function sliderRange(jname, vall, minval, maxval){ 
 $(function() { 
  $( "#slider-range-"+jname+'_'+vall).slider({
   range: true,
   min: minval,
   max: maxval,
   values: [ minval, maxval ],
   slide: function( event, ui ) {
    $( "#amount_from_"+jname+'_'+vall).val(ui.values[ 0 ]);
    $("#amount_to_"+jname+'_'+vall).val(ui.values[ 1 ]); 
   }
  });
  $( "#amount_from" ).val( "$" + $( "#slider-range-"+jname+'_'+vall).slider( "values", 0 ) +
   " - $" + $( "#slider-range-"+jname+'_'+vall).slider( "values", 1 ) );
 });                
}   
sliderRange('presence_count','tab1', '1', '1000');
sliderRange('price','tab1', '1', '1000');
sliderRange('price_opt','tab1', '1', '1000');
sliderRange('price_opt_corp','tab1', '1', '1000');
sliderRange('weight','tab1', '1', '1000');
sliderRange('volume','tab1', '1', '1000');
sliderRange('presence_count','tab2', '1', '1000');
sliderRange('price','tab2', '1', '1000');
sliderRange('price_opt','tab2', '1', '1000');
sliderRange('price_opt_corp','tab2', '1', '1000');
sliderRange('weight','tab2', '1', '1000');
sliderRange('volume','tab2', '1', '1000');
</script>  
</header:js>
<div id="tabs">
    <ul>
      <li><a href="#tabs-1" class="tab1Class"><?php echo $section->parametrs->param145 ?></a></li>
      <li><a href="#tabs-2" class="tab2Class"><?php echo $section->parametrs->param146 ?></a></li> 
    </ul>
<div id="tabs-1" class="tab1Block">   
        <div id="first" class="firstElem">
            <div class="btnCheckClass"><input type="checkbox" name="allCheckTab1" id="allCheckTab1" class="CheckEditClass checkAllTab1"><label for="allCheckTab1" style="cursor: pointer;" class="titleCheckAll"><?php echo $section->parametrs->param152 ?></label></div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
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
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem2','new_imgb2','new_imgn2');">
                <img id="new_imgb2" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn2" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang023 ?></span>
            </div>
            <div class="new_elem" id="new_elem2" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem3','new_imgb3','new_imgn3');">
                <img id="new_imgb3" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn3" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang026 ?></span>
            </div>
            <div class="new_elem" id="new_elem3" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab1_new_elem123','tab1_new_imgb123','tab1_new_imgn123');">
                <img id="tab1_new_imgb123" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab1_new_imgn123" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang053 ?></span>
            </div>
            <div class="new_elem" id="tab1_new_elem123" style="display:none">
                <div class="selectBlockGroup">
                    <select class='sel_comon sel_0'>
                        <option value='-1'><?php echo $section->parametrs->param156 ?></option>
                        <option value="1"><?php echo $section->language->lang061 ?></option>
                        <option value="2"><?php echo $section->language->lang062 ?></option>
                        <option value="3"><?php echo $section->language->lang063 ?></option>
                        <option value="4"><?php echo $section->language->lang064 ?></option>
                    </select>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
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
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
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
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem4','new_imgb4','new_imgn4');">
                <img id="new_imgb4" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn4" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang024 ?></span>
            </div>
            <div class="new_elem" id="new_elem4" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem5','new_imgb5','new_imgn5');">
                <img id="new_imgb5" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn5" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang025 ?></span>
            </div>
            <div class="new_elem" id="new_elem5" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem6','new_imgb6','new_imgn6');">
                <img id="new_imgb6" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn6" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang028 ?></span>
            </div>
            <div class="new_elem" id="new_elem6" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_presence_count_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_presence_count_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-presence_count_tab1" class="sliderClass" style="width:200px;"></div>                     
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem7','new_imgb7','new_imgn7');">
                <img id="new_imgb7" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn7" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang048 ?></span>
            </div>
            <div class="new_elem" id="new_elem7" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_tab1" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem8','new_imgb8','new_imgn8');">
                <img id="new_imgb8" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn8" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang029 ?></span>
            </div>
            <div class="new_elem" id="new_elem8" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_opt_tab1" class="sliderClass" style="width:200px;"></div>
            </div> 
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem9','new_imgb9','new_imgn9');">
                <img id="new_imgb9" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn9" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang030 ?></span>
            </div>
            <div class="new_elem" id="new_elem9" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_opt_corp_tab1" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem11','new_imgb11','new_imgn11');">
                <img id="new_imgb11" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn11" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang033 ?></span>
            </div>
            <div class="new_elem" id="new_elem11" style="display:none"> 
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_weight_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_weight_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-weight_tab1" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem12','new_imgb12','new_imgn12');">
                <img id="new_imgb12" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn12" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang034 ?></span>
            </div>
            <div class="new_elem" id="new_elem12" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_volume_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_volume_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-volume_tab1" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem13','new_imgb13','new_imgn13');">
                <img id="new_imgb13" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn13" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang037 ?></span>
            </div>
            <div class="new_elem" id="new_elem13" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem14','new_imgb14','new_imgn14');">
                <img id="new_imgb14" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn14" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang038 ?></span>
            </div>
            <div class="new_elem" id="new_elem14" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab1"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('new_elem15','new_imgb15','new_imgn15');">
                <img id="new_imgb15" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn15" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang039 ?></span>
            </div>
            <div class="new_elem" id="new_elem15" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
        </div>  
</div>    
<div id="tabs-2" class="tab2Block">
    <div class="btnCheckClass"><input type="checkbox" name="allCheckTab2" id="allCheckTab2" class="CheckEditClass checkAllTab1"><label for="allCheckTab2" style="cursor: pointer;" class="titleCheckAll"><?php echo $section->parametrs->param152 ?></label></div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem1', 'tab2_new_imgb1', 'tab2_new_imgn1');">
                <img id="tab2_new_imgb1" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn1" style="cursor: pointer; display: none;" src="[module_url]filters0.gif">
                <?php echo $section->language->lang022 ?>
                </span>
            </div>
            <div class="new_elem" id="tab2_new_elem1" style="display:none"><input type="text" class="InpClass"></div>
            </div> 
                   
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem2','tab2_new_imgb2','tab2_new_imgn2');">
                <img id="tab2_new_imgb2" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn2" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang023 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem2" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem3','tab2_new_imgb3','tab2_new_imgn3');">
                <img id="tab2_new_imgb3" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn3" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang026 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem3" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem123','tab2_new_imgb123','tab2_new_imgn123');">
                <img id="tab2_new_imgb123" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn123" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang053 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem123" style="display:none">
                <div class="selectBlockGroup">
                    <select class='sel_comon sel_0'>
                        <option value='-1'><?php echo $section->parametrs->param156 ?></option>
                        <option value="1"><?php echo $section->language->lang061 ?></option>
                        <option value="2"><?php echo $section->language->lang062 ?></option>
                        <option value="3"><?php echo $section->language->lang063 ?></option>
                        <option value="4"><?php echo $section->language->lang064 ?></option>
                    </select>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem16','tab2_new_imgb16','tab2_new_imgn16');">
                <img id="tab2_new_imgb16" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn16" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang051 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem16" style="display:none">
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
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem17','tab2_new_imgb17','tab2_new_imgn17');">
                <img id="tab2_new_imgb17" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn17" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang052 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem17" style="display:none">
                <div class="checkBlock">
                <div><input type="checkbox" name="check1" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang056 ?></span></div>
                <div><input type="checkbox" name="check2" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang057 ?></span></div>
                <div><input type="checkbox" name="check3" class="CheckInpClass"><span class="titleParam"><?php echo $section->language->lang058 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem4','tab2_new_imgb4','tab2_new_imgn4');">
                <img id="tab2_new_imgb4" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn4" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang024 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem4" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem5','tab2_new_imgb5','tab2_new_imgn5');">
                <img id="tab2_new_imgb5" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn5" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang025 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem5" style="display:none"><input type="text" class="InpClass"></div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem6','tab2_new_imgb6','tab2_new_imgn6');">
                <img id="tab2_new_imgb6" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn6" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang028 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem6" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_presence_count_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_presence_count_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-presence_count_tab2" class="sliderClass" style="width:200px;"></div>                     
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem7','tab2_new_imgb7','tab2_new_imgn7');">
                <img id="tab2_new_imgb7" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn7" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang048 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem7" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_tab2" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem8','tab2_new_imgb8','tab2_new_imgn8');">
                <img id="tab2_new_imgb8" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn8" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang029 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem8" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_opt_tab2" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem9','tab2_new_imgb9','tab2_new_imgn9');">
                <img id="tab2_new_imgb9" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn9" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang030 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem9" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_price_opt_corp_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-price_opt_corp_tab2" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem11','tab2_new_imgb11','tab2_new_imgn11');">
                <img id="tab2_new_imgb11" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn11" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang033 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem11" style="display:none"> 
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_weight_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_weight_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-weight_tab2" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem12','tab2_new_imgb12','tab2_new_imgn12');">
                <img id="tab2_new_imgb12" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn12" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang034 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem12" style="display:none">
                <div class="rangeBlock">
                <span class="new_elem_from"><?php echo $section->parametrs->param67 ?></span><input type="text" id="amount_from_value_tab1" size="5" maxlength="10" class="InpClassRange">
                <span class="new_elem_to"><?php echo $section->parametrs->param68 ?></span><input type="text" id="amount_to_value_tab1" size="5" maxlength="10" class="InpClassRange"></span>
                </div>
                <div id="slider-range-value_tab2" class="sliderClass" style="width:200px;"></div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem13','tab2_new_imgb13','tab2_new_imgn13');">
                <img id="tab2_new_imgb13" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="tab2_new_imgn13" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang037 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem13" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio1" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem14','tab2_new_imgb14','tab2_new_imgn14');">
                <img id="new_imgb14" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn14" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang038 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem14" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio2" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>
            
            <div class="fieldSearch"> 
            <div>
                <span class="btnCheckClass"><input type="checkbox" name="check" class="CheckEditClass mc_checkInp_tab2"></span>
                <span class="new_title_field" style="cursor: pointer" class="new_title_field" onClick="openBlock('tab2_new_elem15','tab2_new_imgb15','tab2_new_imgn15');">
                <img id="new_imgb15" style="cursor: pointer; display: inline-block" src="[module_url]filters1.gif">
                <img id="new_imgn15" style="cursor: pointer; display: none;" src="[module_url]filters0.gif"><?php echo $section->language->lang039 ?></span>
            </div>
            <div class="new_elem" id="tab2_new_elem15" style="display:none">
                <div class="radioBlock">
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param149 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param150 ?></span></div>
                <div><input type="radio" name="radio3" class="radioClass"><span class="title_radio"><?php echo $section->parametrs->param151 ?></span></div>
                </div>
            </div>
            </div>    
    
        
</div>
</div>
<header:js>
<script type="text/javascript">
    function openBlock(name1, name2, name3){
        $('#'+name1).toggle();
        $('#'+name2).toggle();
        $('#'+name3).toggle();
    }
</script> 
<script type="text/javascript">
    function clearCheck(){
        $('.mc_checkInp_tab2').attr('checked',false);
        $('.mc_checkInp_tab1').attr('checked',false);
        $('#allCheckTab1').attr('checked', false);
        $('#allCheckTab2').attr('checked', false);
    }
</script>
</header:js>
<div class="btnClassSub3">
<div>
    <input type="button" class="buttonSend edClearAll" name="clear_all" id="clear_all" value="<?php echo $section->parametrs->param155 ?>" onClick="clearCheck();">
</div>
<div>
    &nbsp;
</div>
    <input type="button" class="buttonSend edSave" name="Save" value="<?php echo $section->parametrs->param148 ?>" onClick="javascript:history.back();">
    <input type="button" class="buttonSend edBack" name="Back" value="<?php echo $section->parametrs->param154 ?>" onClick="javascript:history.back();">
</div>
</div>   
