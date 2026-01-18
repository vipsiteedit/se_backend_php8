<div class="content">
  <div id="view">
    <h4 class="objectTitle">{$titlepage}</h4>
    <if:{$imgfull}!=''>
    <div id="objimage">
        <img class="objectImage" alt="{$titlepage}" src="{$imgfull}" border="0">
    </div>
    </if>
    <div class="objectText">
    {$fulltext}
    </div>
    <input class="buttonSend" onclick="document.location = '[thispage.link]'" type="button" value="[lang015]">
  </div>
</div>
