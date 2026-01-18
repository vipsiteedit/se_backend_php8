<header:css>
[include_css]
</header:css>
<div class="content slider-bigsh part[part.id]" [contedit]>
<noempty:part.title>
<[part.title_tag] class="contentTitle">
  <span class="contentTitleTxt">[part.title]</span>
</[part.title_tag]>
</noempty>
<noempty:part.image>
  <img border="0" class="contentImage" src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
</noempty>
<noempty:part.text>
  <div class="contentText">[part.text]</div>
</noempty>
  <div class="flicker contentBody" data-block-text="false">
[*addobj]
<ul>
    <repeat:records>
    <li class="object" data-background="[record.image]">
    <a href="[record.field]"><div class="flick-inner">
      <div class="flick-content">
      <noempty:record.title><[record.title_tag] class="objectTitle">[record.title]</[record.title_tag]></noempty>
      <noempty:record.note><div class="objectNote">[record.note]</div></noempty>
      <noempty:record.objecttext1><div class="objectPrice">[record.objecttext1]</div></noempty>
      </div>
    </div></a>  
    </li>
    </repeat:records>
</ul> 
  </div>
</div>
<footer:js>
    [js:jquery/jquery.min.js]
    [module_js:modernizr-custom-v2.7.1.min.js]
    [module_js:hammer-v2.0.3.min.js]
    [module_js:flickerplate.min.js]
    <link href="[this_url_modul]flickerplate.css" rel="stylesheet" type="text/css">
<script>
$(document).ready(function()
{
    $('.flicker').flickerplate(
    {
        arrows: [param1],
        arrows_constraint: [param2],
        auto_flick: [param3],
        auto_flick_delay: [param4],
        dot_alignment: '[param6]',
        dot_navigation: [param5],
        flick_animation: '[param7]',
        theme: '[param8]'
    });
});
</script>
</footer:js>
