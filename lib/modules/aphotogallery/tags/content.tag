<if:[param2]!='d'>
<div class="<if:[param2]=='n'>container<else>container-fluid</if>"></if>
<section class="content adap_imggallery part[part.id]" data-seimglist="[part.id]" [contedit]>
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
  <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
  </div>
  <div class="contentBody">
    <div class="links">
      <div id="links-photo[part.id]">
        [*addobj]
        <div class="classNavigator">
            [SE_PARTSELECTOR]
        </div>
        <repeat:records>
            <a href="[record.image]" class="object col-xs-6 col-sm-4 col-md-2 col-lg-2" title="[record.title]" [objedit]>
                <noempty:record.image>
                    <img border="0" class="objectImage img-responsive" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
                </noempty>
            </a>
        </repeat:records>
        <div class="classNavigator">
            [SE_PARTSELECTOR]
        </div>
      </div>
      <se>
        <span class="object col-xs-6 col-sm-4 col-md-2 col-lg-2 sysedit">
            <img border="0" style="cursor: pointer;" class="objectImage img-responsive" 
                data-seaddimg="[part.id]" src="[module_url]add_image.png" border="0" 
                alt="[lang001]" title="[lang001]">
        </span>
      </se> 
    </div>
  </div>
</section>
<if:[param2]!='d'>
</div></if>
<footer:js>
[js:jquery/jquery.min.js]
[module_js:blueimp-gallery.js]
[include_js({p20:'[param20]', p21: '[param21]', p22: '[param22]', id: '[part.id]'})]
</footer:js>
<header:css>
<link rel="stylesheet" href="[this_url_modul]blueimp-gallery.min.css">
</header:css>
