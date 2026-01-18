<!-- Subpage 3. Витрина-->

<script type="text/javascript">
$(document).ready(function(){
$(".priceBox").click(function(event){
if (window.ajax_request !== undefined){
    if ($(event.target).is(".buttonSend.addcart") && window.ajax_request == true){
        var real_goods = $(event.currentTarget).parent().find('.blockImage');
        var clone_goods = real_goods.clone();
        var id_goods = $(this).find("[name='addcart']").val();
        var x = real_goods.offset(), x2 = $(".fixedCart:last #bodyCart").offset();
        var goods_param = $(this).find("[name='listcartparam']").val();
        toggleLoader(false);
        $.ajax({
            url: "?",
            data: {'addcart':id_goods, 'addcartparam': goods_param},
            type: 'post',
            dataType: "json",
            success:function(result){
                if (result.action != 'empty' && result.action){
                    $(".fixedCart").addClass('hoverCart');
                    $(clone_goods)
                        .css({'position' : 'absolute', 'z-index' : '999', 'left' : x.left, 'top': x.top})
                        //.insertBefore(real_goods)
                        .prependTo('body')
                        .stop()
                        .animate({
                            opacity: 0.5,
                            top: x2.top, 
                            left: x2.left}, 
                        500, function(){
                            $(this).remove();
                            $(".fixedCart").removeClass('hoverCart');
                            if (result.action == 'add'){
                                addAjaxCart(result.data);
                            }
                            if(result.action == 'update'){
                                updAjaxCart(result.data);
                            }               
                        });
                }
                else{
                    toggleLoader(true);
                }
            },
            error:function(){
                toggleLoader(true);
            }
        });
    }
}
else{
    if ($(event.target).is(".buttonSend.addcart"))
        $(event.currentTarget).find('.form_addCart').submit();
}
});
});
</script>

<a name="productlst"></a>
<!-- Сортировка -->
<div class="vitrineSort">
    <form style="margin:0px;" method="post" action="">
        <label class="vitrineSortLabel" for="sortOrderby"><?php echo $section->parametrs->param287 ?></label>
        <select class="vitrineSortSelect" id="sortOrderby" name="sortOrderby" onchange="{this.parentNode.submit();}" >
            <?php echo $VITRINE_SORT_OPTIONS ?>
            
        </select>
        <label class="vitrineSortDirLabel" for="sortDir"><?php echo $section->parametrs->param288 ?></label>
        <select class="vitrineSortDirSelect" id="sortDir" name="sortDir" onchange="{this.parentNode.submit();}" >
            <?php echo $VITRINE_SORT_DIRECTION ?>
            
        </select>
    </form>
</div>
<!-- ------------ -->
<?php foreach($section->objects as $record): ?>
    <table border="0" class="objectTable" cellSpacing="0" cellPadding="0">
        <tbody>
            <tr valign="bottom"> <!-- Top line -->
                <td class="borderTopLeftCorner"></td>
                <td class="borderTopCenter"></td>
                <td class="borderTopRightCorner"></td>                                   
            </tr>
            <tr> <!-- Middle -->
                <td class="borderCenterLeft"></td>
                <td class="theGoodContent">
                    <h4 class="objectTitle">
                        <?php if($section->parametrs->param275=='Y'): ?>
                            <a class="textTitle" href="<?php echo $record->linkshow ?>"><?php echo $record->name ?></a>
                        <?php else: ?>
                            <span class="textTitle">
                                <?php echo $record->name ?>
                            </span>
                        <?php endif; ?>
                    </h4>
                    <div class="blockImage"> 
                        <a href="<?php echo $record->linkshow ?>">
                            <img class="objectImage" src="<?php echo $record->image_prev ?>" border="0" title="<?php echo $record->img_alt ?>" alt="<?php echo $record->img_alt ?>">
                        </a>
                        <?php if($record->flag_hit=='Y'): ?>
                            <span class="flag_hit"><?php echo $section->parametrs->param240 ?></span>
                        <?php endif; ?>
                        <?php if($record->flag_new=='Y'): ?>
                            <span class="flag_new"><?php echo $section->parametrs->param241 ?></span>
                        <?php endif; ?>
                        <?php if($record->unsold=='Y'): ?>
                            <span class="user_price"><?php echo $section->parametrs->param245 ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if($section->parametrs->param266=='Y'): ?>
                        <div class="objectRating">
                            <span class="objectVotesTitle"><?php echo $section->parametrs->param244 ?></span>
                            <span class="objectVotes"><?php echo $record->votes ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param269=='Y'): ?>
                        <div class="comparebox">
                   <input type="checkbox"  value="onCheck" <?php echo $record->compare ?>
                                    onclick="if(this.checked){document.location.href='?ajax_compare=1&idprice=<?php echo $record->id ?>&compare=on&pages=<?php echo $forcompare ?>';}
                                    else
                                    {document.location.href='?ajax_compare=1&idprice=<?php echo $record->id ?>&compare=off&pages=<?php echo $forcompare ?>';}"><label> <?php echo $section->language->lang013 ?> </label>      
                        </div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param114=='Y'): ?>
                        <?php if(!empty($record->note)): ?>
                            <div class="objectNote"><?php echo $record->note ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($section->parametrs->param115=='Y'): ?>
                        <?php if(!empty($record->text)): ?>
                            <div class="objectText"><?php echo $record->text ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($section->parametrs->param83=='Y'): ?>
                        <div class="objectCode"><?php echo $section->parametrs->param116 ?>&nbsp;<?php echo $record->article ?></div>
                    <?php endif; ?>
                    <?php if($section->parametrs->param84=='Y'): ?>
                        <?php if(!empty($record->manufacturer)): ?>
                            <div class="objectManufacturer"><?php echo $section->parametrs->param118 ?>&nbsp;<?php echo $record->manufacturer ?></div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if($section->parametrs->param111=='Y'): ?>        

                       <?php if($record->nullprice=='0'): ?> 

                            <div class="objectPresent">
                                <span class="presenceHeader"><?php echo $section->parametrs->param120 ?></span>
                                <span class="presenceText" id="count_<?php echo $razdel ?>_<?php echo $record->id ?>">&nbsp;<?php echo $record->count ?></span>
                            </div>

                        <?php else: ?>

                            <?php if($section->parametrs->param294!=''): ?>
                                <div class="objectNullPrice"><?php echo $section->parametrs->param294 ?></div>
                            <?php endif; ?>

                        <?php endif; ?>

                    <?php endif; ?>
                    <div class="priceBox">
                        <form class="form_addCart" style="margin:0px;" method="post" action="">
                            <?php if(!empty($record->params)): ?>
                                <div class="divparam">
                                    <?php echo $record->params ?>
                                </div>
                            <?php endif; ?>
                           <?php if($record->nullprice=='0'): ?> 
                                <div class="priceStyle">
                                    <?php if($section->parametrs->param226=='Y'): ?>
                                        <span class="priceHeader"><?php echo $record->priceheader ?></span>
                                    <?php endif; ?>
                                    <?php echo $record->newprice ?>
                                </div>
                           <?php endif; ?>
                            <div class="buttonBox">
                                <input type="hidden" name="addcart" value="<?php echo $record->id ?>">
                                <input type="hidden" name="listcartparam" value="<?php echo $record->listcartparam ?>">
                                <span class="addcart_<?php echo $razdel ?>_<?php echo $record->id ?>" style="<?php echo $record->show_addcart ?>">
                                    <a class="buttonSend addcart" href="javascript:void(0);" title="<?php echo $section->parametrs->param9 ?>"><?php echo $section->parametrs->param3 ?></a>
                                </span>
                                <a class="buttonSend details" href="<?php echo $record->linkshow ?>"><?php echo $section->parametrs->param2 ?></a>
                            </div>                            
                        </form>
                    </div>
                </td>
                <td class="borderCenterRight"></td>
            </tr>
            <tr valign="top"> <!-- Bottom line -->
                <td class="borderBottomLeftCorner"></td>
                <td class="borderBottomCenter"></td>
                <td class="borderBottomRightCorner"></td>
            </tr>
        </tbody>
    </table>

<?php endforeach; ?>
