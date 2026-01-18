<div class="content cont_golos"[contedit]>
    <noempty:part.title>
        <a name="[part.id]"></a>
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
    [*addobj]
    <form action="" method="post" style="margin:0px;">
        <div class="object" [buttonstyle] >
            <wrapper>
                <repeat:records>    
                    <span class="objectTitle record-item"[objedit]>
                        <input TYPE="RADIO" name="voting_radio" value="[obj]"> 
                        [*edobj]
                        <span>[record.title]</span>
                    </span> 
                </repeat:records>
            </wrapper>
            {$VOTINGSHOW}
            <div class="areaButton">
                <input name="GoTo_VOTING" class="buttonSend buttonVoting" {$voting->buttonstyle} [#"type=submit"][se."type=button"] value="[lang001]">
                <input name="GoTo_SHOW" class="buttonSend buttonResult" {$voting->buttonstyle} [#"type=submit"][se."type=button"] value="[lang002]">
            </div> 
            <input type="hidden" name="razdel" value="[part.id]">
        </div>
    </form> 
<se> 
    <div style="width: [param1]px; border-left: 1px solid #000; border-bottom: 1px solid #000;" class="graph_result">
        <div style="width: 20%; height: 10px; margin-bottom: 5px; background: #FF0000;">&nbsp;</div>
        <div style="width: 30%; height: 10px; margin-bottom: 5px; background: #8000FF;">&nbsp;</div>
        <div style="width: 50%; height: 10px; margin-bottom: 5px; background: #00BF30;">&nbsp;</div>
        <div style="width: 0%; height: 10px; margin-bottom: 5px; background: #800080;">&nbsp;</div>
    </div> 
    <ul style="list-style: none;">
        <li class="golos_txt" style="color: #FF0000">- [lang003]<!--[lang004]--> 20%</li> 
        <li class="golos_txt" style="color: #8000FF">- [lang005]<!--[lang006]--> 30%</li> 
        <li class="golos_txt" style="color: #00BF30">- [lang007]<!--[lang008]--> 50%</li> 
        <li class="golos_txt" style="color: #800080">- [lang009]<!--[lang010]--> 0%</li> 
    </ul> 
    <div class="golos_itog">[lang011]
        <span class="txts">1</span>
    </div> 
</se> 
<se>
    <div style="clear:both; border-width:3px; padding: 5pt; font-size:12px; border-color: #FF0000; border-style:dashed; width=100%; height=auto; background-color:white; color:black; " class="sysedit">        [lang012]<br> 
        [lang014]: [param2].
    </div> 
</se> 
</div> 
