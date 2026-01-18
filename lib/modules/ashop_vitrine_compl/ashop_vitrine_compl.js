var ashop_vitrine_compl_execute = function(params){ 
var showDescription = function(k,l,m){$(".markDescription").each(function(){var a=$(this),b=a.parent(),c=b.find(".contentDescription");b.css("position","relative");c.css({position:"absolute","z-index":1E3}).hide();var b=a.position(),f=a.outerWidth(),a=a.outerHeight(),g=c.outerWidth(!0),h=c.outerHeight(!0),d=0,e=0;switch(k){case "l":d=b.left-g;break;case "c":d=b.left+f/2-g/2;break;case "r":d=b.left+f}switch(l){case "t":e=b.top-h;break;case "c":e=b.top+a/2-h/2;break;case "b":e=b.top+a}c.css({left:d+"px",top:e+"px"})});if("c"==m)$(".contentDescription").click(function(){return!1}),$(".markDescription").on("click",function(){var a=$(this).parent().find(".contentDescription");$(".contentDescription").stop().fadeOut(100);a.stop().fadeIn(200);return!1}),$(document).click(function(){$(".contentDescription").fadeOut(100)});else $(".content .markDescription").on({mouseenter:function(){$(this).parent().find(".contentDescription")
.stop().fadeIn(200)},mouseleave:function(){$(".contentDescription").stop().fadeOut(100)}})};

$(document).ready(function(){

var productCompare = function(){
$('.compare').off('change').change(function(){
    var id_price = $(this).data('id');
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: {'compare':id_price},
        dataType: 'json',
        success: function(data){
            if (data.compare){
                var compare = $('.compare[data-id=' + id_price + ']').attr('checked', (data.compare == 'add'));
                if (data.compare == 'add'){
                    compare.closest('.blockCompare').find('.lnkInCompare').show();
                    compare.closest('.blockCompare').find('.del-compare').show();
                    compare.closest('.blockCompare').find('.compare-stext').hide();
                }else{
                    compare.closest('.blockCompare').find('.lnkInCompare').hide();
                    compare.closest('.blockCompare').find('.del-compare').hide();
                    compare.closest('.blockCompare').find('.compare-stext').show();
                }
            }          
        }
    })    
});
}

$('.btnPreorder').on('click', function(){
    var $this = $(this),
        $block = $('.blockPreorder');
    if ($block.length > 0) {  
        $block.find('form').show();
        $block.find('.msgAccepted, .cback_error').hide();
        $block.find('input').val('');
        $block.find('[name=product]').val($this.closest('form').find('[name=addcart]').val());
        $block.find('[name=count]').val($this.closest('form').find('[name=addcartcount]').val()); 
        $.fancybox({content:$block});
    }
})

function checkPreorder() {
    var $block = $('.blockPreorder')
        $email = $block.find('[name=cb_email]'),
        $name = $block.find('[name=cb_name]'),
        pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i,
        result = true;
    $block.find('.msgAccepted, .cback_error').hide();    
    if ($email.val() == '' || !pattern.test($email.val())) {
        $email.parent().find('.cback_error').show(); 
        result = false;
    }
    if ($name.val() == '') {
        $name.parent().find('.cback_error').show();
        result = false;
    } 
    return result;
}

$('.blockPreorder').on('submit', 'form', function(e){
    e.preventDefault();
    if (checkPreorder()) {
    var data = $(this).serialize() + '&preorder=1';
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            $('.blockPreorder').find('.btnConfirm').attr('disabled', true);        
        },
        success: function(data){ 
            if (data.success) {
                $('.blockPreorder').find('form').hide();
                $('.blockPreorder').find('.msgAccepted').show();
            }
            if (data.error) {
                $('.blockPreorder').find('.msgError').html(data.error).show();
            }
        },
        complete: function(){
            $('.blockPreorder').find('.btnConfirm').removeAttr('disabled'); 
        }
    })
    }
})

$('.addcart').click(function(){
    console.log('click addcart');
})

var appendPreloader = function($obj){
    if ($obj.has('.preloader').length)
        $obj.find('.preloader').show();
    else 
        $obj.append('<span class="preloader"></span>');    
}

var hidePreloader = function($obj){
    $obj.find('.preloader').fadeOut(500, function(){
        $(this).remove();
    });   
}
var updReviews = function(action, data){
    if (data.reviews){
        if (action == 'add') {
            $(data.reviews).hide().appendTo($('.reviewsList'));
            $('.reviewItem').fadeIn(500);
        }
        else
            $('.reviewsList').hide().html(data.reviews).fadeIn(400);
    }
    $('.moreReviews').data('offset', data.offset);
    if (data.count - data.offset > 0){
        $('.moreReviews').show();
        $('.countNext').html((Math.min(parseInt(params.param321), data.count - data.offset)));
        $('.countReviews').html(data.count);
    }
    else
        $('.moreReviews').hide();    
}
var getSort = function(e, s){
    var sort = 'reviews_sort=' + e.data('sort') 
    if (e.has((s ? '.desc' : '.asc')).length)
        sort += '&asc=1'; 
    return sort;   
}
$('.moreReviews').on('click', '.buttonSend', function(e){
    var $this = $(this),
        $buttons = $(e.delegateTarget).find('.buttonSend'),
        data = 'get_reviews=1',
        upd = false;
        if ($this.is('.btnShowAll')){
            data += '&all=1';
            upd = true;
        }
        else
            data += '&offset=' + $('.moreReviews').data('offset');
        data += '&' + getSort($('.sortReviews').find('.sortField.selected'))
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: data,
        dataType: 'json',
        beforeSend: function(){
            appendPreloader($this);
            $buttons.attr('disabled', true);  
        },
        success: function(data){ 
            if (data){
                if (data.reviews) {
                    updReviews(upd ? 'upd' : 'add', data);     
                }   
            } 
        },
        complete: function(){
            hidePreloader($this);
            $buttons.attr('disabled', false); 
        }
    })
    return false; 
})

$('.sortReviews').on('click', '.sortField', function(){ 
    var $field = $(this);
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: getSort($field, 1) + '&get_reviews=1',
        dataType: 'json',
        beforeSend: function(){
            appendPreloader($('.sortReviews'));    
        },
        success: function(data){
            if (data){
                if (data.reviews) {
                    updReviews('upd', data);
                    $field.find('i').remove();
                    $field.append(data.direction).addClass('selected').siblings().removeClass('selected').find('i').remove();
                }                    
            }   
        },
        complete: function(){
            hidePreloader($('.sortReviews'));
        }
    })
    return false;
})
$('.reviews').on('click', '.linkShow, .linkCancel', function(){
    $('.addReview').stop().slideToggle(400);
    return false;    
})
$('.addReview form').submit(function(e){
    e.preventDefault();
    var $form = $(this);
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: $form.serialize(),
        dataType: 'json',
        beforeSend: function(){
            appendPreloader($form.find('.btnAdd').attr('disabled', true));    
        },
        success: function(data){
            if (data){
                if (data.success){
                    $('.addReview, .linkShow').hide(500, function(){
                        $(this).remove();
                    });
                     $('.msgSuccess').show(500);
                }
            }    
        },
        complete: function(){
            $form.find('.btnAdd').attr('disabled', false);
            hidePreloader($form);
        }
    }) 
    return false;      
})

$('.blockEditMark').on('change', 'input:radio', function(){
    var $current = $(this).parent();
    $('.blockEditMark').find('.markTitle').html($current.attr('title')); 
    $current.addClass('selectedMark').siblings().removeClass('selectedMark');
    $current.prevAll().addClass('selectedMark');    
})

$('.blockEditMark').on({'mouseenter': function(){
    $('.blockEditMark').removeClass('editMark');
    $(this).addClass('activeMark').prevAll().addClass('activeMark');  
},'mouseleave':function(){
    $('.blockEditMark').addClass('editMark');
    $('.blockEditMark').find('.markItem').removeClass('activeMark');
}}, '.markItem')

$('.reviewsList').on('click', '.reviewVoiting:not(.disabled) .lnkVote', function(){
    var $this = $(this).addClass('selected'),
        $voiting = $this.closest('.reviewVoiting').addClass('disabled');
        vote = $this.is('.likeReview') ? '1' : '-1',
        id_review = $this.closest('.reviewItem').data('id');
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: {'vote':vote, 'id':id_review},
        dataType: 'json',
        beforeSend: function(){
            appendPreloader($voiting);   
        },
        success: function(data){ 
            if (data){
                if (data.success){
                    $voiting.find('.countLikes').text(data.likes); 
                    $voiting.find('.countDislikes').text(data.dislikes);
                    var rating = data.likes - data.dislikes,
                        new_class = rating > 0 ? 'positive' : (rating < 0 ? 'negative' : '');
                    $voiting.closest('.reviewItem').find('.rateValue').removeClass('positive negative').addClass(new_class).text(rating);
                }
            }
        },
        complete: function(){  
            hidePreloader($voiting);       
        }
    })    
})

function imageCarousel() {
if (typeof ($.fn.jCarouselLite) === "function") {
    if($(window).width() > 768){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 4
        });
    }else if($(window).width() < 768 && $(window).width() > 480){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 3
        });
    }else if($(window).width() < 480 && $(window).width() > 380){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 2
        });
    }else if($(window).width() < 380){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 1
        });
    }
}
};

function isTouch() {
  try {
    document.createEvent("TouchEvent");
    return true;
  }
  catch (e) { return false; }
}

var imageZoom = function() {
if (!isTouch()) {    
if (typeof ($.fn.imagezoomsl) === "function") {
    $('.goodsLinkPhoto img').imagezoomsl({
        'zoomrange': [1,4], 
        'stepzoom':0.2
    });
}
}
};



var imageHover = function() {
var event_name = 'mouseenter';

if (isTouch()) {
	event_name = 'click';
}
$('.imageList').on(event_name, 'a', function(){
    var $this = $(this);
    if (!$this.closest('.imageItem').hasClass('activeImg')){
        
        var $photo = $this.closest('.goodsDetail').find('.goodsLinkPhoto');
        $this.closest('.imageItem').addClass('activeImg').siblings().removeClass('activeImg');
    
        $photo.find('img').stop().fadeOut(100, function(){$(this).attr({'src': $this.data('middle'), 'data-large': $this.attr('href')}).stop().fadeIn(300)});
        $photo.find('a').attr('href', $this.attr('href'));
        return false;
    }
})
};

if (typeof ($.fn.fancybox) === "function") {
    $('.imageList a').fancybox({
        helpers : {
            thumbs : {
				width : 50,
				height : 50
            }
        }
    });
    $('.goodsLinkPhoto').on('click', 'a', function(){
        $('.imageList .activeImg a').click();
        return false;
    })
}

$('.paramsSort').on('change', 'select', function(){
    $(this).closest('form').submit();    
})

$('.quickView').click(function(){
    var id_price = $(this).data('id');
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: {'quickview':id_price},
        dataType: 'json',
        beforeSend: function(){ 
            $.fancybox.showLoading();
        },
        success: function(data){  
            if (data && data.quickshow){
                $.fancybox({content:data.quickshow, width:800, autoSize: false, live:false});
                $('.quickShow .imageList a').click(function(){return false;});
                allLOad();   
            }
        },
        complete: function(){
            $.fancybox.hideLoading();
        }
    })
    return false;
})
var changeModifications = function(){
$('.modifications').off('change').on('change', 'input, select', function(){
    var $this = $(this),
        value = $this.val();
        $param = $this.closest('.itemFeature');
        $group = $this.closest('.groupFeature');
        $block_param = $this.closest('.modifications');
        $overlay = $block_param.find('.overlay').show();
        id_price = $block_param.data('goods');
    $.ajax({
        url: params.ajax_url,
        type: 'post',
        data: {'id_price':id_price,'group':$group.data('id'), 'param':$param.data('id'), 'value':value},
        dataType: 'json',
        success: function(data){
        $('.modifications[data-goods=' + id_price + ']').each(function(){
            $group = $(this).find('.groupFeature');
            var $product = $(this).closest('.goodsDetail, .productItem, .blockGoods');
            if (data.price) {
                $product.find('.newPrice').html(data.price.new);
                $product.find('.oldPrice').html(data.price.old);    
            }
            if (data.presence) {
                $product.find('.presenceValue, .hpresence .presence').html(data.presence);    
            }
            if (data.description) {
                $product.find('.goodsDetNote').html(data.description);    
            }
            if (data.count) {
                var $button = $product.find('.addcart');
                if (data.count=='0'){
                    $button.attr('disabled', true);
                    if (params.param233 == 'N')
                        $button.hide();
                }    
                else
                    $button.attr('disabled', false).show();    
            } 
            if (data.images) {
                var $images = $product.find('.morephotos .imageList');
                if ($images.length) {
                    
                    $images.find('.imageItem').hide().find('a').removeAttr('rel');
                    if (data.images.length) {
                        for (var i = 0; i < data.images.length; i++){
                            if (i in data.images) {
                                $images.find('.imageItem[data-id='+data.images[i].id+']').show().find('a').attr('rel', 'imagebox');       
                            }
                        }
                    }
                    else
                        $images.find('.imageItem:first').show().find('a').attr('rel', 'imagebox');
                    $images.find('.imageItem:visible:first a').mouseenter();
                    
                    imageCarousel();
                }
            }
            if (data.params) {
                for (var i in data.params) {
                    var param = data.params[i];
                    if (param.values){
                        $param = $group.find('.itemFeature[data-id='+i+']');
                        if (data.type=='radio') {
                            $param.find('.itemValue').hide().find('input').removeAttr('checked');
                        }
                        else {
                            $param.find('option').hide().removeAttr('selected');    
                        }
                        var $prev = null;
                        for (var j in param.values) {
                            var value = param.values[j];
                            if (data.type=='radio') {
                                var $value = $param.find('.itemValue[data-id='+j+']'); 
                            }
                            else {
                                var $value = $param.find('option[data-id='+j+']');
                            }
                            if ($value.length)
                                $prev = $value.show();
                            else {
                                if (data.type=='radio') {
                                    var prefix = $product.is('.goodsDetail') ? 's_' : '';
                                    if (!$prev) $prev = $param.find('.nameFeature');
                                    $value = $('<label class="itemValue" title="'+value.value+'" data-id="'+j+'"><input type="radio" name="feature['+prefix+id_price+'_'+$group.data('id')+'_'+i+']" value="'+j+'"><span class="featureValue">'+value.value+'</span></label>').insertAfter($prev);
                                    if (param.type == 'colorlist') {
                                        if (value.image)
                                            $value.find('.featureValue').replaceWith('<img class="featureValue" src="' + value.image + '">');
                                        else
                                            $value.find('.featureValue').css('background-color', '#'+value.color).html('<span></span>');
                                    }   
                                }
                                else{
                                    $value = $('<option title="'+value.value+'" data-id="'+j+'" value="'+j+'">'+value.value+'</option>')
                                    if (!$prev){ 
                                        $value.prependTo($param.find('select'));
                                    }
                                    else {   
                                        $value.insertAfter($prev);
                                    }
                                }       
                            }
                                
                            if (j == param.selected) {
                                if (data.type=='radio') {
                                    $value.find('input').attr('checked', true);
                                }
                                else{
                                    $value.attr('selected', true);
                                }
                            }    
           
                        }
                    }
                        
                }
            }
        });
        },
        complete: function(){
            $overlay.fadeOut(200);    
        }
    });
});
}
var allLOad = function(){
    imageHover();
    imageZoom();
    productCompare();
    imageCarousel();
    changeModifications();
    showDescription(params.param307, params.param308, params.param309);
};allLOad();

if ($('.goodsDetail .tabs').length){
    var $titles = $('.goodsDetail .titleHead'),
        $tabs = $('.goodsDetail .tabs').show(),
        $tabs_nav = $tabs.find('.tabsNav'),
        $tabs_content = $tabs.find('.tabsContent');    
    $titles.each(function(){
        $tabs_nav.append('<li class="itemTab" id="' + ($(this).attr('id') ? $(this).attr('id') : '') + '">' + $(this).html() + '</li>');
        $tabs_content.append($(this).next());
        $(this).remove();
    }) 
    $('.tabs .tabsNav .itemTab').click(function(e) {
        e.preventDefault();
        var $this = $(this);
        if (!$this.hasClass('activeTab')) {
            $this.addClass('activeTab').siblings().removeClass('activeTab');
            $tabs_content.find('.content').hide().eq($this.index()).fadeIn(400);
        }
    }); 
    var hash = location.hash;
    var $default_tab = $tabs_nav.find(hash);
    if (!$default_tab.length)
        $default_tab = $tabs_nav.find('.itemTab').first();
    $default_tab.click();  
}

$('.anchor').click(function(){
    var hash = $(this).attr('href');
    if (hash){
        var $anchor = $(hash);
        if ($anchor.length) {
            $('body,html').animate({
                scrollTop: $anchor.offset().top
            }, 400);
            if ($anchor.is('.itemTab'))
                $anchor.click();
        }  
    }
    return false;
});

$(document).hover(function(event){
    if (!$(event.target).is('.click-payment') && !$(event.target).is('.payment-section')){
        $('.payment-section').hide();
    }
    if (!$(event.target).is('.click-delivery') && !$(event.target).is('.delivery-section')){
        $('.delivery-section').hide();
    }
});                               
    
$('.click-payment').hover(function(){
    var payment = $(this);
    var note = payment.closest('.list-payment-box').find('.payment-section');
    var x = payment.position();
    $('.delivery-section').hide();        
    if ($('.payment-section').is(':hidden')) {
        $('.payment-section').css({'position': 'absolute', 'left' : x.left, 'top': x.top + payment.outerHeight(true) + 12}).fadeIn(150);
    }else{
        $('.payment-section').hide();   
    }
    return false;
});

$('.click-delivery').hover(function(){
    var payment = $(this);
    var note = payment.closest('.list-delivery-box').find('.delivery-section');
    var x = payment.position();
    $('.payment-section').hide();        
    if ($('.delivery-section').is(':hidden')) {
        $('.delivery-section').css({'position': 'absolute', 'left' : x.left, 'top': x.top + payment.outerHeight(true) + 12}).fadeIn(150);
    }else{
        $('.delivery-section').hide();   
    }
    return false;
});

})

if ($('.product-options').length) {
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ' ' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k).toFixed(prec);
            };
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
    $('.product-options').on('change input', 'select, input', function(){
        $('.goodsDetPriceBox').find('.option-item').remove();
        var product_price = parseFloat($('meta[itemprop=price]').attr('content')),
            option_price = 0;
        if ($(this).is('.value-item-input')) {
            $(this).closest('.option-value-item').find(':checkbox, :radio').first().attr('checked', true)
        }
        $('.product-options').find('option:selected, input:checked').closest('.option-item').each(function(){
            var option = $(this)
                value = [],
                image = '';
                
            var add_option = $('<div class="option-item"> <img class="option-item-image"> <span class="option-item-name"></span> <span class="option-item-value"></span></div>').appendTo('.goodsDetPriceBox .block-options');
            add_option.find('.option-item-name').html(option.find('h4').html());
            
            if (option.find('.option-item-image').length) {
                image = option.find('.option-item-image').attr('src');
            }
            
            if (option.find('select').length) {
                value.push(option.find('option:selected').text());
                var price = parseFloat(option.find('option:selected').data('price'));
                option_price += option.data('type') == 1 ? product_price * price : price;
            }
            else {
                option.find('input:checked').closest('.option-value-item').each(function(){
                    value.push($(this).find('.value-item-name').text());
                    if ($(this).find('.value-item-image').length) {
                        image = $(this).find('.value-item-image').attr('src');
                    }
                    var price = parseFloat($(this).find('input').data('price'));
                    
                    if ($(this).find('.value-item-input').length > 0)
                        price *= Math.max(1, parseFloat($(this).find('.value-item-input').val()));
                    
                    option_price += option.data('type') == 1 ? product_price * price : price;
                })
            }
            
            if (image)
                add_option.find('.option-item-image').attr('src', image);
            
            
            add_option.find('.option-item-value').html(value.join(', '));
        });
        product_price += option_price;
        $('.goodsDetPriceBox .newPrice').html(number_format(product_price) + ' <span class="fMoneyFlang rubl">руб.</span>')
    })
    $('.product-options').find('select, input').first().change();
}


}

$(window).resize(function(){
if (typeof ($.fn.jCarouselLite) === "function") {
    if($(window).width() > 768){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 4
        });
    }else if($(window).width() < 768 && $(window).width() > 480){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 3
        });
    }else if($(window).width() < 480 && $(window).width() > 380){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 2
        });
    }else if($(window).width() < 380){
        $('.photoSlider').jCarouselLite({
            btnNext: '.next',
            btnPrev: '.prev',
            circular: false,
            mouseWheel: true,
            visible: 1
        });
    }
}
});

function slideItem () {
 if($(window).width() > 991 ) {
 $('.ashopvit_compl .vitrina .productItem').hover(function(){
   var mm = $('.goodsGoods.vitrina > div').length - $(this).index();
   $(this).css('z-index',mm);
   clearTimeout($.data(this,'timer'));
   $(this).children('.product-item-block').animate({'height':'auto'},300);
   $('.object-brand-article', this).slideDown(300);
   //$('.objectTitle', this).animate({'height':'100%'},300);
   $('.addCount', this).slideDown(300);
   $('.modifications .groupFeature', this).slideDown(300);
   $('.bottom-box', this).slideDown(300);
 },function(){
 var mm = ($('.goodsGoods.vitrina > div').length - $(this).index());
 $(this).css('z-index',mm);
 $.data(this,'timer', setTimeout($.proxy(function() {
  var thiss = this;
  $('.object-brand-article', this).slideUp(300);
  //$('.objectTitle', this).animate({'height':'50px'},300);
  $('.addCount', this).slideUp(300);
  $('.modifications .groupFeature', this).slideUp(300);
  $('.bottom-box', this).slideUp(300);
 }, this), 300));
 });
 }
}

$('.colorFeature .itemValue ').each(function(){
    var colors = $('.featureValue',this).css('background-color');
    var arr_colors = colors.replace("rgb(",'').replace(")",'').split(',');
    if(arr_colors[0] > 239 && arr_colors[1] > 239 && arr_colors[2] > 239) {
        $('.featureValue',this).addClass('featureValueWhite');
        if($('>input',this).is(':checked')){
            $('>input',this).closest('.itemValue').addClass('selected');
        }
    }
});

$('.itemValue>input').click(function(){
    $('.itemValue.selected').removeClass('selected');
    $(this).closest('.itemValue').addClass('selected');
});

function resizeImg(class_name, parent_name) {

         var thiss = class_name;
         var pname = parent_name;

         $(thiss).each(function(){
            var p_width = parseInt($(this).parent().parent(pname).css('width'));
            var p_height = parseInt($(this).parent().parent(pname).css('height'));
            var pw_height = p_width + 20;
            //$(this).css('max-height',p_height);
            $(this).parent().parent(pname).css('height',pw_height);
         });

}

function resizeImgProduct(class_name) {
        var thiss = class_name;
        $(thiss).each(function(){
            var min_h = $(this).parent().parent().parent('.product-item-block').css('height');
            $(this).parent().parent().parent().parent('.productItem').css('min-height',min_h);
        });
}

function sizeHeightBlock(class_name, block_name){
    var nm = class_name;
    var bn = block_name;
    var mh = 0;
    $(bn).each(function () {
       var h_block = parseInt($(this).height());
       if(h_block > mh) {
          mh = h_block;
       };
    });
    $(nm).height(mh);
}

function resizeProductItem() {
             
    if($('.ashopvit_compl').width() > 720 && $('.ashopvit_compl').width() < 941){
        $('.ashopvit_compl .vitrina .productItem').addClass('item-column-3');   
    }else if($('.ashopvit_compl').width() < 720 && $('.ashopvit_compl').width() > 480){
        $('.ashopvit_compl .vitrina .productItem').addClass('item-column-2');
    }else if($('.ashopvit_compl').width() < 480){
        $('.ashopvit_compl .vitrina .productItem').addClass('item-column-1');
    }
    
}
