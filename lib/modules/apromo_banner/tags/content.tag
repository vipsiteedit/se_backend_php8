<header:css>
[include_css] 
<serv>
<if:[param4]=='r'>
    <style>
        .part[part.id] .square_banner_block-image {
                background-size: 130% auto !important;
        }        
    </style>
</if>
<if:[param7]=='a'>
    <style>
        .part[part.id] .square_banner_block-image:hover {
        top: -30%;
        }
    </style>
</if> 
</serv>
</header:css>  
<se>
<if:[param4]=='r'>
    <style>
        .part[part.id] .square_banner_block-image {
                background-size: 130% auto !important;
        }        
    </style>
</if>
<if:[param7]=='a'>
    <style>
        .part[part.id] .square_banner_block-image:hover {
        top: -30%;
        }
    </style>
</if> 
</se>
<div class="<if:[param3]=='n'>container<else>container-fluid</if>">
<section class="square_banner_block part[part.id]" [contedit]>
    <repeat:records>
 <a class="square_banner_block-link" href="[record.field]" [objedit]>
        <span class="square_banner_block-image" <noempty:[record.image]>style="background-image: url('[record.image]')"</noempty> >
            <div class="apromo-item_text">
                    <if:[param5]=='a'>
                      <noempty:record.title>
                        <[record.title_tag] class="objectTitle">
                             <span class="objectTitleTxt">[record.title]</span>
                        </[record.title_tag]>
                      </noempty>
                    </if>
                    <if:[param6]=='a'>
                      <noempty:record.note>
                        <div class="objectNote">[record.note]</div>
                      </noempty>
                    </if>
            </div>
        </span>
 </a>
    </repeat:records>
</section>
</div>    
           
