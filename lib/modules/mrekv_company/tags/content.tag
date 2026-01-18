<a name="[razdel]"></a><div class="content userRekv" [part.style][contedit]>
<noempty:part.title>
  <[part.title_tag] class="contentTitle"[part.style_title]><span class="contentTitleTxt">[part.title]</span></[part.title_tag]>
</noempty>
<noempty:part.image>
  <img border="0" class="contentImage" [part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_alt]">
</noempty>
<noempty:part.text>
  <div class="contentText" [part.style_text]>[part.text]</div>
</noempty>
<div class="warning sysedit">[se."[lang011]"][$warning_message]</div>
<form style="margin:0px;" action="[thispage.link]#[razdel]" method="post" class="alldata">
<if:{$refer}!=''>
<input type="hidden" name="referer" value="{$refer}">
</if>
<div class="title">
  [lang001]
</div>
<div class="obj plant">
  <label>[lang002]</label>
  <div><input type="text" name="plant" value="[$_plant]"></div>
</div>
<div class="obj director">
  <label>[lang003]</label>
  <div><input type="text" name="director" value="[$_director]"></div>
</div>
<div class="obj uradres">
  <label>[lang004]</label>
  <div><input type="text" name="uradres" value="[$_uradres]"></div>
</div>
<div class="obj tel">
  <label>[lang005]</label>
  <div><input type="text" name="tel" value="[$_tel]"></div>
</div>
<div class="obj fax">
  <label>[lang006]</label>
  <div><input type="text" name="fax" value="[$_fax]"></div>
</div>
<div class="bankRekv">
<div class="titleBankRekv">
  [lang007]
</div>
[$SE_BANK_REKV_LIST]
<se>
<div class="obj">
  <label>ИНН</label>
  <div><input type="text" maxlength="40" name="" value=""></div>
</div>
<div class="obj">
  <label>КПП</label>
  <div><input type="text" maxlength="40" name="" value=""></div>
</div>
</se>
</div>
<div class="buttonArea">
  <input type="submit" class="buttonSend saveButton" name="GoToRekv" value="[lang008]" {$dis}>
  <if:{$refer}!=''>
  <input onClick="document.location.href='{$refer}';" type="button" class="buttonSend backButton" value="[lang009]" {$dis}>
  </if>
</div>
</form>
</div>
