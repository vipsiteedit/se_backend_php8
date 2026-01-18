<!-- Subpage 1. Блок поиска -->
<header:js>[js:jquery/jquery.min.js]</header:js>
<header:js>[js:ui/jquery.ui.min.js]</header:js>
<header:js>
<script type="text/javascript" src="[module_url]jquery.cookie.js"></script>
<style type="text/css">
#demo-frame > div.demo { padding: 10px !important; };
</style>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.8.22/themes/base/jquery-ui.css" type="text/css" media="all" />
<script type="text/javascript">
function sliderRange(jname, vall, minval, maxval){ 
if($.cookie('min'+jname+vall)==null){
    $.cookie('min'+jname+vall, minval)
}
if($.cookie('max'+jname+vall)==null){
    $.cookie('max'+jname+vall, maxval)
}
 $(function() { 
  $( "#slider-range-"+jname+'_'+vall).slider({
   range: true,
   min: minval,
   max: maxval,
   values: [ $.cookie('min'+jname+vall), $.cookie('max'+jname+vall) ],
   slide: function( event, ui ) {
    if(ui.values[0] == ui.values[1])
        return false;
    
    $( "#amount_from_"+jname+'_'+vall).val(ui.values[ 0 ]);
    $("#amount_to_"+jname+'_'+vall).val(ui.values[ 1 ]); 
    
    $.cookie('min'+jname+vall, ui.values[0]);
    $.cookie('max'+jname+vall, ui.values[1]);
    
   }
  });
  $('.clear').click(function(){
    $.cookie('max'+jname+vall, null);
    $.cookie('min'+jname+vall, null)
  });
    
    $( "#amount_from_"+jname+'_'+vall).change(function(){
        var valRangeL = $("#amount_from_"+jname+'_'+vall).val();
        var valRangeR = $("#amount_to_"+jname+'_'+vall).val();
        if(parseInt(valRangeL) > parseInt(valRangeR)){
            valRangeL = valRangeR;
            $("#amount_from_"+jname+'_'+vall).val(valRangeL);
        }
        $( "#slider-range-"+jname+'_'+vall).slider( "values", 0, valRangeL);
    });
    
    $( "#amount_to_"+jname+'_'+vall).change(function(){
        var valRangeL = $("#amount_from_"+jname+'_'+vall).val();
        var valRangeR = $("#amount_to_"+jname+'_'+vall).val();
        if(valRangeL > maxval){
            valRangeR = maxval;
            $("#amount_to_"+jname+'_'+vall).val(maxval);
        }
        if(parseInt(valRangeL) > parseInt(valRangeR)){
            valRangeL = valRangeR;
            $("#amount_to_"+jname+'_'+vall).val(valRangeR);
        }
        $( "#slider-range-"+jname+'_'+vall).slider( "values", 1, valRangeR);
    });
  
 });                
}   
sliderRange('presence_count','tab1', '0', Math.abs('<?php echo $max_count ?>'));
sliderRange('price','tab1', '0', Math.abs('<?php echo $max_price ?>'));
sliderRange('price_opt','tab1', '0', Math.abs('<?php echo $max_price_opt ?>'));
sliderRange('price_opt_corp','tab1', '0', Math.abs('<?php echo $max_price_opt_corp ?>'));
sliderRange('weight','tab1', '0', Math.abs('<?php echo $max_weight ?>'));
sliderRange('volume','tab1', '0', Math.abs('<?php echo $max_volume ?>'));
sliderRange('presence_count','tab2', '0', Math.abs('<?php echo $max_count ?>'));
sliderRange('price','tab2', '0', Math.abs('<?php echo $max_price ?>'));
sliderRange('price_opt','tab2', '0', Math.abs('<?php echo $max_price_opt ?>'));
sliderRange('price_opt_corp','tab2', '0', Math.abs('<?php echo $max_price_opt_corp ?>'));
sliderRange('weight','tab2', '0', Math.abs('<?php echo $max_weight ?>'));
sliderRange('volume','tab2', '0', Math.abs('<?php echo $max_volume ?>'));
</script>
<script type="text/javascript">
$(document).ready(function(){
  function setDisplayOption(toggleObject, cookieName, toggleImg1, toggleImg2) { 
    if (($.cookie(cookieName) == 0) || ($.cookie(cookieName) == null)) { 
      $(toggleObject).css("display", "none");
      $(toggleImg1).css("display", "inline-block");
      $(toggleImg2).css("display", "none");
    } else { 
      $(toggleObject).css("display", "block");
      $(toggleImg1).css("display", "none");
      $(toggleImg2).css("display", "inline-block");
    } 
  }
  function addToggleWithCookie(toggleLink, toggleObject, cookieName, toggleImg1, toggleImg2){
    var cookieValue;
    setDisplayOption(toggleObject, cookieName, toggleImg1, toggleImg2);
    $(toggleLink).click(function() {
      if ($.cookie(cookieName) == null) {
        cookieValue = 1;
      } else {
        cookieValue = Math.abs($.cookie(cookieName) - 1);
      }
      $.cookie(cookieName, cookieValue);
      $(toggleObject).toggle();
      $(toggleImg1).toggle();
      $(toggleImg2).toggle();
    });
  }
 
    addToggleWithCookie('#showB', '#dopParam', 'cookie_dop');
    
    var arrP = {'0':'name', 
    '1':'price', '2':'manufacturer',
    '3':'article', '4':'code',
    '5':'note', '6':'text',
    '7':'presence_count', '8':'price_opt',
    '9':'price_opt_corp', '10':'measure',
    '11':'volume', '12':'weight',
    '13':'flag_new', '14':'flag_hit',
    '15':'group', '16':'discount'};
      
    $.each(arrP, function(i,val){
        addToggleWithCookie('#show_link_'+val, '#block_tab1_'+val, 'cookie1_'+val, '#img1_tab1_'+val, '#img2_tab1_'+val);
    });
    
    $.each(arrP, function(i,val){
        addToggleWithCookie('#show_link_'+val, '#block_tab2_'+val, 'cookie2_'+val, '#img1_tab2_'+val, '#img2_tab2_'+val);
    });
    
    for(i=0; i<10000; i++){
        if($('#show_link_param_'+i).length){
            addToggleWithCookie('#show_link_param_'+i, '#block_tab2_param_'+i, 'cookie2p_'+i, '#img1_tab2_param_'+i, '#img2_tab2_param_'+i);
        }
    }
    
    for(i=0; i<10000; i++){
        if($('#show_link_param_'+i).length){
            addToggleWithCookie('#show_link_param_'+i, '#block_tab1_param_'+i, 'cookie1p_'+i, '#img1_tab1_param_'+i, '#img2_tab1_param_'+i);
        }
    }
});
</script>
<script type="text/javascript">
function loadSBox(id, name, value) {  
        setTimeout(function(){
            $.post("?ajax_sel<?php echo $razdel ?>",{name: "" + name + "", value: "" + value + ""},
                function(data){
                    if($("#"+name+"_sel").val() != "-1"){
                            $(data).insertAfter("#sel_group"); 
                    }
                }
            ); 
        }, 1);                                                                                            
} 
</script>
<script type="text/javascript">
function selectgroup(val, level){                            
        $('img.lazy').show();                                   
        $.post('?addgroup<?php echo $razdel ?>',{idgroup: ""+val,levels: ""+level},function(data){
            $('img.lazy').hide();                                 
            $('.selectBlockGroup').html(data);
        });
    }   
    
selectgroup(this.value, '<?php echo $level_group ?>');
</script>
</header:js>
<div class="searchBlock">
    <form style="margin:0px" method="post" name="frm" id="frm" action="">
    
        <?php if($flag): ?>
        <div class="classLnk"><a class="LnkEdit" href="<?php echo seMultiDir()."/".$_page."/".$razdel."/sub3/" ?>" rel="nofollow"><?php echo $section->parametrs->param147 ?></a></div>
        <?php endif; ?>
    
    
    <div class="classDopParam">
    
            
            <div id="first" class="firstElem">
            <?php foreach($section->searchparam as $divparam): ?>
                <?php echo $divparam->line ?>
            
<?php endforeach; ?>  
            </div>
            <?php if($show): ?> 
            <div><span class="click_classLink" id="showB" style="cursor: pointer; color: blue;" onClick="" onmouseover="this.style.color='orange'" onmouseout="this.style.color='blue'"><?php echo $section->parametrs->param153 ?></span></div>
            <?php endif; ?>  
            <div class="dopParam" id="dopParam" style="display: none;">
            <?php foreach($section->searchparametrs as $divparametrs): ?>
                <?php echo $divparametrs->line ?>
            
<?php endforeach; ?>    
            </div>
            
    </div>
         
        <div class="btnClass">     
            <input class="buttonSend search" type="submit" value="<?php echo $section->parametrs->param63 ?>" name="shop_search">
            <input class="buttonSend clear" type="submit" value="<?php echo $section->parametrs->param62 ?>" name="clearsearch">
        </div>
        
        
    </form>
</div>                                
