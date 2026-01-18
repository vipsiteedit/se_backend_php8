/* share42.com | 04.03.2012 | (c) Dimox */
function share42(f,m1,m2){(function($){ $(function(){if(!m1)m1=150;if(!m2)m2=20;$('div.share42init').each(function(){u=$(this).attr('data-url');t=$(this).attr('data-title');if(!u)u=location.href;if(!t)t=document.title;u=encodeURIComponent(u);t=encodeURIComponent(t);var s=new Array('"#" onclick="window.open(\'http://www.blogger.com/blog_this.pyra?t&u='+u+'&n='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="[lang007]"',
'"#" onclick="window.open(\'http://www.facebook.com/sharer.php?u='+u+'&t='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="[lang001]"','"#" onclick="window.open(\'https://plusone.google.com/_/+1/confirm?hl=ru&url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="[lang002]"',
'"#" onclick="window.open(\'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+u+'&title='+t+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="[lang003]"',
'"#" onclick="window.open(\'http://twitter.com/share?text='+t+'&url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=550, height=440, toolbar=0, status=0\');return false" title="[lang004]"',
'"#" onclick="window.open(\'http://vk.com/share.php?url='+u+'\', \'_blank\', \'scrollbars=0, resizable=1, menubar=0, left=200, top=200, width=554, height=421, toolbar=0, status=0\');return false" title="[lang005]"',
'"http://my.ya.ru/posts_add_link.xml?URL='+u+'&title='+t+'" title="[lang006]"');

var l='';

var iNum=7;var iShow=[param3];var iPages=Math.ceil(iNum/iShow);

for(j=0;j<s.length;j++){var s42s='';
if((j+1)%iShow==0){s42s='</div><div class="s42s">';}
l+='<a rel="nofollow" style="display:block;width:[param2]px;height:[param2]px;margin:0 0 6px;outline:none;background:url('+f+'{$img}) -'+[param2]*j+'px 0" href='+s[j]+' target="_blank"></a>'+s42s;};
$(this).html('<span id="share42">'+l+'</span>');});var p=$('#share42');function m(){var top=$(window).scrollTop();if(top+m2<m1){p.css({top:m1-top});}else{p.css({top:m2});}}m();$(window).scroll(function(){m();})})})(jQuery)};
