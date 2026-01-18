<footer:js>
[js:jquery/jquery.min.js]
<script style="text/javascript">
    $(".testShowAjaxIcon").toggle(function(){
        $('.loaderAjax').show();
        $(this).attr('value','[lang013]');
    }, function(){
        $('.loaderAjax').hide();
        $(this).attr('value','[lang006]');
    });
    
    $("#emptyAllGoods").toggle(function(){
        $('.noGoods').show();
        $('.issetGoods, .goodInfo').hide();
        $(this).attr('value','[lang014]');
    }, function(){
        $(".issetGoods, .goodInfo:not(.defHidden)").show();
        $('.noGoods').hide();
        $(this).attr('value','[lang007]');
    });
    
</script>
</footer:js>
<div class="content contFlyCart">
<h3>[lang001]</h3>
<p>[lang002] -> [lang003] -> [lang004] -> [lang005]</p>
<input class="testShowAjaxIcon" type="button" value="[lang006]">
<br/><br/>
<input id="emptyAllGoods" type="button" value="[lang007]">
<br/><br/>
<input class="sysedit testShortExtCart" type="button" title="[lang015]" value="[lang016]">
<table border="0" align>
<tr align="center">
    <td>
        &nbsp;
    </td>
    <td>
        [lang008]
    </td>
    <td>
        [lang004]
    </td>
    <td>
        [lang005]
    </td>
    <td>
        [lang009]
    </td>
</tr>
<tr>
    <td>
        [lang010]
    </td>
    <td>
        <div id="fixedCart" class="fixedCart">
            [subpage name=cart]    
        </div>    
    </td>
    <td>
        <div id="fixedCart" class="fixedCart activeCart">
            [subpage name=cart]
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart hoverCart">
            [subpage name=cart]
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart activeCart hoverCart">
            [subpage name=cart]
        </div>
    </td>
</tr>
<tr>
    <td>
        [lang003]
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart">
            [subpage name=cart]
        </div>    
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart activeCart">
            [subpage name=cart]
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart hoverCart">
            [subpage name=cart]
        </div>
    </td>
    <td>
        <div id="fixedCart" class="fixedCart flyCart activeCart hoverCart">
            [subpage name=cart]
        </div>
    </td>
</tr>
</table>
</div>
