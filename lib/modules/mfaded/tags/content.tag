<header:js>
    <style type="text/css">
        .fadedImages .faded * {margin:0px;}
        .faded.none {display:none;}
    </style>
</header:js>
<footer:js>
[js:jquery/jquery.min.js]
[module_js:jscript_jquery.faded.js]
<script type="text/javascript">
    $(function(){
        $(".faded.n[part.id]").faded({
            speed: '[param2]',
            autoplay: '[param3]',
            random: '[param4]'
        });
        $(".faded.none").removeClass('none');
    });
</script>
</footer:js>
<div class="content fadedImages" data-seimglist="[part.id]" [contedit]>
    <noempty:part.title>
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
    <div class="faded n[part.id] none">
        <ul class="fadedArea">
            <repeat:records>
                <li class="object"[objedit]>[*edobj]
                    <noempty:record.title>
                        <[record.title_tag] class="objectTitle">
                            <span class="objectTitleTxt">[*edobj][record.title]</span>
                        </[record.title_tag]>
                    </noempty>
                    <noempty:record.image>
                        <img class="objectImage" src="[record.image_prev]" alt="[record.image_alt]" title="[record.image_title]">
                    </noempty>
                    <noempty:record.note>
                        <div class="objectNote">[record.note]</div>
                    </noempty>
                    <noempty:record.field>
                        <a class="linkNext" href="[record.field]">[lang001]</a>
                    </noempty>
                </li>
            </repeat:records>
        </ul>
    </div>
</div>
