<header:css>
  [include_css]
</header:css>
<footer:js>
[js:jquery/jquery.min.js]
<if:[param7]=='Y'>
[js:ui/jquery.ui.min.js]
</if>
[include_js({
'id':'[part.id]',
'shopcart':'[param6]',
'delitem':'[lang006]',
'curr':'{$pricemoney}',
'param7':'[param7]',
'is_local':'<se>1</se>'
})]
</footer:js>
<footer:css>
[lnk:rouble/rouble.css]
</footer:css>
<header id="section-header" class="<if:[param5]=='Y'>section-header-fixed</if>"[contedit]>
<div class="<if:[param2]=='n'>container<else>container-fluid</if>" id="top_b_header">
    <div class="header-row row">
        <div class="logotype-area <noempty:part.text>col-lg-5 col-md-5 col-sm-6</noempty><empty:part.text>col-lg-6 col-md-6 col-sm-6</empty> col-xs-12 clearfix">
            <a href="home.html">
                <div id="LogotypeBlock" data-seedit="logotype" data-se-maxh="80" data-se-maxw="280" title="[part.image_title]">
                    <if:"[%sitelogotype%]" != ''>
                        <img src="[%sitelogotype%]" alt="[part.image_alt]">
                    <else>                    
                        <img src="[module_url]logo.png" alt="[part.image_alt]">
                    </if>
                </div>
            </a>
            <div class="sitetitle-area">
                 <div class="sitetitle-title" data-seedit="sitetitle">[%sitetitle%]</div>
                 <div class="sitetitle-subtitle" data-seedit="sitesubtitle">[%sitesubtitle%]</div>
            </div>
        </div>
        <noempty:part.text>
          <div class="header-call_back col-lg-3 col-md-3 col-sm-3 col-xs-12">
            <if:[param9]=='Y'>
                <div class="row">
                       <a href="#" data-toggle="modal" data-target="#callBackModal" class="b_call_back-call_button">[lang003]</a>
                </div>
            </if>
          </div>
        </noempty>
        <div class="head-contacts <noempty:part.text>col-lg-4 col-md-4 col-sm-3</noempty><empty:part.text>col-lg-6 col-md-6 col-sm-6</empty> col-xs-12">
            <div class="row">
                <div class="col-xs-12 left-contact">
                    <div class="contact-phone" data-seedit="contacts">
                        <a href="<serv>tel:[%sitephone%]</serv>" class="contact-header-tel">[%sitephone%]</a>
                        <div class="contact-header-mail">[lang031] <a href="<serv>mailto:[%sitemail%]</serv>">[%sitemail%]</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</header>
<if:[param14]=='s'>
<div class="modal fade header_modal" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">[lang029]</h4>
      </div>
      <div class="modal-body">       
        [subpage name=form]
      </div>
    </div>
  </div>
</div>
<else>
<footer:html>
<noempty:part.text>
<div class="modal fade" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <se><empty:[part.text]><b>[lang032]</b><br>
         [lang033]</empty></se>[part.text]
      </div>
    </div>
  </div>
</div>
</noempty>
</footer:html>
</if>
<if:[param5]=='Y'>
<div class="section-header-space"></div>
</if>
