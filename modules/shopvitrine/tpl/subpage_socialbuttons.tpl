<footer:js>
[js:social/social-likes.min.js]
<?php if(strval($section->parametrs->param312) == '01'): ?>
[lnk:social/social-likes_birman.css]
<?php endif; ?>
<?php if(strval($section->parametrs->param312) == '02'): ?>
[lnk:social/social-likes_flat.css]
<?php endif; ?>
<?php if(strval($section->parametrs->param312) == '03'): ?>
[lnk:social/social-likes_classic.css]
<?php endif; ?>
</footer:js>
<div class="socialButtons">
    <div class="social-likes social-likes_vertical_ social-likes_single_" data-single-title="Поделиться" <?php if(!empty($product_name)): ?>data-title="<?php echo $product_name ?>"<?php endif; ?> <?php if(!empty($product_link)): ?>data-url="<?php echo $product_link ?>"<?php endif; ?>>
        <div class="vkontakte" title="Поделиться ссылкой во Вконтакте"><span class="socialTitle">Вконтакте</span></div>
        <div class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках"><span class="socialTitle">Одноклассники</span></div>
        <div class="facebook" title="Поделиться ссылкой на Фейсбуке"><span class="socialTitle">Facebook</span></div>
        <div class="twitter" title="Поделиться ссылкой в Твиттере"><span class="socialTitle">Twitter</span></div>
        <div class="mailru" title="Поделиться ссылкой в Моём мире"><span class="socialTitle">Мой мир</span></div>
        <div class="plusone" title="Поделиться ссылкой в Гугл-плюсе"><span class="socialTitle">Google+</span></div>
    </div>
</div>
