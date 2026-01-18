<footer:js>
[js:jquery/jquery.min.js]
<if:[param1]=='Y'>
[lnk:ionrangeslider/ionrangeslider.min.css]  
[js:ionrangeslider/ionrangeslider.min.js]
</if>
[include_js({
    ajax_url: '?ajax[part.id]',
    param4:'[param4]',
    partNum: '[part.id]',
    param2:'[param2]',
    param3:'[param3]',
    param1:'[param1]',
    filter: '{$filter}'
     
})]
</footer:js>
<div class="content shopFilter" 
    data-type="[part.type]" data-id="[part.id]" <serv>style="display: none;"</serv>[contedit]>
 <noempty:part.title>
        <h3 class="contentTitle">
            <span class="contentTitleTxt">[part.title]</span>
        </h3>
    </noempty> 
<div class="contentBody">    
<se>[subpage name=main]</se>
</div>
</div>
