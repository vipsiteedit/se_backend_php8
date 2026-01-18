<script type='text/javascript'>
/* share42.com | 04.03.2012 | (c) Dimox */
function share42(f,m1,m2){(function($){ $(function(){if(!m1)m1=150;if(!m2)m2=20;$('div.share42init').each(function(){u=$(this).attr('data-url');t=$(this).attr('data-title');if(!u)u=location.href;if(!t)t=document.title;u=encodeURIComponent(u);t=encodeURIComponent(t);var s=new Array('"#" onclick="window.open(\'http://www.blogger.com/blog_this.pyra?t&u='+u+'&n='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang007 ?>"',
'"#" onclick="window.open(\'http://www.facebook.com/sharer.php?u='+u+'&t='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang001 ?>"','"#" onclick="window.open(\'https://plusone.google.com/_/+1/confirm?hl=ru&url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang002 ?>"',
'"#" onclick="window.open(\'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+u+'&title='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang003 ?>"',
'"#" onclick="window.open(\'http://twitter.com/share?text='+t+'&url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang004 ?>"',
'"#" onclick="window.open(\'http://vk.com/share.php?url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=554, height=421, toolbar=0, status=0\');return false" title="<?php echo $section->language->lang005 ?>"',
'"http://my.ya.ru/posts_add_link.xml?URL='+u+'&title='+t+'" title="<?php echo $section->language->lang006 ?>"');
var l='';
<?php if($oth_code!=0): ?>
var iNum=7;var iShow=<?php echo $section->parametrs->param3 ?>;var iPages=Math.ceil(iNum/iShow);
<?php endif; ?>
for(j=0;j<s.length;j++){var s42s='';
<?php if($oth_code!=0): ?>
if((j+1)%iShow==0){s42s='</div><div class="s42s">';}
<?php endif; ?>
l+='<a rel="nofollow" style="display:block;width:<?php echo $section->parametrs->param2 ?>px;height:<?php echo $section->parametrs->param2 ?>px;margin:0 0 6px;outline:none;background:url('+f+'<?php echo $img ?>) -'+<?php echo $section->parametrs->param2 ?>*j+'px 0" href='+s[j]+' target="_blank"></a>'+s42s;};
<?php if($oth_code==0): ?>
$(this).html('<span id="share42">'+l+'</span>');});var p=$('#share42');function m(){var top=$(window).scrollTop();if(top+m2<m1){p.css({top:m1-top});}else{p.css({top:m2});}}m();$(window).scroll(function(){m();})})})(jQuery)};
<?php else: ?>
$(this).html('<div id="share42" style="width:<?php echo $section->parametrs->param2 ?>px"><div class="s42s">'+l+'</div></div><style>#share42 a.s42a.t{background-position: -'+<?php echo $section->parametrs->param2 ?>*7+'px 50% !important}</style>');
var a='<a href="#" class="s42a" style="display:block;height:10px;margin:-2px 0 4px;background:url('+f+'<?php echo $img ?>) -'+8*<?php echo $section->parametrs->param2 ?>+'px 50%;outline:none"></a>';var d='#share42 a.s42a';var l='#share42 div.s42s';$(l+':first').append(a);$(l+':not(:first)').css({display:'none'});if($(l).length>iPages)$(l+':last').remove();function ac(){ $(d+':first-child').addClass('t');$(d+':last-child').addClass('b');};function r(){ $(d).not(':visible').remove();};$(d+':not(.t)').live('click',function(){var p=$(this).parent();r();p.animate({height:'hide'},300).next().not(':last-child').prepend(a).append(a);p.next(':last-child').prepend(a);ac();p.next().animate({height:'show'},300);return false;});$(d+'.t').live('click',function(){var p=$(this).parent();r();p.animate({height:'hide'},300).prev().not(':first-child').prepend(a).append(a);p.prev(':first-child').append(a);ac();p.prev().animate({height:'show'},300);return false;});});
var p=$('#share42');function m(){var top=$(window).scrollTop();if(top+m2<m1){p.css({top:m1-top});}else{p.css({top:m2});}}m();$(window).scroll(function(){m();})})})(jQuery)};
<?php endif; ?>
</script>
