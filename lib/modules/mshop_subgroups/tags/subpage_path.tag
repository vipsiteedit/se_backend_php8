<noempty:{$path_list}>    
    <div class="groupPath">
         <div>
         <a class="lnkPath" href="home.html">[lang004]</a>
        <span class="separPath">[param17]</span>
        <repeat:pathgroup name=path> 
            <if:[path.link]!=''>
                <a class="lnkPath" href="[path.link]">[path.name]</a>
            <else>
                <span class="lnkPath">[path.name]</span>
            </if>
            <noempty:[path.separator]>
                <span class="separPath">[path.separator]</span>
            </noempty>
        </repeat:pathgroup>
        </div>
    </div>
</noempty>
