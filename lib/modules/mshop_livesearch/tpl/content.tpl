<header:js>
[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
<link href="[module_url]autocomplete.css" id="defaultCSS" rel="stylesheet" type="text/css">
</header:js>
<script type="text/javascript">
$(function() {
    $("#livesearch").autocomplete({
        source: function(request,response) {
        $.ajax({
            url: "<?php echo $thispage ?>",
            crossDomain: true,
            dataType: "json",
            data: {
                ajax_levesearch: request.term,
                maxRows: 12
            },
            success: function(data) {
                response(data);
               /* response($.map(data.geonames, function(item) {
                    return {
                        label: item.name + ", " + item.countryName,
                        value: item.name + " (" + item.countryName + ")" + " [" + item.lat + ", " + item.lng + "]"
                    }
                }))*/
            }
        });
        },
        minLength: 2,
        select: function(event,ui) {
           // $('#quicksrhfrm').submit();
            /*$("<p/>").text(ui.item ? ui.item.value : "Ничего не выбрано!").prependTo("#log");
            $("#log").attr("scrollTop", 0);
            $('#quicksrhfrm').submit();*/
        }
    });
});
</script>
<div class="content contSrchcatLive" <?php echo $section->style ?>>
    <?php if($section->title!=''): ?>
        <h3 class="contentTitle" <?php echo $section->style_title ?>>
            <span class="contentTitleTxt"><?php echo $section->title ?></span>
        </h3>
    <?php endif; ?>
    <?php if($section->image!=''): ?>
        <img border="0" class="contentImage" <?php echo $section->style_image ?> src="<?php echo $section->image ?>" alt="<?php echo $section->image_alt ?>">
    <?php endif; ?>
    <?php if($section->text!=''): ?>
        <div class="contentText" <?php echo $section->style_text ?>><?php echo $section->text ?></div>
    <?php endif; ?>
    <form style="margin:0px;" method="POST" action="" id="quicksrhfrm">
        <div class="searchTable">
            <div class="ui-widget" id="live_widget">
                <span><input type="text" name="SHOP_SEARCH[text][string]" id="livesearch" class="ui_search"  onfocus="if (this.value=='<?php echo $section->parametrs->param17 ?>') this.value='';" onblur="if (this.value=='') this.value='<?php echo $section->parametrs->param17 ?>';" value="<?php echo $section->parametrs->param17 ?>">
                

                
                </span>
                <input name="find_shop" type="hidden">
                <span><input type="submit" class="buttonSend edFind" id="find" value="<?php echo $section->parametrs->param2 ?>"></span>
        
            </div>
            <!--div id="log" class="ui-widget ui-widget-content"></div-->
            <?php if($section->parametrs->param13=="Y"): ?>
            <span class="srchcat_linkslc">
                <a href="<?php echo seMultiDir()."/".$section->parametrs->param1."/" ?>" name="dirPage"><?php echo $section->parametrs->param12 ?></a>
            </span>
            <?php endif; ?>
        </div>
    </form>
</div>
