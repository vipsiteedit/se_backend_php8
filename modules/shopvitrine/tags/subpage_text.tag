<noempty:{$product_text}>
<if:{$use_tabs}>
    <repeat:tabs name=tab>
        <h3 class="titleHead"><span>[tab.title]</span></h3>
        <div class="content">[tab.content]</div>
    </repeat:tabs>
<else>
    <h3 class="titleHead" id="text"><span>[lang017]</span></h3>
    <div class="content">{$product_text}</div>
</if>
</noempty>
