<footer:js>[include_js()]</footer:js>
<if:[param10]=='Y'>
<footer:js>
[js:jquery/jquery.min.js]
[include_js({
    part_id: '[part.id]',
    url_end: '{$url_end}',
    param4: '[param4]',
    param9: '[param9]',
    param13: '[param13]',
    multilink: '{$multilink}'
})]
</footer:js>
</if>
<div class="content shopGrouppic part[part.id]" [contentstyle][contedit]>
    <noempty:part.title>
        <h3 class="contentTitle" [part.style_title]>
            <span class="contentTitleTxt">[part.title]</span>
        </h3>
    </noempty>
    <noempty:part.image>
        <img alt="[part.title]" border="0" class="contentImage" [part.style_image] src="[part.image]">
    </noempty>
    <noempty:part.text>
        <div class="contentText" [part.style_text]>[part.text]</div>
    </noempty>
    <div class="groupList">
        [$PRICEMENU]
        <SE> 
            <div class="menuUnit">
                <a href="<if:[param10]=='Y'>javascript:SHMenu(1, '', 0, 0);<else>[param1].html</if>" class="menu menu0 menuActive">
                    <if:[param7]!=1>
                        <img src='[system.path]img\img_100.jpg'>
                        <if:[param8]=='Y'>
                            <span class='span'>[lang001]<if:[param2]=='Y'><span>(1)</span></if></span>
                        </if>
                    <else>
                        <span class='span'>[lang001]<if:[param2]=='Y'><span>(1)</span></if></span>
                    </if>
                </a>
                <div class="submenu submenu1 submenu_mu1" style="display:<if:[param4]==0>block<else>none</if>;">
                    <div class="menuUnit menuUnit1">
                        <a href="[param1].html" class="menu menu1 menuActive">
                            <if:[param7]==3>
                                <img src='[system.path]img\img_100.jpg'>
                                <if:[param8]=='Y'>
                                    <span class='span'>[lang002]<if:[param2]=='Y'><span>(4)</span></if></span>
                                </if>
                            <else>
                                <span class='span'>[lang002]<if:[param2]=='Y'><span>(4)</span></if></span>
                            </if>
                        </a>
                    </div>
                    <div class="menuUnit menuUnit1">
                        <a href="[param1].html" class="menu menu1">
                            <if:[param7]==3>
                                <img src='[system.path]img\img_100.jpg'>
                                <if:[param8]=='Y'>
                                    <span class='span'>[lang003]<if:[param2]=='Y'><span>(4)</span></if></span>
                                </if>
                            <else>
                                <span class='span'>[lang003]<if:[param2]=='Y'><span>(4)</span></if></span>
                            </if>
                        </a>
                    </div>                        
                </div>
            </div>
            <div class="menuUnit">
                <a href="<if:[param10]=='Y'>javascript:SHMenu(2, '', 3, 0);<else>[param1].html</if>" class="menu menu0">
                    <if:[param7]!=1>
                        <img src='[system.path]img\img_100.jpg'>
                        <if:[param8]=='Y'>
                            <span class='span'>[lang004]<if:[param2]=='Y'><span>(4)</span></if></span>
                        </if>
                    <else>
                        <span class='span'>[lang004]<if:[param2]=='Y'><span>(4)</span></if></span>
                    </if>
                </a>
                <div class="submenu submenu1 submenu_mu2" style="display:<if:[param4]==0>block<else>none</if>;">
                    <div class="menuUnit menuUnit1">
                        <a href="[param1].html" class="menu menu1">
                            <span class='span'>[lang005]<if:[param2]=='Y'><span>(4)</span></if></span>
                        </a>
                    </div>
                    <div class="menuUnit menuUnit1">
                        <a href="<if:[param10]=='Y'>javascript:SHMenu(3, '', 0, 0);<else>[param1].html</if>" class="menu menu1">
                            <if:[param7]==3>
                                <img src='[system.path]img\img_100.jpg'>
                                <if:[param8]=='Y'>
                                    <span class='span'>[lang006]<if:[param2]=='Y'><span>(0)</span></if></span>
                                </if>
                            <else>
                                <span class='span'>[lang006]<if:[param2]=='Y'><span>(0)</span></if></span>
                            </if>
                        </a>
                        <div class="submenu submenu2 submenu_mu3" style="display:<if:[param4]==0>block<else>none</if>;">
                            <div class="menuUnit menuUnit2">
                                <a href="<if:[param10]=='Y'>javascript:SHMenu(4, '', 0, 0);<else>[param1].html</if>" class="menu menu2">
                                    <if:[param7]==3>
                                        <img src='[system.path]img\img_100.jpg'>
                                        <if:[param8]=='Y'>
                                            <span class='span'>[lang007]<if:[param2]=='Y'><span>(7)</span></if></span>
                                        </if>
                                    <else>
                                        <span class='span'>[lang007]<if:[param2]=='Y'><span>(7)</span></if></span>
                                    </if>
                                </a>
                                <div class="submenu submenu3 submenu_mu4" style="display:<if:[param4]==0>block<else>none</if>;">
                                    <div class="menuUnit menuUnit3">
                                        <a href="[param1].html" class="menu menu3">
                                            <if:[param7]==3>
                                                <img src='[system.path]img\img_100.jpg'>
                                                <if:[param8]=='Y'>
                                                    <span class='span'>[lang008]<if:[param2]=='Y'><span>(7)</span></if></span>
                                                </if>
                                            <else>
                                                <span class='span'>[lang008]<if:[param2]=='Y'><span>(7)</span></if></span>
                                            </if>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="menuUnit menuUnit2">
                                <a href="[param1].html" class="menu menu2">
                                    <if:[param7]==3>
                                        <img src='[system.path]img\img_100.jpg'>
                                        <if:[param8]=='Y'>
                                            <span class='span'>[lang009]<if:[param2]=='Y'><span>(17)</span></if></span>
                                        </if>
                                    <else>
                                        <span class='span'>[lang009]<if:[param2]=='Y'><span>(17)</span></if></span>
                                    </if>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="menuUnit"> 
                <a href="<if:[param10]=='Y'>javascript:SHMenu(5, '', 0, 0);<else>[param1].html</if>" class="menu menu0">
                    <span class='span'>[lang010]<if:[param2]=='Y'><span>(40)</span></if></span>
                </a>  
                <div class="submenu submenu1 submenu_mu5" style="display:<if:[param4]==0>block<else>none</if>;">
                    <div class="menuUnit menuUnit1"> 
                        <a href="[param1].html" class="menu menu1">
                            <span class='span'>[lang011]<if:[param2]=='Y'><span>(7)</span></if></span>
                        </a>
                    </div>
                    <div class="menuUnit menuUnit1"> 
                        <a href="[param1].html" class="menu menu1">
                            <if:[param7]==3>
                                <img src='[system.path]img\img_100.jpg'>
                                <if:[param8]=='Y'>
                                    <span class='span'>[lang012]<if:[param2]=='Y'><span>(3)</span></if></span>
                                </if>
                            <else>
                                <span class='span'>[lang012]<if:[param2]=='Y'><span>(3)</span></if></span>
                            </if>
                        </a>
                    </div>
                    <div class="menuUnit menuUnit1"> 
                        <a href="[param1].html" class="menu menu1">
                            <if:[param7]==3>
                                <img src='[system.path]img\img_100.jpg'>
                                <if:[param8]=='Y'>
                                    <span class='span'>[lang013]<if:[param2]=='Y'><span>(2)</span></if></span>
                                </if>
                            <else>
                                <span class='span'>[lang013]<if:[param2]=='Y'><span>(2)</span></if></span>
                            </if>
                        </a>
                    </div>
                    <div class="menuUnit menuUnit1"> 
                        <a href="[param1].html" class="menu menu1">
                            <span class='span'>[lang014]<if:[param2]=='Y'><span>(1)</span></if></span>
                        </a>
                    </div>
                </div>
            </div>
        </SE>
    </div>
</div> 
