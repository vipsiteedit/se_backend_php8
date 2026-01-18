<header:css>
    [include_css]
</header:css>
<div class="<if:[param3]=='n'>container<else>container-fluid</if>">
<section class="content row b_banners_group clearfix" [contedit]>
  <div class="b_about_site">
    <div class="b_about_site-content">
        <[part.title_tag] class="b_about_site-content_text">[part.title]</[part.title_tag]>
        <p>[part.text]</p>
    </div>
    <div class="b_about_site-link_block">
        <a href="[param4].html" class="buttonSend">[lang002]</a>
    </div>
  </div>
    <div class="b_banners_group-containers_block">
    <repeat:records>
    <div class="b_banners_group-items">
    <div class="b_banners_group-container" <noempty:[record.image]>style="background-image: url('[record.image]');"</noempty> [objedit]>
             <a class="b_banners_group-container_link" href="[record.field]">
                <div class="b_banners_group-container_content">
                     <noempty:record.title><h4 class="b_banners_group-container_title">[*edobj][record.title]</h4></noempty>
                     <noempty:record.note><div class="b_banners_group-container_text">[record.note]</div></noempty>
                </div>
             </a>
    </div></div>
    </repeat:records>
    </div>
</section>
</div>
