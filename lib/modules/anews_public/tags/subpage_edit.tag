[subpage name=scripts]
<if:[param2]!='d'><div class="<if:[param2]=='n'>container<else>container-fluid</if>"></if>
<section class="content news-public add">
    <b class="errorText">[se."Сообщение об ошибке"][$errortext]</b> 
    <form class="form-horizontal" method="post" action="[link.subpage=edit]" enctype="multipart/form-data" style="margin:0px;">
        <div class="form-group">
             <label class="control-label col-xs-2" for="publics">[lang020]*</label>
             <div class="col-xs-2">
                <input class="form-control" type="date" name="date" value="{$date}">
             </div>
             <div class="col-xs-8">
                <div class="checkbox">
    <label>
        <input type="checkbox" name="publics" {$checked}> [lang021]
                </label>
    </div>
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="parttitle">[lang014]*</label>
             <div class="col-xs-10">
                <input class="form-control" type="text" name="title" value="[$_title]">
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="userfile">[lang015]</label>
             <div class="col-xs-10">
                 <input class="form-control" type="file" name="userfile">
             </div>
        </div>
        <div class="form-group">
             <label class="control-label col-xs-2" for="text">[lang016]*</label>
             <div class="col-xs-10">
                <textarea style="width:100%;" rows="10" cols="40" id="edittar" name="text" class="textarea">[$_text]</textarea>
             </div>
        </div>
        <div class="form-group">
            <div class="col-xs-2"></div>
            <div class="col-xs-4">
            <input class="btn btn-success edSave" type=<SE>"button"</SE><SERV>"submit"</SERV> name="Save" value="[lang017]"<SE> onclick="document.location='[thispage.link]';"</SE>>
            <input class="btn btn-default edBack" type="button" value="[lang019]" onclick="document.location='[thispage.link]';">
            </div>
        </div>
    </form> 
</section>
<if:[param2]!='d'></div></if>
