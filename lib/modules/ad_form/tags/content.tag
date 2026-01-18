 <header:css>
[include_css]
</header:css>
<footer:js>
[js:jquery/jquery.min.js]
<if:[param20]=='Y'>
[lnk:fancybox2/jquery.fancybox.css]
[js:fancybox2/jquery.fancybox.pack.js]
<script type="text/javascript">
$(document).ready(function() {
    $("#fancybox[part.id]").fancybox({
        'openEffect'   : '[param18]',
        'closeEffect'  : '[param18]',
        'openSpeed'    : [param19], 
        'closeSpeed'   : [param19]        
        });
 });
</script>
</if>
[js:jquery.maskedinput.min.js]
<script type="text/javascript">
$(function($){
    if (typeof $.fn.mask == 'function')
        $(".input_phone").mask("[param15] (999) 999-99-99");
});
</script>
</footer:js>
<div class="content gs_form_mod" [part.style][contedit]>
 <if:[param20]=='Y'><a id="fancybox[part.id]" class="link_mod" href="#modal[part.id]">[param17]</a>
 <div style="display:none"></if>
  <div id="modal[part.id]" class="modal_block"> 
   <noempty:part.title><[part.title_tag] class="contentTitle" [part.style_title]>
     <span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
   </noempty>
   <noempty:part.image>
     <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
   </noempty>
   <noempty:part.text>
     <div class="contentText"[part.style_text]>[part.text]</div>
   </noempty>
   <div class="contentBody">  
    <form method="POST" class="fform" id="feedback-form[part.id]">
    <div class="f_object" id="f_name">
    <if:[param2]=='Y'>
    <span class="inputTitle">[lang005]</span>
    </if>
    <input class="inputTxt" <if:[param2]=='N'>style="display:none;"</if> type="text" name="nameFF" <if:[param3]=='Y'>required</if>  <if:[param13]=='Y'>placeholder="[lang001]" onfocus="this.placeholder=''" onblur="this.placeholder='[lang001]'"</if> x-autocompletetype="name">
    </div>
    <div class="f_object" id="f_phone">
    <if:[param6]=='Y'>
    <span class="inputTitle">[lang006]</span>
    </if>
    <input class="inputTxt input_phone" <if:[param6]=='N'>style="display:none;"</if> type="text" name="phoneFF" <if:[param7]=='Y'>required</if> pattern="^((8|\+7|\+38)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$" <if:[param13]=='Y'>placeholder="[lang002]"</if> x-autocompletetype="name">
    </div>
    <div class="f_object" id="f_mail">
    <if:[param4]=='Y'>
    <span class="inputTitle">[lang007]</span>
    </if>
    <input class="inputTxt" <if:[param4]=='N'>style="display:none;"</if> type="email" name="contactFF" <if:[param5]=='Y'>required</if> <if:[param13]=='Y'>placeholder="[lang003]" onfocus="this.placeholder=''" onblur="this.placeholder='[lang003]'"</if> x-autocompletetype="email">
    </div>
    <div class="f_object" id="f_mass">
    <if:[param8]=='Y'>
    <span class="inputTitle">[lang008]</span>
    </if> 
    <textarea class="inputAr" <if:[param8]=='N'>style="display:none;"</if> name="messageFF" <if:[param9]=='Y'>required</if> rows="7" <if:[param13]=='Y'>placeholder="[lang011]" onfocus="placeholder='';" onblur="placeholder='[lang011]';"</if>></textarea>
    </div>
    [subpage name=license]
    <div class="blockBtn"><input class="buttonForm buttonSend" type="submit" <if:[param12]=='Y'>onclick="yaCounter[param10].reachGoal('[param11]');"</if> value="[lang009]"></div>
    </form>
    <script>
    document.getElementById('feedback-form[part.id]').addEventListener('submit', function(evt){
      var http = new XMLHttpRequest(), f = this;
      evt.preventDefault();
      http.open("POST", "[thispage.link]", true);
      http.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      http.send("part=[part.id]"+"&nameFF=" + this.nameFF.value + "&contactFF=" + this.contactFF.value + "&phoneFF=" + this.phoneFF.value + "&messageFF=" + this.messageFF.value);
      http.onreadystatechange = function() {
     if (http.readyState == 4 && http.status == 200) {
       <if:[param20]=='Y'>
       $("#modal[part.id]").html('<div class="alert_mes">' + http.responseText +', [lang010]' + '</div>');
       <else>
       alert(http.responseText +', [lang010]');
       </if>    
       f.messageFF.removeAttribute('value'); // очистить поле сообщения (две строки)
       f.messageFF.value='';
     }
      }
      http.onerror = function() {
     alert('Извините, данные не были переданы');
      }
    this.nameFF.value = "";
    this.phoneFF.value = "";
    this.contactFF.value = "";
    this.messageFF.value = "";
    }, false);
    </script>
   </div>
    
   [*addobj]
   <repeat:records>
    <div class="object record-item" [objedit]>[*edobj]
     <noempty:record.title>
      <div class="objectTitle">
       <span class="objectTitleTxt record-title">[record.title]</span> 
      </div> 
     </noempty>
     <noempty:record.note>
      <div class="objectNote record-note">[record.note]</div> 
     </noempty>
    </div> 
   </repeat:records>
  </div>
 <if:[param20]=='Y'></div></if>
</div>
