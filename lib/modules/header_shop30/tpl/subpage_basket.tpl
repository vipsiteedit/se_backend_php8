                    <a class="infoLink" id="informer_shop_cart-count_goods" href="<?php echo seMultiDir()."/".$section->parametrs->param6."/" ?>"><?php echo $count_goods ?></a>
                    <noindex>
                    <div class="content contFlyCart b_shop_cart_informer" 
                        data-type="ashop_flycart53" data-id="101002" style="display:none;">
                        <div id="fixedCart" class="fixedCart ui-droppable" style="cursor: auto;">
                            <div class="loaderAjax" title="<?php echo $section->language->lang004 ?>" style="display: none;">&nbsp;</div>
                            <div id="headCart">
                                <a id="linkGoCart" href="/shopcart/"><?php echo $section->language->lang005 ?></a>
                                <a class="butShowHide" href="javascript:void(0);" title="<?php echo $section->language->lang007 ?>">&nbsp;</a>
                            </div>  
                            <div id="bodyCart">
                                
                            </div>
                            <div id="footCart">
                                <a href="/shopcart/" class="orderLink" title="<?php echo $section->language->lang011 ?>"><?php echo $section->language->lang008 ?></a>
                                <a href="/shopcart/cart_clear/" class="clearCartLink" 
                                    title="<?php echo $section->language->lang010 ?>" style="display:none;"><?php echo $section->language->lang009 ?></a>
                            </div>
                        </div>
                    </div>
                    </noindex>
