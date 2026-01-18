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
'is_local':'<se>1</se>',
'ajax_url': '?ajax[part.id]',
'min_length': '[param30]'
})]
</footer:js>
<footer:css>
[lnk:rouble/rouble.css]
</footer:css>
<header id="section-header" class="<if:[param5]=='Y'>section-header-fixed</if>"[contedit]>
<div class="<if:[param2]=='n'>container<else>container-fluid</if>" id="top_b_header">
    <div class="header-row row">
        <div class="logotype-area <noempty:part.text>col-lg-4 col-md-4</noempty> <empty:part.text>col-lg-6 col-md-6</empty> col-sm-7 col-xs-6 clearfix">
            <a href="<se>home.html</se><serv>{$seurl}</serv>">
                <div id="LogotypeBlock" data-seedit="logotype" data-se-maxh="80" data-se-maxw="280" title="[part.image_title]">
                    <if:"[%sitelogotype%]" != ''>
                        <img src="[%sitelogotype%]" alt="[part.image_alt]">
                    <else>                    
                        <img src="[module_url]logo.png" alt="[part.image_alt]">
                    </if>
                </div>
            </a>
            <div class="sitetitle-area">
                <if:{$startpage}>
                 <h1 class="sitetitle-title" data-seedit="sitetitle">[%sitetitle%]</h1>
                 <h2 class="sitetitle-subtitle" data-seedit="sitesubtitle">[%sitesubtitle%]</h2>
                <else>
                 <div class="sitetitle-title" data-seedit="sitetitle">[%sitetitle%]</div>
                 <div class="sitetitle-subtitle" data-seedit="sitesubtitle">[%sitesubtitle%]</div>
                </if>
            </div>
        </div>
        <div class="head-contacts <noempty:part.text>col-lg-3</noempty> <empty:part.text>col-lg-3</empty> col-md-3 hidden-sm hidden-xs">
            <div class="row">
                <div class="left-contact">
                    <div class="contact-phone" data-seedit="contacts">
                        <a href="<serv>tel:[%sitephone%]</serv>" class="contact-header-tel">[%sitephone%]</a>
                        <div class="contact-header-mail"><span>E-mail: </span><a href="<serv>mailto:[%sitemail%]</serv>">[%sitemail%]</a></div>
                    </div>
                </div>
            </div>
        </div>
          <div class="header-call_back col-lg-2 col-md-2 hidden-sm hidden-xs">
            <if:[param9]=='Y'>
                <div class="row">
                       <a href="#" data-toggle="modal" data-target="#callBackModal" class="b_call_back-call_button">[lang003]</a>
                </div>
            </if>
          </div>
        <div class="head-btn-block col-lg-3 col-md-3 col-sm-5 col-xs-6 norelative">
            <div class="buttonhead">
                <a class="personbut<if:{$isAuth}==1> se-login-modal<else> auth</if>" data-target="[param8]" href="[param8].html"></a>
                <!-- -->
                <div class="basketbut">
                [subpage name=basket]
                </div>
                <!-- -->
                <div class="searchbut"></div>
            </div>
        </div>
        [subpage name=search]
    </div>
</div>
</header>
<footer:html>
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
<noempty:part.text>
<div class="modal fade header_modal" id="callBackModal" tabindex="-1" role="dialog" aria-labelledby="callBackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <se><empty:[part.text]><b>Не вставлена форма заявки!</b><br>
         Форму можно сгенерировать на сайте <b>https://siteedit24.com</b> в разделе "Формы для сайта"</empty></se>[part.text]
      </div>
    </div>
  </div>
</div>
</noempty>
</if>
</footer:html>
<if:[param5]=='Y'>
<div class="section-header-space"></div>
</if>
