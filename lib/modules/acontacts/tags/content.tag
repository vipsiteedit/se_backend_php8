<if:[param15]==Y>
<footer:js>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    [include_js({'id':'[part.id]','city':'[param10]','addr':'[param11]', 'company':'[param6]'})]
</footer:js>
</if>
<div class="content contacts" [part.style][contedit]><div class="vcard">
<noempty:part.title>
<[part.title_tag] class="contentTitle" [part.style_title]>
  <span class="contentTitleTxt">[part.title]</span>
</[part.title_tag]>
</noempty>
<noempty:part.image>
  <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
</noempty>
<noempty:part.text>
  <div class="contentText"[part.style_text]>[part.text]</div>
</noempty>
<if:[param15]=="Y">
  <div id="YMapsID[part.id]" style="width:100%; height:400px" class="maps"></div>
</if>
<div class="addr-block">
  <div class="name">
    <span class="orgtitle">[lang007]</span><span class="fn org">[param6]</span>
  </div>
  <div class="phone">
    <span class="orgtitle">[lang008]</span><span class="tel">[param7]</span>
  </div>
  <div class="adr">
    <span class="orgtitle">[lang009]</span>
     <span class="postal-code">[param8]</span>[param13]<span class="region">[param9]</span>[param13] 
     <span class="locality">[param10]</span>[param13]<span class="street-address">[param11]</span>
     <br>
     <span class="url">[lang010] http://{$host}<se>mysite.com</se></span>
  </div>
  </div>
</div></div>
