<?php

        $price_id = getRequest('idprice', 1);
        $this_id = getRequest('value', 1); 
        $i_param = getRequest('iparam', 1); 
        $i_select = floor($i_param / 100); 
        $type = getRequest('ttypes', 1);
        $shopdiscount = new plugin_shopDiscount($price_id);   
        $discountproc = $shopdiscount->execute();    
    
        $price = new seShopPrice();
        $price->select('id, price, price_opt, price_opt_corp, presence_count, presence, curr, special_price, discount, measure, img, img_alt'); 
        $goods = $price->find($price_id);
        $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select] = $this_id; // Для сохранения состояния
        $prc = 0; 
        $cnt = 0;
        list($sel, $paramprc, $cnt) = getTreeParam($section, 0, $price_id, $price->presence_count, 0, false, $type, $__MDL_URL);

         // --- Округление и сепараторы ---
        $rounded = ($section->parametrs->param243 == 'Y'); // округление

        if ($section->parametrs->param276 == 'Y') // сепаратор между 1 000 000
            $separator = ' ';
        else
            $separator = '';
                                   
        if ($section->parametrs->param225 == seUserGroupName()) { // optcorp
            $ptype = 1;
            $header = $section->parametrs->param227;
        } else if ($section->parametrs->param224 == seUserGroupName()) { // optovik  
            $ptype = 2;
            $header = $section->parametrs->param122;
        } else {                                    // розничный покупатель
            $ptype = 0;
            $header = $section->parametrs->param121;
        }
        
       $paramlist = 'param:' . join(',', $_SESSION['SHOP_VITRINE']['selected'][$price_id]);                                                                                                                                             
        $plugin_amount = new plugin_shopAmount($price_id, '', $ptype, 1, 
                                               $paramlist, 
                                               $pricemoney);
        $maxcnt = $plugin_amount->getPresenceCount();      
        $goodsStyle = ($maxcnt != 0);        
        //$cnt = $plugin_amount->showPresenceCount($section->parametrs->param69);  // param69 - альтернативный текст "Есть"        
        list($cnt, $goodsStyle) = getShopTextCount($section, $goods, $maxcnt);
        
        $realprice = $plugin_amount->showPrice(true, // discounted
                                               $rounded, // округлять ли цену
                                               $separator); // разделять ли пробелами 000 000
        $oldprice = $plugin_amount->showPrice(false, // discounted
                                              $rounded, // округлять ли цену
                                              $separator); // разделять ли пробелами 000 000        
        $res_img = getParamImg($price_id, $_SESSION['SHOP_VITRINE']['selected'][$price_id][$i_select]);
        //$path_imgall = '/images/'.se_getLang().'/shopimg/';
        //$path_imgmain = '/images/'.se_getLang().'/shopprice/';     
        if (empty($res_img['imgparam'])) {                                         
            if($goods['img']!=''){                             
                list($res_img['imgparam1'],) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param293), 'w', $section->parametrs->param292); //$path_imgmain.$goods['img'];
                list($res_img['imgparam_mid'], ) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param286), 'w', $section->parametrs->param293);
                list($res_img['imgparam_prev'], ) = $psg->getGoodsImage($goods['img'], intval($section->parametrs->param286));    
                if($section->parametrs->param282=='N'){
                    $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam1']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
                }
                if($section->parametrs->param282=='L'){
                    $rezult = "<a class=\"cloud-zoom\" href=\"{$res_img['imgparam1']}\" id=\"zoom1\" rel=\"position: 'right', adjustX: 10, adjustY: 0, showTitle: false, zoomWidth: ".$section->parametrs->param284."\">
                    <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\" title=\"{$res_img['imgparam_alt']}\">
                </a>";
                }
                if($section->parametrs->param282=='Z'){
                    $rezult = "<div id=\"photo\">
                    <a id=\"lightbox-foto1\" rel=\"lightbox-foto\" href=\"{$res_img['imgparam1']}\" title=\"{$res_img['imgparam_alt']}\">
                        <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\">
                    </a>
                </div>";            
                }
            } else {                                   
                $is = new plugin_ShopImages();
                $res_img['imgparam'] = $res_img['imgparam1'] = $res_img['imgparam_mid'] = $res_img['imgparam_prev'] = $is->getFullFromImage($goods['img']);
                $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
            }
        //есть доп картинка        
        } else {                                                                       
            list($res_img['imgparam1'],) = $psg->getGoodsImage($res_img['imgparam'], intval($section->parametrs->param293), 'w', $section->parametrs->param292); //$path_imgall.$res_img['imgparam'];     
            list($res_img['imgparam_mid'],) = $psg->getGoodsImage($res_img['imgparam'], intval($section->parametrs->param286), 'w', $section->parametrs->param292); //"/lib/image.php?img={$res_img['imgparam']}&size={$section->parametrs->param286}";  
            list($res_img['imgparam_alt'],) = strip_tags($res_img['imgparam_alt']);
            if($section->parametrs->param282=='N'){
                $rezult = "<img class=\"goodsPhotoBig\" src=\"{$res_img['imgparam1']}\" border=\"0\" title=\"{$res_img['imgparam_alt']}\" alt=\"{$res_img['imgparam_alt']}\">";
            }
            if($section->parametrs->param282=='L'){
                $rezult = "<a class=\"cloud-zoom\" href=\"{$res_img['imgparam1']}\" id=\"zoom1\" rel=\"position: 'right', adjustX: 10, adjustY: 0, showTitle: false, zoomWidth: ".$section->parametrs->param284."\">
                    <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\" title=\"{$res_img['imgparam_alt']}\">
                </a>";
            }
            if($section->parametrs->param282=='Z'){
                $rezult = "<div id=\"photo\">
                    <a id=\"lightbox-foto1\" rel=\"lightbox-foto\" href=\"{$res_img['imgparam1']}\" title=\"{$res_img['imgparam_alt']}\">
                        <img class=\"goodsPhoto\" src=\"{$res_img['imgparam_mid']}\" border=\"0\" alt=\"{$res_img['imgparam_alt']}\">
                    </a>
                </div>";            
            }
        }
                                                                                 
        echo $sel . '|' . $realprice . '|' . $cnt . '|' . $oldprice . '|' . $goodsStyle .'|'. 
             $res_img['imgparam1'] .'|'. $res_img['imgparam_mid'] .'|'. $res_img['imgparam_prev'] .'|'. 
             $res_img['imgparam_alt'] .'|'. $rezult;                
        unset($plugin_amount);

?>