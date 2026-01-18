<div class="content shopSearchList_editPage">
<form method="post">

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
function showHide(name, val){ 
    $('#block_'+val+'_'+name).toggle();
    $('#img1_'+val+'_'+name).toggle(); 
    $('#img2_'+val+'_'+name).toggle();       
}
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
sliderRange('presence_count','tab1', Math.abs('<?php echo $min_count ?>'), Math.abs('<?php echo $max_count ?>'));
sliderRange('price','tab1', Math.abs('<?php echo $min_price ?>'), Math.abs('<?php echo $max_price ?>'));
sliderRange('price_opt','tab1', Math.abs('<?php echo $min_price_opt ?>'), Math.abs('<?php echo $max_price_opt ?>'));
sliderRange('price_opt_corp','tab1', Math.abs('<?php echo $min_price_opt_corp ?>'), Math.abs('<?php echo $max_price_opt_corp ?>'));
sliderRange('weight','tab1', Math.abs('<?php echo $min_weight ?>'), Math.abs('<?php echo $max_weight ?>'));
sliderRange('volume','tab1', Math.abs('<?php echo $min_volume ?>'), Math.abs('<?php echo $max_volume ?>'));
sliderRange('presence_count','tab2', Math.abs('<?php echo $min_count ?>'), Math.abs('<?php echo $max_count ?>'));
sliderRange('price','tab2', Math.abs('<?php echo $min_price ?>'), Math.abs('<?php echo $max_price ?>'));
sliderRange('price_opt','tab2', Math.abs('<?php echo $min_price_opt ?>'), Math.abs('<?php echo $max_price_opt ?>'));
sliderRange('price_opt_corp','tab2', Math.abs('<?php echo $min_price_opt_corp ?>'), Math.abs('<?php echo $max_price_opt_corp ?>'));
sliderRange('weight','tab2', Math.abs('<?php echo $min_weight ?>'), Math.abs('<?php echo $max_weight ?>'));
sliderRange('volume','tab2', Math.abs('<?php echo $min_volume ?>'), Math.abs('<?php echo $max_volume ?>'));
</script>
<script type="text/javascript">
function checkSyns(name){
 $('.mc_checkInp_'+name).live("click", function () {
  if (this.checked && $('.mc_checkInp_'+name).attr('checked',true)) { 
   $('.mc_checkInp_'+name).attr('checked',false);
   this.checked=true;
  }
  $('#allCheckTab1').attr('checked', false);
  $('#allCheckTab2').attr('checked', false);
 });
} 
</script>
<script type="text/javascript">
    $(document).ready( function() {
       $("#allCheckTab1").click( function() {
            if($('#allCheckTab1').attr('checked')){
                $('.check_Inp_tab1').attr('checked', true);
                $('.check_Inp_tab2').attr('checked',false);
                $('#allCheckTab2').attr('checked', false);
            this.checked=true;
            } else {
                $('.check_Inp_tab1').attr('checked', false);
            }
       });
    });
</script>
<script type="text/javascript">
    $(document).ready( function() {
       $("#allCheckTab2").click( function() {
            if($('#allCheckTab2').attr('checked')){
                $('.check_Inp_tab2').attr('checked', true);
                $('.check_Inp_tab1').attr('checked',false);
                $('#allCheckTab1').attr('checked', false);
            this.checked=true;
            } else {
                $('.check_Inp_tab2').attr('checked', false);
            }
       });
    });
</script>
<script type="text/javascript">
    function clearCheck(){
        $('.check_Inp_tab2').attr('checked',false);
        $('.check_Inp_tab1').attr('checked',false);
        $('#allCheckTab1').attr('checked', false);
        $('#allCheckTab2').attr('checked', false);
    }
</script>
</header:js>


<div id="tabs">
    <ul>
      <li><a href="#tabs-1" class="tab1Class"><?php echo $section->parametrs->param145 ?></a></li>
      <li><a href="#tabs-2" class="tab2Class"><?php echo $section->parametrs->param146 ?></a></li> 
    </ul>
    <div id="tabs-1" class="tab1Block">
    <div class="btnCheckClass"><input type="checkbox" name="allCheckTab1" id="allCheckTab1" class="CheckEditClass checkAllTab1"><label for="allCheckTab1" style="cursor: pointer;" class="titleCheckAll"><?php echo $section->parametrs->param152 ?></label></div>
        <?php foreach($section->searchparam as $divparam): ?>
            <?php echo $divparam->line ?>
        
<?php endforeach; ?>  
        <?php foreach($section->searchparametrs as $divparametrs): ?>
            <?php echo $divparametrs->param_line ?>
        
<?php endforeach; ?>
    </div>
    <div id="tabs-2" class="tab2Block">
    <div class="btnCheckClass"><input type="checkbox" name="all_check" id="allCheckTab2" class="CheckEditClass checkAllTab2"><label for="allCheckTab2" style="cursor: pointer;"><?php echo $section->parametrs->param152 ?></label></div>
        <?php foreach($section->searchparam_two as $divparam_two): ?>
            <?php echo $divparam_two->line_two ?>
        
<?php endforeach; ?>
        <?php foreach($section->searchparametrs_two as $divparametrs_two): ?>
            <?php echo $divparametrs_two->param_line_two ?>
        
<?php endforeach; ?>
    </div>
</div>


<div class="btnClassSub3">
<div>
    <input type="button" class="buttonSend edClearAll" name="clear_all" id="clear_all" value="<?php echo $section->parametrs->param155 ?>" onClick="clearCheck();">
</div>
<div>
    &nbsp;
</div>
    <input type="submit" class="buttonSend edSave" name="Save" value="<?php echo $section->parametrs->param148 ?>">
    <input type="button" class="buttonSend edBack" name="Back" value="<?php echo $section->parametrs->param154 ?>" onClick="javascript:history.back();">
</div>
</form>
</div>
