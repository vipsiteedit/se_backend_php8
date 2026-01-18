<div class="content cont_sitephone"[contentstyle][contedit]>
    <noempty:part.title>
        <[part.title_tag] class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span> 
        </[part.title_tag]> 
    </noempty>
    <noempty:part.image>
        <img alt="[part.image_alt]" border="0" class="contentImage" [part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div> 
    </noempty>
    <object name="callme" width=' <if:"[param2]"=="1">200</if><if:"[param2]"=="2">240</if><if:"[param2]"=="3">300</if><if:"[param2]"=="4">468</if>' height=' <if:"[param2]"=="1">100</if><if:"[param2]"=="2">400</if><if:"[param2]"=="3">250</if><if:"[param2]"=="4">60</if>' align="middle">
        <embed class="flphone" src='<if:"[param1]"!="none"><if:"[param2]"=="1">http://universe.uiscom.ru/media/flash/callme200x100</if><if:"[param2]"=="2">http://universe.uiscom.ru/media/flash/callme240x400</if><if:"[param2]"=="3">http://universe.uiscom.ru/media/flash/callme300x250</if><if:"[param2]"=="4">http://universe.uiscom.ru/media/flash/callme468x60</if></if><if:"[param1]"=="none">[this_url_modul]sitephone_200x100_04</if>.swf?h=[param5]&color=[param1]&person=[param3]&text=[param4]' width=' <if:"[param2]"=="1">200</if><if:"[param2]"=="2">240</if><if:"[param2]"=="3">300</if><if:"[param2]"=="4">468</if>' height=' <if:"[param2]"=="1">100</if><if:"[param2]"=="2">400</if><if:"[param2]"=="3">250</if><if:"[param2]"=="4">60</if>' WMode="transparent" quality="high" bgcolor="#ffffff" name="callme" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
    </object>
    <SE>
        <empty:[param5]>
            <br clear="all">
            <div class="sysedit" style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; ">
                <b> 
                    Не забудьте указать в "параметрах" уникальный ключ.<br>
                    Если у вас нет ключа 
                    <a href="http://www.siteedit.ru/sitephone" target=_blank>получите его заполнив форму на странице </a>
                </b> 
            </div>
            <br>
        </empty>
    </SE>
</div>
