<footer:js>
[js:jquery/jquery.min.js]
<?php if(strval($section->parametrs->param1)=='Y'): ?>
[lnk:ionrangeslider/ionrangeslider.min.css]  
[js:ionrangeslider/ionrangeslider.min.js]
<?php endif; ?>
[include_js({
    ajax_url: '?ajax<?php echo $section->id ?>',
    param4:'<?php echo $section->parametrs->param4 ?>',
    partNum: '<?php echo $section->id ?>',
    param2:'<?php echo $section->parametrs->param2 ?>',
    param3:'<?php echo $section->parametrs->param3 ?>',
    param1:'<?php echo $section->parametrs->param1 ?>'
     
})]
</footer:js>
<div class="content shopFilter" 
    data-type="<?php echo $section->type ?>" data-id="<?php echo $section->id ?>" style="display: none;">
<filter > 

</div>
