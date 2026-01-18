<div class="obj"[objedit]<se><if:[record.id]!=[record.first]> style="display:none;"</if></se>>[*edobj]
    <se>
        <a name="r[part.id]obj[record.id]"></a>
    </se>
    <noempty:record.text1>
        <h4 class="objectTitle">
            <span class="objectTitleTxt">[record.text1]</span>
        </h4>
    </noempty>
    <noempty:record.image>
        <img border="0" class="objectImage" src="[record.image_prev]" alt="[record.image_alt]">
    </noempty>
    <noempty:record.note>
        <div class="objectNote">[record.note]</div>
    </noempty>
    <noempty:record.text>
        <div class="objectText">[record.text]</div>
    </noempty>
</div>
