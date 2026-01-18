<div class="blockSelectUserRegion" style="display:none;">
    <a class="buttonSend btnClose" href="#" title="<?php echo $section->language->lang065 ?>">x</a>
    <h3><?php echo $section->language->lang066 ?></h3>
    
    <div class="blockSelection blockSelectCountry">
        <span class="titleSelection"><?php echo $section->language->lang067 ?></span>
        <div class="blockSelect">
            <select class="ajaxSelect" name="country_id">    
                <?php echo $country_list ?>
            </select>
        </div>
    </div>
    
    <div class="blockSelection blockSelectRegion">
        <span class="titleSelection"><?php echo $section->language->lang068 ?></span>
        <span class="ajaxPreloader" style="display:none;">&nbsp;</span>
        <div class="blockSelect">
            <select class="ajaxSelect" name="region_id">    
                <?php echo $region_list ?>
            </select>
        </div>
    </div>
    
    <div class="blockSelection blockSelectCity">
        <span class="titleSelection"><?php echo $section->language->lang069 ?></span>
        <span class="ajaxPreloader" style="display:none;">&nbsp;</span>
        <div class="blockSelect">
            <select name="city_id">    
                <?php echo $city_list ?>
            </select>
        </div>
    </div>
    
    <div class="blockResultSelected">
        <input  type="submit" value="<?php echo $section->language->lang070 ?>" title="<?php echo $section->language->lang071 ?>" class="buttonSend" id="btnConfirmRegion" style="display:none;">
    </div>
</div>
