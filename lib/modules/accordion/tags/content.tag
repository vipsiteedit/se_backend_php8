<footer:js>
[js:jquery/jquery.min.js]
[js:ui/jquery.ui.min.js]
[include_js({id: '[part.id]', p9: '[param9]', p10: '[param10]', p11: '[param11]'})]
</footer:js>
<div class="content accordion"[contentstyle][contedit] id="id[part.id]">
    <noempty:part.title>
        <[part.title_tag] class="contentTitle"[part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </[part.title_tag]>
    </noempty>
    <noempty:part.image>
        <img border="0" class="contentImage"[part.style_image] src="[part.image]" alt="[part.image_alt]" title="[part.image_title]">
    </noempty>
    <noempty:part.text>
        <div class="contentText"[part.style_text]>[part.text]</div>
    </noempty>
    <wrapper>[*addobj]
    <repeat:records>
        [*edobj]
        <div class="object"[objedit]>
            <[record.title_tag] class="objectTitle">
                <if:[param14]=='N'>
                    <noindex>
                </if>
                    <a class="objectTitleTxt" href="#" <if:[param14]=='N'> rel="nofollow" </if> >[record.title]</a>
                <if:[param14]=='N'>
                    </noindex>
                </if>
            </[record.title_tag]>
            <div class="contentBlock">
            <noempty:record.image>
                <img border="0" class="objectImage" src="[record.image_prev]" border="0" alt="[record.image_alt]" title="[record.image_title]">
            </noempty>
            <noempty:record.note>
                <div class="objectNote">[record.note]</div>
            </noempty>
            <noempty:record.text>
                <if:[param13]=='N'>
                    <noindex>
                </if>
                    <a class="linkNext" href="[record.link_detail]#show[part.id]_[record.id]" <if:[param13]=='N'> rel="nofollow" </if>>[param1]</a>
                <if:[param13]=='N'>
                    </noindex>
                </if>
            </noempty>
            </div>
        </div> 
    </repeat:records>
    </wrapper>   

{SHOW}
    <SERV>
        <if:[param7]=='Y'>
            <script type="text/javascript" src="http://yandex.st/share/share.js" charset="utf-8"></script>
            <script type="text/javascript">
                new Ya.share({
                    'element': 'ya_share1',
                    'elementStyle': {
                        'type': 'button',
                        'linkIcon': true,   //
                        'border': false,
                        'quickServices': ['facebook', 'twitter', 'vkontakte', 'moimir', 'yaru', 'odnoklassniki', 'lj']
                    },
                    'popupStyle': {
                        'copyPasteField': true
                    }
                });
            </script>
        </if>
    </SERV>
    <div class="content accordion_sub" id="view">
        <if:[param5]=='Y'>
            <SERV>
                <script type="text/javascript">
                    function myPrint() {
                        var text = document.getElementById('forPrint').innerHTML;
                        var printwin = document.open('', 'printwin', '');
                        printwin.document.writeln('<html>');
                        printwin.document.writeln('<body onload="window.print();close();">');
                        printwin.document.writeln("<div id='print'>" + text + "</div>");
                        printwin.document.writeln('</body></html>');
                        printwin.document.close();
                    } 
                </script>
            </SERV>
            <div class="print">
               <noindex> 
                    <a href="<SE>#</SE><SERV>javascript:myPrint();</SERV>" rel="nofollow">[param6]</a>
               </noindex> 
            </div>   
        </if>
        <div id="forPrint">
            <if:[param4]=='Y'>
                <a name="show[part.id]_[record.id]"></a>
            </if>
            <noempty:record.title>
                <[part.title_tag] class="objectTitle">
                    <span class="contentTitleTxt">[record.title]</span>
                </[part.title_tag]>
            </noempty>
            <noempty:record.image>
                <div id="objimage">
                    <img class="objectImage" alt="[record.image_alt]" src="[record.image]" border="0">
                </div>
            </noempty>
            <if:[param3]=='Y'>
                <noempty:record.note>
                    <div class="objectNote">[record.note]</div>
                </noempty>
            </if>
            <div class="objectText">[record.text]</div> 
        </div>
        <if:[param7]=='Y'>
            <div id="ya_share1" style="margin: 10px 0;">
                <SE>
                    <img src="[this_url_modul]kont.png">
                </SE>
            </div>
        </if>
        <input class="buttonSend back" onclick="document.location.href='[thispage.link]';" type="button" value="[param2]">
    </div> 
{/SHOW}
</div>       
