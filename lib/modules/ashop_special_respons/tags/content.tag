<footer:js>
[js:jquery/jquery.min.js]
[module_js:responsive_tabs-jquery.responsive_tabs.min.js]
<script >
$('.responsive_tabs-id_[part.id]').responsiveTabs({
    accordionTabElement: '<div class="responsive_tabs-accordion_title"></div>'
});
</script>
<if:[param46]=='Y'>
[js:ui/jquery.ui.min.js]
[include_js({id:[part.id], p32:'[param32]',p46:'[param46]'})]
</if>   
</footer:js>
<header:css>
<if:[param51] == 'c'>
    <style>
        .responsive_tabs-id_[part.id] .b_special_simple-object_image {
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;     
            max-width: -webkit-calc(100% - 40px);
            max-height: -webkit-calc(100% - 40px);
            max-width: calc(100% - 40px);
            max-height: calc(100% - 40px);
        }       
    </style>
</if>
</header:css>
<if:[param1]!='d'>
<div class="<if:[param1]=='n'>container<else>container-fluid</if>"></if>
<section class="special-responsive responsive_tabs responsive_tabs-id_[part.id]" [contedit]>
<noempty:part.title><[part.title_tag] class="content-title contentTitle" [part.style_title]>
  <span class="content-title-txt contentTitleText">[part.title]</span></[part.title_tag]>
</noempty>
<noempty:part.image>
  <img border="0" class="content-image contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]">
</noempty>
<noempty:part.text>
  <div class="content-text contentText">[part.text]</div>
</noempty>
    <ul class="responsive_tabs-tab_list">
        <repeat:records>
        <li class="responsive_tabs-tab_list_item"><a href="#tab-[part.id]_[record.id]" class="responsive_tabs-tab_list_link">[record.title]</a></li>
        </repeat:records>
    </ul>
    <repeat:records>
    <div id="tab-[part.id]_[record.id]" class="responsive_tabs-tab_content">[subpage name=special]</div>
    </repeat:records>
</section><if:[param1]!='d'>
</div></if>
